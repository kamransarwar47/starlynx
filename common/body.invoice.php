<?
    if ($numrows > 0) {
        ?>
        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
               style="margin-top: 20px; border: 1px solid #000;" id="invoice_body">
            <tr>
                <td style="font-size: 14px;"><strong>Project:</strong> <?= getProjectName($project_id) ?></td>
                <td style="font-size: 14px;"><strong>Reference #:</strong> <?= $voucher_id ?></td>
                <td style="font-size: 14px;"><strong>Date:</strong> <?= date("d/m/Y", strtotime($invoice_date)) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="font-size: 14px;">
                    <strong><?= ($transaction_type == "PAYMENT" ? "Paid To" : "Received From") ?>
                        :</strong> <?= getAccountTitle($account_id) ?></td>
            </tr>
            <tr>
                <td colspan="3">
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                        <tr id="listhead">
                            <td width="70%">Particulars</td>
                            <td width="20%" align='right'>Amount</td>
                            <td width="10%" align='right'>Type</td>
                        </tr>
                        <?
                            $dsql = "select * from transactions_details where transaction_id='$id'";
                            $dres = mysql_query($dsql, $conn) or die(mysql_error());
                            $drows = mysql_num_rows($dres);
                            $total = 0;
                            while ($drs = mysql_fetch_array($dres)) {
                                $d_account_id        = $drs["account_id"];
                                $d_amount            = $drs["amount"];
                                $d_notes             = $drs["notes"];
                                $d_voucher_type      = $drs["voucher_type"];
                                $d_bank_id           = $drs["bank_id"];
                                $d_cheque_number     = $drs["cheque_number"];
                                $d_cheque_date       = $drs["cheque_date"];
                                $d_cheque_name       = $drs["cheque_name"];
                                $d_post_date         = $drs["post_date"];
                                $d_clearance_status  = $drs["clearance_status"];
                                $d_check_installment = $drs["check_installment"];
                                $total               += $d_amount;
                                if ($d_check_installment == 'yes') {
                                    $installment_data   = getNextInstallmentDetailByTransaction($d_account_id, $invoice_date);
                                    $current_due_amount = $installment_data['pending_due_amount'];
                                    $remaining_amount   = ($installment_data['remaining_amount'] + $installment_data['pending_due_amount']);
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?
                                            if ($print_user_type == "STAFF") {
                                                $sub_account_array = getSubAccountsExcelSheet($project_id, $d_account_id);
                                                echo $sub_account_array[0] . (($sub_account_array[1] != "") ? "&nbsp;> " . $sub_account_array[1] : "") . (($sub_account_array[2] != "") ? "&nbsp;> " . $sub_account_array[2] : "") . (($sub_account_array[3] != "") ? "&nbsp;> " . $sub_account_array[3] : "");
                                            } else {
                                                echo getAccountTitle($d_account_id);
                                            }
                                            if (!empty($d_notes)) {
                                                echo "<br /><span style='font-size: 10px;'>$d_notes</span>";
                                            }
                                            if ($d_voucher_type == "BANK") {
                                                echo "<span style='float: right; font-size: 10px;'>(" . getBankShortName($d_bank_id) . " / $d_cheque_number / " . ($d_cheque_date == "0000-00-00" ? "" : date("d-m-Y", strtotime($d_cheque_date))) . " / $d_cheque_name)</span>";
                                            }
                                        ?>
                                        <?php
                                            if ($d_check_installment == 'yes') {
                                                if (!empty($installment_data)) {
                                                    ?>
                                                    <br>
                                                    <span style="float: right;">Current Installment Due:</span>
                                                    <br>
                                                    <span style="float: right;">Next Installment Date:</span>
                                                    <br>
                                                    <span style="float: right;">Customer Remaining Amount:</span>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td align='right'>
                                        <?= number_format($d_amount, 2, ".", ",") ?>
                                        <?php
                                            if ($d_check_installment == 'yes') {
                                                if (!empty($installment_data)) {
                                                    ?>
                                                    <br><br>
                                                    <span style="float: left;"><?php echo formatCurrency($current_due_amount); ?></span>
                                                    <br>
                                                    <span style="float: left;"><?php echo(isset($installment_data['next_due_date']) ? date('d/m/Y', strtotime($installment_data['next_due_date'])) : ''); ?></span>
                                                    <br>
                                                    <span style="float: left;"><?php echo formatCurrency($remaining_amount); ?></span>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td align='right' valign="top">
                                        <?= $d_voucher_type ?>
                                    </td>
                                </tr>
                                <?
                            }
                        ?>
                        <tr id="rowLast">
                            <td style="font-size: 14px;"><strong>Total</strong></td>
                            <td style="font-size: 18px; font-weight: bold;" id="totals"
                                align='right'><?= number_format($total, 2, ".", ",") ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <? //if(!empty($notes)) { ?>
                        <!-- tr>
                                               <td style="font-size: 12px;" colspan="3"><strong>Notes:</strong> <?= $notes ?></td>
                                           </tr -->
                        <? //} ?>

                    </table>
                </td>
            </tr>
        </table>
        <?
    }
?>