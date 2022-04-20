<?php

	// 1. Create a database connection
	$connectionWeberp = mysql_connect("127.0.0.1","root","");
	if (!$connectionWeberp) {
		die("Database connection failed: " . mysqli_error($conn));
	}
	
	// 2. Select a database to use 
	$db_select_web = mysql_select_db("weberp",$connectionWeberp);
	if (!$db_select_web) {
		die("Database selection failed: " . mysqli_error($conn));
	}
?>
