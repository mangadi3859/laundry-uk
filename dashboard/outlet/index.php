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

$sql = "SELECT 
tb_outlet.id AS id, 
tb_outlet.nama AS nama, 
tb_outlet.alamat AS alamat, 
tb_outlet.tlp AS tlp,
CASE 
    WHEN tb_user.id IS NOT NULL OR tb_transaksi.id IS NOT NULL OR tb_paket.id IS NOT NULL THEN 1
    ELSE 0
END AS is_used
FROM 
tb_outlet
LEFT JOIN tb_user ON tb_user.id_outlet = tb_outlet.id
LEFT JOIN tb_transaksi ON tb_transaksi.id_outlet = tb_outlet.id
LEFT JOIN tb_paket ON tb_paket.id_outlet = tb_outlet.id
GROUP BY 
tb_outlet.id
ORDER BY 
tb_outlet.id ASC;";

$idOutlet = query($sql);
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
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/outlet-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-table">
                <a href="add.php" class="action-table-btn"><i class="fas fa-plus"></i> Tambah outlet</a>
            </div>
            <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Outlet</th>
                                <th>Alamat</th>
                                <th>Nomer Komunikasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($idOutlet as $k => $row) {
                                $isUsed = array_pop($row);
                                $delBtn = $isUsed ? "" : "<a data-action-delete='{$row['id']}' data-member='{$row["nama"]}' title='HAPUS DATA' class='action-btn btn-danger'>DELETE</a>";
                                echo "<tr>";
                                foreach ($row as $data) {
                                    echo "<td>$data</td>";
                                }
                                echo <<<action
                                <td class="tb-action">
                                    $delBtn
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