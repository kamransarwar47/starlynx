<?
    define("MODULE_ID", "PRJ005");

    include("common/check_access_ext.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if (checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
        if (!empty($plot_id)) {
            $sql = "select * from plots where id=\"" . mysql_real_escape_string($plot_id) . "\"";
            $result = mysql_query($sql, $conn) or die(mysql_error());
            $numrows = mysql_num_rows($result);

            if ($numrows > 0) {
                while ($rs = mysql_fetch_array($result)) {
                    $plot_id        = $rs["id"];
                    $project_id     = $rs["project_id"];
                    $customer_id    = $rs["customer_id"];
                    $dealer_id      = $rs["dealer_id"];
                    $plot_number    = $rs["plot_number"];
                    $plot_type      = $rs["plot_type"];
                    $size           = $rs["size"];
                    $size_type      = $rs["size_type"];
                    $width          = $rs["width"];
                    $length         = $rs["length"];
                    $rate_per_marla = $rs["rate_per_marla"];
                    $status         = $rs["status"];
                    $notes          = $rs["notes"];
                    $plot_value     = $rate_per_marla * $size;
                }

                $print = true;
                system_log(PRNT, "$plot_id loaded for printing.", $userInfo["id"]);
            } else {
                setMessage("Record not found.");
                system_log(PRNT, "Operation failed. [Reason: Record not found.]", $userInfo["id"]);
            }
        } else {
            setMessage("Nothing to load...");
            system_log(PRNT, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

                <?
                $plot_features = "";
                $sql2          = "select feature_id from plots_features where plot_id='$plot_id'";
                $result2 = mysql_query($sql2, $conn) or die(mysql_error());

                while ($rs2 = mysql_fetch_array($result2)) {
                    $feature_id = $rs2["feature_id"];

                    $sql3 = "select title, price, price_type from lookup_plot_features where id='$feature_id'";
                    $result3 = mysql_query($sql3, $conn) or die(mysql_error());

                    while ($rs3 = mysql_fetch_array($result3)) {
                        $feature_title     = $rs3["title"];
                        $feature_price     = $rs3["price"];
                        $feature_pricetype = $rs3["price_type"];

                        if ($feature_pricetype == "F") {
                            $plot_value    += $feature_price;
                            $plot_features .= "$feature_title <span class='notes'>($feature_price)</span><br />";
                        } elseif ($feature_pricetype == "P") {
                            $pval          = ($plot_value * $feature_price) / 100;
                            $plot_value    += $pval;
                            $plot_features .= "$feature_title <span class='notes'>($feature_price%)</span><br />";
                        }
                    }
                }
                ?>

                <p class="box_title">
                    Plot Details
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?= date("d-m-Y", time()) ?></span>
                </p>

                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="tblDetails">
                    <tr>
                        <td valign="top">
                            <p style="font-size: 18px; font-weight: bold;">
                                <strong>Plot Number</strong><br/>
                                <?= $plot_number ?> <span style="font-size: 12px;">(<?= $plot_type ?>)</span>
                            </p>
                        </td>
                        <td valign="top" colspan="2" align="center">
                            <p style="font-size: 18px; font-weight: bold;">

                                <?= getProjectName($project_id) ?>
                            </p>
                        </td>
                        <td valign="top">
                            <p style="font-size: 18px; font-weight: bold;">
                                <strong>Total Value</strong><br/>
                                Rs. <?= number_format($plot_value, 0, ".", ",") ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" width="25%">
                            <p>
                                <strong>Size</strong><br/>
                                <?= $size ?> <?= $size_type ?>
                            </p>
                        </td>
                        <td valign="top" width="25%">
                            <p>
                                <strong>Dimensions (W x L)</strong><br/>
                                <?= $width ?> x <?= $length ?>
                            </p>
                        </td>

                        <td valign="top" width="25%">
                            <p>
                                <strong>Features</strong><br/>
                                <?= $plot_features ?>
                            </p>
                        </td>
                        <td valign="top" width="25%">
                            <p>
                                <strong>Rate per Marla</strong><br/>
                                Rs. <?= number_format($rate_per_marla, 0, ".", ",") ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" colspan="4">
                            <p>
                                <strong>Status & Notes</strong><br/>
                                <?= $status ?>
                                <?
                                    if (!empty($notes)) {
                                        echo " ($notes)";
                                    }
                                ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <p class="box_title">Customer</p>

                <?
                $sql = "select * from customers where id=\"" . mysql_real_escape_string($customer_id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id            = $rs["id"];
                        $full_name     = $rs["full_name"];
                        $father_name   = $rs["father_name"];
                        $street        = $rs["street"];
                        $city          = getCityName($rs["city"]);
                        $zip           = $rs["zip"];
                        $state         = $rs["state"];
                        $country       = getCountryName($rs["country"]);
                        $phone_1       = $rs["phone_1"];
                        $phone_2       = $rs["phone_2"];
                        $mobile_1      = $rs["mobile_1"];
                        $mobile_2      = $rs["mobile_2"];
                        $fax_1         = $rs["fax_1"];
                        $fax_2         = $rs["fax_2"];
                        $email_1       = $rs["email_1"];
                        $email_2       = $rs["email_2"];
                        $id_num        = $rs["id_num"];
                        $date_of_birth = $rs["date_of_birth"];
                    }
                    ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="tblDetails">
                        <tr>
                            <td width="50%" valign="top" colspan="2">
                                <p style="font-size: 18px; font-weight: bold;">
                                    <?= $full_name ?> <br/><strong style="font-size: 12px; font-weight: normal;">Son/Wife of <?= $father_name ?></strong>
                                </p>
                                <p>
                                    <strong>CNIC #:</strong> <?= $id_num ?>
                                    <strong>Date of Birth:</strong> <?= date("d-m-Y", strtotime($date_of_birth)) ?>
                                </p>
                            </td>
                            <td width="50%" valign="top" colspan="2">
                                <p>
                                    <strong>Address</strong><br/>
                                    <?= $street ?>, <?= $city ?>, <?= $country ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Phone(s)</strong><br/>
                                    <?= (!empty($phone_1) ? "$phone_1<br />" : "") ?>
                                    <?= (!empty($phone_2) ? "$phone_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Mobile(s)</strong><br/>
                                    <?= (!empty($mobile_1) ? "$mobile_1<br />" : "") ?>
                                    <?= (!empty($mobile_2) ? "$mobile_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Fax(es)</strong><br/>
                                    <?= (!empty($fax_1) ? "$fax_1<br />" : "") ?>
                                    <?= (!empty($fax_2) ? "$fax_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Email(s)</strong><br/>
                                    <?= (!empty($email_1) ? "$email_1<br />" : "") ?>
                                    <?= (!empty($email_2) ? "$email_2" : "") ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?
                } else {
                    echo "No information found.";
                }
                ?>

                <p class="box_title">Nominee</p>

                <?
                $sql = "select * from customers_nominees where customer_id=\"" . mysql_real_escape_string($customer_id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id            = $rs["id"];
                        $relationship  = $rs["relationship"];
                        $full_name     = $rs["full_name"];
                        $father_name   = $rs["father_name"];
                        $street        = $rs["street"];
                        $city          = getCityName($rs["city"]);
                        $zip           = $rs["zip"];
                        $state         = $rs["state"];
                        $country       = getCountryName($rs["country"]);
                        $phone_1       = $rs["phone_1"];
                        $phone_2       = $rs["phone_2"];
                        $mobile_1      = $rs["mobile_1"];
                        $mobile_2      = $rs["mobile_2"];
                        $fax_1         = $rs["fax_1"];
                        $fax_2         = $rs["fax_2"];
                        $email_1       = $rs["email_1"];
                        $email_2       = $rs["email_2"];
                        $id_num        = $rs["id_num"];
                        $date_of_birth = $rs["date_of_birth"];
                    }
                    ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="tblDetails">
                        <tr>
                            <td width="50%" valign="top" colspan="2">
                                <p style="font-size: 18px; font-weight: bold;">
                                    <?= $full_name ?> <span class="notes">(Relation: <?= $relationship ?>)</span>
                                    <br/><strong style="font-size: 12px; font-weight: normal;">Son/Wife of <?= $father_name ?></strong>
                                </p>
                                <p>
                                    <strong>CNIC #:</strong> <?= $id_num ?>
                                    <strong>Date of Birth:</strong> <?= date("d-m-Y", strtotime($date_of_birth)) ?>
                                </p>
                            </td>
                            <td width="50%" valign="top" colspan="2">
                                <p>
                                    <strong>Address</strong><br/>
                                    <?= $street ?>, <?= $city ?>, <?= $country ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Phone(s)</strong><br/>
                                    <?= (!empty($phone_1) ? "$phone_1<br />" : "") ?>
                                    <?= (!empty($phone_2) ? "$phone_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Mobile(s)</strong><br/>
                                    <?= (!empty($mobile_1) ? "$mobile_1<br />" : "") ?>
                                    <?= (!empty($mobile_2) ? "$mobile_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Fax(es)</strong><br/>
                                    <?= (!empty($fax_1) ? "$fax_1<br />" : "") ?>
                                    <?= (!empty($fax_2) ? "$fax_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Email(s)</strong><br/>
                                    <?= (!empty($email_1) ? "$email_1<br />" : "") ?>
                                    <?= (!empty($email_2) ? "$email_2" : "") ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?
                } else {
                    echo "No information found.";
                }
                ?>

                <p class="box_title">Dealer</p>

                <?
                $sql = "select * from dealers where id=\"" . mysql_real_escape_string($dealer_id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id            = $rs["id"];
                        $full_name     = $rs["full_name"];
                        $father_name   = $rs["father_name"];
                        $street        = $rs["street"];
                        $city          = getCityName($rs["city"]);
                        $zip           = $rs["zip"];
                        $state         = $rs["state"];
                        $country       = getCountryName($rs["country"]);
                        $phone_1       = $rs["phone_1"];
                        $phone_2       = $rs["phone_2"];
                        $mobile_1      = $rs["mobile_1"];
                        $mobile_2      = $rs["mobile_2"];
                        $fax_1         = $rs["fax_1"];
                        $fax_2         = $rs["fax_2"];
                        $email_1       = $rs["email_1"];
                        $email_2       = $rs["email_2"];
                        $id_num        = $rs["id_num"];
                        $date_of_birth = $rs["date_of_birth"];
                    }
                    ?>
                    <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="tblDetails">
                        <tr>
                            <td width="50%" valign="top" colspan="2">
                                <p style="font-size: 18px; font-weight: bold;">
                                    <?= $full_name ?> <br/><strong style="font-size: 12px; font-weight: normal;">Son/Wife of <?= $father_name ?></strong>
                                </p>
                                <p>
                                    <strong>CNIC #:</strong> <?= $id_num ?>
                                    <strong>Date of Birth:</strong> <?= date("d-m-Y", strtotime($date_of_birth)) ?>
                                </p>
                            </td>
                            <td width="50%" valign="top" colspan="2">
                                <p>
                                    <strong>Address</strong><br/>
                                    <?= $street ?>, <?= $city ?>, <?= $country ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Phone(s)</strong><br/>
                                    <?= (!empty($phone_1) ? "$phone_1<br />" : "") ?>
                                    <?= (!empty($phone_2) ? "$phone_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Mobile(s)</strong><br/>
                                    <?= (!empty($mobile_1) ? "$mobile_1<br />" : "") ?>
                                    <?= (!empty($mobile_2) ? "$mobile_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Fax(es)</strong><br/>
                                    <?= (!empty($fax_1) ? "$fax_1<br />" : "") ?>
                                    <?= (!empty($fax_2) ? "$fax_2" : "") ?>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Email(s)</strong><br/>
                                    <?= (!empty($email_1) ? "$email_1<br />" : "") ?>
                                    <?= (!empty($email_2) ? "$email_2" : "") ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?
                } else {
                    echo "No information found.";
                }
                ?>

                <p class="box_title">Agreement</p>

                <?
                $sql = "select * from plots_dues where plot_id=\"" . mysql_real_escape_string($plot_id) . "\" order by due_on asc";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows    = mysql_num_rows($result);
                $total      = "0.00";
                $dues_type  = [];
                $amount     = [];
                $due_on     = [];
                $notes      = [];
                $due_status = [];

                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $dues_type[]  = $rs["dues_type"];
                        $amount[]     = $rs["amount"];
                        $due_on[]     = $rs["due_on"];
                        $notes[]      = $rs["notes"];
                        $due_status[] = $rs["status"];
                    }
                }
                ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist">
                    <tr id="listhead">
                        <td>Type</td>
                        <td>Amount</td>
                        <td>Due On</td>
                        <td>Notes</td>
                        <td>Status</td>
                    </tr>
                    <?
                        if (is_array($dues_type) && !empty($dues_type)) {
                            for ($x = 0; $x < sizeof($dues_type); $x++) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $dues_type[$x] ?>
                                    </td>
                                    <td>
                                        <?= number_format($amount[$x], 0, ".", ",") ?>
                                    </td>
                                    <td>
                                        <?= date("d-m-Y", strtotime($due_on[$x])) ?>
                                    </td>
                                    <td>
                                        <?= $notes[$x] ?>
                                    </td>
                                    <td>
                                        <?= $due_status[$x] ?>
                                    </td>
                                </tr>
                            <? }
                        } ?>
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
?>
</body>
</html>