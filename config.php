<?php

$LOGGER_PATH = dirname(__FILE__) . "/LOGGER.txt";

$ROOT_PATH = "/laundry-uk";

class DashboardTab
{
    public static $DASHBOARD = "dashboard";
    public static $PENDAFTARAN = "pendaftaran";
    public static $PAKET = "paket";
    public static $MEMBER = "member";
    public static $OUTLET = "outlet";
    public static $TRANSAKSI = "transaksi";
    public static $STATUS = "status";
    public static $KARYAWAN = "karyawan";
}

class Privilege
{
    public static $KASIR = "kasir";
    public static $ADMIN = "admin";
    public static $OWNER = "owner";
}