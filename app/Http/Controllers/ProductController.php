<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index($id = null)
    {

        if ($id != null) {
            $products = Product::where('kategori_id', '=', $id)->get();
        } else {
            $products = Product::with(['category'])->get();
        }

        if (request('search')) {
            $key = request('search');
            $products = Product::with(['category'])->where('nama_produk', 'LIKE', '%' . $key . '%')->get();
        }

        return view('admin.product.product', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }

    public function edit($id)
    {
        $product = Product::with(['category'])->find($id);

        return view('admin.product.editproduct', [
            'product' => $product,
            'kategori' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'kategori' => 'required',
            'harga1h' => 'required|numeric',
            'harga3h' => 'required|numeric',
            'harga7h' => 'required|numeric',
            'gambar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $product = new Product();
        $product->nama_produk = $request['nama'];
        $product->deskripsi = $request['deskripsi'];
        $product->kategori_id = $request['kategori'];
        $product->harga1h = $request['harga1h'];
        $product->harga3h = $request['harga3h'];
        $product->harga7h = $request['harga7h'];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '-' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        return redirect(route('product.index'))->with('message', 'Produk berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'kategori' => 'required',
            'harga1h' => 'required|numeric',
            'harga3h' => 'required|numeric',
            'harga7h' => 'required|numeric',
            'gambar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $product = Product::find($id);
        $product->nama_produk = $request['nama'];
        $product->deskripsi = $request['deskripsi'];
        $product->kategori_id = $request['kategori'];
        $product->harga1h = $request['harga1h'];
        $product->harga3h = $request['harga3h'];
        $product->harga7h = $request['harga7h'];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '-' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        // Agar harga pada cart mengikuti saat harga produk di-update oleh Admin
        $cart = new Carts();
        $cart->where('product_id', $id)->where('durasi', 1)->update(['harga' => $product->harga1h]);
        $cart->where('product_id', $id)->where('durasi', 3)->update(['harga' => $product->harga3h]);
        $cart->where('product_id', $id)->where('durasi', 7)->update(['harga' => $product->harga7h]);


        return redirect(route('product.index'))->with('message', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product->gambar != 'noimage.jpg') {
            $filepath = public_path('images') . '/' . $product->gambar;
            unlink($filepath);
        }

        // Agar 'total' dalam Payment berkurang jika alat dihapus
        $payment = new Payment();
        $order = Order::where('product_id', $id)->get();
        foreach ($order as $o) {
            $payment->where('id', $o->payment_id)->decrement('total', $o->harga);
        }

        $product->delete();
        return redirect(route('product.index'))->with('message', 'Produk berhasil dihapus!');
    }
}
