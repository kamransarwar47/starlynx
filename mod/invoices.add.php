<?
    define("MODULE_ID", "ACT002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if(checkPermission($userInfo["id"], CREATE)) {
        if($cmd == "ADD") {
            if(!empty($project_id) && !empty($transaction_type) && !empty($account_id) && !empty($invoice_date) && sizeof($detail_account_id) > 0) {
                $fieldsOk = true;

                for($i=0; $i<sizeof($detail_account_id); $i++) {
                    if(empty($detail_account_id[$i]) || empty($amount[$i])) {
                        $fieldsOk = false;
                    }

                    if($voucher_type[$i] == "BANK" && $transaction_type == "RECEIPT") {
                        if(empty($bank_id[$i]) && empty($cheque_number[$i]) || empty($cheque_date[$i]) || empty($cheque_name[$i])) {
                            $fieldsOk = false;
                        }
                    }
                }

                if($fieldsOk) {
                    $voucherType = ($transaction_type=="PAYMENT"?"P":"R");
                    $voucherNum = getNextVoucherNumber($project_id, $voucherType);
                    $extraFields = "";
                    $extraValues = "";
                    $status = "";

                    if($transaction_type == "PAYMENT") {
                        $extraFields = ", auth_status";
                        $extraValues = ", 'PENDING'";
                        $status = "PENDING";
                    } elseif ($transaction_type == "RECEIPT") {
                        $extraFields = ", handover_status";
                        $extraValues = ", 'PENDING'";
                    }

                    $sql = "insert into
                                transactions (
                                    project_id, account_id, voucher_id, transaction_type, notes, invoice_date, created, created_by $extraFields
                                ) values (
                                    \"".mysql_real_escape_string($project_id)."\",
                                    \"".mysql_real_escape_string($account_id)."\",
                                    \"".mysql_real_escape_string($voucherNum)."\",
                                    \"".mysql_real_escape_string($transaction_type)."\",
                                    \"".mysql_real_escape_string($notes)."\",
                                    \"".mysql_real_escape_string($invoice_date)."\",
                                    NOW(),
                                    \"".$userInfo["id"]."\"
                                    $extraValues
                                )";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $transaction_id = mysql_insert_id();

                    for($i=0; $i<sizeof($detail_account_id); $i++) {
                        $extraFields = "";
                        $extraValues = "";

                        if($transaction_type == "RECEIPT") {
                            if($voucher_type[$i] == "BANK") {
                                $extraFields = ", post_date, clearance_status";
                                $extraValues = ", '".isPostDate($cheque_date[$i])."', 'PENDING'";
                            }
                        }

                        $sql = "insert into
                                    transactions_details (
                                        transaction_id, account_id, amount, notes, voucher_type, bank_id, cheque_number, cheque_date, cheque_name $extraFields
                                    ) values (
                                        \"".mysql_real_escape_string($transaction_id)."\",
                                        \"".mysql_real_escape_string($detail_account_id[$i])."\",
                                        \"".mysql_real_escape_string($amount[$i])."\",
                                        \"".mysql_real_escape_string($detail_notes[$i])."\",
                                        \"".mysql_real_escape_string($voucher_type[$i])."\",
                                        \"".mysql_real_escape_string($bank_id[$i])."\",
                                        \"".mysql_real_escape_string($cheque_number[$i])."\",
                                        \"".mysql_real_escape_string($cheque_date[$i])."\",
                                        \"".mysql_real_escape_string($cheque_name[$i])."\"
                                        $extraValues
                                    )";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                    }

                    if($status == "PENDING") {
                        $msg = "$voucherNum has been created, but is PENDING authorization. <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucherNum'>Click here</a> to view.";
                        $log = "$voucherNum created. Pending Authorization.";
                    } else {
                        $msg = "$voucherNum has been created. <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucherNum'>Click here</a> to view and print.";
                        $log = "$voucherNum created.";
                    }

                    setMessage($msg, true);
                    system_log(CREATE, $log, $userInfo["id"]);
                    unset($cmd, $project_id, $account_id, $transaction_type, $invoice_date, $notes, $detail_account_id, $amount, $detail_notes, $voucher_type, $bank_id, $cheque_number, $cheque_date, $cheque_name);
                } else {
                    setMessage("Some fields are missing, please review your invoice carefully before submit.");
                    system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(CREATE, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
    }

    if(checkPermission($userInfo["id"], MODIFY)) {
        if($cmd == "UPDATE") {
            if(!empty($id) && !empty($project_id) && !empty($plot_number) && !empty($plot_type) && !empty($size) && !empty($size_type) && !empty($width) && !empty($length) && !empty($rate_per_marla) && !empty($status)) {
                $sql = "update
                            plots
                        set
                            project_id=\"".mysql_real_escape_string($project_id)."\",
                            plot_number=\"".mysql_real_escape_string($plot_number)."\",
                            plot_type=\"".mysql_real_escape_string($plot_type)."\",
                            size=\"".mysql_real_escape_string($size)."\",
                            size_type=\"".mysql_real_escape_string($size_type)."\",
                            width=\"".mysql_real_escape_string($width)."\",
                            length=\"".mysql_real_escape_string($length)."\",
                            rate_per_marla=\"".mysql_real_escape_string($rate_per_marla)."\",
                            status=\"".mysql_real_escape_string($status)."\",
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

    // Accounts List
    $actArray = createAccountsArray();
    //print_r($actArray);

    // Banks List
    $bsql = "select id, short_title from banks order by short_title";
    $banks = mysql_query($bsql, $conn) or die(mysql_error());
?>
<script type="text/javascript">
var siteUrl = "<?=$_siteRoot?>";
var row = 1;

$(document).ready( function (){
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

    $("#btnAddMore").click(function(){
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

    if(vtype == "PAYMENT") {
        vtype = "P";
    } else {
        vtype = "R";
    }

    $.ajax({
      url: siteUrl + "ajax.getNextVoucherNumber.php?project_id=" + pid + "&voucher_type=" + vtype,
      success: function(data) {
          $('#voucherNum').html(data);
      }
    });
}

function calculateTotal() {
    var x = 0;

    $("input[name='amount[]']").each(function(index){
        x = x + parseInt($(this).val());
    });

    $("#totals").html(x);
}

function addNewRow()
{
    row++;
    var rowData = "<tr>" +
                   "<td><?=createPageSelectHTML('detail_account_id[]', 'detail_account_id', $actArray, $master_id, '--Select--')?></td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='detail_notes[]' id='detail_notes' maxlength='255' value='<?=$detail_notes?>' />" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='amount[]' id='amount' maxlength='12' value='<?=$amount?>'  onChange='javascript: calculateTotal();' />" +
                   "</td>" +
                   "<td>" +
                   "        <select name='voucher_type[]' id='voucher_type'>" +
                   "         <option value='CASH'>CASH</option>" +
                   "         <option value='BANK'>BANK</option>" +
                   "     </select>" +
                   "</td>" +
                   "<td>" +
                   "        <select name='bank_id[]'>" +
                   "         <? while($brs = mysql_fetch_array($banks)) {echo "<option value='".$brs["id"]."'>".$brs["short_title"]."</option>";}?>" +
                   "     </select>" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='cheque_number[]' id='cheque_number' maxlength='15' value='<?=$cheque_number?>' />" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='cheque_date[]' id='cheque_date" + "_" + row + "' maxlength='10' value='<?=$cheque_date?>' />" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='cheque_name[]' id='cheque_name' maxlength='100' value='<?=$cheque_name?>' />" +
                   "</td>" +
                   "</tr>";

    $("#rowLast").before(rowData);
}
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Accounts</td>
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
                <p class="box_title">Create New Invoice</p>
                <? if(checkPermission($userInfo["id"], CREATE)) { ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td valign="top">
                            <p>
                                <strong>Project</strong><br />
                                <select name="project_id" id="project_id" onChange="javascript: getNextVoucherNumber();">
                                <option value="">--Select--</option>
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
                        </td>
                        <td valign="top">
                            <p>
                                <strong>&nbsp;</strong><br />
                                <select name="transaction_type" id="transaction_type" onChange="javascript: getNextVoucherNumber();">
                                    <?
                                        $types = array('Paid To' => 'PAYMENT', 'Received From' => 'RECEIPT');

                                        foreach($types as $key => $val) {
                                            $selected = "";

                                            if($transaction_type == $val) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"".$val."\" $selected>".$key."</option>";
                                        }
                                    ?>
                                </select>
                                <?
                                    echo createPageSelectHTML("account_id", "account_id", $actArray, $master_id, "--Select--");
                                ?>

                            </p>
                        </td>
                        <td valign="top">
                            <p>
                                <strong>Date</strong><br />
                                <input type="text" size="15" name="invoice_date" id="invoice_date" maxlength="10" value="<?=$invoice_date?>" />
                            </p>
                        </td>
                        <td valign="top">
                            <p>
                                <strong>Voucher Number</strong> <span class="notes">(Next Available)</span><br />
                                <span id="voucherNum" style="font-size: 24px; color: #ff0000; font-weight: bold; line-height: 35px;">xx-x-xxxx-xxxxx</span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                <tr id="listhead">
                                    <td>Particulars</td>
                                    <td>Notes</td>
                                    <td>Amount</td>
                                    <td>Transact via</td>
                                    <td>Bank</td>
                                    <td>Cheque #</td>
                                    <td>Cheque Date</td>
                                    <td>Name on Cheque</td>
                                </tr>
                                <tr>
                                    <td>
                                        <?
                                            echo createPageSelectHTML("detail_account_id[]", "detail_account_id", $actArray, $master_id, "--Select--");
                                        ?>
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="detail_notes[]" id="detail_notes" maxlength="255" value="<?=$detail_notes?>" />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="amount[]" id="amount" maxlength="12" value="<?=$amount?>" onChange="javascript: calculateTotal();" />
                                    </td>
                                    <td>
                                        <select name="voucher_type[]" id="voucher_type">
                                        <?
                                            $vtypes = array('CASH', 'BANK');

                                            for($i=0; $i<sizeof($vtypes); $i++) {
                                                $selected = "";

                                                if($voucher_type == $vtypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"".$vtypes[$i]."\" $selected>".$vtypes[$i]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="bank_id[]">
                                        <?
                                            $banks = mysql_query($bsql, $conn) or die(mysql_error());
                                            while($brs = mysql_fetch_array($banks)) {
                                                echo "<option value=\"".$brs["id"]."\">".$brs["short_title"]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="cheque_number[]" id="cheque_number" maxlength="15" value="<?=$cheque_number?>" />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="cheque_date[]" id="cheque_date" maxlength="10" value="<?=$cheque_date?>" />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="cheque_name[]" id="cheque_name" maxlength="100" value="<?=$cheque_name?>" />
                                    </td>
                                </tr>
                                <tr id="rowLast">
                                    <td><a id="btnAddMore">+ Add more</a></td>
                                    <td style="font-size: 18px;"><strong>Total</strong></td>
                                    <td style="font-size: 24px; font-weight: bold;" id="totals">0.00</td>
                                    <td style="font-size: 18px;" align="right"><strong>Notes</strong></td>
                                    <td colspan="4">
                                        <input type="text" size="62" name="notes" id="notes" maxlength="255" value="<?=$notes?>" />
                                    </td>
                                </tr>
                            </table>
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