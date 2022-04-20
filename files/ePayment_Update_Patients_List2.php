<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
	$Grand_Total = 0;
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

	if(isset($_GET['Report_Type'])){
		$Report_Type = $_GET['Report_Type'];
	}else{
		$Report_Type = '';
	}

	if(isset($_GET['Payment_Mode'])){
		$Payment_Mode = $_GET['Payment_Mode'];
	}else{
		$Payment_Mode = '';
	}
?>
	<legend align=right><b>ePayment Collections Reports</b></legend>
    <center>
        <table width = 100%>
            <tr>
                <td width="5%">SN</td>
                <td>PATIENT NAME</td>
                <td width="10%">PATIENT NUMBER</td>
                <td width="12%">SPONSOR</td>
                <td width="15%" style="text-align: right;">PREPARED DATE</td>
                <td width="15%" style="text-align: right;">EMPLOYEE PREPARED</td>
                <td width="10%" style="text-align: right;">AMOUNT REQUIRED</td>
            </tr>
            <tr><td colspan="7"><hr></td></tr>
