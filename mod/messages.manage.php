<?
    define("MODULE_ID", "SYS005");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
var siteUrl = "<?=$_siteRoot?>";

function getMessage(id) {
    $('#sysMsg').html("<strong style='color: #ff0000;'>Loading...</strong>");

    $.ajax({
      url: siteUrl + "ajax.getMessage.php?ss=<?=$ss?>&box=<?=$box?>&message_id=" + id,
      success: function(data) {
          $('#msgView').html(data);
      }
    });
}
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Messages</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?=showError()?>
                <p class="box_title" style="float: left; width: 400px;">
                    Messages
                </p>

                <span style="float: right; text-align: right; margin-top: 20px;">
                        <? if($box == "SENT") { ?>
                            <a href="index.php?ss=<?=$ss?>&mod=<?=$mod?>">Received</a> |
                            <strong>Sent</strong> |
                        <? } else { ?>
                            <strong>Received</strong> |
                            <a href="index.php?ss=<?=$ss?>&mod=<?=$mod?>&box=SENT">Sent</a> |
                        <? } ?>
                        <a href="index.php?ss=<?=$ss?>&mod=messages.add">New Message</a> |
                        <a href="index.php?ss=<?=$ss?>&mod=messages.add&message_type=SMS">New SMS</a>
                    </span>

                <? if(checkPermission($userInfo["id"], VIEW)) { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="padding-top: 10px;">
                        <tr id="listhead">
                            <td width="10%">&nbsp;</td>
                            <? if($box == "SENT") { ?>
                                <td width="20%">To</td>
                            <? } else { ?>
                                <td width="20%">From</td>
                            <? } ?>
                            <td width="50%">Subject</td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                </table>
                <div id="messages">
                       <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="">
                        <?
                            if($box == "SENT") {
                                $sql = "select * from users_messages where sender_id='".$userInfo["id"]."' order by created desc";
                            } else {
                                $sql = "select * from users_messages where recipient_id='".$userInfo["id"]."' order by created desc";
                            }

                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);

                            if($numrows > 0) {
                                while($rs = mysql_fetch_array($result)) {
                                    $id = $rs["id"];
                                    $sender_id = $rs["sender_id"];
                                    $recipient_id = $rs["recipient_id"];
                                    $subject = stripslashes($rs["subject"]);
                                    //$message = stripslashes($rs["message"]);
                                    $priority = $rs["priority"];
                                    $status = $rs["status"];
                                    $message_type = $rs["message_type"];
                                    $msisdn = $rs["msisdn"];
                                    $created = $rs["created"];

                                    $style = "cursor: pointer; ";

                                    if($status == "UNREAD" && $message_type != "SMS") {
                                        $style .= "font-weight: bold;";
                                    }

                                    echo "<tr style='$style' onClick='javascript: getMessage($id);'>";
                                    echo "<td width='10%'>";

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

                                    echo "</td>";
                                    echo "<td width='20%'>";

                                    if($message_type == "SMS") {
                                        if(!empty($sender_id)) {
                                            if($box == "SENT") {
                                                echo getUserFullName($recipient_id);
                                            } else {
                                                echo getUserFullName($sender_id);
                                            }
                                        } else {
                                            echo $msisdn;
                                        }
                                    } else {
                                        if($box == "SENT") {
                                            echo getUserFullName($recipient_id);
                                        } else {
                                            echo getUserFullName($sender_id);
                                        }
                                    }

                                    echo "</td>";
                                    echo "<td width='50%'>$subject</td>";
                                    echo "<td align='right' width='20%'>".date("d/m/Y h:ia", strtotime($created))."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>";
                                echo "<td align='center' colspan='4'>No message found.</td>";
                                echo "</tr>";
                            }
                        ?>
                        </table>
                   </div>
                   <div id="msgView">
                           <p style='text-align: center;' id='sysMsg'>
                           Please select a message.
                        </p>
                   </div>
            <? } ?>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>

<?
    if(!empty($message_id)) {
        echo "<script type='text/javascript'>\n";
        echo "getMessage($message_id);\n";
        echo "</script>\n";
    }
?>
