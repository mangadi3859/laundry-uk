<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

$userOutlet = query("SELECT nama, id FROM tb_outlet WHERE id = {$_SESSION["auth"]->user["id_outlet"]}");
$laundrySql = "SELECT 
SUM(CASE WHEN tb_transaksi.status = 'proses' THEN 1 ELSE 0 END) AS proses,
SUM(CASE WHEN tb_transaksi.status = 'baru' THEN 1 ELSE 0 END) AS baru,
SUM(CASE WHEN tb_transaksi.status = 'selesai' THEN 1 ELSE 0 END) AS selesai
FROM tb_transaksi;";
$laundry = query($laundrySql)[0];

$dbOutlet = query("SELECT COUNT(*) AS CT FROM tb_outlet")[0];
$dbPaket = query("SELECT COUNT(*) AS CT FROM tb_paket")[0];
$dbMember = query("SELECT COUNT(*) AS CT FROM tb_member")[0];

$_DASHBOARD = DashboardTab::$DASHBOARD;

$user = $_SESSION["auth"]->user;
$isAdmin = $user["role"] == Privilege::$ADMIN;
$isKasir = $user["role"] == Privilege::$KASIR;
$isOwner = $user["role"] == Privilege::$OWNER;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">

    <!-- JS -->
    <script src="../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../public/js/dashboard.js" defer></script>
</head>
<body>
    <?php include "../components/sidebar.php" ?>
    <div class="main-container">
        <main id="main">
            <?php include "../components/navbar.php" ?>
            <div class="dashboard-banner">
                <p>Selamat datang <strong class="accent capitalize"><?= $_SESSION["auth"]->user["nama"] ?></strong>!</p>
                <p>Kamu adalah <strong class="accent capitalize"><?= $_SESSION["auth"]->user["role"] ?></strong>!</p>
            </div>
            <div class="container">
                <div class="profile">
                    <div class="avatar"><?= getInitial($_SESSION["auth"]->user["nama"]) ?></div>
                    <div class="grid-info">
                        <div class="grid-info-name">ID</div>
                        <div class="grid-info-data"><?= getInitial($_SESSION["auth"]->user["id"]) ?></div>
                        <div class="grid-info-name">Nama</div>
                        <div class="grid-info-data"><?= $_SESSION["auth"]->user["nama"] ?></div>
                        <div class="grid-info-name">Username</div>
                        <div class="grid-info-data"><?= $_SESSION["auth"]->user["username"] ?></div>
                        <div class="grid-info-name">Email</div>
                        <div class="grid-info-data"><?= $_SESSION["auth"]->user["email"] ?></div>
                        <div class="grid-info-name">Outlet</div>
                        <div class="grid-info-data"><?= $userOutlet[0]["nama"] ?></div>
                        <div class="grid-info-name">Role</div>
                        <div class="grid-info-data"><?= $_SESSION["auth"]->user["role"] ?></div>
                    </div>
                    <div class="reset-pass">
                        <a data-reset-pass>Ganti password?</a>
                    </div>
                </div>
                <div class="group-panel">
                    <div <?= $isAdmin || $isKasir ? "" : "hidden" ?> class="laundry panel">
                        <div class="head">Laundry</div>
                        <div class="content">
                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-circle-check fas"></i> <span>Selesai</span>
                                </div>
                                <div class="box-body"><?= $laundry["selesai"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-boxes-stacked fas"></i> <span>Proses</span>
                                </div>
                                <div class="box-body"><?= $laundry["proses"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-layer-group fas"></i> <span>Baru</span>
                                </div>
                                <div class="box-body"><?= $laundry["baru"] ?? 0 ?></div>
                            </div>
                        </div>
                    </div>
                    <div <?= $isAdmin || $isOwner ? "" : "hidden" ?> class="database panel">
                        <div class="head">Database</div>
                        <div class="content">
                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-location-dot fas"></i> <span>Outlet</span>
                                </div>
                                <div class="box-body"><?= $dbOutlet["CT"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-boxes-packing fas"></i> <span>Paket</span>
                                </div>
                                <div class="box-body"><?= $dbPaket["CT"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <i class="fa-user fas"></i> <span>Member</span>
                                </div>
                                <div class="box-body"><?= $dbMember["CT"] ?? 0 ?></div>
                            </div>
                        </div>
                    </div>
                    <div <?= $isAdmin || $isOwner ? "" : "hidden" ?> class="actions">
                        <a href="transaksi">Lihat transaksi <i class="fas fa-arrow-up-right-from-square"></i></a>
                        <a href="transaksi/report.php">Buat laporan <i class="fas fa-print"></i></a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>