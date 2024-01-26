<?php
require_once dirname(__FILE__) . "/../conn.php";
require_once dirname(__FILE__) . "/../functions.php";
require_once dirname(__FILE__) . "/../config.php";

internalOnly();

$isAuth = Auth::isAuthenticated();
?>

<link rel="stylesheet" href="<?= $ROOT_PATH ?>/public/css/navbar.css">
<nav id="nav">
    <div class="tab"><?= strCapitalize($_DASHBOARD) ?></div>
    <?php
    if ($isAuth) {
        $nama = $_SESSION["auth"]->user["nama"];
        $initial = getInitial($nama);
        echo <<<at
        <div class="user">
            <span>
                $nama
            </span>
            <div class="avatar">
                $initial
            </div>
        </div>
        at;
    }
    ?>
</nav>