<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8mb4','root','');

// List latest 5 layanan and show latest permohonan.link_drive_rekom per layanan
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
order by l.id desc
limit 5
SQL;

foreach($pdo->query($sql) as $r){
        printf('%d | %s | %s\n', $r['id'], $r['nama_layanan'], $r['link_drive_rekom'] ?? '');
}
