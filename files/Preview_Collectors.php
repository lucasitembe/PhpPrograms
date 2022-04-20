<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total = 0;

	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
	}else{
		$Item_Category_ID = 0;
	}

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

	//GENERATE CATEGORY
	if($Item_Category_ID == 0){
		$Category_Name = 'All';
	}else{
		$get = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($get);
		if($no > 0){
			while ($data = mysqli_fetch_array($get)) {
				$Category_Name = $data['Item_Category_Name'];
			}
		}else{
			$Category_Name = '';
		}
	}

	//GENERATE SPONSOR NAME
	if($Sponsor_ID == 0){
		$Sponsor_Name = 'All';
	}else{
		$get = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($get);
		if($no > 0){
			while ($data = mysqli_fetch_array($get)) {
				$Sponsor_Name = $data['Guarantor_Name'];
			}
		}else{
			$Sponsor_Name = '';
		}
	}
?>
<center><br/>
	<table width="80%">
		<tr>
			<td width="85%">
				<table width="100%">
					<tr>
						<td width="20%" style="text-align: right;"><h4><b>Sponsor ~ </b></h4></td>
						<td><?php echo $Sponsor_Name; ?></td>
						<td width="20%" style="text-align: right;"><h4><b>Category ~ </b></h4></td>
						<td><?php echo $Category_Name; ?></td>
					</tr>
					<tr>
						<td width="20%" style="text-align: right;"><h4><b>Start Date ~ </b></h4></td>
						<td><?php echo $Date_From; ?></td>
						<td width="20%" style="text-align: right;"><h4><b>End Date ~ </b></h4></td>
						<td><?php echo $Date_To; ?></td>
					</tr>
				</table>
			</td>
			<td>
			<input type="button" name="Preview_Collectors_Report" id="Preview_Collectors_Report" class="art-button-green" value="PRINT REPORT" onclick="Preview_Collectors_Report('<?php echo $Item_Category_ID; ?>','<?php echo $Sponsor_ID; ?>','<?php echo $Bill_Type; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>')">
			</td>
	</table><br/>
</center>


<?php
	/*echo "Sponsor ~ ".$Sponsor_Name.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo "Category ~ ".$Category_Name.'<br/>';
	echo "Start Date ~ ".$Date_From.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo 'End Date ~ '.$Date_To.'<br/><br/>';*/

	if($Item_Category_ID == 0){
		if($Sponsor_ID == 0){
			if($Bill_Type == 'All'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' 
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}else if($Bill_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' 
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}else if($Bill_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' 
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}
		}else{
			if($Bill_Type == 'All'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}else if($Bill_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}else if($Bill_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
			}
		}
	}else{
		if($Sponsor_ID == 0){
			if($Bill_Type == 'All'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'a1');
			}else if($Bill_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' 
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'2');
			}else if($Bill_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' 
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'3');
			}
		}else{
			if($Bill_Type == 'All'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'4');
			}else if($Bill_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'5');
			}else if($Bill_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
									tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
									tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
									pp.Transaction_status <> 'cancelled' and
			                        pp.Employee_ID = emp.Employee_ID and
			                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
			                        ic.Item_category_ID = isc.Item_category_ID and
			                        i.Item_ID = ppl.Item_ID and
									ic.Item_category_ID = '$Item_Category_ID' and
			                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
			                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
			                        pp.Sponsor_ID = '$Sponsor_ID'
			                        group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn).'6');
			}
		}
	}

	$num = mysqli_num_rows($select);
	if($num > 0){
?>
	<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Items_Fieldset'>
	<table width="100%">
		<tr><td colspan="5"><hr></td></tr>
		<tr>
			<td width="7%"><b>SN</b></td>
			<td><b>COLLECTOR NAME</b></td>
			<td width="20%" style="text-align: right;"><b>TOTAL CASH</b></td>
			<td width="20%" style="text-align: right;"><b>TOTAL CREDIT</b></td>
			<td width="20%" style="text-align: right;"><b>GRAND TOTAL&nbsp;&nbsp;</b></td>
		</tr>
		<tr><td colspan="5"><hr></td></tr>
<?php
		while ($data = mysqli_fetch_array($select)) {
			$Employee_ID = $data['Employee_ID'];

			//get cash and credit collection based on selected employee
			if($Item_Category_ID == 0){
				if($Sponsor_ID == 0){
					if($Bill_Type == 'All'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}else if($Bill_Type == 'Outpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}else if($Bill_Type == 'Inpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}
				}else{
					if($Bill_Type == 'All'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}else if($Bill_Type == 'Outpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}else if($Bill_Type == 'Inpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					}
				}
			}else{
				if($Sponsor_ID == 0){
					if($Bill_Type == 'All'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'a1');
					}else if($Bill_Type == 'Outpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'2');
					}else if($Bill_Type == 'Inpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'3');
					}
				}else{
					if($Bill_Type == 'All'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'4');
					}else if($Bill_Type == 'Outpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'5');
					}else if($Bill_Type == 'Inpatient'){
						$get_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, pp.payment_type from 
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_items i where
											pp.Transaction_status <> 'cancelled' and
					                        pp.Employee_ID = emp.Employee_ID and
					                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					                        (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
					                        ic.Item_category_ID = isc.Item_category_ID and
					                        i.Item_ID = ppl.Item_ID and
					                        ic.Item_category_ID = '$Item_Category_ID' and
					                        isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
					                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
					                        pp.Sponsor_ID = '$Sponsor_ID' and
					                        pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn).'6');
					}
				}
			}
			$no = mysqli_num_rows($get_details);
			if($no > 0){
				$Total_Cash = 0; $Total_Credit = 0;
				while ($row = mysqli_fetch_array($get_details)) {
					$payment_type = $row['payment_type'];
					if(strtolower($row['Billing_Type']) == 'outpatient cash' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
						$Total_Cash += $row['Amount'];
						$Grand_Total += $row['Amount'];
					}else if(strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')){
						$Total_Credit += $row['Amount'];
						$Grand_Total += $row['Amount'];
					}
				}
				$Grand_Total_Cash += $Total_Cash;
				$Grand_Total_Credit += $Total_Credit;
			}

?>
		<tr>
			<td width="5%"><?php echo ++$temp; ?>.</td>
			<td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
			<td style="text-align: right;"><?php echo number_format($Total_Cash); ?></td>
			<td style="text-align: right;"><?php echo number_format($Total_Credit); ?></td>
			<td style="text-align: right;"><?php echo number_format($Total_Cash + $Total_Credit); ?>&nbsp;&nbsp;</td>
		</tr>
<?php
		}
		echo '<tr><td colspan="5"><hr></td></tr>';
		echo '<tr><td colspan="2" style="text-align: left"><b>GRAND TOTAL</td>
					<td style="text-align: right;"><b>'.number_format($Grand_Total_Cash).'</b></td>
					<td style="text-align: right;"><b>'.number_format($Grand_Total_Credit).'</b></td>
					<td style="text-align: right;"><b>'.number_format($Grand_Total).'&nbsp;&nbsp;</b></td></tr>';
		echo '<tr><td colspan="5"><hr></td></tr></table>';
	}else{
		echo "<br/><br/><center><h3><b>NO COLLECTORS FOUND</b></h3></center>";
	}
?>