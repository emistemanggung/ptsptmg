<?php
require __DIR__ . '/check_permohonan.php';
if (php_sapi_name() !== 'cli') { echo "Run from CLI\n"; exit(1); }
$identifier = $argv[1] ?? null;
check_permohonan($identifier);
