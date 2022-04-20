<?php
	session_start();
	include("./includes/connection.php");
	$temp= 0;
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

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(strtolower($Transaction_Type) == 'pending'){
		$sql = '(btc.Transaction_Status = "pending" or btc.Transaction_Status = "uploaded") and';
	}else if(strtolower($Transaction_Type) == 'all'){
		$sql = '(btc.Transaction_Status = "pending" or btc.Transaction_Status = "uploaded" or btc.Transaction_Status = "Completed") and';
	}else{
		$sql = 'btc.Transaction_Status = "Completed" and';
	}
?>
<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>ePAYMENT TRANSACTION REPORTS</b></legend>
<table width=100% border=1>
	<?php
	    echo "<tr id='thead'>
	            <td width=5%><b>SN</b></td>
	            <td><b>PATIENT NAME</b></td>
	            <td style='text-align: left;'><b>SPONSOR</b></td>
	            <td style='text-align: right;'><b>PAYMENT CODE</b></td>
	            <td style='text-align: right;'><b>AMOUNT REQUIRED</b></td>
	            <td style='text-align: right;'><b>STATUS</b></td>
	        </tr>";
	    echo '<tr><td colspan="6"><hr></td></tr>';

	    //retrieve data
		$result = "select pr.Patient_Name, sp.Guarantor_Name, btc.Payment_Code, btc.Amount_Required, btc.Transaction_Status from
				tbl_patient_registration pr, tbl_sponsor sp, tbl_bank_transaction_cache btc where
				pr.Sponsor_ID = sp.Sponsor_ID and
				btc.Registration_ID = pr.Registration_ID and
				".$sql."
				btc.Transaction_Date_Time between '".$Date_From."' and '".$Date_To."'";
	    

	    $select = mysqli_query($conn,$result);
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
?>
			<tr id='thead'>
	            <td width=5%><?php echo ++$temp; ?></td>
	            <td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
	            <td style='text-align: left;'><?php echo $data['Guarantor_Name']; ?></td>
	            <td style='text-align: right;'><?php echo $data['Payment_Code']; ?></td>
	            <td style='text-align: right;'><?php echo number_format($data['Amount_Required']); ?></td>
	            <td style='text-align: right;'><?php echo 'STATUS'; ?></td>
	        </tr>
<?php
	    	}
	    }
	?>
        </td>
    </tr>
</table>