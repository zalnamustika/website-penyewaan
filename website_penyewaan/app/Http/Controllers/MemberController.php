<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Carts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $product = Product::with(['category'])->get();
        $carts = Carts::where('user_id', '=', Auth::id());

        if (request('search')) {
            $key = request('search');
            $product =  Product::with(['category'])->where('nama_produk', 'LIKE', '%' . $key . '%')->get();
        }

        if (request('kategori')) {
            $product = Product::with(['category'])->where('kategori_id', '=', request('kategori'))->get();
        }

        return view('member.member', [
            'products' => $product,
            'carts' => $carts->get(),
            'total' => $carts->sum('harga'),
            'kategori' => Category::all()
        ]);
    }
}
