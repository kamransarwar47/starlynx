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
            p.title, t.id, t.invoice_date, a.title AS account, a.id AS a_id, a.master_id AS m_id,
            (
                SELECT
                    title
                FROM
                    accounts
                WHERE
                    id = m_id
            ) AS head_account,
            d.account_id AS d_id,
            (
                SELECT
                    title
                FROM
                    accounts
                WHERE
                    id = d_id
            ) AS sub_account,
            t.voucher_id, d.amount, t.transaction_type, d.notes as d_notes, t.notes as t_notes
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
        ORDER BY
            head_account, account, sub_account, t.id
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

    //printArray($result_data);
    //exit;

    echo "<table border='0' width='100%' cellpadding='5' cellspacing='0' align='center' id='reclist'>";
    echo "<tr id='listhead'>";
    echo "<td width='55%'>Account</td>";
    echo "<td width='15%' align='center'>Date</td>";

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
            $data[$rs["title"]][$rs["head_account"]][$rs["transaction_type"]][$rs["account"]][$rs["sub_account"]][] = array(
                "amount" => $rs["amount"],
                "voucher" => $rs["voucher_id"],
                "notes" => $rs["d_notes"],
                "date" => $rs["invoice_date"]
            );
        }

        //printArray($data);
        //exit;

        foreach ($data as $project => $account_data) {

            echo "<tr>";
            echo "<td colspan='4' style='background-color:#F8F8F8;'><strong>$project</strong></td>";
            echo "</tr>";

            foreach ($account_data as $head_account => $transactions) {
                //printArray($transactions);

                echo "<tr>";
                echo "<td colspan='4'><strong>&nbsp;&nbsp;$head_account</strong></td>";
                echo "</tr>";

                foreach ($transactions["RECEIPT"] as $account => $particulars) {
                    //echo $account;
                    //printArray($particulars);

                    echo "<tr>";
                    echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;$account</td>";
                    echo "<td align='right'></td>";

                    if ($incCol == "I" || $incCol == "B") {
                        echo "<td align='right'></td>";
                    }

                    if ($incCol == "E" || $incCol == "B") {
                        echo "<td align='right'></td>";
                    }

                    echo "</tr>";

                    foreach ($particulars as $sub_account => $detail) {
                        //echo $sub_account;
                        //printArray($detail);
                        for ($i = 0; $i < sizeof($detail); $i++) {
                            echo "<tr>";
                            echo "<td>";
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$sub_account";
                            echo " <span class='notes'>(";
                            echo "<a href='index.php?ss=$ss&mod=invoices.view&voucher_number=" . $detail[$i]["voucher"] . "'>" . $detail[$i]["voucher"] . "</a>";

                            if (!empty($detail[$i]["notes"])) {
                                echo " - " . $detail[$i]["notes"];
                            }

                            echo ")</span>";
                            echo "</td>";
                            echo "<td align='center'>" . date("d-m-Y", strtotime($detail[$i]["date"])) . "</td>";

                            if ($incCol == "I" || $incCol == "B") {
                                echo "<td align='right'>" . formatCurrency($detail[$i]["amount"]) . "</td>";
                            }

                            if ($incCol == "E" || $incCol == "B") {
                                echo "<td align='right'>0.00</td>";
                            }

                            echo "</tr>";

                            $income += $detail[$i]["amount"];
                        }
                    }
                }

                foreach ($transactions["PAYMENT"] as $account => $particulars) {
                    //echo $account;
                    //printArray($particulars);

                    echo "<tr>";
                    echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;$account</td>";
                    echo "<td align='right'></td>";

                    if ($incCol == "I" || $incCol == "B") {
                        echo "<td align='right'></td>";
                    }

                    if ($incCol == "E" || $incCol == "B") {
                        echo "<td align='right'></td>";
                    }

                    echo "</tr>";

                    foreach ($particulars as $sub_account => $detail) {
                        //echo $sub_account;
                        //printArray($detail);
                        for ($i = 0; $i < sizeof($detail); $i++) {
                            echo "<tr>";
                            echo "<td>";
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$sub_account";
                            echo " <span class='notes'>(";
                            echo "<a href='index.php?ss=$ss&mod=invoices.view&voucher_number=" . $detail[$i]["voucher"] . "'>" . $detail[$i]["voucher"] . "</a>";

                            if (!empty($detail[$i]["notes"])) {
                                echo " - " . $detail[$i]["notes"];
                            }

                            echo ")</span>";
                            echo "</td>";
                            echo "<td align='center'>" . date("d-m-Y", strtotime($detail[$i]["date"])) . "</td>";

                            if ($incCol == "I" || $incCol == "B") {
                                echo "<td align='right'>0.00</td>";
                            }

                            if ($incCol == "E" || $incCol == "B") {
                                echo "<td align='right'>" . formatCurrency($detail[$i]["amount"]) . "</td>";
                            }

                            echo "</tr>";

                            $expense += $detail[$i]["amount"];
                        }
                    }
                }
            }

        }

        echo "<tr>";
        echo "<td align='right'><strong>Totals</strong></td>";
        echo "<td align='right'></td>";

        if ($incCol == "I" || $incCol == "B") {
            echo "<td align='right'><strong>" . formatCurrency($income) . "</strong></td>";
        }

        if ($incCol == "E" || $incCol == "B") {
            echo "<td align='right'><strong>" . formatCurrency($expense) . "</strong></td>";
        }

        echo "</tr>";
    } else {
        echo "<tr>";
        echo "<td align='center' colspan='4'>No record found.</td>";
        echo "</tr>";
    }

    echo "</table>";
?>