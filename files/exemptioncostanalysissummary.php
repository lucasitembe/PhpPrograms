<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$E_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$E_Name = '';
	}


	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '0000/00/00 00:00';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '0000/00/00 00:00';
	}

	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
	}else{
		$Item_Category_ID = '';
	}

	if(isset($_GET['msamaha_Items'])){
		$msamaha_Items = $_GET['msamaha_Items'];
	}else{
		$msamaha_Items = '0';
	}

	//create filters
	$filter2 = " pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and ";
	$filter = " ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and sp.Exemption = 'yes' and ";


	if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
		$filter2 .= "ict.Item_Category_ID = '$Item_Category_ID' and ";
	}


	$Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age = '';
	}

	$htm = "<table width ='100%' height = '30px'>
		    <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>EXEMPTION COST ANALYSIS REPORT</span></b></td></tr>
		    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($Date_From))." TO ".@date("d F Y H:i:s",strtotime($Date_To))."</span></b></td></tr>
		    </table>";
	//get msamaha types
	if($msamaha_Items != null && $msamaha_Items != '' && $msamaha_Items != 0){
		$select = mysqli_query($conn,"select msamaha_aina from tbl_msamaha_items where msamaha_Items = '$msamaha_Items' order by msamaha_aina") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select msamaha_aina from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
	}
	$no = mysqli_num_rows($select);
	$count = 0;
	$Unknown_Controler = 'no';
	$Unknowns_Male = 0;
	$Unknowns_Female = 0;

	$Value = array();
	$Categories = array();
	$Temp_Categories = array();

	//get selected categories
	if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
		$get_cats = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
	}else{
		$get_cats = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
	}
	$n_cats = mysqli_num_rows($get_cats);
	if($n_cats > 0){
		while($rw = mysqli_fetch_array($get_cats)){
			//get list of msamaha
			if($msamaha_Items != null && $msamaha_Items != '' && $msamaha_Items != 0){
				$sel = mysqli_query($conn,"select msamaha_Items, msamaha_aina from tbl_msamaha_items where msamaha_Items = '$msamaha_Items'") or die(mysqli_error($conn));
			}else{
				$sel = mysqli_query($conn,"select msamaha_Items, msamaha_aina from tbl_msamaha_items") or die(mysqli_error($conn));
			}
			$m_num = mysqli_num_rows($sel);
			if($m_num > 0){
				while($dtz = mysqli_fetch_array($sel)){
					$m_Items = $dtz['msamaha_Items'];
					$tem1 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Sub_Total'; //Amount Collected
					$tem2 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Male'; //eg 4_Wazee_Male
					$tem3 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Female'; //eg 4_Wazee_Female

					$tm1 = $dtz['msamaha_Items'].'_Total_Male';
					$tm2 = $dtz['msamaha_Items'].'_Total_Female';
					$tm3 = $dtz['msamaha_Items'].'_Total_Male_Unknown';
					$tm4 = $dtz['msamaha_Items'].'_Total_Female_Unknown';

					$$tem1 = 0;
					$$tem2 = 0;
					$$tem3 = 0;

					$$tm1 = 0;
					$$tm2 = 0;
					$$tm3 = 0;
					$$tm4 = 0;
				}
			}
			$tem4 = $rw['Item_Category_ID'].'_Total_Unknown';
			$tem5 = $rw['Item_Category_ID'].'_Male_Unknown';
			$tem6 = $rw['Item_Category_ID'].'_Female_Unknown';
			
			$tem7 = $rw['Item_Category_ID'].'_Grand_Total_Amount';
			$tem8 = $rw['Item_Category_ID'].'_Grand_Total_Male';
			$tem9 = $rw['Item_Category_ID'].'_Grand_Total_Female';
			$tem10 = $rw['Item_Category_ID'].'_Grand_Total_Male_Unknown';
			$tem11 = $rw['Item_Category_ID'].'_Grand_Total_Female_Unknown';

			$$tem4 = 0; //Unknown Total Amount
			$$tem5 = 0; //Unknown male
			$$tem6 = 0; //Unknown female

			$$tem7 = 0; //Grand Total Total Amount
			$$tem8 = 0; //Grand Total male
			$$tem9 = 0; //Grand Total female
			$$tem10 = 0; //Grand Total male unknown
			$$tem11 = 0; //Grand Total female unknown
		}
	}

	if($no > 0){
		$details = mysqli_query($conn,"select Gender, pr.Registration_ID, ci.Check_In_ID, pr.Sponsor_ID, ci.msamaha_Items from
	 							tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
	 							$filter
	 							pr.Sponsor_ID = sp.Sponsor_ID and
	 							ci.Registration_ID = pr.Registration_ID") or die(mysqli_error($conn));
		$no_det = mysqli_num_rows($details);
		if($no_det > 0){
			//get details
			while ($dt = mysqli_fetch_array($details)) {
				$Registration_ID = $dt['Registration_ID'];
				$Check_In_ID = $dt['Check_In_ID'];
				$Sponsor_ID = $dt['Sponsor_ID'];
				$Gender = $dt['Gender'];
				$m_Items = $dt['msamaha_Items'];
				
				$Gender_Controler = 'yes';
				$tm1 = $dt['msamaha_Items'].'_Total_Male';
				$tm2 = $dt['msamaha_Items'].'_Total_Female';
				$tm3 = $dt['msamaha_Items'].'_Total_Male_Unknown';
				$tm4 = $dt['msamaha_Items'].'_Total_Female_Unknown';

				//variables to control patients count
				$var_cou = mysqli_query($conn,"select Item_Category_ID from tbl_Exemption_Categories") or die(mysqli_error($conn));
				$n_cou = mysqli_num_rows($var_cou);
				if($n_cou > 0){
					while ($dr = mysqli_fetch_array($var_cou)) {
						$tmp = 'Gender_counter_'.$dr['Item_Category_ID'];
						$$tmp = 'yes';
					}
				}

				//get all payments made based on patient selected
				$pay_details = mysqli_query($conn,"select ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, ict.Item_Category_ID, ci.msamaha_Items from
											tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isc, tbl_item_category ict, tbl_check_in ci where
											$filter2
											pp.Registration_ID = '$Registration_ID' and
											pp.Sponsor_ID = '$Sponsor_ID' and
											pp.Transaction_status <> 'cancelled' and
											pp.Check_In_ID = ci.Check_In_ID and
											pp.Check_In_ID = '$Check_In_ID' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
											ppl.Item_ID = i.Item_ID and
											i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
											isc.Item_category_ID = ict.Item_category_ID and
											ict.Item_Category_ID IN (select Item_Category_ID from tbl_Exemption_Categories)") or die(mysqli_error($conn));
				$nm_pay = mysqli_num_rows($pay_details);
				
				//Get total amount collected
				if($nm_pay > 0){
					while ($my_det = mysqli_fetch_array($pay_details)) {
						$tmp = 'Gender_counter_'.$my_det['Item_Category_ID'];

						$tem1 = $my_det['Item_Category_ID'].'_'.$dt['msamaha_Items'].'_Sub_Total'; //Amount Collected
						$tem2 = $my_det['Item_Category_ID'].'_'.$dt['msamaha_Items'].'_Male'; //eg 4_Wazee_Male
						$tem3 = $my_det['Item_Category_ID'].'_'.$dt['msamaha_Items'].'_Female'; //eg 4_Wazee_Female

						$tem4 = $my_det['Item_Category_ID'].'_Total_Unknown';
						$tem5 = $my_det['Item_Category_ID'].'_Male_Unknown';
						$tem6 = $my_det['Item_Category_ID'].'_Female_Unknown';

						$tem7 = $my_det['Item_Category_ID'].'_Grand_Total_Male';
						$tem8 = $my_det['Item_Category_ID'].'_Grand_Total_Female';
						$tem9 = $my_det['Item_Category_ID'].'_Grand_Total_Male_Unknown';
						$tem10 = $my_det['Item_Category_ID'].'_Grand_Total_Female_Unknown';

						//Calculate Amount
						if(isset($m_Items) && $m_Items > 0 && $m_Items != null && $m_Items != ''){
							$$tem1 += $my_det['Amount'];
						}else{
							$$tem4 += $my_det['Amount'];
							$Unknown_Controler = 'yes';
						}

						//Calculate registered male and female (Calculation per category)
						if($$tmp == 'yes'){
							if(isset($m_Items) && $m_Items > 0 && $m_Items != null && $m_Items != ''){
								if(strtolower($Gender) == 'male'){
									$$tem2 += 1;
								}else{
									$$tem3 += 1;
								}
							}else{
								if(strtolower($Gender) == 'male'){
									$$tem5 += 1;
									$Unknown_Controler = 'yes';
								}else{
									$$tem6 += 1;
									$Unknown_Controler = 'yes';
								}
							}
							$$tmp = 'no';
						}

						//general registered male and female (Calculation per msamaha type)
						if($Gender_Controler == 'yes'){
							if(isset($m_Items) && $m_Items > 0 && $m_Items != null && $m_Items != ''){
								if(strtolower($Gender) == 'male'){
									$$tm1 += 1;
								}else{
									$$tm2 += 1;
								}
							}else{
								if(strtolower($Gender) == 'male'){
									$$tm3 += 1;
									$Unknown_Controler = 'yes';
								}else{
									$$tm4 += 1;
									$Unknown_Controler = 'yes';
								}
							}
							$Gender_Controler = 'no';
						}
					}
				}
			}
		}
	}

	if($msamaha_Items != null && $msamaha_Items != '' && $msamaha_Items != 0){
		$select = mysqli_query($conn,"select msamaha_aina, msamaha_Items from tbl_msamaha_items where msamaha_Items = '$msamaha_Items' order by msamaha_aina") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select msamaha_aina, msamaha_Items from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
	}
	$no = mysqli_num_rows($select);
	$Counter = 0;
	$Grand_Total_Male = 0;
	$Grand_Total_Female = 0;
	$Genaral_Grand_Total = 0;

	if($no > 0){
		//Displaying title
		if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
			$get_cats1 = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
		}else{
			$get_cats1 = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
		}

		$nmz = mysqli_num_rows($get_cats1);
		if($nmz > 0){
			$SN = 0;
			$htm .= '<br/><br/><table width="100%" border=1 style="border-collapse: collapse;">';
			$htm .= '<tr><td width="3%"><b>SN</b></td><td width="15%"><b>GROUP</b></td>';
			while ($mr = mysqli_fetch_array($get_cats1)) {
				$htm .= '<td colspan="3"><b>'.$mr['Item_Category_Name'].'</b></td>';
			}
			$htm .= '<td colspan="3"><b>TOTAL</b></td><tr>';

			//Inner part
			$htm .= '<tr><td>&nbsp;</td><td>&nbsp;</td>';
			for ($i = 0; $i < $nmz; $i++) {
				$htm .= '<td width="5%" style="text-align: center;">ME</td><td width="5%" style="text-align: center;">KE</td><td style="text-align: right;">AMOUNT</td>';
			}
			$htm .= '<td width="5%" style="text-align: center;">ME</td><td width="5%" style="text-align: center;">KE</td><td style="text-align: right;">AMOUNT</td><tr>';

			//Displaying Details
			while ($data = mysqli_fetch_array($select)) {
				$m_Items = $data['msamaha_Items'];
				$msamaha_aina = $data['msamaha_aina'];

				$tm1 = $data['msamaha_Items'].'_Total_Male';
				$tm2 = $data['msamaha_Items'].'_Total_Female';
				
				$Tota_Male = 0;
				$Total_Female = 0;
				$Total_Amount = 0;
				$htm .= '<tr><td>'.(++$SN).'</td><td>'.strtoupper($msamaha_aina).'</td>';
				
				if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
					$get_cats2 = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
				}else{
					$get_cats2 = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
				}

				$nmz2 = mysqli_num_rows($get_cats2);
				if($nmz2 > 0){
					while ($mr2 = mysqli_fetch_array($get_cats2)) {
						$tem1 = $mr2['Item_Category_ID'].'_'.$m_Items.'_Male';
						$tem2 = $mr2['Item_Category_ID'].'_'.$m_Items.'_Female';
						$tem3 = $mr2['Item_Category_ID'].'_'.$m_Items.'_Sub_Total';

						$tem4 = $mr2['Item_Category_ID'].'_Grand_Total_Male';
						$tem5 = $mr2['Item_Category_ID'].'_Grand_Total_Female';
						$tem6 = $mr2['Item_Category_ID'].'_Grand_Total_Amount';

						//if($$tem1 < 1 && $$tem2 < 1){ $$tem3 = 0; }
						$htm .= '<td style="text-align: center;">'.$$tem1.'</td>';
						$htm .= '<td style="text-align: center;">'.$$tem2.'</td>';
						$htm .= '<td style="text-align: right;">'.number_format($$tem3).'</td>';
						$Tota_Male += $$tem1;
						$Total_Female += $$tem2;
						$Total_Amount += $$tem3;
						$$tem4 += $$tem1; //Grand Total Male
						$$tem5 += $$tem2; //Grand Total Female
						$$tem6 += $$tem3; //Grand Total Amount
					}
					$Grand_Total_Male += $$tm1;
					$Grand_Total_Female += $$tm2;
					$Genaral_Grand_Total += $Total_Amount;
					$htm .= '<td style="text-align: center;">'.$$tm1.'</td>';
					$htm .= '<td style="text-align: center;">'.$$tm2.'</td>';
					$htm .= '<td style="text-align: right;">'.number_format($Total_Amount).'</td><tr>';
				}
			}

			//Unknown details
			//Grand Total Details
			$Grant_Male_Unknown = 0;
			$Grand_Female_Unknown = 0;
			$Grand_Amount_Unknown = 0;
			/*if($Unknown_Controler == 'yes'){
				if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
					$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
				}else{
					$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
				}

				$htm .= "<tr><td>".(++$SN)."<td><b>OTHERS</b></td>";
				$nm = mysqli_num_rows($select);
				if($nm > 0){
					while ($data = mysqli_fetch_array($select)) {
						$tem1 = $data['Item_Category_ID'].'_Grand_Total_Male_Unknown';
						$tem2 = $data['Item_Category_ID'].'_Grand_Total_Female_Unknown';
						$tem3 = $data['Item_Category_ID'].'_Total_Unknown';

						//if($$tem1 < 1 && $$tem2 < 1){ $$tem3 = 0; }
						$htm .= '<td style="text-align: center;">'.$$tem1.'</td>';
						$htm .= '<td style="text-align: center;">'.$$tem2.'</td>';
						$htm .= '<td style="text-align: right;">'.number_format($$tem3).'</td>';
						$Grant_Male_Unknown += $$tem1;
						$Grand_Female_Unknown += $$tem2;
						$Grand_Amount_Unknown += $$tem3;
					}
					$htm .= '<td style="text-align: center;">'.$Grant_Male_Unknown.'</td>';
					$htm .= '<td style="text-align: center;">'.$Grand_Female_Unknown.'</td>';
					$htm .= '<td style="text-align: right;">'.number_format($Grand_Amount_Unknown).'</td><tr>';
				}
			}*/





			//Grand Total Details
			if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
				$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
			}else{
				$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
			}

			$Grant_Male = 0;
			$Grand_Female = 0;
			$Grand_Amount = 0;
			$htm .= "<tr><td colspan='2'><b>GRAND TOTAL</b></td>";
			$nm = mysqli_num_rows($select);
			if($nm > 0){
				while ($data = mysqli_fetch_array($select)) {
					$tem1 = $data['Item_Category_ID'].'_Grand_Total_Male';
					$tem2 = $data['Item_Category_ID'].'_Grand_Total_Female';
					$tem3 = $data['Item_Category_ID'].'_Grand_Total_Amount';

					$tem4 = $data['Item_Category_ID'].'_Male_Unknown';
					$tem5 = $data['Item_Category_ID'].'_Female_Unknown';
					$tem6 = $data['Item_Category_ID'].'_Total_Unknown';

					$htm .= '<td style="text-align: center;">'.($$tem1 + $$tem4).'</td>';
					$htm .= '<td style="text-align: center;">'.($$tem2 + $$tem5).'</td>';
					$htm .= '<td style="text-align: right;">'.number_format($$tem3 + $$tem6).'</td>';
					$Grant_Male += ($$tem4);
					$Grand_Female += ($$tem5);
					$Grand_Amount += ($$tem3 + $$tem6);
				}
				$htm .= '<td style="text-align: center;">'.($Grand_Total_Male + $Grant_Male).'</td>';
				$htm .= '<td style="text-align: center;">'.($Grand_Total_Female + $Grand_Female).'</td>';
				$htm .= '<td style="text-align: right;">'.number_format($Grand_Amount).'</td><tr>';
			}
			$htm .= '</table>';
		}
	}
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>