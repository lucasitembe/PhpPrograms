<?php
	session_start();
	if(isset($_GET['Session']) && isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
		echo "yes";
	}else{
		if(isset($_SESSION['Transaction_Mode'])){
			echo "yes";
		}else{
			echo "no";
		}
	}
?>