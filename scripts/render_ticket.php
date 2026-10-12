<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ticket = $argv[1] ?? 'TKT-YWISWCWPRXL6T';
$perm = App\Models\Permohonan::where('no_tiket', $ticket)->first();
if (!$perm) {
    echo "NOT FOUND\n";
    exit(1);
}

// Render view
echo view('guest.show_ticket', ['permohonan' => $perm])->render();
