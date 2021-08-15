<?
    define("MODULE_ID", "SYS008");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName = $_dbServer;
        $mysqlUserName = $_dbUser;
        $mysqlPassword = $_dbPassword;
        $DbName        = $_dbName;
        $backup_name   = "DATABASE_BACKUP_STAR_DEV_" . date('Y-m-d', time()) . ".sql";

        /*
         * Database Backup
         * 5th parameter -> $tables -> array('table_1', 'table_2','table_3');
         * */
        Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName, $tables = false, $backup_name);
    }
?>