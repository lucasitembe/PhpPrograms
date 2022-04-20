<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>General Employees Performance ~ Pharmacy</b></legend>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Bill_Type'])){
		$Bill_Type = $_GET['Bill_Type'];
	}else{
		$Bill_Type = '';
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

	//create filter
	$filter = '';
	if($Bill_Type == 'Outpatient'){
		$filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and";
	}else if($Bill_Type == 'Inpatient'){
		$filter .= "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}

	$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_payments pp, tbl_employee_performance_cache pc, tbl_employee emp where
							pp.Employee_ID = pc.Employee_ID and
							emp.Employee_ID = pp.Employee_ID and
							$filter
							pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by pp.Employee_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$counter = 0;
		while($data = mysqli_fetch_array($select)){
			$temp = 0;
			$Employee_ID = $data['Employee_ID'];
?>
			<table width="100%">
				<tr id='thead'>
				    <td colspan="9"><b><?php echo ++$counter."<b>.</b>&nbsp;&nbsp;&nbsp;".strtoupper($data['Employee_Name']); ?></b></td>
				</tr>
				<tr><td colspan="9"><hr></td></tr>
				<tr id='thead'>
				    <td width=5%><b>SN</b></td>
				    <td width='12%'style='text-align: left;'><b>SPONSOR NAME</b></td>
				    <td width='12%'style='text-align: right;'><b>NO OF PATIENTS</b></td>
				    <td width='12%'style='text-align: right;'><b>CASH</b></td>
				    <td width='12%'style='text-align: right;'><b>CREDIT</b></td>
				    <td width='12%'style='text-align: right;'><b>CANCELLED</b></td>
				    <td width='12%'style='text-align: right;'><b>TOTAL</b></td>
				</tr>
				<tr><td colspan="9"><hr></td></tr>
<?php
			$slct = mysqli_query($conn,"select pp.Sponsor_ID, sp.Guarantor_Name from tbl_patient_payments pp, tbl_sponsor sp where
									pp.Sponsor_ID = sp.Sponsor_ID and
									pp.Employee_ID = '$Employee_ID' and
									$filter
									pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by pp.Sponsor_ID") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($slct);
			if($nm > 0){
				$col = 0;
				$Grand_Cash_Total = 0;
				$Grand_Credit_Total = 0;
				$Grand_Cancelled_Total = 0;
				$Total_Patients = 0;
				while($row = mysqli_fetch_array($slct)){
					$Sponsor_ID = $row['Sponsor_ID'];
					$Cash_Total = 0;
					$Credit_Total = 0;
					$Cancelled_Total = 0;
					//get number of patients
					$patients_num = mysqli_query($conn,"select Registration_ID from tbl_patient_payments pp where
													pp.Sponsor_ID = '$Sponsor_ID' and
													pp.Employee_ID = '$Employee_ID' and
													$filter
													pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by pp.Registration_ID") or die(mysqli_error($conn));
					$n_p = mysqli_num_rows($patients_num);
					$Total_Patients += $n_p;
					//calculate amount
					$get_amount = mysqli_query($conn,"select Price, Discount, Quantity, pp.Billing_Type, pp.Transaction_status, pp.payment_type from
												tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Employee_ID = '$Employee_ID' and
												pp.Sponsor_ID = '$Sponsor_ID' and
												$filter
												pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
					$n_amount = mysqli_num_rows($get_amount);
					if($n_amount > 0){
						while ($am = mysqli_fetch_array($get_amount)) {
							$Total = (($am['Price'] - $am['Discount']) * $am['Quantity']);
							if(((strtolower($am['Billing_Type']) == 'outpatient cash') or (strtolower($am['Billing_Type']) == 'inpatient cash' && strtolower($am['payment_type']) == 'pre')) and (strtolower($am['Transaction_status']) == 'active')){
					            $Cash_Total += $Total;
					            $Grand_Cash_Total += $Total;
					        }elseif(((strtolower($am['Billing_Type']) == 'outpatient cash') or (strtolower($am['Billing_Type']) == 'inpatient cash')) and (strtolower($am['Transaction_status']) == 'cancelled')){
					            $Cancelled_Total = $Cancelled_Total + $Total;
					            $Grand_Cancelled_Total += $Total;
					        }elseif(((strtolower($am['Billing_Type']) == 'outpatient credit') or (strtolower($am['Billing_Type']) == 'inpatient credit') or (strtolower($am['Billing_Type']) == 'inpatient cash' && strtolower($am['payment_type']) == 'post')) and (strtolower($am['Transaction_status']) == 'active')){
					            $Credit_Total += $Total;
					            $Grand_Credit_Total += $Total;
					        }elseif(((strtolower($am['Billing_Type']) == 'outpatient credit') or (strtolower($am['Billing_Type']) == 'inpatient credit')) and (strtolower($am['Transaction_status']) == 'cancelled')){
					            $Cancelled_Total = $Cancelled_Total + $Total;
					            $Grand_Cancelled_Total += $Total;
					        }
						}
					}
?>
					<tr>
					    <td><?php echo ++$col; ?></td>
					    <td><?php echo strtoupper($row['Guarantor_Name']); ?></td>
					    <td style='text-align: right;'><?php echo $n_p; ?></td>
					    <td style='text-align: right;'><?php echo number_format($Cash_Total); ?></td>
					    <td style='text-align: right;'><?php echo number_format($Credit_Total); ?></td>
					    <td style='text-align: right;'><?php echo number_format($Cancelled_Total); ?></td>
					    <td style='text-align: right;'><?php echo number_format($Credit_Total + $Cash_Total); ?></td>
					</tr>
<?php
				}
?>
				<tr><td colspan="9"><hr></td></tr>
				<tr>
				    <td colspan="2"><b>GRAND TOTAL</b></td>
				    <td style='text-align: right;'><?php echo $Total_Patients; ?></td>
				    <td style='text-align: right;'><?php echo number_format($Grand_Cash_Total); ?></td>
				    <td style='text-align: right;'><?php echo number_format($Grand_Credit_Total); ?></td>
				    <td style='text-align: right;'><?php echo number_format($Grand_Cancelled_Total); ?></td>
				    <td style='text-align: right;'><?php echo number_format($Grand_Credit_Total + $Grand_Cash_Total); ?></td>
				</tr>
<?php
			}
?>
				
			</table><br/><br/>
<?php
		}
	}else{
		$select = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_employee_performance_cache pc where emp.Employee_ID = pc.Employee_ID order by Employee_Name") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				$temp = 0;
				echo "<center><h4>NO TRANSACTIONS FOUND FROM SELECTED CASHIERS<br/></h4><br/>";
?>
				<table width="30%">
					<tr>
						<td width="10%"><b>SN</b></td>
						<td><b>CASHIER NAME</b></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
<?php
				while($row = mysqli_fetch_array($select)){
?>
					<tr>
						<td><?php echo ++$temp; ?></td>
						<td><?php echo strtoupper($row['Employee_Name']); ?></td>
					</tr>
<?php
				}
?>
			<tr><td colspan="2"><hr></td></tr>
		</table></center>
<?php
			}else{
				echo "<br/><br/><br/><center><h4>No Cashier Selected! Please Go Through 'CASHIERS CONFIGURATION' Button Above To Select Required Cashiers'<br/></h4></center><br/>";
			}
?>
<?php
	}
?>