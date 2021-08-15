<?
    define("MODULE_ID", "LDO002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        if (!empty($view_id)) {
            $sql = "SELECT lo.*, lo.account_id as account_id, lop.project_id as project_id FROM landowner lo JOIN landowner_projects lop ON lo.id = lop.landowner_id WHERE lop.id = '$view_id'";
            $result = mysql_query($sql, $conn) or die(mysql_error());
            $numrows = mysql_num_rows($result);

            if ($numrows > 0) {
                $print = true;
                system_log(PRNT, "$view_id loaded for printing.", $userInfo["id"]);
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>StarLynx - Star Developers</title>
    <link rel="stylesheet" href="css/print.css" media="screen, print"/>
</head>
<body>
<? include("common/header.invoice.php"); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                <?= showError() ?>
                <p class="box_title">
                    Land Owner Detail
                    <span class="notes"
                          style="float: right; text-align: right; font-style: italic;">Printed on <?= date("d-m-Y", time()) ?></span>
                </p>
                <br>
                <?php
                $project_id           = 0;
                $landowner_account_id = 0;

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $full_name            = $rs["full_name"];
                        $father_name          = $rs["father_name"];
                        $street               = $rs["street"];
                        $city                 = getCityName($rs["city"]);
                        $state                = $rs["state"];
                        $country              = getCountryName($rs["country"]);
                        $phone_1              = $rs["phone_1"];
                        $phone_2              = $rs["phone_2"];
                        $mobile_1             = $rs["mobile_1"];
                        $mobile_2             = $rs["mobile_2"];
                        $id_num               = $rs["id_num"];
                        $project_id           = $rs["project_id"];
                        $landowner_account_id = $rs["account_id"];

                        $phones = array($phone_1, $phone_2, $mobile_1, $mobile_2);
                    }
                    ?>
                    <p class="box_title">
                        <?= $full_name ?> son/wife of <?= $father_name ?>
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
                                        <td>
                                            <!-- Project Land owner Payment Start -->
                                            <?
                                                $paidTotal = 0;

                                                // Select transactions
                                                $sql = "select
                                                        t.account_id, t.voucher_id, t.transaction_type, t.invoice_date, d.notes,
                                                        d.amount, d.voucher_type, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name
                                                    from
                                                        transactions t, transactions_details d
                                                    where
                                                        t.id = d.transaction_id
                                                        and t.account_id = '$landowner_account_id'
                                                        and t.project_id = '$project_id'
                                                        and t.transaction_type = 'PAYMENT'
                                                    order by
                                                        t.invoice_date asc
                                                    ";
                                                $result = mysql_query($sql, $conn) or die(mysql_error());
                                                $numrows = mysql_num_rows($result);

                                                if ($numrows > 0) {
                                                    ?>
                                                    <table border="0" width="100%" cellpadding="5" cellspacing="0"
                                                           align="center" id="reclist">
                                                        <tr id="listhead">
                                                            <td width="15%"><strong>Date</strong></td>
                                                            <td width="75%"><strong>Particulars</strong></td>
                                                            <td width="10%" align="right"><strong>PAID</strong></td>
                                                        </tr>
                                                        <?php
                                                            while ($rs = mysql_fetch_array($result)) {
                                                                $accountTitle  = getAccountTitle($rs["account_id"]);
                                                                $voucher_id    = $rs["voucher_id"];
                                                                $invoice_date  = $rs["invoice_date"];
                                                                $amount        = $rs["amount"];
                                                                $voucher_type  = $rs["voucher_type"];
                                                                $bank_id       = $rs["bank_id"];
                                                                $cheque_number = $rs["cheque_number"];
                                                                $cheque_date   = $rs["cheque_date"];
                                                                $cheque_name   = $rs["cheque_name"];
                                                                $notes         = $rs["notes"];

                                                                echo "<tr>";
                                                                echo "<td>" . date("d-m-Y", strtotime($invoice_date)) . "</td>";
                                                                echo "<td>";
                                                                echo "Paid To" . ": $accountTitle " . (($notes != "") ? "(" . $notes . ")" : "");
                                                                echo "<br /><span style='float: right; font-size: 10px;'>Voucher #: $voucher_id</span>";

                                                                if ($voucher_type == "BANK") {
                                                                    echo "<br /><span style='float: right; font-size: 10px;'>(" . getBankShortName($bank_id) . " / $cheque_number / " . ($cheque_date == "0000-00-00" ? "" : date("d-m-Y", strtotime($cheque_date))) . " / $cheque_name)</span>";
                                                                }

                                                                echo "</td>";

                                                                $paidTotal += $amount;

                                                                echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";

                                                                echo "</tr>";
                                                            }

                                                            echo "<tr>";
                                                            echo "<td>&nbsp;</td>";
                                                            echo "<td style='font-size: 16px; font-weight: bold;'>Total</td>";
                                                            echo "<td align='right' style='font-size: 16px; font-weight: bold;'>" . number_format($paidTotal, 2, ".", ",") . "</td>";
                                                            echo "</tr>";
                                                        ?>
                                                    </table>
                                                    <?php
                                                } else {
                                                    echo "<span style='color: #ff0000;'>No record exists.</span>";
                                                }
                                            ?>
                                            <!-- Plot Commission End -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- Project details -->
                    <?php
                } else {
                    echo "<span style='color: #ff0000;'>Please select a land owner project.</span>";
                }
            }
            ?>
        </td>
    </tr>
</table>
<?
    if ($print) {
        ?>
        <script type="text/javascript">
            window.print();
        </script>
        <?
    }
?>
</body>
</html>