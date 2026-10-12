<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8mb4','root','');
$stmt=$pdo->prepare('select id from layanan where id=?');
$id=62;
$stmt->execute([$id]);
$r=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) { echo "layanan id=$id not found\n"; exit(1); }

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Permohonan;

// Update the latest permohonan for this layanan with a test link
$perm = Permohonan::where('layanan_id', $id)->orderBy('id', 'desc')->first();
if (!$perm) {
	echo "No permohonan found for layanan id=$id; create one first or use admin UI.\n";
	exit(1);
}

$perm->link_drive_rekom = 'https://drive.google.com/file/d/REKOM_TEST_ID/view';
$perm->save();

echo "Updated permohonan id={$perm->id} for layanan id=$id\n";
