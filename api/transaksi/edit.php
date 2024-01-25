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
$member = $payload["member"] ?? NULL;
$tgl = $payload["tgl"] ?? NULL;
$deadline = $payload["deadline"] ?? NULL;
$user = $payload["user"] ?? NULL;
$extra = ((int) $payload["extra"]) ?? NULL;

if (!@$id || !@$member || !@$tgl || !@$deadline || !@$user || !@$extra) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

if (is_nan($extra)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Harga tidak valid"
    ]));
}

$sql = "SELECT id FROM tb_member WHERE nama = '$member'";
$mem = query($sql);

if (empty($mem)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Member tidak terdaftar"
    ]));
}

$sql = "SELECT id FROM tb_user WHERE id = '$user'";
$kasir = query($sql);

if (empty($kasir)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "User tidak terdaftar"
    ]));
}

$sql = "UPDATE tb_transaksi SET id_member = '{$mem[0]['id']}', tgl = '$tgl', batas_waktu = '$deadline', id_user = '{$kasir[0]['id']}', biaya_tambahan = '$extra'  WHERE id = '$id'";
query($sql);

logger("UPDATE TRANSAKSI", "({$_SESSION['auth']->user['nama']}) just modified a record with id ($id)");
exit(json_encode([
    "status" => "ok",
]));