<?php
    // Keyword: TO
    // Syntax: TO <user_name> <message>
    // Purpose: Delivers SMS to user's inbox

    $msgparts = extractMessage($message, 1);

    $msgparts["PARAMS"][0] = strtolower($msgparts["PARAMS"][0]);

    if($msgparts["PARAMS"][0] == "su" || $msgparts["PARAMS"][0] == "waqas") {
        $msgparts["PARAMS"][0] = "su";
    }

    $recipient_id = getUserId($msgparts["PARAMS"][0]);
    $sender_id = getUserId(getUserNameByMobile($sender));

    if(!empty($sender_id)) {
        $subject = "SMS Received from ".getUserFullName($sender_id);
    } else {
        $subject = "SMS Received from $sender";
    }

    $message = $msgparts["MESSAGE"];
    $sql = "INSERT INTO
                users_messages(
                    recipient_id, sender_id, subject, message, priority, status, message_type, msisdn, created
                ) VALUES(
                    \"$recipient_id\",
                    \"$sender_id\",
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
    system_log(CREATE, "New message sent.", $sender_id);
?>