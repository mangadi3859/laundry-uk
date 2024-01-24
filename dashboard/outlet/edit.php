<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$OUTLET;

$id = $_GET["id"] ?? NULL;

if (!@$id) {
    exit(header("Location: ./"));
}

$idOutlet = query("SELECT id, nama, alamat, tlp FROM tb_outlet WHERE id = '$id'");

if (empty($idOutlet)) {
    exit(header("Location: ./"));
}

$idOutlet = $idOutlet[0];

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
    <script src="../../public/js/outlet-edit.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/outlet-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Edit Outlet</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-nama" class="label">ID (Read Only)</label>
                        <input value="<?= $idOutlet["id"] ?>" readonly name="id" id="i-id" class="input" type="text" placeholder="ID Outlet" required />
                    </div>

                    <div class="input-group">
                        <label for="i-nama" class="label">Nama Outlet</label>
                        <input value="<?= $idOutlet["nama"] ?>" name="name" id="i-nama" class="input" type="text" placeholder="Nama Outlet" required />
                    </div>

                    <div class="input-group">
                        <label for="i-alamat" class="label">Alamat</label>
                        <input value="<?= $idOutlet["alamat"] ?>" name="alamat" id="i-alamat" class="input" type="text" placeholder="Alamat Outlet" required />
                    </div>
                    
                    <div class="input-group">
                        <label for="i-nohp" class="label">Nomer Komunikasi</label>
                        <input value="<?= $idOutlet["tlp"] ?>" name="nohp" id="i-nohp" class="input" type="text" data-number-only pattern="62\d{7,13}" placeholder="(62)" required />
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