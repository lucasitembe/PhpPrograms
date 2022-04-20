<legend style="background-color:#006400;color:white;padding:5px;"><b>EXEMPTION COST ANALYSIS REPORT</b></legend>
<table width="100%" class="table table-hover">
<?php
	session_start();
	include("./includes/connection.php");
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

	//get msamaha types
	if($msamaha_Items != null && $msamaha_Items != '' && $msamaha_Items != 0){
		$select = mysqli_query($conn,"select msamaha_aina, msamaha_Items from tbl_msamaha_items where msamaha_Items = '$msamaha_Items' order by msamaha_aina") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select msamaha_aina, msamaha_Items from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
	}
	$no = mysqli_num_rows($select);
	$count = 0;
	$Unknowns_Male = 0;
	$Unknowns_Female = 0;


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
					$tem1 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Sub_Total'; //eg 4_Wazee_Sub_Total
					$tem2 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Male'; //eg 4_Wazee_Male
					$tem3 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Female'; //eg 4_Wazee_Female
					$tem4 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Sub_Total_Unknown'; //eg 4_Wazee_Sub_Total_Unknown
					$tem5 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Male_Unknown'; //eg 4_Wazee_Male_Unknown
					$tem6 = $rw['Item_Category_ID'].'_'.$dtz['msamaha_Items'].'_Female_Unknown'; //eg 4_Wazee_Female_Unknown
					$tem10 = $dtz['msamaha_Items'].'_Total_Male';
					$tem11 = $dtz['msamaha_Items'].'_Total_Female';
					$$tem1 = 0;
					$$tem2 = 0;
					$$tem3 = 0;
					$$tem4 = 0;	//Unknown Sub Total Amount
					$$tem5 = 0;	//Unknown Male
					$$tem6 = 0;	//Unknown Female
					$$tem10 = 0;
					$$tem11 = 0;
				}
			}
			$tem1 = $rw['Item_Category_ID'].'Sub_Total';
			$tem2 = $rw['Item_Category_ID'].'_Male';
			$tem3 = $rw['Item_Category_ID'].'_Female';
			
			$tem4 = $rw['Item_Category_ID'].'_Total_Unknown';
			$tem5 = $rw['Item_Category_ID'].'_Male_Unknown';
			$tem6 = $rw['Item_Category_ID'].'_Female_Unknown';
			$$tem1 = 0;
			$$tem2 = 0;
			$$tem3 = 0;
			$$tem4 = 0; //Unknown Total Amount
			$$tem5 = 0; //Unknown male
			$$tem6 = 0; //Unknown female
		}
	}

	if($no > 0){

		$details = mysqli_query($conn,"select Gender, pr.Registration_ID, ci.Check_In_ID, pr.Sponsor_ID, ci.msamaha_Items from 	tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where $filter pr.Sponsor_ID = sp.Sponsor_ID and 	ci.Registration_ID = pr.Registration_ID") or die(mysqli_error($conn));
		$no_det = mysqli_num_rows($details);
		if($no_det > 0){
			//get details
			while ($dt = mysqli_fetch_array($details)) {$rw['Item_Category_ID'].'_Male';
				$Registration_ID = $dt['Registration_ID'];
				$Gender_counter = 'yes';
				$Check_In_ID = $dt['Check_In_ID'];
				$Sponsor_ID = $dt['Sponsor_ID'];
				$Gender = $dt['Gender'];
				$m_Items = $dt['msamaha_Items'];

				//variables to control patients count
				$var_cou = mysqli_query($conn,"select Item_Category_ID from tbl_exemption_categories") or die(mysqli_error($conn));
				$n_cou = mysqli_num_rows($var_cou);
				if($n_cou > 0){
					while ($dr = mysqli_fetch_array($var_cou)) {
						$tmp = 'Gender_counter_'.$dr['Item_Category_ID'];
						$$tmp = 'yes';
					}
				}
				//get all payments made based on patient selected
				$pay_details = mysqli_query($conn,"SELECT ((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.Billing_Type, ict.Item_Category_ID from 	tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isc, tbl_item_category ict where 	$filter2  	pp.Registration_ID = '$Registration_ID' and 	pp.Sponsor_ID = '$Sponsor_ID' and 	pp.Transaction_status <> 'cancelled' and 	pp.Check_In_ID = '$Check_In_ID' and 	pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and 	ppl.Item_ID = i.Item_ID and	i.Item_Subcategory_ID = isc.Item_Subcategory_ID and 	isc.Item_category_ID = ict.Item_category_ID and 	ict.Item_Category_ID IN (select Item_Category_ID from tbl_exemption_categories)") or die(mysqli_error($conn));
				$nm_pay = mysqli_num_rows($pay_details);
				
				//Get total amount collected
				if($nm_pay > 0){
					while ($my_det = mysqli_fetch_array($pay_details)) {
						$tmp = 'Gender_counter_'.$my_det['Item_Category_ID'];
						$tem2 = $my_det['Item_Category_ID'].'_Total_Unknown';
						$tem1 = $my_det['Item_Category_ID'].'_'.$m_Items.'_Sub_Total';
						
						if(isset($$tem1)){
							$$tem1 += $my_det['Amount'];
						}else{
							$$tem2 += $my_det['Amount'];
						}

						//Calculate registered male and female per msamaha type
						if($$tmp == 'yes'){
							$tem3 = $my_det['Item_Category_ID'].'_'.$m_Items.'_Male';
							$tem4 = $my_det['Item_Category_ID'].'_'.$m_Items.'_Female';
							$tem5 = $my_det['Item_Category_ID'].'_'.$m_Items.'Male_Unknown';
							$tem6 = $my_det['Item_Category_ID'].'_'.$m_Items.'Female_Unknown';
							
							if(strtolower($Gender) == 'male'){
								if(isset($$tem3)){
									$$tem3 += 1;
								}else{
									$$tem5 += 1;
								}
							}else if(strtolower($Gender) == 'female'){
								if(isset($$tem4)){
									$$tem4 += 1;
								}else{
									$$tem6 += 1;
								}
							}
							$$tmp = 'no';
						}

						//Calculate registered male and female per category
						if($Gender_counter == 'yes'){
							$tem7 = $m_Items.'_Total_Male';
							$tem8 = $m_Items.'_Total_Female';
							$tem9 = $my_det['Item_Category_ID'].'_'.'Male_Unknown';
							$tem10 = $my_det['Item_Category_ID'].'_'.'Female_Unknown';
							
							if(strtolower($Gender) == 'male'){
								if(isset($$tem7)){
									$$tem7 += 1;
								}else{
									$$tem9 += 1;
								}
							}else if(strtolower($Gender) == 'female'){
								if(isset($$tem8)){
									$$tem8 += 1;
								}else{
									$$tem10 += 1;
								}
							}
							$Gender_counter = 'no';
						}
					}
				}//
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
		//Displaying details generated
		while ($data = mysqli_fetch_array($select)) {
			$m_Items = $data['msamaha_Items'];
			$t_male = $m_Items.'_Total_Male';
			$t_female = $m_Items.'_Total_Female';

			//$htm .= '<tr><td colspan="2"><b>'.++$Counter.'.&nbsp;&nbsp;&nbsp;GROUP NAME : </b>'.strtoupper($data['msamaha_aina']).'</td></tr>';

			//displaying categories and values
			if($Item_Category_ID != '' && $Item_Category_ID != null && $Item_Category_ID != 0){
				$get_cats = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID and ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
			}else{
				$get_cats = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
			}
			$nmz = mysqli_num_rows($get_cats);
			if($nmz > 0){
				$con = 0; $Total_Male = 0; $Total_Female = 0; $Grand_Total = 0;
				$htm .= '<tr><td colspan="2"><table width="100%" border=1 style="border-collapse: collapse;">';
				$htm .= "<tr><td colspan='6'><b><span style='font-size: x-small;'>".++$Counter."&nbsp;&nbsp;&nbsp;GROUP NAME : </b>".strtoupper($data['msamaha_aina'])."</span></b></td></tr>";
				$htm .= "<tr><td width=5%><span style='font-size: x-small;'><b>S/N</b></span></td>
							<td><b><span style='font-size: x-small;'>CATEGORY NAME</span></b></td>
							<td style='text-align: right;' width='18%'><b><span style='font-size: x-small;'>CREDIT AMOUNT</span></b></td>
							<td style='text-align: center;' width='15%'><b><span style='font-size: x-small;'>REGISTERED MALE</span></b></td>
							<td style='text-align: center;' width='15%'><b><span style='font-size: x-small;'>REGISTERED FEMALE</span></b></td>
							<td style='text-align: center;' width='15%'><b><span style='font-size: x-small;'>TOTAL REGISTERED</span></b></td>
						</tr>";
				while ($wr = mysqli_fetch_array($get_cats)) {
					$tem1 = $wr['Item_Category_ID'].'_'.$m_Items.'_Sub_Total';
					$tem2 = $wr['Item_Category_ID'].'_'.$m_Items.'_Male';
					$tem3 = $wr['Item_Category_ID'].'_'.$m_Items.'_Female';


					$htm .= "<tr>
								<td width=5%><span style='font-size: x-small;'>".++$con."</span></td>
								<td><span style='font-size: x-small;'>".strtoupper($wr['Item_Category_Name'])."</span></td>
								<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($$tem1)."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".$$tem2."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".$$tem3."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".($$tem2 + $$tem3)."</span></td>
							</tr>";

					$Grand_Total += $$tem1;
					$Genaral_Grand_Total += $$tem1;
				}

				$Total_Male += $$t_male;
				$Total_Female += $$t_female;
				$Grand_Total_Male += $$t_male;
				$Grand_Total_Female += $$t_female;

				$htm .= "<tr>
					<td colspan='2'><b><span style='font-size: x-small;'>TOTAL</span><b></td>
					<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($Grand_Total)."</span></td>
					<td style='text-align: center;'><span style='font-size: x-small;'>".$$t_male."</span></td>
					<td style='text-align: center;'><span style='font-size: x-small;'>".$$t_female."</span></td>
					<td style='text-align: center;'><span style='font-size: x-small;'>".($$t_female + $$t_male)."</span></td></tr>";
				$htm .= "</table></td></tr><tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2></td></tr></table>";
			}
		}
	}

	$htm .= "<br/><br/>";
	$htm .= "<span style='font-size: x-small;'><b>GRAND TOTAL : </b>".number_format($Genaral_Grand_Total)."</span><br/>";
	$htm .= "<span style='font-size: x-small;'><b>TOTAL MALE : </b>".$Grand_Total_Male."</span><br/>";
	$htm .= "<span style='font-size: x-small;'><b>TOTAL FEMALE : </b>".$Grand_Total_Female."</span><br/>";
	$htm .= "<span style='font-size: x-small;'><b>TOTAL PATIENTS : </b>".($Grand_Total_Male + $Grand_Total_Female)."</span><br/>";
	
	echo $htm;
?>