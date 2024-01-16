<?php
$_SAFE = true;
require_once "../conn.php";
require_once "../config.php";

$_DASHBOARD = DashboardTab::$DASHBOARD;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryIna - Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">

    <!-- JS -->
    <script src="../public/lib/sweatalert/sweatalert.js" defer></script>
</head>
<body>
    <?php include "../components/sidebar.php" ?>
    <main id="main">
        <?php include "../components/navbar.php" ?>
    </main>
</body>
</html>