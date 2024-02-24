<?php
$_SAFE = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404</title>

    <!-- CSS -->
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/login.css">
    
    <!-- JS -->
</head>
<body>
    <main id="main">
        <div class="login-container" style="flex-direction: column; align-items: start">
            <h1 style="font-size: var(--type-hero-lg)">Error <span style="color: var(--accent-600)">404</span></h1>
            <p style="font-size: var(--type-lg)">Not Found</p>     
            <p style="font-size: var(--type-base); margin-top: 1rem;">While trying to access <u style="color: var(--accent-600); font-weight: bold"><?= $_GET["r"] ?></u></p>
        </div>
    </main>
    
</body>
</html>