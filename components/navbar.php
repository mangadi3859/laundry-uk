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
        $role = $_SESSION["auth"]->user["role"];
        $initial = getInitial($nama);
        $avatarImg = file_exists($AVATAR_PATH . "/av{$_SESSION["auth"]->user["id"]}.jpg") ? "$ROOT_PATH/public/tmp/av{$_SESSION["auth"]->user["id"]}.jpg" : "";

        echo <<<at
        <div class="user">
            <span class="user-name">
                <div class="name-simple">$nama</div>
                <i class="fa-angle-down fa" style="font-size: .7rem; margin-left: .25rem"></i>

                <div class="user-collapse">
                    <div style="text-transform: capitalize">$role</div>    
                    <a href="$ROOT_PATH/logout.php" class="user-logout">Logout <i style="margin-left: auto;" class="fa-right-from-bracket fas"></i></a>
                </div>
            </span>
            <div class="avatar">
                $initial
                <img onerror="this.onerror=null;this.remove()" src="$avatarImg">
            </div>
        </div>
        at;
    }
    ?>
</nav>