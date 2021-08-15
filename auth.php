<?
    include("common/common.start.php");

    define("MODULE_ID", "AUTH");
    system_log(LOGIN, "Login attempt.");

    $authOk = false;
    $location = "";

    if(!empty($username) && !empty($passwd)) {
        $cpasswd = encryptPassword($passwd);

        $sql = "select id, full_name, status, multi_login from users where user_name=\"".mysql_real_escape_string($username)."\" and passwd=\"".$cpasswd."\"";
        $result = mysql_query($sql, $conn) or die(mysql_error());
        $numrows = mysql_num_rows($result);

        if($numrows > 0) {
            while($rs = mysql_fetch_array($result)) {
                $id = $rs["id"];
                $fullname = $rs["full_name"];
                $status = $rs["status"];
                $multilogin = $rs["multi_login"];
            }

            if($status == "ACTIVE") {
                if(isAllowedIp($id, $_SERVER['REMOTE_ADDR'])) {
                    // Check multi-login
                    if($multilogin == "Y" || !isLoggedIn($id)) {
                        // create session and login
                        $ss = createSession($id, $_SERVER['REMOTE_ADDR']);
                        $authOk = true;
                        system_log(LOGIN, "User logged in.", $id);
                    } else {
                        setMessage("You are already logged in.");
                        system_log(LOGIN, "Login failed [Reason: Already logged in.] .");
                    }
                } else {
                    setMessage("You are not authorized to access the system from this location.");
                    system_log(LOGIN, "Login failed [Reason: Unauthorized location.] .");
                }
            } else {
                setMessage("Your account is $status, please contact system administrator.");
                system_log(LOGIN, "Login failed [Reason: $status.] .");
            }
        } else {
            setMessage("You have entered an invalid user name or password.");
            system_log(LOGIN, "Login failed [Reason: Invalid user/password.] .");
        }
    } else {
        setMessage("Please enter a valid user name and password.");
        system_log(LOGIN, "Login failed [Reason: No user/password.] .");
    }

    include("common/common.end.php");

    if($authOk) {
        $location = "?ss=$ss";
    }

    header("Location: index.php".$location);
    exit;
?>