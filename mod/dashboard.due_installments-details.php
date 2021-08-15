<strong class="box_title">Due Installments</strong>
<br />
<?php
    $yyyymm = date("Y-m-t", time());
    $sql = "SELECT
                                                p.id, p.title,
                                                pl.plot_number, pl.plot_type,
                                                d.amount, d.due_on, d.status,
                                                c.full_name AS customer, c.father_name as c_father, c.mobile_1 AS c_mob1, c.mobile_2 AS c_mob2,
                                                dl.full_name AS dealer, dl.mobile_1 AS d_mob1, dl.mobile_2 AS d_mob2
                                            FROM
                                                plots pl, plots_dues d, projects p, customers c, dealers dl
                                            WHERE
                                                p.id = pl.project_id
                                                AND pl.id = d.plot_id
                                                AND pl.customer_id = c.id
                                                AND pl.dealer_id = dl.id
                                                AND d.due_on <= '$yyyymm'
                                                AND d.status =  'DUE'
                                                AND p.id = pl.project_id
                                            ORDER BY
                                                d.due_on ASC, p.title ASC , pl.plot_type ASC , pl.id ASC";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    //echo $sql;
    $numrows = mysql_num_rows($result);
    $installments = array();
    $ctime = time();

    if($numrows > 0) {
        echo "<table border='0' width='100%' cellpadding='3' cellspacing='0' align='center' id='reclist'>";
        echo "<tr id='listhead'>";
        echo "<td width='20%'>Due On</td>";
        echo "<td width='20%'>Plot</td>";
        echo "<td width='40%'>Customer</td>";
        echo "<td width='20%'>Amount</td>";
        echo "</tr>";

        while($rs = mysql_fetch_array($result)) {
            $installments[$rs["id"]."~".$rs["title"]][] = array(
                "plot" => $rs["plot_number"],
                "type" => $rs["plot_type"],
                "amount" => $rs["amount"],
                "due_on" => $rs["due_on"],
                "customer" => $rs["customer"]
            );
        }

        foreach($installments as $project => $details) {
            $prj = explode("~", $project);

            echo "<tr id='listhead'>";
            echo "<td colspan='4'><a href='index.php?ss=$ss&mod=rpt.due_installments&project_id=".$prj[0]."'>".$prj[1]."</a></td>";
            echo "</tr>";

            for($i=0; $i<sizeof($details); $i++) {
                $style = "";
                $ditime = strtotime($details[$i]["due_on"]);

                if($ditime <= $ctime) {
                    $style = "background-color: #ffeeee;";
                }

                echo "<tr style='$style'>";
                echo "<td>".date("d-m-Y", strtotime($details[$i]["due_on"]))."</td>";
                echo "<td>";
                echo ($details[$i]["type"]=="Residential"?"Plot":$details[$i]["type"]);
                echo " ".$details[$i]["plot"];
                echo "</td>";
                echo "<td>".$details[$i]["customer"]."</td>";
                echo "<td align='right'>".formatCurrency($details[$i]["amount"])."</td>";
                echo "</tr>";
            }
        }

        echo "</table><br />";
    } else {
        echo "No pending authorization found.";
    }

    //printArray($installments);
?>