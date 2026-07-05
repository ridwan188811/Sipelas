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
            $table->string('nama_lengkap')->nullable();
            $table->string('nik')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
        });

        Schema::table('detail_skus', function (Blueprint $table) {
            $table->string('bentuk_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->string('barang_usaha')->nullable();
            $table->string('keadaan_usaha_saat_ini')->nullable();
            $table->string('sejak_tahun')->nullable();
            $table->string('keperluan')->nullable();
            $table->text('keterangan_tambahan')->nullable();
        });

        Schema::table('detail_domisilis', function (Blueprint $table) {
            $table->text('alamat_domisili')->nullable();
            $table->string('rt_domisili')->nullable();
            $table->string('rw_domisili')->nullable();
            $table->string('kelurahan_domisili')->nullable();
            $table->string('kecamatan_domisili')->nullable();
            $table->string('kota_domisili')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sktms', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'alamat_lengkap',
                'rt', 'rw', 'kelurahan', 'kecamatan', 'kota'
            ]);
        });

        Schema::table('detail_skus', function (Blueprint $table) {
            $table->dropColumn([
                'bentuk_usaha', 'bidang_usaha', 'barang_usaha', 'keadaan_usaha_saat_ini',
                'sejak_tahun', 'keperluan', 'keterangan_tambahan'
            ]);
        });

        Schema::table('detail_domisilis', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_domisili', 'rt_domisili', 'rw_domisili', 'kelurahan_domisili',
                'kecamatan_domisili', 'kota_domisili'
            ]);
        });
    }
};
