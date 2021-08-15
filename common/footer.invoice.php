<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" id="invoice_footer">
                <tr>
                    <td width="20%" style="padding-top: 40px; font-size: 10px;" align="center">
                        <div style="border-top: 1px solid #000; width: 100px; text-align: center;">(<?=$_signature?>)</div>
                    </td>
                    <td width="65%" align="center" style=" font-size: 10px;">
                        <? if(!empty($notes)) { ?>
                            <?=$notes?>
                           <? } ?>
                    </td>
                    <td width="15%" align="right" style=" font-size: 10px;">
                        <span style="font-style: italic;"><?=$_copyof?></span>
                    </td>
                </tr>
            </table>