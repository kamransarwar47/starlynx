<?
    function pre($data, $json = false, $die = false) {
        echo '<pre>';
        if($json) {
            $data = json_encode($data);
        }
        print_r($data);
        echo '</pre>';
        if($die) {
            die();
        }
    }
    function setMessage($message, $success = false)
    {
        if ($success) {
            $_SESSION["message_success"] = $message;
        } else {
            $_SESSION["message"] = $message;
        }
    }

    function getMessage()
    {
        $message = array();

        if (isset($_SESSION["message"])) {
            $message["type"]    = "ERROR";
            $message["message"] = $_SESSION["message"];
            unset($_SESSION["message"]);
        } elseif (isset($_SESSION["message_success"])) {
            $message["type"]    = "SUCCESS";
            $message["message"] = $_SESSION["message_success"];
            unset($_SESSION["message_success"]);
        }

        return $message;
    }

    function showError()
    {
        $message = getMessage();
        $display = "";

        if (!empty($message["message"])) {
            if ($message["type"] == "ERROR") {
                $state = "ui-state-error";
            } else {
                $state = "ui-state-success";
            }

            $display = "<div class=\"ui-widget\">
                        <div class=\"$state ui-corner-all\" style=\"padding: 0 .7em;\">
                            <p>
                                <span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>
                                " . $message["message"] . "
                            </p>
                        </div>
                    </div>";
        }

        return $display;
    }

    function encryptPassword($passwd)
    {
        return md5($passwd);
    }

    function isLoggedIn($uid)
    {
        global $conn;

        $status = false;
        $sql    = "select id, session_id from users_sessions where user_id='$uid' and status='ACTIVE'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if ($numrows > 0) {
            while ($rs = mysql_fetch_array($result)) {
                $ssid = $rs["session_id"];

                if (checkSession($ssid)) {
                    return true;
                }
            }
        }

        return $status;
    }

    function checkSession($ss)
    {
        global $conn, $CFG_SESSION_TIMEOUT;

        $alive = false;
        $sql   = "select session_time from users_sessions where session_id=\"" . mysql_real_escape_string($ss) . "\" and status='ACTIVE'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if ($numrows > 0) {
            while ($rs = mysql_fetch_array($result)) {
                $session_time = $rs["session_time"];
            }

            $ctime = time();
            $diff  = $ctime - $session_time;

            if ($diff < $CFG_SESSION_TIMEOUT) {
                $alive = true;
            }
        }

        return $alive;
    }

    function isAllowedIp($uid, $ip)
    {
        global $conn;

        $allowed = false;

        // Check if user is allowed to login from anywhere.
        $sql = "select id from users_access_control where user_id='$uid' and allowed_ip='*'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if ($numrows <= 0) {
            $sql = "select id from users_access_control where user_id='$uid' and allowed_ip='$ip'";
            $result = mysql_query($sql, $conn) or die(mysql_error());
            $numrows = mysql_num_rows($result);

            if ($numrows > 0) {
                $allowed = true;
            }
        } else {
            $allowed = true;
        }

        return $allowed;
    }

    function createSession($uid, $ip)
    {
        global $conn;

        $ctime = time();
        $uname = getUserName($uid);
        $ssid  = generateSession($uname);
        $sql   = "insert into
                users_sessions(
                    session_id, user_id, login_time, session_time, status, ip, created
                ) values(
                    '$ssid',
                    '$uid',
                    '$ctime',
                    '$ctime',
                    'ACTIVE',
                    '$ip',
                    NOW()
                )";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        return $ssid;
    }

    function generateSession($uname)
    {
        $ctime   = time();
        $plainss = $uname . $ctime;

        return md5($plainss);
    }

    function refreshSession($ssid)
    {
        global $conn;

        $ctime = time();
        $sql   = "update users_sessions set session_time='$ctime', updated=NOW() where session_id=\"" . mysql_real_escape_string($ssid) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());
    }

    function logout($ssid)
    {
        global $conn;

        $sql = "update users_sessions set status='EXPIRED', updated=NOW() where session_id=\"" . mysql_real_escape_string($ssid) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());
    }

    function getUserName($uid)
    {
        global $conn;

        $uname = "";
        $sql   = "select user_name from users where id='$uid'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $uname = $rs["user_name"];
        }

        return $uname;
    }

    function getUserFullName($uid)
    {
        global $conn;

        $fname = "";
        $sql   = "select full_name from users where id='$uid'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $fname = $rs["full_name"];
        }

        return $fname;
    }

    function getUserMSISDN($uid)
    {
        global $conn;

        $mobile = "";
        $sql    = "select mobile from users where id='$uid'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $mobile = $rs["mobile"];
        }

        return $mobile;
    }

    function getUserNameByMobile($mobile)
    {
        global $conn;

        //$mobile = urldecode($mobile);

        if (strpos($mobile, "+92") !== false) { // number is local and contains country code
            $mobile  = str_replace("+92", "0", $mobile);
            $opt     = substr($mobile, 0, 4);
            $num     = substr($mobile, 4);
            $fmobile = "$opt-$num";
        } else {
            $opt     = substr($mobile, 0, 4);
            $num     = substr($mobile, 4);
            $fmobile = "$opt-$num";
        }

        $uname = "";
        $sql   = "select user_name from users where mobile='$mobile' or mobile='$fmobile'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $uname = $rs["user_name"];
        }

        return $uname;
    }

    function getUserId($uname)
    {
        global $conn;

        $uid = "";
        $sql = "select id from users where user_name=\"" . mysql_real_escape_string($uname) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $uid = $rs["id"];
        }

        return $uid;
    }

    function getSignedUserInfo($ssid)
    {
        global $conn;

        $sql = "select user_id from users_sessions where session_id=\"" . mysql_real_escape_string($ssid) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $uid = $rs["user_id"];
        }

        $sql = "select user_name, full_name from users where id='$uid'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $uname = $rs["user_name"];
            $fname = $rs["full_name"];
        }

        $info = array(
            "id" => $uid,
            "user_name" => $uname,
            "full_name" => $fname
        );

        return $info;
    }

    function checkPermission($uid, $operation)
    {
        global $conn;

        $authorized = false;
        $sql        = "select id from users_permissions where user_id='$uid' and module_id='" . MODULE_ID . "' and operation='$operation'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if ($numrows > 0) {
            $authorized = true;
        }

        return $authorized;
    }

    function checkPermission2($uid, $operation, $moduleId)
    {
        global $conn;

        $authorized = false;
        $sql        = "select id from users_permissions where user_id='$uid' and module_id='" . $moduleId . "' and operation='$operation'";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);
        //echo $sql;
        if ($numrows > 0) {
            $authorized = true;
        }

        return $authorized;
    }

    function system_log($operation, $details, $uid = 0)
    {
        global $conn;

        $details .= " " . print_r($_REQUEST, true);
        $ip      = $_SERVER["REMOTE_ADDR"];
        //$host = @gethostbyaddr($ip);
        $host = $ip;
        $sql  = "insert into
                system_log (
                    module_id, operation, details, user_id, access_ip, host_name, created
                ) values (
                    \"" . mysql_real_escape_string(MODULE_ID) . "\",
                    \"" . mysql_real_escape_string($operation) . "\",
                    \"" . mysql_real_escape_string($details) . "\",
                    '$uid',
                    \"$ip\",
                    \"" . mysql_real_escape_string($host) . "\",
                    NOW()
                )";
        $result = mysql_query($sql, $conn) or die(mysql_error());
    }

    function getCityName($id)
    {
        global $conn;

        $city = "";
        $sql  = "select title from cities where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $city = $rs["title"];
        }

        return $city;
    }

    function getCountryName($id)
    {
        global $conn;

        $country = "";
        $sql     = "select title from countries where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $country = $rs["title"];
        }

        return $country;
    }

    function getCustomerName($id)
    {
        global $conn;

        $customer = "";
        $sql      = "select full_name from customers where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $customer = $rs["full_name"];
        }

        return $customer;
    }

    function getCustomerInfoByAccountId($account_id)
    {
        global $conn;

        $customer = array();
        $sql      = "select id, full_name, father_name, mobile_1, mobile_2 from customers where account_id=\"" . mysql_real_escape_string($account_id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $customer = array(
                "id" => $rs["id"],
                "full_name" => $rs["full_name"],
                "father_name" => $rs["father_name"],
                "mobile_1" => $rs["mobile_1"],
                "mobile_2" => $rs["mobile_2"],
            );
        }

        return $customer;
    }

    function getProjectName($id)
    {
        global $conn;

        $project = "";
        $sql     = "select title from projects where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $project = $rs["title"];
        }

        return $project;
    }

    function getLandOwnerName($id)
    {
        global $conn;

        $full_name = "";
        $sql       = "select full_name from landowner where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $full_name = $rs["full_name"];
        }

        return $full_name;
    }

    function getAccountsChildsArray($actid)
    {
        global $conn;

        $actsArray = array();

        // Recursive
        $pageSql = "select id,master_id,title from accounts where master_id = '" . $actid . "'";

        $r = mysql_query($pageSql, $conn) or die(mysql_error());

        if (mysql_num_rows($r) > 0) {
            while ($pageData = mysql_fetch_assoc($r)) {
                $actsArray[] = $pageData["id"];

                if (hasSubAccounts($pageData["id"])) {
                    $actsArray = array_merge($actsArray, getAccountsChildsArray($pageData["id"]));
                }
            }
        }

        // Straight
        return $actsArray;
    }

    function createAccountsArray($highestParentId = 0, $display = 1)
    {
        global $conn;

        $actsTemp = array();

        if ($highestParentId > 0 && $display == 1) {
            $pageSql = "select id,master_id,title from accounts where id = '" . $highestParentId . "'";
            $r = mysql_query($pageSql, $conn) or die(mysql_error());
            if (mysql_num_rows($r) > 0) {
                while ($pageData = mysql_fetch_assoc($r)) {
                    $actsTemp[0] = $pageData;
                }
            }

            $actsTemp[0]['children'] = createRecursiveAccountsArray($highestParentId);
        } else {
            $actsTemp = createRecursiveAccountsArray($highestParentId);
        }

        return $actsTemp;
    }

    function createRecursiveAccountsArray($highestParentId)
    {
        global $conn;

        $actsArray = array();

        // Recursive
        $pageSql = "select id,master_id,title from accounts where master_id = '" . $highestParentId . "' order by title";

        $r = mysql_query($pageSql, $conn) or die(mysql_error());

        if (mysql_num_rows($r) > 0) {

            $i = 0;

            while ($pageData = mysql_fetch_assoc($r)) {
                $actsArray[$i] = $pageData;

                if (hasSubAccounts($pageData["id"])) {
                    $actsArray[$i]["children"] = createRecursiveAccountsArray($pageData["id"]);
                }
                $i++;
            }
        }

        // Straight
        return $actsArray;
    }

    function createProjectAccountsArray($highestParentId = 0)
    {
        global $conn;

        $actsArray = array();

        // Recursive
        $pageSql = "select a.id, a.master_id, a.title from accounts a JOIN projects p ON a.id = p.account_id where a.master_id = '" . $highestParentId . "' order by title";

        $r = mysql_query($pageSql, $conn) or die(mysql_error());

        if (mysql_num_rows($r) > 0) {

            $i = 0;

            while ($pageData = mysql_fetch_assoc($r)) {
                $actsArray[$i] = $pageData;

                if (hasSubAccounts($pageData["id"])) {
                    $actsArray[$i]["children"] = createProjectAccountsArray($pageData["id"]);
                }

                $i++;
            }

        }

        // Straight
        return $actsArray;
    }

    function getSubAccountsExcelSheet($project_id = 0, $lastAccountId = 0)
    {
        $sub_account_array = createSubAccountsExcelSheet($project_id, $lastAccountId);

        if (array_key_exists('title', $sub_account_array[0][0][0])) {
            $result_array[0] = $sub_account_array[0][0][0]['title'];
            $result_array[1] = $sub_account_array[0][0]['title'];
            $result_array[2] = $sub_account_array[0]['title'];
            $result_array[3] = $sub_account_array['title'];
        } else if (array_key_exists('title', $sub_account_array[0][0])) {
            $result_array[0] = $sub_account_array[0][0]['title'];
            $result_array[1] = $sub_account_array[0]['title'];
            $result_array[2] = $sub_account_array['title'];
            $result_array[3] = "";
        } else if (array_key_exists('title', $sub_account_array[0])) {
            $result_array[0] = $sub_account_array[0]['title'];
            $result_array[1] = $sub_account_array['title'];
            $result_array[2] = "";
            $result_array[3] = "";
        } else {
            $result_array[0] = $sub_account_array['title'];
            $result_array[1] = "";
            $result_array[2] = "";
            $result_array[3] = "";
        }

        return $result_array;
    }

    function createSubAccountsExcelSheet($project_id, $lastAccountId)
    {
        global $conn;

        $actsArray = array();

        // Recursive
        $pageSql = "select a.id, a.master_id, a.title from accounts a where a.id = '" . $lastAccountId . "' order by title";

        $r = mysql_query($pageSql, $conn) or die(mysql_error());

        if (mysql_num_rows($r) > 0) {
            while ($pageData = mysql_fetch_assoc($r)) {
                $actsArray = $pageData;

                if ($pageData["master_id"] != getProjectAccountId($project_id)) {
                    $actsArray[] = createSubAccountsExcelSheet($project_id, $pageData["master_id"]);
                }
            }
        }

        // Straight
        return $actsArray;
    }

    function createPageSelectHTML($name, $id, $pagesArray, $selectedValue = "", $firstLabel = "", $nodeCount = 0)
    {
        if ($nodeCount < 0) {
            $nodeCount = 0;
        }

        $firstLabel = ($firstLabel == "") ? "None" : $firstLabel;
        $html       = "";

        if ($nodeCount == 0) {
            $html = '<select name=\'' . $name . '\' id=\'' . $id . '\'><option value=\'\'>' . $firstLabel . '</option>';
        }

        foreach ($pagesArray as $pageData) {
            $selected = "";
            if ($pageData["id"] == $selectedValue) {
                $selected = "selected='selected'";
            }

            $style              = "";
            $childrenOptionsHTL = "";
            if (!empty($pageData["children"])) {
                $childrenOptionsHTL = createPageSelectHTML("", "", $pageData["children"], $selectedValue, $firstLabel, $nodeCount + 1);
                $style              .= "style='";
                if ($nodeCount == 0) {
                    $style .= "background-color: #cccccc; font-weight: bold;";
                }

                if ($nodeCount >= 1) {
                    $style .= "background-color: #eeeeee; font-weight: bold;";
                }
                $style .= "'";
            }

            $nodeIndent = "";
            for ($i = 0; $i < $nodeCount; $i++) {
                $nodeIndent .= "-";
            }

            if ($nodeCount == 0) {
                $pageData["title"] = strtoupper($pageData["title"]);
            }

            $html .= "<option $style value='" . $pageData["id"] . "' $selected>" . $nodeIndent . " " . $pageData["title"] . "</option>";
            $html .= $childrenOptionsHTL;
        }

        if ($nodeCount == 0) {
            $html .= '</select>';
        }

        return $html;
    }

    function hasSubAccounts($actId)
    {
        global $conn;

        $pageSql = "select * from accounts where master_id = '" . $actId . "'";
        $r       = mysql_query($pageSql, $conn);

        if (mysql_num_rows($r) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function createPageHTML($pagesArray, $canModify, $canDelete, $nodeCount = 0)
    {
        global $ss, $mod, $head_account;

        if ($nodeCount < 0) {
            $nodeCount = 0;
        }

        $html = "";

        foreach ($pagesArray as $pageData) {
            $style              = "";
            $childrenOptionsHTL = "";
            if (!empty($pageData["children"])) {
                $childrenOptionsHTL = createPageHTML($pageData["children"], $canModify, $canDelete, $nodeCount + 1);
                $style              .= "style='";
                if ($nodeCount >= 0 && $nodeCount <= 2) {
                    $style .= "font-weight: bold;";
                }

                if ($nodeCount >= 1) {
                    $style .= " font-style: italic;";
                }
                $style .= "'";
            }

            $nodeIndent = "";
            for ($i = 0; $i < $nodeCount; $i++) {
                $nodeIndent .= "---";
            }

            if ($nodeCount == 0) {
                $pageData["title"] = strtoupper($pageData["title"]);
            }

            $html .= "<tr>";
            $html .= "<td $style>" . $nodeIndent . " " . $pageData["title"] . "</td>";
            $html .= "<td>";

            if ($canModify) {
                $html .= "<a href='index.php?ss=$ss&mod=$mod&head_account=$head_account&id=" . $pageData["id"] . "&cmd=EDIT'>Modify</a> | ";
            }

            if ($canDelete) {
                $html .= "<a href='#' onClick=\"javascript: confirmDelete('index.php?ss=$ss&mod=$mod&head_account=$head_account&id=" . $pageData["id"] . "&cmd=DELETE');\">Delete</a>";
            }

            $html .= "</td>";
            $html .= "</tr>";
            $html .= $childrenOptionsHTL;
        }

        return $html;
    }

    function createInvoiceSelectHTML($name, $id, $pagesArray, $selectedValue = "", $firstLabel = "None", $nodeCount = 0)
    {
        if ($nodeCount < 0) {
            $nodeCount = 0;
        }

        $html = "";

        if ($nodeCount == 0) {
            $html = '<select name=\'' . $name . '\' id=\'' . $id . '\'><option value=\'\'>' . $firstLabel . '</option>';
        }

        foreach ($pagesArray as $pageData) {
            $selected = "";
            if ($pageData["id"] == $selectedValue) {
                $selected = "selected='selected'";
            }

            $style              = "";
            $childrenOptionsHTL = "";
            if (!empty($pageData["children"])) {
                $childrenOptionsHTL = createInvoiceSelectHTML("", "", $pageData["children"], $selectedValue, $firstLabel, $nodeCount + 1);
                $style              .= "style='";
                if ($nodeCount >= 0 && $nodeCount <= 2) {
                    $style .= "font-weight: bold;";
                }

                if ($nodeCount >= 1) {
                    $style .= " font-style: italic;";
                }
                $style .= "'";
            }

            $nodeIndent = "";
            for ($i = 0; $i < $nodeCount; $i++) {
                $nodeIndent .= "---";
            }

            if ($nodeCount == 0) {
                $pageData["title"] = strtoupper($pageData["title"]);
                $html              .= "<optgroup label='" . $pageData["title"] . "'>";
                $html              .= $childrenOptionsHTL;
                $html              .= "</optgroup>";
            } else {

                $html .= "<option $style value='" . $pageData["id"] . "' $selected>" . $nodeIndent . " " . $pageData["title"] . "</option>";
                //$html .= $childrenOptionsHTL;
            }
        }

        if ($nodeCount == 0) {
            $html .= '</select>';
        }

        return $html;
    }

    function getProjectCode($projectId)
    {
        global $conn;

        $projectCode = "";
        $sql         = "select short_code from projects where id='$projectId'";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $projectCode = $rs["short_code"];
        }

        return $projectCode;
    }

    function getNextVoucherNumber($projectId, $voucherType)
    {
        global $conn;

        $projectCode      = getProjectCode($projectId);
        $voucherNumber    = "1";
        $currentMonthYear = date("my", time());
        $sql              = "select voucher_id from transactions where voucher_id like '$projectCode-$voucherType-$currentMonthYear%' order by id desc limit 0,1";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if ($numrows > 0) { // Found running month's voucher
            while ($rs = mysql_fetch_array($result)) {
                $voucher_id = $rs["voucher_id"];
            }

            $vparts        = explode("-", $voucher_id);
            $lastVoucher   = $vparts[3];
            $voucherNumber = $lastVoucher + 1;
        }

        if (strlen($voucherNumber) < 5) {
            $voucherNumber = str_pad($voucherNumber, 5, "0", STR_PAD_LEFT);
        }

        $voucherNumber = "$projectCode-$voucherType-$currentMonthYear-$voucherNumber";

        return $voucherNumber;
    }

    function isPostDate($chequeDate)
    {
        $postDate = "N";
        $ctime    = time();
        $chqtime  = strtotime($chequeDate);

        if ($chqtime > $ctime) {
            $postDate = "Y";
        }

        return $postDate;
    }

    function getAccountTitle($id)
    {
        global $conn;

        $account = "";
        $sql     = "select title from accounts where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account = $rs["title"];
        }

        return $account;
    }

    function getBankName($id)
    {
        global $conn;

        $bank = "";
        $sql  = "select title from banks where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $bank = $rs["title"];
        }

        return $bank;
    }

    function getBankShortName($id)
    {
        global $conn;

        $bank = "";
        $sql  = "select short_title from banks where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $bank = $rs["short_title"];
        }

        return $bank;
    }

    function getDepositAccountTitle($id)
    {
        global $conn;

        $account_number = "";
        $sql            = "select account_number from deposit_accounts where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_number = $rs["account_number"];
        }

        return $account_number;
    }

    function getTransactionId($vnum)
    {
        global $conn;

        $id  = "";
        $sql = "select id from transactions where voucher_id=\"" . mysql_real_escape_string($vnum) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $id = $rs["id"];
        }

        return $id;
    }

    function getPlotAccountId($plot_id)
    {
        global $conn;

        $account_id = "";
        $sql        = "select account_id from plots where id=\"" . mysql_real_escape_string($plot_id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_id = $rs["account_id"];
        }

        return $account_id;
    }

    function getPlotIdByAccountId($plot_account_id)
    {
        global $conn;

        $plot_id = "";
        $sql        = "select id from plots where account_id=\"" . mysql_real_escape_string($plot_account_id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $plot_id = $rs["id"];
        }

        return $plot_id;
    }

    function getProjectAccountId($project_id)
    {
        global $conn;

        $account_id = "";
        $sql        = "select account_id from projects where id=\"" . mysql_real_escape_string($project_id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_id = $rs["account_id"];
        }

        return $account_id;
    }

    function getMinYear()
    {
        global $conn;

        $min = "";
        $sql = "select min(year(invoice_date)) as yr from transactions";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $min = $rs["yr"];
        }

        return $min;
    }

    function getMaxYear()
    {
        global $conn;

        $max = "";
        $sql = "select max(year(invoice_date)) as yr from transactions";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $max = $rs["yr"];
        }

        return $max;
    }

    function printArray($arr)
    {
        echo "<pre style='font-size: 11px; font-family: Courier;'>";
        print_r($arr);
        echo "</pre>";
    }

    function formatCurrency($amt)
    {
        return number_format($amt, 2, ".", ",");
    }

    function formatContacts($contactsArray, $separator)
    {
        $contacts = "";
        $ct       = array();

        for ($i = 0; $i < sizeof($contactsArray); $i++) {
            if (!empty($contactsArray[$i])) {
                $ct[] = $contactsArray[$i];
            }
        }

        $contacts = implode($separator, $ct);

        return $contacts;
    }

    /* KAMRAN UDPATE START */
    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    function random_color()
    {
        return random_color_part() . random_color_part() . random_color_part();
    }

    function createInvoicePersonSelectHTML($pagesArray, $selectedValue = "", $firstLabel = "None", $nodeCount = 0)
    {
        if ($nodeCount < 0) {
            $nodeCount = 0;
        }

        $html = "";

        if ($nodeCount == 0) {
            $html = '<option value=\'\'>' . $firstLabel . '</option>';
        }

        foreach ($pagesArray as $pageData) {
            $selected = "";
            if ($pageData["id"] == $selectedValue) {
                $selected = "selected='selected'";
            }

            $style              = "";
            $childrenOptionsHTL = "";
            if (!empty($pageData["children"])) {
                $childrenOptionsHTL = createInvoicePersonSelectHTML($pageData["children"], $selectedValue, $firstLabel, $nodeCount + 1);
                $style              .= "style='";
                if ($nodeCount >= 0 && $nodeCount <= 2) {
                    $style .= "font-weight: bold;";
                }

                if ($nodeCount >= 1) {
                    $style .= " font-style: italic;";
                }
                $style .= "'";
            }

            $nodeIndent = "";
            for ($i = 0; $i < $nodeCount; $i++) {
                $nodeIndent .= "---";
            }

            if ($nodeCount == 0) {
                $html .= "<option $style value='" . $pageData["id"] . "' $selected>" . " " . $pageData["title"] . "</option>";
                $html .= $childrenOptionsHTL;
            } else {
                $html .= "<option $style value='" . $pageData["id"] . "' $selected>" . " " . $nodeIndent . $pageData["title"] . "</option>";
            }
        }

        return $html;
    }

    /**
     * @param $plot_account_id
     * @param $invoice_date
     *
     * @return array
     */
    function getNextInstallmentDetailByTransaction($plot_account_id, $invoice_date)
    {
        global $conn;
        $data = [];

        // plot dues and remaining amount
        $sql_amount = "SELECT SUM(CASE WHEN DATE(pd.due_on) <= DATE('" . mysql_real_escape_string($invoice_date) . "') THEN pd.amount ELSE 0 END) pending_due_amount, SUM(CASE WHEN DATE(pd.due_on) > DATE('" . mysql_real_escape_string($invoice_date) . "') THEN pd.amount ELSE 0 END) remaining_amount FROM plots_dues pd JOIN plots pt ON pt.id = pd.plot_id WHERE pt.account_id = '" . mysql_real_escape_string($plot_account_id) . "' AND pd.status = 'DUE' ORDER BY due_on ASC";

        $result_amount = mysql_query($sql_amount, $conn) or die(mysql_error());

        if (mysql_num_rows($result_amount) > 0) {
            $rs_amount                  = mysql_fetch_array($result_amount);
            $data['pending_due_amount'] = $rs_amount['pending_due_amount'];
            $data['remaining_amount']   = $rs_amount['remaining_amount'];
        }

        // plot next installment
        $sql_date = "SELECT pd.due_on as due_on FROM plots_dues pd JOIN plots pt ON pt.id = pd.plot_id WHERE pt.account_id = '" . mysql_real_escape_string($plot_account_id) . "' AND DATE(pd.due_on) > DATE('" . mysql_real_escape_string($invoice_date) . "') AND pd.status = 'DUE' ORDER BY due_on ASC LIMIT 1";

        $result_date = mysql_query($sql_date, $conn) or die(mysql_error());

        if (mysql_num_rows($result_date) > 0) {
            $rs_date               = mysql_fetch_array($result_date);
            $data['next_due_date'] = $rs_date['due_on'];
        }

        return $data;
    }

    /**
     * @param $plot_account_id
     * @param $invoice_date
     *
     * @return array
     */
    function getPlotInstallmentDetails($plot_account_id)
    {
        global $conn;
        $data = [];

        // plot dues
        $sql_amount = "SELECT pd.id, pd.plot_id, pd.amount, pd.dues_type, pd.due_on, pd.notes FROM plots_dues pd JOIN plots pt ON pt.id = pd.plot_id WHERE pt.account_id = '" . mysql_real_escape_string($plot_account_id) . "' AND pd.status = 'DUE' ORDER BY due_on ASC";

        $result_amount = mysql_query($sql_amount, $conn) or die(mysql_error());

        if (mysql_num_rows($result_amount) > 0) {
            while ($rs = mysql_fetch_assoc($result_amount)) {
                $data[] = $rs;
            }
        }

        return $data;
    }

    function getLandOwnerAccountId($id)
    {
        global $conn;

        $account_id = "";
        $sql        = "select account_id from landowner where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_id = $rs["account_id"];
        }

        return $account_id;
    }

    function getInvestorAccountId($id)
    {
        global $conn;

        $account_id = "";
        $sql        = "select account_id from investor where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_id = $rs["account_id"];
        }

        return $account_id;
    }

    function getPartnerAccountId($id)
    {
        global $conn;

        $account_id = "";
        $sql        = "select account_id from partner where id=\"" . mysql_real_escape_string($id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $account_id = $rs["account_id"];
        }

        return $account_id;
    }

    function getProjectIdByName($name)
    {
        global $conn;

        $id  = "";
        $sql = "select id from projects where title LIKE \"" . mysql_real_escape_string($name) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $id = $rs["id"];
        }

        return $id;
    }

    function getProjectExpensePercentage($project_id)
    {
        global $conn;

        $expense_percentage = "";
        $sql                = "select expense_percentage from projects where id=\"" . mysql_real_escape_string($project_id) . "\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());

        while ($rs = mysql_fetch_array($result)) {
            $expense_percentage = $rs["expense_percentage"];
        }

        return $expense_percentage;
    }

    function getHeadOfficeExpense($first_day_this_month = '', $last_day_this_month = '', $last_day_last_month = '', $incPending = '', $incPendingClear = '', $expense_percentage = '')
    {
        global $conn;

        $total_head_office_expenses_this_month = 0;
        $total_head_office_expenses_till_date  = 0;

        if ($expense_percentage > 0) {

            $project_id = getProjectIdByName("Head Office");

            if ($incPending != "Y") {
                $pendingAuthHand = "AND (t.transaction_type='PAYMENT' AND t.auth_status='AUTH')";
            } else {
                $pendingAuthHand = "AND t.transaction_type='PAYMENT'";
            }

            if ($incPendingClear != "Y") {
                $pendingClearance = "AND ((t.transaction_type =  'PAYMENT' AND d.voucher_type IN ('BANK',  'CASH')))";
            } else {
                $pendingClearance = "";
            }

            // get total payments against project this month
            $sql = "SELECT 
                SUM(CASE WHEN (t.invoice_date BETWEEN \"" . $first_day_this_month . "\" AND \"" . $last_day_this_month . "\") THEN d.amount ELSE 0 END) AS total_head_office_expenses_this_month,
                SUM(CASE WHEN t.invoice_date <= \"" . $last_day_last_month . "\" THEN d.amount ELSE 0 END) AS total_head_office_expenses_till_date
                FROM projects p, transactions t, transactions_details d
                WHERE p.id = \"" . mysql_real_escape_string($project_id) . "\"
                AND t.project_id = p.id 
                AND t.id = d.transaction_id 
                $pendingAuthHand 
                $pendingClearance";
            $result = mysql_query($sql, $conn) or die(mysql_error());
            $numrows = mysql_num_rows($result);
            if ($numrows > 0) {
                while ($rs = mysql_fetch_array($result)) {
                    $total_head_office_expenses_this_month = $rs['total_head_office_expenses_this_month'];
                    $total_head_office_expenses_till_date  = $rs['total_head_office_expenses_till_date'];
                }
            }

            $total_head_office_expenses_this_month = ($total_head_office_expenses_this_month / 100) * $expense_percentage;
            $total_head_office_expenses_till_date  = ($total_head_office_expenses_till_date / 100) * $expense_percentage;
        }

        return ['total_head_office_expenses_this_month' => $total_head_office_expenses_this_month, 'total_head_office_expenses_till_date' => $total_head_office_expenses_till_date];
    }

    /*
     * Dashboard Statistics
     * */
    function get_project_wise_statistics()
    {
        global $conn;
        $data = [];

        $sql = "SELECT
                    p.title as ptitle, p.short_code as short_code,
                    SUM(CASE WHEN t.transaction_type = 'RECEIPT' THEN d.amount ELSE 0 END) AS total_income,
                    SUM(CASE WHEN t.transaction_type = 'PAYMENT' THEN d.amount ELSE 0 END) AS total_expenses
                FROM
                    projects p, transactions t, transactions_details d
                WHERE
                    t.id = d.transaction_id
                    AND t.project_id = p.id
                GROUP BY
                    p.id
                ORDER BY 
                    p.title";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $num_rows = mysql_num_rows($result);

        if ($num_rows > 0) {
            $n = 0;
            while ($rs = mysql_fetch_assoc($result)) {
                $data[$n]['chart_label']   = $rs['short_code'];
                $data[$n]['hover_label']   = $rs['ptitle'];
                $data[$n]['income']        = $rs['total_income'];
                $data[$n]['expense']       = $rs['total_expenses'];
                $data[$n]['balance']       = ($rs['total_income'] - $rs['total_expenses']);
                $data[$n]['income_hover']  = 'Rs.' . formatCurrency($rs['total_income']);
                $data[$n]['expense_hover'] = 'Rs.' . formatCurrency($rs['total_expenses']);
                $data[$n]['balance_hover'] = 'Rs.' . formatCurrency(($rs['total_income'] - $rs['total_expenses']));
                $n++;
            }
        }
        /*
         * Generating Data for chart
         * */
        return '<script> var project_wise_statistics = ' . json_encode($data) . ';</script>';
    }

    function get_monthly_project_statistics()
    {
        global $conn;
        $projects     = [];
        $months       = [];
        $months_label = [];
        $dataset      = [];

        $sql = "SELECT
                    p.id as id, MONTH(t.invoice_date) as month,
                    SUM(CASE WHEN t.transaction_type = 'RECEIPT' THEN d.amount ELSE 0 END) AS total_income,
                    SUM(CASE WHEN t.transaction_type = 'PAYMENT' THEN d.amount ELSE 0 END) AS total_expenses
                FROM
                    projects p, transactions t, transactions_details d
                WHERE
                    t.id = d.transaction_id
                    AND t.project_id = p.id
                    AND t.invoice_date BETWEEN '" . date('Y-01-01', time()) . "' AND '" . date("Y-m-t", time()) . "'
                GROUP BY
                    p.id, DATE_FORMAT(t.invoice_date, '%Y%m')
                ORDER BY 
                    p.title, MONTH(t.invoice_date)";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $num_rows = mysql_num_rows($result);

        if ($num_rows > 0) {
            while ($rs = mysql_fetch_assoc($result)) {
                $dataset[]  = $rs;
                $projects[] = $rs["id"];
            }

            $projects = array_unique($projects);

            /*
             * Month Number And Titles
             * */
            $date1  = date('Y-01-01', time());
            $date2  = date("Y-m-t", time());
            $output = [];
            $time   = strtotime($date1);
            $last   = date('n-Y', strtotime($date2));

            do {
                $current_month = date('n-Y', $time);
                $month_title   = date('M, Y', $time);

                $output[] = [
                    'month' => date('n', $time),
                    'title' => $month_title,
                ];

                $time = strtotime('+1 month', $time);
            } while ($current_month != $last);

            // month number
            $months = array_column($output, 'month');
            // month title
            $months_label = array_column($output, 'title');
        }

        /*
         * Generating Data for chart
         * */
        $data = "{
            labels: " . json_encode($months_label) . ",
            type: 'line',
            defaultFontFamily: 'Poppins',
            datasets: [";

        foreach ($projects as $project) {
            $graph_color  = "#" . random_color();
            $balance_data = [];
            $data         .= "{
                    data: [";
            foreach ($months as $month) {
                $balance = 0;
                foreach ($dataset as $rs) {
                    if ($project == $rs['id'] && $month == $rs["month"]) {
                        $balance = ($rs["total_income"] - $rs["total_expenses"]);
                    }
                }
                $balance_data[] = $balance;
            }
            $data .= implode(',', $balance_data);
            $data .= "],
                    label: \"" . getProjectName($project) . "\",
                    fill: false,
                    backgroundColor: '" . $graph_color . "',
                    borderColor: '" . $graph_color . "',
                    borderWidth: 2,
                    pointStyle: 'circle',
                    pointRadius: 5,
                    pointBorderColor: '" . $graph_color . "',
                    pointBackgroundColor: '" . $graph_color . "',
                },";

        }
        $data .= "]
            }";

        return "<script> var monthly_project_statistics = " . $data . "</script>";
    }

    /**
     * @param      $host
     * @param      $user
     * @param      $pass
     * @param      $name
     * @param bool $tables
     * @param bool $backup_name
     */
    function Export_Database($host, $user, $pass, $name, $tables = false, $backup_name = false)
    {
        include_once(dirname(__FILE__) . '/Mysqldump/Mysqldump.php');
        try {
            $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.$host.';dbname='.$name, $user, $pass);
            $dump->start($backup_name);
        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
        // Process file download
        if(file_exists($backup_name)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($backup_name).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_name));
            flush(); // Flush system output buffer
            readfile($backup_name);
            unlink($backup_name);
            die();
        } else {
            http_response_code(404);
            die();
        }
    }

    /* KAMRAN UDPATE ENDS */
?>