<?
    define("MODULE_ID", "RPT006");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);

    $print = false;

    if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) {
                    $print = true;
                    system_log(PRNT, "Loaded for printing.", $userInfo["id"]);
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StarLynx - Star Developers</title>
<link rel="stylesheet" href="css/print.css" media="screen, print" />
</head>
<body>
<? include("common/header.invoice.php"); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                <?=showError()?>
                <p class="box_title">
                    Customers List
                    <span class="notes" style="float: right; text-align: right; font-style: italic;">Printed on <?=date("d-m-Y", time())?></span>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong><?=getProjectName($project_id)?></strong>
                </p>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr><td>
                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                            <tr id="listhead">
                                <? if($chkPlist == "Y") { ?>
                                    <td width="20%">Plots</td>
                                    <td width="45%">Customer</td>
                                    <td width="15%">City</td>
                                <? } else { ?>
                                    <td width="65%">Customer</td>
                                    <td width="15%">City</td>
                                <? } ?>
                                <td width="20%">Contact</td>
                            </tr>
                                <?
                                    $wxtra = "";

                                    if(!empty($project_id)) {
                                        $wxtra = "and p.project_id = '$project_id'";
                                    }

                                        $sql = "SELECT DISTINCT p.customer_id, c . *
                                                FROM plots p, customers c
                                                WHERE p.customer_id = c.id
                                                $wxtra
                                                ORDER BY c.full_name";
                                        $result = mysql_query($sql, $conn) or die(mysql_error());
                                        $numrows = mysql_num_rows($result);

                                        if($numrows > 0) {
                                            while($rs = mysql_fetch_array($result)) {
                                                $customer_id = $rs["customer_id"];
                                                $full_name = $rs["full_name"];
                                                $father_name = $rs["father_name"];
                                                $street = $rs["street"];
                                                $city = getCityName($rs["city"]);
                                                $zip = $rs["zip"];
                                                $state = $rs["state"];
                                                $country = getCountryName($rs["country"]);
                                                $phone_1 = $rs["phone_1"];
                                                $phone_2 = $rs["phone_2"];
                                                $mobile_1 = $rs["mobile_1"];
                                                $mobile_2 = $rs["mobile_2"];
                                                $fax_1 = $rs["fax_1"];
                                                $fax_2 = $rs["fax_2"];
                                                $email_1 = $rs["email_1"];
                                                $email_2 = $rs["email_2"];
                                                $id_num = $rs["id_num"];
                                                $date_of_birth = $rs["date_of_birth"];

                                                $phones = array($phone_1, $phone_2, $mobile_1, $mobile_2);
                                                $emails = array($email_1, $email_2);

                                                if($chkPlist == "Y") {
                                                    $sql2 = "select project_id, plot_number, plot_type from plots where customer_id='$customer_id'";
                                                    $result2 = mysql_query($sql2, $conn) or die(mysql_error());
                                                    $numrows2 = mysql_num_rows($result2);
                                                    $plots = array();
                                                    $plist = "";

                                                    if($numrows2 > 0) {
                                                        while($rs2 = mysql_fetch_array($result2)) {
                                                            $project = getProjectName($rs2["project_id"]);

                                                            if($rs2["plot_type"] == "Residential") {
                                                                $plots[$project][] = "Plot #".$rs2["plot_number"];
                                                            } elseif ($rs2["plot_type"] == "Shop") {
                                                                $plots[$project][] = "Shop #".$rs2["plot_number"];
                                                            }
                                                        }
                                                        //print_r($plots);
                                                        foreach($plots as $prj => $list) {
                                                            $plist .= "<strong>$prj</strong><br />".implode(", ", $list)."<br />";
                                                        }
                                                    }
                                                }

                                                echo "<tr>";

                                                if(!empty($plist)) {
                                                    echo "<td valign='top'>$plist</td>";
                                                }

                                                echo "<td valign='top'>";
                                                echo "<strong style='font-size: 14px;'>$full_name</strong><br />";
                                                echo "s/w/d of $father_name<br />";
                                                echo "<span class='notes'>$street<br />$city, $country</span>";
                                                echo "</td>";
                                                echo "<td valign='top'>$city</td>";
                                                echo "<td valign='top'>";
                                                echo formatContacts($phones, "<br /> ");

                                                if(sizeof($emails) > 0) {
                                                    echo "<br />";
                                                    echo formatContacts($emails, "<br /> ");
                                                }

                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                ?>
                        </table>
                   </td></tr>
                   </table>
            <? } ?>
        </td>
    </tr>
</table>
<?
    if($print) {
?>
    <script type="text/javascript">
        window.print();
    </script>
<?
    }
?>
</body>
</html>