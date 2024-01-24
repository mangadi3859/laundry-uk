<?php

$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";

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

$payload = json_decode(file_get_contents("php://input"), true);
$status = $payload["status"] ?? NULL;
$id = $payload["id"] ?? NULL;

if (!@$status || !@$id) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

if (!in_array($status, ["belum_dibayar", "dibayar"])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak valid"
    ]));
}

$sql = "SELECT id, `dibayar` FROM tb_transaksi WHERE id = '$id'";
$transaksi = query($sql);

if (empty($transaksi)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data transaksi tidak ditemukan"
    ]));
}
$tgl_byr = $status != "dibayar" ? ", tgl_bayar = NULL" : ", tgl_bayar = CURRENT_TIME";
$sql = "UPDATE tb_transaksi SET dibayar = '$status' $tgl_byr WHERE id = '$id'";
query($sql);
logger("UPDATE TRANSAKSI", "({$_SESSION["auth"]->user["nama"]}) just modified payment status ({$transaksi[0]['dibayar']} => $status) with id ($id)");

exit(json_encode([
    "status" => "ok",
]));