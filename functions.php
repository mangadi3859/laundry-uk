<?php
require_once "config.php";

function internalOnly()
{
    global $_SAFE;

    if (!@$_SAFE) {
        exit("<h1>Forbidden</h1>");
    }
}

internalOnly();


function logger(string $name, string $message): void
{
    global $LOGGER_PATH;
    $currentLog = "";
    if (file_exists($LOGGER_PATH))
        $currentLog = file_get_contents($LOGGER_PATH);

    $datetime = date("Y/m/d h:i A");
    file_put_contents($LOGGER_PATH, $currentLog . "[$datetime] [$name]: $message\n");
}

function getInitial(string $name): string
{
    $split = explode(" ", $name);
    return strtoupper($split[0][0] . (sizeof($split) > 1 ? $split[sizeof($split) - 1][0] : ""));
}

function strCapitalize(string $str): string
{
    return strtoupper($str[0]) . substr($str, 1);
}


function permitAccess(array $roles, string $redirect)
{
    if (!in_array($_SESSION["auth"]->user["role"], $roles))
        exit(header("Location: $redirect"));

}