<?php
$_SAFE = true;

require_once "../../conn.php";
require_once "../../functions.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

$simple = $_GET["simple"] ?? NULL;
$final = $_GET["final"] ?? NULL;
$from = $_GET["from"] ?? NULL;

if (!@$simple && (!@$final || !@$from)) {
    exit(header("Location: ./transaksi.php"));
}

$SIMPLE = [
    "bulan" => "Bulan " . date("m") . " Tahun " . date("Y"),
    "bulan_lalu" => "Bulan " . date("m", time() - 3600 * 24 * 30) . " Tahun " . date("Y", time() - 3600 * 24 * 30),
    "tahun" => "Tahun " . date("Y"),
    "tahun_lalu" => "Tahun " . date("Y", time() - 3600 * 24 * 30 * 12),
];

$SIMPLE_TIME = [
    "bulan" => "MONTH(tgl) = MONTH(CURRENT_DATE()) AND YEAR(tgl) = YEAR(CURRENT_DATE())",
    "bulan_lalu" => "MONTH(tgl) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND YEAR(tgl) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH))",
    "tahun" => "YEAR(tgl) = YEAR(CURRENT_DATE())",
    "tahun_lalu" => "YEAR(tgl) = YEAR(CURRENT_DATE()) - 1",
];

$timeQuery = @$final ? "tgl BETWEEN '$from 00:00:00' AND '$final 23:59:59'" : "{$SIMPLE_TIME[$simple]}";
$role = $_SESSION["auth"]->user["role"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>LaundryIna - Print</title>
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/report.css">
</head>

<body>

    <div class="header">
        <div class="brand">Laundry<span class="accent">Ina</span></div>
        <div class="head-text">
            <div class="name">LAPORAN TRANSAKSI</div>
            <?php if (@$final && @$from) { ?>
                <div class="periode">Periode <?= $from ?> sampai <?= $final ?></div>
            <?php } else { ?>
                <div class="periode"><?= $SIMPLE[$simple] ?></div>
            <?php } ?>
        </div>
    </div>


    <?php
    if ($role != "kasir") {
        $sql = "SELECT nama_paket, COUNT(nama_paket) AS jumlah_penggunaan
        FROM tb_transaksi INNER JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
        WHERE $timeQuery
        GROUP BY nama_paket
        ORDER BY jumlah_penggunaan DESC";
        $nama_paket = query($sql);
    } else {
        $id_outlet = $_SESSION['auth']->user["id_outlet"];
        $nama_paket = query("SELECT nama_paket, COUNT(nama_paket) AS jumlah_penggunaan FROM tb_transaksi INNER JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id WHERE tgl BETWEEN $queryTime AND tb_outlet.id='$id_outlet'
        GROUP BY nama_paket
        ORDER BY jumlah_penggunaan DESC");
    }

    ?>


    <?php
    if ($role != "kasir") {
        $query_outlet = query("SELECT tb_outlet.id AS id_outlet, tb_outlet.nama AS nama_outlet FROM tb_detail_transaksi
        INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi=tb_transaksi.id
        INNER JOIN tb_outlet ON tb_transaksi.id_outlet=tb_outlet.id WHERE $timeQuery AND dibayar='dibayar' GROUP BY tb_outlet.id");
    } else {
        $id_outlet = $_SESSION['auth']->user["id_outlet"];
        $query_outlet = query("SELECT tb_outlet.id AS id_outlet, tb_outlet.nama AS nama_outlet FROM tb_detail_transaksi
        INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi=tb_transaksi.id
        INNER JOIN tb_outlet ON tb_transaksi.id_outlet=tb_outlet.id WHERE $timeQuery AND dibayar='dibayar' AND tb_outlet.id='$id_outlet' GROUP BY tb_outlet.id");
    }
    ?>




    <table id="tablePrint" cellpadding="10" cellspacing="0">

        <?php
        $total_semua = 0;
        foreach ($query_outlet as $baris_outlet) {
            if ($role != "kasir") {
                $id_outlet = $_SESSION['auth']->user["id_outlet"];
                $query = query("SELECT *, tb_outlet.id AS id_outlet_tb_outlet, tb_outlet.nama AS nama_outlet, tb_transaksi.id AS id_transaksi, tb_member.nama AS nama_member FROM tb_detail_transaksi
                    INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi=tb_transaksi.id
                    INNER JOIN tb_member ON tb_transaksi.id_member=tb_member.id
                    INNER JOIN tb_paket ON tb_detail_transaksi.id_paket=tb_paket.id
                    INNER JOIN tb_outlet ON tb_transaksi.id_outlet=tb_outlet.id
                    INNER JOIN tb_user ON tb_transaksi.id_user=tb_user.id WHERE $timeQuery AND dibayar='dibayar' AND tb_outlet.id='$id_outlet' GROUP BY kode_invoice");
            } else {
                $id_outlet = $_SESSION['auth']->user["id_outlet"];
                $query = query("SELECT *, tb_outlet.id AS id_outlet_tb_outlet, tb_outlet.nama AS nama_outlet, tb_transaksi.id AS id_transaksi, tb_member.nama AS nama_member FROM tb_detail_transaksi
                    INNER JOIN tb_transaksi ON tb_detail_transaksi.id_transaksi=tb_transaksi.id
                    INNER JOIN tb_member ON tb_transaksi.id_member=tb_member.id
                    INNER JOIN tb_paket ON tb_detail_transaksi.id_paket=tb_paket.id
                    INNER JOIN tb_outlet ON tb_transaksi.id_outlet=tb_outlet.id
                    INNER JOIN tb_user ON tb_transaksi.id_user=tb_user.id WHERE $timeQuery dibayar='dibayar' AND tb_outlet.id='$id_outlet' GROUP BY kode_invoice");
            }
        ?>
            <tr>
                <td align="left" class="outlet" colspan="4">Nama Outlet :
                    <b><?= $baris_outlet['nama_outlet'] ?></b>
                </td>
                <!-- <td></td> -->
            </tr>
            <?php
            $no = 1;

            // echo json_encode(mysqli_fetch_assoc($query));
            foreach ($query as $baris) {
            ?>

                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= "Pelanggan: " . $baris['nama_member'] ?></td>

                    <td align="left">
                        <?php
                        $id_transaksi = $baris['id_transaksi'];
                        $query1 = query("SELECT nama_paket, qty FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket=tb_paket.id WHERE id_transaksi='$id_transaksi'");
                        foreach ($query1 as $data_paket) {
                            echo $data_paket['nama_paket'] . " (x{$data_paket['qty']})";
                            echo "<br>";
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        $sql = "SELECT SUM(qty + tb_paket.harga) AS total FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket=tb_paket.id WHERE id_transaksi='$id_transaksi'";
                        $grand_total = query($sql)[0];
                        // exit($sql);
                        $pajak = $grand_total['total'] * $baris['pajak'];
                        $diskon = $grand_total['total'] * $baris['diskon'];
                        $total_keseluruhan = ($grand_total['total'] + $baris['biaya_tambahan'] + $pajak) - $diskon;
                        $tampil_total = number_format($total_keseluruhan, 0, ',', '.');
                        echo "Total Harga : <b>Rp " . $tampil_total . "</b>";
                        $total_semua += $tampil_total;
                        ?>
                    </td>
                </tr>

        <?php
            }

            //end
        }
        ?>
        <tr align="right">
            <td colspan="3"><b>Total Pendapatan</b>
                <br>
                <?php if (@$final && @$from) { ?>
                    <div class="periode">Periode <?= $from ?> sampai <?= $final ?></div>
                <?php } else { ?>
                    <div class="periode"><?= $SIMPLE[$simple] ?></div>
                <?php } ?>
            </td>
            <td>
                <?php
                echo "<h2>Rp " . number_format($total_semua, 3, '.', '.') . "</h2>";
                $pajak_semua = $total_semua * 0.0075;
                echo "Pajak : Rp " . number_format($pajak_semua, 3, '.', '.');
                ?>
            </td>
        </tr>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>