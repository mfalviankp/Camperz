<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========= USER: ADMIN & USER DEMO =========
        DB::table('users')->insert([
            [
                'nama' => 'Admin Camperz',
                'email' => 'admin@camperz.id',
                'password' => bcrypt('admin123'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Camping Indah No. 1, Jakarta',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'user@camperz.id',
                'password' => bcrypt('user123'),
                'no_hp' => '089876543210',
                'alamat' => 'Jl. Petualang No. 7, Bandung',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => bcrypt('user123'),
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Hijau No. 15, Bogor',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========= ITEM ALAT CAMPING =========
        $items = [
            ['nama_item' => 'Tenda Dome 2 Orang', 'deskripsi' => 'Tenda dome ringan, tahan angin dan hujan, cocok untuk 2 orang. Sudah termasuk pasak dan tali.', 'kategori' => 'tenda', 'harga_sewa' => 75000, 'jumlah_stok' => 5],
            ['nama_item' => 'Tenda Dome 4 Orang', 'deskripsi' => 'Tenda keluarga kapasitas 4 orang, double layer, ventilasi baik.', 'kategori' => 'tenda', 'harga_sewa' => 120000, 'jumlah_stok' => 3],
            ['nama_item' => 'Sleeping Bag Polar -5°C', 'deskripsi' => 'Sleeping bag bahan polar nyaman untuk suhu dingin hingga -5 derajat Celsius.', 'kategori' => 'sleeping_bag', 'harga_sewa' => 35000, 'jumlah_stok' => 10],
            ['nama_item' => 'Sleeping Bag Standar', 'deskripsi' => 'Sleeping bag standar cocok untuk dataran rendah, ringan dan mudah dilipat.', 'kategori' => 'sleeping_bag', 'harga_sewa' => 20000, 'jumlah_stok' => 8],
            ['nama_item' => 'Carrier 60L Osprey', 'deskripsi' => 'Carrier 60L dengan frame aluminium, nyaman untuk pendakian panjang.', 'kategori' => 'carrier', 'harga_sewa' => 55000, 'jumlah_stok' => 6],
            ['nama_item' => 'Carrier 40L Daypack', 'deskripsi' => 'Daypack 40L ringan untuk pendakian harian atau short trip.', 'kategori' => 'carrier', 'harga_sewa' => 35000, 'jumlah_stok' => 8],
            ['nama_item' => 'Kompor Portable Gas', 'deskripsi' => 'Kompor gas portabel 1 tungku, efisien dan mudah dibawa.', 'kategori' => 'kompor', 'harga_sewa' => 25000, 'jumlah_stok' => 12],
            ['nama_item' => 'Kompor Trangia Alkohol', 'deskripsi' => 'Kompor alkohol sistem tertutup, sangat ringan untuk ultralight camping.', 'kategori' => 'kompor', 'harga_sewa' => 30000, 'jumlah_stok' => 5],
            ['nama_item' => 'Matras Camping EVA', 'deskripsi' => 'Matras busa EVA 180x60cm, ringan dan tahan lama.', 'kategori' => 'matras', 'harga_sewa' => 15000, 'jumlah_stok' => 15],
            ['nama_item' => 'Matras Inflatable', 'deskripsi' => 'Matras tiup premium, kenyamanan maksimal untuk tidur di alam.', 'kategori' => 'matras', 'harga_sewa' => 40000, 'jumlah_stok' => 7],
            ['nama_item' => 'Senter Headlamp LED', 'deskripsi' => 'Headlamp 200 lumen, waterproof, baterai tahan 8 jam.', 'kategori' => 'senter', 'harga_sewa' => 15000, 'jumlah_stok' => 20],
            ['nama_item' => 'Trekking Pole Aluminium', 'deskripsi' => 'Tiang trekking aluminium sepasang, dapat dilipat tiga bagian.', 'kategori' => 'lainnya', 'harga_sewa' => 25000, 'jumlah_stok' => 10],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert(array_merge($item, [
                'status' => 'tersedia',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
