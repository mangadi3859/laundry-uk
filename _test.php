<?php
$_SAFE = true;
require_once "conn.php";
require_once "functions.php";

$paket = query("SELECT * FROM `tb_paket` WHERE id_outlet = 1");

$outlet = 4;
$data = implode(",<br/>", array_map(function ($v) {
    global $outlet;
    return "('', '$outlet', '{$v['jenis']}', '{$v['nama_paket']}', '{$v['harga']}')";
}, $paket));
echo <<<qr
INSERT INTO tb_paket VALUES <br/>
$data
qr;

// session_destroy();
// echo password_hash("default_pas", PASSWORD_BCRYPT);