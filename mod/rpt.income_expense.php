<?
    define("MODULE_ID", "RPT008");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
$(document).ready( function (){
    $( "input:button").button();

    $(".btnPrint").click(function(){
        //alert($("input:radio[name=rptLevel]:checked").val());
        var incPending = "";
        var incPendingClear = "";
        var rptLevel = $("input:radio[name=rptLevel]:checked").val();
        var project_id = $("#project_id").val();
        var dtFrom = $("#dtFrom").val();
        var dtTo = $("#dtTo").val();
        var incCol = $("input:radio[name=incCol]:checked").val();
        var output = $(this).data('output');

        if($("input:checkbox[name=incPending]:checked").val() == "Y") {
            incPending = "Y";
        }

        if($("input:checkbox[name=incPendingClear]:checked").val() == "Y") {
            incPendingClear = "Y";
        }

        window.open("print.php?ss=<?=$ss?>&printmod=rpt.income_expense&project_id="+ project_id +"&dtFrom=" + dtFrom + "&dtTo=" + dtTo + "&rptLevel=" + rptLevel + "&incPending=" + incPending + "&incPendingClear=" + incPendingClear + "&incCol=" + incCol + "&output=" + output, 'prn');
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
                    Income / Expense Statement

                    <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <? if(!empty($dtFrom) && !empty($dtTo)) { ?>
                            <input type="button" value="Export" class="btnPrint" data-output="csv" style="float: right;" />
                            <input type="button" value="Print" class="btnPrint" data-output="print" style="float: right;" />
                        <? } ?>
                    <? } ?>
                </p>

                <form action="index.php" method="GET">
                <input type="hidden" name="ss" id="ss" value="<?=$ss?>" />
                <input type="hidden" name="mod" id="mod" value="<?=$mod?>" />

                <p style="float: left; width: auto; height: auto;">
                    <strong>Project</strong><br />
                    <select name="project_id" id="project_id">
                    <option value="">-- Select --</option>
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

                <div style="clear: both; width: 1px; height: 1px;"></div>

                <p style="float: left; width: auto; height: auto; margin-right: 20px;">
                    <strong>Report Level</strong><br />
                    <input type="radio" name="rptLevel" id="rptLevel_1" value="TOP" <?=($rptLevel=="TOP")?"checked='checked'":""?> /> Top &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="rptLevel" id="rptLevel_2" value="SUB" <?=($rptLevel=="SUB")?"checked='checked'":""?> /> Sub Accounts &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="rptLevel" id="rptLevel_3" value="FULL" <?=($rptLevel=="FULL")?"checked='checked'":""?> /> Full Details
                    <br /><br />
                    <input type="checkbox" name="incPending" id="incPending" value="Y" <?=($incPending=="Y")?"checked='checked'":""?> /> Include pending authorizations and handovers
                    <br />
                    <input type="checkbox" name="incPendingClear" id="incPendingClear" value="Y" <?=($incPendingClear=="Y")?"checked='checked'":""?> /> Include pending clearances
                    <!-- <br />
                    <input type="checkbox" name="incPostdate" id="incPostdate" value="Y" <?=($incPostdate=="Y")?"checked='checked'":""?> /> Include post dated cheques -->
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>&nbsp;</strong><br />
                    <input type="submit" value="Show" id="btnShow" /><br />
                    <input type="radio" name="incCol" id="incCol_I" value="I" <?=($incCol=="I")?"checked='checked'":""?> /> Income &nbsp;&nbsp;
                    <input type="radio" name="incCol" id="incCol_E" value="E" <?=($incCol=="E")?"checked='checked'":""?> /> Expense &nbsp;&nbsp;
                    <input type="radio" name="incCol" id="incCol_B" value="B" <?=($incCol=="B" || empty($incCol))?"checked='checked'":""?> /> Both
                </p>
                </form>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                <?
                    if(((!empty($dtFrom) && !empty($dtTo)) || (!empty($project_id) && !empty($dtFrom) && !empty($dtTo))) && !empty($rptLevel)) {
                        include($mod."_".$rptLevel.".php");
                    } else {
                        echo "<span style='color: #ff0000;'>Please select a (date range with project or date range) and report level.</span>";
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