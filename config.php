<?php

$LOGGER_PATH = dirname(__FILE__) . "/LOGGER.txt";
$ERROR_LOG_PATH = dirname(__FILE__) . "/ERROR.txt";

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

class Gender
{
    public static $L = "L";
    public static $P = "P";
}

class PaketType
{
    public static $KAOS = "kaos";
    public static $SELIMUT = "selimut";
    public static $BED_COVER = "bed_cover";
    public static $KILOAN = "kiloan";
    public static $LAIN = "lain";
}

#$DEFAULT_PASS (default_pas) = $2y$10$jra2FItAFyz2.BYDigZWBusR6Vz.iqetX/.1BG9AK.xiylgOfcp8W
$DEFAULT_PW = "laundry#1234";