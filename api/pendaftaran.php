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
$idMember = $payload["member"] ?? NULL;
$idOutlet = $payload["outlet"] ?? NULL;
$items = $payload["items"] ?? NULL;
$extra = $payload["extra"] ?? NULL;
$status = $payload["status"] ?? NULL;
$payment = $payload["payment"] ?? NULL;

if (!@$idMember || !@$idOutlet || !@$items) {
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

    $invoice = "INV" . substr("000" . $idOutlet, -3) . "-" . date("y") . strtoupper(Auth::generateToken(7));
    $sql = "INSERT INTO tb_transaksi VALUE (
        '', 
        '$idOutlet',    
        '$invoice',
        '$idMember',
        CURRENT_TIMESTAMP,
        CURRENT_TIMESTAMP + INTERVAL 3 DAY,
        $tgl_byr,
        '$extra',
        '$diskon',
        '$TAX',
        '$_status',
        '$_payment',
        '{$_SESSION["auth"]->user["id"]}'
    )
    ";
    query($sql);
    $idTransaksi = $conn->insert_id;


    foreach ($items as $k => $data) {
        $sql = "INSERT INTO tb_detail_transaksi VALUE ('', '$idTransaksi', '{$data['id']}', '{$data['qty']}')";
        query($sql);
    }

    $conn->commit();
    logger("INSERT TRANSAKSI", "({$_SESSION['auth']->user['nama']}) just created a new record");
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