<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $topUser = User::withCount('payment')
            ->orderBy('payment_count', 'DESC')
            ->limit(5)
            ->get();

        $topProducts = Product::withCount('order')
            ->orderBy('order_count', 'DESC')
            ->limit(10)
            ->get();

        $late = Order::with(['product', 'user', 'payment'])
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

        // Ambil data order yang belum diacc oleh admin 
        $belumAcc = Order::with(['product', 'user'])
            ->where('status', 1)
            ->where('status', '!=', 5)
            ->orderByRaw('DATEDIFF(NOW(), ends) DESC') // Urutkan berdasarkan keterlambatan terlama
            ->get()
            ->map(function ($order) {
                // Menghitung jumlah hari keterlambatan
                $now = Carbon::now();
                $ends = Carbon::parse($order->ends);
                $order->daysLate = $now->diffInDays($ends);

                // Tentukan kelas warna berdasarkan keterlambatan
                if ($order->daysLate == 1) {
                    $order->colorClass = 'table-success'; // Hijau untuk keterlambatan 1 hari
                } elseif ($order->daysLate >= 2 && $order->daysLate <= 3) {
                    $order->colorClass = 'table-warning'; // Kuning untuk keterlambatan 2-3 hari
                } else {
                    $order->colorClass = 'table-danger'; // Merah untuk keterlambatan lebih dari 3 hari
                }

                return $order;
            });

        $belumBayar = Order::whereHas('payment') // Memastikan ada bukti pembayaran
            ->where('status', '<>', [3]) // Status yang diinginkan
            ->get();

        return view('admin.admin', [
            'loggedUsername' => Auth::user()->name,
            'total_user' => User::where('role', 0)->count(),
            'total_product' => Product::count(),
            'total_kategori' => Category::count(),
            'total_penyewaan' => Payment::count(),
            'top_user' => $topUser,
            'top_products' => $topProducts,
            'orders' => $late,
            'belumAcc' => $belumAcc,
            'belumBayar' => $belumBayar,
        ]);
    }

    public function usermanagement()
    {

        $user = User::with(['payment'])->get();

        return view('admin.user.user', [
            'penyewa' => $user->where('role', 0)
        ]);
    }

    public function adminmanagement()
    {
        $user = User::with(['payment'])->get();

        return view('admin.user.admin_management', [
            'admin' => $user->where('role', 1),
            'user' => $user->where('role', 0)
        ]);
    }

    public function newUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:255',
            'telepon' => 'required|max:15'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        $request->session()->flash('registrasi', 'Registrasi Berhasil, Silakan login untuk mulai menyewa');
        return redirect(route('admin.user'));
    }

    public function newOrderIndex($userId)
    {
        $user = User::find($userId);
        $product = Product::with(['category'])->get();
        $cart = Carts::with(['user'])->where('user_id', $userId)->get();

        return view('admin.penyewaan.reservasibaru', [
            'user' => $user,
            'product' => $product,
            'cart' => $cart,
            'total' => $cart->sum('harga')
        ]);
    }

    public function createNewOrder(Request $request, $userId)
    {
        $cart = Carts::where('user_id', $userId)->get();
        $pembayaran = new Payment();

        $pembayaran->no_invoice = $userId . "/" . Carbon::now()->timestamp;
        $pembayaran->user_id = $userId;
        $pembayaran->status = 3;
        $pembayaran->total = $cart->sum('harga');
        $pembayaran->save();

        foreach ($cart as $c) {
            Order::create([
                'product_id' => $c->product_id,
                'user_id' => $c->user_id,
                'payment_id' => Payment::where('user_id', $userId)->orderBy('id', 'desc')->first()->id,
                'durasi' => $c->durasi,
                'starts' => date('Y-m-d H:i', strtotime($request['start_date'] . $request['start_time'])),
                'ends' => date('Y-m-d H:i', strtotime($request['start_date'] . $request['start_time'] . "+" . $c->durasi . " hours")),
                'harga' => $c->harga,
                'status' => 2
            ]);
            $c->delete();
        }

        return redirect(route('penyewaan.index'));
    }
}
