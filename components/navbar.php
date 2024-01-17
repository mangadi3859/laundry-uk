<?php
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";

internalOnly();
?>

<link rel="stylesheet" href="<?= $ROOT_PATH ?>/public/css/navbar.css">
<nav id="nav">
    <div class="tab"><?= strCapitalize($_DASHBOARD) ?></div>
    <div class="user">
        <span>
            <?= $_SESSION["auth"]->user["nama"] ?>
        </span>
        <div class="avatar">
            <?= getInitial($_SESSION["auth"]->user["nama"]) ?>
        </div>
    </div>
</nav>