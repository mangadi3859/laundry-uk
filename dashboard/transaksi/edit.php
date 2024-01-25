<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}


permitAccess([Privilege::$ADMIN], "../");
$_DASHBOARD = DashboardTab::$TRANSAKSI;

$id = $_GET["id"] ?? NULL;

if (!@$id) {
    exit(header("Location: ./"));
}

$sql = "SELECT 
tb_transaksi.id AS id,
tb_transaksi.id_outlet	AS id_outlet,
tb_transaksi.kode_invoice AS invoice,
tb_transaksi.id_member AS id_member,
tb_transaksi.tgl AS tgl,
tb_transaksi.batas_waktu AS batas_waktu,
tb_transaksi.tgl_bayar	AS tgl_bayar,
tb_transaksi.biaya_tambahan	AS biaya_tambahan,
tb_transaksi.diskon	AS diskon,
tb_transaksi.pajak AS pajak,
tb_transaksi.status	AS status,
tb_transaksi.dibayar AS dibayar,
tb_transaksi.id_user AS id_user,
tb_member.nama AS nama_member
FROM tb_transaksi 
JOIN tb_member ON tb_transaksi.id_member = tb_member.id
WHERE tb_transaksi.id = '$id'";
$transaksi = query($sql);

if (empty($transaksi)) {
    exit(header("Location: ./"));
}
$transaksi = $transaksi[0];

$sql = "SELECT id, nama FROM tb_outlet";
$outlet = query($sql);

$sql = "SELECT id, nama FROM tb_member";
$member = query($sql);

$sql = "SELECT id, nama FROM tb_user";
$users = query($sql);

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

    <!-- JS -->
    <script src="../../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/transaksi.js" defer></script>
    <script src="../../public/js/transaksi-edit.js" defer></script>
</head>
<body>
    <?php include "../../components/sidebar.php" ?>
    <div class="main-container">
        <img class="banner" src="../../public/assets/paket-banner.jpg">
        <main id="main">
            <?php include "../../components/navbar.php" ?>
            <form id="form" >
                <div class="head">Edit Data Transaksi</div>
                <div class="form-content">
                    <div class="input-group">
                        <label for="i-id" class="label">ID (Read Only)</label>
                        <input readonly value="<?= $id ?>" name="name" id="i-id" class="input" type="text" placeholder="ID Paket" required />
                    </div>

                    <div class="input-group">
                        <label for="i-member" class="label">Member</label>
                        <input value="<?= $transaksi["nama_member"] ?>" list="list-member" placeholder="Masukan Nama Member" name="member" id="i-member" class="input" required />
                        <datalist name="member" id="list-member" class="input" required>
                            <?php
                            foreach ($member as $opt) {
                                echo "<option value='{$opt['id']}'>{$opt['nama']}</option>";
                            }
                            ?>
                        </datalist>
                    </div>

                    <div class="input-group">
                        <label for="i-tgl" class="label">Tanggal Transaksi</label>
                        <input name="tgl" value="<?= date("Y-m-d", strtotime($transaksi["tgl"])) ?>" id="i-tgl" class="input" type="date" placeholder="Tanggal Transaksi" required />
                    </div>

                    <div class="input-group">
                        <label for="i-batas" class="label">Batas Tanggal Transaksi</label>
                        <input name="batas" value="<?= date("Y-m-d", strtotime($transaksi["batas_waktu"])) ?>" id="i-batas" class="input" type="date" placeholder="Batas Tanggal Transaksi" required />
                    </div>
                    
                    <div class="input-group">
                        <label for="i-kasir" class="label">Kasir</label>
                        <select data-value="<?= $transaksi["id_user"] ?>" name="kasir" id="i-kasir" class="input" required>
                        <option value="">--- Belum dipilih ---</option>
                        <?php
                        foreach ($users as $option) {
                            echo "<option value='{$option['id']}'>{$option['nama']}</option>";
                        }
                        ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="i-extra" class="label">Biaya Tambahan</label>
                        <input name="extra" value="<?= $transaksi["biaya_tambahan"] ?>" id="i-extra" class="input" data-number-only type="text" placeholder="Biaya tambahan" required />
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