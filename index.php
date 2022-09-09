<?php
    // set default timezone
    date_default_timezone_set('Asia/Karachi');
    // Pre-page
    $_self     = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $_parts    = pathinfo($_self);
    $_siteRoot = $_parts["dirname"] . "/";

    include("common/common.start.php");

    // Security
    if (!empty($ss)) {
        include("common/check_session.php");

        // User Info
        $userInfo = getSignedUserInfo($ss);
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>StarLynx - Star Developers</title>
    <link rel="stylesheet" href="css/main.css?v=1.1" media="screen, handheld, tv"/>
    <link rel="stylesheet" href="css/menu.css?v=1.1" media="screen, handheld, tv"/>
    <link rel="stylesheet" href="css/cupertino/jquery-ui-1.8.18.custom.css" media="screen, handheld, tv"/>
    <link rel="stylesheet" href="assets/select2/css/select2.min.css" media="screen, handheld, tv"/>
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput-1.3.js"></script>
    <script type="text/javascript" src="assets/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="js/common.js?v=1.1"></script>
    <?php
        if (!empty($ss) && empty($mod)) {
            ?>
            <!-- Chartist -->
            <link rel="stylesheet" href="assets/plugins/chartist/css/chartist.min.css">
            <link rel="stylesheet" href="assets/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
            <!-- DataTables -->
            <link href="assets/plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">
            <!-- Custom Stylesheet -->
            <link href="css/style.css?v=1.1" rel="stylesheet">
            <?php
        }
    ?>
</head>
<body>
<div id="<?php echo (!empty($ss) && empty($mod)) ? 'wrapper-dashboard' : 'wrapper'; ?>">
    <? include("common/header.php"); ?>
    <div id="<?php echo (!empty($ss) && empty($mod)) ? 'workspace-dashboard' : 'workspace'; ?>">
        <?
            if (empty($ss)) {
                include("common/login_screen.php");
            } else {
                if (empty($mod)) {
                    include("mod/welcome.php");
                } else {
                    if (!@include("mod/$mod.php")) {
                        setMessage("Requested module couldn't be loaded. Either it's not present or you've got a broken link.<br /><br />Please contact your system administrator.");
                        include("common/404.php");
                    }
                }
            }
        ?>
    </div>
    <? include("common/footer.php"); ?>
</div>
<?php
    if (!empty($ss) && empty($mod)) {
        ?>
        <script src="assets/plugins/common/common.min.js"></script>
        <!-- ChartistJS -->
        <script src="assets/plugins/chartist/js/chartist.min.js"></script>
        <script src="assets/plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>
        <!-- Chartjs -->
        <script src="assets/plugins/chart.js/Chart.bundle.min.js"></script>
        <!-- Circle progress -->
        <script src="assets/plugins/circle-progress/circle-progress.min.js"></script>
        <!-- Morrisjs -->
        <script src="assets/plugins/raphael/raphael.min.js"></script>
        <script src="assets/plugins/morris/morris.min.js"></script>
        <!-- DataTables -->
        <script src="assets/plugins/tables/js/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
        <script src="assets/plugins/tables/js/datatable-init/datatable-basic.min.js"></script>
        <!-- Dashboard Data -->
        <script src="assets/plugins/dashboard/dashboard.js?v=1.1"></script>
        <?php
    }
?>
</body>
</html>
<?
    // Post page
    include("common/common.end.php");
?>
