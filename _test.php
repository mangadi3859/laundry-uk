<?php
$_SAFE = true;
require_once "conn.php";
require_once "functions.php";
require_once "config.php";


echo password_verify($DEFAULT_PW, '$2y$10$dO0ihzwtlNvADNsqKlo/.O/uLzbWe4fXzDT2CTadFL/3sVE3VU.WC') ? "Benar" : "Salah";
echo "<br/>";
echo calculateDiscount(12);
echo "<br/>";
echo $invoice = "INV" . substr("000" . 1, -3) . "-" . date("y") . strtoupper(Auth::generateToken(7));
// session_destroy();
// echo password_hash("default_pas", PASSWORD_BCRYPT);


?>


<?php
$i = 0;
foreach ($transaksi as $k => $row) {
    $warning = array_pop($row);
    $idMember = array_pop($row);
    $idOutlet = array_pop($row);
    $member = array_pop($row);


    $row["tgl"] = date("Y/m/d", strtotime($row["tgl"]));
    $row["tgl_bayar"] = date("Y/m/d", strtotime($row["tgl_bayar"]));
    $row["batas_waktu"] = date("Y/m/d", strtotime($row["batas_waktu"]));
    echo "<tr data-outlet='{$idOutlet}' data-member='{$idMember}' data-member-name='{$row['invoice']}'>";
    foreach ($row as $k => $data) {
        if ($k == "id") {
            $i++;
            echo "<td>$i</td>";
            continue;
        }

        if ($k == "invoice" && $warning) {
            echo "<td><a data-warning='{$row['batas_waktu']}' title='Batas waktu terlewat' class='warning fa-triangle-exclamation fas'></a> $data</td>";
            continue;
        }

        if ($k == "batas_waktu" && $warning) {
            echo "<td><a data-warning='{$row['batas_waktu']}' title='Batas waktu terlewat' class='warning fa-triangle-exclamation fas'></a> $data</td>";
            continue;
        }

        if ($k == "tgl_bayar" && $row["dibayar"] != "dibayar") {
            echo "<td>-</td>";
            continue;
        }

        if ($k == "status") {
            $bg = "";
            $text = "";
            $border = "transparent";
            switch ($data) {
                case "baru": {
                    $border = "#004a99";
                    $bg = "#0062cc";
                    $text = "white";
                    // $bg = "#cce5ff";
                    // $text = "#004085";
                    break;
                }
                case "proses": {
                    $border = "#cc9a06";
                    $bg = "#ffc720";
                    $text = "black";
                    // $bg = "#fff3cd";
                    // $text = "#856404";
                    break;
                }
                case "selesai": {
                    $border = "#186429";
                    $bg = "#24963e";
                    $text = "white";
                    break;
                }
                default: {
                    $border = "#565e64";
                    $bg = "#6c757d";
                    $text = "white";
                    break;
                }
            }

            echo "<td><div class='td-info'><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $border;'>$data</span> <button data-info-value='$data' data-status-edit='{$row['id']}' class='status-edit-btn fa fa-pen-to-square'></button></div></td>";
        } else if ($k == "dibayar") {
            $bg = "";
            $text = "";
            $border = "transparent";
            switch ($data) {
                case "belum_dibayar": {
                    $border = "#cc9a06";
                    $bg = "#ffc720";
                    $text = "black";
                    break;
                }
                default: {
                    $border = "#186429";
                    $bg = "#24963e";
                    $text = "white";
                    break;
                }
            }

            echo "<td><div class='td-info'><span style='color: $text; background-color: $bg; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid $border;'>$data</span> <button data-info-value='$data' data-pembayaran-edit='{$row['id']}' class='status-edit-btn fa fa-pen-to-square'></button></div></td>";
        } else
            echo "<td>$data</td>";
    }

    $isAdmin = isPermited([Privilege::$ADMIN]);
    $editBtn = $isAdmin ? "<a href='edit.php?id={$row['id']}' title='EDIT DATA' class='action-btn btn-primary fas fa-gear'></a>" : "";
    echo <<<action
                                <td class="tb-action">
                                    <a href='view.php?id={$row['id']}' title="VIEW DATA" class='action-btn btn-accent fa-eye fas'></a>
                                    $editBtn
                                    <a data-action-delete="{$row['id']}" title="HAPUS DATA" class='action-btn btn-danger fas fa-trash'></a>
                                </td>
                                action;
    echo "</tr>";
}
?>