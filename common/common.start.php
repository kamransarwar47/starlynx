<?PHP
    ini_set("display_errors", "Off");
    error_reporting(E_ALL & ~E_NOTICE);	//

    session_start();

    // Common reqired files: Start
    include("conf/db.php");
    include("conf/global.php");
    include("conf/sms.php");
    include("common/connOpen.php");
    include("lib/common.php");
    include("lib/sms.php");

    // Load settings from database
    $sql = "select var_name, var_value from system_settings order by id";
    $result = mysql_query($sql, $conn) or die(mysql_error());

    while($rs = mysql_fetch_array($result))
    {
        $$rs["var_name"] = $rs["var_value"];
    }

    // Fetch all variables from QueryString
    foreach ($_REQUEST as $varname => $varvalue)
    {
        $$varname  = $varvalue;
    }
?>