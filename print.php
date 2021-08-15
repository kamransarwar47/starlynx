<?
    include("common/common.start.php");

    // Security
    if(!empty($ss)) {
        include("common/check_session.php");

        // User Info
        $userInfo = getSignedUserInfo($ss);
    }

    // Invoice QR Code
    $show_qr_code = false;
    $qr_print_invoices = array(
        // printmod => variable_name
        'invoice' => 'voucher_number'
    );

    if(array_key_exists($printmod, $qr_print_invoices)){
        include('lib/phpqrcode/qrlib.php');
        $qr_file_name = "images/qrcode.png";
        QRcode::png($$qr_print_invoices[$printmod], $qr_file_name);
        $show_qr_code = true;
    }

    include("print/$printmod.php");
    include("common/common.end.php");
?>