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
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl_kirim');
            $table->dateTime('tgl_tiba');
            $table->enum('status_kirim', ['Sedang Dikirim', 'Tiba Ditujuan'])->default('Sedang Dikirim');
            $table->string('bukti_foto', 255);
            $table->foreignId('id_pesan')->constrained('pemesanans')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};
