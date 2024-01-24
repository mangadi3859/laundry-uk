<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN, Privilege::$KASIR, Privilege::$OWNER], "../");
$_DASHBOARD = DashboardTab::$TRANSAKSI;

$sql = "SELECT tb_transaksi.id AS id, 
tb_transaksi.kode_invoice AS invoice, 
tb_outlet.nama AS outlet, 
tb_member.nama AS member, 
tb_transaksi.tgl AS tgl, 
tb_transaksi.batas_waktu AS batas_waktu,
tb_transaksi.tgl_bayar AS tgl_bayar,
tb_transaksi.status AS status,
tb_transaksi.dibayar AS dibayar,
tb_member.nama AS member_name,
tb_transaksi.id_outlet AS id_outlet,
tb_transaksi.id_member AS id_member
FROM tb_transaksi
JOIN tb_user ON tb_user.id = tb_transaksi.id_user
JOIN tb_member ON tb_member.id = tb_transaksi.id_member
JOIN tb_outlet ON tb_outlet.id = tb_transaksi.id_outlet
";

$transaksi = query($sql);

$sql = "SELECT id, nama FROM tb_outlet";
$idOutlet = query($sql);

$sql = "SELECT id, nama FROM tb_member";
$member = query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Transaksi</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">
    <link rel="stylesheet" href="../../public/css/paket.css">
    <link rel="stylesheet" href="../../public/css/transaksi.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/transaksi.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/transaksi-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="action-table">
                <a href="../pendaftaran" class="action-table-btn"><i class="fas fa-plus"></i> Tambah transaksi</a>
                <select name="outlet" id="i-outlet" class="input input-action" data-filter-outlet>
                    <option value="">--- Pilih outlet ---</option>
                    <?php
                    foreach ($idOutlet as $option) {
                        echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                    }
                    ?>
                </select>

                <select name="member" id="i-member" class="input input-action" data-filter-member>
                    <option value="">--- Pilih member ---</option>
                    <?php
                    foreach ($member as $option) {
                        echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                    }
                    ?>
                </select>

                <form action="" method="GET" class="action-form" data-member-form>
                    <input id="i-name" name="search" type="text" placeholder="Cari Member" class="input input-action">
                    <button type="submit" class="action-table-btn"><i class="fas fa-magnifying-glass"></i></button>
                </form>
            </div>

            <div class="action-table">
                <a href="report.php" class="action-table-btn btn-primary"><i class="fas fa-print"></i> Buat laporan</a>
            </div>
            <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Outlet</th>
                                <th>Member</th>
                                <th>Tanggal Transaksi</th>
                                <th>Batas Waktu</th>
                                <th>Tanggal Bayar</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($transaksi as $k => $row) {
                                $idMember = array_pop($row);
                                $idOutlet = array_pop($row);
                                $member = array_pop($row);


                                $row["tgl"] = date("Y/m/d", strtotime($row["tgl"]));
                                $row["tgl_bayar"] = date("Y/m/d", strtotime($row["tgl_bayar"]));
                                $row["batas_waktu"] = date("Y/m/d", strtotime($row["batas_waktu"]));
                                echo "<tr data-outlet='{$idOutlet}' data-member='{$idMember}' data-member-name='$member'>";
                                foreach ($row as $k => $data) {
                                    if ($k == "tgl_bayar" && $row["dibayar"] != "dibayar") {
                                        echo "<td>-</td>";
                                    }

                                    if ($k == "status") {
                                        $bg = "";
                                        $text = "";
                                        switch ($data) {
                                            case "baru": {
                                                    $bg = "#cce5ff";
                                                    $text = "#004085";
                                                    break;
                                                }
                                            case "proses": {
                                                    $bg = "#fff3cd";
                                                    $text = "#856404";
                                                    break;
                                                }
                                            case "selesai": {
                                                    $bg = "#d4edda";
                                                    $text = "#155724";
                                                    break;
                                                }
                                            default: {
                                                    $bg = "#d6d8d9";
                                                    $text = "#1b1e21";
                                                    break;
                                                }
                                        }

                                        echo "<td><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $text;'>$data</span> <button data-info-value='$data' data-status-edit='{$row['id']}' class='status-edit-btn fa fa-pen-to-square'></button></td>";
                                    } else if ($k == "dibayar") {
                                        $bg = "";
                                        $text = "";
                                        switch ($data) {
                                            case "belum_dibayar": {
                                                    $bg = "#fff3cd";
                                                    $text = "#856404";
                                                    break;
                                                }
                                            default: {
                                                    $bg = "#d4edda";
                                                    $text = "#155724";
                                                    break;
                                                }
                                        }

                                        echo "<td><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $text;'>$data</span> <button data-info-value='$data' data-pembayaran-edit='{$row['id']}' class='status-edit-btn fa fa-pen-to-square'></button></td>";
                                    } else
                                        echo "<td>$data</td>";
                                }
                                echo <<<action
                                <td class="tb-action">
                                    <a href='view.php?id={$row['id']}' title="VIEW DATA" class='action-btn btn-accent fa-eye fas'></a>
                                    <a data-action-delete="{$row['id']}" title="HAPUS DATA" class='action-btn btn-danger fas fa-trash'></a>
                                    <a href='edit.php?id={$row['id']}' title="EDIT DATA" class='action-btn btn-primary fas fa-gear'></a>
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