<?php
$_SAFE = true;
require_once "conn.php";

if (!Auth::isAuthenticated()) {
    header("Location: login.php");
}

$_SESSION["auth"]->logout();
header("Location: ./login.php");