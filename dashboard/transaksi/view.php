<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}


permitAccess([Privilege::$ADMIN, Privilege::$KASIR, Privilege::$OWNER], "../");
$_DASHBOARD = DashboardTab::$TRANSAKSI;

$id = $_GET["id"] ?? NULL;

if (!@$id) {
    exit(header("Location: ./"));
}

$sql = "SELECT * FROM tb_transaksi WHERE id = '$id'";
$_transaksi = query($sql);

if (empty($_transaksi)) {
    exit(header("Location: ./"));
}

$_transaksi = $_transaksi[0];

$idMember = $_transaksi["id_member"];
$idOutlet = $_transaksi["id_outlet"];
$status = $_transaksi["status"];
$dibayar = $_transaksi["dibayar"];
$time = strtotime($_transaksi["tgl"]);
$timeFormat = date("Y-m-d", $time);

$sql = "SELECT id, nama FROM tb_member WHERE id = '$idMember'";
$member = query($sql);

$sql = "SELECT id, nama FROM tb_outlet WHERE id = '$idOutlet'";
$outlet = query($sql);

$sql = "SELECT * FROM tb_paket WHERE id_outlet = '$idOutlet'";
$paket = query($sql);

$sql = "SELECT tb_paket.nama_paket AS nama_paket, qty, harga, harga * qty AS total_harga FROM tb_detail_transaksi 
JOIN tb_paket ON tb_paket.id = tb_detail_transaksi.id_paket
WHERE id_transaksi = '$id' 
";
$items = query($sql);

// $sql = "SELECT COUNT(*) AS CT FROM tb_transaksi WHERE id_member = '$idMember' AND dibayar = 'dibayar'";
$diskon = $_transaksi["diskon"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Transaksi</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">
    <link rel="stylesheet" href="../../public/css/pendaftaran-detail.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <!-- <script src="../../public/js/transaksi-view.js" defer></script> -->
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/transaksi-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="detail-info">
                <div class="head">Detail Transaksi</div>
                <div class="grid-info">
                    <span class="grid-head">Tanggal</span> <span>:</span> <span><?= $timeFormat ?></span>
                    <span class="grid-head">Nama Pelanggan</span> <span>:</span> <span><?= $member[0]["nama"] ?></span>
                    <span class="grid-head">Nama Karyawan</span> <span>:</span> <span><?= $_SESSION["auth"]->user["nama"] ?></span>
                    <span class="grid-head">Outlet</span> <span>:</span> <span><?= $outlet[0]["nama"] ?></span>
                    <span class="grid-head">Status</span> <span>:</span> <span><?= $status ?></span>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Paket</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody data-table-detail data-member="<?= $idMember ?>" data-outlet="<?= $idOutlet ?>" data-status="<?= $status ?>" data-payment="<?= $dibayar ?>">

                        <?php
                        $subtotal = 0;
                        $extra = (int) $_transaksi["biaya_tambahan"];

                        foreach ($items as $i => $row) {
                            $subtotal += $row["total_harga"];
                            $index = $i + 1;

                            echo <<<td
                            <tr>
                                <td>$index</td>
                                <td>{$row["nama_paket"]}</td>
                                <td>{$row["qty"]}</td>
                                <td>{$row["harga"]}</td>
                                <td>{$row["total_harga"]}</td>
                            </tr>
                            td;
                        }
                        $pajak = ($subtotal + $extra) * $TAX;
                        $diskonStr = ($subtotal + $extra) * $diskon;
                        $total = $subtotal + $extra + $pajak - $diskon;

                        $_subtotal = number_format($subtotal);
                        $_extra = number_format($extra);
                        $_pajak = number_format($pajak);
                        $_diskonStr = number_format($diskonStr);
                        $_total = number_format($total);

                        ?>

                        <tr data-first-row style="border-top: 2px solid black;">
                            <td></td>
                            <td class="align-left" colspan="3">Subtotal</td>
                            <td data-col-subtotal>Rp. <?= $_subtotal ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Biaya Tambahan</td>
                            <td data-col-extra>Rp. <?= $_extra ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Pajak</td>
                            <td data-col-pajak="<?= $TAX ?>">Rp. <?= $_pajak ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Diskon</td>
                            <td data-col-diskon="<?= $diskon ?>">Rp. <?= $_diskonStr ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Total</td>
                            <td data-col-total>Rp. <?= $_total ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>