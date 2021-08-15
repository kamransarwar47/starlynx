<?
    define("MODULE_ID", "PRJ005");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if(checkPermission($userInfo["id"], MODIFY)) {
        if($cmd == "UPDATE") {
            if(!empty($project_id) && !empty($plot_id) && !empty($customer_id)) {
                // Update plot record
                /* Kamran Update Start */
                $dealer_id = ($dealer_id !== '') ? $dealer_id : 0;
                /* Kamran Update End */
                $sql = "update
                            plots
                        set
                            customer_id=\"".mysql_real_escape_string($customer_id)."\",
                            dealer_id=\"".mysql_real_escape_string($dealer_id)."\",
                            status='SOLD',
                            updated=NOW(),
                            updated_by=\"".$userInfo["id"]."\"
                        where
                            project_id=\"".mysql_real_escape_string($project_id)."\"
                            and id=\"".mysql_real_escape_string($plot_id)."\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());

                // Create plot dues
                $duesOk = true;

                /*for($i=0; $i<sizeof($dues_type); $i++) {
                    if(empty($dues_type[$i]) || empty($amount[$i]) || empty($due_on[$i]) || empty($status[$i])) {
                        $duesOk = false;
                    }
                }*/

                if($duesOk == true) {
                    $sql = "delete from plots_dues where plot_id=\"".mysql_real_escape_string($plot_id)."\"";
                    $result = mysql_query($sql, $conn) or die(mysql_error());

                    for($i=0; $i<sizeof($dues_type); $i++) {
                        if(!empty($dues_type[$i]) && !empty($amount[$i]) & !empty($due_on[$i]) && !empty($status[$i])) {
                            $sql = "insert into
                                        plots_dues (
                                            plot_id, dues_type, amount, due_on, notes, status, created, created_by
                                        ) values (
                                            \"".mysql_real_escape_string($plot_id)."\",
                                            \"".mysql_real_escape_string($dues_type[$i])."\",
                                            \"".mysql_real_escape_string($amount[$i])."\",
                                            \"".mysql_real_escape_string($due_on[$i])."\",
                                            \"".mysql_real_escape_string($notes[$i])."\",
                                            \"".mysql_real_escape_string($status[$i])."\",
                                            NOW(),
                                            \"".$userInfo["id"]."\"
                                        )";
                            $result = mysql_query($sql, $conn) or die(mysql_error());
                        }
                    }

                    setMessage("Record updated.", true);
                    system_log(MODIFY, "Plot record updated.", $userInfo["id"]);
                    unset($dues_type, $amount, $due_on, $notes, $status);
                    $cmd = "EDIT";
                } else {
                    setMessage("Incomplete information was provided for plot dues.");
                    system_log(MODIFY, "Operation failed. [Reason: Incomplete information was provided for plot dues.]", $userInfo["id"]);
                }
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }

        if($cmd == "EDIT") {
            $cmd = "";

            if(!empty($project_id) && !empty($plot_id)) {
                $sql = "select * from plots where id=\"".mysql_real_escape_string($plot_id)."\" and project_id=\"".mysql_real_escape_string($project_id)."\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if($numrows > 0) {
                    while($rs = mysql_fetch_array($result)) {
                        $plot_id = $rs["id"];
                        $project_id = $rs["project_id"];
                        $customer_id = $rs["customer_id"];
                        $dealer_id = $rs["dealer_id"];
                    }

                    $sql = "select * from plots_dues where plot_id=\"".mysql_real_escape_string($plot_id)."\" order by due_on asc";
                    $result = mysql_query($sql, $conn) or die(mysql_error());
                    $numrows = mysql_num_rows($result);
                    $total = "0.00";

                    if($numrows > 0) {
                        while($rs = mysql_fetch_array($result)) {
                            $dues_type[] = $rs["dues_type"];
                            $amount[] = $rs["amount"];
                            $due_on[] = $rs["due_on"];
                            $notes[] = $rs["notes"];
                            $status[] = $rs["status"];
                            $total += $rs["amount"];
                        }
                    }

                    $cmd = "UPDATE";
                    system_log(MODIFY, "$id loaded for editing.", $userInfo["id"]);
                } else {
                    setMessage("Record not found.");
                    system_log(MODIFY, "Operation failed. [Reason: Record not found.]", $userInfo["id"]);
                }
            } else {
                setMessage("Nothing to load...");
                system_log(MODIFY, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }
?>
<script type="text/javascript">
var siteUrl = "<?=$_siteRoot?>";
var row = 1;

$(document).ready( function (){
    $(".due_on").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "2000:c+5"
    });

    $("#btnAddMore").button({
        text: false,
        icons: {
            primary: "ui-icon-circle-plus"
        }
    });

    $("#btnAddMore").click(function(){
        addNewRow();
        $("#due_on_" + row).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "2000:c+5"
        });
    });
});

function calculateTotal() {
    var x = 0;

    $("input[name='amount[]']").each(function(index){
        x = x + parseInt($(this).val());
    });

    $("#totals").html(x);
}

function addNewRow()
{
    row++;
    var rowData = "<tr>" +
                   "<td>" +
                   "        <select name='dues_type[]' id='dues_type'>" +
                   "         <option value='INSTALMENT'>INSTALMENT</option>" +
                   "         <option value='ADVANCE'>ADVANCE</option>" +
                   "         <option value='OTHER'>OTHER</option>" +
                   "     </select>" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='amount[]' id='amount' maxlength='12' value='' onChange='javascript: calculateTotal();' />" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='due_on[]' id='due_on" + "_" + row + "' maxlength='10' value='' />" +
                   "</td>" +
                   "<td>" +
                   "        <input type='text' size='10' name='notes[]' id='notes' maxlength='255' value='' />" +
                   "</td>" +
                   "<td>" +
                   "        <select name='status[]' id='status'>" +
                   "         <option value='DUE'>DUE</option>" +
                   "         <option value='CLEARED'>CLEARED</option>" +
                   "     </select>" +
                   "</td>" +
                   "</tr>";

    $("#rowLast").before(rowData);
}
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Accounts
            <!-- Kamran Update Start-->
            <a style="float: right;" href="index.php?ss=<?PHP print $ss; ?>&amp;mod=dealers.add" class="ui-button ui-widget ui-state-default ui-corner-all btnLink" role="button" target="_blank">Add New Dealer</a>
            <a style="float: right; margin-right: 5px;" href="index.php?ss=<?PHP print $ss; ?>&amp;mod=customers.add" class="ui-button ui-widget ui-state-default ui-corner-all btnLink" role="button" target="_blank">Add New Customer</a>
            <!-- Kamran Update End-->

        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?=showError()?>
                <form action="index.php" method="post" autocomplete="off">
                <input type="hidden" name="ss" value="<?=$ss?>" />
                <input type="hidden" name="mod" value="<?=$mod?>" />
                <input type="hidden" name="cmd" value="<?=$cmd?>" />
                <p class="box_title">Plot Booking</p>
                <? if(checkPermission($userInfo["id"], MODIFY)) { ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td valign="top">
                            <p>
                                <strong>Project</strong><br />
                                <select name="project_id" id="project_id" onChange="javascript: location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&project_id='+this.value;" style="width: 100%;">
                                <option value="">--Select--</option>
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
                        </td>
                        <td valign="top">
                            <p>
                                <strong>Plot Number</strong><br />
                                <select name="plot_id" id="plot_id" onChange="javascript: location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&cmd=EDIT&project_id=<?=$project_id?>&plot_id='+this.value;">
                                <option value="">--Select--</option>
                                <?
                                    $sql = "select id, plot_number, plot_type from plots where project_id=\"".mysql_real_escape_string($project_id)."\" order by plot_type asc, id asc";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $plots = array();

                                    while($rs = mysql_fetch_array($result)) {
                                        $plots[$rs["plot_type"]]["id"][] = $rs["id"];
                                        $plots[$rs["plot_type"]]["number"][] = $rs["plot_number"];
                                    }

                                    //print_r($plots);

                                    foreach($plots as $type => $attrs) {
                                        echo "<optgroup label='$type'>";

                                        for($i=0; $i<sizeof($attrs["id"]); $i++) {
                                            $selected = "";

                                               if($plot_id == $attrs["id"][$i]) {
                                                   $selected = "selected='selected'";
                                               }

                                            echo "<option value=\"".$attrs["id"][$i]."\" $selected>".$attrs["number"][$i]."</option>";
                                        }

                                        echo "</optgroup>";
                                    }
                                ?>
                                </select>
                            </p>
                        </td>
                        <td valign="top">
                            <p>
                                <strong>Customer</strong><br />
                                <select name="customer_id" id="customer_id" class="select2">
                                <option value="">--Select--</option>
                                <?
                                    $sql = "select id, full_name, father_name from customers order by full_name";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());

                                    while($rs = mysql_fetch_array($result)) {
                                        $selected = "";

                                        if($customer_id == $rs["id"]) {
                                            $selected = "selected='selected'";
                                        }

                                        echo "<option value=\"".$rs["id"]."\" $selected>".$rs["full_name"]." s/o ".$rs["father_name"]."</option>";
                                    }
                                ?>
                                </select>
                            </p>
                        </td>
                        <td valign="top">
                            <p>
                                <strong>Dealer</strong><br />
                                <select name="dealer_id" id="dealer_id" class="select2">
                                <option value="">--Select--</option>
                                <?
                                    $sql = "select id, full_name, father_name from dealers order by full_name";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());

                                    while($rs = mysql_fetch_array($result)) {
                                        $selected = "";

                                        if($dealer_id == $rs["id"]) {
                                            $selected = "selected='selected'";
                                        }

                                        echo "<option value=\"".$rs["id"]."\" $selected>".$rs["full_name"]." s/o ".$rs["father_name"]."</option>";
                                    }
                                ?>
                                </select>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                                <tr id="listhead">
                                    <td>Type</td>
                                    <td>Amount</td>
                                    <td>Due On</td>
                                    <td>Notes</td>
                                    <td>Status</td>
                                </tr>
                                <?
                                    if(is_array($dues_type)) {
                                        for($x=0; $x<sizeof($dues_type); $x++) {
                                ?>
                                <tr>
                                    <td>
                                        <select name="dues_type[]" id="dues_type">
                                        <?
                                            $dtypes = array('INSTALMENT', 'ADVANCE', 'OTHER');

                                            for($i=0; $i<sizeof($dtypes); $i++) {
                                                $selected = "";

                                                if($dues_type[$x] == $dtypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"".$dtypes[$i]."\" $selected>".$dtypes[$i]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="amount[]" id="amount" maxlength="12" value="<?=$amount[$x]?>" onChange='javascript: calculateTotal();' />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="due_on[]" class="due_on" maxlength="10" value="<?=$due_on[$x]?>" />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="notes[]" id="notes" maxlength="255" value="<?=$notes[$x]?>" />
                                    </td>
                                    <td>
                                        <select name="status[]" id="status">
                                        <?
                                            $stypes = array('DUE', 'CLEARED');

                                            for($i=0; $i<sizeof($stypes); $i++) {
                                                $selected = "";

                                                if($status[$x] == $stypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"".$stypes[$i]."\" $selected>".$stypes[$i]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <?
                                        }
                                    } else {
                                ?>
                                <tr>
                                    <td>
                                        <select name="dues_type[]" id="dues_type">
                                        <?
                                            $dtypes = array('INSTALMENT', 'ADVANCE', 'OTHER');

                                            for($i=0; $i<sizeof($dtypes); $i++) {
                                                $selected = "";

                                                if($dues_type == $dtypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"".$dtypes[$i]."\" $selected>".$dtypes[$i]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="amount[]" id="amount" maxlength="12" value="" onChange='javascript: calculateTotal();' />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="due_on[]" class="due_on" maxlength="10" value="" />
                                    </td>
                                    <td>
                                        <input type="text" size="10" name="notes[]" id="notes" maxlength="255" value="" />
                                    </td>
                                    <td>
                                        <select name="status[]" id="status">
                                        <?
                                            $stypes = array('DUE', 'CLEARED');

                                            for($i=0; $i<sizeof($stypes); $i++) {
                                                $selected = "";

                                                if($status == $stypes[$i]) {
                                                    $selected = "selected='selected'";
                                                }

                                                echo "<option value=\"".$stypes[$i]."\" $selected>".$stypes[$i]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <?
                                    }
                                ?>
                                <tr id="rowLast">
                                    <td style="font-size: 18px;"><strong>Total</strong></td>
                                    <td style="font-size: 24px; font-weight: bold;" id="totals"><?=number_format($total, 2, ".", ",")?></td>
                                    <td style="font-size: 18px;" align="right">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><a id="btnAddMore">+ Add more</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <p>
                    <input type="submit" value="Submit" />
                </p>
                <? } ?>
                </form>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>