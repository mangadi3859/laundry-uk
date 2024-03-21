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
ORDER BY tb_transaksi.id DESC
";

$transaksi = query($sql);

$sql = "SELECT id, nama FROM tb_outlet";
$outlet = query($sql);

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
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">
    <link rel="stylesheet" href="../../public/css/paket.css">
    <link rel="stylesheet" href="../../public/lib/pagination/styles.css">
    <link rel="stylesheet" href="../../public/css/transaksi.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/lib/moment/moment.js" defer></script>
    <script src="../../public/lib/moment/timezone.js" defer></script>
    <script src="../../public/lib/pagination/jquery.js" defer></script>
    <script src="../../public/lib/pagination/pagination.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/transaksi.js" defer></script>
    <!-- <script src="../../public/js/report.js" defer></script> -->
</head>

<body>
    <?php include "../../components/sidebar.php" ?>

    <div class="main-container">
        <img class="banner" src="../../public/assets/transaksi-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>

            <div class="action-table">
                <?php
                if (isPermited([Privilege::$ADMIN, Privilege::$KASIR])) {
                    $options = "";
                    foreach ($outlet as $option) {
                        $options .= "<option value='{$option['id']}'>{$option['nama']}</option>";
                    }

                    echo <<<jw
                        <a href="../pendaftaran" class="action-table-btn"><i class="fas fa-plus"></i> Tambah transaksi</a>
                        <select name="outlet" id="i-outlet" class="input input-action" data-filter-outlet>
                            <option value="">--- Pilih outlet ---</option>
                            $options
                        </select>
                        jw;
                }
                ?>

                <select name="member" id="i-member" class="input input-action" data-filter-member>
                    <option value="">--- Pilih member ---</option>
                    <?php
                    foreach ($member as $option) {
                        echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                    }
                    ?>
                </select>

                <form action="" method="GET" class="action-form" data-member-form>
                    <input id="i-name" name="search" type="text" placeholder="Cari Kode Invoice" class="input input-action">
                    <button type="submit" class="action-table-btn"><i class="fas fa-magnifying-glass"></i></button>
                </form>
            </div>

            <div class="print-table">
                <form action="report.php" target="_blank" method="GET">
                    <select name="simple" id="i-simple" class="input input-action">
                        <option value="">Semua</option>
                        <option value="bulan">Bulan ini</option>
                        <option value="bulan_lalu">Bulan lalu</option>
                        <option value="tahun">Tahun ini</option>
                        <option value="tahun_lalu">Tahun lalu</option>
                    </select>
                    <button class="action-table-btn" type="submit"><i class="fa fa-print"></i> Print Transaksi</button>
                </form>

                <button title="advance" data-input-advance class="action-table-btn action-advance"><i class="fa fa-gear" style="font-size: 1.1rem;"></i></button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Outlet</th>
                            <th>Member</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody data-table-body="<?= $_SESSION['auth']->user['role'] ?>">

                    </tbody>
                </table>
            </div>

            <div id="pagination" data-pages="<?= sizeof($transaksi) ?>"></div>
        </main>
    </div>
</body>

</html>