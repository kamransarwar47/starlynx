<?php
    $total_income   = 0;
    $total_expenses = 0;
    $total_balance  = 0;

    $sql = "SELECT
                SUM(CASE WHEN t.transaction_type = 'RECEIPT' THEN d.amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN t.transaction_type = 'PAYMENT' THEN d.amount ELSE 0 END) AS total_expenses
            FROM
                transactions t, transactions_details d
            WHERE
                t.id = d.transaction_id
            GROUP BY
                t.transaction_type";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $num_rows = mysql_num_rows($result);

    if ($num_rows > 0) {
        while ($rs = mysql_fetch_array($result)) {
            $total_income   += $rs["total_income"];
            $total_expenses += $rs["total_expenses"];
        }
        $total_balance = ($total_income - $total_expenses);
    }
?>
<div class="col-lg-4">
    <div class="card">
        <div class="stat-widget-one">
            <div class="stat-content d-flex justify-content-between">
                <div class="stat-text text-success">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <div class="stat-digit">
                    <p class="mb-2">Total Income</p>
                    <h3>Rs.<?php echo formatCurrency($total_income); ?></h3>
                </div>
            </div>
            <p class="mb-0">* Includes Pending Auth And Handovers / Clearances</p>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="card">
        <div class="stat-widget-one">
            <div class="stat-content d-flex justify-content-between">
                <div class="stat-text text-warning">
                    <i class="mdi mdi-receipt"></i>
                </div>
                <div class="stat-digit">
                    <p class="mb-2">Total Expenses</p>
                    <h3>Rs.<?php echo formatCurrency($total_expenses); ?></h3>
                </div>
            </div>
            <p class="mb-0">* Includes Pending Auth And Handovers / Clearances</p>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="card">
        <div class="stat-widget-one">
            <div class="stat-content d-flex justify-content-between">
                <?php
                    if ($total_balance > 0) {
                        ?>
                        <div class="stat-text text-success">
                            <i class="mdi mdi-trending-up"></i>
                        </div>
                        <?php
                    } else if ($total_balance < 0) {
                        ?>
                        <div class="stat-text text-danger">
                            <i class="mdi mdi-trending-down"></i>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="stat-text text-muted">
                            <i class="mdi mdi-trending-neutral"></i>
                        </div>
                        <?php
                    }
                ?>
                <div class="stat-digit">
                    <p class="mb-2">Total Balance</p>
                    <h3>Rs.<?php echo formatCurrency($total_balance); ?></h3>
                </div>
            </div>
            <p class="mb-0">* Overall Profit / Loss Value</p>
        </div>
    </div>
</div>