<?php

namespace App\Http\Controllers;

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
            'stok' => 'required',
            'gambar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $product = new Product();
        $product->nama_produk = $request['nama'];
        $product->deskripsi = $request['deskripsi'];
        $product->kategori_id = $request['kategori'];
        $product->stok = $request['stok'];

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
            'stok' => 'required',
            'gambar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $product = Product::find($id);
        $product->nama_produk = $request['nama'];
        $product->deskripsi = $request['deskripsi'];
        $product->kategori_id = $request['kategori'];
        $product->stok = $request['stok'];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '-' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images'), $filename);
            $product->gambar = $filename;
        }

        $product->save();
        return redirect(route('product.index'))->with('message', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product->gambar != 'noimage.jpg') {
            $filepath = public_path('images') . '/' . $product->gambar;
            unlink($filepath);
        }

        // Agar 'total' dalam Payment berkurang jika produk dihapus
        $payment = new Payment();
        $order = Order::where('product_id', $id)->get();
        foreach ($order as $o) {
            $payment->where('id', $o->payment_id)->decrement('total', $o->harga);
        }

        $product->delete();
        return redirect(route('product.index'))->with('message', 'Produk berhasil dihapus!');
    }
}
