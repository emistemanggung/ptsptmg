<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8mb4','root','');

// Select layanan info and latest permohonan.link_drive_rekom (if any)
$sql = <<<'SQL'
select
	l.id,
	l.nama_layanan,
	(
		select p.link_drive_rekom
		from permohonan p
		where p.layanan_id = l.id and p.link_drive_rekom is not null
		order by p.id desc
		limit 1
	) as link_drive_rekom
from layanan l
where l.id = ?
SQL;

$stmt=$pdo->prepare($sql);
$stmt->execute([62]);
$r=$stmt->fetch(PDO::FETCH_ASSOC);
var_export($r);
