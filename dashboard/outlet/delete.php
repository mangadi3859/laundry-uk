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
    WHEN tb_user.id IS NOT NULL OR tb_transaksi.id IS NOT NULL OR tb_paket.id IS NOT NULL THEN 1
    ELSE 0
END AS is_used
FROM 
tb_outlet
LEFT JOIN tb_user ON tb_user.id_outlet = tb_outlet.id
LEFT JOIN tb_transaksi ON tb_transaksi.id_outlet = tb_outlet.id
LEFT JOIN tb_paket ON tb_paket.id_outlet = tb_outlet.id
WHERE tb_outlet.id = '$id'
GROUP BY 
tb_outlet.id;";

$outlet = query($sql);

if (!empty($outlet) && $outlet[0]["is_used"] != 0) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak bisa dihapus karena outlet ini sudah digunakan oleh tabel lainya"
    ]));
}

$sql = "DELETE FROM tb_outlet WHERE id = '$id'";
query($sql);
logger("DELETE OUTLET", "({$_SESSION['auth']->user['nama']}) menghapus outlet_id($id)");

exit(json_encode([
    "status" => "ok",
]));