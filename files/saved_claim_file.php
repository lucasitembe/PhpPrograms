<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$Bill_ID = $_GET['Bill_ID'];
	$contents=  file_get_contents("/var/www/html/ehmsbmc/NHIF_FILE/".$Bill_ID."_claim_file.txt");
    $data = base64_decode($contents);
	//echo $Bill_ID."_claim_file.txt";
	header('Content-Type: application/pdf');
	echo $data;


?>