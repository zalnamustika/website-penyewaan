<?php

namespace App\Http\Controllers;

use App\Mail\OrderAccepted;
use App\Mail\OrderPaid;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{

    public function show()
    {
        // Memeriksa apakah pengguna telah login
        if (!Auth::check()) {
            // Mengarahkan pengguna ke halaman login jika belum login
            return redirect()->route('login')->with('message', 'Anda harus login untuk melihat reservasi Anda.');
        }

        // Mengambil data pembayaran yang terkait dengan pengguna yang sedang login
        $payments = Payment::where('user_id', Auth::id())->get();

        // Menghitung total pembayaran
        $total = $payments->count();

        // Mengambil data reservasi dan riwayat untuk pengguna yang sedang login
        $reservasi = Payment::where('user_id', Auth::id())
            ->where('status', '!=', 4)
            ->orderBy('id', 'DESC')
            ->get();

        $riwayat = Payment::where('user_id', Auth::id())
            ->where('status', 4)
            ->orderBy('id', 'DESC')
            ->get();

        return view('member.reservasi', [
            'reservasi' => $reservasi,
            'riwayat' => $riwayat,
            'total' => $total // Jika Anda memerlukan total pembayaran di view
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
        // Mengecek apakah pengguna terautentikasi
        if (!Auth::check()) {
            // Redirect ke halaman login jika belum login
            return redirect()->route('login')->with('message', 'Anda harus login untuk melanjutkan checkout.');
        }

        // Mengambil item di keranjang pengguna
        $cart = Carts::where('user_id', Auth::id())->get();

        // Membuat entri pembayaran baru
        $pembayaran = new Payment();
        $pembayaran->no_invoice = Auth::id() . "/" . Carbon::now()->timestamp;
        $pembayaran->user_id = Auth::id();
        $pembayaran->total = $cart->sum('harga');
        $pembayaran->save();

        // Mengambil tanggal dan waktu mulai dari request
        $startDate = $request->input('start_date');
        $startTime = $request->input('start_time');

        foreach ($cart as $c) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime, 'Asia/Jakarta');
            $endDateTime = $startDateTime->copy()->addDays($c->durasi);

            // Membuat entri pesanan baru
            Order::create([
                'product_id' => $c->product_id,
                'user_id' => $c->user_id,
                'payment_id' => $pembayaran->id,
                'durasi' => $c->durasi,
                'starts' => $startDateTime->toDateTimeString(),
                'ends' => $endDateTime->toDateTimeString(),
                'harga' => $c->harga,
            ]);

            // Menghapus item dari keranjang setelah pesanan dibuat
            $c->delete();
        }

        // Redirect ke halaman pemesanan setelah checkout
        return redirect(route('order.show'));
    }

    public function destroy($id)
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

        $payment->delete();

        return redirect(route('order.show'));
    }

    public function acc(Request $request, $paymentId)
    {
        // Pastikan $paymentId valid
        $payment = Payment::findOrFail($paymentId);

        // Ambil semua order dari request
        $orders = $request->input('order', []); // Default ke array kosong jika tidak ada

        // Update status orders
        foreach ($orders as $orderId) {
            Order::where('id', $orderId)->update(['status' => 2]); // Status ACC
        }

        // Update status payment dan orders terkait
        $payment->update(['status' => 2]); // Status ACC

        // Update status order yang belum di-ACC menjadi ditolak
        Order::where('payment_id', $paymentId)->where('status', 1)->update(['status' => 4]); // Status Ditolak

        // Hitung total harga dari order yang sudah di-ACC
        $totalAcc = Order::where('payment_id', $paymentId)->where('status', 2)->sum('harga');
        $payment->update(['total' => $totalAcc]);

        // Kirim email
        try {
            Mail::to($payment->user->email)->send(new OrderAccepted($payment));

            // Jika email terkirim, tampilkan pesan sukses
            return back()->with('success', 'Email berhasil dikirim.');
        } catch (\Exception $e) {
            // Tampilkan pesan error untuk debugging
            return back()->withErrors(['mail' => $e->getMessage()]);
        }
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

        try {
            Mail::to($payment->user->email)->send(new OrderPaid($payment));

            // Jika email terkirim, tampilkan pesan sukses
            return back()->with('success', 'Email berhasil dikirim.');
        } catch (\Exception $e) {
            // Tampilkan pesan error untuk debugging
            return back()->withErrors(['mail' => $e->getMessage()]);
        }
    }

    public function addComment(Request $request, $orderId)
    {
        dd($request->all());
        // Validasi input komentar
        $request->validate([
            'komentar' => 'required|string|max:255',
        ]);

        // Temukan order berdasarkan ID
        $order = Order::find($orderId);
        if ($order) {
            // Perbarui komentar untuk order yang ditemukan
            $order->komentar = $request->input('komentar');
            $order->save();

            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Order tidak ditemukan');
    }




    public function produkkembali($id)
    {
        // // Ambil semua ID yang dikirim melalui request (asumsikan dalam bentuk array)
        // $ids = $request->input('id');

        // // Loop melalui setiap ID untuk mengubah statusnya
        // foreach ($ids as $id) {
        //     // Temukan entri Order berdasarkan ID
        //     $order = Order::where('id', $id)->first();

        //     if ($order) {
        //         // Update status order menjadi 'Sudah Kembali'
        //         $order->update(['status_pengembalian' => 'sudah_kembali']);

        //         // Cek apakah semua produk dalam pesanan ini sudah dikembalikan
        //         $allReturned = Order::where('payment_id', $order->payment_id)
        //             ->where('status_pengembalian', '!=', 'sudah_kembali')
        //             ->doesntExist();

        //         if ($allReturned) {
        //             // Jika semua produk sudah dikembalikan, update status pembayaran menjadi 'Selesai'
        //             $payment = Payment::find($order->payment_id);
        //             $payment->update(['status' => 4]);
        //         }
        //     }
        // }


        // return response()->json(['success' => true]);

        Payment::find($id)->update([
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
            ->get()
            ->map(function ($order) {
                // Menghitung jumlah hari keterlambatan
                $now = Carbon::now();
                $ends = Carbon::parse($order->ends);
                $order->daysLate = $now->diffInDays($ends);
                return $order;
            });

        // Mengirim data ke view
        return view('admin.penyewaan.terlambat', compact('orders'));
    }

    public function cetak(Request $request)
    {
        $validatedData = $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date'
        ]);

        $dari = Carbon::parse($validatedData['dari']);
        $sampai = Carbon::parse($validatedData['sampai']);

        // Mengatur locale Carbon ke bahasa Indonesia
        Carbon::setLocale('id');

        // Mendapatkan laporan dengan grouping by no_invoice dan menyertakan nama penyewa
        $laporan = Order::with(['payment', 'product', 'user'])
            ->whereBetween('created_at', [$dari, $sampai])
            ->where('status', 4)
            ->whereHas('payment', function ($query) {
                $query->where('status', '=', 4);
            })
            ->get()
            ->groupBy(function ($order) {
                return $order->payment->no_invoice . ' - ' . $order->user->name;
            });

        $total = $laporan->sum(function ($orders) {
            return $orders->sum('harga');
        });

        // Mengubah format tanggal "dari" dan "sampai" ke dalam bahasa Indonesia untuk ditampilkan di PDF
        $dariFormatted = $dari->translatedFormat('l, j F Y');
        $sampaiFormatted = $sampai->translatedFormat('l, j F Y');

        // Load view untuk PDF
        $pdf = PDF::loadView('admin.laporan', compact('laporan', 'total', 'dariFormatted', 'sampaiFormatted'));

        // Mengembalikan PDF sebagai download
        if ($request->has('download')) {
            return $pdf->download('laporan.pdf');
        }

        // Menampilkan PDF di browser
        return $pdf->stream('laporan.pdf');
    }
}
