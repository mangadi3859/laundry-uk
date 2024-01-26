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
ORDER BY tb_transaksi.id
";

$transaksi = query($sql);

$sql = "SELECT id, nama FROM tb_outlet";
$idOutlet = query($sql);

$sql = "SELECT id, nama FROM tb_member";
$member = query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Transaksi</title>

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css"> -->
    <!-- <link rel="stylesheet" href="../../public/css/global.css"> -->
    <link rel="stylesheet" href="../../public/css/transaksi-report.css">
    <!-- <link rel="stylesheet" href="../../public/css/member.css"> -->
    <!-- <link rel="stylesheet" href="../../public/css/paket.css"> -->
    <!-- <link rel="stylesheet" href="../../public/css/transaksi.css"> -->

    <!-- JS -->
    <!-- <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/lib/moment/moment.js" defer></script>
    <script src="../../public/lib/moment/timezone.js" defer></script>
    <script src="../../public/js/global.js" defer></script> -->
    <script src="../../public/js/transaksi-report.js" defer></script>
</head>
<body>
    <table id="table" border="1" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Outlet</th>
                <th>Member</th>
                <th>Tanggal Transaksi</th>
                <th>Batas Waktu</th>
                <th>Tanggal Bayar</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($transaksi as $k => $row) {
                $warning = array_pop($row);
                $idMember = array_pop($row);
                $idOutlet = array_pop($row);
                $member = array_pop($row);

                $row["tgl"] = date("Y/m/d", strtotime($row["tgl"]));
                $row["tgl_bayar"] = date("Y/m/d", strtotime($row["tgl_bayar"]));
                $row["batas_waktu"] = date("Y/m/d", strtotime($row["batas_waktu"]));
                echo "<tr data-outlet='{$idOutlet}' data-member='{$idMember}' data-member-name='{$row['invoice']}'>";
                foreach ($row as $k => $data) {

                    if ($k == "tgl_bayar" && $row["dibayar"] != "dibayar") {
                        echo "<td>-</td>";
                        continue;
                    } else
                        echo "<td>$data</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <script>
        print();
    </script>
</body>
</html>