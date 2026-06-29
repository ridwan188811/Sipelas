<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->string('nomor_surat')->unique()->nullable();
            $table->string('jenis_surat');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->boolean('is_read_by_user')->default(false);
            $table->boolean('is_read_by_admin')->default(false);
            $table->boolean('is_verified_by_lurah')->default(false);
            $table->string('token_validasi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengajuan_surats'); }
};
