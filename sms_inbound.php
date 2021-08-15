<?
    include("common/common.start.php");

    $keyword = strtoupper($keyword);

    switch($keyword) {
        case "TO":
            include("sms/inbound_TO.php");
            break;

        default:
            include("sms/inbound_generic.php");
            break;
    }

    include("common/common.end.php");
?>