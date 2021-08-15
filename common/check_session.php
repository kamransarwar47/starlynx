<?
	$alive = checkSession($ss);
	
	if($alive) {
		refreshSession($ss);
	} else {
		logout($ss);
		setMessage("Your session has been expired.");
		mysql_close($conn);
		header("Location: index.php");
		exit;
	}
?>