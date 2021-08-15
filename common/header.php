<div id="nav-header">
    <div id="header_area">
        <div id="starlynx">
            <img src="images/starlynx.png" width="134" height="43" border="0"/>
        </div>
        <? if (!empty($ss)) { ?>
            <div id="user_section">
                <span style="font-family: Arial; font-size: 18px; font-weight: bold; color: #666;"><?= $userInfo["full_name"] ?></span><br/>
                <span id="user_menu">
                    <a href="index.php?ss=<?= $ss ?>">Dashboard</a> |
                    <a href="index.php?ss=<?= $ss ?>&mod=messages.manage">Messages</a>
                    <?
                        $sql = "select count(id) as cnt from users_messages where recipient_id='" . $userInfo["id"] . "' and status='UNREAD'";
                        $result = mysql_query($sql, $conn) or die(mysql_error());

                        while ($rs = mysql_fetch_array($result)) {
                            $msgCnt = $rs["cnt"];
                        }

                        if ($msgCnt > 0) {
                            echo "(<font color='#ff0000'><strong>$msgCnt</strong> <img src='images/blinking_new.gif' align='absmiddle' /></font>)";

                            // Show login time notification
                            if (!isset($_SESSION["loginNoticeShow"])) {
                                $_SESSION["loginNoticeShow"] = "Y";
                            }

                            $notices .= "You have $msgCnt unread message(s).";
                        }
                    ?>

                    |
                    <a href="index.php?ss=<?= $ss ?>&mod=users.tasks">My Tasks</a> |
                    <a href="index.php?ss=<?= $ss ?>&mod=users.assigned_tasks">Assigned Tasks</a> |
                    <a href="index.php?ss=<?= $ss ?>&mod=users.chngprofilepasswd">Profile</a> |
                    <a href="logout.php?ss=<?= $ss ?>">Sign out</a>
                </span>
            </div>
        <? } ?>

        <div id="notifications">
            <span style="font-family: Arial; font-size: 18px; font-weight: bold; color: #999;"><?= date("l, j F, Y", time()) ?></span><br/>
            <span id="notify_menu">
                    <?= $_SERVER['REMOTE_ADDR'] ?>
                    <? //=gethostbyaddr($_SERVER['REMOTE_ADDR'])?>
                </span>
        </div>

        <? if (!empty($ss)) { ?>
            <div id="appMenu">
                <div id="menuStart"></div>
                <div id="main-menu">
                    <? include("common/menu.php"); ?>
                </div>
                <div id="menuEnd"></div>
                <div id="header_search_box">
                    <a href="index.php?ss=<?= $ss ?>&mod=invoices.verification">Invoice Verification</a>
                </div>
                <div class="searchBar">
                    <input id="searchQueryInput" type="text" name="searchQueryInput" placeholder="Search" value=""  autocomplete="off"/>
                    <button id="searchQuerySubmit" type="button" name="searchQuerySubmit">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="#1D64A3" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                    </svg>
                    </button>
                </div>
            </div>
        <? } ?>
    </div>
</div>

<? if (isset($_SESSION["loginNoticeShow"]) && $_SESSION["loginNoticeShow"] == "Y") { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#loginNotices").fadeIn(400).delay(5000).fadeOut(400);
        });
    </script>
    <? $_SESSION["loginNoticeShow"] = "N"; ?>
<? } ?>
<div id='loginNotices'>
    <?= $notices ?>
</div>

<? if (!empty($ss)) { ?>
    <script type="text/javascript">
        var siteRoot = "<?=$_siteRoot?>";
        var ss = "<?=$ss?>";
        setTimeout(checkMessages, 60000);
    </script>
<? } ?>
<div id='notices'>
    testing <?= time() ?>
</div>