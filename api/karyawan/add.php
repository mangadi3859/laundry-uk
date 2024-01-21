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

if (!isPermited([Privilege::$ADMIN])) {
    http_response_code(403);
    exit(json_encode([
        "message" => "Forbidden"
    ]));
}

$payload = json_decode(file_get_contents("php://input"), true);
$nama = $payload["nama"] ?? NULL;
$username = $payload["username"] ?? NULL;
$email = $payload["email"] ?? NULL;
$password = @$payload["password"] ? $payload["password"] : $DEFAULT_PW;
$role = $payload["role"] ?? NULL;
$outlet = $payload["outlet"] ?? NULL;

if (!@$nama || !@$username || !@$email || !@$password || !@$role || !@$outlet) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Data tidak lengkap"
    ]));
}

$username = strtolower($username);
$email = strtolower($email);

if (!in_array($role, [Privilege::$ADMIN, Privilege::$OWNER, Privilege::$KASIR])) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Role tidak valid"
    ]));
}

$sql = "SELECT 
SUM(CASE WHEN username = '$username' THEN 1 ELSE 0 END) AS username,
SUM(CASE WHEN email = '$email' THEN 1 ELSE 0 END) AS email
FROM `tb_user`;";
$user = query($sql);
if ($user[0]["username"]) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Username ini tidak tersedia"
    ]));
}

if ($user[0]["email"]) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Email ini sudah terdaftar"
    ]));
}

$sql = "SELECT id FROM tb_outlet WHERE id = '$outlet'";
$outletData = query($sql);

if (empty($outletData)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak terdaftar"
    ]));
}


$hash = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO tb_user VALUE ('', '$email', '$nama', '$username', '$hash', '$outlet', '$role')";

query($sql);

logger("INSERT USER", "({$_SESSION['auth']->user['nama']}) just created a new record");
exit(json_encode([
    "status" => "ok",
]));