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
            p.title, p.id as project_id, t.id, t.invoice_date, a.title AS account, a.id AS a_id, a.master_id AS m_id,
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
            t.voucher_id, d.amount, t.transaction_type, d.notes as d_notes, t.notes as t_notes, d.voucher_type as voucher_type, d.cheque_number as cheque_number, d.cheque_total_amount as cheque_total_amount, t.received_by as handover_to, t.auth_by as auth_by, d.deposit_date as deposit_date, d.deposit_in as deposit_in, d.deposit_acc_title as deposit_acc_title, d.deposit_slip_num as deposit_slip_num, d.deposit_remarks as deposit_remarks
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
                                echo $detail[$i]["voucher"];
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
                                echo $detail[$i]["voucher"];
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
        header("Content-Disposition: attachment; filename=" . $filename . "-REPORT-FULL-" . date("ymdhis") . ".xls");
        echo "<table border='0' width='100%'>";
        echo "<tr style='font-size: 18px;'>";
        echo "<th style='background: #ccdad8;'>DATE</th>";
        echo "<th style='background: #ccdad8;'>PROJECT</th>";
        echo "<th style='background: #ccdad8;'>HEAD ACCOUNT</th>";
        echo "<th style='background: #ccdad8;'>SUB ACCOUNT (1)</th>";
        echo "<th style='background: #ccdad8;'>SUB ACCOUNT (2)</th>";
        echo "<th style='background: #ccdad8;'>SUB ACCOUNT (3)</th>";
        echo "<th style='background: #ccdad8;'>SUB ACCOUNT (4)</th>";
        echo "<th style='background: #ccdad8;'>NAME</th>";
        echo "<th style='background: #ccdad8;'>PAYMENT TYPE</th>";
        echo "<th style='background: #ccdad8;'>NOTES</th>";
        if ($incCol == "I" || $incCol == "B") {
            echo "<th style='background: #ccdad8;'>INCOME</th>";
        }
        if ($incCol == "E" || $incCol == "B") {
            echo "<th style='background: #ccdad8;'>EXPENSE</th>";
        }
        echo "<th style='background: #ccdad8;'>PAYMENT MODE</th>";
        echo "<th style='background: #ccdad8;'>CHEQUE/CASH NO.</th>";
        echo "<th style='background: #ccdad8;'>CHEQUE TOTAL AMOUNT</th>";
        echo "<th style='background: #ccdad8;'>SYSTEM REF NO.</th>";
        echo "<th style='background: #ccdad8;'>HANDOVER TO</th>";
        echo "<th style='background: #ccdad8;'>AUTHORIZED BY</th>";
        echo "<th style='background: #cccccc;'></th>";
        echo "<th style='background: #fde9d9;'>DATE</th>";
        echo "<th style='background: #fde9d9;'>CHEQUE/CASH</th>";
        echo "<th style='background: #fde9d9;'>CHEQUE NO.</th>";
        echo "<th style='background: #fde9d9;'>DEPOSIT IN</th>";
        echo "<th style='background: #fde9d9;'>A/C TITLE</th>";
        echo "<th style='background: #fde9d9;'>AMOUNT</th>";
        echo "<th style='background: #fde9d9;'>DEPOSIT SLIP NO.</th>";
        echo "<th style='background: #fde9d9;'>REMARKS</th>";
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
                    "date" => $rs["invoice_date"],
                    "voucher_type" => $rs["voucher_type"],
                    "cheque_number" => $rs["cheque_number"],
                    "cheque_total_amount" => $rs["cheque_total_amount"],
                    "handover_to" => $rs["handover_to"],
                    "auth_by" => $rs["auth_by"],
                    "deposit_date" => $rs["deposit_date"],
                    "deposit_in" => $rs["deposit_in"],
                    "deposit_acc_title" => $rs["deposit_acc_title"],
                    "deposit_slip_num" => $rs["deposit_slip_num"],
                    "deposit_remarks" => $rs["deposit_remarks"],
                    "project_id" => $rs["project_id"],
                    "last_account_id" => $rs["d_id"],
                );
            }
            foreach ($data as $project => $account_data) {
                foreach ($account_data as $head_account => $transactions) {
                    if ($incCol == "I" || $incCol == "B") {
                        foreach ($transactions["RECEIPT"] as $account => $particulars) {
                            foreach ($particulars as $sub_account => $detail) {
                                for ($i = 0; $i < sizeof($detail); $i++) {

                                    $sub_account_array = getSubAccountsExcelSheet($detail[$i]['project_id'], $detail[$i]['last_account_id']);

                                    echo "<tr align='left' style='font-size: 16px;'>";
                                    echo "<td>" . date("d-M-y", strtotime($detail[$i]["date"])) . "</td>";
                                    echo "<td>$project</td>";
                                    echo "<td>$head_account</td>";
                                    echo "<td>" . $sub_account_array[0] . "</td>";
                                    echo "<td>" . $sub_account_array[1] . "</td>";
                                    echo "<td>" . $sub_account_array[2] . "</td>";
                                    echo "<td>" . $sub_account_array[3] . "</td>";
                                    echo "<td>$account</td>";
                                    echo "<td>RECEIPT</td>";
                                    echo "<td>" . $detail[$i]["notes"] . "</td>";
                                    if ($incCol == "I" || $incCol == "B") {
                                        echo "<td>" . formatCurrency($detail[$i]["amount"]) . "</td>";
                                    }
                                    if ($incCol == "E" || $incCol == "B") {
                                        echo "<td>0.00</td>";
                                    }
                                    echo "<td>" . $detail[$i]['voucher_type'] . "</td>";
                                    echo "<td>" . $detail[$i]['cheque_number'] . "</td>";
                                    echo "<td>" . (($detail[$i]['cheque_total_amount'] != "") ? formatCurrency($detail[$i]['cheque_total_amount']) : "") . "</td>";
                                    echo "<td>" . $detail[$i]['voucher'] . "</td>";
                                    echo "<td>" . getUserFullName($detail[$i]['handover_to']) . "</td>";
                                    echo "<td></td>";
                                    echo "<td style='background: #cccccc;'></td>";
                                    if ($detail[$i]["deposit_slip_num"] != "" && $detail[$i]["deposit_slip_num"] != NULL) {
                                        echo "<td>" . date("d-M-y", strtotime($detail[$i]["deposit_date"])) . "</td>";
                                        echo "<td>" . (($detail[$i]['voucher_type'] == "CASH") ? "CASH" : "CHEQUE") . "</td>";
                                        echo "<td>" . $detail[$i]['cheque_number'] . "</td>";
                                        echo "<td>" . getDepositAccountTitle($detail[$i]['deposit_in']) . "</td>";
                                        echo "<td>" . $detail[$i]['deposit_acc_title'] . "</td>";
                                        echo "<td>" . formatCurrency($detail[$i]["amount"]) . "</td>";
                                        echo "<td>" . $detail[$i]['deposit_slip_num'] . "</td>";
                                        echo "<td>" . $detail[$i]['deposit_remarks'] . "</td>";
                                    } else {
                                        echo "<td colspan='8'></td>";
                                    }
                                    echo "</tr>";
                                    $income += $detail[$i]["amount"];
                                }
                            }
                        }
                    }
                    if ($incCol == "E" || $incCol == "B") {
                        foreach ($transactions["PAYMENT"] as $account => $particulars) {
                            foreach ($particulars as $sub_account => $detail) {
                                for ($i = 0; $i < sizeof($detail); $i++) {

                                    $sub_account_array = getSubAccountsExcelSheet($detail[$i]['project_id'], $detail[$i]['last_account_id']);

                                    echo "<tr align='left' style='font-size: 16px;'>";
                                    echo "<td>" . date("d-M-y", strtotime($detail[$i]["date"])) . "</td>";
                                    echo "<td>$project</td>";
                                    echo "<td>$head_account</td>";
                                    echo "<td>" . $sub_account_array[0] . "</td>";
                                    echo "<td>" . $sub_account_array[1] . "</td>";
                                    echo "<td>" . $sub_account_array[2] . "</td>";
                                    echo "<td>" . $sub_account_array[3] . "</td>";
                                    echo "<td>$account</td>";
                                    echo "<td>PAYMENT</td>";
                                    echo "<td>" . $detail[$i]["notes"] . "</td>";
                                    if ($incCol == "I" || $incCol == "B") {
                                        echo "<td>0.00</td>";
                                    }
                                    if ($incCol == "E" || $incCol == "B") {
                                        echo "<td>" . formatCurrency($detail[$i]["amount"]) . "</td>";
                                    }
                                    echo "<td>" . $detail[$i]['voucher_type'] . "</td>";
                                    echo "<td>" . $detail[$i]['cheque_number'] . "</td>";
                                    echo "<td>" . (($detail[$i]['cheque_total_amount'] != "") ? formatCurrency($detail[$i]['cheque_total_amount']) : "") . "</td>";
                                    echo "<td>" . $detail[$i]['voucher'] . "</td>";
                                    echo "<td></td>";
                                    echo "<td>" . getUserFullName($detail[$i]['auth_by']) . "</td>";
                                    echo "<td style='background: #cccccc;'></td>";
                                    echo "<td colspan='8'></td>";
                                    echo "</tr>";
                                    $expense += $detail[$i]["amount"];
                                }
                            }
                        }
                    }
                }

            }
            echo "<tr>";
            echo "<td align='right' colspan='10' style='font-size: 18px; background: #ffff00;'><strong>TOTALS</strong></td>";
            if ($incCol == "I" || $incCol == "B") {
                echo "<td align='right' style='font-size: 18px; background: #ffff00;'><strong>" . formatCurrency($income) . "</strong></td>";
            }
            if ($incCol == "E" || $incCol == "B") {
                echo "<td align='right' style='font-size: 18px; background: #ffff00;'><strong>" . formatCurrency($expense) . "</strong></td>";
            }
            echo "<td colspan='15' style='background: #ffff00;'></td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td align='center' style='font-size: 16px;'>No record found.</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
?>