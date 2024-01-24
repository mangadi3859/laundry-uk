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

if (!in_array($status, ["baru", "proses", "diambil", "selesai"])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Status tidak valid"
    ]));
}

$sql = "SELECT id, `status` FROM tb_transaksi WHERE id = '$id'";
$transaksi = query($sql);

if (empty($transaksi)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data transaksi tidak ditemukan"
    ]));
}

$sql = "UPDATE tb_transaksi SET status = '$status' WHERE id = '$id'";
query($sql);
logger("UPDATE TRANSAKSI", "({$_SESSION["auth"]->user["nama"]}) just modified status ({$transaksi[0]['status']} => $status) with id ($id)");

exit(json_encode([
    "status" => "ok",
]));