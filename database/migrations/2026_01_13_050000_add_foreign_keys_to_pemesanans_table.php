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
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->foreign('id_pelanggan')
                ->references('id')->on('pelanggans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('id_jenis_bayar')
                ->references('id')->on('jenis_pembayarans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropForeign(['id_pelanggan']);
            $table->dropForeign(['id_jenis_bayar']);
        });
    }
};
