<?
    define("MODULE_ID", "ACT006");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // get account_id of the employee
                $sql = "SELECT account_id FROM employees WHERE id = '{$id}'";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $account_id = mysql_fetch_array($result)['account_id'];

                // Transactions check
                $sql = "select id from transactions where account_id='{$account_id}'";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows <= 0) {

                    $employee_accounts = [];
                    // get all account ids linked to the employee
                    $sql = "SELECT id FROM accounts WHERE master_id = '{$account_id}' OR id = '{$account_id}'";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    while ($rs = mysql_fetch_array($result)) {
                        $employee_accounts[] = $rs['id'];
                    }
                    $employee_accounts = implode(',', $employee_accounts);

                    // checking all transactions linked to the employee accounts from transactions_details
                    $sql = "SELECT id FROM transactions_details WHERE account_id IN ({$employee_accounts})";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Sub Accounts check
                        $sql = "SELECT id FROM accounts WHERE master_id='{$account_id}'";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows <= 0) {

                            // Delete Employee account
                            $sql = "DELETE FROM accounts WHERE id='{$account_id}'";
                            mysql_query($sql, $conn) or die(mysql_error());

                            // Delete employee
                            $sql = "DELETE FROM employees WHERE id IN ({$id})";
                            mysql_query($sql, $conn) or die(mysql_error());

                            setMessage("Record has been deleted.", true);
                            system_log(DELETE, "Employee ID: $id deleted.", $userInfo["id"]);
                            unset($id);

                        } else {
                            setMessage("Record can not be deleted. (Remove Sub Accounts)");
                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Sub Accounts)]", $userInfo["id"]);
                        }

                    } else {
                        setMessage("Record can not be deleted. (Remove Transactions)");
                        system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                    }

                } else {
                    setMessage("Record can not be deleted. (Remove Transactions)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
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

<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Employees</td>
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
                <p class="box_title">Manage Employees</p>
                <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                        <tr id="listhead">
                            <td width="10%">ID</td>
                            <td width="20%">Full Name</td>
                            <td width="15%">Contact</td>
                            <td width="20%">Salary & Incentives</td>
                            <td width="15%">Desig.</td>
                            <td width="10%">Status</td>
                            <td width="10%">Action</td>
                        </tr>
                        <?
                            $sql = "select * from employees order by full_name";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                            $numrows = mysql_num_rows($result);

                            if ($numrows > 0) {
                                $canModify = checkPermission($userInfo["id"], MODIFY);
                                $canDelete = checkPermission($userInfo["id"], DELETE);

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
                                    $email_1       = $rs["email_1"];
                                    $email_2       = $rs["email_2"];
                                    $id_num        = $rs["id_num"];
                                    $date_of_birth = $rs["date_of_birth"];
                                    $status        = $rs["status"];
                                    $desig         = $rs["desig"];
                                    $employee_id   = $rs["employee_id"];
                                    $basic_salary  = $rs["basic_salary"];
                                    $incentives    = $rs["incentives"];

                                    echo "<tr>";
                                    echo "<td>$employee_id</td>";
                                    echo "<td>";
                                    echo "$full_name<br />";
                                    echo "<span class='notes'>$street<br />$city, $country</span>";
                                    echo "</td>";
                                    echo "<td>";
                                    echo(!empty($phone_1) ? "$phone_1<br />" : "");
                                    echo(!empty($phone_2) ? "$phone_2<br />" : "");
                                    echo(!empty($mobile_1) ? "$mobile_1<br />" : "");
                                    echo(!empty($mobile_2) ? "$mobile_2<br />" : "");
                                    echo(!empty($email_1) ? "$email_1<br />" : "");
                                    echo(!empty($email_2) ? "$email_2" : "");
                                    echo "</td>";
                                    echo "<td>" . number_format(($basic_salary + $incentives), 0, ".", ",") . "</td>";
                                    echo "<td>$desig</td>";
                                    echo "<td>$status</td>";
                                    echo "<td>";

                                    if ($canModify) {
                                        echo "<a href='index.php?ss=$ss&mod=employees.add&id=$id&cmd=EDIT'>Modify</a>";
                                    }

                                    if ($canDelete) {
                                        echo " | <a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
                                    }

                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>";
                                echo "<td align='center' colspan='7'>No record found.</td>";
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