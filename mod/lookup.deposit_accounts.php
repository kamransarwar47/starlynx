<?
    define("MODULE_ID", "LUD006");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($account_number) && !empty($account_title)) {
                $sql = "select id from deposit_accounts where account_number=\"" . mysql_real_escape_string($account_number) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    $sql = "insert into
                                deposit_accounts (
                                    account_number, account_title, created, created_by
                                ) values (
                                    \"" . mysql_real_escape_string($account_number) . "\",
                                    \"" . mysql_real_escape_string($account_title) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$account_number has been added successfully.", true);
                    system_log(CREATE, "$account_number created.", $userInfo["id"]);
                    unset($cmd, $account_number, $account_title);
                } else {
                    setMessage("$account_number already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: $account_number already exists.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($account_number) && !empty($account_title)) {
                $sql = "update
                            deposit_accounts
                        set
                            account_number=\"" . mysql_real_escape_string($account_number) . "\",
                            account_title=\"" . mysql_real_escape_string($account_title) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("$account_number has been updated successfully.", true);
                system_log(MODIFY, "$account_number updated.", $userInfo["id"]);
                unset($cmd, $id, $account_number, $account_title);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from deposit_accounts where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id             = $rs["id"];
                        $account_number = $rs["account_number"];
                        $account_title  = $rs["account_title"];
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

                // Transactions check
                $sql = "select id from transactions_details where deposit_in=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // Delete deposit account
                    $sql = "delete from deposit_accounts where id=\"" . mysql_real_escape_string($id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("Record has been deleted.", true);
                    system_log(DELETE, "Deposit Account ID: $id deleted.", $userInfo["id"]);
                    unset($id);

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

<table border="0" width="700" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/lookup.png" width="20" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Lookup Data</td>
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
                <p class="box_title">Deposit Accounts</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Account Number</strong><br/>
                                    <input type="text" size="30" name="account_number" id="account_number" maxlength="100" value="<?= $account_number ?>"/>
                                </p>
                                <p>
                                    <strong>Account Title</strong><br/>
                                    <input type="text" size="30" name="account_title" id="account_title" maxlength="100" value="<?= $account_title ?>"/>
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
                        <td width="40%">Account Number</td>
                        <td width="40%">Account Title</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $sql = "select * from deposit_accounts order by account_number";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);

                            while ($rs = mysql_fetch_array($result)) {
                                $id             = $rs["id"];
                                $account_number = $rs["account_number"];
                                $account_title  = $rs["account_title"];

                                echo "<tr>";
                                echo "<td>$account_number</td>";
                                echo "<td>$account_title</td>";
                                echo "<td>";

                                if ($canModify) {
                                    echo "<a href='index.php?ss=$ss&mod=$mod&id=$id&cmd=EDIT'>Modify</a> | ";
                                }

                                if ($canDelete) {
                                    echo "<a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='3'>No record found.</td>";
                            echo "</tr>";
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