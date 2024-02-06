<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";

// if ($_SERVER["REQUEST_METHOD"] != "POST") {
//     http_response_code(405);
//     exit(json_encode([
//         "message" => "Method Not Allowed"
//     ]));
// }

if (!Auth::isAuthenticated()) {
    http_response_code(401);
    exit(json_encode([
        "message" => "Unauthenticated"
    ]));
}

if (!isPermited([Privilege::$ADMIN, Privilege::$KASIR, Privilege::$OWNER])) {
    http_response_code(403);
    exit(json_encode([
        "message" => "Forbidden"
    ]));
}

$sql = "SELECT tb_transaksi.id AS id, 
tb_transaksi.kode_invoice AS invoice, 
tb_outlet.nama AS outlet, 
tb_member.nama AS member, 
tb_transaksi.tgl AS tgl, 
tb_transaksi.batas_waktu AS batas_waktu,
tb_transaksi.tgl_bayar AS tgl_bayar,
tb_transaksi.status AS status,
tb_transaksi.dibayar AS dibayar,
tb_user.nama AS kasir,
tb_member.nama AS member_name,
tb_transaksi.id_outlet AS id_outlet,
tb_transaksi.id_member AS id_member,
CASE
    WHEN tb_transaksi.batas_waktu <= CURRENT_TIMESTAMP AND tb_transaksi.dibayar != 'dibayar' THEN 1
    ELSE 0
END AS warning
FROM tb_transaksi
JOIN tb_user ON tb_user.id = tb_transaksi.id_user
JOIN tb_member ON tb_member.id = tb_transaksi.id_member
JOIN tb_outlet ON tb_outlet.id = tb_transaksi.id_outlet
ORDER BY tb_transaksi.id DESC
";

$transaksi = query($sql);



echo json_encode($transaksi);