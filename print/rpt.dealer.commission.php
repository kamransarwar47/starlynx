<?
    define("MODULE_ID", "RPT005");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
            if(!empty($dealer_id)) {
                $sql = "select id from dealers where id=\"".mysql_real_escape_string($dealer_id)."\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if($numrows > 0) {
                    $print = true;
                    system_log(PRNT, "$dealer_id loaded for printing.", $userInfo["id"]);
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
                    Dealer Commission
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?=date("d-m-Y", time())?></span>
                </p>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                <?
                    if(!empty($dealer_id)) {
                        $sql = "select * from dealers where id=\"".mysql_real_escape_string($dealer_id)."\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if($numrows > 0) {
                            while($rs = mysql_fetch_array($result)) {
                                $id = $rs["id"];
                                $dealerAccountId = $rs["account_id"];
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
                ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="tblDetails">
                        <tr>
                            <td width="50%" valign="top">
                                <p>
                                    <strong>Dealer Name</strong><br />
                                    <?=$full_name?> son/wife of <?=$father_name?>
                                </p>
                                <p>
                                    <strong>Address</strong><br />
                                    <?=$street?>, <?=$city?>, <?=$country?>
                                </p>
                            </td>
                            <td width="50%" valign="top">
                                <p>
                                        <strong>CNIC #</strong><br />
                                        <?=$id_num?>
                                    </p>

                                <p>
                                        <strong>Phone(s)</strong><br />
                                        <?=formatContacts($phones, ", ")?>
                                    </p>
                            </td>
                        </tr>
                    </table><br />
                    <?
                        $sql = "select * from plots where dealer_id=\"".mysql_real_escape_string($dealer_id)."\"";
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
                                $plot_number = $rs["plot_number"];
                                $plot_type = $rs["plot_type"];
                                $size = $rs["size"];
                                $size_type = $rs["size_type"];

                                //if($numrows2 > 0) {
                                    echo "<span style='font-size: 14px; font-weight: bold;'>";
                                    echo ($plot_type=="Residential"?"Plot":"Shop")." #: $plot_number ($size $size_type) / ".getProjectName($project_id)."<br />";
                                    echo "</span>";
                    ?>
                                    <!-- Plot Commission Start -->
                                    <table borer="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
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
                                                $sql3 = "select
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
                                                $result3 = mysql_query($sql3, $conn) or die(mysql_error());
                                                $numrows3 = mysql_num_rows($result3);

                                                if($numrows3 > 0) {
                                                    while($rs3 = mysql_fetch_array($result3)) {
                                                        $account_id = $rs3["account_id"];
                                                        $accountTitle = getAccountTitle($rs3["account_id"]);
                                                        $voucher_id = $rs3["voucher_id"];
                                                        $transaction_type = $rs3["transaction_type"];
                                                        $invoice_date = $rs3["invoice_date"];
                                                        $amount = $rs3["amount"];
                                                        $voucher_type = $rs3["voucher_type"];
                                                        $bank_id = $rs3["bank_id"];
                                                        $cheque_number = $rs3["cheque_number"];
                                                        $cheque_date = $rs3["cheque_date"];
                                                        $cheque_name = $rs3["cheque_name"];

                                                        echo "<tr>";
                                                        echo "<td valign='top'>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                                        echo "<td valign='top'>";
                                                        echo "Paid to $accountTitle";
                                                        echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: $voucher_id</span>";

                                                        if($voucher_type == "BANK") {
                                                            echo "<br /><span style='float: right; font-size: 10px;'>(".getBankShortName($bank_id)." / $cheque_number / ".($cheque_date=="0000-00-00"?"":date("d-m-Y", strtotime($cheque_date)))." / $cheque_name)</span>";
                                                        }

                                                        echo "</td>";

                                                        $paidTotal += $amount;

                                                        echo "<td valign='top' align='right'>".number_format($amount, 2, ".", ",")."</td>";
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
                    <?
                                //}
                            }
                        }
                    ?>
                <?
                    } else {
                        echo "<span style='color: #ff0000;'>Please select a dealer.</span>";
                    }
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