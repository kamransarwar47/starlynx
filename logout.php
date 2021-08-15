<?
    include("common/common.start.php");

    // User Info
    $userInfo = getSignedUserInfo($ss);

    define("MODULE_ID", "AUTH");
    system_log(LOGOUT, "User logged out.", $userInfo["id"]);
    logout($ss);
    setMessage("Session closed, it's now safe to close this window.", true);
    unset($_SESSION["loginNoticeShow"]);

    include("common/common.end.php");

    header("Location: index.php");
    exit;
?>