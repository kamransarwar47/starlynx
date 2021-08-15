<?
    if(!checkPermission($userInfo["id"], ACCESS)) {
        system_log(ACCESS, "Access denied.", $userInfo["id"]);
        setMessage("Access denied.");
        include("common/403.php");
        exit;
    }
?>