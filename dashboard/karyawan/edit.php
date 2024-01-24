<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$KARYAWAN;

$id = $_GET["id"] ?? NULL;
if (!@$id) {
    exit(header("Location: ./"));
}

$sql = "SELECT * FROM tb_user WHERE id = '$id'";
$user = query($sql);

// echo json_encode($user);

if (empty($user)) {
    exit(header("Location: ./"));
}

$user = $user[0];
$sql = "SELECT id, nama FROM tb_outlet;";
$idOutlet = query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Karyawan</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/karyawan.js" defer></script>
    <script src="../../public/js/karyawan-edit.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/karyawan-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Edit Data User</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-id" class="label">ID (Read Only)</label>
                        <input name="id" value="<?= $id ?>" id="i-id" readonly class="input" type="text" placeholder="ID Karyawan" required />
                    </div>

                    <div class="input-group">
                        <label for="i-nama" class="label">Nama</label>
                        <input value="<?= $user["nama"] ?>" name="name" id="i-nama" class="input" type="text" placeholder="Nama" required />
                    </div>

                    <div class="input-group">
                        <label for="i-username" class="label">Username</label>
                        <input value="<?= $user["username"] ?>" name="username" id="i-username" class="input" type="text" placeholder="Username" required />
                    </div>

                    <div class="input-group">
                        <label for="i-email" class="label">Email</label>
                        <input value="<?= $user["email"] ?>" name="email" id="i-email" class="input" type="email" placeholder="Email" required />
                    </div>

                    <div class="input-group">
                        <label for="i-password" class="label optional">Password</label>
                        <input name="password" id="i-password" class="input" type="password" placeholder="Opsional"  />
                    </div>

                    <div class="input-group">
                        <label for="i-outlet" class="label">Outlet</label>
                        <select data-value="<?= $user["id_outlet"] ?>" name="outlet" id="i-outlet" class="input" required>
                        <option value="">--- Belum dipilih ---</option>
                        <?php
                        foreach ($idOutlet as $option) {
                            echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                        }
                        ?>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="i-role" class="label">Role</label>
                        <select data-value="<?= $user["role"] ?>" name="role" id="i-role" class="input" required>
                            <option value="">--- Belum dipilih ----</option>
                            <option value="<?= Privilege::$KASIR ?>">Kasir</option>
                            <option value="<?= Privilege::$ADMIN ?>">Admin</option>
                            <option value="<?= Privilege::$OWNER ?>">Owner</option>
                        </select>
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