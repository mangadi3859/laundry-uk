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
$id = $payload["id"] ?? NULL;
$nama = $payload["nama"] ?? NULL;
$jenis = $payload["jenis"] ?? NULL;
$idOutlet = $payload["outlet"] ?? NULL;
$harga = $payload["harga"] ?? NULL;

if (!@$id || !@$nama || !@$jenis || !@$idOutlet || !@$harga) {
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

$sql = "SELECT id FROM tb_paket WHERE id = '$id'";
$paket = query($sql);

if (empty($paket)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Paket tidak terdaftar"
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

$sql = "UPDATE tb_paket SET id_outlet = '$idOutlet', jenis = '$jenis', nama_paket = '$nama', harga = '$harga' WHERE id = '$id'";
query($sql);

logger("UPDATE PAKET", "({$_SESSION['auth']->user['nama']}) just modified a record with id ($id)");
exit(json_encode([
    "status" => "ok",
]));