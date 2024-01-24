<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN, Privilege::$KASIR], "../");
$_DASHBOARD = DashboardTab::$PENDAFTARAN;

$sql = "SELECT id, nama FROM tb_member;";
$member = query($sql);

$sql = "SELECT id, nama FROM tb_outlet;";
$idOutlet = query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Pendaftaran</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/pendaftaran.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/pendaftaran-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-form">
                <a href="../member/add.php" class="action-table-btn"><i class="fas fa-plus"></i> Tambah member</a>
            </div>
            <form id="form" method="POST" action="detail.php">
                <div class="head">Form Registrasi</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-member" class="label">Member</label>
                        <input name="member" list="list-member" type="text" id="i-member" placeholder="Nama member" class="input" required />
                        <datalist id="list-member">
                            <?php
                            foreach ($member as $option) {
                                echo "<option>{$option['nama']}</option>";
                            }
                            ?>
                        </datalist>
                    </div>

                    <?php

                    $dataOutlet = [];
                    foreach ($idOutlet as $option) {
                        array_push($dataOutlet, "<option value='{$option['id']}'>{$option['nama']}</option>");
                    }
                    $strOutlet = implode("\n", $dataOutlet);

                    $input = <<<all
                        <div class="input-group">
                            <label for="i-outlet" class="label optional">Outlet</label>
                            <select name="outlet" id="i-outlet" class="input">
                                <option value="">--- Belum dipilih ---</option>
                                $strOutlet
                            </select>
                        </div>
                    
                        <div class="input-group">
                            <label for="i-status" class="label optional optional">Status</label>
                            <select name="status" id="i-status" class="input">
                                <option value="">--- Belum dipilih ---</option>
                                <option value="baru">Baru</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="diambil">Diambil</option>
                            </select>
                        </div>
                    
                        <div class="input-group">
                            <label for="i-dibayar" class="label optional">Pembayaran</label>
                            <select name="dibayar" id="i-dibayar" class="input">
                                <option value="">--- Belum dipilih ----</option>
                                <option value="belum_dibayar">Belum dibayar</option>
                                <option value="dibayar">Dibayar</option>
                            </select>
                        </div>
                        all;

                    if (isPermited([Privilege::$ADMIN])) {
                        echo $input;
                    }
                    ?>
                </div>

                <div class="form-action">
                    <button class="link btn-primary" type="submit">Kirim</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>