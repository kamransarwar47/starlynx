<?
    define("MODULE_ID", "ACT002");
    include("common/check_access_ext.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
    $print = false;
    $stamp = "";
    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
    if (!empty($voucher_number)) {
        $sql = "select * from transactions where voucher_id=\"" . mysql_real_escape_string($voucher_number) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            while ($rs = mysql_fetch_array($result)) {
                $id               = $rs["id"];
                $project_id       = $rs["project_id"];
                $account_id       = $rs["account_id"];
                $voucher_id       = $rs["voucher_id"];
                $transaction_type = $rs["transaction_type"];
                $notes            = $rs["notes"];
                $invoice_date     = $rs["invoice_date"];
                $auth_status      = $rs["auth_status"];
                $auth_by          = $rs["auth_by"];
                $auth_on          = $rs["auth_on"];
                $handover_status  = $rs["handover_status"];
                $received_by      = $rs["received_by"];
                $received_on      = $rs["received_on"];
                $print_count      = $rs["print_count"];
                $printed_by       = $rs["printed_by"];
                $printed_on       = $rs["printed_on"];
                if (($transaction_type == "PAYMENT" && $auth_status == "AUTH") || $transaction_type == "RECEIPT") {
                    $print = true;
                }
                if ($print) {
                    if ($print_count > 0) {
                        system_log(REPRINT, "$voucher_number loaded for reprinting.", $userInfo["id"]);
                        $stamp = "<div class='stamp'><img src='images/stamp-duplicate.png' /></div>";
                    } else {
                        system_log(PRNT, "$voucher_number loaded for printing.", $userInfo["id"]);
                    }
                } else {
                    setMessage("$voucher_number is not authorized for printing.");
                    system_log(PRNT, "Operation failed. [Reason: $voucher_number is not authorized for printing.]", $userInfo["id"]);
                }
            }
        } else {
            setMessage("$voucher_number not found.");
            system_log(PRNT, "Operation failed. [Reason: $voucher_number not found.]", $userInfo["id"]);
        }
    } else {
        setMessage("Please provide a valid voucher number.");
        system_log(PRNT, "Operation failed. [Reason: No voucher was supplied.]", $userInfo["id"]);
    }
    if (!$print) {
        header("Location: index.php?ss=$ss");
        exit;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>StarLynx - Star Developers</title>
    <link rel="stylesheet" href="css/print.css" media="screen, print"/>
</head>
<body>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" id="invoice">
    <tr>
        <td>
            <? include("common/header.invoice.php"); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= $stamp ?>
            <?
                $print_user_type = "RECIPIENT";
                include("common/body.invoice.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?
                $_signature = "Accountant";
                $_copyof    = "Recepient Copy";
                include("common/footer.invoice.php");
            ?>
        </td>
    </tr>
</table>
<!-- table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="margin-bottom: 20px;">
    <tr>
        <td style="border-bottom: 1px dashed #000; page-break-after: always;">&nbsp;</td>
    </tr>
</table -->
<? if ($drows > 4) { ?>
    <h1 style="margin:0; padding: 0; page-break-before: always; width: 100%;"></h1>
<? } else { ?>
    <h1 style="border-bottom: 1px dashed #000; width: 100%;"></h1>
<? } ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" id="invoice">
    <tr>
        <td>
            <? include("common/header.invoice.php"); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= $stamp ?>
            <?
                $print_user_type = "STAFF";
                include("common/body.invoice.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?
                $_signature = "Recepient";
                $_copyof    = "Office Copy";
                include("common/footer.invoice.php");
            ?>
        </td>
    </tr>
</table>
<?
    }
    if ($print) {
        $sql = "update
                    transactions
                set
                    print_count = (print_count+1),
                    printed_by = \"" . $userInfo["id"] . "\",
                    printed_on = NOW()
                where
                    id = '$id'
                ";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        ?>
        <script type="text/javascript">
            window.print();
        </script>
        <?
    }
?>
</body>
</html>