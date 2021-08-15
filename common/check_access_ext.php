<?
    if(!checkPermission($userInfo["id"], ACCESS)) {
        system_log(ACCESS, "Access denied.", $userInfo["id"]);
        setMessage("Access denied.");
        header("Location: index.php?ss=$ss");
        exit;
    }
?>