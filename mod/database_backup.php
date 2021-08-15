<?
    define("MODULE_ID", "SYS008");
    include("common/check_access.php");
    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("input:button").button();

        $(".btnBackup").click(function () {
            window.open("print.php?ss=<?=$ss?>&printmod=download.database_backup");
        });
    });
</script>
<table border="0" width="40%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/reports.png" width="17" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;"> Database Backup</td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <?= showError() ?>
            <p class="box_title">Database Backup</p>
            <p style="color: #ff5630;">*Click and wait until the database download is complete</p>
            <br/>
            <p class="box_title" style="text-align: center;">
                <? if (checkPermission($userInfo["id"], CREATE)) { ?>
                    <input type="button" value="DOWNLOAD" class="btnBackup"/>
                <? } ?>
            </p>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>