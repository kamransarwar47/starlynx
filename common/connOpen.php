<?PHP
	// Database Connection: Opens Connection
	$conn = mysql_connect($_dbServer, $_dbUser, $_dbPassword) or die(mysql_error());
	$db = mysql_select_db($_dbName, $conn) or die(mysql_error($conn));
?>