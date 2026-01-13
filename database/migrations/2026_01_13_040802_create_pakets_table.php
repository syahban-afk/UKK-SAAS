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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 500);
            $table->enum('jenis_paket', ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat']);
            $table->integer('jumlah_pax', 11);
            $table->integer('harga_paket', 11);
            $table->string('deskripsi');
            $table->string('foto1', 255);
            $table->string('foto2', 255);
            $table->string('foto3', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
