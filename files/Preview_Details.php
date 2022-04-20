<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

	//Get Patient Details
	$select = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, ci.Check_In_Date_And_Time from
							tbl_patient_registration pr, tbl_check_in ci where
							pr.Registration_ID = ci.Registration_ID and
							pr.Registration_ID = '$Registration_ID' and
							ci.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Gender = $data['Gender'];
			$Phone_Number = $data['Phone_Number'];
			$Check_In_Date_And_Time = $data['Check_In_Date_And_Time'];
		}
	}else{
		$Patient_Name = '';
		$Date_Of_Birth = '';
		$Gender = '';
		$Phone_Number = '';
		$Check_In_Date_And_Time = '';
	}

	$date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";

	//get sponsor
	$select = mysqli_query($conn,"SELECT Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Guarantor_Name = $data['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}
?>
<fieldset>
	<table width="100%">
		<tr>
			<td width="10%" style="text-align: right;">Patient Name</td>
			<td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
			<td width="7%" style="text-align: right;">Patient #</td>
			<td width="9%"><input type="text" readonly="readonly" value="<?php echo $Registration_ID; ?>"></td>
			<td width="5%" style="text-align: right;">Age</td>
			<td width="18%"><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
			<td width="10%" style="text-align: right;">Sponsor Name</td>
			<td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Guarantor_Name)); ?>"></td>
		</tr>
		<tr>
			<td style="text-align: right;">Gender</td>
			<td><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
			<td style="text-align: right;">Visit Date</td>
			<td colspan="3"><input type="text" value="<?php echo $Check_In_Date_And_Time; ?>" readonly="readonly"></td>
			<td style="text-align: right;" colspan="2">
				<input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Sponsor_ID; ?>)">
			</td>
		</tr>
	</table>
</fieldset>
<fieldset style="overflow-y: scroll; height: 300px; background-color: white;">
<?php
	$Grand_Total = 0;
        $Grand_Total_outpatient=0;
        $Grand_Total_inpatient=0;
	//Get Receipts
	$select_res = mysqli_query($conn,"SELECT Patient_Payment_ID, Payment_Date_And_Time, Billing_Type from
								tbl_patient_payments where
								Registration_ID = '$Registration_ID' and
								Check_In_ID = '$Check_In_ID' and
								Sponsor_ID = '$Sponsor_ID' and
								Transaction_status <> 'cancelled' and auth_code <> '' AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list) and 
								(((Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Patient From Outside') and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and payment_type = 'pre'))") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select_res);
	if($nm > 0){
		while ($row = mysqli_fetch_array($select_res)) {
			$count = 0;
			$Total = 0;
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
			$Billing_Type = $row['Billing_Type'];
?>
			<table width="100%">
				<tr>
					<td style="text-align: left;"><b>Receipt Number : </b><?php echo $Patient_Payment_ID; ?></td>
					<td style="text-align: left;"><b>Receipt Date : </b><?php echo $Payment_Date_And_Time; ?></td>
					<td style="text-align: left;"><b>Billing Type : </b><?php echo $Billing_Type; ?></td>
				</tr>
				<tr><td colspan="3"><hr></td></tr>
				<tr>
					<td colspan="3">
						<table width="100%">
							<tr id="sss">
								<td width="5%">SN</td>
								<td>ITEM NAME</td>
								<td style="text-align: right;" width="15%">PRICE</td>
								<td style="text-align: right;" width="15%">DISCOUNT</td>
								<td style="text-align: right;" width="15%">QUANTITY</td>
								<td style="text-align: right;" width="15%">SUB TOTAL</td>
							</tr>
					<?php
						$slct = mysqli_query($conn,"SELECT i.Product_Name, ppl.Price, ppl.Discount, ppl.Quantity from tbl_patient_payment_item_list ppl, tbl_items i where
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
											ppl.Item_ID = i.Item_ID") or die(mysqli_error($conn));
						$nmz = mysqli_num_rows($slct);
						if($nmz > 0){
							while ($dt = mysqli_fetch_array($slct)) {
					?>
								<tr id="sss">
									<td><?php echo ++$count; ?></td>
									<td><?php echo $dt['Product_Name']; ?></td>
									<td style="text-align: right;"><?php echo number_format($dt['Price']); ?></td>
									<td style="text-align: right;"><?php echo number_format($dt['Discount']); ?></td>
									<td style="text-align: right;"><?php echo $dt['Quantity']; ?></td>
									<td style="text-align: right;"><?php echo number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']); ?></td>
								</tr>
					<?php
								$Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                 if($Billing_Type=="Outpatient Cash"){
                                                                    $Grand_Total_outpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }else{
                                                                    $Grand_Total_inpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }
								$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
						}
					?>
							<tr><td colspan="6"><hr></td></tr>
							<tr id="sss">
								<td colspan="5"><b>GRAND TOTAL</b></td>
								<td style="text-align: right;"><?php echo number_format($Total); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br/><br/>
<?php
		}
	}else{
		echo "<center><br/><br/><br/><br/><br/>NO TRANSACTIONS FOUND</center>";
	}
?>
</fieldset>
<table width="100%">
	<tr>
		<td style="text-align: right;"><?php echo '<b>OUTPATIENT TOTAL : '.number_format($Grand_Total_outpatient); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td style="text-align: right;"><?php echo '<b>INPATIENT TOTAL : '.number_format($Grand_Total_inpatient); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td style="text-align: right;"><?php echo '<b>GRAND TOTAL : '.number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>