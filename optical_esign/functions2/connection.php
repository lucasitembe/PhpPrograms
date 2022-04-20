<?php

// 1. Create a database connection
	$connection = mysql_connect("192.168.4.15","ehmsuser","ehms2gpitg2014");
	if (!$connection) {
		die("Database connection failed: " . mysql_error());
	}
	
	// 2. Select a database to use  acc_fresh
	
        $db_select = mysql_select_db("ehms_database",$connection);
         
	if (!$db_select) {
	   die("Database selection failed: " . mysql_error());
	}
?>
