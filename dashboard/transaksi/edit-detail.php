<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN, Privilege::$KASIR], "../");
$_DASHBOARD = DashboardTab::$TRANSAKSI;

$idTransaksi = $_GET["id"] ?? NULL;

if (!@$idTransaksi) {
    exit(header("Location: ./"));
}

$sql = "SELECT * FROM tb_transaksi WHERE id = '$idTransaksi' LIMIT 1;";
$transaksi = query($sql)[0];

$idMember = $transaksi["id_member"];
$idOutlet = $transaksi["id_outlet"];
$status = $transaksi["status"];
$dibayar = $transaksi["dibayar"];
$time = strtotime($transaksi["tgl"]);
$timeFormat = date("Y-m-d", $time);

if (!@$idMember) {
    exit(header("Location: ./?err=Data tidak lengkap"));
}

$sql = "SELECT id, nama FROM tb_member WHERE id = '$idMember'";
$member = query($sql);


if (empty($member)) {
    exit(header("Location: ./?err=Member tidak terdaftar"));
}

$sql = "SELECT id, nama FROM tb_outlet WHERE id = '$idOutlet'";
$outlet = query($sql);

if (empty($outlet)) {
    exit(header("Location: ./?err=Outlet tidak terdaftar"));
}

$sql = "SELECT * FROM tb_paket WHERE id_outlet = '$idOutlet'";
$paket = query($sql);

$sql = "SELECT COUNT(*) AS CT FROM tb_transaksi WHERE id_member = '$idMember' AND dibayar = 'dibayar'";
$diskon = $transaksi["diskon"];

$extra = $transaksi["biaya_tambahan"];

$sql = "SELECT tb_detail_transaksi.id AS id, id_transaksi, id_outlet, tb_paket.id AS id_paket, nama_paket, qty, jenis, harga FROM tb_detail_transaksi JOIN tb_paket ON tb_paket.id = tb_detail_transaksi.id_paket WHERE id_transaksi = '$idTransaksi'";
$_detail = query($sql);

foreach ($_detail as $i => $d) {
    $_detail[$i]["qty"] = (int) $d["qty"];
    $_detail[$i]["harga"] = (int) $d["harga"];
}

$detail = base64_encode(json_encode($_detail));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Edit Detail</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/member.css">
    <link rel="stylesheet" href="../../public/css/pendaftaran-detail.css">

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/edit-detail.js" defer></script>
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
                    <select data-input data-id-transaksi="<?= $idTransaksi ?>" data-outlet='<?= $outlet[0]['id'] ?>' name="paket" id="i-paket" class="input input-action">
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
                    <tbody data-table-detail data-extra-fee="<?= $extra ?>" data-id-transaksi="<?= $idTransaksi ?>" data-member="<?= $idMember ?>" data-outlet="<?= $idOutlet ?>" data-status="<?= $status ?>" data-payment="<?= $dibayar ?>" data-detail64="<?= $detail ?>">

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