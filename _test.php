<?php
$_SAFE = true;
require_once "conn.php";
require_once "functions.php";
require_once "config.php";


echo password_verify($DEFAULT_PW, '$2y$10$dO0ihzwtlNvADNsqKlo/.O/uLzbWe4fXzDT2CTadFL/3sVE3VU.WC') ? "Benar" : "Salah";
echo "<br/>";
echo calculateDiscount(12);
echo "<br/>";
echo $invoice = "INV" . substr("000" . 1, -3) . "-" . date("y") . strtoupper(Auth::generateToken(7));
// session_destroy();
// echo password_hash("default_pas", PASSWORD_BCRYPT);

echo base64_encode(file_get_contents($AVATAR_PATH . "/av{$_SESSION["auth"]->user["id"]}.jpg"));
