<?
    define("MODULE_ID", "INV002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $("#btnPrint").click(function () {
            window.open("print.php?ss=<?=$ss?>&printmod=investor.view&investor_id=<?=$investor_id?>", 'prn');
        });
    });
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Investor Detail
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <?= showError() ?>
                <?php
                if (!empty($investor_id)) {
                    $sql = "select * from investor where id=\"" . mysql_real_escape_string($investor_id) . "\"";
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

                    <!-- Project details -->
                    <?php
                    $sql_project = "SELECT t.project_id, t.account_id FROM transactions t JOIN projects p ON t.project_id = p.id WHERE t.account_id = \"" . mysql_real_escape_string(getInvestorAccountId($investor_id)) . "\" GROUP BY t.project_id ORDER BY p.title ASC";
                    $result_project = mysql_query($sql_project, $conn) or die(mysql_error());
                    $numrows_project = mysql_num_rows($result_project);

                    if ($numrows_project > 0) {
                        while ($rs_project = mysql_fetch_array($result_project)) {
                            $project_id          = $rs_project["project_id"];
                            $investor_account_id = $rs_project["account_id"];
                            ?>

                            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td>
                                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center"
                                               id="tblDetails">
                                            <tr>
                                                <td width="50%" valign="top">
                                                    <p style="font-size: 18px; font-weight: bold;">
                                                        <strong>Project</strong><br>
                                                        <?= getProjectName($project_id) ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <!-- Project Plots -->
                                                    <?php
                                                        $paidTotal = 0;
                                                        $rcvdTotal = 0;

                                                    // Select project plots
                                                    $sql_plots = "select
                                                        DISTINCT(d.account_id)
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and t.account_id = '$investor_account_id'
                                                        and t.project_id = '$project_id'";
                                                    $result_plots = mysql_query($sql_plots, $conn) or die(mysql_error());
                                                    $numrows_plots = mysql_num_rows($result_plots);

                                                    if ($numrows_plots > 0) {
                                                        while ($rs_plots = mysql_fetch_array($result_plots)) {
                                                        $plot_account_id = $rs_plots["account_id"];
                                                    ?>
                                                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" style="margin-bottom: 10px;">
                                                        <tr>
                                                            <td width="50%" valign="top">
                                                                <p style="font-size: 18px; font-weight: bold;">
                                                                    <strong>Plot</strong><br>
                                                                    <?= getAccountTitle($plot_account_id) ?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                    <!-- Project Investor Payment Start -->
                                                    <table borer="0" width="100%" cellpadding="5" cellspacing="0"
                                                           align="center" id="reclist">
                                                        <tr id="listhead">
                                                            <td width="15%"><strong>Date</strong></td>
                                                            <td width="75%"><strong>Particulars</strong></td>
                                                            <td width="10%" align="right"><strong>RCVD.</strong></td>
                                                            <td width="10%" align="right"><strong>PAID</strong></td>
                                                        </tr>

                                                        <?
                                                            // Select transactions
                                                            $sql = "select
                                                        t.account_id, t.voucher_id, t.transaction_type, t.invoice_date, d.notes,
                                                        d.amount, d.voucher_type, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and t.account_id = '$investor_account_id'
                                                        and t.project_id = '$project_id'
                                                        and d.account_id = '$plot_account_id'
                                                    order by
                                                        t.invoice_date asc
                                                    ";
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
                                                                    $notes            = $rs["notes"];

                                                                    echo "<tr>";
                                                                    echo "<td>" . date("d-m-Y", strtotime($invoice_date)) . "</td>";
                                                                    echo "<td>";
                                                                    echo ($transaction_type == "PAYMENT" ? "- Paid To" : "+ Received From") . ": $accountTitle " . (($notes != "") ? "(" . $notes . ")" : "");
                                                                    echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a></span>";

                                                                    if ($voucher_type == "BANK") {
                                                                        echo "<br /><span style='float: right; font-size: 10px;'>(" . getBankShortName($bank_id) . " / $cheque_number / " . ($cheque_date == "0000-00-00" ? "" : date("d-m-Y", strtotime($cheque_date))) . " / $cheque_name)</span>";
                                                                    }

                                                                    echo "</td>";

                                                                    if ($transaction_type == "RECEIPT") {
                                                                        $rcvdTotal += $amount;
                                                                        echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";
                                                                        echo "<td align='center'>--</td>";
                                                                    } else {
                                                                        $paidTotal += $amount;
                                                                        echo "<td align='center'>--</td>";
                                                                        echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";
                                                                    }

                                                                    echo "</tr>";
                                                                }
                                                            }
                                                        ?>
                                                    </table>
                                                    <!-- Plot Commission End -->
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <table borer="0" width="100%" cellpadding="5" cellspacing="0"
                                                           align="center" id="reclist" style="border-top: 2px dashed #aaa; padding-top: 10px;">
                                                        <tr id="listhead">
                                                            <td width="15%"><strong>&nbsp;</strong></td>
                                                            <td width="75%"><strong>&nbsp;</strong></td>
                                                            <td width="10%" align="right"><strong>RCVD.</strong></td>
                                                            <td width="10%" align="right"><strong>PAID</strong></td>
                                                        </tr>
                                                        <?php
                                                            echo "<tr>";
                                                            echo "<td>&nbsp;</td>";
                                                            echo "<td style='font-size: 16px; font-weight: bold;'>Total</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($rcvdTotal, 2, ".", ",") . "</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($paidTotal, 2, ".", ",") . "</td>";
                                                            echo "</tr>";

                                                            echo "<tr>";
                                                            echo "<td>&nbsp;</td>";
                                                            echo "<td style='font-size: 16px; font-weight: bold;'>Balance</td>";
                                                            echo "<td colspan='2' align='center' style='font-size: 16px; font-weight: bold;'>" . number_format(($rcvdTotal - $paidTotal), 2, ".", ",") . "</td>";
                                                            echo "</tr>";
                                                        ?>
                                                    </table>
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
                    <!-- Project details -->
                    <?
                } else {
                    echo "<span style='color: #ff0000;'>Please select an investor.</span>";
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