<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_domisilis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surats')->onDelete('cascade');
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('dokumen_surat_pengantar_rt_rw')->nullable();
            $table->string('dokumen_fotokopi_ktp_pemohon')->nullable();
            $table->string('dokumen_fotokopi_keluarga_kk')->nullable();
            $table->string('dokumen_surat_pernyataan_domisili___pemohon')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('detail_domisilis'); }
};
