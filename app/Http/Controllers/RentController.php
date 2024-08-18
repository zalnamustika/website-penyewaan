<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;

class RentController extends Controller
{
    public function index() {
        return view('admin.penyewaan.penyewaan',[
            'penyewaan' => Payment::with(['user','order'])->where('status', '!=', 4)->orderBy('id','DESC')->get(),
        ]);
    }

    public function detail($id) {
        $detail = Order::with(['user', 'payment', 'product'])->where('payment_id', $id)->get();
        $payment = Payment::find($id);
    
        return view('admin.penyewaan.detail',[
            'detail' => $detail,
            'total' => $payment->total,
            'status' => $payment->status,
        ]);
    }
    
    
    public function destroy($id) {
        // Temukan entri Payment berdasarkan ID
        $payment = Payment::findOrFail($id);
    
        // Dapatkan semua Orders terkait dengan entri Payment tersebut
        $orders = Order::where('payment_id', $payment->id)->get();
    
        foreach($orders as $order) {
            // Temukan produk terkait dengan entri Order
            $product = Product::find($order->product_id);
            
            if ($product) {
                // Kembalikan jumlah stok produk
                $product->stok += $order->quantity;
                $product->save();
            }
        }
    
        // Hapus entri Payment
        $payment->delete();
    
        // Redirect ke halaman penyewaan
        return redirect()->route('penyewaan.index')->with('success', 'Payment dan related orders berhasil dihapus.');
    }    

    public function riwayat() {
        return view('admin.penyewaan.riwayat',[
            'penyewaan' => Payment::where('status', 4)->get()
        ]);
    }
}
