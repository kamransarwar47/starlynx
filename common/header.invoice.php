<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" id="invoice_header">
    <tr>
        <td width="<?php echo ($show_qr_code) ? '30%' : '33%'; ?>"><img src="images/logo-invoice.png" width="75" border="0" /></td>
        <td width="<?php echo ($show_qr_code) ? '30%' : '34%'; ?>" align="center">
            <span id="office_name"><?=$CFG_OFFICE_NAME?></span><br />
            <span id="office_address"><?=$CFG_OFFICE_ADDRESS?></span>
        </td>
        <td width="<?php echo ($show_qr_code) ? '30%' : '33%'; ?>" align="right">
            <span id="office_contact">
                Phone: <?=$CFG_OFFICE_PHONE?><br />
                Email: <?=$CFG_OFFICE_EMAIL?><br />
                Web: <?=$CFG_OFFICE_WEB?>
            </span>
        </td>
        <?php
        if($show_qr_code) {
        ?>
        <td width="10%" align="right">
            <img style="height: 120px;" src="<?php echo $qr_file_name; ?>" alt="Invoice QR code">
        </td>
        <?php
        }
        ?>
    </tr>
</table>