<?
    define("MODULE_ID", "LUD003");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($title)) {
                $sql = "select id from countries where title=\"" . mysql_real_escape_string($title) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    $sql = "insert into
                                countries (
                                    title, created, created_by
                                ) values (
                                    \"" . mysql_real_escape_string($title) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title has been added successfully.", true);
                    system_log(CREATE, "$title created.", $userInfo["id"]);
                    unset($cmd, $title);
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
            if (!empty($id) && !empty($title)) {
                $sql = "update
                            countries
                        set
                            title=\"" . mysql_real_escape_string($title) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("$title has been updated successfully.", true);
                system_log(MODIFY, "$title updated.", $userInfo["id"]);
                unset($cmd, $id, $title);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from countries where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id    = $rs["id"];
                        $title = $rs["title"];
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

                // Check country in customers
                $sql = "select id from customers where country=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // Check country in customer nominees
                    $sql = "select id from customers_nominees where country=\"" . mysql_real_escape_string($id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Check country in employees
                        $sql = "select id from employees where country=\"" . mysql_real_escape_string($id) . "\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows <= 0) {

                            // Check country in dealers
                            $sql = "select id from dealers where country=\"" . mysql_real_escape_string($id) . "\"";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);
                            if ($numrows <= 0) {

                                // Check country in landowners
                                $sql = "select id from landowner where country=\"" . mysql_real_escape_string($id) . "\"";
                                $result = mysql_query($sql, $conn) or die(mysql_error());
                                $numrows = mysql_num_rows($result);
                                if ($numrows <= 0) {

                                    // Check country in investor
                                    $sql = "select id from investor where country=\"" . mysql_real_escape_string($id) . "\"";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    if ($numrows <= 0) {

                                        // Check country in partner
                                        $sql = "select id from partner where country=\"" . mysql_real_escape_string($id) . "\"";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows = mysql_num_rows($result);
                                        if ($numrows <= 0) {

                                            // Delete country
                                            $sql = "delete from countries where id=\"" . mysql_real_escape_string($id) . "\"";
                                            $result = mysql_query($sql, $conn) or die(mysql_error());

                                            setMessage("Record has been deleted.", true);
                                            system_log(DELETE, "Country ID: $id deleted.", $userInfo["id"]);
                                            unset($id);

                                        } else {
                                            setMessage("Record can not be deleted. (Remove Country From Partners)");
                                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Partners)]", $userInfo["id"]);
                                        }

                                    } else {
                                        setMessage("Record can not be deleted. (Remove Country From Investors)");
                                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Investors)]", $userInfo["id"]);
                                    }

                                } else {
                                    setMessage("Record can not be deleted. (Remove Country From Landowners)");
                                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Landowners)]", $userInfo["id"]);
                                }

                            } else {
                                setMessage("Record can not be deleted. (Remove Country From Dealers)");
                                system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Dealers)]", $userInfo["id"]);
                            }

                        } else {
                            setMessage("Record can not be deleted. (Remove Country From Employees)");
                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Employees)]", $userInfo["id"]);
                        }

                    } else {
                        setMessage("Record can not be deleted. (Remove Country From Customer Nominees)");
                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Customer Nominees)]", $userInfo["id"]);
                    }

                } else {
                    setMessage("Record can not be deleted. (Remove Country From Customers)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Country From Customers)]", $userInfo["id"]);
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

<table border="0" width="600" cellpadding="0" cellspacing="0" align="center">
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
                <p class="box_title">Countries</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Title</strong><br/>
                                    <input type="text" size="30" name="title" id="title" maxlength="100" value="<?= $title ?>"/>
                                </p>
                            </td>
                            <td width="50%" valign="top">

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
                        $sql = "select * from countries order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);

                            while ($rs = mysql_fetch_array($result)) {
                                $id    = $rs["id"];
                                $title = $rs["title"];

                                echo "<tr>";
                                echo "<td>$title</td>";
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