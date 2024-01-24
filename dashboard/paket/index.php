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

$sql = "SELECT 
tb_paket.id AS id, 
tb_paket.nama_paket AS nama_paket, 
tb_paket.jenis AS jenis,
tb_outlet.nama AS nama_outlet, 
tb_paket.harga AS harga,
CASE 
    WHEN tb_detail_transaksi.id IS NOT NULL THEN 1
    ELSE 0
END AS is_used,
tb_outlet.id AS id_outlet
FROM 
tb_paket
LEFT JOIN tb_detail_transaksi ON tb_detail_transaksi.id_paket = tb_paket.id
LEFT JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id
GROUP BY 
tb_paket.id
ORDER BY 
tb_paket.id ASC;";

$paket = query($sql);

$sql = "SELECT id, nama FROM tb_outlet";
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
    <link rel="stylesheet" href="../../public/css/paket.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/paket.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/paket-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-table">
                <a href="add.php" class="action-table-btn"><i class="fas fa-plus"></i> Tambah paket</a>
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
                                <th>Nama Paket</th>
                                <th>Jenis</th>
                                <th>Outlet</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($paket as $k => $row) {
                                $idOutlet = array_pop($row);
                                $isUsed = array_pop($row);
                                $delBtn = $isUsed ? "" : "<a data-paket='{$row['nama_paket']}' data-action-delete='{$row['id']}' title='HAPUS DATA' class='action-btn btn-danger'>DELETE</a>";
                                echo "<tr data-outlet='{$idOutlet}'>";
                                foreach ($row as $k => $data) {
                                    if ($k == "harga") {
                                        $formated = number_format($data, 0, ".", ",");
                                        echo "<td>Rp. $formated</td>";
                                    } else
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