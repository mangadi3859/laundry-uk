<?php
$_SAFE = true;
require_once "conn.php";
require_once "functions.php";

if (Auth::isAuthenticated()) {
    exit(header("Location: ./"));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/login.css">

    <!-- JS -->
    <script src="public/lib/sweatalert/sweatalert.js"  defer></script>
    <script src="public/js/login.js"  defer></script>
</head>
<body>
    <main id="main">
        <div class="login-container">
            <div class="login-banner"></div>
            <form action="api/login.php" method="POST" id="loginForm">
                <div class="head">
                    <p>Login</p>
                </div>
                <div class="form-body">
                    <div class="input-group">
                        <label for="i-email">Email</label>
                        <input  id="i-email" class="input" required type="text" name="username" placeholder="Email or Username">
                    </div>
                    <div class="input-group">
                        <label for="i-password">Password</label>
                        <input id="i-password" class="input" required autocomplete="off" type="password" name="password" placeholder="Password">
                        <div class="actions">
                            <button class="btn-primary" type="submit">LOGIN</button>
                            <p class="footer">Powered By LaundryIna</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    
</body>
</html>