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