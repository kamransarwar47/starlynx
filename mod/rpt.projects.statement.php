<?
    define("MODULE_ID", "RPT011");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $(".btnPrint").click(function () {
            var incPending = "";
            var incPendingClear = "";
            var project_id = $("#project_id").val();
            var report_month_from = $("#dtMonthFrom").val();
            var report_month_to = $("#dtMonthTo").val();
            var output = $(this).data('output');

            if ($("input:checkbox[name=incPending]:checked").val() == "Y") {
                incPending = "Y";
            }

            if ($("input:checkbox[name=incPendingClear]:checked").val() == "Y") {
                incPendingClear = "Y";
            }

            window.open("print.php?ss=<?=$ss?>&printmod=rpt.projects.statement&project_id=" + project_id + "&report_month_from=" + report_month_from + "&report_month_to=" + report_month_to + "&incPending=" + incPending + "&incPendingClear=" + incPendingClear + "&output=" + output, 'prn');
        });

        $('#dtMonthFrom, #dtMonthTo').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            maxDate: 'm', // restrict to show month less than current month
            currentText: "Current Month",
            closeText: 'Select',
            onClose: function (dateText, inst) {
                // set the date accordingly
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },
            beforeShow: function (input, inst) {
                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length - 4, datestr.length);
                    month = jQuery.inArray(datestr.substring(0, datestr.length - 5), $(this).datepicker('option', 'monthNames'));
                    $(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }
        });
    });
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
                    Project Statement

                    <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <? if (!empty($project_id) && !empty($report_month_from) && !empty($report_month_to)) { ?>
                            <input type="button" value="Export" class="btnPrint" data-output="csv"
                                   style="float: right;"/>
                            <input type="button" value="Print" class="btnPrint" data-output="print"
                                   style="float: right;"/>
                        <? } ?>
                    <? } ?>
                </p>

                <div style="clear: both; width: 1px; height: 1px;"></div>

                <form action="index.php" method="GET">
                    <input type="hidden" name="ss" id="ss" value="<?= $ss ?>"/>
                    <input type="hidden" name="mod" id="mod" value="<?= $mod ?>"/>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>Project</strong><br/>
                        <select name="project_id" id="project_id">
                            <option value="">-- Select --</option>
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
                        <strong>From</strong><br/>
                        <input type="text" name="report_month_from" id="dtMonthFrom" value="<?= $report_month_from ?>" autocomplete="off"/>
                    </p>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>To</strong><br/>
                        <input type="text" name="report_month_to" id="dtMonthTo" value="<?= $report_month_to ?>" autocomplete="off"/>
                    </p>

                    <p style="width: auto; height: auto; float: left;">
                        <strong>&nbsp;</strong><br/>
                        <input type="submit" value="Show" id="btnShow"/><br/>
                    </p>

                    <div style="clear: both; width: 1px; height: 1px;"></div>

                    <p style="float: left; width: auto; height: auto; margin-right: 20px; margin-top: 0px;">
                        <input type="checkbox" name="incPending" id="incPending" value="Y" <?= ($incPending == "Y") ? "checked='checked'" : "" ?> /> Include pending authorizations
                        and handovers
                        <br/>
                        <input type="checkbox" name="incPendingClear" id="incPendingClear" value="Y" <?= ($incPendingClear == "Y") ? "checked='checked'" : "" ?> /> Include pending
                        clearances
                    </p>
                </form>

                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <?
                                if (!empty($project_id) && !empty($report_month_from) && !empty($report_month_to)) {

                                    /*// first day of selected month
                                    $first_day_this_month = date('Y-m-d', strtotime("first day of " . $report_month));
                                    // check if selected month is current month or not and get the last day
                                    if (date("my", strtotime($report_month)) == date("my")) {
                                        $last_day_this_month = date('Y-m-d', time());
                                    } else {
                                        $last_day_this_month = date('Y-m-d', strtotime("last day of " . $report_month));
                                    }
                                    // last day of previous month
                                    $last_day_last_month = date('Y-m-d', strtotime("last day of previous month " . $report_month));*/


                                    // first day of start month
                                    $first_day_this_month = date('Y-m-d', strtotime("first day of " . $report_month_from));
                                    // check if end month is current month or not and get the last day
                                    if (date("my", strtotime($report_month_to)) == date("my")) {
                                        $last_day_this_month = date('Y-m-d', time());
                                    } else {
                                        $last_day_this_month = date('Y-m-d', strtotime("last day of " . $report_month_to));
                                    }
                                    // This day of current month
                                    $last_day_last_month = date('Y-m-d', time());

                                    // Saleable marlas
                                    $saleable_marlas = 0;
                                    // Head Office expense calculation
                                    $head_office_expense_percentage = getProjectExpensePercentage($project_id);
                                    $head_office_total_expense      = getHeadOfficeExpense($first_day_this_month, $last_day_this_month, $last_day_last_month, $incPending, $incPendingClear, $head_office_expense_percentage);

                                    // Residential plots
                                    $sold_plot_marlas         = 0;
                                    $sold_plot_total          = 0;
                                    $plot_accounts            = [];
                                    $sold_plot_total_received = 0;
                                    $sold_plot_total_paid     = 0;
                                    $sold_plot_balance        = 0;
                                    $reserved_plot_marlas     = 0;
                                    $vacant_plot_marlas       = 0;
                                    $registered_plot_marlas   = 0;
                                    // Shops
                                    $sold_shop_marlas         = 0;
                                    $sold_shop_total          = 0;
                                    $shop_accounts            = [];
                                    $sold_shop_total_received = 0;
                                    $sold_shop_total_paid     = 0;
                                    $sold_shop_balance        = 0;
                                    $reserved_shop_marlas     = 0;
                                    $vacant_shop_marlas       = 0;
                                    $registered_shop_marlas   = 0;

                                    /*
                                     * Income
                                     * */

                                    // Project income this month
                                    $total_project_income_this_month = 0;
                                    // Partner investment this month
                                    $total_partner_investment_this_month = 0;
                                    // Investor investment this month
                                    $total_investor_investment_this_month = 0;

                                    // Project income till date
                                    $total_project_income_till_date = 0;
                                    // Partner investment till date
                                    $total_partner_investment_till_date = 0;
                                    // Investor investment till date
                                    $total_investor_investment_till_date = 0;

                                    /*
                                     * Expenses
                                     * */

                                    // Project expenses this month
                                    $total_project_expenses_this_month = 0;
                                    // Dealers expenses this month
                                    $total_dealer_expenses_this_month = 0;
                                    // Land payment expenses this month
                                    $total_land_payment_expenses_this_month = 0;
                                    // Partner expenses this month
                                    $total_partner_expenses_this_month = 0;
                                    // Investor expenses this month
                                    $total_investor_expenses_this_month = 0;
                                    // Head Office expense this month
                                    $head_office_expense_share_this_month = 0;

                                    // Project expenses till date
                                    $total_project_expenses_till_date = 0;
                                    // Dealers expenses till date
                                    $total_dealer_expenses_till_date = 0;
                                    // Land payment expenses till date
                                    $total_land_payment_expenses_till_date = 0;
                                    // Partner expenses till date
                                    $total_partner_expenses_till_date = 0;
                                    // Investor expenses till date
                                    $total_investor_expenses_till_date = 0;
                                    // Head Office expense till date
                                    $head_office_expense_share_till_date = 0;

                                    // get all plots against project
                                    $sql = "select id, account_id, plot_type, size, rate_per_marla, status from plots where project_id=\"" . mysql_real_escape_string($project_id) . "\"";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            $id = $rs["id"];

                                            $saleable_marlas += $rs["size"];

                                            // Residential plot Accounts
                                            if ($rs['plot_type'] == "Residential") {
                                                $plot_accounts[] = $rs['account_id'];
                                            }
                                            // Residential plots
                                            if ($rs['plot_type'] == "Residential" && $rs['status'] != "VACANT") {
                                                $sold_plot_marlas += $rs["size"];
                                                $sold_plot_total  += $rs["size"] * $rs['rate_per_marla'];

                                                /*
                                                 * Calculate features price and add in total
                                                 * */
                                                $sql2 = "select feature_id from plots_features where plot_id = '$id'";
                                                $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                                                while ($rs2 = mysql_fetch_array($result2)) {
                                                    $feature_id = $rs2["feature_id"];

                                                    $sql3 = "select price, price_type from lookup_plot_features where id = '$feature_id'";
                                                    $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                                                    while ($rs3 = mysql_fetch_array($result3)) {
                                                        $feature_price     = $rs3["price"];
                                                        $feature_pricetype = $rs3["price_type"];

                                                        if ($feature_pricetype == "F") {
                                                            $sold_plot_total += $feature_price;
                                                        } elseif ($feature_pricetype == "P") {
                                                            $pval            = ($sold_plot_total * $feature_price) / 100;
                                                            $sold_plot_total += $pval;
                                                        }
                                                    }
                                                }

                                            }

                                            // Shop plot Accounts
                                            if ($rs['plot_type'] == "Shop") {
                                                $shop_accounts[] = $rs['account_id'];
                                            }
                                            // Shop plots
                                            if ($rs['plot_type'] == "Shop" && $rs['status'] != "VACANT") {
                                                $sold_shop_marlas += $rs["size"];
                                                $sold_shop_total  += $rs["size"] * $rs['rate_per_marla'];

                                                /*
                                                 * Calculate features price and add in total
                                                 * */
                                                $sql2 = "select feature_id from plots_features where plot_id = '$id'";
                                                $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                                                while ($rs2 = mysql_fetch_array($result2)) {
                                                    $feature_id = $rs2["feature_id"];

                                                    $sql3 = "select price, price_type from lookup_plot_features where id = '$feature_id'";
                                                    $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                                                    while ($rs3 = mysql_fetch_array($result3)) {
                                                        $feature_price     = $rs3["price"];
                                                        $feature_pricetype = $rs3["price_type"];

                                                        if ($feature_pricetype == "F") {
                                                            $sold_shop_total += $feature_price;
                                                        } elseif ($feature_pricetype == "P") {
                                                            $pval            = ($sold_shop_total * $feature_price) / 100;
                                                            $sold_shop_total += $pval;
                                                        }
                                                    }
                                                }
                                            }

                                            // Residential reserved
                                            if ($rs['plot_type'] == "Residential" && $rs['status'] == "RESERVED") {
                                                $reserved_plot_marlas += $rs["size"];
                                            }
                                            // Shop reserved
                                            if ($rs['plot_type'] == "Shop" && $rs['status'] == "RESERVED") {
                                                $reserved_shop_marlas += $rs["size"];
                                            }

                                            // Residential vacant
                                            if ($rs['plot_type'] == "Residential" && $rs['status'] == "VACANT") {
                                                $vacant_plot_marlas += $rs["size"];
                                            }
                                            // Shop vacant
                                            if ($rs['plot_type'] == "Shop" && $rs['status'] == "VACANT") {
                                                $vacant_shop_marlas += $rs["size"];
                                            }

                                            // Residential registered
                                            if ($rs['plot_type'] == "Residential" && $rs['status'] == "REGISTERED") {
                                                $registered_plot_marlas += $rs["size"];
                                            }
                                            // Shop registered
                                            if ($rs['plot_type'] == "Shop" && $rs['status'] == "REGISTERED") {
                                                $registered_shop_marlas += $rs["size"];
                                            }
                                        }
                                    }

                                    // get total received & paid against plots - CUSTOMERS TRANSACTIONS
                                    if ($incPending != "Y") {
                                        $pendingAuthHand = "AND ((t.transaction_type='PAYMENT' AND t.auth_status='AUTH') OR (t.transaction_type='RECEIPT' AND t.handover_status='HANDOVER'))";
                                    } else {
                                        $pendingAuthHand = "";
                                    }

                                    if ($incPendingClear != "Y") {
                                        $pendingClearance = "AND ((t.transaction_type =  'RECEIPT' AND d.voucher_type =  'BANK' AND d.clearance_status =  'CLEARED') OR (t.transaction_type =  'RECEIPT' AND d.voucher_type =  'CASH') OR (t.transaction_type =  'PAYMENT' AND d.voucher_type IN ('BANK',  'CASH')))";
                                    } else {
                                        $pendingClearance = "";
                                    }

                                    $sql = "SELECT 
                                            d.account_id AS account_id,
                                            d.amount AS amount,
                                            t.transaction_type AS transaction_type
                                            FROM projects p, transactions t, transactions_details d, accounts a 
                                            WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\" 
                                            AND a.master_id = \"" . $CFG_ACCOUNT_CUSTOMERS . "\"
                                            AND t.project_id = p.id 
                                            AND t.id = d.transaction_id 
                                            AND t.account_id = a.id 
                                            AND t.invoice_date <= '$last_day_this_month'
                                            $pendingAuthHand 
                                            $pendingClearance";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            if (in_array($rs['account_id'], $plot_accounts) && $rs['transaction_type'] == "RECEIPT") {
                                                $sold_plot_total_received += $rs['amount'];
                                            }
                                            if (in_array($rs['account_id'], $plot_accounts) && $rs['transaction_type'] == "PAYMENT") {
                                                $sold_plot_total_paid += $rs['amount'];
                                            }
                                            if (in_array($rs['account_id'], $shop_accounts) && $rs['transaction_type'] == "RECEIPT") {
                                                $sold_shop_total_received += $rs['amount'];
                                            }
                                            if (in_array($rs['account_id'], $shop_accounts) && $rs['transaction_type'] == "PAYMENT") {
                                                $sold_shop_total_paid += $rs['amount'];
                                            }
                                        }
                                    }
                                    $sold_plot_total_received -= $sold_plot_total_paid;
                                    $sold_shop_total_received -= $sold_shop_total_paid;

                                    /*
                                     * Income
                                     * */

                                    if ($incPending != "Y") {
                                        $pendingAuthHand = "AND (t.transaction_type='RECEIPT' AND t.handover_status='HANDOVER')";
                                    } else {
                                        $pendingAuthHand = "AND t.transaction_type='RECEIPT'";
                                    }

                                    if ($incPendingClear != "Y") {
                                        $pendingClearance = "AND ((t.transaction_type = 'RECEIPT' AND d.voucher_type = 'BANK' AND d.clearance_status = 'CLEARED') OR (t.transaction_type = 'RECEIPT' AND d.voucher_type = 'CASH'))";
                                    } else {
                                        $pendingClearance = "";
                                    }

                                    // get total received against project this month - INCLUDING PARTNER AND INVESTOR PAYMENTS SEPARATELY
                                    $sql = "SELECT  
                                            SUM(CASE WHEN (a.master_id <> \"" . $CFG_ACCOUNT_INVESTORS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_PARTNERS . "\") THEN d.amount ELSE 0 END) AS total_project_income_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_INVESTORS . "\" THEN d.amount ELSE 0 END) AS total_investor_investment_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_PARTNERS . "\" THEN d.amount ELSE 0 END) AS total_partner_investment_this_month
                                            FROM projects p, transactions t, transactions_details d, accounts a 
                                            WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\"
                                            AND (t.invoice_date BETWEEN \"" . $first_day_this_month . "\" AND \"" . $last_day_this_month . "\")
                                            AND t.project_id = p.id 
                                            AND t.id = d.transaction_id 
                                            AND t.account_id = a.id 
                                            $pendingAuthHand 
                                            $pendingClearance";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            $total_project_income_this_month      = $rs['total_project_income_this_month'];
                                            $total_partner_investment_this_month  = $rs['total_partner_investment_this_month'];
                                            $total_investor_investment_this_month = $rs['total_investor_investment_this_month'];
                                        }
                                    }

                                    // get total received against project till last month - INCLUDING PARTNER AND INVESTOR PAYMENTS SEPARATELY
                                    $sql = "SELECT  
                                            SUM(CASE WHEN (a.master_id <> \"" . $CFG_ACCOUNT_INVESTORS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_PARTNERS . "\") THEN d.amount ELSE 0 END) AS total_project_income_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_INVESTORS . "\" THEN d.amount ELSE 0 END) AS total_investor_investment_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_PARTNERS . "\" THEN d.amount ELSE 0 END) AS total_partner_investment_till_date
                                            FROM projects p, transactions t, transactions_details d, accounts a 
                                            WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\"
                                            AND t.invoice_date <= \"" . $last_day_last_month . "\"
                                            AND t.project_id = p.id 
                                            AND t.id = d.transaction_id 
                                            AND t.account_id = a.id 
                                            $pendingAuthHand 
                                            $pendingClearance";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows = mysql_num_rows($result);
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            $total_project_income_till_date      = $rs['total_project_income_till_date'];
                                            $total_partner_investment_till_date  = $rs['total_partner_investment_till_date'];
                                            $total_investor_investment_till_date = $rs['total_investor_investment_till_date'];
                                        }
                                    }

                                    /*
                                     * Expenses
                                     * */

                                    if ($incPending != "Y") {
                                        $pendingAuthHand = "AND (t.transaction_type='PAYMENT' AND t.auth_status='AUTH')";
                                    } else {
                                        $pendingAuthHand = "AND t.transaction_type='PAYMENT'";
                                    }

                                    if ($incPendingClear != "Y") {
                                        $pendingClearance = "AND ((t.transaction_type =  'PAYMENT' AND d.voucher_type IN ('BANK',  'CASH')))";
                                    } else {
                                        $pendingClearance = "";
                                    }

                                    // get total payments against project this month
                                    $sql = "SELECT  
                                            SUM(CASE WHEN (a.master_id <> \"" . $CFG_ACCOUNT_DEALERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_LANDOWNERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_PARTNERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_INVESTORS . "\") THEN d.amount ELSE 0 END) AS total_project_expenses_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_DEALERS . "\" THEN d.amount ELSE 0 END) AS total_dealer_expenses_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_LANDOWNERS . "\" THEN d.amount ELSE 0 END) AS total_land_payment_expenses_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_PARTNERS . "\" THEN d.amount ELSE 0 END) AS total_partner_expenses_this_month,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_INVESTORS . "\" THEN d.amount ELSE 0 END) AS total_investor_expenses_this_month
                                            FROM projects p, transactions t, transactions_details d, accounts a 
                                            WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\"
                                            AND (t.invoice_date BETWEEN \"" . $first_day_this_month . "\" AND \"" . $last_day_this_month . "\")
                                            AND t.project_id = p.id 
                                            AND t.id = d.transaction_id 
                                            AND t.account_id = a.id 
                                            $pendingAuthHand 
                                            $pendingClearance";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows                  = mysql_num_rows($result);
                                    $total_expense_this_month = 0;
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            $total_project_expenses_this_month      = $rs['total_project_expenses_this_month'];
                                            $total_dealer_expenses_this_month       = $rs['total_dealer_expenses_this_month'];
                                            $total_land_payment_expenses_this_month = $rs['total_land_payment_expenses_this_month'];
                                            $total_partner_expenses_this_month      = $rs['total_partner_expenses_this_month'];
                                            $total_investor_expenses_this_month     = $rs['total_investor_expenses_this_month'];
                                        }
                                        // Calculate total expense
                                        $total_expense_this_month = $total_project_expenses_this_month + $total_dealer_expenses_this_month + $total_land_payment_expenses_this_month + $total_partner_expenses_this_month + $total_investor_expenses_this_month;
                                        // Head Office Expense this month
                                        $head_office_expense_share_this_month = $head_office_total_expense['total_head_office_expenses_this_month'];
                                    }

                                    // get total payments against project till last month
                                    $sql = "SELECT  
                                            SUM(CASE WHEN (a.master_id <> \"" . $CFG_ACCOUNT_DEALERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_LANDOWNERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_PARTNERS . "\" AND a.master_id <> \"" . $CFG_ACCOUNT_INVESTORS . "\") THEN d.amount ELSE 0 END) AS total_project_expenses_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_DEALERS . "\" THEN d.amount ELSE 0 END) AS total_dealer_expenses_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_LANDOWNERS . "\" THEN d.amount ELSE 0 END) AS total_land_payment_expenses_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_PARTNERS . "\" THEN d.amount ELSE 0 END) AS total_partner_expenses_till_date,
                                            SUM(CASE WHEN a.master_id = \"" . $CFG_ACCOUNT_INVESTORS . "\" THEN d.amount ELSE 0 END) AS total_investor_expenses_till_date
                                            FROM projects p, transactions t, transactions_details d, accounts a 
                                            WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\"
                                            AND t.invoice_date <= \"" . $last_day_last_month . "\"
                                            AND t.project_id = p.id 
                                            AND t.id = d.transaction_id 
                                            AND t.account_id = a.id 
                                            $pendingAuthHand 
                                            $pendingClearance";
                                    $result = mysql_query($sql, $conn) or die(mysql_error());
                                    $numrows                 = mysql_num_rows($result);
                                    $total_expense_till_date = 0;
                                    if ($numrows > 0) {
                                        while ($rs = mysql_fetch_array($result)) {
                                            $total_project_expenses_till_date      = $rs['total_project_expenses_till_date'];
                                            $total_dealer_expenses_till_date       = $rs['total_dealer_expenses_till_date'];
                                            $total_land_payment_expenses_till_date = $rs['total_land_payment_expenses_till_date'];
                                            $total_partner_expenses_till_date      = $rs['total_partner_expenses_till_date'];
                                            $total_investor_expenses_till_date     = $rs['total_investor_expenses_till_date'];
                                        }
                                        // Calculate total expense
                                        $total_expense_till_date = $total_project_expenses_till_date + $total_dealer_expenses_till_date + $total_land_payment_expenses_till_date + $total_partner_expenses_till_date + $total_investor_expenses_till_date;
                                        // Head Office Expense till date
                                        $head_office_expense_share_till_date = $head_office_total_expense['total_head_office_expenses_till_date'];
                                    }
                                    ?>
                                    <br/>
                                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table border="0" width="100%" cellpadding="5" cellspacing="0"
                                                       align="center" id="reclist">
                                                    <tbody>
                                                    <tr id="listhead">
                                                        <td style="font-size: 14px;">Project</td>
                                                        <td colspan="4" align="left" style="font-size: 14px;"><?php echo getProjectName($project_id); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td align="right" style="font-size: 14px; font-style: italic;"><strong>Total Amount</strong></td>
                                                        <td align="right" style="font-size: 14px; font-style: italic;"><strong>Received Amount</strong></td>
                                                        <td align="right" style="font-size: 14px; font-style: italic;"><strong>Balance Amount</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Total Saleable Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($saleable_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Sold Plot Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($sold_plot_marlas, 2, ".", ","); ?></td>
                                                        <td align="right"><?php echo formatCurrency($sold_plot_total); ?></td>
                                                        <td align="right"><?php echo formatCurrency($sold_plot_total_received); ?></td>
                                                        <td align="right">
                                                            <?php
                                                                $sold_plot_balance = ($sold_plot_total - $sold_plot_total_received);
                                                                echo formatCurrency($sold_plot_balance);
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Sold Shop Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($sold_shop_marlas, 2, ".", ","); ?></td>
                                                        <td align="right"><?php echo formatCurrency($sold_shop_total); ?></td>
                                                        <td align="right"><?php echo formatCurrency($sold_shop_total_received); ?></td>
                                                        <td align="right">
                                                            <?php
                                                                $sold_shop_balance = ($sold_shop_total - $sold_shop_total_received);
                                                                echo formatCurrency($sold_shop_balance);
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr id="listhead">
                                                        <td style="font-size: 14px; font-style: italic;">Total</td>
                                                        <td align="right"><?php echo number_format($sold_plot_marlas + $sold_shop_marlas, 2, ".", ","); ?></td>
                                                        <td align="right"><?php echo formatCurrency(($sold_plot_total + $sold_shop_total)); ?></td>
                                                        <td align="right"><?php echo formatCurrency(($sold_plot_total_received + $sold_shop_total_received)); ?></td>
                                                        <td align="right"><?php echo formatCurrency(($sold_plot_balance + $sold_shop_balance)) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Reserve Plot Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($reserved_plot_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Vacant Plot Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($vacant_plot_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Reserve Shop Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($reserved_shop_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Vacant Shop Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($vacant_shop_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Registered Plot Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($registered_plot_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; font-style: italic;">Registered Shop Marlas</strong></td>
                                                        <td align="right"><?php echo number_format($registered_shop_marlas, 2, ".", ","); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" colspan="2"><strong style="font-size: 14px;"><?php echo date('M, Y', strtotime($report_month_from)); ?> - <?php echo date('M, Y', strtotime($report_month_to)); ?>
                                                                (<?php echo date('d/m/Y', strtotime($first_day_this_month)); ?>
                                                                - <?php echo date("d/m/Y", strtotime($last_day_this_month)); ?>)</strong></td>
                                                        <td>&nbsp;</td>
                                                        <td align="center" colspan="2"><strong style="font-size: 14px;">Till This month (START
                                                                - <?php echo date('d/m/Y', strtotime($last_day_last_month)); ?>)</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Project Income</td>
                                                        <td align="right"><?php echo formatCurrency($total_project_income_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Project Income</td>
                                                        <td align="right"><?php echo formatCurrency($total_project_income_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Partner's Investment</td>
                                                        <td align="right"><?php echo formatCurrency($total_partner_investment_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Partner's Investment</td>
                                                        <td align="right"><?php echo formatCurrency($total_partner_investment_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Investor's Investment</td>
                                                        <td align="right"><?php echo formatCurrency($total_investor_investment_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Investor's Investment</td>
                                                        <td align="right"><?php echo formatCurrency($total_investor_investment_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Total Income</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency(($total_project_income_this_month + $total_partner_investment_this_month + $total_investor_investment_this_month)); ?></strong>
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Total Income</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency(($total_project_income_till_date + $total_partner_investment_till_date + $total_investor_investment_till_date)); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Project Expense</td>
                                                        <td align="right"><?php echo formatCurrency($total_project_expenses_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Project Expense</td>
                                                        <td align="right"><?php echo formatCurrency($total_project_expenses_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Commission</td>
                                                        <td align="right"><?php echo formatCurrency($total_dealer_expenses_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Commission</td>
                                                        <td align="right"><?php echo formatCurrency($total_dealer_expenses_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Land Payments</td>
                                                        <td align="right"><?php echo formatCurrency($total_land_payment_expenses_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Land Payments</td>
                                                        <td align="right"><?php echo formatCurrency($total_land_payment_expenses_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Partner Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($total_partner_expenses_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Partner Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($total_partner_expenses_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Investor Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($total_investor_expenses_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Investor Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($total_investor_expenses_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Head Office Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($head_office_expense_share_this_month); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>Head Office Expenses</td>
                                                        <td align="right"><?php echo formatCurrency($head_office_expense_share_till_date); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Total Expenses</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency($total_expense_this_month + $head_office_expense_share_this_month); ?></strong>
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Total Expenses</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency($total_expense_till_date + $head_office_expense_share_till_date); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Balance</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency(($total_project_income_this_month + $total_partner_investment_this_month + $total_investor_investment_this_month) - ($total_expense_this_month + $head_office_expense_share_this_month)); ?></strong>
                                                        </td>
                                                        <td>&nbsp;</td>
                                                        <td><strong style="font-size: 14px; text-decoration: underline;">Balance</strong></td>
                                                        <td align="right"><strong
                                                                    style="font-size: 14px; text-decoration: underline;"><?php echo formatCurrency(($total_project_income_till_date + $total_partner_investment_till_date + $total_investor_investment_till_date) - ($total_expense_till_date + $head_office_expense_share_till_date)); ?></strong>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?
                                } else {
                                    echo "<span style='color: #ff0000;'>Please select a project and date range.</span>";
                                }
                            ?>
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