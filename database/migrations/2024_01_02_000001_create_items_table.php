<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->string('kategori'); // tenda, sleeping_bag, carrier, dll
            $table->decimal('harga_sewa', 12, 2); // harga per hari
            $table->integer('jumlah_stok')->default(0);
            $table->string('foto')->nullable();
            $table->enum('status', ['tersedia', 'maintenance'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
