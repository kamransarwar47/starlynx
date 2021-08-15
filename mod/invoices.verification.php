<table border="0" width="30%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td class="top_left"></td>
        <td class="bg_title"><img src="images/customers.png" width="31" height="20" border="0" align="absmiddle" style="margin-bottom: 7px;" /> <p class="box_title" style="display: inline; font-size: 14px;">Invoice Verification</p></td>
        <td class="top_right"></td>
    </tr>
    <tr>
        <td class="border_left"></td>
        <td>
            <video id="preview" style="width: 100%;"></video>
        </td>
        <td class="border_right"></td>
    </tr>
    <tr>
        <td class="bottom_left"></td>
        <td class="border_bottom"></td>
        <td class="bottom_right"></td>
    </tr>
</table>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, backgroundScan: false });
    //
    Instascan.Camera.getCameras().then(function (cameras){
        if(cameras.length > 0){
            scanner.start(cameras[0]);
        }else{
            alert('No cameras found.');
        }
    }).catch(function(e){
        console.error(e);
        alert(e);
    });
    //
    var siteUrl = "<?=$_siteRoot?>";
    scanner.addListener('scan',function(content){
        window.location.href = siteUrl + "index.php?ss=<?=$ss?>&mod=invoices.view&voucher_number=" + content;
    });
</script>