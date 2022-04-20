<?php

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}


	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}


	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = '';
	}


	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = '';
	}


	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	echo 'HERE';

	echo "<a href='generalperformancereport.php?Date_From=".$Date_From."&Date_To=".$Date_To."&Billing_Type=".$Billing_Type."&Employee_ID=".$Employee_ID."' class='art-buttonj'>PREVIEW REPORT</a>";
	//?Date_From=".$Date_From."&Date_To=".$Date_To."&Billing_Type=".$Billing_Type."&Employee_ID=".$Employee_ID."&Sponsor_ID=".$Sponsor_ID."' class='art-button-green' target='_blank'>PREVIEW REPORT</a>
?>