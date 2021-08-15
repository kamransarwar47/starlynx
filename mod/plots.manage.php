<?
    define("MODULE_ID", "PRJ003");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                $plot_accounts = [];
                $delete_plot   = false;
                // get account_id of the plot
                $account_id = getPlotAccountId($id);

                // get all account ids linked to the plot
                $sql = "SELECT id FROM accounts WHERE master_id = '{$account_id}' OR id = '{$account_id}'";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                if (mysql_num_rows($result) > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $plot_accounts[] = $rs['id'];
                    }
                    $plot_accounts = implode(',', $plot_accounts);

                    // checking all transactions linked to the plot accounts from transactions_details
                    $sql = "SELECT id FROM transactions_details WHERE account_id IN ({$plot_accounts})";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    if ($numrows <= 0) {

                        // Sub Accounts check
                        $sql = "SELECT id FROM accounts WHERE master_id='{$account_id}'";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows <= 0) {
                            // Delete plot
                            $delete_plot = true;
                        } else {
                            setMessage("Record can not be deleted. (Remove Sub Accounts)");
                            system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Sub Accounts)]", $userInfo["id"]);
                        }

                    } else {
                    setMessage("Record can not be deleted. (Remove Transactions)");
                    system_log(DELETE, "Operation failed. [Reason: Record can not be deleted. (Remove Transactions)]", $userInfo["id"]);
                }
            } else {
                // delete plot
                $delete_plot = true;
            }

            // Delete plot data
            if ($delete_plot) {

                // Delete Plot account
                $sql = "DELETE FROM accounts WHERE id='{$account_id}'";
                mysql_query($sql, $conn) or die(mysql_error());

                // Delete Plot features
                $sql = "DELETE FROM plots_features WHERE plot_id IN ({$id})";
                mysql_query($sql, $conn) or die(mysql_error());

                // Delete Plot dues
                $sql = "DELETE FROM plots_dues WHERE plot_id IN ({$id})";
                mysql_query($sql, $conn) or die(mysql_error());

                // Delete Plot
                $sql = "DELETE FROM plots WHERE id IN ({$id})";
                mysql_query($sql, $conn) or die(mysql_error());

                setMessage("Record has been deleted.", true);
                system_log(DELETE, "Plot ID: $id deleted.", $userInfo["id"]);
                unset($id);
            }

        } else {
            setMessage("Nothing to load...");
            system_log(DELETE, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
        }
    }
    }
?>

<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
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
                <p class="box_title">Manage Plots</p>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
            <p>
                <strong>Project</strong><br/>
                <select name="project_id" id="project_id"
                        onChange="javascript: location.href='index.php?ss=<?= $ss ?>&mod=<?= $mod ?>&project_id='+this.value;">
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
            <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                   style="margin-top: 10px;">
                <tr id="listhead">
                    <td width="10%">Type</td>
                    <td width="10%">Plot Number</td>
                    <td width="10%">Size</td>
                    <td width="10%">Rate/Marla</td>
                    <td width="10%">Features</td>
                    <td width="10%">Value</td>
                    <td width="10%">Status</td>
                    <td width="15%">Allotted To</td>
                    <td width="15%">Action</td>
                </tr>
                <?
                    $sql = "select * from plots where project_id=\"" . mysql_real_escape_string($project_id) . "\" order by plot_type asc, id asc";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);

                    if ($numrows > 0) {
                    $canModify = checkPermission($userInfo["id"], MODIFY);
                    $canDelete = checkPermission($userInfo["id"], DELETE);
                    $canViewBooking = checkPermission2($userInfo["id"], VIEW, "PRJ005");
                    $canBook = checkPermission2($userInfo["id"], MODIFY, "PRJ005");
                    $canPrint = checkPermission2($userInfo["id"], PRNT, "PRJ005");
                    $canViewStatement = checkPermission2($userInfo["id"], VIEW, "RPT001");

                    while ($rs = mysql_fetch_array($result)) {
                    $id = $rs["id"];
                    $customer = getCustomerName($rs["customer_id"]);
                    $plot_number = $rs["plot_number"];
                    $plot_type = $rs["plot_type"];
                    $size = $rs["size"];
                    $size_type = $rs["size_type"];
                    $width = $rs["width"];
                    $length = $rs["length"];
                    $rate_per_marla = $rs["rate_per_marla"];
                    $status = $rs["status"];
                    $notes = $rs["notes"];

                    $plot_value = $rate_per_marla * $size;
                    $plot_features = "";

                    $sql2 = "select feature_id from plots_features where plot_id='$id'";
                    $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                    while ($rs2 = mysql_fetch_array($result2)) {
                    $feature_id = $rs2["feature_id"];

                    $sql3 = "select title, price, price_type from lookup_plot_features where id='$feature_id'";
                    $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                    while ($rs3 = mysql_fetch_array($result3)) {
                    $feature_title = $rs3["title"];
                    $feature_price = $rs3["price"];
                    $feature_pricetype = $rs3["price_type"];

                    if ($feature_pricetype == "F") {
                    $plot_value += $feature_price;
                    $plot_features .= "$feature_title <span class='notes'>($feature_price)</span><br />";
                    } elseif ($feature_pricetype == "P") {
                    $pval = ($plot_value * $feature_price) / 100;
                    $plot_value += $pval;
                    $plot_features .= "$feature_title <span class='notes'>($feature_price%)</span><br />";
                    }
                    }
                    }

                    switch ($status) {
                    case "VACANT":
                    $style = "background-color: #EEFFEE;";
                    break;

                    case "SOLD":
                    $style = "background-color: #FFEEFF;";
                    break;

                    case "RESERVED":
                    $style = "background-color: #FFFFDD;";
                    break;

                    default:
                    $style = "";
                    break;
                    }

                    echo "<tr style='$style'>";
                    echo "<td>$plot_type</td>";
                    echo "<td>";
                    echo "$plot_number";

                    if (!empty($notes)) {
                    echo "<br /><span class='notes'>$notes</span>";
                    }

                    echo "</td>";
                    echo "<td>$size $size_type<br /><span class='notes'>$width x $length</span></td>";
                    echo "<td>" . number_format($rate_per_marla, 0, ".", ",") . "</td>";
                    echo "<td>$plot_features</td>";
                    echo "<td>" . number_format($plot_value, 0, ".", ",") . "</td>";
                    echo "<td>$status</td>";
                    echo "<td>$customer</td>";
                    echo "<td>";

                    if ($canBook) {
                    echo "<a href='index.php?ss=$ss&mod=bookings.add&plot_id=$id&project_id=$project_id&cmd=EDIT'>Book</a>";
                    }

                    if ($canViewBooking) {
                    echo " | <a href='index.php?ss=$ss&mod=bookings.view&plot_id=$id'>View</a>";
                    }

                    if ($canViewStatement) {
                    echo " | <a href='index.php?ss=$ss&mod=rpt.plots.statement&project_id=$project_id&plot_id=$id'>Statement</a>";
                    }

                    if ($canPrint) {
                    echo " | <a href='print.php?ss=$ss&printmod=plots.booking&plot_id=$id' target='prn'>Print</a>";
                    }

                    if ($canModify) {
                    echo " | <a href='index.php?ss=$ss&mod=plots.add&id=$id&project_id=$project_id&cmd=EDIT'>Modify</a>";
                    }

                    if ($canDelete) {
                    echo " | <a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
                    }

                    echo "</td>";
                    echo "</tr>";
                    }
                    } else {
                    echo "<tr>";
                    echo "<td align='center' colspan='9'>No record found.</td>";
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