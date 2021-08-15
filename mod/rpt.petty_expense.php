<?
    define("MODULE_ID", "RPT010");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
$(document).ready( function (){
    $( "input:button").button();

    $("#btnPrint").click(function(){
        window.open("print.php?ss=<?=$ss?>&printmod=rpt.petty_expense&project_id=<?=$project_id?>&dtFrom=<?=$dtFrom?>&dtTo=<?=$dtTo?>", 'prn');
    });

    $("#dtFrom, #dtTo").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "<?getMinYear()?>:c+5"
    });
});

function reloadList() {
    location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&project_id='+$('#project_id').val();
}
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
                    Petty Expense

                    <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <input type="button" value="Print" id="btnPrint" style="float: right;" />
                    <? } ?>
                </p>

                <form action="index.php" method="GET">
                <input type="hidden" name="ss" id="ss" value="<?=$ss?>" />
                <input type="hidden" name="mod" id="mod" value="<?=$mod?>" />

                <p style="float: left; width: auto; height: auto;">
                    <strong>Project</strong><br />
                    <select name="project_id" id="project_id"> <!--  onChange="javascript: reloadList();" -->
                    <option value="">-- All --</option>
                    <?
                        $sql = "select id, title from projects order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());

                        while($rs = mysql_fetch_array($result)) {
                            $selected = "";

                            if($project_id == $rs["id"]) {
                                $selected = "selected='selected'";
                            }

                            echo "<option value=\"".$rs["id"]."\" $selected>".$rs["title"]."</option>";
                        }
                    ?>
                    </select>
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
                    <strong>&nbsp;</strong><br />
                    <input type="submit" value="Show" id="btnShow" />
                </p>

                </form>

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
                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
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
                                                echo " <span class='notes'>($notes)</span>";
                                            }

                                            echo "</td>";
                                            echo "<td valign='top'><a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a></td>";
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
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>