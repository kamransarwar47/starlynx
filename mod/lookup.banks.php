<?
    define("MODULE_ID", "LUD001");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($title) && !empty($short_title) && !empty($branch_address)) {    // && !empty($account_number)
                $sql = "select id from banks where short_title=\"" . mysql_real_escape_string($short_title) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    $sql = "insert into
                                banks (
                                    title, short_title, branch_code, branch_address, created, created_by
                                ) values (
                                    \"" . mysql_real_escape_string($title) . "\",
                                    \"" . mysql_real_escape_string($short_title) . "\",
                                    \"" . mysql_real_escape_string($branch_code) . "\",
                                    \"" . mysql_real_escape_string($branch_address) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title ($short_title) has been added successfully.", true);
                    system_log(CREATE, "$title ($short_title) created.", $userInfo["id"]);
                    unset($cmd, $title, $short_title, $branch_address, $branch_code);
                } else {
                    setMessage("$short_title already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: $short_title already exists.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($title) && !empty($short_title) && !empty($branch_address)) {
                $sql = "update
                            banks
                        set
                            title=\"" . mysql_real_escape_string($title) . "\",
                            short_title=\"" . mysql_real_escape_string($short_title) . "\",
                            branch_code=\"" . mysql_real_escape_string($branch_code) . "\",
                            branch_address=\"" . mysql_real_escape_string($branch_address) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("$title ($short_title) has been updated successfully.", true);
                system_log(MODIFY, "$title ($short_title) updated.", $userInfo["id"]);
                unset($cmd, $id, $title, $short_title, $branch_address, $branch_code);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from banks where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id          = $rs["id"];
                        $title       = $rs["title"];
                        $short_title = $rs["short_title"];
                        //$account_number = $rs["account_number"];
                        $branch_code    = $rs["branch_code"];
                        $branch_address = $rs["branch_address"];
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
                $sql = "select id from transactions_details where bank_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // Delete bank
                    $sql = "delete from banks where id=\"" . mysql_real_escape_string($id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("Record has been deleted.", true);
                    system_log(DELETE, "Bank ID: $id deleted.", $userInfo["id"]);
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

<table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/lookup.png" width="20" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Lookup Data</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <form action="index.php" method="post">
                <input type="hidden" name="ss" value="<?= $ss ?>"/>
                <input type="hidden" name="mod" value="<?= $mod ?>"/>
                <input type="hidden" name="cmd" value="<?= $cmd ?>"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <p class="box_title">Banks</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Title</strong><br/>
                                    <input type="text" size="30" name="title" id="title" maxlength="100" value="<?= $title ?>"/>
                                </p>

                                <p>
                                    <strong>Short Title</strong> <span class="notes">(i.e: MCB, MBL, HBL, NBP or etc.)</span><br/>
                                    <input type="text" size="30" name="short_title" id="short_title" maxlength="10" value="<?= $short_title ?>"/>
                                </p>

                                <!-- <p>
                                <strong>Account Number</strong><br />
                                <input type="text" size="30" name="account_number" id="account_number" maxlength="100" value="<?= $account_number ?>" />
                            </p>  -->
                            </td>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Branch Code</strong> <span class="notes">(Optional)</span><br/>
                                    <input type="text" size="30" name="branch_code" id="branch_code" maxlength="10" value="<?= $branch_code ?>"/>
                                </p>

                                <p>
                                    <strong>Branch Address</strong><br/>
                                    <input type="text" size="30" name="branch_address" id="branch_address" maxlength="255" value="<?= $branch_address ?>"/>
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
                        <td width="55%">Title</td>
                        <td width="5%">Short</td>
                        <td width="20%">A/c #</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $sql = "select * from banks order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);

                            while ($rs = mysql_fetch_array($result)) {
                                $id             = $rs["id"];
                                $title          = $rs["title"];
                                $short_title    = $rs["short_title"];
                                $account_number = $rs["account_number"];
                                $branch_code    = $rs["branch_code"];
                                $branch_address = $rs["branch_address"];

                                echo "<tr>";
                                echo "<td>$title<br /><span class='notes'>$branch_address<br />Code: $branch_code</span></td>";
                                echo "<td>$short_title</td>";
                                echo "<td>$account_number</td>";
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
                            echo "<td align='center' colspan='4'>No record found.</td>";
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