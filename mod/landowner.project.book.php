<?
    define("MODULE_ID", "LDO003");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id)) {
                // Update project installment plan
                $sql = "delete from landowner_projects_dues where landowner_projects_id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                for ($i = 0; $i < sizeof($amount); $i++) {
                    if (!empty($amount[$i]) & !empty($due_on[$i]) && !empty($status[$i])) {
                        $sql = "insert into
                                        landowner_projects_dues (
                                            landowner_projects_id, amount, due_on, notes, status, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($id) . "\",
                                            \"" . mysql_real_escape_string($amount[$i]) . "\",
                                            \"" . mysql_real_escape_string($due_on[$i]) . "\",
                                            \"" . mysql_real_escape_string($notes[$i]) . "\",
                                            \"" . mysql_real_escape_string($status[$i]) . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                    }
                }
                setMessage("Record updated.", true);
                system_log(MODIFY, "Plot record updated.", $userInfo["id"]);
                unset($amount, $due_on, $notes, $status);
                $cmd = "EDIT";
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if ($cmd == "EDIT") {
            $cmd = "UPDATE";

            if (!empty($id)) {
                $sql = "select * from landowner_projects_dues where landowner_projects_id=\"" . mysql_real_escape_string($id) . "\" order by due_on asc";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                $total   = "0.00";
                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $amount[] = $rs["amount"];
                        $due_on[] = $rs["due_on"];
                        $notes[]  = $rs["notes"];
                        $status[] = $rs["status"];
                        $total    += $rs["amount"];
                    }

                    system_log(MODIFY, "$id loaded for editing.", $userInfo["id"]);
                } else {
                    setMessage("No Record found.");
                    system_log(MODIFY, "Operation failed. [Reason: No Record found.]", $userInfo["id"]);
                }
                // get landowner details
                $account_id     = '';
                $landowner_name = '';
                $project_id     = '';
                $sql            = "SELECT lo.account_id as account_id, lo.full_name as landowner_name, lop.project_id as project_id FROM landowner lo JOIN landowner_projects lop ON lo.id = lop.landowner_id WHERE lop.id = '$id'";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $account_id     = $rs['account_id'];
                        $landowner_name = $rs['landowner_name'];
                        $project_id     = $rs['project_id'];
                    }
                }
                // get landowner payments
                $total_paid = 0;
                if ($account_id != '') {
                    $sql = "select
                            SUM(d.amount) AS total_paid
                        from
                            transactions t, transactions_details d
                        where
                            t.id = d.transaction_id
                            and t.account_id = '$account_id'
                            and t.project_id = '$project_id'
                            and t.transaction_type = 'PAYMENT'
                        order by
                            t.invoice_date asc";

                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);

                    if ($numrows > 0) {
                        while ($rs = mysql_fetch_array($result)) {
                            $total_paid = $rs['total_paid'];
                        }
                    }
                }
            } else {
                setMessage("Nothing to load...");
                system_log(MODIFY, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }
?>
<script type="text/javascript">
    var siteUrl = "<?=$_siteRoot?>";
    var row = 1;

    $(document).ready(function () {
        $(".due_on").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "2000:c+5"
        });

        $("#btnAddMore").button({
            text: false,
            icons: {
                primary: "ui-icon-circle-plus"
            }
        });

        $("#btnAddMore").click(function () {
            addNewRow();
            $("#due_on_" + row).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                yearRange: "2000:c+5"
            });
        });
    });

    function calculateTotal() {
        var x = 0;

        $("input[name='amount[]']").each(function (index) {
            x = x + parseInt($(this).val());
        });

        $("#totals").html(x);
    }

    function addNewRow() {
        row++;
        var rowData = "<tr>" +
            "<td>" +
            "        <input type='text' size='10' name='due_on[]' id='due_on" + "_" + row + "' maxlength='10' value='' />" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='amount[]' id='amount' maxlength='12' value='' onChange='javascript: calculateTotal();' />" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='notes[]' id='notes' maxlength='255' value='' />" +
            "</td>" +
            "<td>" +
            "        <select name='status[]' id='status'>" +
            "         <option value='DUE'>DUE</option>" +
            "         <option value='CLEARED'>CLEARED</option>" +
            "     </select>" +
            "</td>" +
            "</tr>";

        $("#rowLast").before(rowData);
    }
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"/> Land Owner</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <form action="index.php" method="post" autocomplete="off">
                <input type="hidden" name="ss" value="<?= $ss ?>"/>
                <input type="hidden" name="mod" value="<?= $mod ?>"/>
                <input type="hidden" name="cmd" value="<?= $cmd ?>"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <p class="box_title">Installment Plan</p>
                <? if (checkPermission($userInfo["id"], MODIFY)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <p>
                                    <strong>Project</strong><br/>
                                    <?php echo getProjectName($project_id); ?>
                                </p>
                                <p>
                                    <strong>Land Owner</strong><br/>
                                    <?php echo $landowner_name; ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                    <tr id="listhead">
                                        <td>Due On</td>
                                        <td>Amount</td>
                                        <td>Notes</td>
                                        <td>Status</td>
                                    </tr>
                                    <?
                                        if (is_array($amount)) {
                                            for ($x = 0;
                                                 $x < sizeof($amount);
                                                 $x++) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="text" size="10" name="due_on[]" class="due_on" maxlength="10" value="<?= $due_on[$x] ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="amount[]" id="amount" maxlength="12" value="<?= $amount[$x] ?>"
                                                               onChange='javascript: calculateTotal();'/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="notes[]" id="notes" maxlength="255" value="<?= $notes[$x] ?>"/>
                                                    </td>
                                                    <td>
                                                        <select name="status[]" id="status">
                                                            <?
                                                                $stypes = array('DUE', 'CLEARED');

                                                                for ($i = 0;
                                                                     $i < sizeof($stypes);
                                                                     $i++) {
                                                                    $selected = "";

                                                                    if ($status[$x] == $stypes[$i]) {
                                                                        $selected = "selected='selected'";
                                                                    }

                                                                    echo "<option value=\"" . $stypes[$i] . "\" $selected>" . $stypes[$i] . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="text" size="10" name="due_on[]" class="due_on" maxlength="10" value=""/>
                                                </td>
                                                <td>
                                                    <input type="text" size="10" name="amount[]" id="amount" maxlength="12" value="" onChange='javascript: calculateTotal();'/>
                                                </td>
                                                <td>
                                                    <input type="text" size="10" name="notes[]" id="notes" maxlength="255" value=""/>
                                                </td>
                                                <td>
                                                    <select name="status[]" id="status">
                                                        <?
                                                            $stypes = array('DUE', 'CLEARED');

                                                            for ($i = 0;
                                                                 $i < sizeof($stypes);
                                                                 $i++) {
                                                                $selected = "";

                                                                if ($status == $stypes[$i]) {
                                                                    $selected = "selected='selected'";
                                                                }

                                                                echo "<option value=\"" . $stypes[$i] . "\" $selected>" . $stypes[$i] . "</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?
                                        }
                                    ?>
                                    <tr id="rowLast">
                                        <td style="font-size: 18px;"><strong>Total</strong></td>
                                        <td style="font-size: 24px; font-weight: bold;" id="totals"><?= number_format($total, 2, ".", ",") ?></td>
                                        <td style="font-size: 18px;" align="right">&nbsp;</td>
                                        <td><a id="btnAddMore">+ Add more</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-size: 24px;">&nbsp;</td>
                                    </tr>
                                    <tr align="left" style="font-size: 18px;">
                                        <td><strong>Total Amount:</strong> <?php echo formatCurrency($total); ?></td>
                                        <td><strong>Total Paid:</strong> <?php echo formatCurrency($total_paid); ?></td>
                                        <td><strong>Balance:</strong> <?php echo formatCurrency(($total - $total_paid)); ?></td>
                                        <td style="font-size: 24px;">&nbsp;</td>
                                    </tr>
                                </table>
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