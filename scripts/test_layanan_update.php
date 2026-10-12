<?php
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the framework enough to use Eloquent model autoloading
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Layanan;
use App\Models\Permohonan;

$layanan = Layanan::first();
if (!$layanan) {
    $layanan = Layanan::create(['nama_layanan' => 'TEST', 'deskripsi' => 'test', 'kode_layanan' => 'TEST']);
    echo "Created test layanan id={$layanan->id}\n";
}

// Find or create a permohonan for this layanan and set link_drive_rekom
$perm = Permohonan::where('layanan_id', $layanan->id)->orderBy('id', 'desc')->first();
if (!$perm) {
    echo "No permohonan found for layanan id={$layanan->id}. Create one via admin UI before running this test.\n";
    exit(1);
}

try {
    $perm->update(['link_drive_rekom' => 'https://drive.google.com/file/d/REKOM_TEST_ID/view']);
    echo "Permohonan updated successfully.\n";
} catch (\Throwable $e) {
    echo "Error updating permohonan: " . $e->getMessage() . "\n";
}
