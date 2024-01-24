<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

if ($_SERVER["REQUEST_METHOD"] != 'POST') {
    exit(header("Location: ./"));
}

permitAccess([Privilege::$ADMIN, Privilege::$KASIR], "../");
$_DASHBOARD = DashboardTab::$PENDAFTARAN;

$idMember = $_POST["member"] ?? NULL;
$idOutlet = @$_POST["outlet"] ? $_POST["outlet"] : $_SESSION["auth"]->user["id_outlet"];
$status = @$_POST["status"] ? $_POST["status"] : "baru";
$dibayar = @$_POST["dibayar"] ? $_POST["dibayar"] : "belum_dibayar";
$time = date_create("now");
$timeFormat = date("Y-m-d");

if (!@$idMember) {
    exit(header("Location: ./?err=Data tidak lengkap"));
}

$sql = "SELECT id, nama FROM tb_member WHERE nama = '$idMember'";
$member = query($sql);


if (empty($member)) {
    exit(header("Location: ./?err=Member tidak terdaftar"));
}

$idMember = $member[0]["id"];

$sql = "SELECT id, nama FROM tb_outlet WHERE id = '$idOutlet'";
$outlet = query($sql);

if (empty($member)) {
    exit(header("Location: ./?err=Outlet tidak terdaftar"));
}

$sql = "SELECT * FROM tb_paket WHERE id_outlet = '$idOutlet'";
$paket = query($sql);

$sql = "SELECT COUNT(*) AS CT FROM tb_transaksi WHERE id_member = '$idMember' AND dibayar = 'dibayar'";
$diskon = query($sql)[0]["CT"];
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
    <link rel="stylesheet" href="../../public/css/pendaftaran-detail.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/pendaftaran-detail.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/pendaftaran-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <div class="detail-info">
                <div class="head">Detail Transaksi</div>
                <div class="grid-info">
                    <span class="grid-head">Tanggal</span> <span>:</span> <span><?= $timeFormat ?></span>
                    <span class="grid-head">Nama Pelanggan</span> <span>:</span> <span><?= $member[0]["nama"] ?></span>
                    <span class="grid-head">Nama Karyawan</span> <span>:</span> <span><?= $_SESSION["auth"]->user["nama"] ?></span>
                    <span class="grid-head">Outlet</span> <span>:</span> <span><?= $outlet[0]["nama"] ?></span>
                    <span class="grid-head">Status</span> <span>:</span> <span><?= $status ?></span>
                </div>
            </div>

            <div class="action-table">
                <div class="group">
                    <select data-input data-outlet='<?= $outlet[0]['id'] ?>' name="paket" id="i-paket" class="input input-action">
                        <option value="">--- Pilih paket ---</option>
                        <?php
                        foreach ($paket as $option) {
                            echo "<option value='{$option['id']}'>{$option['nama_paket']}</option>";
                        }
                        ?>
                    </select>
                    <button data-input-paket type="submit" class="action-table-btn"><i class="fas fa-cart-plus"></i></button>
                </div>

                <div class="group" style="display: none;">
                    <input data-input id="i-qty" name="qty" type="number" value="1" required min="1" placeholder="Jumlah paket" class="input input-action">
                    <button data-input-qty type="submit" class="action-table-btn"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="action-table">
                <div class="group">
                    <input data-input id="i-plus" name="tambahan" type="number" min="0" placeholder="Biaya Tambahan" class="input input-action">
                    <button data-input-tambahan type="submit" class="action-table-btn"><i class="fas fa-plus"></i></button>
                </div>

                <div class="group" style="display: none;">
                    <button data-submit type="submit" class="btn-submit btn-accent">Selesai <i class="fas fa-angle-right"></i></button>
                </div>
            </div>

            <div class="table-container" data-preview style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Paket</th>
                            <th>Jenis Paket</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody data-preview-row>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Paket</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody data-table-detail>

                        <tr data-first-row style="border-top: 2px solid black;">
                            <td></td>
                            <td class="align-left" colspan="3">Subtotal</td>
                            <td data-col-subtotal colspan="2">Rp. 0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Biaya Tambahan</td>
                            <td data-col-extra colspan="2">Rp. 0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Pajak</td>
                            <td data-col-pajak="<?= $TAX ?>" colspan="2">Rp. 0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Diskon</td>
                            <td data-col-diskon="<?= $diskon ?>" colspan="2">Rp. 0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-left" colspan="3">Total</td>
                            <td data-col-total colspan="2">Rp. 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>