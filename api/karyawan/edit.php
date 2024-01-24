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
$id = $payload["id"] ?? NULL;
$nama = $payload["nama"] ?? NULL;
$username = $payload["username"] ?? NULL;
$email = $payload["email"] ?? NULL;
$password = $payload["password"] ?? NULL;
$role = $payload["role"] ?? NULL;
$idOutlet = $payload["outlet"] ?? NULL;


if (!@$id || !@$nama || !@$username || !@$email || !@$role || !@$idOutlet) {
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

$sql = "SELECT id, username, email FROM tb_user WHERE id = '$id'";
$checkUser = query($sql);

if (empty($checkUser)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "ID user tidak terdaftar"
    ]));
}

$sql = "SELECT 
SUM(CASE WHEN username = '$username' AND username != '{$checkUser[0]['username']}' THEN 1 ELSE 0 END) AS username,
SUM(CASE WHEN email = '$email' AND email != '{$checkUser[0]['email']}' THEN 1 ELSE 0 END) AS email
FROM `tb_user`";
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

$sql = "SELECT id FROM tb_outlet WHERE id = '$idOutlet'";
$outletData = query($sql);

if (empty($outletData)) {
    exit(json_encode([
        "status" => "failed",
        "message" => "Outlet tidak terdaftar"
    ]));
}

$hash = @$password ? ", password = '" . password_hash($password, PASSWORD_BCRYPT) . "'" : "";
$sql = "UPDATE tb_user SET nama = '$nama', username = '$username', id_outlet = '$idOutlet', `role` = '$role' $hash WHERE id = '$id'";
query($sql);

logger("UPDATE USER", "({$_SESSION['auth']->user['nama']}) just modified a record with id ($id)");
exit(json_encode([
    "status" => "ok",
]));