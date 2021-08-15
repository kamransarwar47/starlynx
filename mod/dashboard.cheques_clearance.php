<?php
    $sql = "SELECT
                t.account_id, t.voucher_id, t.invoice_date, sum(d.amount) as amount, d.bank_id, d.cheque_number
            FROM
                transactions t, transactions_details d
            WHERE
                t.id = d.transaction_id
                AND t.transaction_type =  'RECEIPT'
                AND t.handover_status =  'HANDOVER'
                AND d.clearance_status = 'PENDING'
            GROUP BY
                t.voucher_id, d.cheque_number
            HAVING 
                d.cheque_number <> ''
            ORDER BY
                t.id desc
            ";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4>Pending Clearances <span class="card-title"><?php echo "<a style='float: right;' href='index.php?ss=$ss&mod=invoices.manage'>View All</a>"; ?></span></h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>&nbsp;</th>
                        <th>Chq. # / Cash Sr.</th>
                        <th>Inv. Date</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($numrows > 0) {
                            while ($rs = mysql_fetch_array($result)) {
                                $account_id    = $rs["account_id"];
                                $voucher_id    = $rs["voucher_id"];
                                $amount        = $rs["amount"];
                                $bank_id       = $rs["bank_id"];
                                $cheque_number = $rs["cheque_number"];
                                $invoice_date  = $rs["invoice_date"];

                                echo "<tr>";
                                echo "<td><a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>$voucher_id</a></td>";
                                echo "<td>" . getAccountTitle($account_id) . "</td>";
                                echo "<td>$cheque_number<br />" . getBankShortName($bank_id) . "</td>";
                                echo "<td>" . date("d-m-Y", strtotime($invoice_date)) . "</td>";
                                echo "<td align='right'>" . number_format($amount, 2, ".", ",") . "</td>";
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