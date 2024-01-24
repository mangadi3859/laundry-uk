<?php

$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";

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
$idOutlet = $payload["outlet"] ?? NULL;

if (!@$id || !@$idOutlet) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT * FROM tb_paket WHERE id = '$id' AND id_outlet = '$idOutlet'";
$paket = query($sql);

if (empty($paket)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data paket tidak ditemukan"
    ]));
}

exit(json_encode([
    "status" => "ok",
    "data" => $paket[0]
]));