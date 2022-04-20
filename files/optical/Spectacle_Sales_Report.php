<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
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
?>
<legend align="right">SPECTACLES SALES REPORT</legend>
<table width="100%" class="table table-striped table-hover">
	<thead style="background-color:#bdb5ac">

			<tr>
				<th style="text-align:left;"><b>SN</b></th>
				<th><b>PARTICULARS</b></th>
				<th style="text-align:left;"><b>CASH QTY</b></th>
				<th style="text-align:left;"><b>CASH VALUE</b>&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th style="text-align:left;"><b>CREDIT QTY</b></th>
				<th style="text-align:left;"><b>CREDIT VALUE</b>&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th style="text-align:left;"><b>TOTAL QTY</b></td>
				<th style="text-align:left;"><b>TOTAL VALUE</b>&nbsp;&nbsp;&nbsp;&nbsp;</th>
			</tr>
	</thead>

    <tr><td colspan="8"><hr></td></tr>
<?php
	if($Sponsor_ID == 0){
		// $select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
		// 					from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
		// 					i.Item_ID = ppl.Item_ID and
		// 					pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
		// 					i.Consultation_Type = 'Optical' and
		// 					pp.Transaction_status <> 'cancelled' and
		// 					pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
		$select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
							from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
							i.Item_ID = ppl.Item_ID and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							i.Consultation_Type = 'Optical' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and
							pp.Transaction_status <> 'cancelled' and
							pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));

	}else{
		// $select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
		// 					from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
		// 					i.Item_ID = ppl.Item_ID and
		// 					pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
		// 					pp.Sponsor_ID = '$Sponsor_ID' and
		// 					i.Consultation_Type = 'Optical' and
		// 					pp.Transaction_status <> 'cancelled' and
		// 					pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
		$select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
							from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
							i.Item_ID = ppl.Item_ID and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							i.Consultation_Type = 'Optical' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and
							pp.Transaction_status <> 'cancelled' and
							pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
	}
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		$Grand_Total_Quantity_Cash = 0;
		$Grand_Total_Quantity_Credit = 0;
		$Grand_Total_Value_Cash = 0;
		$Grand_Total_Value_Credit = 0;
		while ($data = mysqli_fetch_array($select)) {
			$Total_Quantity_Cash = 0;
			$Total_Quantity_Credit = 0;
			$Total_Value_Cash = 0;
			$Total_Value_Credit = 0;
			$Item_ID = $data['Item_ID'];

			//generate quantity & Total
			if($Sponsor_ID == 0){
				// $select_details = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
				// 					from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
				// 					i.Item_ID = ppl.Item_ID and
				// 					i.Item_ID = '$Item_ID' and
				// 					pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				// 					i.Consultation_Type = 'Optical' and ilc.Check_In_Type='Optical' and
				// 					pp.Transaction_status <> 'cancelled' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and  ilc.Status='dispensed' and pp.Transaction_status = 'active' and 
				// 					pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
				$select_details = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
									from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									i.Item_ID = ppl.Item_ID and
									i.Item_ID = '$Item_ID' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									i.Consultation_Type = 'Optical' and
									pp.Transaction_status <> 'cancelled' and
									pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));

			}else{
				// $select_details = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
				// 					from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
				// 					i.Item_ID = ppl.Item_ID and
				// 					i.Item_ID = '$Item_ID' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and 
				// 					pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				// 					pp.Sponsor_ID = '$Sponsor_ID' and
				// 					i.Consultation_Type = 'Optical'  and ilc.Check_In_Type='Optical' and pp.Transaction_status = 'active' and
				// 					pp.Transaction_status <> 'cancelled' and 
				// 					pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
				$select_details = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
									from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									i.Item_ID = ppl.Item_ID and
									i.Item_ID = '$Item_ID' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									pp.Sponsor_ID = '$Sponsor_ID' and
									i.Consultation_Type = 'Optical' and
									pp.Transaction_status <> 'cancelled' and
									pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));

			}
			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					if(strtolower($row['Billing_Type']) == 'outpatient cash' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
						$Total_Quantity_Cash += $row['Quantity'];
						$Total_Value_Cash += (($row['Price'] - $row['Discount']) * $row['Quantity']);
						$Grand_Total_Quantity_Cash += $row['Quantity'];
						$Grand_Total_Value_Cash += (($row['Price'] - $row['Discount']) * $row['Quantity']);
					}else if(strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
						$Total_Quantity_Credit += $row['Quantity'];
						$Total_Value_Credit += (($row['Price'] - $row['Discount']) * $row['Quantity']);
						$Grand_Total_Quantity_Credit += $row['Quantity'];
						$Grand_Total_Value_Credit += (($row['Price'] - $row['Discount']) * $row['Quantity']);
					}


					
				}
			}
?>
			<tr> 
		        <td><?php echo ++$temp; ?></td>
		        <td><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo $data['Product_Name']; ?></a></td>
		        <td style="text-align: center;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Quantity_Cash); ?></a></td>
		        <td style="text-align: right;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Value_Cash); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        <td style="text-align: center;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Quantity_Credit); ?></a></td>
		        <td style="text-align: right;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Value_Credit); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        <td style="text-align: center;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Quantity_Credit + $Total_Quantity_Cash); ?></a></td>
		        <td style="text-align: right;"><a href="search_item_spectacle_patient_list.php?Product_Name=<?php echo $data['Product_Name']; ?>&Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Item_ID=<?php echo $Item_ID; ?>"><?php echo number_format($Total_Value_Credit + $Total_Value_Cash); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
<?php
		}
?>
		<tr><td colspan="8"><hr></td></tr>
		<tr>
			<td colspan="2"><b>TOTAL</b></td>
			<td style="text-align: center;"><b><?php echo $Grand_Total_Quantity_Cash; ?></b></td>
			<td style="text-align: right;"><b><?php echo number_format($Grand_Total_Value_Cash); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style="text-align: center;"><b><?php echo $Grand_Total_Quantity_Credit; ?></b></td>
			<td style="text-align: right;"><b><?php echo number_format($Grand_Total_Value_Credit); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style="text-align: center;"><b><?php echo ($Grand_Total_Quantity_Credit + $Grand_Total_Quantity_Cash); ?></b></td>
			<td style="text-align: right;"><b><?php echo number_format($Grand_Total_Value_Credit + $Grand_Total_Value_Cash); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
<?php
	}
?>
</table>
<br/><br/><br/>