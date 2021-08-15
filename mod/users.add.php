<?
    define("MODULE_ID", "SYS001");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    include("conf/modules.php");

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($user_name) && !empty($passwd) && !empty($passwd2) && !empty($full_name) && !empty($mobile) && !empty($multi_login) && !empty($status) && sizeof($allowed_ip > 0)) {
                if ($passwd == $passwd2) {
                    $exists = getUserId($user_name);

                    if (empty($exists)) {
                        // Create User Record
                        $cpass = encryptPassword($passwd);
                        $sql   = "insert into
                                    users (
                                        user_name, passwd, full_name, mobile, notes, status, multi_login, created, created_by
                                    ) values (
                                        \"" . mysql_real_escape_string($user_name) . "\",
                                        \"" . $cpass . "\",
                                        \"" . mysql_real_escape_string($full_name) . "\",
                                        \"" . mysql_real_escape_string($mobile) . "\",
                                        \"" . mysql_real_escape_string($notes) . "\",
                                        \"" . mysql_real_escape_string($status) . "\",
                                        \"" . mysql_real_escape_string($multi_login) . "\",
                                        NOW(),
                                        \"" . $userInfo["id"] . "\"
                                    )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $user_id = getUserId($user_name);

                        // Add Allowed IPs
                        for ($i = 0; $i < sizeof($allowed_ip); $i++) {
                            $sql = "insert into
                                        users_access_control (
                                            user_id, allowed_ip, created, created_by
                                        ) values (
                                            '$user_id',
                                            \"" . mysql_real_escape_string($allowed_ip[$i]) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                        }

                        // Add Permissions
                        if (sizeof($module) > 0) {
                            foreach ($module as $module_id => $permissions) {
                                for ($i = 0; $i < sizeof($permissions); $i++) {
                                    $sql = "insert into
                                                users_permissions (
                                                    user_id, module_id, operation, created, created_by
                                                ) values (
                                                    '$user_id',
                                                    \"" . mysql_real_escape_string($module_id) . "\",
                                                    \"" . mysql_real_escape_string($permissions[$i]) . "\",
                                                    NOW(),
                                                    \"" . $userInfo["id"] . "\"
                                                )";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                }
                            }
                        }

                        setMessage("$user_name has been added successfully.", true);
                        system_log(CREATE, "$user_name created.", $userInfo["id"]);
                        unset($user_name, $passwd, $passwd2, $full_name, $mobile, $notes, $multi_login, $status, $allowed_ip, $module);
                    } else {
                        setMessage("$user_name is not available, please try other names.");
                        system_log(CREATE, "Operation failed. [Reason: $user_name not available.]", $userInfo["id"]);
                    }
                } else {
                    setMessage("Password was not retyped correctly.");
                    system_log(CREATE, "Operation failed. [Reason: Password was not retyped correctly.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    } else {
        setMessage("Permission denied.");
        system_log(CREATE, "Permission denied.", $userInfo["id"]);
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($user_name) && !empty($full_name) && !empty($mobile) && !empty($multi_login) && !empty($status) && sizeof($allowed_ip > 0)) {
                $ok = true;

                // Update User Record
                if (!empty($passwd) && !empty($passwd2)) {
                    if ($passwd == $passwd2) {
                        $cpass      = encryptPassword($passwd);
                        $passFields = "passwd=\"$cpass\",";
                    } else {
                        $ok = false;
                        setMessage("Password was not retyped correctly.");
                        system_log(MODIFY, "Operation failed. [Reason: Password was not retyped correctly.]", $userInfo["id"]);
                    }
                }

                if ($ok) {
                    $sql = "update
                                        users
                                    set
                                            $passFields
                                            full_name=\"" . mysql_real_escape_string($full_name) . "\",
                                            mobile=\"" . mysql_real_escape_string($mobile) . "\",
                                            notes=\"" . mysql_real_escape_string($notes) . "\",
                                            status=\"" . mysql_real_escape_string($status) . "\",
                                            multi_login=\"" . mysql_real_escape_string($multi_login) . "\",
                                            updated=NOW(),
                                            updated_by=\"" . $userInfo["id"] . "\"
                                    where
                                            id=\"" . mysql_real_escape_string($id) . "\"
                                        ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $user_id = $id;

                    // Add Allowed IPs
                    $sql = "delete from users_access_control where user_id='$user_id'";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    for ($i = 0; $i < sizeof($allowed_ip); $i++) {
                        $sql = "insert into
                                            users_access_control (
                                                user_id, allowed_ip, created, created_by
                                            ) values (
                                                '$user_id',
                                                \"" . mysql_real_escape_string($allowed_ip[$i]) . "\",
                                                NOW(),
                                                \"" . $userInfo["id"] . "\"
                                            )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                    }

                    // Add Permissions
                    $sql = "delete from users_permissions where user_id='$user_id'";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    if (sizeof($module) > 0) {
                        foreach ($module as $module_id => $permissions) {
                            for ($i = 0; $i < sizeof($permissions); $i++) {
                                $sql = "insert into
                                                    users_permissions (
                                                        user_id, module_id, operation, created, created_by
                                                    ) values (
                                                        '$user_id',
                                                        \"" . mysql_real_escape_string($module_id) . "\",
                                                        \"" . mysql_real_escape_string($permissions[$i]) . "\",
                                                        NOW(),
                                                        \"" . $userInfo["id"] . "\"
                                                    )";
                                $result = mysql_query($sql, $conn) or die(mysql_error());
                            }
                        }
                    }

                    setMessage("$user_name has been updated successfully.", true);
                    system_log(MODIFY, "$user_name updated.", $userInfo["id"]);
                    unset($user_name, $passwd, $passwd2, $full_name, $mobile, $notes, $multi_login, $status, $allowed_ip, $module);
                    $cmd = "EDIT";
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from users where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id          = $rs["id"];
                        $user_name   = $rs["user_name"];
                        $full_name   = $rs["full_name"];
                        $mobile      = $rs["mobile"];
                        $notes       = $rs["notes"];
                        $status      = $rs["status"];
                        $multi_login = $rs["multi_login"];
                    }

                    $sql2 = "select * from users_access_control where user_id='$id'";
                    $result2 = mysql_query($sql2, $conn) or die(mysql_error());
                    $numrows2 = mysql_num_rows($result2);

                    if ($numrows2 > 0) {
                        $allowed_ip = array();

                        while ($rs2 = mysql_fetch_array($result2)) {
                            $allowed_ip[] = $rs2["allowed_ip"];
                        }
                    }

                    $sql3 = "select * from users_permissions where user_id='$id'";
                    $result3 = mysql_query($sql3, $conn) or die(mysql_error());
                    $module = array();

                    while ($rs3 = mysql_fetch_array($result3)) {
                        $module[$rs3["module_id"]][] = $rs3["operation"];
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
        $cmd = "ADD";
    }
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnAddMore").button({
            text: false,
            icons: {
                primary: "ui-icon-circle-plus"
            }
        });

        $("#btnAddMore").click(function () {
            addNewRow();
        });

        $("#chkSelAll").click(function () {
            $(":checkbox").attr("checked", "checked");
        });

        $("#chkDSelAll").click(function () {
            $(":checkbox").removeAttr("checked");
        });
    });

    function addNewRow() {
        var rowData = "<p style=\"padding: 0; margin: 0;\"><input type=\"text\" name=\"allowed_ip[]\" maxlength=\"30\" size=\"30\" value=\"\" /></p>";

        $("#rowLast").before(rowData);
    }
</script>
<table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/users.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Users
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
                <p class="box_title"><?= ($cmd == "ADD" ? "Create New" : "Modify") ?> User</p>
                <? if (checkPermission($userInfo["id"], CREATE) || checkPermission($userInfo["id"], MODIFY)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="40%" valign="top">
                                <p>
                                    <strong>User Name</strong><br/>
                                    <? if ($cmd == "ADD") { ?>
                                        <input type="text" size="30" name="user_name" id="user_name" maxlength="20"
                                               value="<?= $user_name ?>"/>
                                    <? } else { ?>
                                        <?= $user_name ?>
                                        <input type="hidden" name="user_name" value="<?= $user_name ?>"/>
                                    <? } ?>
                                </p>

                                <p>
                                    <strong>Password</strong> <? if ($cmd != "ADD") { ?><span class="notes"
                                                                                              style="color: #ff0000;">(Leave blank if don't want to change)</span><? } ?>
                                    <br/>
                                    <input type="password" size="30" name="passwd" id="passwd" maxlength="20" value=""/>
                                </p>

                                <p>
                                    <strong>Retype Password</strong><br/>
                                    <input type="password" size="30" name="passwd2" id="passwd2" maxlength="20"
                                           value=""/>
                                </p>

                                <p>
                                    <strong>Allow Multi-login?</strong>
                                    <?
                                        if (empty($multi_login)) {
                                            $multi_login = "N";
                                        }
                                    ?>
                                    <input type="radio" name="multi_login"
                                           value="Y" <?= ($multi_login == "Y") ? "checked='checked'" : "" ?> /> Yes
                                    <input type="radio" name="multi_login"
                                           value="N" <?= ($multi_login == "N") ? "checked='checked'" : "" ?> /> No
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Account Status</strong>
                                    <select name="status" id="status">
                                        <?
                                            $statuses = array('ACTIVE', 'LOCKED', 'SUSPENDED');

                                            for ($i = 0; $i < sizeof($statuses); $i++) {
                                                $selected = "";

                                                if ($status == $statuses[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $statuses[$i] . "\" $selected\">" . $statuses[$i] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <strong>Allowed IPs</strong> <span class="notes">(Enter * if you want to allow any IP)</span>
                                    <div style="border: 1px solid #ccc; width: 350px; height: 100px; overflow: auto; padding: 10px;">
                                        <? if (is_array($allowed_ip)) { ?>
                                        <? for ($i = 0;
                                            $i < sizeof($allowed_ip);
                                            $i++) { ?>
                                <p style="padding: 0; margin: 0;"><input type="text" name="allowed_ip[]" maxlength="30"
                                                                         size="30" value="<?= $allowed_ip[$i] ?>"/></p>
                                <? } ?>
                                <? } else { ?>
                                    <p style="padding: 0; margin: 0;"><input type="text" name="allowed_ip[]"
                                                                             maxlength="30" size="30" value=""/></p>
                                <? } ?>

                                <p id="rowLast" style="padding: 0; margin: 0;"><a id="btnAddMore">+ Add more</a></p>
                                </div>
                                </p>
                            </td>
                            <td width="30%" valign="top">
                                <p>
                                    <strong>Full Name</strong><br/>
                                    <input type="text" size="30" name="full_name" id="full_name" maxlength="100"
                                           value="<?= $full_name ?>"/>
                                </p>

                                <p>
                                    <strong>Mobile Number</strong><br/>
                                    <input type="text" size="30" name="mobile" id="mobile" maxlength="20"
                                           value="<?= $mobile ?>"/>
                                </p>

                                <p>
                                    <strong>Notes</strong><br/>
                                    <textarea name="notes" id="notes" cols="30" rows="3"><?= $notes ?></textarea>
                                </p>


                            </td>
                            <td width="30%" valign="top">
                                <p style="width: 350px;"><strong>Authorized Modules</strong> <span class="notes"
                                                                                                   style="float: right; text-align: right;"><a
                                                href="#" id="chkSelAll">Select All</a> | <a href="#" id="chkDSelAll">Deselect All</a></span>
                                </p>
                                <div style="border: 1px solid #ccc; width: 350px; height: 290px; overflow: auto; padding: 10px;">
                                    <? //print_r($module); ?>
                                    <table border="0" width="100%" cellpadding="2" cellspacing="0" align="center"
                                           id="user_modules">
                                        <thead>
                                        <tr style="font-weight: bold; display: block;">
                                            <td width="47%" style="border-bottom: 2px solid #999;">Module</td>
                                            <td width="8%" align="center" style="border-bottom: 2px solid #999;">A</td>
                                            <td width="8%" align="center" style="border-bottom: 2px solid #999;">C</td>
                                            <td width="8%" align="center" style="border-bottom: 2px solid #999;">M</td>
                                            <td width="8%" align="center" style="border-bottom: 2px solid #999;">D</td>
                                            <td width="8%" align="center" style="border-bottom: 2px solid #999;">V</td>
                                            <td width="9%" align="center" style="border-bottom: 2px solid #999;">P</td>
                                            <td width="9%" align="center" style="border-bottom: 2px solid #999;">R</td>
                                        </tr>
                                        </thead>
                                        <tbody style="display: block; overflow: auto; height: 270px; width: 100%;">
                                        <?
                                            foreach ($system_modules as $group => $modules) {
                                                if (sizeof($modules) > 0) {
                                                    echo "<tr>";
                                                    echo "<td colspan='8'><strong style='color: #999;'>[$group]</strong></td>";
                                                    echo "</tr>";

                                                    foreach ($modules as $key => $vals) {
                                                        $params = explode(",", $vals[0]);

                                                        echo "<tr>";
                                                        echo "<td>" . $params[0] . "</td>";

                                                        for ($i = 1; $i < sizeof($params); $i++) {
                                                            $checked = "";

                                                            echo "<td align='center'>";

                                                            if ($params[$i] != "x") {
                                                                if (in_array($params[$i], $module[$key])) {
                                                                    $checked = "checked='checked'";
                                                                }

                                                                echo "<input type=\"checkbox\" name=\"module[" . $key . "][]\" value=\"" . $params[$i] . "\" $checked />";
                                                            } else {
                                                                echo "x";
                                                            }

                                                            echo "</td>";
                                                        }


                                                        echo "</tr>";
                                                    }
                                                }
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" value="Submit"/>
                    </p>
                <? } ?>
            </form>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>