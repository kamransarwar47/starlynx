<?
    define("MODULE_ID", "PRJ002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "GENERATE") {
            if (!empty($project_id) && !empty($numstart) && !empty($numend) && !empty($plot_type)) {
                $sql = "select title, rate_per_marla from projects where id=\"" . mysql_real_escape_string($project_id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                while ($rs = mysql_fetch_array($result)) {
                    $project_name   = $rs["title"];
                    $rate_per_marla = $rs["rate_per_marla"];
                }

                $cnt = 0;

                for ($i = $numstart; $i <= $numend; $i++) {
                    $plot_number = $i;

                    if (!empty($extra)) {
                        if ($pos == "AFTER") {
                            $plot_number = $plot_number . $extra;
                        }
                        if ($pos == "BEFORE") {
                            $plot_number = $extra . $plot_number;
                        }
                    }

                    if ($new_account == "Y") {
                        // Create new account
                        if ($plot_type == "Residential") {
                            $title = "Plot $plot_number";
                        } else {
                            $title = "$plot_type $plot_number";
                        }

                        $sql = "insert into
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

                    if (empty($account_id) || $account_id == "") {
                        $account_id = 0;
                    }

                    $sql = "insert into
                                plots (
                                    project_id, account_id, plot_number, plot_type, rate_per_marla, created, created_by
                                ) values(
                                    \"" . mysql_real_escape_string($project_id) . "\",
                                    \"" . mysql_real_escape_string($account_id) . "\",
                                    \"" . mysql_real_escape_string($plot_number) . "\",
                                    \"" . mysql_real_escape_string($plot_type) . "\",
                                    \"" . mysql_real_escape_string($rate_per_marla) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $plot_id = mysql_insert_id();

                    /* Kamran Update Start */

                    // creating commission and expense accounts with plot
                    if ($new_sub_accounts == "Y") {
                        // Create Commission Account
                        $title = "Commission";
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
                        $commission_account = mysql_insert_id();

                        system_log(MODIFY, "New account ($title - $commission_account) created.", $userInfo["id"]);

                        // Create Expense Account
                        $title = "Expense";
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
                        $expense_account = mysql_insert_id();

                        system_log(MODIFY, "New account ($title - $expense_account) created.", $userInfo["id"]);

                        // Update plot record
                        $sql = "update
                                plots
                            set
                                commission_account=\"" . mysql_real_escape_string($commission_account) . "\",
                                expense_account=\"" . mysql_real_escape_string($expense_account) . "\",
                                updated=NOW(),
                                updated_by=\"" . $userInfo["id"] . "\"
                            where
                                id=\"" . mysql_real_escape_string($plot_id) . "\"
                            ";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                    }

                    /* Kamran Update End */

                    $cnt++;
                }


                setMessage("$cnt plots ($numstart to $numend) for $project_name have been created.", true);
                system_log(CREATE, "$cnt plots ($numstart to $numend) for $project_name have been created.", $userInfo["id"]);
                unset($cmd);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }
?>

<table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/projects.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Projects
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
                <input type="hidden" name="cmd" value="GENERATE"/>
                <p class="box_title">Generate Plots</p>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Project</strong><br/>
                                    <select name="project_id" id="project_id" onChange="javascript: location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&project_id='+this.value;">
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

                                <p>
                                    <strong>Parent Account</strong><br/>
                                    <?
                                        $actArray = createAccountsArray(getProjectAccountId($project_id));
                                        //print_r($actArray);
                                        echo createPageSelectHTML("master_id", "master_id", $actArray, $master_id);
                                    ?>
                                    <br/>
                                    <input type="checkbox" id="new_account" name="new_account" value="Y"/> Create new
                                    accounts under parent account.<br>
                                    <input type="checkbox" id="new_sub_accounts" name="new_sub_accounts" value="Y"/>
                                    Create "Commission" and "Expense" accounts under plot's account.
                                </p>
                            </td>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Numbering Range & Type</strong><br/>
                                    <input type="text" size="5" name="numstart" id="numstart" maxlength="5"
                                           value="<?= $numstart ?>"/>
                                    <input type="text" size="5" name="numend" id="numend" maxlength="5"
                                           value="<?= $numend ?>"/>
                                    <select name="plot_type" id="plot_type">
                                        <?
                                            $plottypes = array('Residential', 'Shop', 'Other');

                                            for ($i = 0; $i < sizeof($plottypes); $i++) {
                                                $selected = "";

                                                if ($plot_type == $plottypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $plottypes[$i] . "\" $selected>" . $plottypes[$i] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <strong>Add with Number</strong> <span class='notes'>(Optional)</span><br/>
                                    <input type="text" size="13" name="extra" id="extra" maxlength="10"
                                           value="<?= $extra ?>"/>
                                    <select name="pos" id="pos">
                                        <option value="AFTER">After Number</option>
                                        <option value="BEFORE">Before Number</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" value="Generate"/>
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
<script>
    $(document).ready(function () {
        $('#new_sub_accounts').click(function () {
            if ($(this).is(':checked')) {
                $('#new_account').prop("checked", true);
            }
        });
    });
</script>