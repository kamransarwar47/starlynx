<?
    define("MODULE_ID", "SYS003");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if(checkPermission($userInfo["id"], MODIFY)) {
        if($cmd == "UPDATE") {
            if(!empty($passwd) && !empty($passwd2)) {
                if($passwd == $passwd2) {
                    $cpass = encryptPassword($passwd);
                    $sql = "update
                                users
                            set
                                passwd=\"$cpass\",
                                updated=NOW(),
                                updated_by=\"".$userInfo["id"]."\"
                            where
                                id=\"".$userInfo["id"]."\"
                            ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("Password changed successfully.", true);
                    system_log(MODIFY, "Password changed.", $userInfo["id"]);
                    unset($passwd, $passwd2);
                } else {
                    setMessage("Password was not retyped correctly.");
                    system_log(MODIFY, "Operation failed. [Reason: Password was not retyped correctly.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }
?>
<table border="0" width="40%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/users.png" width="20" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Users</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?=showError()?>
            <form action="index.php" method="post">
            <input type="hidden" name="ss" value="<?=$ss?>" />
            <input type="hidden" name="mod" value="<?=$mod?>" />
            <input type="hidden" name="cmd" value="UPDATE" />
            <p class="box_title">Change Password</p>
            <? if(checkPermission($userInfo["id"], CREATE) || checkPermission($userInfo["id"], MODIFY)) { ?>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td valign="top">
                        <p>
                            <strong>User Name</strong><br />
                                <?=getUserName($userInfo["id"])?>
                        </p>

                        <p>
                            <strong>Password</strong><br />
                            <input type="password" size="30" name="passwd" id="passwd" maxlength="20" value="" />
                        </p>

                        <p>
                            <strong>Retype Password</strong><br />
                            <input type="password" size="30" name="passwd2" id="passwd2" maxlength="20" value="" />
                        </p>
                    </td>
                </tr>
            </table>
            <p>
                <input type="submit" value="Submit" />
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