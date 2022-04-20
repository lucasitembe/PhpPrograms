<?php
	session_start();
	include("./includes/connection.php");
	include("./functions/items.php");
	//Get Employee_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
	
    //Get Sub_Department_ID
    if (isset($_SESSION['Pharmacy_ID'])) {
	    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	} else {
	    $Sub_Department_ID = 0;
	}

	//get sub_Department_Name
	$slct = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while($row = mysqli_fetch_array($slct)){
			$Sub_Department_Name = $row['Sub_Department_Name'];
		}
	}else{
		$Sub_Department_Name = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age = '';
	}


	$select = mysqli_query($conn,"select * from tbl_multi_dispense_cache where Employee_ID = '$Employee_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
?>
<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Details_Area'>
	<?php
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
			echo '<table width="100%">';
			$Dispense_Cache_ID = $data['Dispense_Cache_ID'];
			$Transaction_Type = $data['Transaction_Type'];
			$Registration_ID = $data['Registration_ID'];
			$Payment_Cache_ID = $data['Payment_Cache_ID'];

			//Get patient details
			$slct = mysqli_query($conn,"select Patient_Name, Date_Of_Birth, Gender, sp.Guarantor_Name, pc.Billing_Type from 
									tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor sp, tbl_item_list_cache ilc where
									pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
									pc.Registration_ID = pr.Registration_ID and
									ilc.Transaction_Type = '$Transaction_Type' and
									pr.Sponsor_ID = sp.Sponsor_ID and
									pc.Payment_Cache_ID = '$Payment_Cache_ID' and
									ilc.Status in('paid', 'approved') and
									ilc.Sub_Department_ID = '$Sub_Department_ID' limit 1") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($slct);
			if($nm > 0){
				while ($row = mysqli_fetch_array($slct)) {
					$date1 = new DateTime($Today);
			        $date2 = new DateTime($row['Date_Of_Birth']);
			        $diff = $date1->diff($date2);
			        $age = $diff->y . " Years, ";
			        $age .= $diff->m . " Months, ";
			        $age .= $diff->d . " Days";

			        //Generate Billing_Type
			        if(strtolower(substr($row['Billing_Type'], 0, 3)) == 'inp'){
			        	$B_Type = 'Inpatient '.$Transaction_Type;
			        }else{
			        	$B_Type = 'Outpatient '.$Transaction_Type;
			        }
	?>
			
					<tr>
						<td style="text-align: left;"><?php echo ++$temp; ?><b>.</b>&nbsp;&nbsp;Patient Name : <b><?php echo ucwords(strtolower($row['Patient_Name'])); ?></b></td>
						<td style="text-align: left;">Gender : <?php echo $row['Gender']; ?></td>
						<td style="text-align: left;">Age : <?php echo $age; ?></td>
					</tr>
					<tr>
						<td style="text-align: left;">Sponsor Name : <?php echo strtoupper($row['Guarantor_Name']); ?></td>
						<td style="text-align: left;">Billing Type : <?php echo $B_Type; ?></td>
						<td style="text-align: right;"><input type="button" value="REMOVE" onclick="Remove_Transaction_Confrim(<?php echo $Dispense_Cache_ID; ?>)"></td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
					<tr><td colspan="3">
	<?php
					//get datails
					$get_details = mysqli_query($conn,"select ilc.Price, ilc.Quantity, ilc.Discount, i.Product_Name, emp.Employee_Name, ilc.Doctor_Comment, ilc.Item_ID, ilc.Edited_Quantity from 
												tbl_item_list_cache ilc, tbl_items i, tbl_employee emp where
												ilc.Consultant_ID = emp.Employee_ID and
												ilc.Item_ID = i.Item_ID and
												ilc.Sub_Department_ID = '$Sub_Department_ID' and
												ilc.Status in('paid', 'approved') and
												ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
												ilc.Transaction_Type = '$Transaction_Type'") or die(mysqli_error($conn));
					$nmz = mysqli_num_rows($get_details);
					if($nmz > 0){
						$count = 0;
	?>
						<table width="100%">
							<tr>
								<td width="5%"><b>SN</b></td>
								<td><b>MEDICATION NAME</b></td>
								<td width="8%" style="text-align: right;"><b>PRICE</b></td>
								<td width="8%" style="text-align: right;"><b>DISCOUNT</b></td>
								<td width="8%" style="text-align: right;"><b>QUANTITY</b></td>
								<td width="8%" style="text-align: right;"><b>BALANCE</b></td>
								<td width="9%" style="text-align: right;"><b>SUB TOTAL</b></td>
								<td width="13%">&nbsp;&nbsp;&nbsp;<b>DOSAGE</b></td>
								<td width="15%"><b>ORDERED BY</b></td>
							</tr>
							<tr><td colspan="9"><hr></td></tr>
	<?php
							$Grand_Total = 0;
							while ($detail = mysqli_fetch_array($get_details)) {
								//Get Quanity Required
								if($detail['Edited_Quantity'] > 0){
									$Qty = $detail['Edited_Quantity'];
								}else{
									$Qty = $detail['Quantity'];
								}

								//Get Item Balance
								$Item_ID = $detail['Item_ID'];
								$bl = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
								$nn = mysqli_num_rows($bl);
								if($nn > 0){
									$result =  mysqli_fetch_assoc($bl);
									$Balance = $result['Item_Balance'];
								}else{
									$Balance = 0;
									mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
								}

								if($Balance < $Qty && isset($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) && strtolower($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) == 'no'){
									$style = ' background-color: #006400;';
									$style2 = ' style = "background-color: #006400;"';
								}else{
									$style = '';
									$style2 = '';
								}
	?>
								<tr id="sss">
									<td <?php echo $style2; ?>><?php echo (++$count); ?></td>
									<td <?php echo $style2; ?>><?php echo $detail['Product_Name']; ?></td>
									<td style="text-align: right; <?php echo $style; ?>"><?php echo number_format($detail['Price']); ?></td>
									<td style="text-align: right; <?php echo $style; ?>"><?php echo number_format($detail['Discount']); ?></td>
									<td style="text-align: right; <?php echo $style; ?>"><?php echo number_format($detail['Quantity']); ?></td>
									<td style="text-align: right; <?php echo $style; ?>"><?php echo $Balance; ?></td>
									<td style="text-align: right; <?php echo $style; ?>"><?php echo number_format(($detail['Price'] - $detail['Discount']) * $detail['Quantity']); ?></td>
									<td <?php echo $style2; ?>>&nbsp;&nbsp;&nbsp;<?php echo $detail['Doctor_Comment']; ?></td>
									<td <?php echo $style2; ?>><?php echo ucwords(strtolower($detail['Employee_Name'])); ?></td>
								</tr>
	<?php
								$Grand_Total += (($detail['Price'] - $detail['Discount']) * $detail['Quantity']);
							}
	?>
							<tr><td colspan="9"><hr></td></tr>
							<tr><td colspan="6"><b>SUB TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Grand_Total); ?></b></td></tr>
							
						</table>
	<?php						
					}
					echo "</td></tr>";
				}
			}
			echo "</table><br/>";
		}
?>
</fieldset>
<fieldset>
	<table width="100%">
		<table width="100%">
			<tr>
				<td style="text-align: center;">
			<?php
				if(isset($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) && strtolower($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) == 'no'){
					echo "<b style='color: #037CB0;'>Warning!. Only available balance will be part of dispensing process</b>";
				}
			?>
				</td>
				<td width="20%"><b style='color: #037CB0;'>Location : <?php echo $Sub_Department_Name; ?></b></td><td width=15%><b style='color: #037CB0;'>Total Transactions : <?php echo $temp; ?></b></td>
				<td style="text-align: right;">
					<input type="button" name="M_Dispense" id="M_Dispense" class="art-button-green" value="DISPENSE" onclick="Confirm_Dispensing_Process()">
					<input type="button" name="C_Dispense" id="C_Dispense" class="art-button-green" value="CANCEL DISPENSE" onclick="Cancel_Dispense()">
				</td>
			</tr>
		</table>
	</table>
</fieldset>
<?php
		}else{
			echo "<br/><br/><br/><br/><br/><br/>
					<center><h2><b>NO TRANSACTIONS SELECTED</b></h2></center>";
		}
	?>