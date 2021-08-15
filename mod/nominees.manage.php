<?
    define("MODULE_ID", "CST004");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // Delete customer nominee
                $sql = "delete from customers_nominees where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("Record has been deleted.", true);
                system_log(DELETE, "Customer Nominee ID: $id deleted.", $userInfo["id"]);
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#date_of_birth").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "c-100:c"
        });

        $("#id_num").mask("99999-9999999-9", {placeholder: "x"});
    });
</script>
<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Customers</td>
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
                <p class="box_title">Manage Nominees</p>
                <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                        <tr id="listhead">
                            <td width="30%">Full Name</td>
                            <td width="20%">Father/Husband Name</td>
                            <td width="15%">Contact</td>
                            <td width="15%">NIC #</td>
                            <td width="10%">Date of Birth</td>
                            <td width="10%">Action</td>
                        </tr>
                        <?
                            $sql = "select * from customers_nominees order by full_name, customer_id";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);

                            if ($numrows > 0) {
                                $canModify = checkPermission($userInfo["id"], MODIFY);
                                $canDelete = checkPermission($userInfo["id"], DELETE);

                                while ($rs = mysql_fetch_array($result)) {
                                    $id            = $rs["id"];
                                    $customer      = getCustomerName($rs["customer_id"]);
                                    $relationship  = $rs["relationship"];
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

                                    echo "<tr>";
                                    echo "<td>";
                                    echo "$full_name<br />";
                                    echo "<span class='notes'><strong>($relationship of $customer)</strong><br />$street<br />$city, $country</span>";
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
                                    echo "<td>$date_of_birth</td>";
                                    echo "<td>";

                                    if ($canModify) {
                                        echo "<a href='index.php?ss=$ss&mod=nominees.add&id=$id&cmd=EDIT'>Modify</a> | ";
                                    }

                                    if ($canDelete) {
                                        echo "<a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
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