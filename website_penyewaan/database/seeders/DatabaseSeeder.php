<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'SuperAdmin',
                'email' => 'superadmin@test.com',
                'password' => Hash::make('superadmin'),
                'role' => 2
            ],
            [
                'name' => 'Admin',
                'email' => 'Admin@test.com',
                'password' => Hash::make('admin'),
                'role' => 1
            ],
            [
                'name' => 'Zalna Mustika',
                'email' => 'zalnamustika@test.com',
                'password' => Hash::make('zalnamustika'),
                'role' => 0
            ]
        ]);

        DB::table('categories')->insert([
            [
                'nama_kategori' => 'Baju Adat'
            ],
            [
                'nama_kategori' => 'Baju Profesi'
            ],
            [
                'nama_kategori' => 'Perhiasan'
            ],
            [
                'nama_kategori' => 'Baju Pesta'
            ]
        ]);

        DB::table('products')->insert([
            [
                'kategori_id' => '1',
                'nama_produk' => 'Baju Koto Gadang',
                'harga1h' => '70000',
                'harga3h' => '130000',
                'harga7h' => '200000',
                'ukuran' => 'All size',
                'jumlah' => '2',
                'gambar' => 'bajuadat.jpg',
                'deskripsi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima facere, distinctio eum ullam quos, exercitationem, a est optio rerum accusantium culpa. Iure eaque architecto magnam minus iusto culpa ipsa, sint eveniet ratione dolore. Aut esse similique sapiente? Veniam vero voluptate laboriosam tenetur dolorem odit ullam, dolor similique placeat quisquam atque?'
            ],
            [
                'kategori_id' => '2',
                'nama_produk' => 'Baju Songket',
                'harga1h' => '80000',
                'harga3h' => '120000',
                'harga7h' => '300000',
                'ukuran' => 'L',
                'jumlah' => '1',
                'gambar' => 'bajuadat.jpg',
                'deskripsi' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis iste suscipit recusandae minus perferendis doloremque! Eligendi consectetur iusto vitae atque, aliquam pariatur minus nemo cumque? Facere enim tempora ea eius!'
            ],
            [
                'kategori_id' => '3',
                'nama_produk' => 'Baju Anak Daro',
                'harga1h' => '90000',
                'harga3h' => '140000',
                'harga7h' => '400000',
                'ukuran' => 'all size',
                'jumlah' => '3',
                'gambar' => 'bajuadat.jpg',
                'deskripsi' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laboriosam non iusto dignissimos quia ab iure nobis aliquid corporis, quaerat totam quo modi accusantium officia illum vero odio accusamus ipsum tempora ullam optio. Quasi ipsa adipisci veniam illo amet placeat est.'
            ],
            [
                'kategori_id' => '4',
                'nama_produk' => 'Suntiang biasa',
                'harga1h' => '80000',
                'harga3h' => '140000',
                'harga7h' => '500000',
                'ukuran' => 'm',
                'jumlah' => '2',
                'gambar' => 'bajuadat.jpg',
                'deskripsi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum blanditiis soluta pariatur recusandae iusto nobis iste placeat quibusdam deleniti odio quasi cum qui delectus nulla corporis, vel temporibus reprehenderit. Commodi eligendi sint alias molestias architecto?'
            ],
        ]);
    }
    
}
