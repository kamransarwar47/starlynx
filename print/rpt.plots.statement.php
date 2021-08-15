<?
    define("MODULE_ID", "RPT001");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
            if(!empty($plot_id)) {
                $sql = "select id from plots where id=\"".mysql_real_escape_string($plot_id)."\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if($numrows > 0) {
                    $print = true;
                    system_log(PRNT, "$plot_id loaded for printing.", $userInfo["id"]);
                } else {
                    setMessage("Record not found.");
                    system_log(PRNT, "Operation failed. [Reason: Record not found.]", $userInfo["id"]);
                }
            } else {
                setMessage("Nothing to load...");
                system_log(PRNT, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StarLynx - Star Developers</title>
<link rel="stylesheet" href="css/print.css" media="screen, print" />
</head>
<body>
<? include("common/header.invoice.php"); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                <?=showError()?>
                <p class="box_title">
                    Plot Statement
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?=date("d-m-Y", time())?></span>
                </p>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                <?
                    //if(!empty($project_id) && !empty($plot_id)) {
                        $sql = "select * from plots where id=\"".mysql_real_escape_string($plot_id)."\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if($numrows > 0) {
                            while($rs = mysql_fetch_array($result)) {
                                $plot_id = $rs["id"];
                                $project_id = $rs["project_id"];
                                $customer_id = $rs["customer_id"];
                                $dealer_id = $rs["dealer_id"];
                                $plotAccountId = $rs["account_id"];
                                $commission_account = $rs["commission_account"];
                                $expense_account = $rs["expense_account"];
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
                            }
                        }
                ?>

                <?
                                    $plot_features = "";
                                    $sql2 = "select feature_id from plots_features where plot_id='$plot_id'";
                                    $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                                    while($rs2 = mysql_fetch_array($result2)) {
                                        $feature_id = $rs2["feature_id"];

                                        $sql3 = "select title, price, price_type from lookup_plot_features where id='$feature_id'";
                                        $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                                        while($rs3 = mysql_fetch_array($result3)) {
                                            $feature_title = $rs3["title"];
                                            $feature_price = $rs3["price"];
                                            $feature_pricetype = $rs3["price_type"];

                                            if($feature_pricetype == "F") {
                                                $plot_value += $feature_price;
                                                $plot_features .= "$feature_title <span class='notes'>($feature_price)</span><br />";
                                            } elseif ($feature_pricetype == "P") {
                                                $pval = ($plot_value * $feature_price) / 100;
                                                $plot_value += $pval;
                                                $plot_features .= "$feature_title <span class='notes'>($feature_price%)</span><br />";
                                            }
                                        }
                                    }
                                ?>

                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
                        <tr>
                            <td width="50%" valign="top">
                                <p style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                                    <strong>Plot Number</strong><br />
                                    <?=$plot_number?> <span style="font-size: 12px;">(<?=$plot_type?>)</span>
                                </p>
                                <p style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                                    <strong>Project</strong><br />
                                    <?=getProjectName($project_id)?>
                                </p>
                                <p style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                                    <strong>Total Value</strong><br />
                                    Rs. <?=number_format($plot_value, 0, ".", ",")?>
                                </p>


                                <?=!empty($plot_features)?"<p><strong>Features</strong><br />$plot_features</p>":""?>
                            </td>
                            <td width="50%" valign="top">
                                <?
                                    if(!empty($customer_id)) {
                                        $sql = "select * from customers where id=\"".mysql_real_escape_string($customer_id)."\"";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows = mysql_num_rows($result);

                                        if($numrows > 0) {
                                            while($rs = mysql_fetch_array($result)) {
                                                $id = $rs["id"];
                                                $full_name = $rs["full_name"];
                                                $father_name = $rs["father_name"];
                                                $street = $rs["street"];
                                                $city = getCityName($rs["city"]);
                                                $zip = $rs["zip"];
                                                $state = $rs["state"];
                                                $country = getCountryName($rs["country"]);
                                                $phone_1 = $rs["phone_1"];
                                                $phone_2 = $rs["phone_2"];
                                                $mobile_1 = $rs["mobile_1"];
                                                $mobile_2 = $rs["mobile_2"];
                                                $fax_1 = $rs["fax_1"];
                                                $fax_2 = $rs["fax_2"];
                                                $email_1 = $rs["email_1"];
                                                $email_2 = $rs["email_2"];
                                                $id_num = $rs["id_num"];
                                                $date_of_birth = $rs["date_of_birth"];

                                                $phones = array($phone_1, $phone_2, $mobile_1, $mobile_2);
                                            }
                                        }
                                    }
                                ?>
                                <p style="margin-bottom: 10px;">
                                    <strong>Sold to</strong><br />
                                    <?=$full_name?> son/wife of <?=$father_name?>
                                </p>
                                <p style="margin-bottom: 10px;">
                                    <strong>Address</strong><br />
                                    <?=$street?>, <?=$city?>, <?=$country?>
                                </p>
                                <p style="margin-bottom: 10px;">
                                        <strong>CNIC #</strong><br />
                                        <?=$id_num?>
                                    </p>

                                <p style="margin-bottom: 10px;">
                                        <strong>Phone(s)</strong><br />
                                        <?=formatContacts($phones, ", ")?>
                                    </p>

                                <p>
                                    <strong>Status & Notes</strong><br />
                                    <?=$status?>
                                    <?
                                        if(!empty($notes)) {
                                            echo " / $notes";
                                        }
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <!-- Plot Amounts Start -->
                                <? if($chkPrnStmt == "Y") { ?>
                                <table borer="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                    <tr id="listhead">
                                        <td width="15%"><strong>Date</strong></td>
                                        <td width="45%"><strong>Particulars</strong></td>
                                        <td width="10%" align="right"><strong>RCVD.</strong></td>
                                        <td width="10%" align="right"><strong>PAID</strong></td>
                                        <td width="20%" align="right"><strong>Balance / Dues</strong></td>
                                    </tr>
                                    <?
                                        // Get date of first receipt
                                        //$plotAccountId = getPlotAccountId($plot_id);
                                        $sql = "select
                                                    t.invoice_date
                                                from
                                                    transactions t, transactions_details d
                                                where
                                                    t.id = d.transaction_id
                                                    and d.account_id = '$plotAccountId'
                                                order by
                                                    t.id asc
                                                limit 0,1
                                                ";
                                         //echo $sql;
                                         $result = mysql_query($sql, $conn) or die(mysql_error());
                                         $numrows = mysql_num_rows($result);

                                         if($numrows > 0) {
                                             while($rs = mysql_fetch_array($result)) {
                                                 $openingDate = $rs["invoice_date"];
                                             }
                                    ?>
                                            <tr>
                                                <td><?=date("d-m-Y", strtotime($openingDate))?></td>
                                                <td>** Opening Balance **</td>
                                                <td align="right">--</td>
                                                <td align="right">--</td>
                                                <td align="right"><?=number_format($plot_value, 2, ".", ",")?></td>
                                            </tr>
                                    <?
                                            $balance = $plot_value;
                                            $rcvdTotal = 0;
                                            $paidTotal = 0;
                                            $balanceTotal = 0;

                                            // Select transactions
                                            $sql = "select
                                                        t.account_id, t.voucher_id, t.transaction_type, t.invoice_date,
                                                        d.amount, d.voucher_type, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and d.account_id = '$plotAccountId'
                                                    order by
                                                        t.invoice_date asc
                                                    ";
                                            $result = mysql_query($sql, $conn) or die(mysql_error());
                                            $numrows = mysql_num_rows($result);

                                            if($numrows > 0) {
                                                while($rs = mysql_fetch_array($result)) {
                                                    $account_id = $rs["account_id"];
                                                    $accountTitle = getAccountTitle($rs["account_id"]);
                                                    $voucher_id = $rs["voucher_id"];
                                                    $transaction_type = $rs["transaction_type"];
                                                    $invoice_date = $rs["invoice_date"];
                                                    $amount = $rs["amount"];
                                                    $voucher_type = $rs["voucher_type"];
                                                    $bank_id = $rs["bank_id"];
                                                    $cheque_number = $rs["cheque_number"];
                                                    $cheque_date = $rs["cheque_date"];
                                                    $cheque_name = $rs["cheque_name"];

                                                    echo "<tr>";
                                                    echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                                    echo "<td>";

                                                    if($transaction_type == "PAYMENT") {
                                                        echo "+ Paid to $accountTitle";
                                                    } else {
                                                        echo "- Received from $accountTitle";
                                                    }

                                                    echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: $voucher_id</span>";

                                                    if($voucher_type == "BANK") {
                                                        echo "<br /><span style='float: right; font-size: 10px;'>(".getBankShortName($bank_id)." / $cheque_number / ".($cheque_date=="0000-00-00"?"":date("d-m-Y", strtotime($cheque_date)))." / $cheque_name)</span>";
                                                    }

                                                    echo "</td>";

                                                    if($transaction_type == "RECEIPT") {
                                                        $balance = $balance - $amount;
                                                        $rcvdTotal += $amount;

                                                        echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                                        echo "<td align='right'>--</td>";
                                                    } else {
                                                        $balance = $balance + $amount;
                                                        $paidTotal += $amount;

                                                        echo "<td align='right'>--</td>";
                                                        echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                                    }

                                                    echo "<td align='right'>".number_format($balance, 2, ".", ",")."</td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            $balanceTotal = $plot_value - $rcvdTotal + $paidTotal;

                                            echo "<tr>";
                                            echo "<td>&nbsp;</td>";
                                            echo "<td style='font-size: 16px; font-weight: bold;'>Totals</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($rcvdTotal, 2, ".", ",")."</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($paidTotal, 2, ".", ",")."</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($balanceTotal, 2, ".", ",")."</td>";
                                            echo "</tr>";

                                             echo "<tr>";
                                             echo "<td>&nbsp;</td>";
                                             echo "<td style='font-size: 16px; font-weight: bold;'>Balance</td>";
                                             echo "<td colspan='2' align='center' style='font-size: 16px; font-weight: bold;'>" . number_format(($rcvdTotal - $paidTotal), 2, ".", ",") . "</td>";
                                             echo "<td>&nbsp;</td>";
                                             echo "</tr>";
                                         } else {
                                             echo "<tr><td colspan='5'>No record found.</td></tr>";
                                         }
                                    ?>
                                </table>
                                <? } ?>
                                <!-- Plot Amounts End -->

                                <? if($chkPrnExp == "Y") { ?>
                                <br />
                                <!-- Plot Expense Start -->
                                <table borer="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                    <tr id="listhead">
                                        <td colspan="5"><strong>Expense</strong></td>
                                    </tr>
                                    <tr id="listhead">
                                        <td width="15%"><strong>Date</strong></td>
                                        <td width="45%"><strong>Particulars</strong></td>
                                        <td width="10%" align="right"><strong>RCVD.</strong></td>
                                        <td width="10%" align="right"><strong>PAID</strong></td>
                                        <td width="20%" align="right"><strong>Balance / Dues</strong></td>
                                    </tr>

                                    <?
                                            $balance = 0;
                                            $rcvdTotal = 0;
                                            $paidTotal = 0;
                                            $balanceTotal = 0;

                                            // Select transactions
                                            $sql = "select
                                                        t.account_id, t.voucher_id, t.transaction_type, t.invoice_date,
                                                        d.amount, d.voucher_type, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and d.account_id = '$expense_account'
                                                    order by
                                                        t.invoice_date asc
                                                    ";	//and t.account_id = '$customerAccountId'
                                            $result = mysql_query($sql, $conn) or die(mysql_error());
                                            $numrows = mysql_num_rows($result);

                                            if($numrows > 0) {
                                                while($rs = mysql_fetch_array($result)) {
                                                    $account_id = $rs["account_id"];
                                                    $accountTitle = getAccountTitle($rs["account_id"]);
                                                    $voucher_id = $rs["voucher_id"];
                                                    $transaction_type = $rs["transaction_type"];
                                                    $invoice_date = $rs["invoice_date"];
                                                    $amount = $rs["amount"];
                                                    $voucher_type = $rs["voucher_type"];
                                                    $bank_id = $rs["bank_id"];
                                                    $cheque_number = $rs["cheque_number"];
                                                    $cheque_date = $rs["cheque_date"];
                                                    $cheque_name = $rs["cheque_name"];

                                                    echo "<tr>";
                                                    echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                                    echo "<td>";

                                                    if($transaction_type == "PAYMENT") {
                                                        echo "- Paid to $accountTitle";
                                                    } else {
                                                        echo "+ Received from $accountTitle";
                                                    }

                                                    echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: $voucher_id</span>";

                                                    if($voucher_type == "BANK") {
                                                        echo "<br /><span style='float: right; font-size: 10px;'>(".getBankShortName($bank_id)." / $cheque_number / ".($cheque_date=="0000-00-00"?"":date("d-m-Y", strtotime($cheque_date)))." / $cheque_name)</span>";
                                                    }

                                                    echo "</td>";

                                                    if($transaction_type == "RECEIPT") {
                                                        $balance = $balance + $amount;
                                                        $rcvdTotal += $amount;

                                                        echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                                        echo "<td align='center'>--</td>";
                                                    } else {
                                                        $balance = $balance - $amount;
                                                        $paidTotal += $amount;

                                                        echo "<td align='ccenter'>--</td>";
                                                        echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                                    }

                                                    echo "<td align='right'>".number_format($balance, 2, ".", ",")."</td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            $balanceTotal = $rcvdTotal - $paidTotal;

                                            echo "<tr>";
                                            echo "<td>&nbsp;</td>";
                                            echo "<td style='font-size: 16px; font-weight: bold;'>Totals</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($rcvdTotal, 2, ".", ",")."</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($paidTotal, 2, ".", ",")."</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($balanceTotal, 2, ".", ",")."</td>";
                                            echo "</tr>";
                                    ?>
                                </table>
                                <!-- Plot Expense End -->
                                <? } ?>

                                <? if($chkPrnComm == "Y") { ?>
                                <br />
                                <!-- Plot Commission Start -->
                                <table borer="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                    <tr id="listhead">
                                        <td colspan="3"><strong>Commission Paid</strong></td>
                                    </tr>
                                    <tr id="listhead">
                                        <td width="15%"><strong>Date</strong></td>
                                        <td width="75%"><strong>Particulars</strong></td>
                                        <td width="10%" align="right"><strong>PAID</strong></td>
                                    </tr>

                                    <?
                                            $balance = 0;
                                            $rcvdTotal = 0;
                                            $paidTotal = 0;
                                            $balanceTotal = 0;

                                            // Select transactions
                                            $sql = "select
                                                        t.account_id, t.voucher_id, t.transaction_type, t.invoice_date,
                                                        d.amount, d.voucher_type, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and d.account_id = '$commission_account'
                                                    order by
                                                        t.invoice_date asc
                                                    ";	//and t.account_id = '$customerAccountId'
                                            $result = mysql_query($sql, $conn) or die(mysql_error());
                                            $numrows = mysql_num_rows($result);

                                            if($numrows > 0) {
                                                while($rs = mysql_fetch_array($result)) {
                                                    $account_id = $rs["account_id"];
                                                    $accountTitle = getAccountTitle($rs["account_id"]);
                                                    $voucher_id = $rs["voucher_id"];
                                                    $transaction_type = $rs["transaction_type"];
                                                    $invoice_date = $rs["invoice_date"];
                                                    $amount = $rs["amount"];
                                                    $voucher_type = $rs["voucher_type"];
                                                    $bank_id = $rs["bank_id"];
                                                    $cheque_number = $rs["cheque_number"];
                                                    $cheque_date = $rs["cheque_date"];
                                                    $cheque_name = $rs["cheque_name"];

                                                    echo "<tr>";
                                                    echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                                    echo "<td>";
                                                    echo "Paid to $accountTitle";
                                                    echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: $voucher_id</span>";

                                                    if($voucher_type == "BANK") {
                                                        echo "<br /><span style='float: right; font-size: 10px;'>(".getBankShortName($bank_id)." / $cheque_number / ".($cheque_date=="0000-00-00"?"":date("d-m-Y", strtotime($cheque_date)))." / $cheque_name)</span>";
                                                    }

                                                    echo "</td>";

                                                    $paidTotal += $amount;

                                                    echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            echo "<tr>";
                                            echo "<td>&nbsp;</td>";
                                            echo "<td style='font-size: 16px; font-weight: bold;'>Total</td>";
                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>".number_format($paidTotal, 2, ".", ",")."</td>";
                                            echo "</tr>";
                                    ?>
                                </table>
                                <!-- Plot Commission End -->
                                <? } ?>
                            </td>
                        </tr>
                    </table>
                <?
                    //} else {
                    //    echo "<span style='color: #ff0000;'>Please select a plot.</span>";
                    //}
                ?>
                   </td></tr>
                   </table>
            <? } ?>
        </td>
    </tr>
</table>
<?
    if($print) {
?>
    <script type="text/javascript">
        window.print();
    </script>
<?
    }
?>
</body>
</html>