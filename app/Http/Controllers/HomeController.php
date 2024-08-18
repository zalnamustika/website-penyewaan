<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['category'])->get();
        if (request('search')) {
            $key = request('search');
            $products =  Product::with(['category'])->where('nama_produk', 'LIKE', '%' . $key . '%')->get();
        }
        if (request('kategori')) {
            $products = Product::with(['category'])->where('kategori_id', '=', request('kategori'))->get();
        }

        return view('home', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }

    public function detail($id)
    {
        $detail = Product::with(['category'])->find($id);

        return view('detail', [
            'detail' => $detail,
            'order' => Order::where('product_id', $id)->where('status', 2)->orderBy('starts', 'DESC')->get()
        ]);
    }
}
