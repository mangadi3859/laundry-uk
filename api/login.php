<?php
$_SAFE = true;

require_once "../conn.php";
require_once "../functions.php";

if (Auth::isAuthenticated())
    exit(header("Location: ../"));

if ($_SERVER["REQUEST_METHOD"] != "POST")
    exit(header("Location: ../login.php"));

$username = $_POST["username"] ?? NULL;
$password = $_POST["password"] ?? NULL;

if (!@$username || @!$password) {
    exit(header("Location: ../login.php?err=Data tidak lengkap"));
}

$username = strtolower($username);

$escape = $conn->escape_string($username);
$sql = "SELECT * FROM tb_user WHERE username = '$escape' OR email = '$escape'";

$dataUser = query($sql)[0];

if (!@$dataUser) {
    exit(header("Location: ../login.php?err=User tidak ditemukan"));
}

if (!password_verify($password, $dataUser["password"])) {
    exit(header("Location: ../login.php?err=Password salah"));
}

$_SESSION["auth"] = new Auth($username, $password);
header("Location: ../");

