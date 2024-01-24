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

if (!@$id) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$sql = "SELECT 
CASE 
    WHEN tb_detail_transaksi.id IS NOT NULL THEN 1
    ELSE 0
END AS is_used
FROM 
tb_paket
LEFT JOIN tb_detail_transaksi ON tb_detail_transaksi.id_paket = tb_paket.id
GROUP BY 
tb_paket.id";

$idOutlet = query($sql);

if (!empty($idOutlet) && $idOutlet[0]["is_used"] != 0) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Paket tidak bisa dihapus karena paket ini sudah digunakan oleh tabel lainya"
    ]));
}

$sql = "DELETE FROM tb_paket WHERE id = '$id'";
query($sql);
logger("DELETE PAKET", "({$_SESSION['auth']->user['nama']}) just deleted a record with the id ($id)");

exit(json_encode([
    "status" => "ok",
]));