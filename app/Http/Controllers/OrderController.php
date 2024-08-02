<?php

namespace App\Http\Controllers;

use App\Mail\OrderAccepted;
use App\Mail\OrderPaid;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // public function show()
    // {
    //     $reservasi = Payment::with('order')->where('user_id', Auth::id())
    //         ->where('status', '!=', 4)
    //         ->orderBy('id', 'DESC')
    //         ->get();
    //     $riwayat = Payment::with('order')->where('user_id', Auth::id())
    //         ->where('status', 4)
    //         ->orderBy('id', 'DESC')
    //         ->get();

    //     return view('member.reservasi', compact('reservasi', 'riwayat'));
    // }

    public function show()
    {
        $payment = Payment::with(['user', 'order'])->where('user_id', Auth::id());
        return view('member.reservasi', [
            'reservasi' => $payment->where('status', '!=', 4)->orderBy('id', 'DESC')->get(),
            'riwayat' => Payment::with(['user', 'order'])->where('user_id', Auth::id())->where('status', 4)->orderBy('id', 'DESC')->get()
        ]);
    }

    public function detail($id)
    {
        $detail = Order::where('payment_id', $id)->get();
        $payment = Payment::find($id);

        if ($payment->user_id == Auth::id()) {
            return view('member.detailreservasi', [
                'paymentss' => $payment,
                'detail' => $detail,
                'total' => $payment->total,
                'paymentId' => $payment->id,
                'paymentStatus' => $detail->first()->payment->status,
                'bukti' => $payment->bukti
            ]);
        } else {
            return abort(403, "Forbidden");
        }
    }

    public function create(Request $request)
    {
        $cart = Carts::where('user_id', Auth::id())->get();
        $pembayaran = new Payment();

        $pembayaran->no_invoice = Auth::id() . "/" . Carbon::now()->timestamp;
        $pembayaran->user_id = Auth::id();
        $pembayaran->total = $cart->sum('harga');
        $pembayaran->save();

        foreach ($cart as $c) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request['start_date'] . ' ' . $request['start_time'], 'Asia/Jakarta');
            $endDateTime = $startDateTime->copy()->addDays($c->durasi);
            Order::create([
                'product_id' => $c->product_id,
                'user_id' => $c->user_id,
                'payment_id' => Payment::where('user_id', Auth::id())->orderBy('id', 'desc')->first()->id,
                'durasi' => $c->durasi,
                'starts' => $startDateTime->format('Y-m-d H:i', strtotime($request['start_date'] . $request['start_time'])),
                'ends' => $endDateTime->format('Y-m-d H:i', strtotime($request['start_date'] . ' ' . $request['start_time'] . " +" . $c->durasi . " days")),
                'harga' => $c->harga,
            ]);
            $c->delete();
        }

        return redirect(route('order.show'));
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        $payment->delete();

        return redirect(route('order.show'));
    }

    public function acc(Request $request, $paymentId)
    {
        $orders = $request->order ?? [];
        $payment = new Payment();

        foreach ($orders as $o) {
            Order::where('id', $o)->update(['status' => 2]);
        }
        $payment->find($paymentId)->update(['status' => 2]);
        Order::where('payment_id', $paymentId)->where('status', 1)->update(['status' => 3]);
        $payment->where('id', $paymentId)->update(['total' => Order::where('payment_id', $paymentId)->where('status', 2)->sum('harga')]);

        Mail::to($payment->find($paymentId)->user->email)->send(new OrderAccepted($payment->find($paymentId)));
        return back();
    }

    public function bayar(Request $request, $id)
    {
        $this->validate($request, [
            'bukti' => "image|mimes:png,jpg,svg,jpeg,gif|max:5000"
        ]);

        $payment = Payment::find($id);
        if ($request->hasFile('bukti')) {
            $gambar = $request->file('bukti');
            $filename = time() . '-' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/evidence'), $filename);
        }
        $payment->update([
            'bukti' => $filename
        ]);

        return back();
    }

    public function ditolakbayar(Request $request)
    {
        Log::info('Received request to reject payment:', ['id' => $request->id]);

        // Temukan entri Payment berdasarkan ID
        $payment = Payment::find($request->id);

        if (!$payment) {
            Log::warning('Payment not found:', ['id' => $request->id]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Dapatkan semua Orders terkait dengan entri Payment tersebut
        $orders = Order::where('payment_id', $payment->id)->get();

        foreach ($orders as $order) {
            // Temukan produk terkait dengan entri Order
            $product = Product::find($order->product_id);

            if ($product) {
                // Kembalikan jumlah stok produk
                $product->stok += $order->quantity;
                $product->save();
            } else {
                Log::warning('Product not found for order:', ['order_id' => $order->id, 'product_id' => $order->product_id]);
            }
        }

        // Perbarui status Payment
        $payment->update(['status' => 5]);
        Log::info('Payment status updated:', ['id' => $payment->id, 'status' => $payment->status]);

        return response()->json(['message' => 'Status updated successfully'], 200);
    }


    public function accbayar($id)
    {
        $payment = Payment::find($id);

        $payment->update([
            'status' => 3
        ]);

        Mail::to($payment->user->email)->send(new OrderPaid($payment));
        return back();
    }

    public function produkkembali($id)
    {
        $payment = Payment::findOrFail($id);

        // Dapatkan semua Orders terkait dengan entri Payment tersebut
        $orders = Order::where('payment_id', $payment->id)->get();

        foreach ($orders as $order) {
            // Temukan produk terkait dengan entri Order
            $product = Product::find($order->product_id);

            if ($product) {
                // Kembalikan jumlah stok produk
                $product->stok += $order->quantity;
                $product->save();
            }
        }

        // Hapus entri Payment
        $payment->update([
            'status' => 4
        ]);

        return back();
    }

    public function terlambat()
    {
        // Mengambil data orders yang terlambat dikembalikan
        $orders = Order::with(['product', 'user', 'payment'])
        ->where('status', 2) // Asumsi status 2 berarti produk dipinjam
        ->where('ends', '<', Carbon::now())
        ->get();

        // Mengirim data ke view
        return view('admin.penyewaan.terlambat', compact('orders'));
    }

    public function cetak(Request $request)
    {
        $validatedData = $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date'
        ]);

        $dari = $validatedData['dari'];
        $sampai = $validatedData['sampai'];

        $laporan = Order::with(['payment', 'product', 'user'])
            ->whereBetween('created_at', [$dari, $sampai])
            ->where('status', 2)
            ->whereHas('payment', function ($query) {
                $query->where('status', '>', 2);
            })
            ->get(['*', 'created_at AS tanggal']);

        $total = $laporan->sum('harga');

        return view('admin.laporan', compact('laporan', 'total'));
    }
}
