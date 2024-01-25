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

// if (!isPermited([Privilege::$ADMIN, Privilege::$KASIR, Privilege::$OWNER])) {
//     http_response_code(403);
//     exit(json_encode([
//         "message" => "Forbidden"
//     ]));
// }

$payload = json_decode(file_get_contents("php://input"), true);
$id = $payload["id"] ?? NULL;

if (!@$id) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

try {
    $conn->begin_transaction();
    $sql = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = '$id'";
    query($sql);

    $sql = "DELETE FROM tb_transaksi WHERE id = '$id'";
    query($sql);

    logger("DELETE TRANSAKSI", "({$_SESSION['auth']->user['nama']}) just deleted a record with the id ($id)");
    $conn->commit();
    exit(json_encode([
        "status" => "ok",
    ]));
} catch (Exception $err) {
    $conn->rollback();
    exit(json_encode([
        "status" => "failed",
        "message" => $err->getMessage(),
    ]));
}