<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: ../login.php"));
}

permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$MEMBER;

$id = $_GET["id"] ?? NULL;
if (!@$id) {
    exit(header("Location: ./"));
}

$sql = "SELECT * FROM tb_member";
$data = query($sql);

if (empty($data)) {
    exit(header("Location: ./"));
}

$data = $data[0];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Member</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/member.js" defer></script>
    <script src="../../public/js/member-edit.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/member-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Edit Data Member</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-id" class="label">ID (Read Only)</label>
                        <input name="id" value="<?= $id ?>" id="i-id" class="input" readonly type="text" placeholder="Nama" required />
                    </div>

                    <div class="input-group">
                        <label for="i-nama" class="label">Nama Member</label>
                        <input value="<?= $data["nama"] ?>" name="name" id="i-nama" class="input" type="text" placeholder="Nama" required />
                    </div>

                    <div class="input-group">
                        <label for="i-alamat" class="label">Alamat</label>
                        <input name="alamat" value="<?= $data["alamat"] ?>" id="i-alamat" class="input" type="text" placeholder="Alamat" required />
                    </div>
                    
                    <div class="input-group">
                        <label for="i-gender" class="label">Jenis Kelamin</label>
                        <select data-value="<?= $data["jenis_kelamin"] ?>" name="gender" id="i-gender" class="input" required>
                            <option value="">--- Belum dipilih ----</option>
                            <option value="L">Laki - Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="i-nohp" class="label">Nomer HP</label>
                        <input name="nohp" value="<?= $data["tlp"] ?>" id="i-nohp" class="input" type="text" data-number-only pattern="62\d{7,13}" placeholder="(62)" required />
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