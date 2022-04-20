<?php 
	$file = file_get_contents("../NHIF_FILE/91_claim_file.txt");
	header("Content-Type: application/pdf");

	echo base64_decode($file);
 ?>