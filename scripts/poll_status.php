<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Permohonan;

$ticket = $argv[1] ?? 'TKT-YWISWCWPRXL6T';
$perm = Permohonan::where('no_tiket', $ticket)->first();
if (!$perm) {
    echo json_encode(['success' => false, 'message' => 'Nomor tiket tidak ditemukan.']) . PHP_EOL;
    exit(1);
}

$noTiket = $perm->no_tiket;
$linkDriveRekom = $perm->link_drive_rekom ?? null;
$statusPtsp = $perm->status_ptsp ?? null;
$ptspLastError = $perm->ptsp_last_error ?? null;
$selesai = !empty($perm->link_drive_rekom) || $statusPtsp === 'gagal_kirim';

echo json_encode([
    'no_tiket' => $noTiket,
    'link_drive_rekom' => $linkDriveRekom,
    'status_ptsp' => $statusPtsp,
    'ptsp_last_error' => $ptspLastError,
    'selesai' => $selesai,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
