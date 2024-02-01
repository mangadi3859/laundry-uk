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

$sql = "SELECT tb_user.id AS id, tb_user.nama AS nama, tb_user.username AS username, tb_user.email AS email, tb_outlet.nama AS outlet, tb_user.role AS `role`, tb_outlet.id AS id_outlet FROM tb_user
JOIN tb_outlet ON tb_outlet.id = tb_user.id_outlet";

$paket = query($sql);

$sql = "SELECT id, nama FROM tb_outlet";
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
    <link rel="stylesheet" href="../../public/css/paket.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/karyawan.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/karyawan-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-table">
                <a href="add.php" class="action-table-btn"><i class="fas fa-plus"></i> Tambah karyawan</a>
                <select name="outlet" id="i-outlet" class="input input-action" data-filter-outlet>
                    <option value="">--- Pilih outlet ---</option>
                    <?php
                    foreach ($idOutlet as $option) {
                        echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Karyawan</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Outlet</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($paket as $k => $row) {
                                $idOutlet = array_pop($row);
                                echo "<tr data-outlet='{$idOutlet}'>";
                                foreach ($row as $k => $data) {
                                    if ($k == "harga") {
                                        $formated = number_format($data, 0, ".", ",");
                                        echo "<td>Rp. $formated</td>";
                                    } else
                                        echo "<td>$data</td>";
                                }

                                $isSameUser = $_SESSION["auth"]->user["id"] == $row['id'];
                                $href = $isSameUser ? "data-user-prohibited" : "href='edit.php?id={$row['id']}'";
                                echo <<<action
                                <td class="tb-action">
                                    <a $href title="EDIT DATA" class='action-btn btn-primary'>EDIT</a>
                                </td>
                                action;
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
        </main>
    </div>
</body>
</html>