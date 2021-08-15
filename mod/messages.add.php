<?
    define("MODULE_ID", "SYS004");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if(empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    if(checkPermission($userInfo["id"], CREATE)) {
        if($cmd == "ADD") {
            if(!empty($recipient_id) && !empty($subject) && !empty($message) && !empty($priority) && !empty($message_type)) {
                if($message_type == "SMS") {
                    $msisdn = prepareMSISDN($recipient_id);
                    $recipient_id = "NULL";
                    $subject .= " $msisdn";
                }

                $sql = "INSERT INTO
                            users_messages(
                                recipient_id, sender_id, subject, message, priority, status, message_type, msisdn, created
                            ) VALUES(
                                \"".mysql_real_escape_string($recipient_id)."\",
                                \"".mysql_real_escape_string($userInfo["id"])."\",
                                \"".mysql_real_escape_string($subject)."\",
                                \"".mysql_real_escape_string($message)."\",
                                \"".mysql_real_escape_string($priority)."\",
                                \"UNREAD\",
                                \"".mysql_real_escape_string($message_type)."\",
                                \"".mysql_real_escape_string($msisdn)."\",
                                NOW()
                            )";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                if($message_type == "SMS") {
                    $sender = getUserFullName($userInfo["id"]);
                    $sms = "$message\nFrom: $sender";
                    @send_sms($sender, $msisdn, $sms);
                }

                setMessage("Message has been sent successfully.", true);
                system_log(CREATE, "New message sent.", $userInfo["id"]);
                unset($cmd, $recipient_id, $subject, $message, $priority, $message_type, $msisdn);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if($cmd == "REPLY" || $cmd == "FORWARD") {
            $sql = "select * from users_messages where id='$message_id'";
            $result = mysql_query($sql, $conn) or die(mysql_error());
            $numrows = mysql_num_rows($result);

            if($numrows > 0) {
                system_log(ACCESS, "Message loaded for $cmd.", $userInfo["id"]);

                while($rs = mysql_fetch_array($result)) {
                    $id = $rs["id"];
                    $sender_id = $rs["sender_id"];
                    $recipient_id = $rs["recipient_id"];
                    $subject = stripslashes($rs["subject"]);
                    $message = stripslashes($rs["message"]);
                    $priority = $rs["priority"];
                    $status = $rs["status"];
                    $message_type = $rs["message_type"];
                    $msisdn = $rs["msisdn"];
                    $created = $rs["created"];
                }

                if($message_type == "SMS") {
                    $recipient_id = $msisdn;

                    if($cmd == "REPLY") {
                        $message = "";
                    }
                } else {
                    if($cmd == "REPLY") {
                        $subject = "Re: ".$subject;
                        $message = "\n\n\n*** On ".date("d/m/Y h:ia", strtotime($created)).", ".getUserFullName($sender_id)." wrote: ***\n".$message;
                        $recipient_id = $sender_id;
                    }

                    if($cmd == "FORWARD") {
                        $subject = "Fwd: ".$subject;
                        $fwdmsg = "\n\n\n---------- Forwarded message ----------\n";
                        $fwdmsg .= "From: ".getUserFullName($sender_id)."\n";
                        $fwdmsg .= "Date: ".date("d/m/Y h:ia", strtotime($created))."\n";
                        $fwdmsg .= "Subject: ".$subject."\n";
                        $fwdmsg .= "To: ".getUserFullName($recipient_id)."\n\n";
                        $fwdmsg .= $message;

                        $message = $fwdmsg;
                    }
                }
            }

            $setFocus = true;
            $cmd = "";
        }
    }

    if(empty($cmd)) {
        $cmd = "ADD";
    }
?>
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
                <form action="index.php" method="post">
                <input type="hidden" name="ss" value="<?=$ss?>" />
                <input type="hidden" name="mod" value="<?=$mod?>" />
                <input type="hidden" name="cmd" value="<?=$cmd?>" />
                <p class="box_title">Send Message</p>
                <? if(checkPermission($userInfo["id"], CREATE)) { ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td width="60%" valign="top">
                            <p>
                                <strong>From</strong><br />
                                <?=$userInfo["full_name"]?>
                            </p>
                            <p>
                                <strong>To</strong><br />
                                <?
                                    if($message_type == "SMS") {
                                ?>
                                    <input type="text" name="recipient_id" id="recipient_id" size="30" maxlength="20" value="<?=prepareMSISDN($msisdn)?>" />
                                <? } else { ?>
                                    <select name="recipient_id" id="recipient_id">
                                    <option value="">-- Select --</option>
                                    <?
                                        $sql = "select id, full_name from users where id!='".$userInfo["id"]."' and status='ACTIVE'";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());

                                        while($rs = mysql_fetch_array($result)) {
                                            $selected = "";

                                            if($recipient_id == $rs["id"]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value='".$rs["id"]."' $selected>".$rs["full_name"]."</option>";
                                        }
                                    ?>
                                    </select>
                                <? } ?>
                            </p>

                            <? if($message_type != "SMS") { ?>
                                <p>
                                    <strong>Subject</strong><br />
                                    <input type="text" size="70" name="subject" id="subject" maxlength="255" value="<?=$subject?>" />
                                </p>
                            <? } ?>
                            <p>
                                <strong>Message</strong><br />
                                <textarea name="message" id="message" cols="70" rows="7"><?=$message?></textarea>
                            </p>
                        </td>
                        <td width="40%" valign="top">
                            <? if($message_type != "SMS") { ?>
                                <p>
                                    <strong>Priority</strong><br />
                                    <select name="priority" id="priority">
                                    <?
                                        $prios = array('MEDIUM', 'LOW', 'HIGH');

                                        for($i=0; $i<sizeof($prios); $i++) {
                                            $selected = "";

                                            if($priority == $prios[$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value='".$prios[$i]."' $selected>".$prios[$i]."</option>";
                                        }
                                    ?>
                                    </select>
                                </p>

                                <p>
                                    <strong>Type</strong><br />
                                    <select name="message_type" id="message_type">
                                    <?
                                        $types = array('NORMAL', 'ALERT', 'NOTICE', 'WARNING');

                                        for($i=0; $i<sizeof($types); $i++) {
                                            $selected = "";

                                            if($message_type == $types[$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value='".$types[$i]."' $selected>".$types[$i]."</option>";
                                        }
                                    ?>
                                    </select>
                                </p>
                            <? } else { ?>
                                <input type="hidden" name="message_type" id="message_type" value="SMS" />
                                <input type="hidden" name="priority" id="priority" value="MEDIUM" />
                                <input type="hidden" name="subject" id="subject" value="SMS To" />
                            <? } ?>
                        </td>
                    </tr>
                </table>
                <p>
                    <input type="submit" value="Submit" />
                </p>
                <? } ?>
                </form>
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
    if($setFocus) {
?>
        <script type="text/javascript">
            $("#message").focus();
        </script>
<?
    }
?>