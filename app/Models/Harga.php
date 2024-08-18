<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'product_id',
        'harga',
        'hari',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
