<?php
require_once 'functions.php';
internalOnly();

date_default_timezone_set("Asia/Makassar");

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "";
$DB_NAME = "laundry";
$EXPIRES = 3600 * 24;

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

function query(string $query): array
{
    global $conn;
    $data = [];
    $res = $conn->query($query);
    // $res->

    if ($conn->error) {
        logger("SQL ERROR", "An error has occur, see ERROR LOG to see the detail");
        logError("SQL ERROR", $conn->error);
        logError("SQL QUERY", $query);
        throw new Exception($conn->error);
    }

    if (!is_object($res) || $res->num_rows <= 0)
        return [];

    while ($value = $res->fetch_assoc()) {
        array_push($data, $value);
    }

    return $data;
}

class Auth
{
    private static string $KEY = "auth";
    public string $token;
    public array $user;
    public function __construct(string $username, string $password = "", string $token = "")
    {
        global $EXPIRES;
        if (session_status() != PHP_SESSION_ACTIVE)
            session_start();

        if (!empty($token)) {
            $sql = "SELECT * FROM auth WHERE token = '$token'";
            $data = query($sql);

            $userSql = "SELECT * FROM tb_user WHERE id = '{$data[0]['id_user']}'";
            $user = query($userSql);

            if (!empty($data) && !empty($user)) {
                $this->token = $token;
                $this->user = $user[0];

                setcookie(self::$KEY, $token, time() + $EXPIRES, "/");
                $this->token = $token;
                $this->user = $user[0];
                return;
            }
        }

        $sql = "SELECT * FROM tb_user WHERE username = '$username' OR email = '$username'";
        $userData = query($sql);

        if (empty($userData) || !password_verify($password, $userData[0]["password"]))
            throw new Exception("Wrong password");

        $hashToken = dechex($userData[0]["id"]) . "." . self::generateToken();

        $sqlLogin = "INSERT INTO auth VALUE ('$hashToken','{$userData[0]['id']}', CURRENT_TIMESTAMP + INTERVAL $EXPIRES SECOND)";
        query($sqlLogin);
        $this->token = $hashToken;
        $this->user = $userData[0];
        setcookie(self::$KEY, $hashToken, time() + $EXPIRES, "/");

        logger("LOGIN", "Someone logged in as ({$userData[0]['nama']})");
    }

    public static function generateToken(int $length = 24): string
    {
        $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQ0123456789";
        $res = "";

        for ($i = 0; $i < $length; $i++) {
            $res .= $str[random_int(0, strlen($str) - 1)];
        }

        return $res;
    }


    public function logout(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE)
            return;

        if (@$this->token) {
            query("DELETE FROM auth WHERE token ='$this->token' OR expires <= CURRENT_TIMESTAMP");
        }

        session_destroy();
        $logActor = $this->user["nama"];
        logger("LOGOUT", "Someone logged out from ($logActor)");
    }

    static function isAuthenticated(): bool
    {
        global $EXPIRES;
        if (!@$_SESSION["auth"] && !@$_SESSION["auth"]->user && !@$_COOKIE["auth"])
            return false;

        if (!@$_SESSION["auth"]) {
            $sql = "SELECT * FROM auth WHERE token = '{$_COOKIE['auth']}'";
            $data = query($sql);

            if (empty($data))
                return false;

            $userSql = "SELECT * FROM tb_user WHERE id = '{$data[0]['id_user']}'";
            $user = query($userSql);

            if (empty($user))
                return false;
            $_SESSION["auth"] = new self($user[0]["username"], "", $_COOKIE["auth"]);
        }

        if (!@$_COOKIE["auth"]) {
            $sql = "SELECT * FROM auth WHERE token = '{$_SESSION['auth']->token}'";
            $data = query($sql);

            $userSql = "SELECT * FROM tb_user WHERE id = '{$data[0]['id_user']}'";
            $user = query($userSql);

            if (empty($data) && empty($user))
                return false;

            setcookie(self::$KEY, $data[0]["token"], time() + $EXPIRES, "/");
        }

        $checkSql = "SELECT * FROM auth WHERE token = '{$_COOKIE['auth']}' AND expires > CURRENT_TIMESTAMP";
        $checkData = query($checkSql);

        return !empty($checkData);
    }
}

session_start();