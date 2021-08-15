<?php
    $subject = "SMS Received from $sender";
    $message = $message;
    $sql = "INSERT INTO
                users_messages(
                    recipient_id, sender_id, subject, message, priority, status, message_type, msisdn, created
                ) VALUES(
                    \"1\",
                    \"0\",
                    \"".mysql_real_escape_string($subject)."\",
                    \"".mysql_real_escape_string($message)."\",
                    \"MEDIUM\",
                    \"UNREAD\",
                    \"SMS\",
                    \"".mysql_real_escape_string($sender)."\",
                    NOW()
                )";
    $result = mysql_query($sql, $conn) or die(mysql_error());

    setMessage("Message has been sent successfully.", true);
    system_log(CREATE, "New message sent.");
?>