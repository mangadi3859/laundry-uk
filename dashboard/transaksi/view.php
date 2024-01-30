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

$sql = "SELECT id, nama, alamat FROM tb_member WHERE id = '$idMember'";
$member = query($sql);

$sql = "SELECT id, nama FROM tb_outlet WHERE id = '$idOutlet'";
$outlet = query($sql);

$sql = "SELECT * FROM tb_paket WHERE id_outlet = '$idOutlet'";
$paket = query($sql);

$actor = query("SELECT * FROM tb_user WHERE id = '{$_transaksi['id_user']}'");

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
    <!-- <link rel="stylesheet" href="../../public/css/report.css"> -->
    <link rel="stylesheet" href="../../public/css/invoice.css">
    
    
    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/report.js" defer></script>
    <!-- <script src="../../public/js/transaksi-view.js" defer></script> -->
</head>
<body>
    <?php include "../../components/sidebar.php" ?>

    <div  id="print-layer">
        <div class="invoice-box">
            <div class="invoice-head">
                <div class="company">
                    <div class="brand">Laundry<span class="accent">Ina</span></div>
                    <div class="self-info">
                        <div>Invoice: <?= $_transaksi["kode_invoice"] ?></div>
                        <div>Created: <?= date("M d, Y", strtotime($_transaksi["tgl"])) ?></div>
                        <div>Due: <?= date("M d, Y", strtotime($_transaksi["batas_waktu"])) ?></div>
                    </div>
                </div>

                <div class="actors">
                    <div class="company-detail">
                        <div>PT. LaundryIna</div>
                        <div>Jln. Prof, Mohyamin 4 no 3a</div>
                        <div><?= $actor[0]["nama"] ?></div>
                        <div><?= $actor[0]["email"] ?></div>
                    </div>
                    <div class="actor-detail">
                        <div><?= $member[0]["nama"] ?></div>
                        <div><?= $member[0]["alamat"] ?></div>
                        <div><strong><?= $_transaksi["status"] == "dibayar" ? "Paid" : "Unpaid" ?></strong></div>
                    </div>
                </div>
            </div>

            <div class="invoice-body">
                <table>
                    <thead>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </thead>
                    <tbody>
                    <?php
                    $subtotal = 0;
                    $extra = (int) $_transaksi["biaya_tambahan"];

                    foreach ($items as $i => $row) {
                        $subtotal += $row["total_harga"];
                        $index = $i + 1;
                        $_total_harga = number_format((int) $row["total_harga"]);

                        echo <<<td
                        <tr>
                            <td>{$row["nama_paket"]}</td>
                            <td>{$row["qty"]}x</td>
                            <td>Rp. $_total_harga</td>
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

                    <tr>
                        <td>Extra Fee</td>
                        <td colspan="2">Rp. <?= $_extra ?></td>
                    </tr>

                    <tr>
                        <td>Tax</td>
                        <td colspan="2">Rp. <?= $_pajak ?></td>
                    </tr>

                    <tr>
                        <td>Discount</td>
                        <td colspan="2">Rp. <?= $_diskonStr ?></td>
                    </tr>

                    <tr class="final">
                        <td></td>
                        <td colspan="2">Total: Rp. <?= $_total ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="main-container">
        <img class="banner" src="../../public/assets/transaksi-banner.jpg">
        <main id="main">
        <?php include "../../components/navbar.php" ?>
        <?php
        if (isPermited([Privilege::$ADMIN, Privilege::$OWNER]))
            echo <<<jw
            <div class="action-table">
                <a id="printBtn" class="action-table-btn btn-primary"><i class="fas fa-print"></i> Buat laporan</a>
            </div>
            jw;
        ?>
            <div class="invoice-box">
                <div class="invoice-head">
                    <div class="company">
                        <div class="brand">Laundry<span class="accent">Ina</span></div>
                        <div class="self-info">
                            <div>Invoice: <?= $_transaksi["kode_invoice"] ?></div>
                            <div>Created: <?= date("M d, Y", strtotime($_transaksi["tgl"])) ?></div>
                            <div>Due: <?= date("M d, Y", strtotime($_transaksi["batas_waktu"])) ?></div>
                        </div>
                    </div>

                    <div class="actors">
                        <div class="company-detail">
                            <div>PT. LaundryIna</div>
                            <div>Jln. Prof, Mohyamin 4 no 3a</div>
                            <div><?= $actor[0]["nama"] ?></div>
                            <div><?= $actor[0]["email"] ?></div>
                        </div>
                        <div class="actor-detail">
                            <div><?= $member[0]["nama"] ?></div>
                            <div><?= $member[0]["alamat"] ?></div>
                            <div><strong><?= $_transaksi["status"] == "dibayar" ? "Paid" : "Unpaid" ?></strong></div>
                        </div>
                    </div>
                </div>

                <div class="invoice-body">
                    <table>
                        <thead>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </thead>
                        <tbody>
                        <?php
                        $subtotal = 0;
                        $extra = (int) $_transaksi["biaya_tambahan"];

                        foreach ($items as $i => $row) {
                            $subtotal += $row["total_harga"];
                            $index = $i + 1;
                            $_total_harga = number_format((int) $row["total_harga"]);

                            echo <<<td
                            <tr>
                                <td>{$row["nama_paket"]}</td>
                                <td>{$row["qty"]}x</td>
                                <td>Rp. $_total_harga</td>
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

                        <tr>
                            <td>Extra Fee</td>
                            <td colspan="2">Rp. <?= $_extra ?></td>
                        </tr>

                        <tr>
                            <td>Tax</td>
                            <td colspan="2">Rp. <?= $_pajak ?></td>
                        </tr>

                        <tr>
                            <td>Discount</td>
                            <td colspan="2">Rp. <?= $_diskonStr ?></td>
                        </tr>

                        <tr class="final">
                            <td></td>
                            <td colspan="2">Total: Rp. <?= $_total ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>