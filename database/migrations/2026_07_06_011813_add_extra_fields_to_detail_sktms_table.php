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
        Schema::table('detail_sktms', function (Blueprint $table) {
            $table->string('nama_yang_menggunakan_surat')->nullable();
            $table->string('hubungan_dengan_pemohon')->nullable();
            $table->text('keterangan_tambahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sktms', function (Blueprint $table) {
            $table->dropColumn(['nama_yang_menggunakan_surat', 'hubungan_dengan_pemohon', 'keterangan_tambahan']);
        });
    }
};
