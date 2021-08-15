<!--
    Pending Authorization
-->
<?php
    $sql = "SELECT
                t.id, t.voucher_id, t.account_id, SUM( d.amount ) AS amt
            FROM
                transactions t, transactions_details d
            WHERE
                t.id = d.transaction_id
                AND t.transaction_type =  'PAYMENT'
                AND t.auth_status =  'PENDING'
            GROUP BY
                t.id
            ORDER BY
                t.id DESC";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-6">
    <div class="card">
        <div class="card-body">
            <h4>Pending Authorization <span class="card-title"><?php echo "<a style='float: right;' href='index.php?ss=$ss&mod=invoices.manage'>View All</a>"; ?></span></h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>&nbsp;</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($numrows > 0) {
                            while ($rs = mysql_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td><a href='index.php?ss=$ss&mod=invoices.view&voucher_number={$rs["voucher_id"]}'>{$rs["voucher_id"]}</a></td>";
                                echo "<td>" . getAccountTitle($rs["account_id"]) . "</td>";
                                echo "<td>" . number_format($rs["amt"], 2, ".", ",") . "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--
    Pending Handover
-->
<?php
    $sql = "SELECT
                t.id, t.voucher_id, t.account_id, SUM( d.amount ) AS amt
            FROM
                transactions t, transactions_details d
            WHERE
                t.id = d.transaction_id
                AND t.transaction_type =  'RECEIPT'
                AND t.handover_status =  'PENDING'
            GROUP BY
                t.id
            ORDER BY
                t.id DESC";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-6">
    <div class="card">
        <div class="card-body">
            <h4>Pending Handover <span class="card-title"><?php echo "<a style='float: right;' href='index.php?ss=$ss&mod=invoices.manage'>View All</a>"; ?></span></h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>&nbsp;</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($numrows > 0) {
                            while ($rs = mysql_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td><a href='index.php?ss=$ss&mod=invoices.view&voucher_number={$rs["voucher_id"]}'>{$rs["voucher_id"]}</a></td>";
                                echo "<td>" . getAccountTitle($rs["account_id"]) . "</td>";
                                echo "<td>" . number_format($rs["amt"], 2, ".", ",") . "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>