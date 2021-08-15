<?
    include("common/common.start.php");

    define("MODULE_ID", "SYS005");

    // User Info
    $userInfo = getSignedUserInfo($ss);

    if(!empty($message_id)) {
        $sql = "select * from users_messages where id='$message_id'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if($numrows > 0) {
            system_log(ACCESS, "Message loaded for reading.", $userInfo["id"]);

            while($rs = mysql_fetch_array($result)) {
                $id = $rs["id"];
                $sender_id = $rs["sender_id"];
                $recipient_id = $rs["recipient_id"];
                $subject = stripslashes($rs["subject"]);
                $message = stripslashes(nl2br($rs["message"]));
                $priority = $rs["priority"];
                $status = $rs["status"];
                $message_type = $rs["message_type"];
                $msisdn = $rs["msisdn"];
                $created = $rs["created"];
                $updated = $rs["updated"];
            }
        }

        echo "<table border='0' width='100%' cellpadding='0' cellspacing='0' align='center'>";
        echo "<tr>";
        echo "<td style='font-size: 18px; font-weight: bold; padding-bottom: 10px;'>";
        echo $subject;
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='padding-bottom: 10px;'>";
        echo "<strong>";

        if($message_type == "SMS") {
            echo $msisdn;
        } else {
            if($box == "SENT")
            {
                echo getUserFullName($recipient_id);
            } else {
                echo getUserFullName($sender_id);
            }
        }

        echo "</strong><br /><span class='notes' style='margin-right: 10px;'>".date("d/m/Y h:ia", strtotime($created))."</span>";

        if($box == "SENT" && $message_type != "SMS") {
            if($status == "READ") {
                echo "<br /><span class='notes' style='margin-right: 10px;'>Read On: ".date("d/m/Y h:ia", strtotime($updated))."</span>";
            } else {
                echo "<br /><span class='notes' style='margin-right: 10px;'>Read On: Unread</span>";
            }
        }

        switch($priority) {
            case "HIGH":
            echo "<img src='images/prio_high.png' title='High Priority' align='absmiddle' /> ";
            break;
        }

        switch($message_type) {
            case "ALERT":
                echo "<img src='images/alert.gif' title='Alert' align='absmiddle' /> ";
                break;

            case "NOTICE":
                echo "<img src='images/notice.gif' title='NOTICE' align='absmiddle' /> ";
                break;

            case "WARNING":
                echo "<img src='images/warn.png' title='Warning' align='absmiddle' /> ";
                break;

            case "SMS":
                echo "<img src='images/icon_sms.gif' title='SMS' align='absmiddle' /> ";
                break;
        }

        echo "<span style='float: right'>";
        echo "<a href='index.php?ss=$ss&mod=messages.add&message_id=$id&cmd=REPLY'>Reply</a> | ";
        echo "<a href='index.php?ss=$ss&mod=messages.add&message_id=$id&cmd=FORWARD'>Forward</a>";
        echo "</span>";

        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo $message;
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        if($status == "UNREAD" && $box != "SENT") {// && $sender_id != $userInfo["id"]
            $sql = "update users_messages set status='READ', updated=NOW() where id='$message_id'";
            $result = mysql_query($sql, $conn) or die(mysql_error());
        }
    } else {
        echo "Requested message wasn't found.";
    }

    include("common/common.end.php");
?>