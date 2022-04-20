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

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get sponsor name
	$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Guarantor_Name = $data['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = '';
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


	if(isset($_GET['Folio_Number'])){
		$Patient_Folio_Number = $_GET['Folio_Number'];
	}else{
		$Patient_Folio_Number = '';
	}


    $htm = "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
        <tr><td></td></tr>
		<tr>
		    <td style='text-align: center;'><b>REVENUE COLLECTION BY FOLIO REPORT</b></td>
		</tr></table>";
	$htm .= "<table width='100%'>
				<tr><td><span style='font-size: x-small;'><b>SPONSOR NAME ~ </b>".strtoupper($Guarantor_Name)."</span></td>
                                <td><span style='font-size: x-small;'><b>PATIENT TYPE ~ </b>".strtoupper($Patient_Type)."</span></td></tr>
				<tr><td><span style='font-size: x-small;'><b>START DATE ~ </b>".$Start_Date."</span></td>"
                . "<td><span style='font-size: x-small;'><b>BILL TYPE ~ </b>".strtoupper($Billing_Type)."</span></td></tr>
				<tr><td><span style='font-size: x-small;'><b>END DATE ~ </b>".$End_Date."</span></td></tr>
			</table>";


	//generate billing type 
	if($Patient_Type == 'All'){
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')";
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
			$Filter_Billing = "(pp.billing_type = 'Inpatient Cash')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Inpatient Credit')";
		}
	}

    if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$select = mysqli_query($conn,"select pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pr.Registration_ID = '$Patient_Number'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }else if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
    	$select = mysqli_query($conn,"select pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pr.Patient_Name like '%$Patient_Name%'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }else if(isset($_GET['Folio_Number']) && $_GET['Folio_Number'] != null && $_GET['Folio_Number'] != ''){
    	$select = mysqli_query($conn,"select pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							pp.Folio_Number = '$Patient_Folio_Number'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }else{
    	$select = mysqli_query($conn,"select pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID, pp.Claim_Form_Number
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							$Filter_Billing and 
							pp.receipt_date between '$Start_Date' and '$End_Date' and 
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID'
							GROUP BY  pp.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pr.Patient_Name,pp.Folio_Number limit 1200") or die(mysqli_error($conn));
    }

	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			//get authorization number
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
			
			//get all transaction based on details above

			$details = mysqli_query($conn,"select Phone_Number,Patient_Name, Date_Of_Birth, Gender from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($details);
			if($nm > 0){
				while ($dt = mysqli_fetch_array($details)) {
					$Patient_Name = $dt['Patient_Name'];
					$Date_Of_Birth = $dt['Date_Of_Birth'];
					$Gender = $dt['Gender'];
                                        $Phone_no = $dt['Phone_Number'];
				}
			}else{
				$Patient_Name = '';
				$Date_Of_Birth = '';
				$Gender = '';
			}

			//calculate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months";

			$htm .= '<table width="100%" border="1" style="border-collapse: collapse;">
						<tr>
							<td width="35%" style="text-align: left;"><span style="font-size: x-small;"><b>'.++$temp.'.  PATIENT NAME ~ '.strtoupper($Patient_Name).'</b></span></td>
							<td width="20%"><span style="font-size: x-small;"><b>PATIENT# ~ '.$Registration_ID.'</b></span></td>
							<td width="25%"><span style="font-size: x-small;"><b>AGE ~ '.$age.'</b></span></td>
							<td width="20%"><span style="font-size: x-small;"><b>GENDER ~ '.$Gender.'</b></span></td>
                                                        <td width="20%"><span style="font-size: x-small;"><b>PHONE NUMBER ~ '.$Phone_no.'</b></span></td>
						</tr>
						<tr>
							<td><span style="font-size: x-small;"><b>FOLIO NO ~ '.$Folio_Number.'</b></span></td>
							<td><span style="font-size: x-small;"><b>AUTHORIZATION NO ~ '.$AuthorizationNo.'</b></span></td>
							<td><span style="font-size: x-small;"><b>CLAIM NO ~ '.$Claim_Form_Number.'</b></span></td>
							<td colspan="2"><span style="font-size: x-small;"><b>SPONSOR ~ '.$Guarantor_Name.'</b></span></td>
						</tr>
						</table>';
				
					$slct = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
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
						$cat_no = 0;
						$Sub_Total = 0;
						while($det = mysqli_fetch_array($slct)){
							$Total = 0;
							$Item_category_ID = $det['Item_category_ID'];
							$Item_Category_Name = $det['Item_Category_Name'];
							$htm .= '<br/><span style="font-size: x-small;"><b>'.++$cat_no.'. '.strtoupper($Item_Category_Name).'</span></b>';
							$htm .= '<table width="100%" style="border-collapse: collapse;" border="1">';
							$htm .= "<tr>
										<td width='3%' style='text-align: center;'><span style='font-size: x-small;'>SN</span></td>
										<td><span style='font-size: x-small;'>ITEM NAME</span></td>
										<td width='14%'><span style='font-size: x-small;'>CONSULTANT</span></td>
										<td width='7%' style='text-align: right;'><span style='font-size: x-small;'>PRICE</span></td>
										<td width='4%' style='text-align: center;'><span style='font-size: x-small;'>QTY</span></td>
										<td width='4%' style='text-align: right;'><span style='font-size: x-small;'>DISC</span></td>
										<td width='6%' style='text-align: center;'><span style='font-size: x-small;'>TRANS#</span></td>
										<td width='12%'><span style='font-size: x-small;'>TRANS DATE</span></td>
										<td style='text-align: right;' width='9%'><span style='font-size: x-small;'>TOTAL</span></td>
									</tr>";
							$get_items = mysqli_query($conn,"select ppl.Patient_Direction, ppl.Consultant_ID, i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount,  ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Receipt_Date from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											i.Item_ID = ppl.Item_ID and
											pp.Transaction_status <> 'cancelled' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											ic.Item_category_ID = '$Item_category_ID' and
											pp.Folio_Number = '$Folio_Number' and
                                                                                        $Filter_Billing and     
											pp.receipt_date between '$Start_Date' and '$End_Date' and
											pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
									$htm .= "<tr>
										<td width='5%' style='text-align: center;'><span style='font-size: x-small;'>".++$inum."</span></td>
										<td><span style='font-size: x-small;'>".ucwords(strtolower($dts['Product_Name']))."</span></td>
										<td><span style='font-size: x-small;'>".ucwords(strtolower($Doctor_Name))."</span></td>
										<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($dts['Price'])."</span></td>
										<td style='text-align: center;'><span style='font-size: x-small;'>".$dts['Quantity']."</span></td>
										<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($dts['Discount'])."</span></td>
										<td width='8%' style='text-align: center;'><span style='font-size: x-small;'>".$dts['Patient_Payment_ID']."</span></td>
										<td width='12%'><span style='font-size: x-small;'>".$dts['Receipt_Date']."</span></td>
										<td style='text-align: right;'><span style='font-size: x-small;'>".number_format(($dts['Price'] - $dts['Discount'])*$dts['Quantity'])."</span></td>
									</tr>";
									$Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
									$Grand_Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
									$Sub_Total += (($dts['Price'] - $dts['Discount'])*$dts['Quantity']);
								}
								//$htm .= "<tr><td colspan='9'><hr></td></tr>";
								$htm .= "<tr><td colspan='8' style='text-align: right;'><span style='font-size: x-small;'><b>SUB TOTAL</b></span></td>
											<td style='text-align: right;'><b><span style='font-size: x-small;'>".number_format($Total)."</b></span></td></tr>";
								//$htm .= "<tr><td colspan='9'><hr></td></tr>";
								$htm .= '</table>';
							}
						}
								$htm .= "<table width='100%' style='border-collapse: collapse;' border='1'>
											<tr><td style='text-align: right;'><span style='font-size: x-small;'><b>TOTAL BILL : ".number_format($Sub_Total)."</b></span></td></tr>
										</table>";
								//$htm .= "<table width='100%'><tr><td><hr></td></tr><tr><td>TOTAL BILL : ".number_format($Sub_Total)."</td></tr></table>";
								//$htm .= "<tr><td colspan='8' style='text-align: right;'><b>TOTAL BILL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
								$htm .= "<tr><td colspan='9'><hr></td></tr>";
						$htm .= '</table>';
					}
				
				$htm .= '';

		}
	}
	$htm .= '<table width="100%">
				<tr>
					<td style="text-align: right;" width="91%"><b>GRAND TOTAL</b></td>
					<td width="9%" style="text-align: right;"><b>'.number_format($Grand_Total).'</b></td>
				</tr>
			</table><span style="font-size: x-small;">KEY: <br/>QTY=Quantity;&nbsp;&nbsp;&nbsp;DISC=Discount;&nbsp;&nbsp;&nbsp;TRANS#=Transaction Number;</span>';
	
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();

	$mpdf->SetDisplayMode('fullpage');
	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	// LOAD a stylesheet
	$stylesheet = file_get_contents('patient_file.css');
	$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($data, 2);

	$mpdf->WriteHTML($htm);
	$mpdf->Output();
?>