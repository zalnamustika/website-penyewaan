<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_produk', 'deskripsi', 'kategori_id', 'harga1h', 'harga3h', 'harga7h', 'ukuran', 'jumlah', 'gambar'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function order() {
        return $this->hasMany(Order::class,'product_id','id');
    }
}
