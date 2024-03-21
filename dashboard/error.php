<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";
require_once "../config.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: $LOGIN_PATH"));
}

permitAccess([Privilege::$ADMIN], "./");
$_DASHBOARD = "ErrorLog";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Error Log</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/log.css">

    <!-- JS -->
    <script src="../public/lib/sweatalert/sweatalert.js" defer></script>
    <script src="../public/js/global.js" defer></script>
</head>

<body>
    <?php include "../components/sidebar.php" ?>
    <div class="main-container">
        <main id="main">
            <?php include "../components/navbar.php" ?>

            <div class="log-container">
                <?php foreach (file($ERROR_LOG_PATH) as $log) {
                    // $split = explode("]:", $log, 2);
                    echo "<div class='log-text'>$log</div>";
                } ?>
            </div>
        </main>
    </div>
</body>

</html>