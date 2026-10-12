<?php
$host = '127.0.0.1';
$db   = 'laravel';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// If an argument is provided, treat it as no_tiket to filter.
$ticket = $argv[1] ?? null;

if ($ticket) {
    $stmt = $pdo->prepare('SELECT id, no_tiket, sumber, id_layanan_asal, tipe_layanan_asal, link_drive_rekom, status, created_at FROM permohonan WHERE no_tiket = ? LIMIT 1');
    $stmt->execute([$ticket]);
    $rows = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare('SELECT id, no_tiket, sumber, id_layanan_asal, tipe_layanan_asal, link_drive_rekom, status, created_at FROM permohonan WHERE sumber = ? ORDER BY id DESC LIMIT 10');
    $stmt->execute(['layanan_madrasah']);
    $rows = $stmt->fetchAll();
}

if (!$rows) {
    echo "No rows found.\n";
    exit(0);
}

// Print nicely
$fmt = "%-5s %-22s %-18s %-12s %-18s %-60s %-12s %s\n";
printf($fmt, 'ID', 'NO_TIKET', 'SUMBER', 'ID_LAYANAN', 'TIPE_LAYANAN', 'LINK_DRIVE_REKOM', 'STATUS', 'CREATED_AT');
foreach ($rows as $r) {
    $link = $r['link_drive_rekom'] ?? '';
    if (strlen($link) > 58) $link = substr($link,0,55) . '...';
    printf($fmt,
        $r['id'], $r['no_tiket'], $r['sumber'], $r['id_layanan_asal'] ?? '', $r['tipe_layanan_asal'] ?? '', $link, $r['status'] ?? '', $r['created_at'] ?? ''
    );
}
