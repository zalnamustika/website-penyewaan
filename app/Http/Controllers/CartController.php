<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, $id, $userId)
    {

        $cart = new Carts();
        $product = Product::find($id);


        if ($request['btn'] == '24') {
            $harga = $product->harga1h;
        } elseif ($request['btn'] == '72') {
            $harga = $product->harga3h;
        } elseif ($request['btn'] == '168') {
            $harga = $product->harga7h;
        }

        $cart->user_id = $userId;
        $cart->product_id = $product->id;
        $cart->harga = $harga;
        $cart->durasi = $request['btn'];

        $cart->save();

        return back()->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    public function destroy($id)
    {
        $product = Carts::find($id);
        $product->delete();

        return back();
    }
}
