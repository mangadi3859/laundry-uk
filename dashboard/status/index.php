<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

$_DASHBOARD = DashboardTab::$STATUS;

$token = $_GET["token"] ?? NULL;
$sql = "SELECT * FROM tb_member WHERE token = '$token'";
$member = query($sql);
$transaksi = [];

if (!empty($member)) {
    global $transaksi;

    $sql = "SELECT tb_transaksi.id AS id, 
    tb_transaksi.kode_invoice AS invoice, 
    tb_transaksi.tgl AS tgl, 
    tb_transaksi.batas_waktu AS batas_waktu,
    tb_transaksi.tgl_bayar AS tgl_bayar,
    tb_transaksi.status AS status,
    tb_transaksi.dibayar AS dibayar,
    CASE
        WHEN tb_transaksi.batas_waktu <= CURRENT_TIMESTAMP AND tb_transaksi.dibayar != 'dibayar' THEN 1
        ELSE 0
    END AS warning
    FROM tb_transaksi
    WHERE id_member = '{$member[0]['id']}'
    ORDER BY tb_transaksi.id
    ";

    $transaksi = query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Status</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">
    <link rel="stylesheet" href="../../public/css/transaksi.css">
    <link rel="stylesheet" href="../../public/css/status.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <!-- <script src="../../public/js/transaksi-view.js" defer></script> -->
</head>

<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/status-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>

            <div class="action-table">
                <form action="" method="GET" class="action-form">
                    <input name="token" type="text" placeholder="Cari token member" class="input-action">
                    <button type="submit" class="action-table-btn"><i class="fas fa-magnifying-glass"></i></button>
                </form>
            </div>

            <?php
            if (@$token && !empty($member)) {
                echo <<<detail
                <div class="detail-info">
                    <div class="head">Preview Member</div>
                    <div class="grid-info">
                        <span class="grid-head">Member</span>
                        <span>{$member[0]['nama']}</span>
                        <span class="grid-head">Token</span>
                        <span>{$member[0]['token']}</span>
                    </div>
                </div>
                detail;
            }
            ?>

            <div class="table-container" <?= empty($transaksi) ? "hidden" : "" ?>>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Tanggal Transaksi</th>
                            <th>Batas Waktu</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($transaksi as $i => $row) {
                            $warning = array_pop($row);


                            $row["tgl"] = date("Y/m/d", strtotime($row["tgl"]));
                            $row["tgl_bayar"] = date("Y/m/d", strtotime($row["tgl_bayar"]));
                            $row["batas_waktu"] = date("Y/m/d", strtotime($row["batas_waktu"]));
                            $index = $i + 1;
                            echo "<tr>";
                            foreach ($row as $k => $data) {
                                if ($k == "id") {
                                    echo "<td>$index</td>";
                                    continue;
                                }
                                if ($k == "invoice" && $warning) {
                                    echo "<td><a data-warning='{$row['batas_waktu']}' title='Batas waktu terlewat' class='warning fa-triangle-exclamation fas'></a> $data</td>";
                                    continue;
                                }

                                if ($k == "batas_waktu" && $warning) {
                                    echo "<td><a data-warning='{$row['batas_waktu']}' title='Batas waktu terlewat' class='warning fa-triangle-exclamation fas'></a> $data</td>";
                                    continue;
                                }

                                if ($k == "tgl_bayar" && $row["dibayar"] != "dibayar") {
                                    echo "<td>-</td>";
                                    continue;
                                }

                                if ($k == "status") {
                                    $bg = "";
                                    $text = "";
                                    switch ($data) {
                                        case "baru": {
                                                $bg = "#cce5ff";
                                                $text = "#004085";
                                                break;
                                            }
                                        case "proses": {
                                                $bg = "#fff3cd";
                                                $text = "#856404";
                                                break;
                                            }
                                        case "selesai": {
                                                $bg = "#d4edda";
                                                $text = "#155724";
                                                break;
                                            }
                                        default: {
                                                $bg = "#d6d8d9";
                                                $text = "#1b1e21";
                                                break;
                                            }
                                    }

                                    echo "<td><div class='td-info'><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $text;'>$data</span></div></td>";
                                } else if ($k == "dibayar") {
                                    $bg = "";
                                    $text = "";
                                    switch ($data) {
                                        case "belum_dibayar": {
                                                $bg = "#fff3cd";
                                                $text = "#856404";
                                                break;
                                            }
                                        default: {
                                                $bg = "#d4edda";
                                                $text = "#155724";
                                                break;
                                            }
                                    }

                                    echo "<td><div class='td-info'><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $text;'>$data</span></div></td>";
                                } else
                                    echo "<td>$data</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>