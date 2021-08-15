<?
    define("MODULE_ID", "RPT003");

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
                    Receipts Handover Report
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?=date("d-m-Y", time())?></span>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>From</strong> <?=date("d-m-Y", strtotime($dtFrom))?>
                    <strong>To</strong> <?=date("d-m-Y", strtotime($dtTo))?>
                </p>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                <?
                    if(!empty($dtFrom) && !empty($dtTo)) {
                        $sql = "SELECT
                                    t . * , sum( d.amount ) AS amt
                                FROM
                                    transactions t, transactions_details d
                                WHERE
                                    t.id = d.transaction_id
                                    AND t.transaction_type='RECEIPT'
                                    AND t.handover_status='HANDOVER'
                                    AND t.invoice_date BETWEEN '$dtFrom' AND '$dtTo'
                                GROUP BY
                                    t.id
                                ORDER BY
                                    invoice_date asc";
                        //echo $sql;
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        echo "<table border='0' width='100%' cellpadding='5' cellspacing='0' align='center' id='reclist'>";
                        echo "<tr id='listhead'>";
                        echo "<td width='15%'>Date</td>";
                        echo "<td width='20%'>Invoice #</td>";
                        echo "<td width='35%'>Received From</td>";
                        echo "<td width='10%' align='right'>Amount</td>";
                        echo "<td width='20%' align='center'>Handed Over To</td>";
                        echo "</tr>";

                        if($numrows > 0) {
                            while($rs = mysql_fetch_array($result)) {
                                $id = $rs["id"];
                                //$project_id = $rs["project_id"];
                                $account_id = $rs["account_id"];
                                $voucher_id = $rs["voucher_id"];
                                $transaction_type = $rs["transaction_type"];
                                $notes = $rs["notes"];
                                $invoice_date = $rs["invoice_date"];
                                $auth_status = $rs["auth_status"];
                                $auth_by = $rs["auth_by"];
                                $auth_on = $rs["auth_on"];
                                $handover_status = $rs["handover_status"];
                                $handover_by = $rs["received_by"];
                                $handover_on = $rs["received_on"];
                                $amt = $rs["amt"];



                                echo "<tr>";
                                echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                echo "<td>$voucher_id</td>";
                                echo "<td>".getAccountTitle($account_id)."<br /><span class='notes'>$notes</span></td>";
                                echo "<td align='right'>".number_format($amt, 2, ".", ",")."</td>";
                                echo "<td align='center'>";
                                echo getUserFullName($handover_by)."<br />";
                                echo "<span class='notes'>".date("d-m-Y h:ia", strtotime($handover_on))."</span>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='6'>No record found.</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "<span style='color: #ff0000;'>Please select a date range.</span>";
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