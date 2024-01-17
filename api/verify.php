<?php

$_SAFE = true;
require_once "../conn.php";

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
$password = $payload["password"];

if (!password_verify($password, $_SESSION["auth"]->user["password"])) {
    exit(json_encode([
        "status" => "failed",
    ]));
}

exit(json_encode([
    "status" => "ok",
]));