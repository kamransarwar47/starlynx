<?
    define("MODULE_ID", "RPT007");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
$(document).ready( function (){
    $( "input:button").button();

    $("#btnPrint").click(function(){
        window.open("print.php?ss=<?=$ss?>&printmod=rpt.customers_plots_list&project_id=<?=$project_id?>", 'prn');
    });
});

function reloadList() {
    location.href='index.php?ss=<?=$ss?>&mod=<?=$mod?>&project_id='+$('#project_id').val();
}
</script>
<table border="0" width="60%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/reports.png" width="17" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> Reports</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <? if(checkPermission($userInfo["id"], VIEW)) { ?>
                <?=showError()?>
                <p class="box_title">
                    Customers Plots List

                    <? if(checkPermission($userInfo["id"], PRNT) || checkPermission($userInfo["id"], REPRINT)) { ?>
                        <input type="button" value="Print" id="btnPrint" style="float: right;" />
                    <? } ?>
                </p>

                <p style="float: left; width: auto; height: auto;">
                    <strong>Project</strong><br />
                    <select name="project_id" id="project_id" onChange="javascript: reloadList();">
                    <option value="">-- All --</option>
                    <?
                        $sql = "select id, title from projects order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());

                        while($rs = mysql_fetch_array($result)) {
                            $selected = "";

                            if($project_id == $rs["id"]) {
                                $selected = "selected='selected'";
                            }

                            echo "<option value=\"".$rs["id"]."\" $selected>".$rs["title"]."</option>";
                        }
                    ?>
                    </select>
                </p>
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <?
                        if(!empty($project_id)) {
                    ?>
                            <tr>
                                <td colspan="5">
                                    <p class="box_title"><?=getProjectName($project_id)?></p>
                                </td>
                            </tr>
                    <?
                        }
                    ?>
                    <tr><td>
                        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist" style="margin-top: 10px;">
                            <tr id="listhead">
                                <td width="10%">Plot #</td>
                                <td width="45%">Customer</td>
                                <td width="15%">City</td>
                                <td width="20%">Contact</td>
                                <td width="10%">Status</td>
                            </tr>
                <?
                    $wxtra = "";

                    if(!empty($project_id)) {
                        $wxtra = "and p.project_id = '$project_id'";
                    }

                        $sql = "SELECT
                                    c.full_name, c.father_name, c.street, c.city, c.zip, c.state, c.country, c.phone_1, c.phone_2, c.mobile_1, c.mobile_2, c.email_1, c.email_2,
                                    p.project_id, p.plot_number, p.plot_type, p.status
                                FROM
                                    customers c, plots p
                                WHERE
                                    c.id = p.customer_id
                                    $wxtra
                                ORDER BY p.id, p.plot_type";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);

                        if($numrows > 0) {
                            while($rs = mysql_fetch_array($result)) {
                                //$customer_id = $rs["customer_id"];
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
                                $email_1 = $rs["email_1"];
                                $email_2 = $rs["email_2"];
                                $prj_id = $rs["project_id"];
                                $plot_number = $rs["plot_number"];
                                $plot_type = $rs["plot_type"];
                                $status = $rs["status"];

                                $phones = array($phone_1, $phone_2, $mobile_1, $mobile_2);
                                $emails = array($email_1, $email_2);

                                echo "<tr>";
                                echo "<td valign='top'><strong>$plot_number</strong><br /><span class='notes'>$plot_type";

                                if(empty($project_id)) {
                                    echo "<br />".getProjectName($prj_id)."</span>";
                                }

                                echo "</td>";
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
                                echo "<td valign='top'>$status</td>";
                                echo "</tr>";
                            }
                        }
                ?>
                        </table>
                <?
                    //} else {
                    //    echo "<span style='color: #ff0000;'>Please select a plot.</span>";
                    //}
                ?>
                   </td></tr>
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