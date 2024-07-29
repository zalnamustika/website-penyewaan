<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    public function showAllAlat() {
        if(request('category')) {
            $query = request('category');
            $filtered = DB::table('products')
                ->join('categories', 'categories.id','products.kategori_id')
                ->where('kategori_id', $query)
                ->get(['products.id','kategori_id','nama_produk','harga','ukuran','gambar','deskripsi','nama_kategori']);

            if($filtered->isNotEmpty()) {
                return response()->json([
                    'message' => 'success',
                    'data' => $filtered
                ], 200, ['Content-Type' => 'application/json']);
            } else {
                return response()->json([
                    'message' => 'NOT FOUND',
                    'data' => []
                ], 404, ['Content-Type' => 'application/json']);
            }
        } else {
            $alat = DB::table('alats')
                ->join('categories', 'categories.id','alats.kategori_id')
                ->get(['products.id','kategori_id','nama_produk','harga','ukuran','gambar','deskripsi','nama_kategori']);

            return response()->json([
                'message' => 'success',
                'data' => $alat
            ],200, ['Content-Type' => 'application/json']);
        }
    }

    public function showAllCategory() {
        return response()->json([
            'message' => 'success',
            'data' => Category::all(['id','nama_kategori'])
        ]);
    }

    public function detail($id) {
        $product = Product::find($id, ['id', 'kategori_id','nama_produk','harga','ukuran','gambar','deskripsi','nama_kategori']);

        $booked = DB::table('orders')
            ->join('products', 'products.id','=','orders.product_id')
            ->join('payments','payments.id','=','orders.payment_id')
            ->where('products.id', $id)
            ->where('orders.status', 2)
            ->where('payments.status', 3)
            ->get(['starts AS start','ends AS end','durasi']);


        return response()->json([
            "message" => "success",
            "data" => $product,
            "booked" => $booked
        ], 200);
    }
}
