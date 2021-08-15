<?
define("MODULE_ID", "CST002");

include("common/check_access.php");

system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $("#btnPrint").click(function () {
            window.open("print.php?ss=<?=$ss?>&printmod=customers.view&customer_id=<?=$customer_id?>", 'prn');
        });
    });
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Customer Detail
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <?= showError() ?>
                <?php
                if (!empty($customer_id)) {
                    $sql = "select * from customers where id=\"" . mysql_real_escape_string($customer_id) . "\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);

                    if ($numrows > 0) {
                        while ($rs = mysql_fetch_array($result)) {
                            $id                = $rs["id"];
                            $customerAccountId = $rs["account_id"];
                            $full_name         = $rs["full_name"];
                            $father_name       = $rs["father_name"];
                            $street            = $rs["street"];
                            $city              = getCityName($rs["city"]);
                            $state             = $rs["state"];
                            $country           = getCountryName($rs["country"]);
                            $phone_1           = $rs["phone_1"];
                            $phone_2           = $rs["phone_2"];
                            $mobile_1          = $rs["mobile_1"];
                            $mobile_2          = $rs["mobile_2"];
                            $id_num            = $rs["id_num"];

                            $phones = array($phone_1, $phone_2, $mobile_1, $mobile_2);
                        }
                    }
                    ?>
                    <p class="box_title">
                        <?= $full_name ?> son/wife of <?= $father_name ?>
                        <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                            <input type="button" value="Print" id="btnPrint" style="float: right;"/>
                        <? } ?>
                    </p>
                    <p>
                        <strong>Address</strong><br/>
                        <?= $street ?>, <?= $city ?>, <?= $country ?>
                    </p>
                    <p>
                        <strong>CNIC #</strong><br/>
                        <?= $id_num ?>
                    </p>

                    <p>
                        <strong>Phone(s)</strong><br/>
                        <?= formatContacts($phones, ", ") ?>
                    </p>

                    <!-- plot details -->
                    <?php
                    $sql_plot = "SELECT id FROM plots WHERE customer_id = \"" . mysql_real_escape_string($customer_id) . "\"";
                    $result_plot = mysql_query($sql_plot, $conn) or die(mysql_error());
                    $numrows_plot = mysql_num_rows($result_plot);

                    if ($numrows_plot > 0) {
                        while ($rs_plot = mysql_fetch_array($result_plot)) {
                            ?>

                            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td>
                                        <?
                                        $sql = "select * from plots where id=\"" . mysql_real_escape_string($rs_plot["id"]) . "\"";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows = mysql_num_rows($result);

                                        if ($numrows > 0) {
                                            while ($rs = mysql_fetch_array($result)) {
                                                $plot_id            = $rs["id"];
                                                $project_id         = $rs["project_id"];
                                                $customer_id        = $rs["customer_id"];
                                                $dealer_id          = $rs["dealer_id"];
                                                $plotAccountId      = $rs["account_id"];
                                                $commission_account = $rs["commission_account"];
                                                $expense_account    = $rs["expense_account"];
                                                $plot_number        = $rs["plot_number"];
                                                $plot_type          = $rs["plot_type"];
                                                $size               = $rs["size"];
                                                $size_type          = $rs["size_type"];
                                                $width              = $rs["width"];
                                                $length             = $rs["length"];
                                                $rate_per_marla     = $rs["rate_per_marla"];
                                                $status             = $rs["status"];
                                                $notes              = $rs["notes"];
                                                $plot_value         = $rate_per_marla * $size;
                                            }
                                        }
                                        ?>


                                        <?
                                        $plot_features = "";
                                        $sql2          = "select feature_id from plots_features where plot_id='$plot_id'";
                                        $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                                        while ($rs2 = mysql_fetch_array($result2)) {
                                            $feature_id = $rs2["feature_id"];

                                            $sql3 = "select title, price, price_type from lookup_plot_features where id='$feature_id'";
                                            $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                                            while ($rs3 = mysql_fetch_array($result3)) {
                                                $feature_title     = $rs3["title"];
                                                $feature_price     = $rs3["price"];
                                                $feature_pricetype = $rs3["price_type"];

                                                if ($feature_pricetype == "F") {
                                                    $plot_value    += $feature_price;
                                                    $plot_features .= "$feature_title <span class='notes'>($feature_price)</span><br />";
                                                } elseif ($feature_pricetype == "P") {
                                                    $pval          = ($plot_value * $feature_price) / 100;
                                                    $plot_value    += $pval;
                                                    $plot_features .= "$feature_title <span class='notes'>($feature_price%)</span><br />";
                                                }
                                            }
                                        }
                                        ?>

                                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
                                               id="tblDetails">
                                            <tr>
                                                <td width="50%" valign="top">
                                                    <p style="font-size: 18px; font-weight: bold;">
                                                        <strong>Plot Number</strong><br>
                                                        <?= $plot_number ?> <span
                                                                style="font-size: 12px;">(<?= $plot_type ?>
                                                            )</span>
                                                    </p>
                                                    <p style="font-size: 18px; font-weight: bold;">
                                                        <strong>Total Value</strong><br>
                                                        Rs. <?= number_format($plot_value, 0, ".", ",") ?>
                                                    </p>
                                                    <?= !empty($plot_features) ? "<p><strong>Features</strong><br />$plot_features</p>" : "" ?>
                                                </td>
                                                <td width="50%" valign="top">
                                                    <p style="font-size: 18px; font-weight: bold;">
                                                        <strong>Project</strong><br>
                                                        <?= getProjectName($project_id) ?>
                                                    </p>
                                                    <p style="font-size: 18px; font-weight: bold;">
                                                        <strong>Status & Notes</strong><br>
                                                        <?= $status ?>
                                                        <?
                                                        if (!empty($notes)) {
                                                            echo " / $notes";
                                                        }
                                                        ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <!-- Plot Amounts Start -->
                                                    <table borer="0" width="100%" cellpadding="5" cellspacing="0"
                                                           align="center"
                                                           id="reclist">
                                                        <tr id="listhead">
                                                            <td width="15%"><strong>Date</strong></td>
                                                            <td width="45%"><strong>Particulars</strong></td>
                                                            <td width="10%" align="right"><strong>RCVD.</strong></td>
                                                            <td width="10%" align="right"><strong>PAID</strong></td>
                                                            <td width="20%" align="right"><strong>Balance /
                                                                    Dues</strong></td>
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
                                                ";    //and t.account_id = '$customerAccountId'
                                                        //echo $sql;
                                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                                        $numrows = mysql_num_rows($result);

                                                        if ($numrows > 0) {
                                                            while ($rs = mysql_fetch_array($result)) {
                                                                $openingDate = $rs["invoice_date"];
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?= date("d-m-Y", strtotime($openingDate)) ?></td>
                                                                <td>** Opening Balance **</td>
                                                                <td align="center">--</td>
                                                                <td align="center">--</td>
                                                                <td align="right"><?= number_format($plot_value, 2, ".", ",") ?></td>
                                                            </tr>
                                                            <?
                                                            $balance      = $plot_value;
                                                            $rcvdTotal    = 0;
                                                            $paidTotal    = 0;
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
                                                    ";    //and t.account_id = '$customerAccountId'
                                                            $result = mysql_query($sql, $conn) or die(mysql_error());
                                                            $numrows = mysql_num_rows($result);

                                                            if ($numrows > 0) {
                                                                while ($rs = mysql_fetch_array($result)) {
                                                                    $account_id       = $rs["account_id"];
                                                                    $accountTitle     = getAccountTitle($rs["account_id"]);
                                                                    $voucher_id       = $rs["voucher_id"];
                                                                    $transaction_type = $rs["transaction_type"];
                                                                    $invoice_date     = $rs["invoice_date"];
                                                                    $amount           = $rs["amount"];
                                                                    $voucher_type     = $rs["voucher_type"];
                                                                    $bank_id          = $rs["bank_id"];
                                                                    $cheque_number    = $rs["cheque_number"];
                                                                    $cheque_date      = $rs["cheque_date"];
                                                                    $cheque_name      = $rs["cheque_name"];

                                                                    echo "<tr>";
                                                                    echo "<td>" . date("d-m-Y", strtotime($invoice_date)) . "</td>";
                                                                    echo "<td>";

                                                                    if ($transaction_type == "PAYMENT") {
                                                                        echo "+ Paid to $accountTitle";
                                                                    } else {
                                                                        echo "- Received from $accountTitle";
                                                                    }

                                                                    echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a></span>";

                                                                    if ($voucher_type == "BANK") {
                                                                        echo "<br /><span style='float: right; font-size: 10px;'>(" . getBankShortName($bank_id) . " / $cheque_number / " . ($cheque_date == "0000-00-00" ? "" : date("d-m-Y", strtotime($cheque_date))) . " / $cheque_name)</span>";
                                                                    }

                                                                    echo "</td>";

                                                                    if ($transaction_type == "RECEIPT") {
                                                                        $balance   = $balance - $amount;
                                                                        $rcvdTotal += $amount;

                                                                        echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";
                                                                        echo "<td align='center'>--</td>";
                                                                    } else {
                                                                        $balance   = $balance + $amount;
                                                                        $paidTotal += $amount;

                                                                        echo "<td align='ccenter'>--</td>";
                                                                        echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";
                                                                    }

                                                                    echo "<td align='right'>" . number_format($balance, 2, ".", ",") . "</td>";
                                                                    echo "</tr>";
                                                                }
                                                            }

                                                            $balanceTotal = $plot_value - $rcvdTotal + $paidTotal;

                                                            echo "<tr>";
                                                            echo "<td>&nbsp;</td>";
                                                            echo "<td style='font-size: 16px; font-weight: bold;'>Totals</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($rcvdTotal, 2, ".", ",") . "</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($paidTotal, 2, ".", ",") . "</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($balanceTotal, 2, ".", ",") . "</td>";
                                                            echo "</tr>";
                                                        } else {
                                                            echo "<tr><td colspan='5'>No record found.</td></tr>";
                                                        }
                                                        ?>
                                                    </table>
                                                    <!-- Plot Amounts End -->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <?php
                        }
                    } else {
                        echo "<span style='color: #ff0000;'>No record exists.</span>";
                    }
                    ?>
                    <!-- plot details -->
                    <?
                } else {
                    echo "<span style='color: #ff0000;'>Please select a customer.</span>";
                }
                ?>
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