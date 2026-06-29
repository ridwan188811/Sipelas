<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surats')->onDelete('cascade');
            $table->string('nama_usaha')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->string('lama_usaha')->nullable();
            $table->string('dokumen_surat_pengantar_rt_rw')->nullable();
            $table->string('dokumen_fotokopi_ktp_pemohon')->nullable();
            $table->string('dokumen_fotokopi_keluarga_kk')->nullable();
            $table->string('dokumen_foto_tempat_usaha')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('detail_skus'); }
};
