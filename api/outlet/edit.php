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
$alamat = $payload["alamat"] ?? NULL;
$nohp = $payload["nohp"] ?? NULL;

if (!@$id || !@$nama || !@$alamat || !@$nohp) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT id FROM tb_outlet WHERE id = '$id'";
$outlet = query($sql);

if (empty($outlet)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak ditemukan"
    ]));
}

$sql = "UPDATE tb_outlet SET nama = '$nama', alamat = '$alamat', tlp = '$nohp' WHERE id = '$id'";
query($sql);

logger("UPDATE OUTLET", "({$_SESSION['auth']->user['nama']}) mengubah data id_outlet($id)");
exit(json_encode([
    "status" => "ok",
]));