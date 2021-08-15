<?
    define("MODULE_ID", "SYS002");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/users.png" width="20" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Users</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">Manage Users</p>
            <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                <tr id="listhead">
                    <td width="5%">ID</td>
                    <td width="25%">Full Name</td>
                    <td width="15%">Mobile</td>
                    <td width="10%">Send SMS</td>
                    <td width="15%">User Name</td>
                    <td width="10%">Status</td>
                    <td width="10%">Multi Login</td>
                    <td width="10%">Action</td>
                </tr>
                <?
                    $sql = "select * from users order by full_name";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    $canModify = checkPermission($userInfo["id"], MODIFY);

                    while ($rs = mysql_fetch_array($result)) {
                        $id          = $rs["id"];
                        $full_name   = $rs["full_name"];
                        $mobile      = $rs["mobile"];
                        $user_name   = $rs["user_name"];
                        $status      = $rs["status"];
                        $multi_login = $rs["multi_login"];
                        $notes       = $rs["notes"];

                        echo "<tr>";
                        echo "<td>$id</td>";
                        echo "<td>$full_name<br /><span class='notes'>$notes</span></td>";
                        echo "<td>$mobile</td>";
                        echo "<td><a href='index.php?ss=$ss&mod=messages.add&message_type=SMS&msisdn=" . prepareMSISDN($mobile) . "'><img src='images/icon_sms.gif' title='Send SMS'/></a></td>";
                        echo "<td>$user_name</td>";
                        echo "<td>$status</td>";
                        echo "<td>$multi_login</td>";
                        echo "<td>";

                        if ($canModify) {
                            echo "<a href='index.php?ss=$ss&mod=users.add&id=$id&cmd=EDIT'>Modify</a>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>