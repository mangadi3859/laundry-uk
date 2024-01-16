<?php
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";

internalOnly();
$_DASHBOARD = DashboardTab::$DASHBOARD;
?>

<link rel="stylesheet" href="<?= $ROOT_PATH ?>/public/css/sidebar.css">
<aside id="sidebar">
    <div class="side-brand">
        <a class="brand" href="<?= $ROOT_PATH ?>/dashboard">Laundry<strong>Ina</strong></a>
    </div>
    <div class="sidebar-item">
        <a class="<?= $_DASHBOARD == DashboardTab::$DASHBOARD ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard"><i class="fa-home fas"></i> <span>Dashboard</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$PENDAFTARAN ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/pendaftaran.php"><i class="fa-cash-register fas"></i> <span>Pendaftaran</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$PAKET ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/paket.php"><i class="fa-dropbox fab"></i> <span>Paket</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$MEMBER ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/member.php"><i class="fa-user fas"></i> <span>Member</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$OUTLET ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/outlet.php"><i class="fa-location-arrow fas"></i> <span>Outlet</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$TRANSAKSI ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/transaksi.php"><i class="fa-book fas"></i> <span>Transaksi</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$STATUS ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/status"><i class="fa-archway fas"></i> <span>Status</span> <i class="angle fa fa-angle-right"></i></a>
        <a class="<?= $_DASHBOARD == DashboardTab::$KARYAWAN ? "active" : "" ?>" href="<?= $ROOT_PATH ?>/dashboard/karyawan"><i class="fa-user-tie fas"></i> <span>Karyawan</span> <i class="angle fa fa-angle-right"></i></a>
    </div>
    <div class="logout">
        <a data-logout-path="<?= $ROOT_PATH ?>/logout.php" id="logoutBtn"><span>Logout</span> <i style="margin-left: auto;" class="fa-right-from-bracket fas"></i></a>
    </div>
</aside>
<script src="<?= $ROOT_PATH ?>/public/js/sidebar.js" defer></script>