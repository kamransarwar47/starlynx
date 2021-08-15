<?
    define("MODULE_ID", "PRJ001");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($title) && !empty($rate_per_marla) && !empty($short_code)) {
                $sql = "select id from projects where title=\"" . mysql_real_escape_string($title) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    if ($new_account == "Y") {
                        // Create new account
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
                        $account_id = mysql_insert_id();

                        system_log(CREATE, "New account ($title - $account_id) created.", $userInfo["id"]);
                    } else {
                        $account_id = $master_id;
                    }

                    $sql = "insert into
                                projects (
                                    account_id, title, description, rate_per_marla, short_code, created, created_by
                                ) values (
                                    \"" . mysql_real_escape_string($account_id) . "\",
                                    \"" . mysql_real_escape_string($title) . "\",
                                    \"" . mysql_real_escape_string($description) . "\",
                                    \"" . mysql_real_escape_string($rate_per_marla) . "\",
                                    \"" . mysql_real_escape_string($short_code) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title has been added successfully.", true);
                    system_log(CREATE, "$title created.", $userInfo["id"]);
                    unset($cmd, $account_id, $master_id, $title, $description, $rate_per_marla, $short_code);
                } else {
                    setMessage("$title is already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: $title already exists.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($title) && !empty($rate_per_marla) && !empty($short_code)) {
                if ($new_account == "Y") {
                    // Create new account
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
                    $account_id = mysql_insert_id();

                    system_log(MODIFY, "New account ($title - $account_id) created.", $userInfo["id"]);
                } else {
                    $account_id = $master_id;
                }

                $sql = "update
                            projects
                        set
                            account_id=\"" . mysql_real_escape_string($account_id) . "\",
                            title=\"" . mysql_real_escape_string($title) . "\",
                            description=\"" . mysql_real_escape_string($description) . "\",
                            rate_per_marla=\"" . mysql_real_escape_string($rate_per_marla) . "\",
                            short_code=\"" . mysql_real_escape_string($short_code) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("$title has been updated successfully.", true);
                system_log(MODIFY, "$title updated.", $userInfo["id"]);
                unset($cmd, $id, $account_id, $master_id, $title, $description, $rate_per_marla, $short_code);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from projects where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id             = $rs["id"];
                        $master_id      = $rs["account_id"];
                        $title          = $rs["title"];
                        $description    = $rs["description"];
                        $rate_per_marla = $rs["rate_per_marla"];
                        $short_code     = $rs["short_code"];
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

                // Plots check
                $sql = "select id from plots where project_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // Transactions check
                    $sql = "select id from transactions where project_id=\"" . mysql_real_escape_string($id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Landowner Projects check
                        $sql = "select id from landowner_projects where project_id=\"" . mysql_real_escape_string($id) . "\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows <= 0) {

                            // Sub Accounts check
                            $account_id = getProjectAccountId($id);
                            $sql        = "SELECT id FROM accounts WHERE master_id='{$account_id}'";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);
                            if ($numrows <= 0) {

                                // Delete Project
                                $sql = "delete from projects where id=\"" . mysql_real_escape_string($id) . "\"";
                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                // Delete Account
                                $sql = "DELETE FROM accounts WHERE id='{$account_id}'";
                                mysql_query($sql, $conn) or die(mysql_error());

                                setMessage("Record has been deleted.", true);
                                system_log(DELETE, "Project ID: $id deleted.", $userInfo["id"]);
                                unset($id);

                            } else {
                                setMessage("Record can not be deleted. (Remove Sub Accounts)");
                                system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Sub Accounts)]", $userInfo["id"]);
                            }

                        } else {
                            setMessage("Record can not be deleted. (Remove Landowner Projects)");
                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Landowner Projects)]", $userInfo["id"]);
                        }

                    } else {
                        setMessage("Record can not be deleted. (Remove Transactions)");
                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                    }

                } else {
                    setMessage("Record can not be deleted. (Remove Plots)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Plots)]", $userInfo["id"]);
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
        <td class="bg_title"><img src="images/projects.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Projects
        </td>
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
                <p class="box_title">Manage Projects</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Title</strong><br/>
                                    <input type="text" size="30" name="title" id="title" maxlength="100"
                                           value="<?= $title ?>"/>
                                </p>

                                <p>
                                    <strong>Rate Per Marla</strong><br/>
                                    <input type="text" size="30" name="rate_per_marla" id="rate_per_marla"
                                           maxlength="10" value="<?= $rate_per_marla ?>"/>
                                </p>

                                <p>
                                    <strong>Parent Account</strong><br/>
                                    <?
                                        $actArray = createProjectAccountsArray();
                                        echo createPageSelectHTML("master_id", "master_id", $actArray, $master_id);
                                    ?>
                                    <br/>
                                    <input type="checkbox" name="new_account" value="Y"/> Create new account under
                                    parent account.
                                </p>
                            </td>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Short Code</strong><br/>
                                    <input type="text" size="10" name="short_code" id="short_code" maxlength="2"
                                           value="<?= $short_code ?>"/>
                                </p>

                                <p>
                                    <strong>Description</strong> <span class="notes">(Optional)</span><br/>
                                    <textarea rows="5" cols="30" name="description"
                                              id="description"><?= $description ?></textarea>
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
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                       style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="60%">Title</td>
                        <td width="10%">Code</td>
                        <td width="10%">Rate</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $sql = "select * from projects order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);

                            while ($rs = mysql_fetch_array($result)) {
                                $id             = $rs["id"];
                                $title          = $rs["title"];
                                $description    = $rs["description"];
                                $rate_per_marla = $rs["rate_per_marla"];
                                $short_code     = $rs["short_code"];

                                echo "<tr>";
                                echo "<td>";
                                echo "$title";

                                if (!empty($description)) {
                                    echo "<br /><span class='notes'>$description</span>";
                                }

                                echo "</td>";
                                echo "<td>$short_code</td>";
                                echo "<td>" . number_format($rate_per_marla, 0, ".", ",") . "</td>";
                                echo "<td>";

                                echo "<a href='index.php?ss=$ss&mod=plots.manage&project_id=$id'>Plots</a> | ";

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