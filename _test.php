<?php
$_SAFE = true;
require_once "functions.php";

// session_destroy();
echo password_hash("default_pas", PASSWORD_BCRYPT);