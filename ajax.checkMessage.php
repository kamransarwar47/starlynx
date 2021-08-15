<?
    include("common/common.start.php");

    // User Info
    $userInfo = getSignedUserInfo($ss);
    //echo "Hello";

    $sql = "select count(id) as cnt from users_messages where recipient_id='".$userInfo["id"]."' and status='UNREAD' and ajax_notice='N'";
    $result = mysql_query($sql, $conn) or die(mysql_error());

    while($rs = mysql_fetch_array($result)) {
        $msgCnt = $rs["cnt"];
    }

    if($msgCnt > 0) {
        $sql = "update users_messages set ajax_notice='Y' where recipient_id='".$userInfo["id"]."' and status='UNREAD'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        echo "You have got $msgCnt new message(s).";
    }

    include("common/common.end.php");
?>