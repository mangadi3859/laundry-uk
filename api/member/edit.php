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
$id = $payload["id"] ?? NULL;
$nama = $payload["nama"] ?? NULL;
$alamat = $payload["alamat"] ?? NULL;
$gender = $payload["gender"] ?? NULL;
$nohp = $payload["nohp"] ?? NULL;

if (!@$id || !@$nama || !@$alamat || !@$gender || !@$nohp) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT id FROM tb_member WHERE id = '$id'";
$idOutlet = query($sql);

if (empty($idOutlet)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Member tidak ditemukan"
    ]));
}

if (!in_array($gender, ["L", "P"])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Gender tidak valid"
    ]));
}

$sql = "UPDATE tb_member SET nama = '$nama', alamat = '$alamat', jenis_kelamin = '$gender', tlp = '$nohp' WHERE id = '$id'";
query($sql);

logger("UPDATE MEMBER", "({$_SESSION['auth']->user['nama']}) just modified a record with id ($id)");
exit(json_encode([
    "status" => "ok",
]));