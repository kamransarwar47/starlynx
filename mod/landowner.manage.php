<?
    define("MODULE_ID", "LDO002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // Check landowner projects
                $sql = "select id from landowner_projects where landowner_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    // get account_id of the landowner
                    $account_id = getLandOwnerAccountId($id);

                    // Transactions check
                    $sql = "select id from transactions where account_id=\"" . mysql_real_escape_string($account_id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Delete landowner account
                        $sql = "DELETE FROM accounts WHERE id='{$account_id}'";
                        mysql_query($sql, $conn) or die(mysql_error());

                        // Delete landowner
                        $sql = "delete from landowner where id=\"" . mysql_real_escape_string($id) . "\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());

                        setMessage("Record has been deleted.", true);
                        system_log(DELETE, "Landowner ID: $id deleted.", $userInfo["id"]);
                        unset($id);

                    } else {
                        setMessage("Record can not be deleted. (Remove Transactions)");
                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                    }

                } else {
                    setMessage("Record can not be deleted. (Remove Landowner Projects)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Landowner Projects)]", $userInfo["id"]);
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#date_of_birth").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "c-100:c"
        });

        $("#nic_number").mask("99999-9999999-9", {placeholder: "x"});
    });
</script>
<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Land Owner
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">Manage Land Owner</p>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <form action="index.php" method="get" autocomplete="off">
                    <input type="hidden" name="ss" value="<?= $ss ?>"/>
                    <input type="hidden" name="mod" value="<?= $mod ?>"/>
                    <p style="float: left; width: auto;">
                        <strong>Project</strong><br/>
                        <select name="project_id" id="project_id">
                            <option value="">-- Select --</option>
                            <?
                                $sql = "select id, title from projects order by title";
                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                while ($rs = mysql_fetch_array($result)) {
                                    $selected = "";

                                    if ($project_id == $rs["id"]) {
                                        $selected = "selected='selected'";
                                    }

                                    echo "<option value=\"" . $rs["id"] . "\" $selected>" . $rs["title"] . "</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p style="float: left; width: auto;">
                        <strong>Land Owner Name</strong><br/>
                        <input type="text" name="landowner_name" id="landowner_name" value="<?= $landowner_name ?>"/>
                    </p>
                    <p style="float: left; width: auto;">
                        <strong>NIC #</strong><br/>
                        <input type="text" name="nic_number" id="nic_number" value="<?= $nic_number ?>"
                               placeholder="xxxxx-xxxxxxx-x"/>
                    </p>

                    <p style="float: left; width: auto; padding-top: 13px;">
                        <input type="submit" value="Submit"/>
                    </p>
                </form>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                       style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="25%">Full Name</td>
                        <td width="20%">Father/Husband Name</td>
                        <td width="15%">Contact</td>
                        <td width="10%">NIC #</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        if (!empty($landowner_name) || !empty($nic_number) || !empty($project_id)) {
                            $select  = "";
                            $join    = "";
                            $special = " WHERE ";
                        }

                        if (!empty($project_id)) {
                            $select  .= ", lp.id as lp_id";
                            $join    .= " JOIN landowner_projects lp ON lo.id = lp.landowner_id";
                            $special .= " lp.project_id=\"" . mysql_real_escape_string($project_id) . "\"";
                        }

                        if (!empty($landowner_name)) {
                            if (!empty($project_id)) {
                                $special .= " AND ";
                            }
                            $special .= " lo.full_name LIKE \"%" . mysql_real_escape_string($landowner_name) . "%\"";
                        }

                        if (!empty($nic_number)) {
                            if (!empty($landowner_name) || !empty($project_id)) {
                                $special .= " AND ";
                            }
                            $special .= " lo.id_num=\"" . mysql_real_escape_string($nic_number) . "\"";
                        }
                        $sql = "SELECT lo.*" . $select . " FROM landowner lo" . $join . $special . " ORDER BY full_name";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canDelete = checkPermission($userInfo["id"], DELETE);
                            $canView   = checkPermission($userInfo["id"], VIEW);

                            while ($rs = mysql_fetch_array($result)) {
                                $id            = $rs["id"];
                                $full_name     = $rs["full_name"];
                                $father_name   = $rs["father_name"];
                                $street        = $rs["street"];
                                $city          = getCityName($rs["city"]);
                                $zip           = $rs["zip"];
                                $state         = $rs["state"];
                                $country       = getCountryName($rs["country"]);
                                $phone_1       = $rs["phone_1"];
                                $phone_2       = $rs["phone_2"];
                                $mobile_1      = $rs["mobile_1"];
                                $mobile_2      = $rs["mobile_2"];
                                $fax_1         = $rs["fax_1"];
                                $fax_2         = $rs["fax_2"];
                                $email_1       = $rs["email_1"];
                                $email_2       = $rs["email_2"];
                                $id_num        = $rs["id_num"];
                                $date_of_birth = $rs["date_of_birth"];
                                $lp_id         = $rs["lp_id"];

                                echo "<tr>";
                                echo "<td>";
                                echo "$full_name<br />";
                                echo "<span class='notes'>$street<br />$city, $country</span>";
                                echo "</td>";
                                echo "<td>$father_name</td>";
                                echo "<td>";
                                echo(!empty($phone_1) ? "$phone_1<br />" : "");
                                echo(!empty($phone_2) ? "$phone_2<br />" : "");
                                echo(!empty($mobile_1) ? "$mobile_1<br />" : "");
                                echo(!empty($mobile_2) ? "$mobile_2<br />" : "");
                                echo(!empty($email_1) ? "$email_1<br />" : "");
                                echo(!empty($email_2) ? "$email_2" : "");
                                echo "</td>";
                                echo "<td>$id_num</td>";
                                echo "<td>";

                                if ($canView) {
                                    echo "<a href='index.php?ss=$ss&mod=landowner.project.manage&landowner_id=$id'>Projects</a> | ";
                                }

                                if ($canModify) {
                                    echo "<a href='index.php?ss=$ss&mod=landowner.add&id=$id&cmd=EDIT'>Modify</a> | ";
                                }

                                if ($canDelete) {
                                    echo "<a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
                                }

                                if ($canView) {
                                    if (!empty($project_id)) {
                                        echo " | <a href='index.php?ss=$ss&mod=landowner.view&view_id=$lp_id'>View</a>";
                                        echo " | <a href='index.php?ss=$ss&mod=landowner.project.book&id=$lp_id&cmd=EDIT'>Installment Plan</a>";
                                    }
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='6'>No record found.</td>";
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