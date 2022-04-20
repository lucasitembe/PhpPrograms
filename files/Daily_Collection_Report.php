<?php
	session_start();
	include("./includes/connection.php");
	$filter = '';

	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}
	
	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}
	
	$date_from = strtotime($Start_Date); // Convert date to a UNIX timestamp  
	$date_to = strtotime($End_Date); // Convert date to a UNIX timestamp

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	
	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}
	
?>
<fieldset style='overflow-y:scroll; height:360px; background-color: white;'>
	<table width="100%">
		<tr><td colspan="7"><hr></td></tr>
	    <tr>
	        <td width="3%"><b>SN</b></td>
	        <td><b>TRANSACTIONS DATE</b></td>
	        <td width="13%" style="text-align: right;"><b>CASH</b>&nbsp;&nbsp;&nbsp;</td>
	        <td width="13%" style="text-align: right;"><b>CREDIT</b>&nbsp;&nbsp;&nbsp;</td>
	        <!-- <td width="13%" style="text-align: right;"><b>MSAMAHA</b>&nbsp;&nbsp;&nbsp;</td> -->
	        <td width="13%" style="text-align: right;"><b>CANCELLED</b>&nbsp;&nbsp;&nbsp;</td>
	        <td width="13%" style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
	    </tr>
	    <tr><td colspan="7"><hr></td></tr>
<?php
	$temp = 0;
	$no_of_days = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Cancelled = 0;
	$Grand_Total_Msamaha = 0;

	// Loop from the start date to end date and output all dates inbetween  
	for ($i=$date_from; $i<=$date_to; $i+=86400) {
		$Total_Cash = 0;
		$Total_Credit = 0;
		$Total_Cancelled = 0;
		$Total_Msamaha = 0;
		$no_of_days++;
		$Current_Date = date("Y-m-d", $i);
		
		//get details
		$select = mysqli_query($conn,"SELECT pp.auth_code, pp.Pre_Paid,sp.Exemption,pp.Billing_Type, pp.Transaction_status, sum((price - discount)*quantity) as Amount, pp.payment_type,pp.Payment_Code,pp.payment_mode,pp.manual_offline
								from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_sponsor sp where
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								sp.Sponsor_ID = pp.Sponsor_ID and
								$filter
								pp.Receipt_Date = '$Current_Date'
								group BY ppl.patient_payment_id order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($data = mysqli_fetch_array($select)){
                            $Billing_Type=$data['Billing_Type'];
				// if(isset($_SESSION['systeminfo']['Inpatient_Prepaid']) && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
				// 	if(((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid']==0) ||strtolower($Billing_Type) =="patient from outside"|| (strtolower($data['Billing_Type']) == 'inpatient cash'&& $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
				// 		$Total_Cash += $data['Amount'];
				// 	}else if(($data['Exemption']!='yes') && (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || ((strtolower($data['Billing_Type']) == 'inpatient cash') && $data['payment_type']== 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
				// 		$Total_Credit += $data['Amount'];
				// 	}else if(strtolower($data['Transaction_status']) == 'cancelled'){
				// 		$Total_Cancelled += $data['Amount'];
				// 	}else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	            //         $Total_Msamaha += $data['Amount'];;
			            
                //     } 
				// }else{
					if(((strtolower($data['Billing_Type']) == 'outpatient cash'&& $data['Pre_Paid']==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'  && ($data['auth_code'] != '' || $data['manual_offline'] = 'manual' || ($data['Payment_Code'] != '' && ($data['payment_mode']== 'CRDB' || $data['payment_mode']== 'CRDB..' )))){
						$Total_Cash += $data['Amount'];
					}
					else if((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') ||  (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
						$Total_Credit += $data['Amount'];
					}else if(strtolower($data['Transaction_status']) == 'cancelled'){
						$Total_Cancelled += $data['Amount'];
					}else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	                    $Total_Msamaha += $data['Amount'];;
			            
                    } 
				// }
			}
		}
?>
	    <tr>
	        <td><?php echo ++$temp; ?><b>.</b></td>
	        <td><?php echo date("d-m-Y", strtotime($Current_Date))."--".date("l", strtotime($Current_Date)); ?></td>
	        <td style="text-align: right;"><?php echo number_format($Total_Cash); ?>&nbsp;&nbsp;&nbsp;</td>
	        <td style="text-align: right;"><?php echo number_format($Total_Credit); ?>&nbsp;&nbsp;&nbsp;</td>
	        <!-- <td style="text-align: right;">< ?php echo number_format($Total_Msamaha); ?>&nbsp;&nbsp;&nbsp;</td> -->
	        <td style="text-align: right;"><?php echo number_format($Total_Cancelled); ?>&nbsp;&nbsp;&nbsp;</td>
	        <td style="text-align: right;"><?php echo number_format($Total_Cash + $Total_Credit); ?>&nbsp;&nbsp;&nbsp;</td>
	    </tr>
<?php
		//get grand total
		$Grand_Total_Cash += $Total_Cash;
		$Grand_Total_Credit += $Total_Credit;
		$Grand_Total_Cancelled += $Total_Cancelled;
		$Grand_Total_Msamaha += $Total_Msamaha;

		if(($temp%4) == 0){
			echo "<tr><td colspan='7'><hr></td></tr>";
		}
	}

?>
	</table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td width="16.7%"><b>NO OF DAYS : <?php echo $no_of_days; ?></b></td>
			<td width="16.7%"><b>TOTAL CASH : <?php echo number_format($Grand_Total_Cash); ?></b></td>
			<td width="16.7%"><b>TOTAL CREDIT : <?php echo number_format($Grand_Total_Credit); ?></b></td>
			<!-- <td width="16.7%"><b>TOTAL MSAMAHA : < ?php echo number_format($Grand_Total_Msamaha); ?></b></td> -->
			<td width="16.7%"><b>TOTAL CANCELLED : <?php echo number_format($Grand_Total_Cancelled); ?></b></td>
			<td width="16.7%"></td>
			<td width="16.7%"><b>GRAND TOTAL : <?php echo number_format($Grand_Total_Cash + $Grand_Total_Credit); ?></b></td>
		</tr>
	</table>
</fieldset>