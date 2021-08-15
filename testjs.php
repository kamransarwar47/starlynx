<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test JS</title>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#test").click(function(){
        $("#divMsg").fadeIn(400).delay(5000).fadeOut(400);
    });
});
</script>
</head>
<body>
<input type="button" id="test" value="Click" />
<div id="divMsg" style="width: 200px; height: 100px; border: 1px solid #000000; background-color: #eeeeee; display: none; position: absolute; left: 80%; top: 80%;">
    You have a new message.
</div>
</body>
</html>