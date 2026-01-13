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
        Schema::table('detail_pemesanans', function (Blueprint $table) {
            $table->foreign('id_paket')
                ->references('id')->on('pakets')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pemesanans', function (Blueprint $table) {
            $table->dropForeign(['id_paket']);
        });
    }
};
