<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasTable('surat_templates')) {
        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat')->unique();
            $table->longText('content');
            $table->timestamps();
        });
        echo "Tabel surat_templates berhasil dibuat.";
    } else {
        echo "Tabel surat_templates sudah ada.";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
