<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function store(Request $request, $id, $userId)
    {
        // Tentukan apakah user terautentikasi atau tidak
        $userId = $userId !== 'guest' ? $userId : null;

        // Find the item
        $product = Product::find($id);

        // Check if the item exists
        if (!$product) {
            return back()->with('error', 'Item tidak ditemukan');
        }

        // Check stock
        $stokBarang = $product->stok;
        if ($stokBarang < 1) {
            return back()->with('error', 'Stok sudah habis');
        }

        // Get the selected harga_id
        $hargaId = $request->input('harga_id');

        // Find the harga entry
        $hargaEntry = \App\Models\Harga::find($hargaId);
        if (!$hargaEntry) {
            return back()->with('error', 'Harga tidak ditemukan');
        }

        // Check if the item with the same harga_id is already in the cart
        $cart = Carts::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->where('harga_id', $hargaId)
            ->first();

        if ($cart) {
            // Update quantity if item is already in the cart with the same harga_id
            $cart->jumlah += 1;
        } else {
            // Create a new cart entry if item is not in the cart with the same harga_id
            $cart = new Carts();
            $cart->harga_id = $hargaId;
            $cart->user_id = $userId;
            $cart->product_id = $product->id;
            $cart->harga = $hargaEntry->harga;
            $cart->jumlah = 1; // Initial quantity
            $cart->durasi = $hargaEntry->hari; // Set durasi from harga entry
        }

        // Update the stock quantity
        $product->update([
            'stok' => $stokBarang - 1,
        ]);

        // Save the cart item
        $cart->save();

        return back()->with('success', 'Berhasil ditambahkan ke keranjang');
    }




    public function clearCartAfterTimeout(Request $request)
    {
        $cartItemId = $request->input('id');
        $cartItem = Carts::find($cartItemId);

        if ($cartItem) {
            // Kembalikan stok produk
            $product = Product::find($cartItem->product_id);
            $product->stok += $cartItem->jumlah;
            $product->save();

            // Hapus item keranjang
            $cartItem->delete();

            return response()->json(['success' => 'Cart item removed and stock updated.']);
        }

        return response()->json(['error' => 'Cart item not found.'], 404);
    }

    public function destroy($id)
    {
        // Find the cart item
        $cartItem = Carts::find($id);

        if ($cartItem) {
            // Find the associated product
            $product = Product::find($cartItem->product_id);

            if ($product) {
                // Restore the product stock
                $product->stok += $cartItem->jumlah;
                $product->save();
            }

            // Delete the cart item
            $cartItem->delete();
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang dan stok dikembalikan');
    }
}
