<?
    define("MODULE_ID", "LUD005");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($expense_percentage)) {
                $sql = "update
                            projects
                        set
                            expense_percentage=\"" . mysql_real_escape_string($expense_percentage) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                setMessage(getProjectName($id) . " has been updated successfully.", true);
                system_log(MODIFY, getProjectName($id) . " updated.", $userInfo["id"]);
                unset($cmd, $id, $expense_percentage);
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
                        $id         = $rs["id"];
                        $title      = $rs["title"];
                        $percentage = $rs["expense_percentage"];
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
    if (empty($cmd)) {
        $cmd = "";
    }
?>

<table border="0" width="700" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/lookup.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Lookup Data
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
                <p class="box_title">Head Office Percentage</p>
                <? if (checkPermission($userInfo["id"], MODIFY) && $cmd == "UPDATE") { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Project</strong><br/>
                                    <?php echo $title; ?>
                                </p>
                            </td>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Percentage</strong><br/>
                                    <input type="text" size="20" name="expense_percentage" id="expense_percentage"
                                           maxlength="12"
                                           value="<?= $percentage ?>"/> %
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
                        <td width="70%">Project</td>
                        <td width="10%">Percentage</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $sql = "select * from projects order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            while ($rs = mysql_fetch_array($result)) {
                                $id         = $rs["id"];
                                $title      = $rs["title"];
                                $percentage = $rs["expense_percentage"];
                                echo "<tr>";
                                echo "<td>$title</td>";
                                echo "<td>$percentage</td>";
                                echo "<td>";
                                if ($canModify) {
                                    echo "<a href='index.php?ss=$ss&mod=$mod&id=$id&cmd=EDIT'>Modify</a>";
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