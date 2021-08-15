<?php
    $yyyymm = date("Y-m-t", time());
    $sql    = "SELECT
                p.id, p.title, SUM( d.amount ) as amount
            FROM
                plots pl, plots_dues d, projects p, customers c, dealers dl
            WHERE
                p.id = pl.project_id
                AND pl.id = d.plot_id
                AND d.due_on <=  '$yyyymm'
                AND d.status =  'DUE'
                AND pl.customer_id = c.id
                AND pl.dealer_id = dl.id
            GROUP BY
                p.title
            ORDER BY
                p.title ASC";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows            = mysql_num_rows($result);
    $total_dues         = 0;
    $monthly_due_report = [];

    if ($numrows > 0) {
        $n = 0;
        while ($rs = mysql_fetch_array($result)) {
            $monthly_due_report[$n]["id"]     = $rs["id"];
            $monthly_due_report[$n]["amount"] = $rs["amount"];
            $monthly_due_report[$n]["title"]  = $rs["title"];
            $total_dues                       += $rs["amount"];
            $n++;
        }
    }
?>
<div class="col-lg-4">
    <div class="card card-widget">
        <div class="card-body">
            <h4 class="text-muted">Project Dues till <?= date("F, Y", time()) ?></h4>
            <h2 class="mt-4">Rs.<?php echo formatCurrency($total_dues); ?></h2>
            <span>Total Dues</span>
            <div class="monthly_due_report" id="monthly_due_report">
                <?php
                    if (!empty($monthly_due_report)) {
                        foreach ($monthly_due_report as $r) {
                            $percentage = number_format((($r["amount"] / $total_dues) * 100), 2);
                            echo '<div class="mt-4">
                                        <h5 class="text-muted">Rs.' . formatCurrency($r["amount"]) . '</h5>
                                        <h6><a href="index.php?ss=' . $ss . '&mod=rpt.due_installments&project_id=' . $r["id"] . '">' . $r["title"] . '</a> <span class="pull-right mr-2">' . $percentage . '%</span></h6>
                                        <div class="progress mb-3" style="height: 7px">
                                            <div class="progress-bar" style="width: ' . $percentage . '%; background-color: #' . random_color() . '" role="progressbar"><span class="sr-only">' . $percentage . '% Complete</span>
                                            </div>
                                        </div>
                                    </div>';
                        }
                    } else {

                    }
                ?>
            </div>
        </div>
    </div>
</div>
