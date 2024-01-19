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
$alamat = $payload["alamat"] ?? NULL;
$nohp = $payload["nohp"] ?? NULL;

if (!@$nama || !@$alamat || !@$nohp) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "INSERT INTO tb_outlet VALUE ('', '$nama', '$alamat', '$nohp' )";
query($sql);

logger("INSERT OUTLET", "({$_SESSION['auth']->user['nama']}) menambahkan data outlet");
exit(json_encode([
    "status" => "ok",
]));