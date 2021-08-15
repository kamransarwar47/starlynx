<?
    define("MODULE_ID", "LDO003");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($landowner_id) && !empty($project_id)) {
                $sql = "SELECT id FROM landowner_projects WHERE landowner_id=\"" . mysql_real_escape_string($landowner_id) . "\" AND project_id=\"" . mysql_real_escape_string($project_id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    $sql = "INSERT INTO
                                    landowner_projects (
                                        project_id, landowner_id, created, created_by
                                    ) values (
                                        \"" . mysql_real_escape_string($project_id) . "\",
                                        \"" . mysql_real_escape_string($landowner_id) . "\",
                                        NOW(),
                                        \"" . $userInfo["id"] . "\"
                                    )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage(getProjectName($project_id) . " has been added successfully.", true);
                    system_log(CREATE, getProjectName($project_id) . " created.", $userInfo["id"]);
                    unset($cmd, $project_id);
                } else {
                    setMessage(getProjectName($project_id) . " is already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: " . getProjectName($project_id) . " already exists.]", $userInfo["id"]);
                }

            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }
    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // Delete Landowner Project Dues
                $sql = "delete from landowner_projects_dues where landowner_projects_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                // Delete Landowner Project
                $sql = "delete from landowner_projects where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("Record has been deleted.", true);
                system_log(DELETE, "Landowner Project ID: $id deleted.", $userInfo["id"]);
                unset($id);

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
        <td class="bg_title"><img src="images/lookup.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Land Owner
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
                <input type="hidden" name="landowner_id" value="<?= $landowner_id ?>"/>
                <p class="box_title">Land Owner Projects</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <p style="float: left; width: auto;">
                        <strong>Project</strong><br/>
                        <select name="project_id" id="project_id">
                            <option value="">-- Select --</option>
                            <?
                                $sql = "select id, title from projects order by title";
                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                while ($rs = mysql_fetch_array($result)) {
                                    echo "<option value=\"" . $rs["id"] . "\">" . $rs["title"] . "</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p style="float: left; width: auto; padding-top: 13px; margin-left: 5px;">
                        <input type="submit" value="Allocate"/>
                    </p>
                    <?php
                }
                ?>
            </form>

            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <strong>Land Owner</strong><br/>
                            <?php echo getLandOwnerName($landowner_id); ?>
                        </td>
                    </tr>
                </table>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="70%">Project</td>
                        <td width="30%">Action</td>
                    </tr>
                    <?
                        $sql = "SELECT * FROM landowner_projects WHERE landowner_id = '$landowner_id' ORDER BY id ASC";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows > 0) {
                            $canView   = checkPermission($userInfo["id"], VIEW);
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);
                            while ($rs = mysql_fetch_array($result)) {
                                $id        = $rs["id"];
                                $title     = getProjectName($rs["project_id"]);
                                $landowner = $rs["landowner_id"];
                                echo "<tr>";
                                echo "<td>$title</td>";
                                echo "<td>";
                                if ($canView) {
                                    echo "<a href='index.php?ss=$ss&mod=landowner.view&view_id=$id'>View</a> | ";
                                }

                                if ($canModify) {
                                    echo "<a href='index.php?ss=$ss&mod=landowner.project.book&id=$id&cmd=EDIT'>Installment Plan</a> | ";
                                }

                                if ($canDelete) {
                                    echo "<a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&landowner_id=$landowner&cmd=DELETE');\">Delete</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='2'>No record found.</td>";
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