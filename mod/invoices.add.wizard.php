<?
    define("MODULE_ID", "ACT002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], CREATE)) {
        if ($cmd == "ADD") {
            if (!empty($project_id) && !empty($transaction_type) && !empty($person_type) && !empty($account_id) && !empty($invoice_date) && sizeof($detail_account_id) > 0) {
                $fieldsOk                 = true;
                $customer_check           = false;
                $installment_check        = false;
                $installment_amount_check = false;
                $installment_data         = [];
                $plots_cleared_check      = [];
                $plots_amount_check       = [];
                $total_due_amount         = 0;

                for ($i = 0; $i < sizeof($detail_account_id); $i++) {
                    if (empty($detail_account_id[$i]) || empty($amount[$i])) {
                        $fieldsOk = false;
                    }

                    if ($voucher_type[$i] == "BANK" && $transaction_type == "RECEIPT") {
                        if (empty($bank_id[$i]) && empty($cheque_number[$i]) || empty($cheque_date[$i]) || empty($cheque_name[$i])) {
                            $fieldsOk = false;
                        }
                    }

                    // customer verification on plot payments by customer //
                    if ($person_type == 'CUSTOMERS' && $detail_account_id[$i] != "") {
                        $sql = "SELECT id, customer_id FROM plots WHERE account_id = '" . mysql_real_escape_string($detail_account_id[$i]) . "'";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        if (mysql_num_rows($result) > 0) {
                            $rs          = mysql_fetch_array($result);
                            $customer_id = getCustomerInfoByAccountId($account_id)['id'];
                            $plot_id     = $rs['id'];
                            // check if both ids match
                            if ($rs['customer_id'] != $customer_id) {
                                if ($cc_input == "no") {
                                    echo "<script>$(document).ready(function() { $('span.ccw_{$i}').html('<div class=\"helptooltip\"><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: 0px;\"></span><span class=\"helptooltiptext\">Current Owner: <strong>" . ((getCustomerName($rs['customer_id']) != "") ? getCustomerName($rs['customer_id']) : 'NIL') . "</strong></span></div>'); });</script>";
                                    $fieldsOk       = false;
                                    $customer_check = true;
                                } else {
                                    $sql = "UPDATE plots SET customer_id = '" . mysql_real_escape_string($customer_id) . "' WHERE id = '" . mysql_real_escape_string($plot_id) . "'";
                                    mysql_query($sql, $conn) or die(mysql_error());
                                    system_log(MODIFY, "Plot record updated.", $userInfo["id"]);
                                }
                            }
                        }
                        // installment check
                        if ($check_installment[$i] == "yes") {
                            $installment_data[$i]['id']     = $detail_account_id[$i];
                            $installment_data[$i]['amount'] = $amount[$i];
                        }
                    }
                }

                // check if dues exist in case of installment
                if (!empty($installment_data)) {

                    $installment_array = array_values($installment_data);

                    // group account ids and sum amount
                    $installment_data = array_reduce($installment_array, function ($array, $item) {
                        if (!isset($array[$item['id']])) {
                            $array[$item['id']] = ['id' => $item['id'], 'amount' => $item['amount']];
                        } else {
                            $array[$item['id']]['amount'] += $item['amount'];
                        }
                        return $array;
                    });

                    $installment_data = array_values($installment_data);

                    // check installment details
                    foreach ($installment_data as $data) {
                        $check_data = getPlotInstallmentDetails($data['id']);
                        if (empty($check_data)) {
                            $fieldsOk              = false;
                            $installment_check     = true;
                            $plots_cleared_check[] = getAccountTitle($data['id']);
                        } else {
                            if ($data['amount'] > array_sum(array_column($check_data, 'amount'))) {
                                $total_due_amount         += array_sum(array_column($check_data, 'amount'));
                                $fieldsOk                 = false;
                                $installment_amount_check = true;
                                $plots_amount_check[]     = getAccountTitle($data['id']);
                            }
                        }
                    }
                }

                if ($installment_amount_check) {
                    setMessage("Amount is greater than Total Due Amount (" . formatCurrency($total_due_amount) . ") against " . implode(' / ', $plots_amount_check) . ". Cannot create Installment Invoice against this/these plot/s.");
                }

                if ($installment_check) {
                    setMessage("All dues are cleared against " . implode(' / ', $plots_cleared_check) . ". Cannot create Installment Invoice against this/these plot/s.");
                }

                if ($customer_check) {
                    setMessage("To change ownership click OK and submit again OR click CANCEL and change customer name to current owner name. <span style='margin-left: 50px;'><input type='button' id='ccbtn_ok' class='ui-button ui-widget ui-state-default ui-corner-all' value='OK'><input type='button' id='ccbtn_cancel' class='ui-button ui-widget ui-state-highlight ui-corner-all' style='width: 80px; margin-left: 5px;' value='CANCEL'></span>");
                }
                // customer verification on plot payments and installments //

                if ($fieldsOk) {
                    $voucherType = ($transaction_type == "PAYMENT" ? "P" : "R");
                    $voucherNum  = getNextVoucherNumber($project_id, $voucherType);
                    $extraFields = "";
                    $extraValues = "";
                    $status      = "";
                    //$smsAmount = "";

                    if ($transaction_type == "PAYMENT") {
                        $extraFields = ", auth_status";
                        $extraValues = ", 'PENDING'";
                        $status      = "PENDING";
                    } elseif ($transaction_type == "RECEIPT") {
                        $extraFields = ", handover_status";
                        $extraValues = ", 'PENDING'";
                    }

                    $sql = "insert into
                                transactions (
                                    project_id, account_id, voucher_id, transaction_type, notes, invoice_date, created, created_by $extraFields
                                ) values (
                                    \"" . mysql_real_escape_string($project_id) . "\",
                                    \"" . mysql_real_escape_string($account_id) . "\",
                                    \"" . mysql_real_escape_string($voucherNum) . "\",
                                    \"" . mysql_real_escape_string($transaction_type) . "\",
                                    \"" . mysql_real_escape_string($notes) . "\",
                                    \"" . mysql_real_escape_string($invoice_date) . "\",
                                    NOW(),
                                    \"" . $userInfo["id"] . "\"
                                    $extraValues
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $transaction_id = mysql_insert_id();

                    for ($i = 0; $i < sizeof($detail_account_id); $i++) {
                        $extraFields = "";
                        $extraValues = "";

                        if ($transaction_type == "RECEIPT") {
                            if ($voucher_type[$i] == "BANK") {
                                $extraFields = ", post_date, clearance_status";
                                $extraValues = ", '" . isPostDate($cheque_date[$i]) . "', 'PENDING'";
                            }
                        }

                        $d_notes = ($detail_notes[$i][0] == 'OTHER') ? $detail_notes[$i][1] : $detail_notes[$i][0];

                        $sql = "insert into
                                    transactions_details (
                                        transaction_id, account_id, amount, notes, voucher_type, bank_id, cheque_number, cheque_date, cheque_name, cheque_total_amount, check_installment $extraFields
                                    ) values (
                                        \"" . mysql_real_escape_string($transaction_id) . "\",
                                        \"" . mysql_real_escape_string($detail_account_id[$i]) . "\",
                                        \"" . mysql_real_escape_string($amount[$i]) . "\",
                                        \"" . mysql_real_escape_string($d_notes) . "\",
                                        \"" . mysql_real_escape_string($voucher_type[$i]) . "\",
                                        \"" . mysql_real_escape_string($bank_id[$i]) . "\",
                                        \"" . mysql_real_escape_string($cheque_number[$i]) . "\",
                                        \"" . mysql_real_escape_string($cheque_date[$i]) . "\",
                                        \"" . mysql_real_escape_string($cheque_name[$i]) . "\",
                                        \"" . mysql_real_escape_string($cheque_total_amount[$i]) . "\",
                                        \"" . mysql_real_escape_string($check_installment[$i]) . "\"
                                        $extraValues
                                    )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());

                        //$smsAmount += $amount[$i];

                        // Auto clearance of installments
                        if ($check_installment[$i] == "yes") {
                            // check installment details
                            $check_data = getPlotInstallmentDetails($detail_account_id[$i]);

                            if (!empty($check_data)) {
                                $installment_diff = $amount[$i];
                                foreach ($check_data as $d) {
                                    $installment_diff -= $d['amount'];
                                    $paid_amount      = $d['amount'] + $installment_diff;
                                    $remaining_amount = $d['amount'] - $paid_amount;
                                    $query_extra      = ", updated = NOW(), updated_by = '{$userInfo['id']}', notes = '" . $d['notes'] . " >>> (SYSTEM CLEARED)" . "'";
                                    if ($installment_diff < 0) {
                                        // when less amount is paid update current and clear. create a new installment with remaining amount
                                        $sql = "UPDATE plots_dues SET status = 'CLEARED', amount = '{$paid_amount}' $query_extra WHERE id = '{$d['id']}'";
                                        mysql_query($sql, $conn) or die(mysql_error());

                                        $updated_notes = str_replace([" >>> (SYSTEM GENERATED)", " (SYSTEM GENERATED)"], "", $d['notes']);

                                        $sql = "insert into
                                        plots_dues (
                                            plot_id, dues_type, amount, due_on, notes, status, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string($d['plot_id']) . "\",
                                            \"" . mysql_real_escape_string($d['dues_type']) . "\",
                                            \"" . mysql_real_escape_string($remaining_amount) . "\",
                                            \"" . mysql_real_escape_string($d['due_on']) . "\",
                                            \"" . mysql_real_escape_string($updated_notes . " >>> (SYSTEM GENERATED)") . "\",
                                            \"" . mysql_real_escape_string("DUE") . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                                        mysql_query($sql, $conn) or die(mysql_error());
                                        break;

                                    } else if ($installment_diff > 0) {
                                        // when greater amount is paid clear current installment and loop for next
                                        $sql = "UPDATE plots_dues SET status = 'CLEARED' $query_extra WHERE id = '{$d['id']}'";
                                        mysql_query($sql, $conn) or die(mysql_error());

                                    } else {
                                        // when full installment amount is paid clear the installment
                                        $sql = "UPDATE plots_dues SET status = 'CLEARED' $query_extra WHERE id = '{$d['id']}'";
                                        mysql_query($sql, $conn) or die(mysql_error());
                                        break;

                                    }
                                }
                            }

                        }
                    }

                    if ($status == "PENDING") {
                        $msg = "$voucherNum has been created, but is PENDING authorization. <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucherNum'>Click here</a> to view.";
                        $log = "$voucherNum created. Pending Authorization.";
                    } else {
                        $msg = "$voucherNum has been created. <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucherNum'>Click here</a> to view and print.";
                        $log = "$voucherNum created.";
                    }

                    setMessage($msg, true);
                    system_log(CREATE, $log, $userInfo["id"]);

                    // Send SMS to Customer upon Receipt
                    //                    if($transaction_type == "RECEIPT") {
                    //                        $customer = getCustomerInfoByAccountId($account_id);
                    //
                    //                        if(isArray($customer) && sizeof($customer) > 0) {
                    //                            $sms = "Dear Mr./Ms abc, thank you for paying Rs. xxxxxxxxx for plot 1, shop 2 of Shadman City Phase 1";
                    //                        }
                    //                    }

                    unset($cmd, $project_id, $account_id, $transaction_type, $invoice_date, $notes, $detail_account_id, $amount, $detail_notes, $voucher_type, $bank_id, $cheque_number, $cheque_date, $cheque_name, $cheque_total_amount, $check_installment);
                } else {
                    if (!$customer_check && !$installment_check && !$installment_amount_check) {
                        setMessage("Some fields are missing, please review your invoice carefully before submit.");
                        system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
                    }
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if (empty($cmd)) {
        $cmd = "ADD";
    }

    // Accounts List
    $prActId  = "";
    $actArray = array();

    if (!empty($project_id)) {
        $prActId  = getProjectAccountId($project_id);
        $actArray = createAccountsArray($prActId);
    }

    // Banks List
    $bsql = "select id, short_title from banks order by short_title";
    $banks = mysql_query($bsql, $conn) or die(mysql_error());
?>
<style>
    #detail_account_id {
        max-width: 230px;
    }
</style>
<script type="text/javascript">
    var siteUrl = "<?=$_siteRoot?>";
    var row = <?php echo (is_array($detail_account_id)) ? (sizeof($detail_account_id) - 1) : 0; ?>;

    $(document).ready(function () {
        $("#invoice_date, #cheque_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "c:c+5"
        });

        $("#btnAddMore").button({
            text: false,
            icons: {
                primary: "ui-icon-circle-plus"
            }
        });

        $("#btnAddMore").click(function () {
            addNewRow();
            $("#cheque_date_" + row).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                yearRange: "c:c+5"
            });
        });
    });

    function getNextVoucherNumber() {
        pid = $("#project_id").val();
        vtype = $("#transaction_type").val();

        if (vtype == "PAYMENT") {
            vtype = "P";
        } else {
            vtype = "R";
        }

        $.ajax({
            url: siteUrl + "ajax.getNextVoucherNumber.php?project_id=" + pid + "&voucher_type=" + vtype,
            success: function (data) {
                $('#voucherNum').html(data);
                $(".voucher_type").change();
            }
        });
    }

    /* KAMRAN UPDATE START */
    function getSelectedPersonType(account_id) {
        ptype = $("#person_type").val();
        prjid = $("#project_id").val();

        $.ajax({
            url: siteUrl + "ajax.getSelectedPersonType.php?person_type=" + ptype + "&account_id=" + account_id + "&project_id=" + prjid,
            success: function (data) {
                $("#account_id").html(data).select2({dropdownAutoWidth: true});
            }
        });
    }

    $(document).on('change', '.detail_notes_select', function () {
        var display = 'none';
        if ($(this).val() == 'OTHER') {
            display = 'block';
        }
        $(this).parent('td').find('input#detail_notes_input').css('display', display);
    });

    $(document).on('change', '.voucher_type', function () {
        var serial_no = "";
        vtype = $("#transaction_type").val();

        if (vtype == "RECEIPT") {
            if ($(this).val() == 'CASH') {
                serial_no = $("#voucherNum").html().trim();
            }
        }

        $(this).closest('tr').find('input#cheque_number').val(serial_no).attr('readonly', ((serial_no != "") ? true : false));
    });

    /*
     * setting field to readonly on page refresh
     * */
    $(document).ready(function () {
        if ($("#transaction_type").val() == "RECEIPT") {
            $(".voucher_type").each(function () {
                if ($(this).val() == "CASH") {
                    $(this).closest('tr').find('input#cheque_number').attr('readonly', true);
                }
            });
        }
    });
</script>

<?php
    if (is_array($detail_account_id)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                getSelectedPersonType(<?php echo $account_id; ?>);
            });
        </script>
        <?php
    }
?>

<script type="text/javascript">
    // click ok button on customer change warning
    $(document).on('click', '#ccbtn_ok', function () {
        $('#cc_input').val('yes');
        $(this).parents('div.ui-widget').remove();
    });
    // click cancel button on customer change warning
    $(document).on('click', '#ccbtn_cancel', function () {
        $('#cc_input').val('no');
        $(this).parents('div.ui-widget').remove();
    });
    /* KAMRAN UPDATE END     */

    function calculateTotal() {
        var x = 0;

        $("input[name='amount[]']").each(function (index) {
            x = x + parseInt((isNaN(parseInt($(this).val()))) ? 0 : $(this).val());
        });

        $("#totals").html(x);
    }

    function addNewRow() {
        row++;
        var rowData = "<tr>" +
            "<td><span class='ccw_" + row + "'></span><?=createPageSelectHTML('detail_account_id[]', 'detail_account_id', $actArray, $master_id, '--Select--')?></td>" +
            "<td>" +
            "        <div class='helptooltip'><input type='hidden' value='no' name='check_installment[" + row + "]'><input type='checkbox' class='ci_checkbox' value='yes' name='check_installment[" + row + "]'>?<span class='helptooltiptext'>In case of installment check this checkbox. Otherwise leave empty.</span></div>" +
            "</td>" +
            "<td>" +
            "<select name='detail_notes[" + row + "][]' style='width: 94%; margin-bottom: 3px;' class='detail_notes_select'>" +
            '<option value="">--SELECT--</option>' +
            '<option value="ADVANCE">ADVANCE</option>' +
            '<option value="INSTALMENT">INSTALMENT</option>' +
            '<option value="COMMISSION">COMMISSION</option>' +
            '<option value="PAYMENT RETURN">PAYMENT RETURN</option>' +
            '<option value="OTHER">OTHER</option>' +
            "</select>" +
            "<input type='text' style='display:none; width: 90%;' name='detail_notes[" + row + "][]' id='detail_notes_input' maxlength='255' value=''/>" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='amount[]' id='amount' maxlength='12' value=''  onChange='javascript: calculateTotal();' />" +
            "</td>" +
            "<td>" +
            "        <select name='voucher_type[]' id='voucher_type' class='voucher_type'>" +
            "         <option value='CASH'>CASH</option>" +
            "         <option value='BANK'>BANK</option>" +
            "     </select>" +
            "</td>" +
            "<td>" +
            "        <select name='bank_id[]'>" +
            "         <? while ($brs = mysql_fetch_array($banks)) {
                echo "<option value='" . $brs["id"] . "'>" . $brs["short_title"] . "</option>";
            }?>" +
            "     </select>" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='12' name='cheque_number[]' id='cheque_number' maxlength='15' value='' />" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='cheque_date[]' id='cheque_date" + "_" + row + "' maxlength='10' value='' />" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='cheque_name[]' id='cheque_name' maxlength='100' value='' />" +
            "</td>" +
            "<td>" +
            "        <input type='text' size='10' name='cheque_total_amount[]' id='cheque_total_amount' maxlength='20' value='' />" +
            "</td>" +
            "</tr>";

        $("#rowLast").before(rowData);
        $("#rowLast").prev("tr").find(".voucher_type").change();
    }
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Accounts
            <a style="float: right;" href="index.php?ss=<?PHP print $ss; ?>&amp;mod=customers.add"
               class="ui-button ui-widget ui-state-default ui-corner-all btnLink" role="button" target="_blank">Add New
                Customer</a>
        </td>
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
                <input type="hidden" id="cc_input" name="cc_input" value="no">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <p class="box_title">Create New Invoice</p>
                        </td>
                        <td align="right">
                            <p>
                                <strong>Voucher Number</strong> <span class="notes">(Next Available)</span><br/>
                                <span id="voucherNum"
                                      style="font-size: 24px; color: #ff0000; font-weight: bold; line-height: 35px;">
                                    <?
                                        if (!empty($project_id) && !empty($transaction_type)) {
                                            $voucherType = ($transaction_type == "PAYMENT" ? "P" : "R");
                                            $voucherNum  = getNextVoucherNumber($project_id, $voucherType);

                                            echo $voucherNum;
                                        } else {
                                            echo "xx-x-xxxx-xxxxx";
                                        }
                                    ?>
                                </span>
                            </p>
                        </td>
                    </tr>
                </table>
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Project</strong><br/>
                                    <select name="project_id" id="project_id"
                                            onChange="javascript: location.href='index.php?ss=<?= $ss ?>&mod=<?= $mod ?>&project_id='+this.value;">
                                        <option value="">--Select--</option>
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
                                    <select name="transaction_type" id="transaction_type"
                                            onChange="javascript: getNextVoucherNumber();">
                                        <?
                                            $types = array('--Payment Type--' => '', 'Paid To' => 'PAYMENT', 'Received From' => 'RECEIPT');

                                            foreach ($types as $key => $val) {
                                                $selected = "";

                                                if ($transaction_type == $val) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $val . "\" $selected>" . $key . "</option>";
                                            }
                                        ?>
                                    </select>
                                    <!-- KAMRAN UPDATES START-->
                                    <select name="person_type" id="person_type"
                                            onChange="javascript: getSelectedPersonType();">
                                        <?
                                            $types = array(
                                                '--Person Type--' => '',
                                                'Customers' => 'CUSTOMERS',
                                                'Employees' => 'EMPLOYEES',
                                                'Dealers' => 'DEALERS',
                                                'Vendors' => 'VENDORS',
                                                'Land Owners' => 'LANDOWNERS',
                                                'Investors' => 'INVESTORS',
                                                'Partners' => 'PARTNERS',
                                            );

                                            foreach ($types as $key => $val) {
                                                $selected = "";
                                                if ($person_type == $val) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"" . $val . "\" $selected>" . $key . "</option>";
                                            }
                                        ?>
                                    </select>
                                    <select name="account_id" id="account_id" class="select2" style="max-width: 450px;">
                                        <option value="">--Select Name--</option>
                                    </select>
                                    <!-- KAMRAN UPDATES END -->
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Date</strong><br/>
                                    <input type="text" size="25" name="invoice_date" id="invoice_date" maxlength="10"
                                           value="<?= $invoice_date ?>"/>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <? if (!empty($project_id)) { ?>
                                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
                                           id="reclist">
                                        <tr id="listhead">
                                            <td>Particulars</td>
                                            <td>&nbsp;</td>
                                            <td>Notes</td>
                                            <td>Amount</td>
                                            <td>Transact via</td>
                                            <td>Bank</td>
                                            <td>Cheque # / Cash Serial</td>
                                            <td>Cheque Date</td>
                                            <td>Name on Cheque</td>
                                            <td>Total Cheque amount</td>
                                        </tr>
                                        <?
                                            if (is_array($detail_account_id)) {
                                                //printArray($detail_account_id);
                                                for ($x = 0; $x < sizeof($detail_account_id); $x++) {
                                                    $total += $amount[$x];
                                                    //echo sizeof($detail_account_id);
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <span class="ccw_<?php echo $x; ?>"></span>
                                                            <?
                                                                echo createPageSelectHTML("detail_account_id[]", "detail_account_id", $actArray, $detail_account_id[$x], "--Select--");
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="helptooltip"><input type="hidden" value="no"
                                                                                            name="check_installment[<?php echo $x; ?>]"><input
                                                                        type="checkbox" class="ci_checkbox" value="yes"
                                                                        name="check_installment[<?php echo $x; ?>]" <?php echo ($check_installment[$x] == 'yes') ? 'checked="checked"' : ''; ?>>?
                                                                <span class="helptooltiptext">In case of installment check this checkbox. Otherwise leave empty.</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="detail_notes[<?php echo $x; ?>][]"
                                                                    style="width: 94%; margin-bottom: 3px;"
                                                                    class="detail_notes_select">
                                                                <?
                                                                    $d_notes = array(
                                                                        '--SELECT--' => '',
                                                                        'ADVANCE' => 'ADVANCE',
                                                                        'INSTALMENT' => 'INSTALMENT',
                                                                        'COMMISSION' => 'COMMISSION',
                                                                        'PAYMENT RETURN' => 'PAYMENT RETURN',
                                                                        'OTHER' => 'OTHER',
                                                                    );

                                                                    foreach ($d_notes as $key => $val) {
                                                                        $selected = "";
                                                                        if ($detail_notes[$x][0] == $val) {
                                                                            $selected = "selected='selected'";
                                                                        }

                                                                        echo "<option value=\"" . $val . "\" $selected>" . $key . "</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                            <input type="text"
                                                                   style="display: <?php echo ($detail_notes[$x][0] == 'OTHER') ? 'block' : 'none'; ?>; width: 90%;"
                                                                   name="detail_notes[<?php echo $x; ?>][]"
                                                                   id="detail_notes_input"
                                                                   maxlength="255" value="<?= $detail_notes[$x][1] ?>"/>
                                                        </td>
                                                        <td>
                                                            <input type="text" size="10" name="amount[]" id="amount"
                                                                   maxlength="12" value="<?= $amount[$x] ?>"
                                                                   onChange="javascript: calculateTotal();"/>
                                                        </td>
                                                        <td>
                                                            <select name="voucher_type[]" id="voucher_type"
                                                                    class="voucher_type">
                                                                <?
                                                                    $vtypes = array('CASH', 'BANK');

                                                                    for ($i = 0; $i < sizeof($vtypes); $i++) {
                                                                        $selected = "";

                                                                        if ($voucher_type[$x] == $vtypes[$i]) {
                                                                            $selected = "selected='selected'";
                                                                        }

                                                                        echo "<option value=\"" . $vtypes[$i] . "\" $selected>" . $vtypes[$i] . "</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="bank_id[]">
                                                                <?
                                                                    $banks = mysql_query($bsql, $conn) or die(mysql_error());
                                                                    while ($brs = mysql_fetch_array($banks)) {
                                                                        $selected = "";

                                                                        if ($bank_id[$x] == $brs["id"]) {
                                                                            $selected = "selected='selected'";
                                                                        }

                                                                        echo "<option value=\"" . $brs["id"] . "\" $selected>" . $brs["short_title"] . "</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" size="12" name="cheque_number[]"
                                                                   id="cheque_number" maxlength="15"
                                                                   value="<?= $cheque_number[$x] ?>"/>
                                                        </td>
                                                        <td>
                                                            <input type="text" size="10" name="cheque_date[]"
                                                                   id="cheque_date" maxlength="10"
                                                                   value="<?= $cheque_date[$x] ?>"/>
                                                        </td>
                                                        <td>
                                                            <input type="text" size="10" name="cheque_name[]"
                                                                   id="cheque_name" maxlength="100"
                                                                   value="<?= $cheque_name[$x] ?>"/>
                                                        </td>
                                                        <td>
                                                            <input type="text" size="10" name="cheque_total_amount[]"
                                                                   id="cheque_total_amount" maxlength="20"
                                                                   value="<?= $cheque_total_amount[$x] ?>"/>
                                                        </td>
                                                    </tr>
                                                    <?
                                                }
                                            } else {
                                                $total = "0.00";
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="ccw_0"></span>
                                                        <?
                                                            echo createPageSelectHTML("detail_account_id[]", "detail_account_id", $actArray, $master_id, "--Select--");
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="helptooltip"><input type="hidden" value="no"
                                                                                        name="check_installment[0]"><input
                                                                    type="checkbox" class="ci_checkbox" value="yes"
                                                                    name="check_installment[0]" <?php echo ($check_installment == 'yes') ? 'checked="checked"' : ''; ?>>?
                                                            <span class="helptooltiptext">In case of installment check this checkbox. Otherwise leave empty.</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select name="detail_notes[0][]"
                                                                style="width: 94%; margin-bottom: 3px;"
                                                                class="detail_notes_select">
                                                            <?
                                                                $d_notes = array(
                                                                    '--SELECT--' => '',
                                                                    'ADVANCE' => 'ADVANCE',
                                                                    'INSTALMENT' => 'INSTALMENT',
                                                                    'COMMISSION' => 'COMMISSION',
                                                                    'PAYMENT RETURN' => 'PAYMENT RETURN',
                                                                    'OTHER' => 'OTHER',
                                                                );

                                                                foreach ($d_notes as $key => $val) {
                                                                    $selected = "";
                                                                    if ($detail_notes[0][0] == $val) {
                                                                        $selected = "selected='selected'";
                                                                    }

                                                                    echo "<option value=\"" . $val . "\" $selected>" . $key . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <input type="text"
                                                               style="display: <?php echo ($detail_notes[0][0] == 'OTHER') ? 'block' : 'none'; ?>; width: 90%;"
                                                               name="detail_notes[0][]" id="detail_notes_input"
                                                               maxlength="255" value="<?= $detail_notes[0][1] ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="amount[]" id="amount"
                                                               maxlength="12" value="<?= $amount ?>"
                                                               onChange="javascript: calculateTotal();"/>
                                                    </td>
                                                    <td>
                                                        <select name="voucher_type[]" id="voucher_type"
                                                                class="voucher_type">
                                                            <?
                                                                $vtypes = array('CASH', 'BANK');

                                                                for ($i = 0; $i < sizeof($vtypes); $i++) {
                                                                    $selected = "";

                                                                    if ($voucher_type == $vtypes[$i]) {
                                                                        $selected = "selected='selected'";
                                                                    }

                                                                    echo "<option value=\"" . $vtypes[$i] . "\" $selected>" . $vtypes[$i] . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="bank_id[]">
                                                            <?
                                                                $banks = mysql_query($bsql, $conn) or die(mysql_error());
                                                                while ($brs = mysql_fetch_array($banks)) {
                                                                    echo "<option value=\"" . $brs["id"] . "\">" . $brs["short_title"] . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="12" name="cheque_number[]"
                                                               id="cheque_number" maxlength="15"
                                                               value="<?= $cheque_number ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="cheque_date[]"
                                                               id="cheque_date"
                                                               maxlength="10" value="<?= $cheque_date ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="cheque_name[]"
                                                               id="cheque_name"
                                                               maxlength="100" value="<?= $cheque_name ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" size="10" name="cheque_total_amount[]"
                                                               id="cheque_total_amount"
                                                               maxlength="20" value="<?= $cheque_total_amount ?>"/>
                                                    </td>
                                                </tr>
                                                <?
                                            }
                                        ?>

                                        <tr id="rowLast">
                                            <td><a id="btnAddMore">+ Add more</a></td>
                                            <td>&nbsp;</td>
                                            <td style="font-size: 18px;"><strong>Total</strong></td>
                                            <td style="font-size: 24px; font-weight: bold;"
                                                id="totals"><?= formatCurrency($total) ?></td>
                                            <td style="font-size: 18px;" align="right"><strong>Notes</strong></td>
                                            <td colspan="7">
                                                <input type="text" size="62" name="notes" id="notes" maxlength="255"
                                                       value="<?= $notes ?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                <? } ?>
                            </td>
                        </tr>
                    </table>
                    <? if (!empty($project_id)) { ?>
                        <p>
                            <input type="submit" value="Submit"/>
                        </p>
                    <? } ?>
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