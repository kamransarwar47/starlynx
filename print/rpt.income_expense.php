<?
    define("MODULE_ID", "RPT008");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    // print output
    if ($output == 'print') {

    $print = false;

    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        $print = true;
        system_log(PRNT, "Loaded for printing.", $userInfo["id"]);
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
<? include("common/header.invoice.php"); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <? if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                <?= showError() ?>
                <p class="box_title">
                    Income / Expense Statement
                    <span class="notes"
                          style="float: right; text-align: right; font-style: italic;">Printed on <?= date("d-m-Y", time()) ?></span>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong style="font-size: 14px;"><?= getProjectName($project_id) ?></strong><br/>
                    <?= date("d-m-Y", strtotime($dtFrom)) ?> to <?= date("d-m-Y", strtotime($dtTo)) ?>
                    <?
                        switch ($rptLevel) {
                            case "TOP":
                                echo " (Top Level)";
                                break;

                            case "SUB":
                                echo " (Sub Accounts Level)";
                                break;

                            case "FULL":
                                echo " (Full Details)";
                                break;
                        }

                        if ($incPending == "Y") {
                            echo "<br /><span class='notes' style='font-size: 10px;'>Includes pending authorizations and handovers.</span>";
                        }

                        if ($incPendingClear == "Y") {
                            echo "<br /><span class='notes' style='font-size: 10px;'>Includes pending clearances.</span>";
                        }
                    ?>
                </p>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <?
                                if (((!empty($dtFrom) && !empty($dtTo)) || (!empty($project_id) && !empty($dtFrom) && !empty($dtTo))) && !empty($rptLevel)) {
                                    include($printmod . "_" . $rptLevel . ".php");
                                } else {
                                    echo "<span style='color: #ff0000;'>Please select a (date range with project or date range) and report level.</span>";
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            <? } ?>
        </td>
    </tr>
</table>
<?
    if ($print) {
        ?>
        <script type="text/javascript">
            window.print();
        </script>
        <?
    }
    // output excel sheet
    } else {

    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        system_log(PRNT, "Loaded for download.", $userInfo["id"]);

        if (((!empty($dtFrom) && !empty($dtTo)) || (!empty($project_id) && !empty($dtFrom) && !empty($dtTo))) && !empty($rptLevel)) {
            include($printmod . "_" . $rptLevel . ".php");
        } else {
            ?>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <?
                            echo "<span style='color: #ff0000;'>Please select a (date range with project or date range) and report level.</span>";
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}
?>
</body>
</html>