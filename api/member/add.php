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

if (!isPermited([Privilege::$ADMIN, Privilege::$KASIR])) {
    http_response_code(403);
    exit(json_encode([
        "message" => "Forbidden"
    ]));
}

$payload = json_decode(file_get_contents("php://input"), true);
$nama = $payload["nama"] ?? NULL;
$alamat = $payload["alamat"] ?? NULL;
$gender = $payload["gender"] ?? NULL;
$nohp = $payload["nohp"] ?? NULL;

if (!@$nama || !@$alamat || !@$gender || !@$nohp) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$nama = strtolower($nama);

if (!in_array($gender, ["L", "P"])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Gender tidak valid"
    ]));
}

$sql = "SELECT id FROM tb_member WHERE nama = '$nama'";
if (sizeof(query($sql))) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Nama sudah terdaftar"
    ]));
}

$token = Auth::generateToken(10);
$sql = "INSERT INTO tb_member VALUE ('', '$nama', '$alamat', '$gender', '$nohp', '$token')";
query($sql);

logger("INSERT MEMBER", "({$_SESSION['auth']->user['nama']}) just created a new record");
exit(json_encode([
    "status" => "ok",
]));
