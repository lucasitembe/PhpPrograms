<?php
	include("./includes/connection.php");
	$temp = 0;
	$Grand_Total = 0;
	$Doctor_Name = '';
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

	

	
	if(isset($_GET['Patient_Type'])){
		$Patient_Type = $_GET['Patient_Type'];
	}else{
		$Patient_Type = '';
	}

	
	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}

	echo $Patient_Number. " "  . $Sponsor_ID . " start:" . $Start_Date . " End date: " .$End_Date . " Patient type: " . $Patient_Type;
	if(isset($_GET['Folio_Number'])){
		$Patient_Folio_Number = $_GET['Folio_Number'];
	}else{
		$Patient_Folio_Number = '';
	}

	//generate billing type 
	if($Patient_Type == 'All'){
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'pre'))";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'post'))";
		}
	}else if($Patient_Type == 'Outpatient'){
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Outpatient Credit')";
		}
	}else{
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'pre')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Inpatient Credit' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'post'))";
		}
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }



    if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							WHERE pp.patient_payment_id = ppl.patient_payment_id AND
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date >= '$Start_Date' AND  
							pp.receipt_date <= '$End_Date' AND 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pr.Registration_ID LIKE '%$Patient_Number%'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));

    }else if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
    	$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date >= '$Start_Date' AND
							pp.receipt_date <= '$End_Date' AND
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pr.Patient_Name like '%$Patient_Name%'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }else if(isset($_GET['Folio_Number']) && $_GET['Folio_Number'] != null && $_GET['Folio_Number'] != ''){
    	$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pp.Folio_Number = '$Patient_Folio_Number'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }else{
    	$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Sponsor_ID = '$Sponsor_ID'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }

	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			//get authorization number
		$diagnosis = '';
			$Check_In_ID = $data['Check_In_ID'];
			$get_check_in = mysqli_query($conn,"select AuthorizationNo from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
			$n_autho = mysqli_num_rows($get_check_in);
			if($n_autho > 0){
				while ($auth = mysqli_fetch_array($get_check_in)) {
					$AuthorizationNo = $auth['AuthorizationNo'];
				}
			}else{
				$AuthorizationNo = '';
			}

			$Patient_Bill_ID = $data['Patient_Bill_ID'];
			$Registration_ID = $data['Registration_ID'];
			$Folio_Number = $data['Folio_Number'];
			$Claim_Form_Number = $data['Claim_Form_Number'];
	//============getting diseasecodes===============
$select_con = mysqli_query($conn,"SELECT c.consultation_ID,d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
    	FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID
    	AND dc.diagnosis_type = 'diagnosis'
    	AND c.Patient_Payment_Item_List_ID IN (
    	    SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
    		ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
    		pp.Folio_Number = '$Folio_Number' and
    		pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn) . "io");
$no_of_rows = mysqli_num_rows($select_con);
if ($no_of_rows > 0) {
    while ($diagnosis_row = mysqli_fetch_array($select_con)) {
        $diagnosis .= $diagnosis_row['disease_code'] . "; ";
        $Consultant_Name .= $diagnosis_row['Consultant_Name'] . "; ";
    }
}
$Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE Folio_Number='$Folio_Number' AND Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'"))['consultation_ID'];
$select_con1 = mysqli_query($conn,"SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = w.Employee_ID) as Consultant_Name
    	FROM tbl_ward_round w,tbl_ward_round_disease dw, tbl_disease d
    	WHERE w.Round_ID=dw.Round_ID AND d.Disease_ID = dw.Disease_ID
    	AND dw.diagnosis_type = 'diagnosis'
        AND w.Process_Status = 'served'
    	AND w.Consultation_ID ='$Consultation_ID' ") or die(mysqli_error($conn));


$no_of_rows1 = mysqli_num_rows($select_con1);
if ($no_of_rows1 > 0) {
    while ($diagnosis_row1 = mysqli_fetch_array($select_con1)) {
        $diagnosis .= $diagnosis_row1['disease_code'] . "; ";
        $Consultant_Name .= $diagnosis_row1['Consultant_Name'] . "; ";
    }
}		
			//get all transaction based on details above

			$details = mysqli_query($conn,"SELECT Patient_Name, Date_Of_Birth, Member_Number, Gender from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($details);
			if($nm > 0){
				while ($dt = mysqli_fetch_array($details)) {
					$Patient_Name = $dt['Patient_Name'];
					$Date_Of_Birth = $dt['Date_Of_Birth'];
					$Gender = $dt['Gender'];
					$Member_Number = $dt['Member_Number'];
				}
			}else{
				$Patient_Name = '';
				$Date_Of_Birth = '';
				$Gender = '';
				$Member_Number = '';
			}

			//calculate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
?>
			<table width="100%">
				<tr><td colspan="5"><hr></td></tr>
				<tr>
					<td width="10%" style="text-align: left;"><b><?php echo ++$temp; ?>.  PATIENT NAME ~ </b></td>
					<td width="15%"><b><?php echo strtoupper($Patient_Name); ?></b></td>
					<td width="12%"><b>PATIENT# ~ <?php echo $Registration_ID; ?></b></td>
					<td width="12%"><b>MEMBER NUMBER ~ <?php echo $Member_Number; ?></b></td>
					<td width="15%"><b>AGE ~ <?php echo $age; ?></b></td>
				</tr>
				<tr>
					<td width="10%"><b>GENDER ~ </b><?php echo $Gender; ?></td>
					<td width="15%"><b>AUTHORIZATION NO ~ </b><?php echo $AuthorizationNo; ?></td>
					<td width="10%"><b>FOLIO NO ~ <?php echo $Folio_Number; ?></b></td>
					<td width="14%"><b>CLAIM NO ~ </b><?php echo $Claim_Form_Number; ?></td>
					<td width="14%"><b>TYPE OF ILLNESS ~ </b><?php echo $diagnosis; ?></td>
				</tr>
				<tr><td colspan="5"><hr></td></tr>
				<tr><td colspan="5">
				<?php
					$slct = mysqli_query($conn,"SELECT ic.Item_category_ID, ic.Item_Category_Name from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											i.Item_ID = ppl.Item_ID and
											pp.Sponsor_ID = '$Sponsor_ID' and
											pp.Check_In_ID = '$Check_In_ID' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											pp.Transaction_status <> 'cancelled' and
											pp.Folio_Number = '$Folio_Number' and
											$Filter_Billing and
                                                                                        pp.receipt_date between '$Start_Date' and '$End_Date' and
											pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID
										") or die(mysqli_error($conn));
					$num_slct = mysqli_num_rows($slct);
					if($num_slct > 0){
						echo '<table width="100%">';
						$cat_no = 0;
						$Sub_Total = 0;
						while($det = mysqli_fetch_array($slct)){
							$Total = 0;
							$Item_category_ID = $det['Item_category_ID'];
							$Item_Category_Name = $det['Item_Category_Name'];
							echo '<tr><td colspan="8" style="text-align: left;"><b>'.++$cat_no.'. '.strtoupper($Item_Category_Name).'</b></td></tr>';
							echo "<tr>
										<td width='5%' style='text-align: center;'>SN</td>
										<td>ITEM NAME</td>
										<td width='10%'>CONSULTANT&nbsp;&nbsp;&nbsp;</td>
										<td width='7%' style='text-align: right;'>PRICE&nbsp;&nbsp;&nbsp;</td>
										<td width='5%' style='text-align: center;'>QUANTITY</td>
										<td width='6%' style='text-align: right;'>DISCOUNT&nbsp;&nbsp;&nbsp;</td>
										<td width='6%' style='text-align: center;'>RECEIPT#</td>
										<td width='12%'>RECEIPT DATE</td>
										<td style='text-align: right;' width='9%'>SUB TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                                                
									</tr>";
//                                                                        echo "<tr><td>Check_In_ID==$Check_In_ID Folio_Number$Folio_Number Item_category_ID$Item_category_ID Patient_Bill_ID$Patient_Bill_ID</td></tr>";
							$get_items = mysqli_query($conn,"select ppl.Patient_Direction, ppl.Consultant_ID, i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount,  ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											i.Item_ID = ppl.Item_ID and
											pp.Transaction_status <> 'cancelled' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											ic.Item_category_ID = '$Item_category_ID' and
											pp.Folio_Number = '$Folio_Number' and
                                                                                        pp.receipt_date between '$Start_Date' and '$End_Date' and 
											pp.Registration_ID = '$Registration_ID' ORDER BY pp.receipt_date ASC") or die(mysqli_error($conn));
							$n_get_items = mysqli_num_rows($get_items);
							
							if($n_get_items > 0){
								$inum = 0;
								while ($dts = mysqli_fetch_array($get_items)) {
									$Consultant_ID = $dts['Consultant_ID'];
									$Patient_Direction = $dts['Patient_Direction'];

									//if Patient_Direction = 'Direct To Doctor' or 'Direct To Doctor Via Nurse Station' get doctor name
									if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){
										$select_doctor = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));										
										$no_doc = mysqli_num_rows($select_doctor);
										if($no_doc > 0){
											while ($dtz = mysqli_fetch_array($select_doctor)) {
												$Doctor_Name = $dtz['Employee_Name'];
											}
										}
									}
									echo "<tr>
										<td width='5%' style='text-align: center;'>".++$inum."</td>
										<td>".ucwords(strtolower($dts['Product_Name']))."</td>
										<td>".ucwords(strtolower($Doctor_Name))."&nbsp;&nbsp;&nbsp;</td>
										<td style='text-align: right;'>".number_format($dts['Price'])."&nbsp;&nbsp;&nbsp;</td>
										<td style='text-align: center;'>".$dts['Quantity']."</td>
										<td style='text-align: right;'>".number_format($dts['Discount'])."&nbsp;&nbsp;&nbsp;</td>
										<td width='8%' style='text-align: center;'>".$dts['Patient_Payment_ID']."</td>
										<td width='12%'>".$dts['Payment_Date_And_Time']."</td>
										<td style='text-align: right;'>".number_format(($dts['Price'] - $dts['Discount'])*$dts['Quantity'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									</tr>";
									$Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
									$Grand_Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
									$Sub_Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
								}
								echo "<tr><td colspan='9'><hr></td></tr>";
								echo "<tr><td colspan='8' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>".number_format($Total)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
								echo "<tr><td colspan='9'><hr></td></tr>";
							}
						}
								echo "<tr><td colspan='8' style='text-align: right;'><b>TOTAL BILL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
								echo "<tr><td colspan='9'><hr></td></tr>";
						echo '</table>';
					}
				?>
				</td></tr>
			</table>
			<br/>
<?php
		}
	}
?>
<table width="100%">
	<tr>
		<td style="text-align: right;" width="91%"><b>GRAND TOTAL</b></td>
		<td width="9%" style="text-align: right;"><b><?php echo number_format($Grand_Total); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>