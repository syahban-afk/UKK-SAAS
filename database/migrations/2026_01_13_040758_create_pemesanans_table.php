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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained();
            $table->foreignId('id_jenis_bayar')->constrained();
            $table->string('no_resi', 30)->unique();
            $table->date('tgl_pesan');
            $table->enum('status_pesan', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai'])->default('Menunggu Konfirmasi');
            $table->integer('total_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
