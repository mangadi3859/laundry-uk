<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: ../login.php"));
}

permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$OUTLET;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Outlet</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/outlet.js" defer></script>
    <script src="../../public/js/outlet-add.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/outlet-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Form Outlet</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-nama" class="label">Nama Outlet</label>
                        <input name="name" id="i-nama" class="input" type="text" placeholder="Nama Outlet" required />
                    </div>

                    <div class="input-group">
                        <label for="i-alamat" class="label">Alamat</label>
                        <input name="alamat" id="i-alamat" class="input" type="text" placeholder="Alamat Outlet" required />
                    </div>
                    
                    <div class="input-group">
                        <label for="i-nohp" class="label">Nomer Komunikasi</label>
                        <input name="nohp" id="i-nohp" class="input" type="text" data-number-only pattern="62\d{7,13}" placeholder="(62)" required />
                    </div>
                </div>

                <div class="form-action">
                    <button class="link btn-primary" type="submit">Kirim</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>