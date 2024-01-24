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

if (!@$id) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT id FROM tb_member WHERE id = '$id' = '$id'";
$member = query($sql);

if (empty($paket)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Member tidak ditemukan"
    ]));
}

$sql = "SELECT COUNT(*) AS CT FROM tb_transaksi WHERE id_member = '$id' AND dibayar = 'dibayar'";
$ct = query($sql)[0];

exit(json_encode([
    "status" => "ok",
    "data" => calculateDiscount((int) ($ct["CT"]))
]));