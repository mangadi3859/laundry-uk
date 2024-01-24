<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

$search = $_GET["search"] ?? NULL;
$filter = @$search ? "WHERE nama LIKE '%$search%'" : "";

permitAccess([Privilege::$ADMIN, Privilege::$KASIR], "../");
$_DASHBOARD = DashboardTab::$MEMBER;
$laki = Gender::$L;
$sql = "SELECT id, nama, alamat, 
CASE
    WHEN jenis_kelamin = '$laki' THEN 'Laki - Laki'
    ELSE 'Perempuan'
END AS gender,
tlp, token FROM tb_member $filter ORDER BY id ASC;";
$idOutlet = query($sql);
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
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/member-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-table">
                <a href="add.php" class="action-table-btn"><i class="fas fa-plus"></i> Tambah member</a>
                <form action="" method="GET" class="action-form">
                    <input name="search" type="text" placeholder="Cari nama" class="input-action">
                    <button type="submit" class="action-table-btn"><i class="fas fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Nomer HP</th>
                                <th>Token</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($idOutlet as $k => $row) {
                                echo "<tr>";
                                foreach ($row as $data) {
                                    echo "<td>$data</td>";
                                }

                                echo <<<action
                                <td class="tb-action">
                                    <a data-action-delete="{$row['id']}" data-member="{$row["nama"]}" title="HAPUS DATA" class='action-btn btn-danger'>DELETE</a>
                                    <a href='edit.php?id={$row['id']}' title="EDIT DATA" class='action-btn btn-primary'>EDIT</a>
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