<?php
	session_start();
	include("./includes/connection.php");
	$filter = '';

	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 'All';
	}

	if(isset($_GET['Start_Receipt'])){
		$Start_Receipt = $_GET['Start_Receipt'];
	}else{
		$Start_Receipt = '';
	}

	if(isset($_GET['End_Receipt'])){
		$End_Receipt = $_GET['End_Receipt'];
	}else{
		$End_Receipt = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '0';
	}

	//swap receipt value if and only if end receipt value is less than start receipt
	if($End_Receipt < $Start_Receipt){
		$Temp_Value = $End_Receipt;
		$End_Receipt = $Start_Receipt;
		$Start_Receipt = $Temp_Value;
	}

	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}

	$Title = '<tr><td colspan="9"><hr></td></tr>
				<tr>
			        <td width="3%"><b>SN</b></td>
			        <td><b>PATIENT NAME</b></td>
			        <td width="14%"><b>SPONSOR NAME</b></td>
			        <td width="14%"><b>EMPLOYEE CREATED</b></td>
			        <td width="10%"><b>RECEIPT #</b></td>
			        <td width="12%"><b>RECEIPT DATE</b></td>
			        <td width="10%"><b>BILLING TYPE</b></td>
			        <td width="10%"><b>STATUS</b></td>
			        <td width="10%" style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    </tr>
			    <tr><td colspan="9"><hr></td></tr>';
?>
<fieldset style='overflow-y:scroll; height:410px; background-color: white;'>
<table width="100%">
<?php
	echo $Title;
	$select = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, emp.Employee_Name, pp.Transaction_status,
							sum((price - discount)*quantity) as Amount, sp.Guarantor_Name, pr.Patient_Name, pp.payment_type
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp where
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pr.Registration_ID = pp.Registration_ID and
							sp.Sponsor_ID = pp.Sponsor_ID and
							emp.Employee_ID = pp.Employee_ID and
							$filter
							pp.Patient_Payment_ID between '$Start_Receipt' and '$End_Receipt'
							group BY ppl.patient_payment_id order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	$temp = 0;
	$Total_Cash = 0;
	$Total_Credit = 0;
	$Total_Cancelled = 0;
	if($no > 0){
		while($data = mysqli_fetch_array($select)){
?>
		    <tr>
		        <td><?php echo ++$temp; ?></td>
		        <td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
		        <td><?php echo $data['Guarantor_Name']; ?></td>
		        <td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
		        <td><?php echo $data['Patient_Payment_ID']; ?></td>
		        <td><?php echo $data['Payment_Date_And_Time']; ?></td>
		        <td><?php echo $data['Billing_Type']; ?></td>
		        <td><?php echo $data['Transaction_status']; ?></td>
		        <td style="text-align: right;"><?php echo number_format($data['Amount']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    </tr>
<?php
			//get total
			if((strtolower($data['Billing_Type']) == 'outpatient cash' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
				$Total_Cash += $data['Amount'];
			}else if((strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
				$Total_Credit += $data['Amount'];
			}else if(strtolower($data['Transaction_status']) == 'cancelled'){
				$Total_Cancelled += $data['Amount'];
			}
			if(($temp%20) == 0){
				echo $Title;
			}
		}
	}
?>
</table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td width="20%" style="text-align: left;"><b>No of Receipts : <?php echo $no; ?></b></td>
			<td width="20%" style="text-align: left;"><b>Total Cash : <?php echo number_format($Total_Cash); ?></b></td>
			<td width="20%" style="text-align: left;"><b>Total Credit : <?php echo number_format($Total_Credit); ?></b></td>
			<td width="20%" style="text-align: left;"><b>Total Cancelled : <?php echo number_format($Total_Cancelled); ?></b></td>
			<td width="20%" style="text-align: left;"><b>Grand Total : <?php echo number_format($Total_Credit + $Total_Cash); ?></b></td>
		</tr>
	</table>
</fieldset>