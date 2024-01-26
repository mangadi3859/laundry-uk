<?php
require_once dirname(__FILE__) . "/../conn.php";
require_once dirname(__FILE__) . "/../functions.php";
require_once dirname(__FILE__) . "/../config.php";

internalOnly();

$isAuth = Auth::isAuthenticated();
$_user = $isAuth ? $_SESSION["auth"]->user : NULL;
$isAdmin = $isAuth && $_user["role"] == Privilege::$ADMIN;
$isKasir = $isAuth && $_user["role"] == Privilege::$KASIR;
$isOwner = $isAuth && $_user["role"] == Privilege::$OWNER;
?>

<link rel="stylesheet" href="<?= $ROOT_PATH ?>/public/css/sidebar.css">
<aside id="sidebar">
    <div class="side-brand">
        <a class="brand" href="<?= $ROOT_PATH ?>/dashboard">Laundry<strong>Ina</strong></a>
    </div>
    <div class="sidebar-item">
        <?php
        if ($isKasir || $isAdmin || $isOwner) {
            $tab = $_DASHBOARD == DashboardTab::$DASHBOARD ? "active" : "";
            echo <<<at
                <a class="$tab" href="$ROOT_PATH/dashboard"><i class="fa-home fas"></i> <span>Dashboard</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        if ($isKasir || $isAdmin) {
            $tab = $_DASHBOARD == DashboardTab::$PENDAFTARAN ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/pendaftaran"><i class="fa-cash-register fas"></i> <span>Pendaftaran</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        if ($isAdmin) {
            $tab = $_DASHBOARD == DashboardTab::$PAKET ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/paket"><i class="fa-dropbox fab"></i> <span>Paket</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        if ($isAdmin || $isKasir) {
            $tab = $_DASHBOARD == DashboardTab::$MEMBER ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/member"><i class="fa-user fas"></i> <span>Member</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        if ($isAdmin) {
            $tab = $_DASHBOARD == DashboardTab::$OUTLET ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/outlet"><i class="fa-location-arrow fas"></i> <span>Outlet</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        if ($isAdmin || $isKasir || $isOwner) {
            $tab = $_DASHBOARD == DashboardTab::$TRANSAKSI ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/transaksi"><i class="fa-book fas"></i> <span>Transaksi</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }

        $_tab = $_DASHBOARD == DashboardTab::$STATUS ? "active" : "";
        echo <<<at
            <a class="$_tab" href="$ROOT_PATH/dashboard/status"><i class="fa-archway fas"></i> <span>Status</span> <i class="angle fa fa-angle-right"></i></a>
        at;

        if ($isAdmin) {
            $tab = $_DASHBOARD == DashboardTab::$KARYAWAN ? "active" : "";
            echo <<<at
            <a class="$tab" href="$ROOT_PATH/dashboard/karyawan"><i class="fa-user-tie fas"></i> <span>Karyawan</span> <i class="angle fa fa-angle-right"></i></a>
            at;
        }
        ?>        
    </div>
    <div class="logout">
    <?php
    if ($isAuth) {
        echo <<<at
                <a data-logout-path="$ROOT_PATH/logout.php" id="logoutBtn"><span>Logout</span> <i style="margin-left: auto;" class="fa-right-from-bracket fas"></i></a>
            at;
    }
    ?>
    </div>
</aside>
<script src="<?= $ROOT_PATH ?>/public/js/sidebar.js" defer></script>