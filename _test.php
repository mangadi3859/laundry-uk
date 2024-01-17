<?php
$_SAFE = true;
require_once "functions.php";

session_destroy();
echo password_hash("kmzwa8awaa", PASSWORD_BCRYPT);