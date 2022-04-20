<?php
	$back = $_SERVER["HTTP_REFERER"];
	@session_start();
	if(isset($_GET['Sponsor'])){
		$_SESSION['RadiologyReportSponsor'] = $_GET['Sponsor'];
	}
	header("Location: $back");
	
?>