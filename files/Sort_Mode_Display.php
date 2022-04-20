<?php
	@session_start();
	include("./includes/connection.php");
	include("allFunctions.php");
	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}


	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}


	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get guarantor name
	$SponsorStatus=' active';
	$sponsor = json_decode(get_Sponsor($Sponsor_ID, $SponsorStatus), true);
	$Guarantor_Name=$sponsor[0]['Guarantor_Name'];
	
	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
	
	if(isset($_GET['Receipt_Mode'])){
		$Receipt_Mode = $_GET['Receipt_Mode'];
		$_SESSION['Sort_Mode'] = $_GET['Receipt_Mode'];
	}else{
		$Receipt_Mode = '';
		$_SESSION['Sort_Mode'] = '';
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
	
    
    $canZero=false;
    if(strtolower($_SESSION['systeminfo']['enable_zeroing_price']) == 'yes'){
       $canZero=true;
    }
?>
	<legend>
		<?php
			if(strtolower($Transaction_Type) == 'credit_bill_details'){
				echo "CREDIT BILL DETAILS";
			}else{
				echo "CASH BILL DETAILS";
			}
	echo "</legend>";
			//get details
			if(strtolower($_SESSION['Sort_Mode']) == 'group_by_receipt'){
				if (strtolower($Transaction_Type) == 'cash_bill_details') {
					$Billing_Type = "  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";
					$get_details = json_decode(getDataByReceipt($Registration_ID, $Patient_Bill_ID, $Check_In_ID, $Billing_Type), true);
				} else {
					$Billing_Type = " AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";
					$get_details = json_decode(getDataByReceipt($Registration_ID, $Patient_Bill_ID, $Check_In_ID, $Billing_Type), true);
				}
                                
                
				if (sizeof($get_details) > 0) {
					$temp_rec = 0;
					foreach ($get_details as $row) {
						$Patient_Payment_ID = $row['Patient_Payment_ID'];
						$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
						$Billing_Type_sts = $row['Billing_Type'];
						$Sponsor_ID = $row['Sponsor_ID'];
						$SponsorStatus=' active';
						$sponsor = json_decode(get_Sponsor($Sponsor_ID, $SponsorStatus), true);
						$spnsor_name=$sponsor[0]['Guarantor_Name'];
						
						echo "<table width='100%' class='cash_credit_bill_tbl'>";
						echo "<tr><td colspan='10'><b>".++$temp_rec.'. Receipt Number ~ <i><label style="color: #0079AE;" onclick="View_Details('.$row['Patient_Payment_ID'].',0);">'.$row['Patient_Payment_ID']."</i></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt Date ~ ".$row['Payment_Date_And_Time']."</b></td></tr>";

					?>
						<tr style='background:#DEDEDE'>
							<td width="4%">SN</td>
                            <?php echo ($canZero)?'<td width="4%">&nbsp;</td>':''?>
							<td width="20%">ITEM NAME</td>
							
							<td width="10%" style="text-align: left;">ADDED DATE</td>
							<td width="10%" style="text-align: left;">ADDED BY</td>
							<td width="10%" style="text-align: left;">WARD</td>
							<td width="10%" style="text-align: left;">STATUS</td>
							<td width="10%" style="text-align: left;">SPONSOR</td>
							<td width="10%" style="text-align: right;">PRICE</td>
							<td width="10%" style="text-align: right;">DISCOUNT</td>
							<td width="10%" style="text-align: right;">QUANTITY</td>
							<td width="10%" style="text-align: right;">SUB TOTAL</td>
						</tr>
						<!-- <tr><td colspan='6'><hr></td></tr> -->
					<?php
						 $items = json_decode(getItemByReceipt($Patient_Payment_ID), true);
						 if (sizeof($items) > 0) {
							$temp = 0;
							$Sub_Total = 0;
							foreach ($items as $dt) {
								$Hospital_Ward_ID=$dt['Hospital_Ward_ID'];
								$wardType='';
								$ward_status=' active';
								$Hward = json_decode(getHospitalaWard($Hospital_Ward_ID, $wardType, $ward_status ), true);
								$Hospital_Ward_Name=$Hward[0]['Hospital_Ward_Name'];
								
								echo '<tr>
											<td width="4%">'.++$temp.'<b>.</b></td>';
											$chk='';
											if($dt['Price']==0) {
												$chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
											}
											echo ($canZero)?'<td width="4%"><input type="checkbox" class="zero_items" id="'.$dt['Patient_Payment_Item_List_ID'].'" '.$chk.'/></td>':'';
											echo '<td width="20%"><label for="'.$dt['Patient_Payment_Item_List_ID'].'" style="display:block">'.ucwords(strtolower($dt['Product_Name'])).'</label></td>
											
											<td>'.$dt['Transaction_Date_And_Time'].'</td>
											<td>'.$dt['Consultant'].'</td>
											<td>'.$Hospital_Ward_Name.'</td>
											<td>'.$Billing_Type_sts.'</td>
											<td>'.$spnsor_name.'</td>
											<td width="10%" style="text-align: right">'.number_format($dt['Price']).'</td>
											<td width="10%" style="text-align: right;">'.number_format($dt['Discount']).'</td>
											<td width="10%" style="text-align: right;">'.$dt['Quantity'].'</td>
											<td width="10%" style="text-align: right;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</td>
										</tr>';
									$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
								}
								echo "<tr style='background:#DEDEDE'><td colspan='10' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
							}
						echo "</table><br/>";
					}
				}else{
					echo "<b>NO TRANSACTION FOUND</b>";
				}
			}else{
				//get categories
				// AND pp.Check_In_ID='$Check_In_ID'
				if (strtolower($Transaction_Type) == 'cash_bill_details') {
					$Billing_Type = "  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";
					$get_cat = json_decode(getBillByCategory($Patient_Bill_ID, $Registration_ID, $Billing_Type), true);
				} else {
					$Billing_Type = "  AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";
					$get_cat = json_decode(getBillByCategory($Patient_Bill_ID, $Registration_ID, $Billing_Type), true);
					
				}
				
				if (sizeof($get_cat) > 0) {
					$temp_cat = 0;
					foreach ($get_cat as $row) {
						$Item_category_ID = $row['Item_category_ID'];
						
						echo "<table width='100%' class='cash_credit_bill_tbl'>";
						echo "<tr><td colspan='11'><b>".++$temp_cat.'. '.strtoupper($row['Item_Category_Name'])."</b></td></tr>";

					?>
						<tr style='background:#DEDEDE'>
							<td width="4%">SN</td>
                            <?php echo ($canZero)?'<td width="4%">&nbsp;</td>':''?>
							<td width="20%">ITEM NAME</td>
							<td width="10%" style="text-align: center;">RECEIPT#</td>							
							<td width="10%" style="text-align: left;">ADDED DATE</td>
							<td width="10%" style="text-align: left;">ADDED BY</td>
							<td width="10%" style="text-align: left;">WARD</td>
							<td width="10%" style="text-align: left;">STATUS</td>
							<td width="10%" style="text-align: left;">SPONSOR</td>
							<td width="10%" style="text-align: right;">PRICE</td>
							<td width="10%" style="text-align: right;">DISCOUNT</td>
							<td width="10%" style="text-align: right;">QUANTITY</td>
							<td width="10%" style="text-align: right;">SUB TOTAL</td>
						</tr>
						<!-- <tr><td colspan='7'><hr></td></tr> -->
					<?php
							
						if (strtolower($Transaction_Type) == 'cash_bill_details') {
							$Billing_Type = "  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";

							$items = json_decode(getBillItems($Patient_Bill_ID, $Registration_ID, $Billing_Type, $row['Item_category_ID']), true);
						} else {
							$Billing_Type = "  AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";
							$items = json_decode(getBillItems($Patient_Bill_ID, $Registration_ID, $Billing_Type, $row['Item_category_ID']), true);
						}

						if (sizeof($items) > 0) {
							$temp = 0;
							$Sub_Total = 0;
							foreach ($items as $dt) {
								$Hospital_Ward_ID=$dt['Hospital_Ward_ID'];
								$Billing_Type_sts=$dt['Billing_Type'];
								$wardType='';
								$ward_status=' active';
								$Sponsor_ID = $dt['Sponsor_ID'];
								$SponsorStatus='active';
								$sponsor = json_decode(get_Sponsor($dt['Sponsor_ID'], $SponsorStatus), true);
								$spnsor_name=$sponsor[0]['Guarantor_Name'];
								$Hward = json_decode(getHospitalaWard($Hospital_Ward_ID, $wardType, $ward_status ), true);
								$Hospital_Ward_Name=$Hward[0]['Hospital_Ward_Name'];

								echo '<tr>
								<td width="4%">'.++$temp.'<b>.</b></td>';
								$chk='';
								if($dt['Price']==0) {
									$chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
								}
								echo ($canZero)?'<td width="4%"><input type="checkbox" class="zero_items" id="'.$dt['Patient_Payment_Item_List_ID'].'" '.$chk.'/></td>':'';
																	
								echo '<td width="20%"><label for="'.$dt['Patient_Payment_Item_List_ID'].'" style="display:block">'.ucwords(strtolower($dt['Product_Name'])).'</label></td>
									<td width="10%" style="text-align: center"><label style="color: #0079AE;" onclick="View_Details('.$dt['Patient_Payment_ID'].','.$dt['Patient_Payment_Item_List_ID'].');"><b>'.$dt['Patient_Payment_ID'].'</b></label></td>
									
									<td>'.$dt['Transaction_Date_And_Time'].'</td>
									<td>'.$dt['Consultant'].'</td>
									<td>'.$Hospital_Ward_Name.'</td>
									<td>'.$Billing_Type_sts.'</td>
									<td>'.$spnsor_name.'</td>
									<td width="10%" style="text-align: right">'.number_format($dt['Price']).'</td>
									<td width="10%" style="text-align: right;">'.number_format($dt['Discount']).'</td>
									<td width="10%" style="text-align: right;">'.$dt['Quantity'].'</td>
									<td width="10%" style="text-align: right;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</td>
								</tr>';
								$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
							//echo "<tr><td colspan='10'><hr></td></tr>";
							echo "<tr style='background:#DEDEDE'><td colspan='11' style='text-align: right;'><b>SUB TOTAL : </b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
							//echo "<tr><td colspan='7'><hr></td></tr>";
						}
						echo "</table><br/>";
					}
				}else{
					echo "<b>NO TRANSACTION FOUND </b>";
				}
			}
		?>
	</legend>