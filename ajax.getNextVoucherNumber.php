<?
    include("common/common.start.php");

    if(!empty($project_id) && !empty($voucher_type)) {
        $voucherNum = getNextVoucherNumber($project_id, $voucher_type);

        echo $voucherNum;
    } else {
        echo "xx-x-xxxx-xxxxx";
    }

    include("common/common.end.php");
?>