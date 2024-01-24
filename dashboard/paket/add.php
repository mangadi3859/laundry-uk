<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$PAKET;

$sql = "SELECT id, nama FROM tb_outlet;";
$idOutlet = query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Paket</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/paket.js" defer></script>
    <script src="../../public/js/paket-add.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/paket-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Form Paket</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-nama" class="label">Nama Paket</label>
                        <input name="name" id="i-nama" class="input" type="text" placeholder="Nama" required />
                    </div>

                    
                    <div class="input-group">
                        <label for="i-jenis" class="label">Jenis Paket</label>
                        <select name="jenis" id="i-jenis" class="input" required>
                            <option value="">--- Belum dipilih ----</option>
                            <option value="<?= PaketType::$KAOS ?>">Kaos</option>
                            <option value="<?= PaketType::$BED_COVER ?>">Bed Cover</option>
                            <option value="<?= PaketType::$KILOAN ?>">Kiloan</option>
                            <option value="<?= PaketType::$SELIMUT ?>">Selimut</option>
                            <option value="<?= PaketType::$LAIN ?>">Lain</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="i-outlet" class="label">Outlet</label>
                        <select name="outlet" id="i-outlet" class="input" required>
                        <option value="">--- Belum dipilih ---</option>
                        <?php
                        foreach ($idOutlet as $option) {
                            echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                        }
                        ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="i-harga" class="label">Harga</label>
                        <input name="harga" id="i-harga" class="input" data-number-only type="text" placeholder="Harga" required />
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