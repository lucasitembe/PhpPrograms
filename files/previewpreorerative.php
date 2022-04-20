<?php
	session_start();
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }else{
    	$E_Name = ucwords(strtolower($_SESSION['userinfo']['Employee_Name']));
    }

	if(isset($_GET['Pre_Operative_ID'])){
		$Pre_Operative_ID = $_GET['Pre_Operative_ID'];
	}else{
		$Pre_Operative_ID = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age = '';
	}
	
	//Get required details
	$select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Member_Number, pr.Phone_Number,
							poc.Theatre_Time, emp.Employee_Name, poc.Ward_Signature, poc.Theatre_Signature, poc.Operative_Date_Time, poc.Special_Information from 
							tbl_pre_operative_checklist poc, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
							sp.Sponsor_ID = pr.Sponsor_ID and
							poc.Registration_ID = pr.Registration_ID and
							Pre_Operative_ID = '$Pre_Operative_ID' and
							poc.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_assoc($select)) {
			$Registration_ID = $data['Registration_ID'];
			$Patient_Name = $data['Patient_Name'];
			$Gender = $data['Gender'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Member_Number = $data['Member_Number'];
			$Phone_Number = $data['Phone_Number'];
			$Theatre_Time = $data['Theatre_Time'];
			$Employee_Name = $data['Employee_Name'];
			$Ward_Signature = $data['Ward_Signature'];
			$Theatre_Signature = $data['Theatre_Signature'];
			$Operative_Date_Time = $data['Operative_Date_Time'];
			$Special_Information = $data['Special_Information'];
		}

		//Get Nurse in ward & Nurse in Theatre
		$slct = mysqli_query($conn,"select Employee_Name as Ward_Signature, (select Employee_Name from tbl_employee where Employee_ID = '$Theatre_Signature') as Theatre_Signature from tbl_employee where Employee_ID = '$Ward_Signature'") or die(mysqli_error($conn));
		$results = mysqli_fetch_assoc($slct);
		$Ward_Signature = $results['Ward_Signature'];
		$Theatre_Signature = $results['Theatre_Signature'];

		//Get patient Age
		$date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

	}else{
		$Registration_ID = '';
		$Patient_Name = '';
		$Gender = '';
		$Date_Of_Birth = '';
		$Guarantor_Name = '';
		$Member_Number = '';
		$Phone_Number = '';
		$Theatre_Time = '';
		$Employee_Name = '';
		$Ward_Signature = '';
		$Theatre_Signature = '';
		$Operative_Date_Time = '';
		$Special_Information = '';
	}
	
	$htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	    <tr><td>&nbsp;</td></tr></table>";

	$htm .= '<b><span style="font-size: x-small;">PRE - OPERATIVE CHECKLIST REPORT<br/><br/>PATIENT DETAILS</span></b>
				<table width="100%" border=1 style="border-collapse: collapse;">
					<tr>
						<td width="16%" style="text-align: right;"><span style="font-size: x-small;">Patient Name</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($Patient_Name)).'</span></td>
						<td width="16%" style="text-align: right;"><span style="font-size: x-small;">Patient Age</span></td>
						<td><span style="font-size: x-small;">'.$age.'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Patient #</span></td>
						<td><span style="font-size: x-small;">'.$Registration_ID.'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">Gender</span></td>
						<td><span style="font-size: x-small;">'.$Gender.'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Sponsor Name</span></td>
						<td><span style="font-size: x-small;">'.strtoupper($Guarantor_Name).'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">Member Number</span></td>
						<td><span style="font-size: x-small;">'.$Member_Number.'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Theatre Date</span></td>
						<td><span style="font-size: x-small;">'.@date("d F Y H:i:s",strtotime($Theatre_Time)).'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">Phone Number</span></td>
						<td><span style="font-size: x-small;">'.$Phone_Number.'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Nurse In Ward</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($Ward_Signature)).'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">Nurse In Theatre</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($Theatre_Signature)).'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Employee Created</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($Employee_Name)).'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">Date Created</span></td>
						<td><span style="font-size: x-small;">'.@date("d F Y H:i:s",strtotime($Operative_Date_Time)).'</span></td>
					</tr>
					<tr>
						<td style="text-align: right;"><span style="font-size: x-small;">Special Information</span></td>
						<td colspan="3"><span style="font-size: x-small;">'.$Special_Information.'</span></td>
					</tr>
				</table><br/>';

	$Completed = '<table width="100%" border=1 style="border-collapse: collapse;">
					<thead>
						<tr>
							<td colspan="3"><span style="font-size: x-small;"><b>COMPLETED PRE OPERATIVE</b></span></td>
						</tr>
						<tr>
							<td width="4%"><span style="font-size: x-small;"><b>SN</b></span></td>
							<td width="40%"><span style="font-size: x-small;"><b>TASK</b></span></td>
							<td><span style="font-size: x-small;"><b>REMARK</b></span></td>
						</tr></thead>'; 
	$Uncompleted = '<table width="100%" border=1 style="border-collapse: collapse;">
					<thead>
						<tr>
							<td colspan="3"><span style="font-size: x-small;"><b>UNCOMPLETED PRE OPERATIVE</b></span></td>
						</tr>
						<tr>
							<td width="4%"><span style="font-size: x-small;"><b>SN</b></span></td>
							<td width="40%"><span style="font-size: x-small;"><b>TASK</b></span></td>
							<td><span style="font-size: x-small;"><b>REMARK</b></span></td>
						</tr></thead>';
	$C_count = 0;
	$C_uncount = 0;

	$details1 = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items where Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($details1);
	if($no > 0){
		while ($row = mysqli_fetch_array($details1)) {
			$Patient_Identified_Name = $row['Patient_Identified_Name'];
			$Urine_passed = $row['Urine_passed'];
			$Dentures_removed = $row['Dentures_removed'];
			$Contact_lenses = $row['Contact_lenses'];
			$Jowerly_removed = $row['Jowerly_removed'];
			$Cosmetic_and_Clothing = $row['Cosmetic_and_Clothing'];
			$Consent_form_signed = $row['Consent_form_signed'];
			$Enema_or_laxative = $row['Enema_or_laxative'];
			$Other_prosthesis = $row['Other_prosthesis'];
			$Special_order = $row['Special_order'];
			$Operative_site = $row['Operative_site'];
			$Radiographs_accompanying = $row['Radiographs_accompanying'];
			$Test_for_HIV = $row['Test_for_HIV'];
			$Identification_bands = $row['Identification_bands'];
			$Loose_teeth = $row['Loose_teeth'];
			$Hearing_adis = $row['Hearing_adis'];
			$Pre_operative_skin = $row['Pre_operative_skin'];
			$Valuable_securely = $row['Valuable_securely'];
			$Theatre_gowns = $row['Theatre_gowns'];
			$Care_patient_case = $row['Care_patient_case'];
			$Oral_hygiene = $row['Oral_hygiene'];
			$Catheter = $row['Catheter'];
			$Patient_having = $row['Patient_having'];
			$Check_list = $row['Check_list'];
			$Test_for_VDRL = $row['Test_for_VDRL'];
			$Test_for_Hopatitis = $row['Test_for_Hopatitis'];			
		}
	}

	$details2 = mysqli_query($conn,"select * from tbl_pre_operative_Remarks where Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
	$no2 = mysqli_num_rows($details2);
	if($no2 > 0){
		while ($row = mysqli_fetch_array($details2)) {
			$Patient_Idenified_Remark = $row['Patient_Idenified_Remark'];
			$Urine_passed_Remark = $row['Urine_passed_Remark'];
			$Dentures_removed_Remark = $row['Dentures_removed_Remark'];
			$Contact_lenses_Remark = $row['Contact_lenses_Remark'];
			$Jowerly_removed_Remark = $row['Jowerly_removed_Remark'];
			$Cosmetic_and_Clothing_Remark = $row['Cosmetic_and_Clothing_Remark'];
			$Consent_form_signed_Remark = $row['Consent_form_signed_Remark'];
			$Enema_or_laxative_Remark = $row['Enema_or_laxative_Remark'];
			$Other_prosthesis_Remark = $row['Other_prosthesis_Remark'];
			$Special_order_Remark = $row['Special_order_Remark'];
			$Operative_site_Remark = $row['Operative_site_Remark'];
			$Radiographs_accompanying_Remark = $row['Radiographs_accompanying_Remark'];
			$Test_for_HIV_Remark = $row['Test_for_HIV_Remark'];
			$Identification_bands_Remark = $row['Identification_bands_Remark'];
			$Loose_teeth_Remark = $row['Loose_teeth_Remark'];
			$Hearing_adis_Remark = $row['Hearing_adis_Remark'];
			$Pre_operative_skin_Remark = $row['Pre_operative_skin_Remark'];
			$Valuable_securely_Remark = $row['Valuable_securely_Remark'];
			$Theatre_gowns_Remark = $row['Theatre_gowns_Remark'];
			$Care_patient_case_Remark = $row['Care_patient_case_Remark'];
			$Oral_hygiene_Remark = $row['Oral_hygiene_Remark'];
			$Catheter_Remark = $row['Catheter_Remark'];
			$Patient_having_Remark = $row['Patient_having_Remark'];
			$Check_list_Remark = $row['Check_list_Remark'];
			$Test_for_VDRL_Remark = $row['Test_for_VDRL_Remark'];
			$Test_for_Hopatitis_Remark = $row['Test_for_Hopatitis_Remark'];
		}
	}

	//Arrange accordingly
	if($Patient_Identified_Name == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Patient identified name</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Patient_Idenified_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Patient identified name</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Patient_Idenified_Remark.'</span></td></tr>';
	}

	if($Urine_passed == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Urine passed before promed action</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Urine_passed_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Urine passed before promed action</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Urine_passed_Remark.'</span></td></tr>';
	}

	if($Dentures_removed == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Dentures removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Dentures_removed_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Dentures removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Dentures_removed_Remark.'</span></td></tr>';
	}

	if($Contact_lenses == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Contact lenses removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Contact_lenses_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Contact lenses removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Contact_lenses_Remark.'</span></td></tr>';
	}

	if($Jowerly_removed == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Jowerly removed and rings tapped</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Jowerly_removed_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Jowerly removed and rings tapped</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Jowerly_removed_Remark.'</span></td></tr>';
	}

	if($Cosmetic_and_Clothing == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Cosmetic and Clothing Removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Cosmetic_and_Clothing_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Cosmetic and Clothing Removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Cosmetic_and_Clothing_Remark.'</span></td></tr>';
	}

	if($Consent_form_signed == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Consent form signed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Consent_form_signed_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Consent form signed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Consent_form_signed_Remark.'</span></td></tr>';
	}

	if($Enema_or_laxative == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Enema or laxative given</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Enema_or_laxative_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Enema or laxative given</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Enema_or_laxative_Remark.'</span></td></tr>';
	}

	if($Other_prosthesis == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Other prosthesis (if any) removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Other_prosthesis_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Other prosthesis (if any) removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Other_prosthesis_Remark.'</span></td></tr>';
	}

	if($Special_order == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Special order carried out, as Nasogastric tube</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Special_order_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Special order carried out, as Nasogastric tube</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Special_order_Remark.'</span></td></tr>';
	}

	if($Operative_site == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Operative site marked</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Operative_site_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Operative site marked</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Operative_site_Remark.'</span></td></tr>';
	}

	if($Radiographs_accompanying == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Radiographs accompanying patient</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Radiographs_accompanying_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Radiographs accompanying patient</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Radiographs_accompanying_Remark.'</span></td></tr>';
	}

	if($Test_for_HIV == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for HIV</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_HIV_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for HIV</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_HIV_Remark.'</span></td></tr>';
	}

	if($Identification_bands == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Identification bands present and correct</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Identification_bands_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Identification bands present and correct</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Identification_bands_Remark.'</span></td></tr>';
	}

	if($Loose_teeth == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Loose teeth, crowns, and bridges</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Loose_teeth_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Loose teeth, crowns, and bridges</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Loose_teeth_Remark.'</span></td></tr>';
	}

	if($Hearing_adis == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Hearing adis removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Hearing_adis_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Hearing adis removed</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Hearing_adis_Remark.'</span></td></tr>';
	}

	if($Pre_operative_skin == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Pre - operative skin preparation</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Pre_operative_skin_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Pre - operative skin preparation</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Pre_operative_skin_Remark.'</span></td></tr>';
	}

	if($Valuable_securely == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Valuable securely stored</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Valuable_securely_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Valuable securely stored</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Valuable_securely_Remark.'</span></td></tr>';
	}

	if($Theatre_gowns == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Theatre gowns and pants wom</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Theatre_gowns_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Theatre gowns and pants wom</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Theatre_gowns_Remark.'</span></td></tr>';
	}

	if($Care_patient_case == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Care patient case notes and other relevant chart sheet present</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Care_patient_case_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Care patient case notes and other relevant chart sheet present</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Care_patient_case_Remark.'</span></td></tr>';
	}

	if($Oral_hygiene == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Oral hygiene given</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Oral_hygiene_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Oral hygiene given</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Oral_hygiene_Remark.'</span></td></tr>';
	}

	if($Catheter == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Catheter is situ</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Catheter_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Catheter is situ</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Catheter_Remark.'</span></td></tr>';
	}

	if($Patient_having == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Patient having an i.v catheter cannula(as 16,18)</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Patient_having_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Patient having an i.v catheter cannula(as 16,18)</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Patient_having_Remark.'</span></td></tr>';
	}

	if($Check_list == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Check list complete</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Check_list_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Check list complete</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Check_list_Remark.'</span></td></tr>';
	}

	if($Test_for_VDRL == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for VDRL</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_VDRL_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for VDRL</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_VDRL_Remark.'</span></td></tr>';
	}
	
	if($Test_for_Hopatitis == '1'){
		$Completed .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_count.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for Hopatitis</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_Hopatitis_Remark.'</span></td></tr>';
	}else{
		$Uncompleted .= '<tr><td width="4%"><span style="font-size: x-small;">'.++$C_uncount.'</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">Test for Hopatitis</span></td>
			<td style="text-align: left;"><span style="font-size: x-small;">'.$Test_for_Hopatitis_Remark.'</span></td></tr>';
	}

	$htm .= $Completed;
	$htm .= '</table><br/>';

	$htm .= $Uncompleted;
	$htm .= '</table>';

	include("./MPDF/mpdf.php");
	$mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
	$mpdf->SetFooter('Printed by '.$E_Name.'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
	$mpdf->WriteHTML($htm);
	$mpdf->Output();
?>