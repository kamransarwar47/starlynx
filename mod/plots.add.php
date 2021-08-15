<?
    define("MODULE_ID", "PRJ004");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if(empty($master_id) || $master_id == 0 || $master_id == "0") {
        $master_id = 0;
    }

    /*
    if(checkPermission($userInfo["id"], CREATE)) {
        if($cmd == "ADD") {
            if(!empty($title) && !empty($rate_per_marla)) {
                $sql = "select id from projects where title=\"".mysql_real_escape_string($title)."\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if($numrows <= 0) {
                    $sql = "insert into
                                projects (
                                    title, description, rate_per_marla, created, created_by
                                ) values (
                                    \"".mysql_real_escape_string($title)."\",
                                    \"".mysql_real_escape_string($description)."\",
                                    \"".mysql_real_escape_string($rate_per_marla)."\",
                                    NOW(),
                                    \"".$userInfo["id"]."\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    setMessage("$title has been added successfully.", true);
                    system_log(CREATE, "$title created.", $userInfo["id"]);
                    unset($cmd, $title, $description, $rate_per_marla);
                } else {
                    setMessage("$title is already registered in the system.");
                    system_log(CREATE, "Operation failed. [Reason: $title already exists.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }
    */

    if(checkPermission($userInfo["id"], MODIFY)) {
        if($cmd == "UPDATE") {
            if(!empty($id) && !empty($project_id) && !empty($plot_number) && !empty($plot_type) && !empty($size) && !empty($size_type) && !empty($width) && !empty($length) && !empty($rate_per_marla) && !empty($status)) {
                if($new_account == "Y") {
                        // Create new account
                        $title = "Plot $plot_number";
                        $sql = "insert into
                                    accounts (
                                        title, master_id, created, created_by
                                    ) values (
                                        \"".mysql_real_escape_string($title)."\",
                                        \"".mysql_real_escape_string($master_id)."\",
                                        NOW(),
                                        \"".$userInfo["id"]."\"
                                    )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $account_id = mysql_insert_id();

                        system_log(MODIFY, "New account ($title - $account_id) created.", $userInfo["id"]);
                    } else {
                        $account_id = $master_id;
                    }

                if($new_sub_accounts == "Y") {
                    // Create Commission Account
                    $title = "Commission";
                    $sql = "insert into
                                    accounts (
                                        title, master_id, created, created_by
                                    ) values (
                                        \"".mysql_real_escape_string($title)."\",
                                        \"".mysql_real_escape_string($account_id)."\",
                                        NOW(),
                                        \"".$userInfo["id"]."\"
                                    )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $commission_account = mysql_insert_id();

                    system_log(MODIFY, "New account ($title - $commission_account) created.", $userInfo["id"]);

                    // Create Expense Account
                    $title = "Expense";
                    $sql = "insert into
                                    accounts (
                                        title, master_id, created, created_by
                                    ) values (
                                        \"".mysql_real_escape_string($title)."\",
                                        \"".mysql_real_escape_string($account_id)."\",
                                        NOW(),
                                        \"".$userInfo["id"]."\"
                                    )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $expense_account = mysql_insert_id();

                    system_log(MODIFY, "New account ($title - $expense_account) created.", $userInfo["id"]);

                    // Update plot record
                    $sql = "update
                                plots
                            set
                                commission_account=\"".mysql_real_escape_string($commission_account)."\",
                                expense_account=\"".mysql_real_escape_string($expense_account)."\",
                                updated=NOW(),
                                updated_by=\"".$userInfo["id"]."\"
                            where
                                id=\"".mysql_real_escape_string($id)."\"
                            ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                }

                // additional update
                $additional_update = "";
                if($status == "VACANT") {
                    $additional_update = "customer_id=0,";
                }

                $sql = "update
                            plots
                        set
                            project_id=\"".mysql_real_escape_string($project_id)."\",
                            $additional_update
                            account_id=\"".mysql_real_escape_string($account_id)."\",
                            plot_number=\"".mysql_real_escape_string($plot_number)."\",
                            plot_type=\"".mysql_real_escape_string($plot_type)."\",
                            size=\"".mysql_real_escape_string($size)."\",
                            size_type=\"".mysql_real_escape_string($size_type)."\",
                            width=\"".mysql_real_escape_string($width)."\",
                            length=\"".mysql_real_escape_string($length)."\",
                            rate_per_marla=\"".mysql_real_escape_string($rate_per_marla)."\",
                            status=\"".mysql_real_escape_string($status)."\",
                            notes=\"".mysql_real_escape_string($notes)."\",
                            updated=NOW(),
                            updated_by=\"".$userInfo["id"]."\"
                        where
                            id=\"".mysql_real_escape_string($id)."\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                // Update features
                $sql = "delete from plots_features where plot_id='$id'";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                for($i=0; $i<sizeof($plot_features); $i++) {
                    $sql = "insert into
                                plots_features (
                                    plot_id, feature_id, created, created_by
                                ) values (
                                    \"".mysql_real_escape_string($id)."\",
                                    \"".mysql_real_escape_string($plot_features[$i])."\",
                                    NOW(),
                                    \"".$userInfo["id"]."\"
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                }

                setMessage("Plot number $plot_number has been updated successfully.", true);
                system_log(MODIFY, "$plot_number ($project_id) updated.", $userInfo["id"]);
                $cmd = "EDIT";
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if($cmd == "EDIT") {
            $cmd = "";

            if(!empty($id)) {
                $sql = "select * from plots where id=\"".mysql_real_escape_string($id)."\" and project_id=\"".mysql_real_escape_string($project_id)."\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if($numrows > 0) {
                    while($rs = mysql_fetch_array($result)) {
                        $id = $rs["id"];
                        $master_id = $rs["account_id"];
                        $plot_number = $rs["plot_number"];
                        $plot_type = $rs["plot_type"];
                        $size = $rs["size"];
                        $size_type = $rs["size_type"];
                        $width = $rs["width"];
                        $length = $rs["length"];
                        $rate_per_marla = $rs["rate_per_marla"];
                        $status = $rs["status"];
                        $notes = $rs["notes"];
                        $discount = $rs["discount"];
                        $discount_type = $rs["discount_type"];
                        $commission = $rs["commission"];
                        $commission_type = $rs["commission_type"];
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

    if(empty($cmd)) {
        $cmd = "ADD";
    }
?>

<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/projects.png" width="20" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Projects</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?=showError()?>
                <form action="index.php" method="post">
                <input type="hidden" name="ss" value="<?=$ss?>" />
                <input type="hidden" name="mod" value="<?=$mod?>" />
                <input type="hidden" name="cmd" value="<?=$cmd?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <p class="box_title"><?=($cmd=="ADD"?"Create New":"Modify")?> Plot</p>
                <? if(checkPermission($userInfo["id"], CREATE)) { ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td width="33%" valign="top">
                            <p>
                                <strong>Project</strong><br />
                                <select name="project_id" id="project_id" onChange="javascript: location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&id=<?=$id?>&project_id='+this.value+'&cmd=EDIT';">
                                <?
                                    $sql = "select id, title from projects order by title";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());

                                    while($rs = mysql_fetch_array($result)) {
                                        $selected = "";

                                        if($project_id == $rs["id"]) {
                                            $selected = "selected='selected'";
                                        }

                                        echo "<option value=\"".$rs["id"]."\" $selected>".$rs["title"]."</option>";
                                    }
                                ?>
                                </select>
                            </p>

                            <p>
                                <strong>Plot Number & Type</strong><br />
                                <input type="text" size="10" name="plot_number" id="plot_number" maxlength="10" value="<?=$plot_number?>" />
                                <select name="plot_type" id="plot_type">
                                    <?
                                        $plottypes = array('Residential', 'Shop', 'Other');

                                        for($i=0; $i<sizeof($plottypes); $i++) {
                                            $selected = "";

                                            if($plot_type == $plottypes[$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$plottypes[$i]."\" $selected>".$plottypes[$i]."</option>";
                                        }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <strong>Size</strong><br />
                                <input type="text" size="10" name="size" id="size" maxlength="5" value="<?=$size?>" />
                                <select name="size_type" id="size_type">
                                    <?
                                        $sizetypes = array('Marla', 'Kanal');

                                        for($i=0; $i<sizeof($sizetypes); $i++) {
                                            $selected = "";

                                            if($plot_type == $sizetypes[$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$sizetypes[$i]."\" $selected>".$sizetypes[$i]."</option>";
                                        }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <strong>Dimensions</strong> <span class="notes">(Width x Length)</span><br />
                                <input type="text" size="10" name="width" id="width" maxlength="6" value="<?=$width?>" />
                                <input type="text" size="10" name="length" id="length" maxlength="6" value="<?=$length?>" />
                            </p>
                        </td>
                        <td width="34%" valign="top">
                            <p>
                                <strong>Rate</strong><br />
                                <input type="text" size="30" name="rate_per_marla" id="rate_per_marla" maxlength="11" value="<?=$rate_per_marla?>" />
                            </p>
                            <p>
                                <strong>Status</strong><br />
                                <select name="status" id="status">
                                    <?
                                        $statuses = array('VACANT', 'SOLD', 'RESERVED', 'REGISTERED');

                                        for($i=0; $i<sizeof($statuses); $i++) {
                                            $selected = "";

                                            if($status == $statuses[$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$statuses[$i]."\" $selected>".$statuses[$i]."</option>";
                                        }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <strong>Plot Features</strong><br />
                                <?
                                    $sql = "select id, title, price, price_type from lookup_plot_features";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());

                                    while($rs = mysql_fetch_array($result)) {
                                        $xfeature_id = $rs["id"];
                                        $xfeature_title = $rs["title"];
                                        $xfeature_price = $rs["price"];
                                        $xfeature_pricetype = $rs["price_type"];

                                        if($xfeature_pricetype == "F") {
                                            $xftitle = "$xfeature_title <span class='notes'>($xfeature_price)</span>";
                                        } elseif ($xfeature_pricetype == "P") {
                                            $xftitle = "$xfeature_title <span class='notes'>($xfeature_price%)</span>";
                                        }

                                        $checked = "";

                                        if($cmd == "UPDATE") {
                                            $sql2 = "select id from plots_features where plot_id='$id' and feature_id='$xfeature_id'";
                                            $result2 = mysql_query($sql2, $conn) or die(mysql_error());
                                            $numrows2 = mysql_num_rows($result2);


                                            if($numrows2 > 0) {
                                                $checked = "checked='checked'";
                                            }
                                        }

                                        echo "<input type='checkbox' name='plot_features[]' value='$xfeature_id' $checked /> $xftitle<br />";
                                    }
                                ?>
                            </p>
                        </td>
                        <td width="33%" valign="top">
                            <p>
                                <strong>Notes</strong> <span class="notes">(Optional)</span><br />
                                <textarea rows="7" cols="30" name="notes" id="notes"><?=$notes?></textarea>
                            </p>
                            <p>
                                <strong>Discount</strong> <span class="notes">(Optional)</span><br />
                                <input type="text" size="10" name="discount" id="discount" maxlength="10" value="<?=$discount?>" />
                                <select name="discount_type" id="discount_type">
                                    <?
                                        $dtypes = array("P" => "Percent", "F" => "Fixed");

                                        foreach($dtypes as $key => $val) {
                                            $selected = "";

                                            if($discount_type == $key) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$key."\" $selected>".$val."</option>";
                                        }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <strong>Commission</strong> <span class="notes">(Optional)</span><br />
                                <input type="text" size="10" name="commission" id="commission" maxlength="10" value="<?=$commission?>" />
                                <select name="commission_type" id="commission_type">
                                    <?
                                        $ctypes = array("P" => "Percent", "F" => "Fixed");

                                        foreach($ctypes as $key => $val) {
                                            $selected = "";

                                            if($commission_type == $key) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$key."\" $selected>".$val."</option>";
                                        }
                                    ?>
                                </select>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>
                                <strong>Parent Account</strong> <span class="notes" style="color: #ff0000;">(Use this section carefully.)</span><br />
                                <?
                                    $actArray = createAccountsArray(getProjectAccountId($project_id));
                                    //print_r($actArray);
                                    echo createPageSelectHTML("master_id", "master_id", $actArray, $master_id);
                                ?>
                                <br />
                                <input type="checkbox" name="new_account" id="new_account" value="Y" /> Create new account under parent account.<br />
                                <input type="checkbox" name="new_sub_accounts" id="new_sub_accounts" value="Y" /> Create "Commission" and "Expense" accounts under plot's account.
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
<script>
    $(document).ready(function () {
        $('#new_sub_accounts').click(function() {
            if ($(this).is(':checked')) {
                $('#new_account').prop("checked", true);
            }
        });
    });
</script>