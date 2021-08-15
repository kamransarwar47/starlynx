<?
    define("MODULE_ID", "RPT002");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
$(document).ready( function (){
    $( "input:button").button();

    $("#btnPrint").click(function(){
        window.open("print.php?ss=<?=$ss?>&printmod=rpt.payments_auth&dtFrom=" + $("#dtFrom").val() + "&dtTo=" + $("#dtTo").val(), 'prn');
    });

    $("#btnShow").click(function(){
        location.href = "index.php?ss=<?=$ss?>&mod=<?=$mod?>&dtFrom=" + $("#dtFrom").val() + "&dtTo=" + $("#dtTo").val();
    });

    $("#dtFrom, #dtTo").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "<?getMinYear()?>:<?=getMaxYear()?>"
    });
});
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/reports.png" width="17" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Reports</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <? if(checkPermission($userInfo["id"], VIEW)) { ?>
                <?=showError()?>
                <p class="box_title">
                    Payments Authorization

                    <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <? if(!empty($dtFrom) && !empty($dtTo)) { ?>
                            <input type="button" value="Print" id="btnPrint" style="float: right;" />
                        <? } ?>
                    <? } ?>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>From</strong><br />
                    <input type="text" name="dtFrom" id="dtFrom" value="<?=$dtFrom?>" autocomplete="off" />
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>To</strong><br />
                    <input type="text" name="dtTo" id="dtTo" value="<?=$dtTo?>" autocomplete="off"/>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>&nbsp;</strong><br />
                    <input type="button" value="Show" id="btnShow" />
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
                                    AND t.transaction_type='PAYMENT'
                                    AND t.auth_status='AUTH'
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
                        echo "<td width='15%'>Invoice #</td>";
                        echo "<td width='40%'>Paid To</td>";
                        echo "<td width='10%' align='right'>Amount</td>";
                        echo "<td width='20%' align='center'>Authorized By</td>";
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
                                $handover_by = $rs["handover_by"];
                                $handover_on = $rs["handover_on"];
                                $amt = $rs["amt"];



                                echo "<tr>";
                                echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                echo "<td><a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a></td>";
                                echo "<td>".getAccountTitle($account_id)."<br /><span class='notes'>$notes</span></td>";
                                echo "<td align='right'>".number_format($amt, 2, ".", ",")."</td>";
                                echo "<td align='center'>";
                                echo getUserFullName($auth_by)."<br />";
                                echo "<span class='notes'>".date("d-m-Y h:ia", strtotime($auth_on))."</span>";
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
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>