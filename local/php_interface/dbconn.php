<?
//date_default_timezone_set("Europe/Moscow");
date_default_timezone_set("Etc/GMT-3");
define("DBPersistens", false);
$DBType = "mysql";
$DBHost = "localhost";
$DBLogin = "texenergo";
$DBPassword = "AiHuegNav3";
$DBName = "texenergo";
// $DBType = "mysql";
// $DBHost = "127.0.0.1:4040";
// $DBLogin = "texenergo";
// $DBPassword = "AiHuegNav3";
// $DBName = "texenergo";


$DBDebug = false;
$DBDebugToFile = false;

define("MYSQL_TABLE_TYPE", "INNODB");

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0755);
@umask(~BX_DIR_PERMISSIONS);
@ini_set("memory_limit", "1024M");
define("BX_DISABLE_INDEX_PAGE", true);
if(!(defined("CHK_EVENT") && CHK_EVENT===true)){
    define("BX_CRONTAB_SUPPORT", true);
}
?>