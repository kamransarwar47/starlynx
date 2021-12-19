<?
    define("MODULE_ID", "LUD005");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
    if (checkPermission($userInfo["id"], MODIFY)) {
        if ($cmd == "UPDATE") {
            if (!empty($id) && !empty($expense_date) && $expense_percentage != '') {
                $percentage_data = array();
                $expense_date = date('Y-m', strtotime($expense_date));
                if(!empty($percentage)) {
                    $percentage_data = unserialize($percentage);
                }
                $percentage_data[$expense_date] = array(
                    'month_year' => $expense_date,
                    'percentage' => $expense_percentage
                );
                $sql = "update
                            projects
                        set
                            expense_percentage=\"" . mysql_real_escape_string(serialize($percentage_data)) . "\",
                            updated=NOW(),
                            updated_by=\"" . $userInfo["id"] . "\"
                        where
                            id=\"" . mysql_real_escape_string($id) . "\"
                        ";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                setMessage(getProjectName($id) . " has been updated successfully.", true);
                system_log(MODIFY, getProjectName($id) . " updated.", $userInfo["id"]);
                unset($cmd, $id, $expense_date, $expense_percentage);
            } else {
                setMessage("One or more required fields are empty.");
                system_log(MODIFY, "Operation failed. [Reason: Required fields were empty.]", $userInfo["id"]);
            }
        }
        if ($cmd == "VIEW") {
            if (!empty($id)) {
                $sql = "select * from projects where id=\"" . mysql_real_escape_string($id) . "\"";
                $result = mysql_query($sql, $conn) or die(mysql_error());
                $numrows = mysql_num_rows($result);
                if ($numrows > 0) {
                    while ($rs = mysql_fetch_array($result)) {
                        $id         = $rs["id"];
                        $title      = $rs["title"];
                        $percentage = $rs["expense_percentage"];
                    }
                    if($cmd == "VIEW") {
                        $cmd = "UPDATE";
                        $expense_date = '';
                        $expense_percentage = '';
                        system_log(MODIFY, "$id loaded for editing.", $userInfo["id"]);
                    }
                } else {
                    $cmd = "";
                    setMessage("Record not found.");
                    system_log(MODIFY, "Operation failed. [Reason: Record not found.]", $userInfo["id"]);
                }
            } else {
                $cmd = "";
                setMessage("Nothing to load...");
                system_log(MODIFY, "Operation failed. [Reason: No id was supplied.]", $userInfo["id"]);
            }
        }
    }
    if (empty($cmd)) {
        $cmd = "";
    }
?>
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<table border="0" width="700" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/lookup.png" width="20" height="20" border="0" align="absmiddle"
                                  style="margin-bottom: 7px;"/> Lookup Data
        </td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <form action="index.php" method="post">
                <input type="hidden" name="ss" value="<?= $ss ?>"/>
                <input type="hidden" name="mod" value="<?= $mod ?>"/>
                <input type="hidden" name="cmd" value="<?= $cmd ?>"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <textarea name="percentage" style="display: none;"><?= $percentage ?></textarea>
                <p class="box_title">Head Office Percentage</p>
                <? if (checkPermission($userInfo["id"], MODIFY) && $cmd == "UPDATE") { ?>
                    <table border="0" width="50%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="2" valign="top">
                                <p>
                                    <strong>Project</strong><br/>
                                    <?php echo $title; ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p>
                                    <strong>Date</strong><br/>
                                    <input type="text" name="expense_date" id="expense_date" value="<?= $expense_date ?>" autocomplete="off"/>
                                </p>
                            </td>
                            <td valign="top">
                                <p>
                                    <strong>Percentage</strong><br/>
                                    <input type="text" size="20" name="expense_percentage" id="expense_percentage"
                                           maxlength="12"
                                           value="<?= $expense_percentage ?>" autocomplete="off"/> %
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" value="Submit"/>
                    </p>
                <? } ?>
            </form>

            <? if (checkPermission($userInfo["id"], VIEW) && $cmd != "UPDATE") { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                       style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="80%">Project</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?
                        $sql = "select * from projects order by title";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        if ($numrows > 0) {
                            $canModify = checkPermission($userInfo["id"], MODIFY);
                            while ($rs = mysql_fetch_array($result)) {
                                $id         = $rs["id"];
                                $title      = $rs["title"];
                                echo "<tr>";
                                echo "<td>$title</td>";
                                echo "<td>";
                                if ($canModify) {
                                    echo "<a href='index.php?ss=$ss&mod=$mod&id=$id&cmd=VIEW'>View</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='2'>No record found.</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            <? } ?>

            <!-- Load Percentages with months -->
            <? if (checkPermission($userInfo["id"], MODIFY) && $cmd == "UPDATE") { ?>
                <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center" id="reclist"
                       style="margin-top: 10px;">
                    <tr id="listhead">
                        <td width="40%">Month</td>
                        <td width="40%">Percentage</td>
                    </tr>
                    <?
                        $sql = "select expense_percentage from projects where id=\"" . mysql_real_escape_string($id) . "\"";
                        $result = mysql_query($sql, $conn) or die(mysql_error());
                        $numrows = mysql_num_rows($result);
                        $result = mysql_fetch_array($result);
                        $result = unserialize($result['expense_percentage']);
                        asort($result);
                        if (!empty($result)) {
                            foreach ($result as $key => $val) {
                                echo "<tr>";
                                echo "<td>" . date('F, Y', strtotime($val['month_year'])) . "</td>";
                                echo "<td>" . $val['percentage'] . "%</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td align='center' colspan='3'>No record found.</td>";
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
<script>
    $(document).ready(function () {
        $('#expense_date').datepicker({
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