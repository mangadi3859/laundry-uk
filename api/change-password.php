<?php

$_SAFE = true;
require_once "../conn.php";
require_once "../functions.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    exit(json_encode([
        "error" => "Method Not Allowed"
    ]));
}

if (!Auth::isAuthenticated()) {
    http_response_code(401);
    exit(json_encode([
        "error" => "Unauthenticated"
    ]));
}

$payload = json_decode(file_get_contents("php://input"), true);
$oldPw = $payload["old_pw"] ?? NULL;
$password = $payload["password"] ?? NULL;


if (!@$password) {
    exit(json_encode([
        "status" => "failed",
    ]));
}

if (!password_verify($oldPw, $_SESSION["auth"]->user["password"])) {
    exit(json_encode([
        "status" => "failed",
    ]));
}


$hash = password_hash($password, PASSWORD_BCRYPT);
$sql = "UPDATE tb_user SET password = '$hash' WHERE id = '{$_SESSION["auth"]->user["id"]}';";

query($sql);
logger("UPDATE", "({$_SESSION["auth"]->user["nama"]}) just changed their password");

exit(json_encode([
    "status" => "ok",
]));
