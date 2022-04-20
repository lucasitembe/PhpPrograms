<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	//get patient details
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name from
							tbl_patient_registration pr, tbl_sponsor sp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Gender = $data['Gender'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
		}
	}else{
		$Patient_Name = '';
		$Gender = '';
		$Guarantor_Name = '';
		$Date_Of_Birth = '0000/00/00';
	}

	//calculate age
	$date1 = new DateTime($Today);
	$date2 = new DateTime($Date_Of_Birth);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}
	
	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}
	
	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Claim_Form_Number'])){
		$Claim_Form_Number = $_GET['Claim_Form_Number'];
	}else{
		$Claim_Form_Number = '';
	}
?>

<fieldset style="background-color: white;">
	<table width="100%">
		<tr>
			<td width="12%" style="text-align: right;"><b>PATIENT NAME&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Patient_Name; ?></td>
			<td width="13%" style="text-align: right;"><b>GENDER&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Gender; ?></td>
			<td width="15%" style="text-align: right;"><b>AGE&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $age; ?></td>
			<td width="15%" style="text-align: right;"><b>REGISTRATION NUMBER&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Registration_ID; ?></td>
		</tr>
		<tr>
			<td style="text-align: right;"><b>SPONSOR NAME&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Guarantor_Name; ?></td>
			<td style="text-align: right;"><b>FOLIO NUMBER&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Folio_Number; ?></td>
			<td style="text-align: right;"><b>CLAIM FORM NUMBER&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $Claim_Form_Number; ?></td>
			<td colspan="2"></td>
		</tr>
	</table>
</fieldset>
<br/>
<fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
<?php
	$Grand_Total = 0;
	$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											ppl.Item_ID = i.Item_ID and
											pp.Folio_Number = '$Folio_Number' and
											pp.Check_In_ID = '$Check_In_ID' and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											pp.Registration_ID = '$Registration_ID' and
											ic.Item_Category_ID = isc.Item_Category_ID and
											pp.Transaction_status <> 'cancelled' and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID
											group by ic.Item_Category_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($get_categories);
	if($num > 0){
		while ($data = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $data['Item_Category_ID'];
?>			
			<table width="100%" border=1 style="border-collapse: collapse;">
				<tr><td colspan="4"><b><span style="font-size: small;"><?php echo strtoupper($data['Item_Category_Name']); ?></b></td></tr>
<?php					
					//get transactions based on Item_Category_ID
					$get_details = mysqli_query($conn,"select Product_Name, Price, Quantity, Discount from
												tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												ppl.Item_ID = i.Item_ID and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Check_In_ID = '$Check_In_ID' and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Registration_ID = '$Registration_ID' and
												isc.Item_Category_ID = '$Item_Category_ID' order by ppl.Patient_Payment_Item_List_ID") or die(mysqli_error($conn));
					$num_get_details = mysqli_num_rows($get_details);
					if($num > 0){
						$temp = 0;
						$Sub_Total = 0;
?>
						<tr>
							<td width="4%"><b><span style="font-size: small;">No</span></b></td>
							<td><b><span style="font-size: small;">Particular</span></b></td>
							<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Quantity</span></b></td>
							<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Amount</span></b></td>
						</tr>
<?php
						while ($dtz = mysqli_fetch_array($get_details)) {
?>
							<tr>
								<td><span style="font-size: small;"><?php echo ++$temp; ?></span></td>
								<td><span style="font-size: small;"><?php echo $dtz['Product_Name']; ?></td>
								<td style="text-align: right;"><span style="font-size: small;"><?php echo $dtz['Quantity']; ?></td>
								<td style="text-align: right;"><span style="font-size: small;"><?php echo number_format(($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']); ?></td>
							</tr>
<?php
							$Sub_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
							$Grand_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
						}
					}
?>
					<tr>
						<td colspan="3"><b><span style="font-size: small;">Sub Total</span></b></td>
						<td style="text-align: right;"><b><span style="font-size: small;"><?php echo number_format($Sub_Total); ?></span></b></td>
					</tr>
					</table><br/>
<?php
		}
	}
?>
</fieldset>
<table width="100%">
	<tr>
		<td style="text-align: right;">
			<h3><b>GRAND TOTAL : </b><?php echo number_format($Grand_Total); ?></h3>
		</td>
	</tr>
</table>