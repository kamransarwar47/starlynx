<?php
function prepareMSISDN($msisdn) {
    $msisdn = str_replace("+92", "0", $msisdn);
    $msisdn = str_replace("-", "", $msisdn);
    $msisdn = str_replace(".", "", $msisdn);

    return $msisdn;
}

function extractMessage($fullmessage, $params=0) {
    $parts = explode(" ", $fullmessage);
    $fmsg = array();
    $keyword = "";
    $parameters = array();
    $message = "";
    $skip = 1 + $params;
    $ret = array();

    for($i=0; $i<sizeof($parts); $i++) {
        $parts[$i] = trim($parts[$i]);

        if(!empty($parts[$i])) {
            if($i==0) {
                $keyword = $parts[$i];
            }

            if($params > 0 && ($i >= 1 && $i <= $params)) {
                $parameters[] = $parts[$i];
            }

            if($i >= $skip) {
                $fmsg[] = $parts[$i];
            }
        }
    }

    $message = implode(" ", $fmsg);

    $ret["KEYWORD"] = $keyword;
    $ret["PARAMS"] = $parameters;
    $ret["MESSAGE"] = $message;

    return $ret;
}

function send_sms($from, $smsto, $msg, $params=array())
{
        global $_smscIP;
        global $_smscHost;
        global $_smscPort;
        global $_smscUser;
        global $_smscPasswd;
        global $_smscKeepAlive;

        $res = false;
        $lenMsg = strlen($msg);
        $date = date("Y-m-d H:i:s", time());

        //$params = (!empty($params))?"&".$params:$params;
        $kannel = array ( 'host' => $_smscIP, 'port' => $_smscPort, 'user' => $_smscUser, 'pass' => $_smscPasswd);
        $string = "PhoneNumber=$smsto&text=".urlencode($msg);//.$params;	//"&from=".urlencode($from).
        $smsurl = "GET /?user=".$kannel['user']."&password=".$kannel['pass']."&".$string." HTTP/1.1\r\nHost: $_smscIP:$_smscPort\r\nUser-Agent: LiveTech SMS Engine\r\nAccept: text/xml;\r\nKeep-Alive: $_smscKeepAlive\r\nContent-Length: 0\r\n\r\n";
        $sock = fsockopen($kannel['host'],$kannel['port'], $errno, $errstr, 30) or die("#: $errno <br>$errstr");
        //$url = "http://{$kannel['host']}:{$kannel['port']}/?$string";
        //$sock = fopen($url, "r") or die("Error: $url");

        if($sock)
        {
            fwrite($sock, $smsurl);
            $result = trim(fread($sock, 200));
            fclose($sock);

            if(strpos($result, "\n") !== false)
            {
                $rslt = explode("\n", $result);
                $test = trim($rslt[0]);
            } else {
                $test = $result;
            }

            switch(strtoupper($test))
            {
                case "HTTP/1.0 200 OK":
                    $status = "SENT";
                    $res = true;
                    break;

                case "HTTP/1.0 401 Authorization Required":
                    $status = "FAIL";
                    $res = false;
                    break;

                default:
                    $status = "UNKNOWN";
                    $res = false;
                    break;
            }

            //$res = true;
        } else {
            $status = "FAIL";
            $test = "Couldn't connect to gateway.";
            $res = false;
        }

        // Text logging
        $msg = str_replace("\n", "~~~", $msg);
        $fp = fopen("c:/xampp/htdocs/starlynx/sms/logs/nowsms.log", "a+");
        fwrite($fp, "[Date: $date] [Status: $status] [User: $from] [Mask: ".$params["mask"]."] [Recipient: $smsto] [Text: $msg] [Gateway: $_smscHost] [Account: $_smscUser] [Response: $test] [Len: $lenMsg] [SMS-ID: ".$params["sms-id"]."]\n");
        fclose($fp);
        //echo "[Date: $date] [Status: $status] [User: $from] [Mask: ".$params["mask"]."] [Recipient: $smsto] [Text: $msg] [Gateway: $_smscHost] [Account: $_smscUser] [Response: $test] [Len: $lenMsg] [SMS-ID: ".$params["sms-id"]."]\n";
        return $res;
}

function send_direct_sms($from, $smsto, $msg)
{
    global $_smscIP;
    global $_smscHost;
    global $_smscPort;
    global $_smscUser;
    global $_smscPasswd;

    $kannel = array ( 'host' => $_smscHost, 'port' => $_smscPort, 'user' => $_smscUser, 'pass' => $_smscPasswd);
    $smsto = preg_replace("/(^03)/","00923",$smsto);
    $smsto = preg_replace("/(^92)/","0092",$smsto);
    $string = "to=$smsto&text=".urlencode($msg)."&from=".urlencode($from);
    $smsurl = "http://".$kannel['host'].":".$kannel['port']."/cgi-bin/sendsms?user=".$kannel['user']."&pass=".$kannel['pass']."&".$string;
    $smsurl = "GET /cgi-bin/sendsms?user=".$kannel['user']."&pass=".$kannel['pass']."&".$string." HTTP/1.1\r\nHost: $_smscIP:$_smscPort\r\nUser-Agent:  SMSProgram\r\nAccept: text/xml;\r\nKeep-Alive: 300\r\nContent-Length: 0\r\n\r\n";
    $sock = fsockopen($kannel['host'],$kannel['port']);

    if($sock)
    {
        fwrite($sock, $smsurl);
        $result = fread($sock,200);
        fclose($sock);

        return true;
    } else {
        return false;
    }
}
?>