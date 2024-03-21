<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";


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
$idTransaksi = $payload["id"] ?? NULL;
$idMember = $payload["member"] ?? NULL;
$idOutlet = $payload["outlet"] ?? NULL;
$items = $payload["items"] ?? NULL;
$extra = $payload["extra"] ?? NULL;
$status = $payload["status"] ?? NULL;
$payment = $payload["payment"] ?? NULL;

if (!@$idTransaksi || !@$idMember || !@$idOutlet || !@$items) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

if (sizeof($items) < 1) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Pilih setidaknya 1 paket"
    ]));
}

if ((int) $extra < 0) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Biaya tambahan tidak valid"
    ]));
}

$sql = "SELECT id FROM tb_outlet WHERE id = '$idOutlet'";
$outletData = query($sql);

if (empty($outletData)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak terdaftar"
    ]));
}

$sql = "SELECT COUNT(*) AS CT FROM tb_transaksi WHERE id_member = '$idMember' AND dibayar = 'dibayar'";
$tf = query($sql)[0]["CT"];
$diskon = calculateDiscount((int) $tf);
$_status = @$status ? $status : "baru";
$_payment = @$payment ? $payment : "belum_dibayar";
$tgl_byr = $_payment == "dibayar" ? "CURRENT_TIMESTAMP" : "NULL";

try {
    $conn->begin_transaction();

    $sql = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = '$idTransaksi'";
    query($sql);

    foreach ($items as $k => $data) {
        $sql = "INSERT INTO tb_detail_transaksi VALUE ('', '$idTransaksi', '{$data['id_paket']}', '{$data['qty']}')";
        query($sql);
    }

    $conn->commit();
    logger("UPDATE DETAIL TRANSAKSI", "({$_SESSION['auth']->user['nama']}) just modified a record with id ($idTransaksi)");
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
