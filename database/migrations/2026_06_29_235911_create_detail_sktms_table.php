<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_sktms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surats')->onDelete('cascade');
            $table->string('keperluan')->nullable();
            $table->string('nama_anak')->nullable(); // for sktm-sekolah
            $table->string('tempat_tanggal_lahir_anak')->nullable(); // for sktm-sekolah
            $table->string('sekolah_tujuan')->nullable(); // for sktm-sekolah
            $table->string('dokumen_surat_pengantar_rt_rw')->nullable();
            $table->string('dokumen_fotokopi_ktp_pemohon')->nullable();
            $table->string('dokumen_fotokopi_keluarga_kk')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('detail_sktms'); }
};
