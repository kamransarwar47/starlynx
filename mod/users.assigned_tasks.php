<?
    define("MODULE_ID", "SYS007");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($user_id) && !empty($title) && !empty($details) && !empty($due_on)) {
                $sql = "insert into
                            users_tasks (
                                user_id, title, details, status, due_on, assigned_by, created
                            ) values (
                                \"" . mysql_real_escape_string($user_id) . "\",
                                \"" . mysql_real_escape_string($title) . "\",
                                \"" . mysql_real_escape_string($details) . "\",
                                \"DUE\",
                                \"" . mysql_real_escape_string($due_on) . "\",
                                \"" . $userInfo["id"] . "\",
                                NOW()
                            )";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("Task has been assigned successfully.", true);
                system_log(CREATE, "New task created.", $userInfo["id"]);
                unset($cmd, $user_id, $title, $details, $due_on);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (empty($cmd)) {
        $cmd = "ADD";
    }
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $("#dlgNewTask").dialog({
            autoOpen: false,
            height: 500,
            width: 500,
            modal: true,
            buttons: {
                "Create": function () {
                    var title = $("#title").val();
                    var details = $("#details").val();
                    var due_on = $("#due_on").val();
                    var assign_to = $("#user_id").val();

                    if (title == "" || details == "" || due_on == "" || assign_to == "") {
                        alert("Please input all fields.");
                    } else {
                        $("#frmNewTask").submit();
                    }
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {
                $(this).dialog("close");
            }
        });

        $("#btnNewTask").click(function () {
            $("#dlgNewTask").dialog("open");
        });

        $("#due_on").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "c:c+5"
        });
    });
</script>
<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Users</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">
                Assigned Tasks
                <?php if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <input type="button" value="New Task" id="btnNewTask" style="float: right;"/>
                <?php } ?>
            </p>
            <br/>
            <br/>
            <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                <div id="dlgNewTask" title="Add New Task">
                    <p style="font-size: 12px;">All fields are required.</p>

                    <form action="index.php" method="POST" id="frmNewTask" autocomplete="off">
                        <input type="hidden" name="ss" value="<?= $ss ?>"/>
                        <input type="hidden" name="mod" value="<?= $mod ?>"/>
                        <input type="hidden" name="cmd" value="ADD"/>
                        <p>
                            <strong>Title</strong><br/>
                            <input type="text" name="title" id="title" size="41" maxlength="255" value=''/>
                        </p>

                        <p>
                            <strong>Details</strong><br/>
                            <textarea name="details" id="details" rows="5" cols="39"></textarea>
                        </p>

                        <p style="float: left;">
                            <strong>Due On</strong><br/>
                            <input type="text" name="due_on" id="due_on" size="20" maxlength="10" value=''/>
                        </p>

                        <p style="float: left; margin-left: 25px;">
                            <strong>Assign To</strong><br/>
                            <select name="user_id" id="user_id">
                                <option value="">-- Select --</option>
                                <?
                                    $sql = "select id, full_name from users where status='ACTIVE'";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());

                                    while ($rs = mysql_fetch_array($result)) {
                                        $selected = "";

                                        if ($user_id == $rs["id"]) {
                                            $selected = "selected='selected'";
                                        }

                                        echo "<option value='" . $rs["id"] . "' $selected>" . $rs["full_name"] . "</option>";
                                    }
                                ?>
                            </select>
                        </p>
                    </form>
                </div>
            <? } ?>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="65%">Task</td>
                        <td width="10%">Assigned To</td>
                        <td width="10%">Due On</td>
                        <td width="15%">Status</td>
                    </tr>
                    <?
                        $sql = "SELECT * FROM users_tasks WHERE assigned_by = '" . $userInfo["id"] . "' AND user_id <> '" . $userInfo["id"] . "' ORDER BY status ASC, created ASC";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            while ($rs = mysql_fetch_array($result)) {
                                $id          = $rs["id"];
                                $title       = stripslashes($rs["title"]);
                                $details     = stripslashes(nl2br($rs["details"]));
                                $status      = $rs["status"];
                                $due_on      = date("d-m-Y", strtotime($rs["due_on"]));
                                $assigned_by = getUserFullName($rs["user_id"]);
                                $created     = date("d-m-Y", strtotime($rs["created"]));
                                $updated     = date("d-m-Y", strtotime($rs["updated"]));

                                $style = "";

                                if ($status == "DUE") {
                                    $t_due = strtotime($rs["due_on"]);
                                    $t     = time();

                                    if ($t_due <= $t) {
                                        $style = "background-color: #ffeeee;";
                                    }
                                }

                                echo "<tr style='$style'>";
                                echo "<td valign='center'>";
                                echo "<a name='task_$id'></a><strong style='font-size: 18px;'>$title</strong><br />";
                                echo "$details";
                                echo "</td>";
                                echo "<td valign='center'>$assigned_by<br /><span class='notes'>$created</span></td>";
                                echo "<td valign='center'>$due_on</td>";
                                echo "<td valign='center'>";

                                if ($status == "DUE") {
                                    echo "<span style='background-color: #ff0000; color: #ffffff; padding: 5px; float: left;'>$status</span>";
                                } else {
                                    echo "<span style='background-color: #006600; color: #ffffff; padding: 5px; float:left;'>$status</span>";
                                }

                                if ($status == "COMPLETE") {
                                    echo "<span style='float: left; margin: 5px;' class='notes'>$updated</span>";
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