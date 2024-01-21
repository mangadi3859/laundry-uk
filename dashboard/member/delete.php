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

if (!@$id) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT id FROM tb_transaksi WHERE id_member = '$id'";
$transaksi = query($sql);
$filter = implode(", ", array_map(function ($v) {
    return $v["id"];
}, $transaksi));


try {
    $begin = $conn->begin_transaction();
    if (sizeof($transaksi)) {
        query("DELETE FROM tb_detail_transaksi WHERE id_transaksi IN ($filter);");
        query("DELETE FROM tb_transaksi WHERE id_member = '$id'");
    }

    query("DELETE FROM tb_member WHERE id = '$id';");
    $conn->commit();
    logger("DELETE MEMBER", "({$_SESSION['auth']->user['nama']}) just deleted a record with the id ($id)");
    exit(json_encode([
        "status" => "ok",
    ]));
} catch (Exception $err) {
    $conn->rollback();
    exit(json_encode([
        "status" => "failed",
        "message" => $err,
    ]));
}



