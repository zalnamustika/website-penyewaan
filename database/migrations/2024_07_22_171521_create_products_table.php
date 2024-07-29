<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('nama_produk');
            $table->integer('harga1h');
            $table->integer('harga3h');
            $table->integer('harga7h');
            $table->string('ukuran');
            $table->string('jumlah');
            $table->string('gambar')->default('noimage.jpg');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
