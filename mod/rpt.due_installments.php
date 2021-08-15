<?
    define("MODULE_ID", "RPT009");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $(".btnPrint").click(function () {
            var project_id = $("#project_id").val();
            var plot_id = $("#plot_id").val();
            var dtFrom = $("#dtFrom").val();
            var dtTo = $("#dtTo").val();
            var output = $(this).data('output');

            window.open("print.php?ss=<?=$ss?>&printmod=rpt.due_installments&project_id=" + project_id + "&plot_id=" + plot_id + "&dtFrom=" + dtFrom + "&dtTo=" + dtTo + "&output=" + output, 'prn');
        });

        $("#dtFrom, #dtTo").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "<?getMinYear()?>:c+5"
        });
    });

    function reloadList() {
        location.href = 'index.php?ss=<?=$ss?>&mod=<?=$mod?>&project_id=' + $('#project_id').val() + '&dtFrom=' + $('#dtFrom').val() + '&dtTo=' + $('#dtTo').val();
    }
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/reports.png" width="17" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Reports
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <?= showError() ?>
                <p class="box_title">
                    Due Installments

                    <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <input type="button" value="Export" class="btnPrint" data-output="csv" style="float: right;"/>
                        <input type="button" value="Print" class="btnPrint" data-output="print" style="float: right;"/>
                    <? } ?>
                </p>
                <br>
                <br>
                <form action="index.php" method="GET">
                    <input type="hidden" name="ss" id="ss" value="<?= $ss ?>"/>
                    <input type="hidden" name="mod" id="mod" value="<?= $mod ?>"/>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>Project</strong><br/>
                        <select name="project_id" id="project_id" onChange="javascript: reloadList();">
                            <option value="">-- All --</option>
                            <?
                                $sql = "select id, title from projects order by title";
                                $result = mysql_query($sql, $conn) or die(mysql_error());

                                while ($rs = mysql_fetch_array($result)) {
                                    $selected = "";

                                    if ($project_id == $rs["id"]) {
                                        $selected = "selected='selected'";
                                    }

                                    echo "<option value=\"" . $rs["id"] . "\" $selected>" . $rs["title"] . "</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p style="float: left; width: auto; height: auto;">
                        <strong>Plot Number</strong><br/>
                        <select name="plot_id" id="plot_id">
                            <option value="">--Select--</option>
                            <?
                                if (!empty($project_id)) {
                                    $sql = "select id, plot_number, plot_type from plots where project_id=\"" . mysql_real_escape_string($project_id) . "\" order by plot_type asc, id asc";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $plots = array();

                                    while ($rs = mysql_fetch_array($result)) {
                                        $plots[$rs["plot_type"]]["id"][]     = $rs["id"];
                                        $plots[$rs["plot_type"]]["number"][] = $rs["plot_number"];
                                    }

                                    foreach ($plots as $type => $attrs) {
                                        echo "<optgroup label='$type'>";

                                        for ($i = 0; $i < sizeof($attrs["id"]); $i++) {
                                            $selected = "";

                                            if ($plot_id == $attrs["id"][$i]) {
                                                $selected = "selected='selected'";
                                            }

                                            echo "<option value=\"" . $attrs["id"][$i] . "\" $selected>" . $attrs["number"][$i] . "</option>";
                                        }

                                        echo "</optgroup>";
                                    }
                                }
                            ?>
                        </select>
                    </p>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>From</strong><br/>
                        <input type="text" name="dtFrom" id="dtFrom" value="<?= $dtFrom ?>" autocomplete="off"/>
                    </p>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>To</strong><br/>
                        <input type="text" name="dtTo" id="dtTo" value="<?= $dtTo ?>" autocomplete="off"/>
                    </p>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>&nbsp;</strong><br/>
                        <input type="submit" value="Show" id="btnShow"/>
                    </p>

                </form>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td colspan="5">
                            <p style="float: left; width: auto; height: auto;">
                                <?php
                                    if (!empty($project_id)) {
                                        ?>
                                        <strong style="font-size: 14px;"><?= getProjectName($project_id) ?></strong><br/>
                                        <?php
                                    }
                                ?>
                                <? if (empty($dtFrom) || empty($dtTo)) { ?>
                                    From Beginning To <?= date("F Y", time()) ?>
                                <? } else { ?>
                                    From <?= date("d-m-Y", strtotime($dtFrom)) ?> To <?= date("d-m-Y", strtotime($dtTo)) ?>
                                <? } ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                                   style="margin-top: 10px;">
                                <tr id="listhead">
                                    <td width="15%">Due On</td>
                                    <td width="10%">Project</td>
                                    <td width="10%">Plot #</td>
                                    <td width="15%">Amount</td>
                                    <td width="25%">Customer</td>
                                    <td width="25%">Dealer</td>
                                </tr>
                                <?
                                    if ((!empty($project_id) && !empty($dtFrom) && !empty($dtTo)) || (empty($project_id) && (!empty($dtFrom) && !empty($dtTo)))) {
                                        $dxtra = "d.due_on BETWEEN '$dtFrom' AND '$dtTo'";

                                        if (!empty($project_id)) {
                                            $projectExtra = "AND p.id = '$project_id'";
                                        } else {
                                            $projectExtra = "";
                                        }
                                        if (!empty($plot_id)) {
                                            $plotExtra = "AND d.plot_id = '$plot_id'";
                                        } else {
                                            $plotExtra = "";
                                        }

                                        $sql = "SELECT
                                                pl.plot_number, pl.plot_type,
                                                d.amount, d.due_on, d.status,
                                                c.full_name AS customer, c.father_name as c_father, c.mobile_1 AS c_mob1, c.mobile_2 AS c_mob2,
                                                dl.full_name AS dealer, dl.mobile_1 AS d_mob1, dl.mobile_2 AS d_mob2, p.title AS project_title
                                            FROM
                                                plots pl  
                                            JOIN
                                            plots_dues d ON pl.id = d.plot_id
                                            JOIN 
                                            projects p ON pl.project_id = p.id 
                                            LEFT JOIN
                                            customers c ON pl.customer_id = c.id
                                            LEFT JOIN
                                            dealers dl ON pl.dealer_id = dl.id
                                            WHERE
                                                $dxtra
                                                $plotExtra
                                                AND d.status =  'DUE'
                                                $projectExtra
                                            ORDER BY
                                                d.due_on ASC, p.title ASC , pl.plot_type ASC , pl.id ASC";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows      = mysql_num_rows($result);
                                        $ctime        = time();
                                        $total_amount = 0;

                                        if ($numrows > 0) {
                                            while ($rs = mysql_fetch_array($result)) {
                                                //$customer_id = $rs["customer_id"];
                                                $plot_number     = $rs["plot_number"];
                                                $plot_type       = $rs["plot_type"];
                                                $amount          = $rs["amount"];
                                                $due_on          = date("d-m-Y", strtotime($rs["due_on"]));
                                                $status          = $rs["status"];
                                                $customer        = $rs["customer"];
                                                $c_father        = $rs["c_father"];
                                                $c_mob1          = $rs["c_mob1"];
                                                $c_mob2          = $rs["c_mob2"];
                                                $dealer          = $rs["dealer"];
                                                $d_mob1          = $rs["d_mob1"];
                                                $d_mob2          = $rs["d_mob2"];
                                                $d_project_title = $rs["project_title"];
                                                $total_amount    += $amount;

                                                $style  = "";
                                                $ditime = strtotime($rs["due_on"]);

                                                if ($ditime <= $ctime) {
                                                    $style = "background-color: #ffeeee;";
                                                }

                                                echo "<tr style='$style'>";
                                                echo "<td valign='top'>$due_on</td>";
                                                echo "<td valign='top'>$d_project_title</td>";
                                                echo "<td valign='top'>";

                                                echo($plot_type == "Residential" ? "Plot" : $plot_type);
                                                echo " $plot_number";

                                                echo "</td>";
                                                echo "<td valign='top'>" . formatCurrency($amount) . "</td>";
                                                echo "<td valign='top'>";
                                                echo "<strong>$customer</strong><br /><span class='notes'>s/w/o $c_father</span>";

                                                if (!empty($c_mob1)) {
                                                    echo "<br />$c_mob1";
                                                }
                                                if (!empty($c_mob2)) {
                                                    echo "<br />$c_mob2";
                                                }

                                                echo "</td>";
                                                echo "<td valign='top'>";
                                                echo "<strong>$dealer</strong>";

                                                if (!empty($d_mob1)) {
                                                    echo "<br />$d_mob1";
                                                }
                                                if (!empty($d_mob2)) {
                                                    echo "<br />$d_mob2";
                                                }

                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            echo "<tr style='font-size: 14px;'>
                                                    <td colspan='2'>&nbsp;</td>
                                                    <td><strong>Total</strong></td>
                                                    <td><strong>" . formatCurrency($total_amount) . "</strong></td>
                                                    <td colspan='2'>&nbsp;</td>
                                                  </tr>";
                                        } else {
                                            echo "<tr><td colspan='6'>Nothing due between this date range.</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'><span style='color: #ff0000;'>Please select a project with/or date range.</span></td></tr>";
                                    }
                                ?>
                            </table>
                        </td>
                    </tr>
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