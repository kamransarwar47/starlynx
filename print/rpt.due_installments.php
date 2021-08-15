<?
    define("MODULE_ID", "RPT009");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

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
        $numrows = mysql_num_rows($result);
    }

    // print output
    if ($output == 'print') {

    $print = false;

    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        $print = true;
        system_log(PRNT, "Loaded for printing.", $userInfo["id"]);
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
                    Due Installments
                    <span class="notes"
                          style="float: right; text-align: right; font-style: italic;">Printed on <?= date("d-m-Y", time()) ?></span>
                </p>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td colspan="5">
                            <p style="float: left; width: auto; height: auto;">
                                <?php
                                if(!empty($project_id)) {
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

                                                echo "<tr>";
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
                                        echo "<tr><td colspan='6'><span style='color: #ff0000;'>Please select a project.</span></td></tr>";
                                    }
                                ?>
                            </table>
                        </td>
                    </tr>
                </table>
            <? } ?>
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

    // output excel sheet
    } else {
    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        system_log(PRNT, "Loaded for download.", $userInfo["id"]);

        if ((!empty($project_id) && !empty($dtFrom) && !empty($dtTo)) || (empty($project_id) && (!empty($dtFrom) && !empty($dtTo)))) {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . "REPORT-DUE-INSTALLMENTS-" . date("ymdhis") . ".xls");
            ?>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                <?
                    if (!empty($project_id)) {
                        ?>
                        <tr align="center">
                            <td>
                                <strong style="font-size: 14px;"><?= getProjectName($project_id) ?></strong>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
                <tr align="center">
                    <td>
                        <strong>
                            <? if (empty($dtFrom) || empty($dtTo)) { ?>
                                From Beginning To <?= date("F Y", time()) ?>
                            <? } else { ?>
                                From <?= date("d-m-Y", strtotime($dtFrom)) ?> To <?= date("d-m-Y", strtotime($dtTo)) ?>
                            <? } ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <?
                    if ($numrows > 0) {
                        ?>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
                                    <tr style="background: #d9d9d9;" align="left">
                                        <td style="font-size: 16px; font-weight: bold;">Due On</td>
                                        <td style="font-size: 16px; font-weight: bold;">Project</td>
                                        <td style="font-size: 16px; font-weight: bold;">Plot #</td>
                                        <td style="font-size: 16px; font-weight: bold;">Amount</td>
                                        <td style="font-size: 16px; font-weight: bold;">Customer</td>
                                        <td style="font-size: 16px; font-weight: bold;">Dealer</td>
                                    </tr>
                                    <?
                                        $total_due = 0;
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

                                            echo "<tr align='left'>";
                                            echo "<td>$due_on</td>";
                                            echo "<td>$d_project_title</td>";
                                            echo "<td>";

                                            echo($plot_type == "Residential" ? "Plot" : $plot_type);
                                            echo " $plot_number";

                                            echo "</td>";
                                            echo "<td>" . formatCurrency($amount) . "</td>";
                                            echo "<td>";
                                            echo "<strong>$customer</strong> <span>s/w/o $c_father</span>";

                                            if (!empty($c_mob1)) {
                                                echo " $c_mob1";
                                            }
                                            if (!empty($c_mob2)) {
                                                echo " $c_mob2";
                                            }

                                            echo "</td>";
                                            echo "<td>";
                                            echo "<strong>$dealer</strong>";

                                            if (!empty($d_mob1)) {
                                                echo " $d_mob1";
                                            }
                                            if (!empty($d_mob2)) {
                                                echo " $d_mob2";
                                            }

                                            echo "</td>";
                                            echo "</tr>";
                                            $total_due += $amount;
                                        }
                                    ?>
                                    <tr style="background: #d9d9d9;" align="left">
                                        <td colspan="2">&nbsp;</td>
                                        <td><strong>Total</strong></td>
                                        <td><strong><?php echo formatCurrency($total_due); ?></strong></td>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "<tr><td>Nothing due between this date range.</td></tr>";
                    }
                ?>
            </table>
            <?php
        } else {
            ?>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <?
                            echo "<span style='color: #ff0000;'>Please select a project with/or date range.</span>";
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}
?>
</body>
</html>