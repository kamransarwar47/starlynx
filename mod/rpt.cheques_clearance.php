<?
    define("MODULE_ID", "RPT004");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
$(document).ready( function (){
    $( "input:button").button();

    $("#btnPrint").click(function(){
        window.open("print.php?ss=<?=$ss?>&printmod=rpt.cheques_clearance&dtFrom=" + $("#dtFrom").val() + "&dtTo=" + $("#dtTo").val() + "&clearance_status=" + $("#clearance_status").val(), 'prn');
    });

    $("#btnShow").click(function(){
        location.href = "index.php?ss=<?=$ss?>&mod=<?=$mod?>&dtFrom=" + $("#dtFrom").val() + "&dtTo=" + $("#dtTo").val() + "&clearance_status=" + $("#clearance_status").val();
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
                    Cheques / Cash Clearance

                    <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <? if(!empty($dtFrom) && !empty($dtTo)) { ?>
                            <input type="button" value="Print" id="btnPrint" style="float: right;" />
                        <? } ?>
                    <? } ?>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>From</strong><br />
                    <input type="text" name="dtFrom" id="dtFrom" value="<?=$dtFrom?>" autocomplete="off"/>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>To</strong><br />
                    <input type="text" name="dtTo" id="dtTo" value="<?=$dtTo?>" autocomplete="off"/>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>Clearance Status</strong><br />
                    <select name="clearance_status" id="clearance_status">
                        <option value="">All</option>
                        <?
                            $cstatus = array('CLEARED', 'PENDING', 'BOUNCED');

                            for($i=0; $i<sizeof($cstatus); $i++) {
                                $selected = "";

                                if($clearance_status == $cstatus[$i]) {
                                    $selected = "selected='selected'";
                                }

                                echo "<option value=\"".$cstatus[$i]."\" $selected>".$cstatus[$i]."</option>";
                            }
                        ?>
                    </select>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>&nbsp;</strong><br />
                    <input type="button" value="Show" id="btnShow" />
                </p>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                <?
                    if(!empty($dtFrom) && !empty($dtTo)) {
                        $wxtra = "";

                        if(!empty($clearance_status)) {
                            $wxtra .= " AND d.clearance_status='$clearance_status'";
                        }

                        $sql = "SELECT
                                    t.id, t.project_id, t.account_id, t.voucher_id, t.notes, t.invoice_date,
                                    d.id AS d_id, d.account_id as d_account, sum(d.amount) as amount, d.notes, d.bank_id, d.cheque_number, d.cheque_date, d.cheque_name,
                                    d.post_date, d.clearance_status, d.cleared_on, d.cleared_by
                                FROM
                                    transactions t, transactions_details d
                                WHERE
                                    t.id = d.transaction_id
                                    AND t.transaction_type =  'RECEIPT'
                                    AND t.handover_status =  'HANDOVER'
                                    AND t.invoice_date BETWEEN '$dtFrom' AND '$dtTo'
                                    $wxtra
                                GROUP BY
                                    t.voucher_id, d.cheque_number
                                HAVING 
                                    d.cheque_number <> ''
                                ORDER BY
                                    t.invoice_date ASC
                                ";
                        //echo $sql;
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        echo "<table border='0' width='100%' cellpadding='5' cellspacing='0' align='center' id='reclist'>";
                        echo "<tr id='listhead'>";
                        echo "<td width='15%'>Inv. Date</td>";
                        echo "<td width='25%'>Cheque # / Cash Serial</td>";
                        echo "<td width='10%'>Chq. Date</td>";
                        echo "<td width='15%'>Name on Cheque</td>";
                        echo "<td width='10%'>Bank</td>";
                        echo "<td width='10%' align='right'>Amount</td>";
                        echo "<td width='15%' align='center'>Clearance</td>";
                        echo "</tr>";

                        if($numrows > 0) {
                            while($rs = mysql_fetch_array($result)) {
                                $id = $rs["id"];
                                //$project_id = $rs["project_id"];
                                $account_id = $rs["account_id"];
                                $voucher_id = $rs["voucher_id"];
                                //$transaction_type = $rs["transaction_type"];
                                //$notes = $rs["notes"];
                                $invoice_date = $rs["invoice_date"];
                                $d_id = $rs["d_id"];
                                $amount = $rs["amount"];
                                $bank_id = $rs["bank_id"];
                                $cheque_number = $rs["cheque_number"];
                                $cheque_name = $rs["cheque_name"];
                                $cheque_date = $rs["cheque_date"];
                                $post_date = $rs["post_date"];
                                $clearance_status = $rs["clearance_status"];
                                $cleared_on = $rs["cleared_on"];
                                $cleared_by = $rs["cleared_by"];

                                echo "<tr>";
                                echo "<td>".date("d-m-Y", strtotime($invoice_date))."</td>";
                                echo "<td>";
                                echo "$cheque_number";
                                echo "<br /><span class='notes'>";
                                echo "Voucher #: <a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a><br />";
                                echo getAccountTitle($account_id);
                                echo "</span>";
                                echo "</td>";
                                echo "<td>";
                                echo ($cheque_date == "0000-00-00" ? "" : date("d-m-Y", strtotime($cheque_date)));

                                if($post_date == "Y") {
                                    echo "<br /><span class='notes' style='color: #ff0000;'>Post Date</span>";
                                }

                                echo "</td>";
                                echo "<td>$cheque_name</td>";
                                echo "<td>".getBankShortName($bank_id)."</td>";
                                echo "<td align='right'>".number_format($amount, 2, ".", ",")."</td>";
                                echo "<td align='center'>";
                                echo $clearance_status;

                                if($clearance_status !== "PENDING") {
                                    echo "<br /><span class='notes'>";
                                    echo date("d-m-Y h:ia", strtotime($cleared_on));
                                    echo "<br />".getUserName($cleared_by);
                                    echo "</span>";
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