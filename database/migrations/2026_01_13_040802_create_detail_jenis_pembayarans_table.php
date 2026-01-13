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
        Schema::create('detail_jenis_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_pembayaran')->constrained();
            $table->string('no_rek', 25);
            $table->string('tempat_bayar', 50);
            $table->string('logo', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jenis_pembayarans');
    }
};
