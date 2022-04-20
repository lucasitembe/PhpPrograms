<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
?>

<?php
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Sponsor_ID,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, bd.Bed_Name
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp,tbl_beds bd where
							cd.Admission_ID = ad.Admision_ID and
							bd.Bed_ID = ad.Bed_ID and
							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							ad.Admission_Status = 'Pending' and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Registration_ID = $data['Registration_ID'];
			$Gender = $data['Gender'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Member_Number = $data['Member_Number'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Employee_Name = $data['Employee_Name'];
			$Admission_Date_Time = $data['Admission_Date_Time'];
			$Bed_Name = $data['Bed_Name'];
			$Hospital_Ward_Name = $data['Hospital_Ward_Name'];
			$Sponsor_ID = $data['Sponsor_ID'];

			//calculate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
		}
	}else{
		$Patient_Name = '';
		$Registration_ID = '';
		$Gender = '';
		$Date_Of_Birth = '';
		$Member_Number = '';
		$Guarantor_Name = '';
		$Employee_Name = '';
		$Admission_Date_Time = '';
		$Bed_Name = '';
		$Hospital_Ward_Name = '';
		$Sponsor_ID = '';
	}

	//get last Patient_Bill_ID
	$select = mysqli_query($conn,"select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where 
							Registration_ID = '$Registration_ID' and
							Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Bill_ID = $data['Patient_Bill_ID'];
			$Sponsor_ID = $data['Sponsor_ID'];
			$Folio_Number = $data['Folio_Number'];
		}
	}else{
		$Patient_Bill_ID = '';
		$Sponsor_ID = '';
		$Folio_Number = '';
	}
?>

<fieldset>
	<table width="100%">
		<tr>
			<td width="25%"><b>Patient Name &nbsp;&nbsp;&nbsp;</b><?php echo ucwords(strtolower($Patient_Name)); ?></td>
			<td width="25%"><b>Patient Number &nbsp;&nbsp;&nbsp;</b><?php echo $Registration_ID; ?></td>
			<td width="20%"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b><?php echo strtoupper($Guarantor_Name); ?></td>
			<td width="15%"><b>Member Number &nbsp;&nbsp;&nbsp;</b><?php echo $Member_Number; ?></td>
			<td width="15%"><b>&nbsp;&nbsp;&nbsp;Folio Number &nbsp;&nbsp;&nbsp;</b><?php echo $Folio_Number; ?></td>
		</tr>
		<tr>
			<td><b>Gender &nbsp;&nbsp;&nbsp;</b><?php echo $Gender; ?></td>
			<td><b>Admitted By &nbsp;&nbsp;&nbsp;</b><?php echo $Employee_Name; ?></td>
			<td><b>Admission Date &nbsp;&nbsp;&nbsp;</b><?php echo $Admission_Date_Time; ?></td>
			<td colspan="2"><b>Ward & Room &nbsp;&nbsp;&nbsp;</b><?php echo $Hospital_Ward_Name.' ~ '.$Bed_Name; ?></td>
		</tr>
	</table>
</fieldset>

<?php
?>
<table width="100%">
	<tr>
		<td width="80%">
			<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
				<legend>
					<?php
						if(strtolower($Guarantor_Name) == 'cash'){
							echo "CASH BILL DETAILS";
						}else{
							echo "CREDIT BILL DETAILS";
						}
					?>
				</legend>
			<?php
				if(isset($_SESSION['Sort_Mode']) && $_SESSION['Sort_Mode'] == 'Group_By_Receipt'){
					if(strtolower($Guarantor_Name) == 'cash'){
						$get_details = mysqli_query($conn,"select pp.Patient_Bill_ID, pp.Sponsor_ID, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where 
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Transaction_type = 'indirect cash' and
												(pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Folio_Number = '$Folio_Number' and
												Check_In_ID = '$Check_In_ID' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
					}else{
						$get_details = mysqli_query($conn,"select pp.Patient_Bill_ID, pp.Sponsor_ID, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where 
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Transaction_type = 'indirect cash' and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Folio_Number = '$Folio_Number' and
												Check_In_ID = '$Check_In_ID' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
					}
					$num = mysqli_num_rows($get_details);
					if($num > 0){
						$temp_rec = 0;
						while ($row = mysqli_fetch_array($get_details)) {
							$Patient_Payment_ID = $row['Patient_Payment_ID'];
							$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
							echo "<table width='100%'>";
							echo "<tr><td colspan='6'><b>".++$temp_rec.'. Receipt Number ~ <i>'.$row['Patient_Payment_ID']."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt Date ~ ".$row['Payment_Date_And_Time']."</b></td></tr>";

						?>
							<tr>
								<td width="4%">SN</td>
								<td>ITEM NAME</td>
								<td width="10%" style="text-align: right;">PRICE</td>
								<td width="10%" style="text-align: right;">DISCOUNT</td>
								<td width="10%" style="text-align: right;">QUANTITY</td>
								<td width="10%" style="text-align: right;">SUB TOTAL</td>
							</tr>
							<tr><td colspan='6'><hr></td></tr>
						<?php
								$items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount from 
														tbl_items i, tbl_patient_payment_item_list ppl where
														i.Item_ID = ppl.Item_ID and
														ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
							$nm = mysqli_num_rows($items);
							if($nm > 0){
								$temp = 0;
								$Sub_Total = 0;
								while ($dt = mysqli_fetch_array($items)) {
									echo '<tr>
											<td width="4%">'.++$temp.'<b>.</b></td>
											<td>'.ucwords(strtolower($dt['Product_Name'])).'</td>
											<td width="10%" style="text-align: right">'.number_format($dt['Price']).'</td>
											<td width="10%" style="text-align: right;">'.number_format($dt['Discount']).'</td>
											<td width="10%" style="text-align: right;">'.$dt['Quantity'].'</td>
											<td width="10%" style="text-align: right;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</td>
										</tr>';
									$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
								}
								echo "<tr><td colspan='6'><hr></td></tr>";
								echo "<tr><td colspan='5' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
							}
							echo "</table>";
						}
					}
				}else{
					//get categories
					$get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Check_In_ID = '$Check_In_ID' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_cat);
					if($num > 0){
						$temp_cat = 0;
						while ($row = mysqli_fetch_array($get_cat)) {
							$Item_category_ID = $row['Item_category_ID'];
							echo "<table width='100%'>";
							echo "<tr><td colspan='6'><b>".++$temp_cat.'. '.strtoupper($row['Item_Category_Name'])."</b></td></tr>";

						?>
							<tr>
								<td width="4%">SN</td>
								<td>ITEM NAME</td>
								<td width="10%" style="text-align: center;">RECEIPT#</td>
								<td width="10%" style="text-align: right;">PRICE</td>
								<td width="10%" style="text-align: right;">DISCOUNT</td>
								<td width="10%" style="text-align: right;">QUANTITY</td>
								<td width="10%" style="text-align: right;">SUB TOTAL</td>
							</tr>
							<tr><td colspan='7'><hr></td></tr>
						<?php
								$items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_category_ID = '$Item_category_ID' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
							$nm = mysqli_num_rows($items);
							if($nm > 0){
								$temp = 0;
								$Sub_Total = 0;
								while ($dt = mysqli_fetch_array($items)) {
									echo '<tr>
											<td width="4%">'.++$temp.'<b>.</b></td>
											<td>'.ucwords(strtolower($dt['Product_Name'])).'</td>
											<td width="10%" style="text-align: center"><label style="color: #0079AE;" onclick="View_Details('.$dt['Patient_Payment_ID'].','.$dt['Patient_Payment_Item_List_ID'].');"><b>'.$dt['Patient_Payment_ID'].'</b></label></td>
											<td width="10%" style="text-align: right">'.number_format($dt['Price']).'</td>
											<td width="10%" style="text-align: right;">'.number_format($dt['Discount']).'</td>
											<td width="10%" style="text-align: right;">'.$dt['Quantity'].'</td>
											<td width="10%" style="text-align: right;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</td>
										</tr>';
									$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
								}
								echo "<tr><td colspan='7'><hr></td></tr>";
								echo "<tr><td colspan='6' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
							}
							echo "</table>";
						}
					}
				}
			?>
			</fieldset>
		</td>
		<td>
			<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Details'>
				<legend>
					<?php
						if(strtolower($Guarantor_Name) == 'cash'){
							echo "CASH BILL SUMMARY";
						}else{
							echo "CREDIT BILL SUMMARY";
						}
					?>
				</legend>
					<br/><br/><br/>
					<input type="button" name="Approval_Bill_Cash" id="Approval_Bill_Cash" value="APPROVAL CASH BILL" class="art-button-green">
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php if(strtolower($Guarantor_Name) == 'cash'){ ?>
					<input type="button" name="Approval_Bill_Cash" id="Approval_Bill_Cash" value="APPROVAL CASH BILL" class="art-button-green">
					<select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
				 		<option selected="selected" value="Cash_Bill_Details">Cash Bill Details</option>
				 	</select>
			<?php }else{ ?>
					<input type="button" name="Approval_Bill_Cash" id="Approval_Bill_Cash" value="APPROVAL CASH BILL" class="art-button-green">
					<input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green">
		 		<select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
			 		<option selected="selected" value="Credit_Bill_Details">Credit Bill Details</option>
			 		<option value="Cash_Bill_Details">Cash Bill Details</option>
			 	</select>
			<?php	} ?>
		 		<select name="Receipt_Mode" id="Receipt_Mode" onchange="Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
			 		<option selected="selected" value="Group_By_Category">Group by Category</option>
			 		<option value="Group_By_Receipt" <?php if(isset($_SESSION['Sort_Mode']) && strtolower($_SESSION['Sort_Mode']) == 'group_by_receipt'){ echo 'selected="selected"'; } ?>>Group by Receipt</option>
			 	</select>
		</td>
	</tr>
</table>