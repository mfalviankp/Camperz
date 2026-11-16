<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->decimal('total_harga', 12, 2);
            $table->decimal('biaya_layanan', 12, 2)->default(0);
            $table->enum('status', [
                'belum_bayar',
                'menunggu_pengambilan',
                'aktif',
                'menunggu_inspeksi',
                'menunggu_pelunasan_denda',
                'selesai',
                'dibatalkan'
            ])->default('belum_bayar');
            $table->string('qr_pickup')->nullable();
            $table->string('qr_return')->nullable();
            $table->timestamp('tanggal_pengambilan_aktual')->nullable();
            $table->timestamp('tanggal_pengembalian_aktual')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
