<?
    define("MODULE_ID", "ACT004");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($title) && !empty($head_account)) {
                if (empty($master_id)) {
                    $master_id = $head_account;
                }
                /* KAMRAN UDPATES */
                $sql_check = "SELECT id FROM accounts WHERE title LIKE \"" . mysql_real_escape_string($title) . "\" AND master_id = " . mysql_real_escape_string($master_id);
                $result_check = mysql_query($sql_check, $conn) or die(mysql_error());
                if (mysql_num_rows($result_check) > 0) {
                    setMessage("Title already exists in this account.");
                    system_log(CREATE, "Operation failed. [Reason: Title already exists in this account.]", $userInfo["id"]);
                } else {
                    $sql = "insert into
                            accounts (
                                title, master_id, created, created_by
                            ) values (
                                \"" . mysql_real_escape_string($title) . "\",
                                \"" . mysql_real_escape_string($master_id) . "\",
                                NOW(),
                                \"" . $userInfo["id"] . "\"
                            )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title has been added successfully.", true);
                    system_log(CREATE, "$title created.", $userInfo["id"]);
                    unset($cmd, $title, $master_id);
                }
                /* KAMRAN UDPATES */
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($title) && !empty($head_account)) {
                if (empty($master_id)) {
                    $master_id = $head_account;
                }

                /* KAMRAN UDPATES */
                $sql_check = "SELECT id FROM accounts WHERE title LIKE \"" . mysql_real_escape_string($title) . "\" AND master_id = " . mysql_real_escape_string($master_id) . " AND id != " . mysql_real_escape_string($id);
                $result_check = mysql_query($sql_check, $conn) or die(mysql_error());
                if (mysql_num_rows($result_check) > 0) {
                    setMessage("Title already exists in this account.");
                    system_log(CREATE, "Operation failed. [Reason: Title already exists in this account.]", $userInfo["id"]);
                } else {
                    $sql = "update
                            accounts
                        set
                            title=\"" . mysql_real_escape_string($title) . "\",
                            master_id=\"" . mysql_real_escape_string($master_id) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title has been updated successfully.", true);
                    system_log(MODIFY, "$title updated.", $userInfo["id"]);
                    unset($cmd, $id, $title, $master_id);
                }

            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from accounts where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id        = $rs["id"];
                        $title     = $rs["title"];
                        $master_id = $rs["master_id"];
                    }

                    $cmd = "UPDATE";
                    system_log(MODIFY, "$id loaded for editing.", $userInfo["id"]);
                } else {
                    setMessage("Record not found.");
                    system_log(MODIFY, "Operation failed. [Reason: Record not found.]", $userInfo["id"]);
                }
            } else {
                setMessage("Nothing to load...");
                system_log(MODIFY, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // Check transactions
                $sql = "select id from transactions where account_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // Check transaction details
                    $sql = "select id from transactions_details where account_id=\"" . mysql_real_escape_string($id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Check sub accounts
                        $sql = "select id from accounts where master_id=\"" . mysql_real_escape_string($id) . "\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows <= 0) {

                            // Check system accounts
                            $system_accounts = array(
                                $CFG_ACCOUNT_CUSTOMERS,
                                $CFG_ACCOUNT_VENDORS,
                                $CFG_ACCOUNT_DEALERS,
                                $CFG_ACCOUNT_LANDOWNERS,
                                $CFG_ACCOUNT_INVESTORS,
                                $CFG_ACCOUNT_PARTNERS
                            );
                            if (!in_array($id, $system_accounts)) {

                                // Delete Account
                                $sql = "delete from accounts where id=\"" . mysql_real_escape_string($id) . "\"";
                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                setMessage("Record has been deleted.", true);
                                system_log(DELETE, "$id deleted.", $userInfo["id"]);
                                unset($id);

                            } else {
                                setMessage("Record can not be deleted. (Cannot Remove System Accounts)");
                                system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Cannot Remove System Accounts)]", $userInfo["id"]);
                            }

                        } else {
                            setMessage("Record can not be deleted. (Remove Sub Accounts)");
                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Sub Accounts)]", $userInfo["id"]);
                        }

                    } else {
                        setMessage("Record can not be deleted. (Remove Transactions)");
                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                    }

                } else {
                    setMessage("Record can not be deleted. (Remove Transactions)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                }

            } else {
                setMessage("Nothing to load...");
                system_log(DELETE, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }

    if (empty($cmd)) {
        $cmd = "ADD";
    }
?>

<table border="0" width="750" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Accounts</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <form action="index.php" method="post" autocomplete="off">
                <input type="hidden" name="ss" value="<?= $ss ?>"/>
                <input type="hidden" name="mod" value="<?= $mod ?>"/>
                <input type="hidden" name="cmd" value="<?= $cmd ?>"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <p class="box_title">Manage Accounts</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Head Account</strong><br/>
                                    <select name="head_account" onChange="javascript: location.href='index.php?ss=<?= $ss ?>&mod=<?= $mod ?>&head_account='+this.value;">
                                        <option value="">--Select--</option>
                                        <?
                                            $sql = "select * from accounts where master_id='0'";
                                            $result = mysql_query($sql, $conn) or die(mysql_error());

                                            while ($rs = mysql_fetch_array($result)) {
                                                $selected = "";

                                                if ($head_account == $rs["id"]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $rs["id"] . "\" $selected>" . $rs["title"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <strong>Title</strong><br/>
                                    <input type="text" size="30" name="title" id="title" maxlength="100" value="<?= $title ?>"/>
                                </p>
                                <p>
                                    <strong>Parent Account</strong><br/>
                                    <?
                                        if (!empty($head_account)) {
                                            $actArray = createAccountsArray($head_account);
                                            //print_r($actArray);
                                            echo createPageSelectHTML("master_id", "master_id", $actArray, $master_id);
                                        } else {
                                            echo "<span style='color: #ff0000;'>Please select head account first.</option>";
                                        }
                                    ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" value="Submit"/>
                    </p>
                <? } ?>
            </form>

            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="80%">Title</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $canModify = checkPermission($userInfo["id"], MODIFY);
                        $canDelete = checkPermission($userInfo["id"], DELETE);

                        if (!empty($head_account)) {
                            echo createPageHTML(createAccountsArray($head_account), $canModify, $canDelete);
                        } else {
                            echo "<span style='color: #ff0000;'>Please select head account first.</option>";
                        }
                    ?>
                </table>
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