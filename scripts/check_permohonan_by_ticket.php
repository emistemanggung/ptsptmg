<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8mb4','root','');
$stmt=$pdo->prepare('select id, layanan_id, link_drive_rekom from permohonan where no_tiket=?');
$stmt->execute(['TKT-UKDJUTOBJ3KY4']);
$r=$stmt->fetch(PDO::FETCH_ASSOC);
var_export($r);
