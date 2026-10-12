<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$ticket = $argv[1] ?? 'TKT-COSNOYQBPMDLN';
$p = App\Models\Permohonan::where('no_tiket', $ticket)->first();
if (!$p) {
    echo "NOT FOUND\n";
    exit(1);
}
echo json_encode($p->toArray(), JSON_PRETTY_PRINT) . "\n";
