// JavaScript Document

$(document).ready(function () {
    $("input:submit").button();

    document.title = (($("p.box_title").text() != '') ? $("p.box_title").text() + " - " : "") + document.title;
});

function checkMessages() {
    $.ajax({
        url: siteRoot + "ajax.checkMessage.php?ss=" + ss,
        success: function (data) {
            if (data != "") {
                $('#notices').html(data);
                $("#notices").fadeIn(400).delay(5000).fadeOut(400);
                //$("#notices").show();
            }
        }
    });

    setTimeout(checkMessages, 60000);
}

function confirmDelete(url) {
    var m = confirm("Are you sure, you want to delete this record?");

    if (m) {
        location.href = url;
    } else {
        return false;
    }
}

function confirmHandover(url) {
    var m = confirm("Click OK to confirm.");

    if (m) {
        location.href = url;
    } else {
        return false;
    }
}

$(document).ready(function () {
    if ($(".select2")[0]) {
        $('.select2').select2();
    }
});

function number_format(number, decimals, dec_point, thousands_point) {

    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
}

$(document).ready(function() {
    $('#searchQuerySubmit').click(function() {
        var str = $('#searchQueryInput').val();
        let re = new RegExp(/[A-Z]{2}-[R|C]{1}-[0-9]+$/gmi);
        if(str == '') {
            alert('Enter Search Shortcode');
        } else if (!re.test(str)) {
            alert('Enter Correct Shortcode eg. AB-R/C-123');
        } else {
            $.ajax({
                url: siteRoot + "ajax.searchShortCode.php?search=" + str,
                success: function (data) {
                    response = JSON.parse(data);
                    if(response.success) {
                        window.location.href = siteRoot + 'index.php?ss=' + ss + '&mod=rpt.plots.statement&project_id=' + response.project_id + '&plot_id=' + response.plot_id;
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
        
    });
});