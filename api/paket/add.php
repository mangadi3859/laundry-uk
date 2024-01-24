<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    exit(json_encode([
        "message" => "Method Not Allowed"
    ]));
}

if (!Auth::isAuthenticated()) {
    http_response_code(401);
    exit(json_encode([
        "message" => "Unauthenticated"
    ]));
}

if (!isPermited([Privilege::$ADMIN])) {
    http_response_code(403);
    exit(json_encode([
        "message" => "Forbidden"
    ]));
}

$payload = json_decode(file_get_contents("php://input"), true);
$nama = $payload["nama"] ?? NULL;
$jenis = $payload["jenis"] ?? NULL;
$idOutlet = $payload["outlet"] ?? NULL;
$harga = $payload["harga"] ?? NULL;

if (!@$nama || !@$jenis || !@$idOutlet || !@$harga) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

if (is_nan($harga)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Harga tidak valid"
    ]));
}

if (!in_array($jenis, [PaketType::$BED_COVER, PaketType::$KAOS, PaketType::$KILOAN, PaketType::$LAIN, PaketType::$SELIMUT])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Jenis paket tidak valid"
    ]));
}

$harga = (int) $harga;
if ($harga < 1000) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Harga tidak boleh dibawah 1000 rupiah"
    ]));
}

$sql = "SELECT id FROM tb_outlet WHERE id = '$idOutlet'";
$outletData = query($sql);

if (empty($outletData)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak terdaftar"
    ]));
}

$sql = "INSERT INTO tb_paket VALUE ('', '$idOutlet', '$jenis', '$nama', '$harga')";
query($sql);

logger("INSERT PAKET", "({$_SESSION['auth']->user['nama']}) just created a new record");
exit(json_encode([
    "status" => "ok",
]));