<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From  = '';
	}


	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}



	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}


	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}
?>
<input type="button" name="Preview_Paid" id="Preview_Paid" value="PREVIEW PAID & UNPAID DETAILS" class="art-button-green" onclick="Preview_Paid_Details('<?php echo $Sponsor_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Employee_ID; ?>')">
<a href="patientregistrationreport.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Employee_ID=<?php echo $Employee_ID; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>" class="art-button-green" target="_blank">PREVIEW REPORT</a>