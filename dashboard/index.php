<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

$visibility = $_SESSION["auth"]->user["role"] == "kasir" ? "WHERE tb_transaksi.id_outlet = '{$_SESSION["auth"]->user["id_outlet"]}'" : "";
$userOutlet = query("SELECT nama, id FROM tb_outlet WHERE id = {$_SESSION["auth"]->user["id_outlet"]}");
$laundrySql = "SELECT 
SUM(CASE WHEN tb_transaksi.status = 'proses' THEN 1 ELSE 0 END) AS proses,
SUM(CASE WHEN tb_transaksi.status = 'baru' THEN 1 ELSE 0 END) AS baru,
SUM(CASE WHEN tb_transaksi.status = 'selesai' THEN 1 ELSE 0 END) AS selesai
FROM tb_transaksi $visibility;";
$laundry = query($laundrySql)[0];

$dbOutlet = query("SELECT COUNT(*) AS CT FROM tb_outlet")[0];
$dbPaket = query("SELECT COUNT(*) AS CT FROM tb_paket")[0];
$dbMember = query("SELECT COUNT(*) AS CT FROM tb_member")[0];

$_DASHBOARD = DashboardTab::$DASHBOARD;

$user = $_SESSION["auth"]->user;
$isAdmin = $user["role"] == Privilege::$ADMIN;
$isKasir = $user["role"] == Privilege::$KASIR;
$isOwner = $user["role"] == Privilege::$OWNER;

$avatarImgDashboard = file_exists($AVATAR_PATH . "/av{$_SESSION["auth"]->user["id"]}.jpg") ? "data:image/jpeg;base64," . base64_encode(file_get_contents($AVATAR_PATH . "/av{$_SESSION["auth"]->user["id"]}.jpg")) : "";
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
    <script src="../public/js/global.js" defer></script>
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
                    <div class="avatar">
                        <?= getInitial($_SESSION["auth"]->user["nama"]) ?>
                        <img loading="lazy" data-default="<?= $avatarImgDashboard ?>" data-avatar-img src="<?= $avatarImgDashboard ?>">
                        <label for="i-avatar"><span>Edit</span></label>
                        <input data-avatar-input hidden type="file" accept="image/jpeg  " id="i-avatar">
                    </div>
                    <div class="avatar-action" data-avatar-action>
                        <a data-avatar-save title="Simpan avatar">Simpan</a>
                        <a data-avatar-cancel title="Batalkan perubahan avatar">Batal</a>
                        <a data-avatar-delete title="Hapus avatar"><i class="fas fa-trash"></i></a>
                    </div>
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
                                    <div class="circle">
                                        <i class="fa-circle-check fas"></i>
                                    </div>
                                    <span>Selesai</span>
                                </div>
                                <div class="box-body"><?= $laundry["selesai"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <div class="circle">
                                        <i class="fa-boxes-stacked fas"></i>
                                    </div>
                                    <span>Proses</span>
                                </div>
                                <div class="box-body"><?= $laundry["proses"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <div class="circle">
                                        <i class="fa-layer-group fas"></i>
                                    </div>
                                    <span>Baru</span>
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
                                    <div class="circle">
                                        <i class="fa-location-dot fas"></i>
                                    </div>
                                    <span>Outlet</span>
                                </div>
                                <div class="box-body"><?= $dbOutlet["CT"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <div class="circle">
                                        <i class="fa-boxes-packing fas"></i>
                                    </div>
                                    <span>Paket</span>
                                </div>
                                <div class="box-body"><?= $dbPaket["CT"] ?? 0 ?></div>
                            </div>

                            <div class="content-box">
                                <div class="box-title">
                                    <div class="circle">
                                        <i class="fa-user fas"></i>
                                    </div>
                                    <span>Member</span>
                                </div>
                                <div class="box-body"><?= $dbMember["CT"] ?? 0 ?></div>
                            </div>
                        </div>
                    </div>
                    <div <?= $isAdmin || $isKasir || $isOwner ? "" : "hidden" ?> class="actions">
                        <a href="transaksi">Lihat transaksi <i class="fas fa-arrow-up-right-from-square"></i></a>
                        <a href="transaksi?print">Buat laporan <i class="fas fa-print"></i></a>

                        <?php if ($isAdmin) : ?>
                            <a href="log.php">Logger <i class="fas fa-gear"></i></a>
                            <a href="error.php">Error <i class="fas fa-xmark"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>