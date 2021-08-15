<?
    define("MODULE_ID", "ACT002");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "AUTH") {
            if (!empty($voucher_number)) {
                $aOk = true;
                for ($i = 0; $i < sizeof($d_id); $i++) {
                    if ($d_type[$i] == "BANK") {
                        if (empty($d_id[$i]) || empty($bank_id[$i]) || empty($cheque_number[$i]) || empty($cheque_date[$i]) || empty($cheque_name[$i])) {
                            $aOk = false;
                        }
                    }
                }
                if ($aOk) {
                    $id = getTransactionId($voucher_number);
                    for ($i = 0; $i < sizeof($d_id); $i++) {
                        $sql = "update
                                    transactions_details
                                set
                                    bank_id = \"" . mysql_real_escape_string($bank_id[$i]) . "\",
                                    cheque_number = \"" . mysql_real_escape_string($cheque_number[$i]) . "\",
                                    cheque_date = \"" . mysql_real_escape_string($cheque_date[$i]) . "\",
                                    cheque_name = \"" . mysql_real_escape_string($cheque_name[$i]) . "\",
                                    cheque_total_amount = \"" . mysql_real_escape_string($cheque_total_amount[$i]) . "\"
                                where
                                    transaction_id = '$id'
                                    and id = \"" . mysql_real_escape_string($d_id[$i]) . "\"
                                ";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                    }
                    $sql = "update
                                transactions
                            set
                                auth_status = 'AUTH',
                                auth_by = \"" . $userInfo["id"] . "\",
                                auth_on = NOW(),
                                updated = NOW(),
                                updated_by = \"" . $userInfo["id"] . "\"
                            where
                                id = '$id'
                            ";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    setMessage("$voucher_number has been authorized.", true);
                    system_log(MODIFY, "$voucher_number authorized.", $userInfo["id"]);
                    //unset($id);
                } else {
                    setMessage("One or more required fields are empty.11");
                    system_log(MODIFY, "Operation failed. [Reason: AUTH FAILED, one or more fields were empty.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: AUTH FAILED, one or more fields were empty.]", $userInfo["id"]);
            }
        }
        if ($cmd == "BANKFORMSUBMIT") {
            if (!empty($tid) && !empty($cnum) && !empty($deposit_slip_num) && !empty($deposit_date)) {
                $sql = "update
                            transactions_details
                        set
                            deposit_slip_num = \"" . mysql_real_escape_string($deposit_slip_num) . "\",
                            deposit_date = \"" . mysql_real_escape_string($deposit_date) . "\",
                            deposit_in = \"" . mysql_real_escape_string(explode("|", $deposit_in)[0]) . "\",
                            deposit_acc_title = \"" . mysql_real_escape_string($deposit_acc_title) . "\",
                            deposit_remarks = \"" . mysql_real_escape_string($deposit_remarks) . "\"
                        where
                            cheque_number = \"" . mysql_real_escape_string($cnum) . "\"
                            and transaction_id = \"" . mysql_real_escape_string($tid) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                setMessage("Bank Deposit Fields updated.", true);
                system_log(MODIFY, "Bank Deposit Fields updated.", $userInfo["id"]);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: BAND DEPOSIT FORM FAILED, one or more fields were empty.]", $userInfo["id"]);
            }
        }
        if ($cmd == "CLEARANCE") {
            if (!empty($tid) && !empty($cnum) && !empty($st)) {
                $sql = "update
                            transactions_details
                        set
                            clearance_status = \"" . mysql_real_escape_string($st) . "\",
                            cleared_on = NOW(),
                            cleared_by = \"" . $userInfo["id"] . "\"
                        where
                            cheque_number = \"" . mysql_real_escape_string($cnum) . "\"
                            and transaction_id = \"" . mysql_real_escape_string($tid) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                setMessage("Clearance status updated.", true);
                system_log(MODIFY, "Clearance status updated.", $userInfo["id"]);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: CLEARANCE FAILED, one or more fields were empty.]", $userInfo["id"]);
            }
        }
        if ($cmd == "HANDOVER") {
            if (!empty($voucher_number)) {
                $id  = getTransactionId($voucher_number);
                $sql = "update
                            transactions
                        set
                            handover_status = 'HANDOVER',
                            received_by = \"" . $userInfo["id"] . "\",
                            received_on = NOW(),
                            updated = NOW(),
                            updated_by = \"" . $userInfo["id"] . "\"
                        where
                            id = '$id'
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                setMessage("$voucher_number has been handed over.", true);
                system_log(MODIFY, "$voucher_number handed over.", $userInfo["id"]);
                //unset($id);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: HANDOVER FAILED, one or more fields were empty.]", $userInfo["id"]);
            }
        }
    }
    if (!empty($voucher_number)) {
        $sql = "select * from transactions where voucher_id=\"" . mysql_real_escape_string($voucher_number) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            while ($rs = mysql_fetch_array($result)) {
                $id               = $rs["id"];
                $project_id       = $rs["project_id"];
                $account_id       = $rs["account_id"];
                $voucher_id       = $rs["voucher_id"];
                $transaction_type = $rs["transaction_type"];
                $notes            = $rs["notes"];
                $invoice_date     = $rs["invoice_date"];
                $auth_status      = $rs["auth_status"];
                $auth_by          = $rs["auth_by"];
                $auth_on          = $rs["auth_on"];
                $handover_status  = $rs["handover_status"];
                $received_by      = $rs["received_by"];
                $received_on      = $rs["received_on"];
                $print_count      = $rs["print_count"];
                $printed_by       = $rs["printed_by"];
                $printed_on       = $rs["printed_on"];
                system_log(VIEW, "$voucher_number loaded.", $userInfo["id"]);
            }
        } else {
            setMessage("$voucher_number not found.");
            system_log(VIEW, "Operation failed. [Reason: $voucher_number not found.]", $userInfo["id"]);
        }
    } else {
        setMessage("Please provide a valid voucher number.");
        system_log(VIEW, "Operation failed. [Reason: No voucher was supplied.]", $userInfo["id"]);
    }
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $("#btnPrint").click(function () {
            window.open("print.php?ss=<?=$ss?>&printmod=invoice&voucher_number=<?=$voucher_number?>", 'prn');
        });

        $("#dlgAuth").dialog({
            autoOpen: false,
            height: 350,
            width: 980,
            modal: true,
            buttons: {
                "Authorize": function () {
                    $("#frmAuth").submit();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {
                $(this).dialog("close");
            }
        });

        $("#btnAuth").click(function () {
            $("#dlgAuth").dialog("open");
        });

        /* Clearance form */
        $("#dlgBankDeposit").dialog({
            autoOpen: false,
            height: 410,
            width: 380,
            modal: true,
            buttons: {
                "Submit": function () {
                    $("#frmBankDeposit").submit();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {
                $(this).dialog("close");
            }
        });

        $("#cheque_date, input[id|='cheque_date']").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "c:c+5"
        });

        $("#deposit_in").change(function () {
            if ($(this).val() != "") {
                $("#deposit_acc_title").val($(this).val().split("|")[1]);
            } else {
                $("#deposit_acc_title").val("");
            }
        });
    });

    function bankFormSubmit(tid, cnum) {
        if (tid != "") {
            $('#tid_brd').val(tid);
            $('#cnum_brd').val(cnum);

            $("#dlgBankDeposit").dialog("open");
        }
    }

    function updateClearance(st, tid, cnum) {
        if (st != "") {
            document.location = "index.php?ss=<?=$ss?>&mod=<?=$mod?>&voucher_number=<?=$voucher_number?>&cmd=CLEARANCE&st=" + st + "&tid=" + tid + "&cnum=" + cnum;
        }
    }
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Accounts
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">
                View Invoice

                <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                    <? if (($transaction_type == "PAYMENT" && $auth_status == "AUTH") || $transaction_type == "RECEIPT") { ?>
                        <span class="notes" style="float: right; padding-left: 10px; padding-top: 10px;">
                        <?
                            if ($transaction_type == "PAYMENT") {
                                echo "Authorized by " . getUserFullName($auth_by) . "<br />";
                                echo "On " . date("d-m-Y h:ia", strtotime($auth_on));
                            } elseif ($transaction_type == "RECEIPT" && $handover_status == "HANDOVER") {
                                echo "Handed over to " . getUserFullName($received_by) . "<br />";
                                echo "On " . date("d-m-Y h:ia", strtotime($received_on));
                            }
                        ?>
                        </span>
                        <input type="button" value="Print" id="btnPrint" style="float: right;"/>
                    <? } ?>
                <? } ?>

                <? if (checkPermission($userInfo["id"], MODIFY)) { ?>
                <? if ($transaction_type == "PAYMENT" && $auth_status == "PENDING") { ?>
                <input type="button" value="Authorize" id="btnAuth" style="float: right;"/>
                <div id="dlgAuth" title="Authorize Payment">
            <p style="font-size: 12px;">All fields are required.</p>

            <form action="index.php" method="POST" id="frmAuth" autocomplete="off">
                <input type="hidden" name="ss" value="<?= $ss ?>"/>
                <input type="hidden" name="mod" value="<?= $mod ?>"/>
                <input type="hidden" name="cmd" value="AUTH"/>
                <input type="hidden" name="voucher_number" value="<?= $voucher_number ?>"/>
                <?
                    $dsql = "select * from transactions_details where transaction_id='$id'";
                    $dres = mysql_query($dsql, $conn) or die(mysql_error());
                    $cnt = 0;
                    while ($drs = mysql_fetch_array($dres)) {
                        $d_id                  = $drs["id"];
                        $d_account_id          = $drs["account_id"];
                        $d_amount              = $drs["amount"];
                        $d_notes               = $drs["notes"];
                        $d_voucher_type        = $drs["voucher_type"];
                        $d_bank_id             = $drs["bank_id"];
                        $d_cheque_number       = $drs["cheque_number"];
                        $d_cheque_date         = $drs["cheque_date"];
                        $d_cheque_name         = $drs["cheque_name"];
                        $d_cheque_total_amount = $drs["cheque_total_amount"];
                        $d_post_date           = $drs["post_date"];
                        $d_clearance_status    = $drs["clearance_status"];
                        if ($d_voucher_type == "BANK") {
                            ?>
                            <div id="authList"
                                 style="float: left; width: 950px; height: auto; border-bottom: 1px solid #cccccc; padding-bottom: 10px;">
                                <input type="hidden" name="d_id[]" value="<?= $d_id ?>"/>
                                <input type="hidden" name="d_type[]" value="<?= $d_voucher_type ?>"/>
                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Account</strong><br/>
                                    <?= getAccountTitle($d_account_id) ?>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Amount</strong><br/>
                                    <?= $d_amount ?>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Bank</strong><br/>
                                    <select name='bank_id[]'>
                                        <?
                                            $bsql = "select id, short_title from banks order by short_title";
                                            $banks = mysql_query($bsql, $conn) or die(mysql_error());
                                            while ($brs = mysql_fetch_array($banks)) {
                                                $selected = "";
                                                if ($brs["id"] == $d_bank_id) {
                                                    $selected = "selected='selected'";
                                                }
                                                echo "<option value='" . $brs["id"] . "' $selected>" . $brs["short_title"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Cheque Number</strong><br/>
                                    <input type="text" size="20" name="cheque_number[]" id="cheque_number"
                                           maxlength="15" value="<?= $d_cheque_number ?>"/>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Cheque Date</strong><br/>
                                    <input type="text" size="20" name="cheque_date[]" id="cheque_date-<?= $cnt ?>"
                                           maxlength="10" value="<?= $d_cheque_date ?>"/>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Name on Cheque</strong><br/>
                                    <input type="text" size="20" name="cheque_name[]" id="cheque_name" maxlength="100"
                                           value="<?= $d_cheque_name ?>"/>
                                </p>
                                <p style="float: left; font-size: 12px; display: block; width: 160px; height: 30px;">
                                    <strong>Total amount on Cheque</strong><br/>
                                    <input type="text" size="20" name="cheque_total_amount[]" id="cheque_total_amount"
                                           maxlength="20" value="<?= $d_cheque_total_amount ?>"
                                           placeholder="Leave empty if blank"/>
                                </p>
                            </div>
                            <? $cnt++; ?>
                        <? } else { ?>
                            <div id="authList"
                                 style="float: left; width: 850px; height: auto; border-bottom: 1px solid #cccccc; padding-bottom: 10px;">
                                <input type="hidden" name="d_type[]" value="<?= $d_voucher_type ?>"/>
                                <p style="float: left; font-size: 12px; display: block; width: 150px; height: 30px;">
                                    <strong>Account</strong><br/>
                                    <?= getAccountTitle($d_account_id) ?>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Amount</strong><br/>
                                    <?= $d_amount ?>
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 80px; height: 30px;">
                                    <strong>Bank</strong><br/>
                                    N/A
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Cheque Number</strong><br/>
                                    N/A
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Cheque Date</strong><br/>
                                    N/A
                                </p>

                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Name on Cheque</strong><br/>
                                    N/A
                                </p>
                                <p style="float: left; font-size: 12px; display: block; width: 120px; height: 30px;">
                                    <strong>Total amount on Cheque</strong><br/>
                                    N/A
                                </p>
                            </div>
                        <? } ?>
                    <? } ?>
            </form>
            </div>
        <? } elseif ($transaction_type == "RECEIPT" && $handover_status == "PENDING") { ?>
            <input type="button" value="Hand Over" id="btnHandover" style="float: right;"
                   onClick="javascript: confirmHandover('index.php?ss=<?= $ss ?>&mod=<?= $mod ?>&voucher_number=<?= $voucher_number ?>&cmd=HANDOVER');"/>
        <? } ?>
        <? } ?>
            </p>
            <?
                if (checkPermission($userInfo["id"], VIEW)) {
                    if ($numrows > 0) {
                        ?>
                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
                               style="margin-top: 20px;">
                            <tr>
                                <td style="font-size: 14px;">
                                    <strong>Project:</strong> <?= getProjectName($project_id) ?></td>
                                <td style="font-size: 14px;"><strong>Reference #:</strong> <?= $voucher_id ?></td>
                                <td style="font-size: 14px;">
                                    <strong>Date:</strong> <?= date("d-m-Y", strtotime($invoice_date)) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-size: 14px;">
                                    <strong><?= ($transaction_type == "PAYMENT" ? "Paid To" : "Received From") ?>
                                        :</strong> <?= getAccountTitle($account_id) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
                                           id="reclist">
                                        <tr id="listhead">
                                            <td>Particulars</td>
                                            <td>Notes</td>
                                            <td align='right'>Amount</td>
                                            <td align='center'>Transact via</td>
                                            <td align='center'>Bank</td>
                                            <td align='center'>Cheque # / Cash Serial</td>
                                            <td align='center'>Cheque Date</td>
                                            <td align='center'>Name on Cheque</td>
                                            <td align='center'>Total amount on Cheque</td>
                                        </tr>
                                        <?
                                            $dsql = "select * from transactions_details where transaction_id='$id'";
                                            $dres = mysql_query($dsql, $conn) or die(mysql_error());
                                            $total = 0;
                                            while ($drs = mysql_fetch_array($dres)) {
                                                $d_account_id          = $drs["account_id"];
                                                $d_amount              = $drs["amount"];
                                                $d_notes               = $drs["notes"];
                                                $d_voucher_type        = $drs["voucher_type"];
                                                $d_bank_id             = $drs["bank_id"];
                                                $d_cheque_number       = $drs["cheque_number"];
                                                $d_cheque_date         = $drs["cheque_date"];
                                                $d_cheque_name         = $drs["cheque_name"];
                                                $d_cheque_total_amount = $drs["cheque_total_amount"];
                                                $d_post_date           = $drs["post_date"];
                                                $d_clearance_status    = $drs["clearance_status"];
                                                $total                 += $d_amount;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                            $sub_account_array = getSubAccountsExcelSheet($project_id, $d_account_id);
                                                            echo $sub_account_array[0] . (($sub_account_array[1] != "") ? "&nbsp;> " . $sub_account_array[1] : "") . (($sub_account_array[2] != "") ? "&nbsp;> " . $sub_account_array[2] : "") . (($sub_account_array[3] != "") ? "&nbsp;> " . $sub_account_array[3] : "");
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $d_notes ?>
                                                    </td>
                                                    <td align='right'>
                                                        <?= number_format($d_amount, 2, ".", ",") ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?= $d_voucher_type ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?
                                                            if ($d_voucher_type == "BANK") {
                                                                echo getBankShortName($d_bank_id);
                                                            }
                                                        ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?= $d_cheque_number ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?= ($d_cheque_date == "0000-00-00" ? "" : $d_cheque_date) ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?= $d_cheque_name ?>
                                                    </td>
                                                    <td align='center'>
                                                        <?= (($d_cheque_total_amount != '0.00') ? $d_cheque_total_amount : "") ?>
                                                    </td>
                                                </tr>
                                                <?
                                            }
                                        ?>
                                        <tr id="rowLast">
                                            <td></td>
                                            <td style="font-size: 18px;"><strong>Total</strong></td>
                                            <td style="font-size: 24px; font-weight: bold;" id="totals"
                                                align='right'><?= number_format($total, 2, ".", ",") ?></td>
                                            <td style="font-size: 18px;" align="right"><strong>Notes</strong></td>
                                            <td colspan="5">
                                                <?= $notes ?>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Pending Clearance Start -->
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                            <tr>
                                <td>
                                    <?
                                        //if(!empty($dtFrom) && !empty($dtTo)) {
                                        $sql = "SELECT
                                    t.id, t.project_id, t.account_id, t.voucher_id, t.notes, t.invoice_date,
                                    d.id AS d_id, d.account_id as d_account, sum(d.amount) as amount, d.notes, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name, d.cheque_total_amount,
                                    d.post_date, d.clearance_status, d.cleared_on, d.cleared_by, d.deposit_slip_num, d.deposit_date, d.deposit_remarks, d.deposit_in, d.deposit_acc_title
                                FROM
                                    transactions t, transactions_details d
                                WHERE
                                    t.id = d.transaction_id
                                    AND t.transaction_type =  'RECEIPT'
                                    AND t.handover_status =  'HANDOVER'
                                    AND t.voucher_id='$voucher_number'
                                GROUP BY
                                    d.cheque_number
                                HAVING 
                                    d.cheque_number <> ''
                                ";
                                        //echo $sql;
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows = mysql_num_rows($result);
                                        if ($numrows > 0) {
                                            echo "<br /><strong style='font-size: 16px;'>Clearance Status</strong>";
                                            echo "<table border='0' width='100%' cellpadding='5' cellspacing='0' align='center' id='reclist'>";
                                            echo "<tr id='listhead'>";
                                            echo "<td width='5%'>Inv. Date</td>";
                                            echo "<td width='10%'>Cheque # / Cash Serial</td>";
                                            echo "<td width='5%'>Chq. Date</td>";
                                            echo "<td width='10%'>Name on Cheque</td>";
                                            echo "<td width='10%'>Total amount on Cheque</td>";
                                            echo "<td width='5%'>Bank</td>";
                                            echo "<td width='5%'>Amount</td>";
                                            echo "<td width='10%'>Deposit Slip No.</td>";
                                            echo "<td width='10%'>Deposit In</td>";
                                            echo "<td width='10%'>Deposit Acc. Title</td>";
                                            echo "<td width='5%'>Deposit Date</td>";
                                            echo "<td width='10%'>Remarks</td>";
                                            echo "<td width='5%' align='center'>Clearance</td>";
                                            echo "</tr>";
                                            if ($numrows > 0) {
                                                while ($rs = mysql_fetch_array($result)) {
                                                    $id = $rs["id"];
                                                    //$project_id = $rs["project_id"];
                                                    $account_id = $rs["account_id"];
                                                    $voucher_id = $rs["voucher_id"];
                                                    //$transaction_type = $rs["transaction_type"];
                                                    //$notes = $rs["notes"];
                                                    $invoice_date        = $rs["invoice_date"];
                                                    $d_id                = $rs["d_id"];
                                                    $amount              = $rs["amount"];
                                                    $bank_id             = $rs["bank_id"];
                                                    $cheque_number       = $rs["cheque_number"];
                                                    $cheque_name         = $rs["cheque_name"];
                                                    $cheque_total_amount = $rs["cheque_total_amount"];
                                                    $cheque_date         = $rs["cheque_date"];
                                                    $post_date           = $rs["post_date"];
                                                    $clearance_status    = $rs["clearance_status"];
                                                    $cleared_on          = $rs["cleared_on"];
                                                    $cleared_by          = $rs["cleared_by"];
                                                    $deposit_slip_num    = $rs["deposit_slip_num"];
                                                    $deposit_date        = $rs["deposit_date"];
                                                    $deposit_remarks     = $rs["deposit_remarks"];
                                                    $deposit_in          = $rs["deposit_in"];
                                                    $deposit_acc_title   = $rs["deposit_acc_title"];
                                                    echo "<tr>";
                                                    echo "<td>" . date("d-m-Y", strtotime($invoice_date)) . "</td>";
                                                    echo "<td>";
                                                    echo "$cheque_number";
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo(($cheque_date == '0000-00-00') ? "" : date('d-m-Y', strtotime($cheque_date)));
                                                    if ($post_date == "Y") {
                                                        echo "<br /><span class='notes' style='color: #ff0000;'>Post Date</span>";
                                                    }
                                                    echo "</td>";
                                                    echo "<td>$cheque_name</td>";
                                                    echo "<td>" . (($cheque_total_amount != '0.00') ? $cheque_total_amount : "") . "</td>";
                                                    echo "<td>" . getBankShortName($bank_id) . "</td>";
                                                    echo "<td>" . number_format($amount, 2, ".", ",") . "</td>";
                                                    echo "<td>" . $deposit_slip_num . "</td>";
                                                    echo "<td>" . getDepositAccountTitle($deposit_in) . "</td>";
                                                    echo "<td>" . $deposit_acc_title . "</td>";
                                                    echo "<td>" . (($deposit_date == '0000-00-00') ? "" : date('d-m-Y', strtotime($deposit_date))) . "</td>";
                                                    echo "<td>" . $deposit_remarks . "</td>";
                                                    echo "<td align='center'>";
                                                    echo $clearance_status;
                                                    if ($clearance_status !== "PENDING") {
                                                        echo "<br /><span class='notes'>";
                                                        echo date("d-m-Y h:ia", strtotime($cleared_on));
                                                        echo "<br />" . getUserFullName($cleared_by);
                                                        echo "</span>";
                                                    } else {
                                                        if (checkPermission($userInfo["id"], MODIFY)) {
                                                            echo "<br/>";
                                                            if ($deposit_slip_num == '') {
                                                                echo "<input type='button' value='Deposit Form' class='ui-button ui-widget ui-state-default ui-corner-all ui-state-hover' onclick=\"javascript: bankFormSubmit('$id', '$cheque_number');\">";
                                                            } else {
                                                                echo "<select id='clearance_status' name='clearance_status' onChange=\"javascript: updateClearance(this.value, '$id', '$cheque_number');\">";
                                                                echo "<option value=''>-- Update --</option>";
                                                                echo "<option value='CLEARED'>CLEARED</option>";
                                                                echo "<option value='BOUNCED'>BOUNCED</option>";
                                                                echo "</select>";
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr>";
                                                echo "<td align='center' colspan='7'>No record found.</td>";
                                                echo "</tr>";
                                            }
                                            echo "</table>";
                                        }
                                        //} else {
                                        //    echo "<span style='color: #ff0000;'>Please select a date range.</span>";
                                        //}
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <div id="dlgBankDeposit" title="Bank Reconciliation Details">

                            <form action="index.php?ss=<?php echo $ss; ?>&mod=<?php echo $mod; ?>&voucher_number=<?php echo $voucher_number; ?>"
                                  method="POST" id="frmBankDeposit" autocomplete="off">
                                <input type="hidden" name="cmd" value="BANKFORMSUBMIT"/>
                                <input type="hidden" name="tid" id="tid_brd">
                                <input type="hidden" name="cnum" id="cnum_brd">
                                <div id="authList">
                                    <p style="float: left; font-size: 12px; display: block; height: 30px; margin-right: 20px;">
                                        <strong>Deposit Slip No.</strong><br/>
                                        <input type="text" size="20" name="deposit_slip_num" id="deposit_slip_num"
                                               maxlength="20"/>
                                    </p>
                                    <p style="float: left; font-size: 12px; display: block; height: 30px;">
                                        <strong>Deposit Date</strong><br/>
                                        <input type="text" size="20" name="deposit_date" id="cheque_date"
                                               maxlength="10"/>
                                    </p>
                                    <p style="float: left; font-size: 12px; display: block; height: 30px; margin-right: 20px;">
                                        <strong>Deposit In</strong><br/>
                                        <select name="deposit_in" id="deposit_in" style="width: 161.77px; height: 24px;">
                                            <option value="">-- Select --</option>
                                            <?
                                                $sql = "select id, account_number, account_title from deposit_accounts order by account_number";
                                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                                while ($rs = mysql_fetch_array($result)) {
                                                    echo "<option value=\"" . $rs["id"] . "|" . $rs["account_title"] . "\">" . $rs["account_number"] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </p>
                                    <p style="float: left; font-size: 12px; display: block; height: 30px;">
                                        <strong>Account Title</strong><br/>
                                        <input type="text" size="20" name="deposit_acc_title" id="deposit_acc_title"
                                               maxlength="100"/>
                                    </p>
                                    <p style="float: left; font-size: 12px; display: block; width: 160px; height: 30px;">
                                        <strong>Remarks</strong><br/>
                                        <textarea name="deposit_remarks" id="deposit_remarks"
                                                  style="margin: 0px; height: 126px; width: 332px;"></textarea>
                                    </p>
                                </div>
                            </form>
                        </div>

                        <!-- Pending Clearance End -->
                        <?
                    }
                }
            ?>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>