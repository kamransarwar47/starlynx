<?
    define("MODULE_ID", "RPT010");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
                    $print = true;
                    system_log(PRNT, "Loaded for printing.", $userInfo["id"]);
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
                    Petty Expense
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?=date("d-m-Y", time())?></span>
                </p>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <?
                        if(!empty($project_id) && !empty($dtFrom) && !empty($dtTo)) {
                    ?>
                            <tr>
                                <td colspan="5">
                                    <p style="float: left; width: auto; height: auto;">
                                        <strong style="font-size: 14px;"><?=getProjectName($project_id)?></strong><br />
                                            From <?=date("d-m-Y", strtotime($dtFrom))?> To <?=date("d-m-Y", strtotime($dtTo))?>
                                    </p>
                                </td>
                            </tr>
                    <?
                        }
                    ?>
                    <tr><td>
                        <table border="0" width="100%" cellpadding="2" cellspacing="0" align="center" id="reclist" style="margin-top: 10px; font-size: 9px !important;">
                            <tr id="listhead">
                                <td width="15%">Date</td>
                                <td width="40%">Particulars</td>
                                <td width="25%">&nbsp;</td>
                                <td width="20%" align='right'>Amount</td>
                            </tr>
                            <?
                                if(!empty($project_id) && !empty($dtFrom) && !empty($dtTo)) {
                                    $sql = "SELECT
                                                t.voucher_id, a.title, d.amount, d.notes, t.invoice_date
                                            FROM
                                                transactions t, transactions_details d, accounts a
                                            WHERE
                                                a.master_id = 11
                                                AND t.project_id = '$project_id'
                                                AND t.transaction_type =  'PAYMENT'
                                                AND a.id = d.account_id
                                                AND d.transaction_id = t.id
                                                AND t.invoice_date BETWEEN  '$dtFrom' AND '$dtTo'
                                            ORDER BY t.invoice_date ASC";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    $total = 0;

                                    if($numrows > 0) {
                                        while($rs = mysql_fetch_array($result)) {
                                            $voucher_id = $rs["voucher_id"];
                                            $title = $rs["title"];
                                            $amount = $rs["amount"];
                                            $notes = $rs["notes"];
                                            $created = date("d-m-Y", strtotime($rs["invoice_date"]));
                                            $total += $amount;

                                            echo "<tr>";
                                            echo "<td valign='top'>$created</td>";
                                            echo "<td valign='top'>";
                                            echo $title;

                                            if(!empty($notes)) {
                                                echo " ($notes)";
                                            }

                                            echo "</td>";
                                            echo "<td valign='top'>$voucher_id</td>";
                                            echo "<td valign='top' align='right'>".formatCurrency($amount)."</td>";
                                            echo "</tr>";
                                        }

                                        echo "<tr>";
                                        echo "<td valign='top'>&nbsp;</td>";
                                        echo "<td valign='top'>&nbsp;</td>";
                                        echo "<td valign='top' style='font-size: 14px; font-weight: bold;'>Total</td>";
                                        echo "<td valign='top' align='right' style='font-size: 14px; font-weight: bold;'>".formatCurrency($total)."</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr><td colspan='4'>Nothing found.</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'><span style='color: #ff0000;'>Please select a project and date range.</span></td></tr>";
                                }
                            ?>
                        </table>
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