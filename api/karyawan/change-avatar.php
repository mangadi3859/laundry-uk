<?php
$_SAFE = true;
require_once "../../conn.php";
require_once "../../functions.php";
require_once "../../config.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    exit(json_encode([
        "message" => "Method Not Allowed"
    ]));
}

if (!Auth::isAuthenticated()) {
    http_response_code(401);
    exit(json_encode([
        "message" => "Unauthenticated"
    ]));
}

$file = $_FILES["avatar"] ?? NULL;
$del = $_POST["del"] ?? NULL;

if (@$del) {
    unlink($AVATAR_PATH . "/av{$_SESSION["auth"]->user['id']}.jpg");
    logger("DELETE AVATAR", "({$_SESSION['auth']->user['nama']}) just deleted their avatar");
    exit(json_encode([
        "status" => "ok",
    ]));
}

if (!@$file) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Avatar tidak ditemukan",
    ]));
}

if ($file["size"] / 1000 / 1000 > 1.5) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Avatar tidak bisa melebihi 1.5MB",
    ]));
}

move_uploaded_file($file["tmp_name"], $AVATAR_PATH . "/av{$_SESSION["auth"]->user['id']}.jpg");

echo json_encode([
    "status" => "ok",
]);
logger("UPDATE AVATAR", "({$_SESSION['auth']->user['nama']}) just modified their avatar");
