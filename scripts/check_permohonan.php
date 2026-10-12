<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Permohonan;

function check_permohonan($identifier)
{
    if (empty($identifier)) {
        echo "Usage: php check_permohonan.php <ticket|id|id_layanan_asal>\n";
        exit(1);
    }

    $query = Permohonan::query();

    // try exact ticket match
    $result = Permohonan::where('no_tiket', $identifier)->get();

    if ($result->isEmpty()) {
        // try numeric id or id_layanan_asal
        if (is_numeric($identifier)) {
            $result = Permohonan::where('id', (int)$identifier)
                ->orWhere('id_layanan_asal', (string)$identifier)
                ->get();
        }
    }

    if ($result->isEmpty()) {
        // last attempt: search by partial match on no_tiket
        $result = Permohonan::where('no_tiket', 'like', "%$identifier%")
            ->orWhere('sumber', 'like', "%$identifier%")
            ->get();
    }

    if ($result->isEmpty()) {
        echo "NOT FOUND\n";
        exit(1);
    }

    echo json_encode($result->map(function ($r) { return $r->toArray(); })->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}

// If executed directly from CLI, run with $argv[1]
if (php_sapi_name() === 'cli' && isset($argv) && basename($argv[0]) === basename(__FILE__)) {
    $identifier = $argv[1] ?? null;
    check_permohonan($identifier);
}
