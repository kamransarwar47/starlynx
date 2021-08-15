<?
    define("MODULE_ID", "ACT003");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    if (checkPermission($userInfo["id"], DELETE)) {
        if ($cmd == "DELETE") {
            $cmd = "";

            if (!empty($id)) {

                // check transactions for installments and create installment entries
                $sql = "SELECT t.invoice_date as invoice_date, td.account_id as plot_account_id, td.amount as amount, td.check_installment as check_installment FROM transactions t JOIN transactions_details td ON t.id = td.transaction_id WHERE t.id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $num_rows = mysql_num_rows($result);

                if ($num_rows > 0) {
                    while ($rs = mysql_fetch_assoc($result)) {
                        if($rs['check_installment'] == 'yes') {
                            $sql = "insert into
                                        plots_dues (
                                            plot_id, dues_type, amount, due_on, notes, status, created, created_by
                                        ) values (
                                            \"" . mysql_real_escape_string(getPlotIdByAccountId($rs['plot_account_id'])) . "\",
                                            \"" . mysql_real_escape_string("INSTALMENT") . "\",
                                            \"" . mysql_real_escape_string($rs['amount']) . "\",
                                            \"" . mysql_real_escape_string($rs['invoice_date']) . "\",
                                            \"" . mysql_real_escape_string(" >>> (SYSTEM GENERATED)") . "\",
                                            \"" . mysql_real_escape_string("DUE") . "\",
                                            NOW(),
                                            \"" . $userInfo["id"] . "\"
                                        )";
                            mysql_query($sql, $conn) or die(mysql_error());
                        }
                    }
                }

                // Delete Transaction
                $sql = "delete from transactions where id=\"" . mysql_real_escape_string($id) . "\"";
                mysql_query($sql, $conn) or die(mysql_error());
                if (mysql_affected_rows() > 0) {

                    // Delete Transaction Detail
                    $sql = "delete from transactions_details where transaction_id=\"" . mysql_real_escape_string($id) . "\"";
                    mysql_query($sql, $conn) or die(mysql_error());

                }

                setMessage("Record has been deleted.", true);
                system_log(DELETE, "Invoice ID: $id deleted.", $userInfo["id"]);
                unset($id);

            } else {
                setMessage("Nothing to load...");
                system_log(DELETE, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#dtFrom, #dtTo").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "<?getMinYear()?>:<?=getMaxYear()?>"
        });
    });
</script>
<table border="0" width="90%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/accounts.png" width="16" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Accounts
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">Manage Invoices</p>
            <? if (checkPermission($userInfo["id"], VIEW)) { ?>
                <form method="get" action="index.php" autocomplete="off">
                    <input type="hidden" name="ss" value="<?= $ss ?>"/>
                    <input type="hidden" name="mod" value="<?= $mod ?>"/>
                    <p style="float: left; width: auto;">
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
                        <input type="text" name="dtFrom" id="dtFrom" value="<?= $dtFrom ?>"/>
                    </p>

                    <p style="float: left; width: auto; height: auto;">
                        <strong>To</strong><br/>
                        <input type="text" name="dtTo" id="dtTo" value="<?= $dtTo ?>"/>
                    </p>

                    <p style="float: left; width: auto;">
                        <strong>Invoice #</strong><br/>
                        <input type="text" name="voucher_id" id="voucher_id" size="30" value="<?= $voucher_id ?>"/>
                    </p>

                    <p style="float: left; width: auto; padding-top: 13px;">
                        <input type="submit" value="Submit"/>
                    </p>
                </form>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                       style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="10%">Date</td>
                        <td width="10%">Type</td>
                        <td width="15%">Invoice #</td>
                        <td width="30%">Account</td>
                        <td width="10%" align='right'>Amount</td>
                        <td width="10%" align="center">Status</td>
                        <td width="15%" align='right'>Action</td>
                    </tr>
                    <?
                        if (empty($project_id)) {
                            $special = "AND ((t.transaction_type='PAYMENT' AND t.auth_status='PENDING') OR (t.transaction_type='RECEIPT' AND t.handover_status='PENDING'))";
                        } else {
                            $special = "AND project_id=\"" . mysql_real_escape_string($project_id) . "\"";
                        }

                        if (!empty($voucher_id)) {
                            $special .= " AND t.voucher_id=\"" . mysql_real_escape_string($voucher_id) . "\"";
                        }

                        if (!empty($dtFrom) && !empty($dtTo)) {
                            $special .= " AND t.invoice_date between \"" . mysql_real_escape_string($dtFrom) . "\" AND \"" . mysql_real_escape_string($dtTo) . "\"";
                        }

                        $sql = "SELECT
                                    t.* , sum( d.amount ) AS amt
                                FROM
                                    transactions t, transactions_details d
                                WHERE
                                    t.id = d.transaction_id
                                    $special
                                GROUP BY
                                    t.id
                                ORDER BY
                                    t.id desc";
                        //echo $sql;
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            $canView   = checkPermission($userInfo["id"], VIEW);
                            $canDelete = checkPermission($userInfo["id"], DELETE);

                            while ($rs = mysql_fetch_array($result)) {
                                $id = $rs["id"];
                                //$project_id = $rs["project_id"];
                                $account_id       = $rs["account_id"];
                                $voucher_id       = $rs["voucher_id"];
                                $transaction_type = $rs["transaction_type"];
                                $notes            = $rs["notes"];
                                $invoice_date     = $rs["invoice_date"];
                                $auth_status      = $rs["auth_status"];
                                $auth_by          = $rs["auth_by"];
                                $auth_on          = $rs["auth_on"];
                                $handover_status  = $rs["handover_status"];
                                $handover_by      = $rs["handover_by"];
                                $handover_on      = $rs["handover_on"];
                                $amt              = $rs["amt"];

                                echo "<tr>";
                                echo "<td>$invoice_date</td>";
                                echo "<td>$transaction_type</td>";
                                echo "<td>$voucher_id</td>";
                                echo "<td>" . getAccountTitle($account_id) . "<br /><span class='notes'>$notes</span></td>";
                                echo "<td align='right'>" . number_format($amt, 2, ".", ",") . "</td>";
                                echo "<td align='center'>";

                                if ($transaction_type == "PAYMENT") {
                                    if ($auth_status == "PENDING") {
                                        echo "<span style='color: #ff0000;'>$auth_status</span>";
                                    } else {
                                        echo $auth_status;
                                    }
                                } else {
                                    if ($handover_status == "PENDING") {
                                        echo "<span style='color: #ff0000;'>$handover_status</span>";
                                    } else {
                                        echo $handover_status;
                                    }
                                }

                                echo "</td>";
                                echo "<td align='right'>";

                                if ($canModify) {
                                    if ($transaction_type == "PAYMENT" && $auth_status == "PENDING") {
                                        echo "<a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>Authorize</a> | ";
                                    }

                                    if ($transaction_type == "RECEIPT" && $handover_status == "PENDING") {
                                        echo "<a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>Hand Over</a> | ";
                                    }
                                }

                                if ($canView) {
                                    echo "<a href='index.php?ss=$ss&mod=invoices.view&voucher_number=$voucher_id'>View</a>";
                                }

                                if ($canDelete) {
                                    echo " | <a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&id=$id&cmd=DELETE');\">Delete</a>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='7'>No record found.</td>";
                            echo "</tr>";
                        }
                    ?>
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