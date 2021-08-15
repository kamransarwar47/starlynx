<?php
    if ($incPending != "Y") {
        $pendingAuthHand = "AND ((t.transaction_type='PAYMENT' AND t.auth_status='AUTH') OR (t.transaction_type='RECEIPT' AND t.handover_status='HANDOVER'))";
    } else {
        $pendingAuthHand = "";
    }

    if ($incPendingClear != "Y") {
        $pendingClearance = "AND (
            (
                t.transaction_type =  'RECEIPT'
                AND d.voucher_type =  'BANK'
                AND d.clearance_status =  'CLEARED'
            )
            OR
            (
                t.transaction_type =  'RECEIPT'
                AND d.voucher_type =  'CASH'
            )
            OR
            (
                t.transaction_type =  'PAYMENT'
                AND d.voucher_type IN ('BANK',  'CASH')
            )
        )";
    } else {
        $pendingClearance = "";
    }

    $result_data = array();
    $sql_project = "";
    $date_range  = "";

    // --- search logic --- //

    // only date --- project empty
    if ((!empty($dtFrom) && !empty($dtTo)) && empty($project_id)) {
        $sql_project = "SELECT id FROM projects ORDER BY title ASC";
        $date_range  = "AND t.invoice_date BETWEEN '$dtFrom' AND '$dtTo'";

        // project with date range
    } else {
        $date_range  = "AND t.invoice_date BETWEEN '$dtFrom' AND '$dtTo'";
        $sql_project = "SELECT id, title FROM projects WHERE id = \"" . $project_id . "\" ORDER BY title ASC";

    }

    $result_project = mysql_query($sql_project, $conn) or die(mysql_error());

    while ($rs_project = mysql_fetch_array($result_project)) {

        $sql = "SELECT
            p.title, t.id, a.title as account, a.id AS a_id, a.master_id AS m_id,
            (
                SELECT
                    title
                FROM
                    accounts
                WHERE
                    id = m_id
            ) AS head_account,
            SUM(d.amount) AS amt, t.transaction_type
        FROM
            projects p, transactions t, transactions_details d, accounts a
        WHERE
            p.id = \"" . $rs_project['id'] . "\"
            AND t.project_id = p.id
            AND t.id = d.transaction_id
            AND t.account_id = a.id
            $date_range
            $pendingAuthHand
            $pendingClearance
        GROUP BY
            account, transaction_type
        ORDER BY
            head_account, t.id
        ";
        //echo $sql . '<br><br>';
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {

            while ($rs = mysql_fetch_array($result)) {
                array_push($result_data, $rs);
            }
        }
    }

    /*
     * Print
     * */
    if ($output == 'print') {

        echo "<table border='0' width='100%' cellpadding='5' cellspacing='0' align='center' id='reclist'>";
        echo "<tr id='listhead'>";
        echo "<td width='70%'>Account</td>";

        if ($incCol == "I" || $incCol == "B") {
            echo "<td width='15%' align='right'>Income</td>";
        }

        if ($incCol == "E" || $incCol == "B") {
            echo "<td width='15%' align='right'>Expense</td>";
        }

        echo "</tr>";

        if (!empty($result_data)) {
            $data    = array();
            $income  = 0;
            $expense = 0;

            foreach ($result_data as $rs) {
                $data[$rs["title"]][$rs["head_account"]][$rs["transaction_type"]][$rs["account"]] = $rs["amt"];
            }

            foreach ($data as $project => $account_data) {

                echo "<tr>";
                echo "<td colspan='4' style='background-color:#F8F8F8;'><strong>$project</strong></td>";
                echo "</tr>";

                foreach ($account_data as $head_account => $transactions) {
                    //printArray($transactions);

                    echo "<tr>";
                    echo "<td colspan='3'><strong>&nbsp;&nbsp;$head_account</strong></td>";
                    echo "</tr>";

                    foreach ($transactions["RECEIPT"] as $account => $amount) {
                        //printArray($transactions);
                        //printArray($recs);
                        echo "<tr>";
                        echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;$account</td>";

                        if ($incCol == "I" || $incCol == "B") {
                            echo "<td align='right'>" . formatCurrency($amount) . "</td>";
                        }

                        if ($incCol == "E" || $incCol == "B") {
                            echo "<td align='right'>0.00</td>";
                        }

                        echo "</tr>";

                        $income += $amount;
                    }

                    foreach ($transactions["PAYMENT"] as $account => $amount) {
                        //printArray($transactions);
                        //printArray($recs);
                        echo "<tr>";
                        echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;$account</td>";

                        if ($incCol == "I" || $incCol == "B") {
                            echo "<td align='right'>0.00</td>";
                        }

                        if ($incCol == "E" || $incCol == "B") {
                            echo "<td align='right'>" . formatCurrency($amount) . "</td>";
                        }

                        echo "</tr>";

                        $expense += $amount;
                    }
                }

            }

            echo "<tr>";
            echo "<td align='right'><strong>Totals</strong></td>";

            if ($incCol == "I" || $incCol == "B") {
                echo "<td align='right'><strong>" . formatCurrency($income) . "</strong></td>";
            }

            if ($incCol == "E" || $incCol == "B") {
                echo "<td align='right'><strong>" . formatCurrency($expense) . "</strong></td>";
            }

            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td align='center' colspan='3'>No record found.</td>";
            echo "</tr>";
        }

        echo "</table>";

        /*
         * Download Excel Sheet
         * */
    } else {
        if ($incCol == "I") {
            $filename = 'INCOME';
        } else if ($incCol == "E") {
            $filename = 'EXPENSE';
        } else {
            $filename = 'INCOME-EXPENSE';
        }
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $filename . "-REPORT-SUB-ACCOUNTS-" . date("ymdhis") . ".xls");
        echo "<table border='0' width='100%'>";
        echo "<tr style='font-size: 18px;'>";
        echo "<th style='background: #ccdad8;'>PROJECT</th>";
        echo "<th style='background: #ccdad8;'>HEAD ACCOUNT</th>";
        echo "<th style='background: #ccdad8;'>NAME</th>";
        echo "<th style='background: #ccdad8;'>PAYMENT TYPE</th>";
        if ($incCol == "I" || $incCol == "B") {
            echo "<th style='background: #ccdad8;'>INCOME</th>";
        }
        if ($incCol == "E" || $incCol == "B") {
            echo "<th style='background: #ccdad8;'>EXPENSE</th>";
        }
        echo "</tr>";

        if (!empty($result_data)) {
            $data    = array();
            $income  = 0;
            $expense = 0;

            foreach ($result_data as $rs) {
                $data[$rs["title"]][$rs["head_account"]][$rs["transaction_type"]][$rs["account"]] = array(
                    "amount" => $rs["amt"],
                );
            }

            foreach ($data as $project => $account_data) {
                foreach ($account_data as $head_account => $transactions) {
                    if ($incCol == "I" || $incCol == "B") {
                        foreach ($transactions["RECEIPT"] as $account => $detail) {
                            echo "<tr align='left' style='font-size: 16px;'>";
                            echo "<td>$project</td>";
                            echo "<td>$head_account</td>";
                            echo "<td>$account</td>";
                            echo "<td>RECEIPT</td>";
                            if ($incCol == "I" || $incCol == "B") {
                                echo "<td>" . formatCurrency($detail["amount"]) . "</td>";
                            }
                            if ($incCol == "E" || $incCol == "B") {
                                echo "<td>0.00</td>";
                            }
                            echo "</tr>";

                            $income += $detail["amount"];
                        }
                    }

                    if ($incCol == "E" || $incCol == "B") {
                        foreach ($transactions["PAYMENT"] as $account => $detail) {
                            echo "<tr align='left' style='font-size: 16px;'>";
                            echo "<td>$project</td>";
                            echo "<td>$head_account</td>";
                            echo "<td>$account</td>";
                            echo "<td>PAYMENT</td>";
                            if ($incCol == "I" || $incCol == "B") {
                                echo "<td>0.00</td>";
                            }
                            if ($incCol == "E" || $incCol == "B") {
                                echo "<td>" . formatCurrency($detail["amount"]) . "</td>";
                            }
                            echo "</tr>";

                            $expense += $detail["amount"];
                        }
                    }
                }

            }

            echo "<tr>";
            echo "<td align='right' colspan='4' style='font-size: 18px; background: #ffff00;'><strong>TOTALS</strong></td>";
            if ($incCol == "I" || $incCol == "B") {
                echo "<td align='right' style='font-size: 18px; background: #ffff00;'><strong>" . formatCurrency($income) . "</strong></td>";
            }
            if ($incCol == "E" || $incCol == "B") {
                echo "<td align='right' style='font-size: 18px; background: #ffff00;'><strong>" . formatCurrency($expense) . "</strong></td>";
            }
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td align='center' style='font-size: 16px;'>No record found.</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
?>