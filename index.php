<?php
$_SAFE = true;
require_once "conn.php";
require_once "functions.php";

if (!Auth::isAuthenticated()) {
    exit(header("Location: ./login.php"));
}

header("Location: dashboard");

