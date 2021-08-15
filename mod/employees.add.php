<?
    define("MODULE_ID", "ACT005");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($full_name) && !empty($father_name) && !empty($id_num) && !empty($date_of_birth) && !empty($street) && !empty($city) && !empty($country) && !empty($basic_salary) && !empty($joining_date) && !empty($status) && !empty($project_id) && !empty($employee_id) && !empty($desig)) {
                $sql = "select id from employees where id_num=\"" . mysql_real_escape_string($id_num) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows <= 0) {
                    if ($new_account == "Y") {
                        // Create new account
                        $title = $full_name;
                        $sql   = "insert into
                                    accounts (
                                        title, master_id, created, created_by
                                    ) values (
                                        \"" . mysql_real_escape_string($title) . "\",
                                        \"" . mysql_real_escape_string($master_id) . "\",
                                        NOW(),
                                        \"" . $userInfo["id"] . "\"
                                    )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $account_id = mysql_insert_id();

                        system_log(CREATE, "New account ($title - $account_id) created.", $userInfo["id"]);
                    } else {
                        $account_id = $master_id;
                    }

                    if ($new_sub_accounts == "Y") {
                        // Create Loan Account
                        $title = "Loan / Advances";
                        $sql   = "insert into
                                        accounts (
                                            title, master_id, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($title) . "\",
                                            \"" . mysql_real_escape_string($account_id) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $loan_account = mysql_insert_id();

                        system_log(CREATE, "New account ($title - $loan_account) created.", $userInfo["id"]);

                        // Create Salary Account
                        $title = "Salary";
                        $sql   = "insert into
                                        accounts (
                                            title, master_id, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($title) . "\",
                                            \"" . mysql_real_escape_string($account_id) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $salary_account = mysql_insert_id();

                        system_log(CREATE, "New account ($title - $salary_account) created.", $userInfo["id"]);
                    }

                    $sql = "insert into
                                employees (
                                    project_id, account_id, loan_account, salary_account, full_name, father_name, street, city, zip, state, country,
                                    phone_1, phone_2, mobile_1, mobile_2, email_1, email_2,
                                    id_num, date_of_birth, joining_date, resign_date, status, basic_salary, incentives, employee_id, desig,
                                    created, created_by
                                ) values (
                                    \"" . mysql_real_escape_string($project_id) . "\",
                                    \"" . mysql_real_escape_string($account_id) . "\",
                                    \"" . mysql_real_escape_string($loan_account) . "\",
                                    \"" . mysql_real_escape_string($salary_account) . "\",
                                    \"" . mysql_real_escape_string($full_name) . "\",
                                    \"" . mysql_real_escape_string($father_name) . "\",
                                    \"" . mysql_real_escape_string($street) . "\",
                                    \"" . mysql_real_escape_string($city) . "\",
                                    \"" . mysql_real_escape_string($zip) . "\",
                                    \"" . mysql_real_escape_string($state) . "\",
                                    \"" . mysql_real_escape_string($country) . "\",
                                    \"" . mysql_real_escape_string($phone_1) . "\",
                                    \"" . mysql_real_escape_string($phone_2) . "\",
                                    \"" . mysql_real_escape_string($mobile_1) . "\",
                                    \"" . mysql_real_escape_string($mobile_2) . "\",
                                    \"" . mysql_real_escape_string($email_1) . "\",
                                    \"" . mysql_real_escape_string($email_2) . "\",
                                    \"" . mysql_real_escape_string($id_num) . "\",
                                    \"" . mysql_real_escape_string($date_of_birth) . "\",
                                    \"" . mysql_real_escape_string($joining_date) . "\",
                                    \"" . mysql_real_escape_string($resign_date) . "\",
                                    \"" . mysql_real_escape_string($status) . "\",
                                    \"" . mysql_real_escape_string($basic_salary) . "\",
                                    \"" . mysql_real_escape_string($incentives) . "\",
                                    \"" . mysql_real_escape_string($employee_id) . "\",
                                    \"" . mysql_real_escape_string($desig) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$full_name has been added successfully.", true);
                    system_log(CREATE, "$full_name created.", $userInfo["id"]);
                    unset($cmd, $account_id, $loan_account, $salary_account, $full_name, $father_name, $street, $city, $zip, $state, $country, $phone_1, $phone_2, $mobile_1, $mobile_2, $email_1, $email_2, $id_num, $date_of_birth, $joining_date, $resign_date, $basic_salary, $incentives, $status, $project_id, $employee_id, $desig);
                } else {
                    setMessage("$id_num is already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: $id_num already exists.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($full_name) && !empty($father_name) && !empty($id_num) && !empty($date_of_birth) && !empty($street) && !empty($city) && !empty($country) && !empty($basic_salary) && !empty($joining_date) && !empty($status) && !empty($project_id) && !empty($employee_id) && !empty($desig)) {
                if ($new_account == "Y") {
                    // Create new account
                    $title = $full_name;
                    $sql   = "insert into
                                    accounts (
                                        title, master_id, created, created_by
                                    ) values (
                                        \"" . mysql_real_escape_string($title) . "\",
                                        \"" . mysql_real_escape_string($master_id) . "\",
                                        NOW(),
                                        \"" . $userInfo["id"] . "\"
                                    )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $account_id = mysql_insert_id();

                    system_log(CREATE, "New account ($title - $account_id) created.", $userInfo["id"]);
                } else {
                    $account_id = $master_id;
                }

                if ($new_sub_accounts == "Y") {
                    // Create Loan Account
                    $title = "Loan / Advances";
                    $sql   = "insert into
                                        accounts (
                                            title, master_id, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($title) . "\",
                                            \"" . mysql_real_escape_string($account_id) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $loan_account = mysql_insert_id();

                    system_log(CREATE, "New account ($title - $loan_account) created.", $userInfo["id"]);

                    // Create Salary Account
                    $title = "Salary";
                    $sql   = "insert into
                                        accounts (
                                            title, master_id, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($title) . "\",
                                            \"" . mysql_real_escape_string($account_id) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $salary_account = mysql_insert_id();

                    system_log(CREATE, "New account ($title - $salary_account) created.", $userInfo["id"]);

                    // Update employee record
                    $sql = "update
                                    employees
                                set
                                    loan_account=\"" . mysql_real_escape_string($loan_account) . "\",
                                    salary_account=\"" . mysql_real_escape_string($salary_account) . "\",
                                    updated=NOW(),
                                    updated_by=\"" . $userInfo["id"] . "\"
                                where
                                    id=\"" . mysql_real_escape_string($id) . "\"
                                ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                }

                $sql = "update
                            employees
                        set
                            project_id=\"" . mysql_real_escape_string($project_id) . "\",
                            account_id=\"" . mysql_real_escape_string($account_id) . "\",
                            full_name=\"" . mysql_real_escape_string($full_name) . "\",
                            father_name=\"" . mysql_real_escape_string($father_name) . "\",
                            street=\"" . mysql_real_escape_string($street) . "\",
                            city=\"" . mysql_real_escape_string($city) . "\",
                            zip=\"" . mysql_real_escape_string($zip) . "\",
                            state=\"" . mysql_real_escape_string($state) . "\",
                            country=\"" . mysql_real_escape_string($country) . "\",
                            phone_1=\"" . mysql_real_escape_string($phone_1) . "\",
                            phone_2=\"" . mysql_real_escape_string($phone_2) . "\",
                            mobile_1=\"" . mysql_real_escape_string($mobile_1) . "\",
                            mobile_2=\"" . mysql_real_escape_string($mobile_2) . "\",
                            email_1=\"" . mysql_real_escape_string($email_1) . "\",
                            email_2=\"" . mysql_real_escape_string($email_2) . "\",
                            id_num=\"" . mysql_real_escape_string($id_num) . "\",
                            date_of_birth=\"" . mysql_real_escape_string($date_of_birth) . "\",
                            joining_date=\"" . mysql_real_escape_string($joining_date) . "\",
                            resign_date=\"" . mysql_real_escape_string($resign_date) . "\",
                            status=\"" . mysql_real_escape_string($status) . "\",
                            basic_salary=\"" . mysql_real_escape_string($basic_salary) . "\",
                            incentives=\"" . mysql_real_escape_string($incentives) . "\",
                            employee_id=\"" . mysql_real_escape_string($employee_id) . "\",
                            desig=\"" . mysql_real_escape_string($desig) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                setMessage("$full_name has been updated successfully.", true);
                system_log(MODIFY, "$full_name updated.", $userInfo["id"]);
                unset($account_id, $loan_account, $salary_account, $full_name, $father_name, $street, $city, $zip, $state, $country, $phone_1, $phone_2, $mobile_1, $mobile_2, $email_1, $email_2, $id_num, $date_of_birth, $joining_date, $resign_date, $basic_salary, $incentives, $status, $project_id, $employee_id, $desig);
                $cmd = "EDIT";
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "";

            if (!empty($id)) {
                $sql = "select * from employees where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id            = $rs["id"];
                        if(!isset($project_id) || $project_id == "") {
                            $project_id    = $rs["project_id"];
                        }
                        $master_id     = $rs["account_id"];
                        $full_name     = $rs["full_name"];
                        $father_name   = $rs["father_name"];
                        $street        = $rs["street"];
                        $city          = $rs["city"];
                        $zip           = $rs["zip"];
                        $state         = $rs["state"];
                        $country       = $rs["country"];
                        $phone_1       = $rs["phone_1"];
                        $phone_2       = $rs["phone_2"];
                        $mobile_1      = $rs["mobile_1"];
                        $mobile_2      = $rs["mobile_2"];
                        $email_1       = $rs["email_1"];
                        $email_2       = $rs["email_2"];
                        $id_num        = $rs["id_num"];
                        $date_of_birth = $rs["date_of_birth"];
                        $joining_date  = $rs["joining_date"];
                        $resign_date   = $rs["resign_date"];
                        $status        = $rs["status"];
                        $basic_salary  = $rs["basic_salary"];
                        $incentives    = $rs["incentives"];
                        $employee_id   = $rs["employee_id"];
                        $desig         = $rs["desig"];
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
        $("#date_of_birth, #joining_date, #resign_date").datepicker({
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
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Employees
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
                <p class="box_title"><?= ($cmd == "ADD" ? "Create New" : "Modify") ?> Employee</p>
                <? if (checkPermission($userInfo["id"], CREATE) || checkPermission($userInfo["id"], MODIFY)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="33%" valign="top">
                                <p>
                                    <strong>Full Name</strong><br/>
                                    <input type="text" size="30" name="full_name" id="full_name" maxlength="100"
                                           value="<?= $full_name ?>"/>
                                </p>
                                <p>
                                    <strong>Father / Husband Name</strong><br/>
                                    <input type="text" size="30" name="father_name" id="father_name" maxlength="100"
                                           value="<?= $father_name ?>"/>
                                </p>
                                <p>
                                    <strong>National ID</strong><br/>
                                    <input type="text" size="30" name="id_num" id="id_num" maxlength="20"
                                           value="<?= $id_num ?>"/>
                                </p>
                                <p>
                                    <strong>Date of Birth</strong> <span class="notes">(format: YYYY-MM-DD)</span><br/>
                                    <input type="text" size="30" name="date_of_birth" id="date_of_birth" maxlength="10"
                                           value="<?= $date_of_birth ?>"/>
                                </p>
                            </td>
                            <td width="34%" valign="top">
                                <p>
                                    <strong>Address</strong><br/>
                                    <input type="text" size="30" name="street" id="street" maxlength="255"
                                           value="<?= $street ?>"/>
                                </p>
                                <p>
                                    <strong>City & Zip / Post Code</strong> <span class="notes">(Zip / Post Code is optional)</span><br/>
                                    <select name="city" id="city">
                                        <?
                                            $sql = "select id, title from cities order by title";
                                            $result = mysql_query($sql, $conn) or die(mysql_error());

                                            while ($rs = mysql_fetch_array($result)) {
                                                $selected = "";

                                                if ($city == $rs["id"]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $rs["id"] . "\" $selected>" . $rs["title"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                    <input type="text" size="8" name="zip" id="zip" maxlength="10" value="<?= $zip ?>"/>
                                </p>
                                <p>
                                    <strong>State / Province</strong> <span class="notes">(optional)</span><br/>
                                    <input type="text" size="30" name="state" id="state" maxlength="30"
                                           value="<?= $state ?>"/>
                                </p>
                                <p>
                                    <strong>Country</strong><br/>
                                    <select name="country" id="country">
                                        <?
                                            $sql = "select id, title from countries order by title";
                                            $result = mysql_query($sql, $conn) or die(mysql_error());

                                            while ($rs = mysql_fetch_array($result)) {
                                                $selected = "";

                                                if ($country == $rs["id"]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $rs["id"] . "\" $selected>" . $rs["title"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>
                            </td>
                            <td width="33%" valign="top">
                                <p>
                                    <strong>Phone(s)</strong> <span class="notes">(optional)</span><br/>
                                    <input type="text" size="15" name="phone_1" id="phone_1" maxlength="20"
                                           value="<?= $phone_1 ?>"/>
                                    <input type="text" size="15" name="phone_2" id="phone_2" maxlength="20"
                                           value="<?= $phone_2 ?>"/>
                                </p>
                                <p>
                                    <strong>Mobile Phone(s)</strong> <span class="notes">(optional)</span><br/>
                                    <input type="text" size="15" name="mobile_1" id="mobile_1" maxlength="20"
                                           value="<?= $mobile_1 ?>"/>
                                    <input type="text" size="15" name="mobile_2" id="mobile_2" maxlength="20"
                                           value="<?= $mobile_2 ?>"/>
                                </p>
                                <p>
                                    <strong>Email(s)</strong> <span class="notes">(optional)</span><br/>
                                    <input type="text" size="15" name="email_1" id="email_1" maxlength="50"
                                           value="<?= $email_1 ?>"/>
                                    <input type="text" size="15" name="email_2" id="email_2" maxlength="50"
                                           value="<?= $email_2 ?>"/>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Basic Salary</strong><br/>
                                    <input type="text" size="20" name="basic_salary" id="basic_salary" maxlength="10"
                                           value="<?= $basic_salary ?>"/>
                                </p>
                                <p>
                                    <strong>Incentives</strong> <span class="notes">(optional)</span><br/>
                                    <input type="text" size="20" name="incentives" id="incentives" maxlength="10"
                                           value="<?= $incentives ?>"/>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Date of Joining</strong> <span
                                            class="notes">(format: YYYY-MM-DD)</span><br/>
                                    <input type="text" size="30" name="joining_date" id="joining_date" maxlength="10"
                                           value="<?= $joining_date ?>"/>
                                </p>
                                <p>
                                    <strong>Date of Resign</strong> <span
                                            class="notes">(format: YYYY-MM-DD) optional</span><br/>
                                    <input type="text" size="30" name="resign_date" id="resign_date" maxlength="10"
                                           value="<?= $resign_date ?>"/>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Status</strong><br/>
                                    <select id="status" name="status">
                                        <?
                                            $estatuses = array('EMPLOYED', 'RESIGNED', 'FIRED');

                                            for ($i = 0; $i < sizeof($estatuses); $i++) {
                                                $selected = "";

                                                if ($status == $estatuses[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value='" . $estatuses[$i] . "' $selected>" . $estatuses[$i] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <strong>Project</strong><br/>
                                    <select name="project_id" id="project_id"
                                            onChange="javascript: location.href='index.php?ss=<?= $ss ?>&mod=<?= $mod ?>&project_id='+this.value+'<?= ($cmd == "UPDATE") ? '&id=' . $id . '&cmd=EDIT' : ''; ?>';">
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
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Employee ID</strong><br/>
                                    <input type="text" size="20" name="employee_id" id="employee_id" maxlength="10"
                                           value="<?= $employee_id ?>"/>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Designation</strong><br/>
                                    <input type="text" size="20" name="desig" id="desig" maxlength="30"
                                           value="<?= $desig ?>"/>
                                </p>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                    <p>
                        <strong>Parent Account</strong><br/>
                        <?
                            if (isset($project_id) && $project_id != "") {
                                $actArray = createAccountsArray(getProjectAccountId($project_id));
                            } else {
                                $actArray = [];
                            }
                            //print_r($actArray);
                            echo createPageSelectHTML("master_id", "master_id", $actArray, $master_id);
                        ?>
                        <br/>
                        <input type="checkbox" name="new_account" value="Y"/> Create new accounts under parent
                        account.<br/>
                        <input type="checkbox" name="new_sub_accounts" value="Y"/> Create "Loan / Advances" and "Salary"
                        accounts under employee's account.
                    </p>
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