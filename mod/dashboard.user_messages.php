<strong class="box_title">Messages</strong>
<table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                        <tr id="listhead">
                            <td width="10%">&nbsp;</td>
                            <td width="20%">From</td>
                            <td width="45%">Subject</td>
                            <td width="25%">&nbsp;</td>
                        </tr>
                        <?
                            $sql = "select * from users_messages where recipient_id='".$userInfo["id"]."' and status='UNREAD' order by created desc";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);

                            if($numrows > 0) {
                                while($rs = mysql_fetch_array($result)) {
                                    $id = $rs["id"];
                                    $sender_id = $rs["sender_id"];
                                    $subject = $rs["subject"];
                                    $message = $rs["message"];
                                    $priority = $rs["priority"];
                                    $status = $rs["status"];
                                    $message_type = $rs["message_type"];
                                    $msisdn = $rs["msisdn"];
                                    $created = $rs["created"];

                                    $style = "cursor: pointer; ";

//                                    if($status == "UNREAD") {
//                                        $style = "font-weight: bold;";
//                                    }

                                    echo "<tr style='$style' onClick=\"javascript: location.href='index.php?ss=$ss&mod=messages.manage&message_id=$id';\">";
                                    echo "<td>";

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
                                    echo "<td>";

                                    if(empty($sender_id)) {
                                        echo $msisdn;
                                    } else {
                                        echo getUserFullName($sender_id);
                                    }

                                    echo "</td>";
                                    echo "<td style='$style'>$subject</td>";
                                    echo "<td align='right'>".date("d/m/Y h:ia", strtotime($created))."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>";
                                echo "<td align='center' colspan='4'>No new message, <a href='index.php?ss=$ss&mod=messages.manage'>Click here</a> to see all messages.</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
<br />