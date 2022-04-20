<?php
	session_start();
	include("./includes/connection.php");
	$Number = 0;
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}


	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = 0;
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	}

	$select1 = mysqli_query($conn,"select Payment_Item_Cache_List_ID,consultation_ID from tbl_surgery where
		Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select1);
			// die ("select Payment_Item_Cache_List_ID,consultation_ID from tbl_surgery where
			// Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'".$no);
			if($no > 0) {


									//get patient details
						$select = mysqli_query($conn,"select Patient_Name, Guarantor_Name, Member_Number, pr.Phone_Number, Gender,
						Date_Of_Birth from tbl_patient_registration pr, tbl_sponsor sp where
						pr.Sponsor_ID = sp.Sponsor_ID and
						pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
					while ($data = mysqli_fetch_array($select)) {
					$Patient_Name = $data['Patient_Name'];
					$Guarantor_Name = $data['Guarantor_Name'];
					$Member_Number = $data['Member_Number'];
					$Phone_Number = $data['Phone_Number'];
					$Gender = $data['Gender'];
					$Date_Of_Birth = $data['Date_Of_Birth'];
					}
					$date1 = new DateTime($Today);
					$date2 = new DateTime($Date_Of_Birth);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
					$age .= $diff->d." Days";
					}else{
					$Patient_Name = '';
					$Guarantor_Name = '';
					$Member_Number = '';
					$Phone_Number = '';
					$Gender = '';
					$Date_Of_Birth = '';
					$age = '';
					}


					$htm = "<table width ='100%'>
					<tr>
					<td>
					<img src='./branchBanner/branchBanner.png' width=100%>
					</td>
					</tr>
					<tr>
					<td style='text-align: center;'><b>POST OPERATIVE NOTES</b></td>
					</tr></table><br/>"; // border=1 style='border-collapse: collapse;'

					$htm .= "<b><span style='font-size: small;'>".++$Number.": PATIENT DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Patient Name</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Patient_Name))."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Patient #</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Registration_ID))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Gender</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Gender))."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Age</span></td>
					<td><span style='font-size: small;'>".$age."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Sponsor Name</span></td>
					<td><span style='font-size: small;'>".strtoupper($Guarantor_Name)."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Member #</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Member_Number))."</span></td>
					</tr>
					</table><br/>";

					//get Product_Name
					$select = mysqli_query($conn,"select i.Product_Name from tbl_item_list_cache ilc, tbl_items i where
								ilc.Item_ID = i.Item_ID and
								ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
					$nm = mysqli_num_rows($select);
					if($nm > 0){
					while ($row = mysqli_fetch_array($select)) {
					$Product_Name = $row['Product_Name'];
					}
					}else{
					$Product_Name = '';
					}

					//get surgery details
					$select = mysqli_query($conn,"select consultation_ID,cutting_time,end_cutting_time,pos.Surgery_Date,pos.duration_of_surgery, pos.Incision, pos.Position, pos.Type_Of_Anesthetic, pos.Post_operative_ID, pos.consultation_ID
								from tbl_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i where
								pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
								ilc.Item_ID - i.Item_ID and
								ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					while ($row = mysqli_fetch_array($select)) {
					$Surgery_Date = $row['Surgery_Date'];
					$duration_of_surgery = $row['duration_of_surgery'];
					$Incision = $row['Incision'];
					$Position = $row['Position'];
					$Type_Of_Anesthetic = $row['Type_Of_Anesthetic'];
					$Post_operative_ID = $row['Post_operative_ID'];
					$cutting_time = $row['cutting_time'];
					$end_cutting_time = $row['end_cutting_time'];
					$consultation_ID = $row['consultation_ID'];
					}
					}else{
					$Surgery_Date = '';
					$Incision = '';
					$Position = '';
					$Type_Of_Anesthetic = '';
					$Post_operative_ID = 0;
					$consultation_ID = 0;
					$duration_of_surgery = '';
					}

					$htm .= "<b><span style='font-size: small;'>".++$Number.": SURGERY DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Name</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($Product_Name))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Date</span></td>
					<td><span style='font-size: small;'>".$Surgery_Date."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Duration</span></td>
					<td width='3%'><span style='font-size: small;'>".$duration_of_surgery."</span></td>
					<td width='10%' style='text-align: right;'><span style='font-size: small;'>Incision</span></td>
					<td><span style='font-size: small;'>".$Incision."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Position</span></td>
					<td colspan='2'><span style='font-size: small;'>".$Position."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Type Of Anesthetic</span></td>
					<td colspan='2'><span style='font-size: small;'>".$Type_Of_Anesthetic."</span></td>
					</tr>
							<tr>
								<td style='text-align:right'><span style='font-size: small;'>Cutting Time</span></td>
								<td colspan='2'><span style='font-size: small;'>$cutting_time</span></td>
								<td style='text-align:right'><span style='font-size: small;'>Ending Cutting Time</span></td>
								<td colspan='2'><span style='font-size: small;'>$end_cutting_time</span></td>
							</tr>
					</table><br/>";

					$select = mysqli_query($conn,"select post_operative_remarks,Procedure_Description, surgery_status, Identification_Of_Prosthesis, Estimated_Blood_loss, Complications,
								Drains, Specimen_sent, Postoperative_Orders from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
					$nop = mysqli_num_rows($select); 
					if($nop > 0){
					$htm .= "<b><span style='font-size: small;'>".++$Number.": COMMENTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
					<td><span style='font-size: small;'>Comments</span></td>
					</tr>";
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
					$Procedure_Description = $data['Procedure_Description'];
					$Identification_Of_Prosthesis = $data['Identification_Of_Prosthesis'];
					$Estimated_Blood_loss = $data['Estimated_Blood_loss'];
					$Complications = $data['Complications'];
					$Drains = $data['Drains'];
					$Specimen_sent = $data['Specimen_sent'];
					$Postoperative_Orders = $data['Postoperative_Orders'];
					$surgery_status = $data['surgery_status'];
					$post_operative_remarks = $data['post_operative_remarks'];

					$htm .= "<tr><td><span style='font-size: small;'>Surgery Status</span></td>
								<td><span style='font-size: small;'>".$surgery_status."</span></td></tr>";

					//if($Procedure_Description != null && $Procedure_Description != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Procedure Description And Closure</span></td>
								<td><span style='font-size: small;'>".$Procedure_Description."</span></td></tr>";
					//}

					//if($Identification_Of_Prosthesis != null && $Identification_Of_Prosthesis != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Identification Of Prosthesis</span></td>
								<td><span style='font-size: small;'>".$Identification_Of_Prosthesis."</span></td></tr>";
					//}

					//if($Estimated_Blood_loss != null && $Estimated_Blood_loss != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Estimated Blood Loss</span></td>
								<td><span style='font-size: small;'>".$Estimated_Blood_loss."</span></td></tr>";
					//}

					//if($Complications != null && $Complications != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Problems / Complications</span></td>
								<td><span style='font-size: small;'>".$Complications."</span></td></tr>";
					//}

					//if($Drains != null && $Drains != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Drains</span></td>
								<td><span style='font-size: small;'>".$Drains."</span></td></tr>";
					//}

					//if($Specimen_sent != null && $Specimen_sent != ''){
					$htm .= "<tr><td><span style='font-size: small;'>Specimen Taken</span></td>
								<td><span style='font-size: small;'>".$Specimen_sent."</span></td></tr>";
					//}
					}
					//get post operative orders
					$Pharmacy = '';
					$Laboratory = '';
					$Radiology='';
					$Num_Pharmacy = 0;
					$Num_Laboratory = 0;

					$slct = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
					$nm_slct = mysqli_num_rows($slct);
					if($nm_slct > 0){
					while($dt = mysqli_fetch_array($slct)){
						$Payment_Cache_ID = $dt['Payment_Cache_ID'];
					}
					}else{
					$Payment_Cache_ID = 0;
					}
					if($Payment_Cache_ID != 0 && $Payment_Cache_ID != null && $Payment_Cache_ID != ''){
					$select_items = mysqli_query($conn,"select i.Product_Name, ilc.Check_In_Type from tbl_item_list_cache ilc, tbl_items i where
												i.Item_ID = ilc.Item_ID and
												ilc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select_items);
					if($num > 0){
						while($dts = mysqli_fetch_array($select_items)){
							if(strtolower($dts['Check_In_Type']) == 'pharmacy'){
								$Pharmacy .= "<span style='font-size: small;'>".++$Num_Pharmacy.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
							}
							if(strtolower($dts['Check_In_Type']) == 'laboratory'){
								$Laboratory .= "<span style='font-size: small;'>".++$Num_Laboratory.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
							}
							if(strtolower($dts['Check_In_Type']) == 'radiology'){
								$Radiology .= "<span style='font-size: small;'>".++$Num_Laboratory.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
							}
						}
					}
									if($Pharmacy != '' || $Laboratory != ''||$Radiology !=''){
						$htm .= "<tr><td><span style='font-size: small;'>Postoperative Orders</span></td>
									<td>";
						if($Pharmacy != ''){
							$htm .= "<span style='font-size: small;'>PHARMACEUTICAL</span><br/>".$Pharmacy.'<br/>';
						}
						if($Laboratory != ''){
							$htm .= "<span style='font-size: small;'>INVESTIGATIONS-LABORATORY</span><br/>".$Laboratory;
						}
						if($Radiology != ''){
							$htm .= "<span style='font-size: small;'>INVESTIGATIONS-RADIOLOGY</span><br/>".$Radiology;
						}
						$htm .= "</td></tr>";
					}
					}
									
					$htm .= "</table><br/>";
					}

					$select = mysqli_query($conn,"select  d.disease_code, d.disease_name
								from tbl_post_operative_diagnosis pod, tbl_disease d where
								d.disease_ID = pod.disease_ID and
								pod.Post_operative_ID = '$Post_operative_ID' and
								pod.Diagnosis_Type = 'Preoperative Diagnosis'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					$htm .= "<b><span style='font-size: small;'>".++$Number.": PREOPERATIVE DIAGNOSIS (INDICATION)</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Disease Code</span></td>
					<td colspan='3'><span style='font-size: small;'>Disease Name</span></td>
					</tr>";
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
					$htm .=	"<tr>
							<td width='20%' style='text-align: right;'><span style='font-size: small;'>".strtoupper($data['disease_code'])."</span></td>
							<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($data['disease_name']))."</span></td>
						</tr>";
					}
					$htm .= "</table><br/>";
					}


					$select = mysqli_query($conn,"select  d.disease_code, d.disease_name
								from tbl_post_operative_diagnosis pod, tbl_disease d where
								d.disease_ID = pod.disease_ID and
								pod.Post_operative_ID = '$Post_operative_ID' and
								pod.Diagnosis_Type = 'Postoperative Diagnosis'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					$htm .= "<b><span style='font-size: small;'>".++$Number.": POSTOPERATIVE DIAGNOSIS (FINDINGS)</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'>Disease Code</span></td>
					<td colspan='3'><span style='font-size: small;'>Disease Name</span></td>
					</tr>";
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
					$htm .=	"<tr>
							<td width='20%' style='text-align: right;'><span style='font-size: small;'>".strtoupper($data['disease_code'])."</span></td>
							<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($data['disease_name']))."</span></td>
						</tr>";
					}
					$htm .= "</table><br/>";
					}
					//get participants
					$Surgeons = '';
					$Assisitant_Surgeons = '';
					$Nurses = '';
					$Anaesthetics = '';

					$select = mysqli_query($conn,"select pop.Employee_Type, emp.Employee_Name from 
							tbl_post_operative_participant pop, tbl_employee emp where
							emp.Employee_ID = pop.Employee_ID and
							pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
					$Counter_Surgeon = 0;
					$Counter_Assistant_Surgeon = 0;
					$Counter_Nurse = 0;
					$Counter_Anaesthetics = 0;

					while ($data = mysqli_fetch_array($select)) {
					if($data['Employee_Type'] == 'Surgeon'){
					$Surgeons .= ++$Counter_Surgeon.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
					}else if($data['Employee_Type'] == 'Assistant Surgeon'){
					$Assisitant_Surgeons .= ++$Counter_Assistant_Surgeon.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
					}else if($data['Employee_Type'] == 'Nurse'){
					$Nurses .= ++$Counter_Nurse.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
					}else if($data['Employee_Type'] == 'Anaesthetics'){
					$Anaesthetics .= ++$Counter_Anaesthetics.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
					}
					}

					$htm .= "<b><span style='font-size: small;'>".++$Number.": PARTICIPANTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
					<td><span style='font-size: small;'>Participant Name</span></td>
					</tr>";
					if($Surgeons != ''){
					$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Surgeons</span></td>
						<td><span style='font-size: small;'>".$Surgeons."</span></td>
					</tr>";
					}
					if($Assisitant_Surgeons != ''){
					$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Assistant Surgeons</span></td>
						<td><span style='font-size: small;'>".$Assisitant_Surgeons."</span></td>
					</tr>";
					}
					if($Nurses != ''){
					$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Nurses</span></td>
						<td><span style='font-size: small;'>".$Nurses."</span></td>
					</tr>";
					}
					if($Anaesthetics != ''){
					$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Anaesthetists</span></td>
						<td><span style='font-size: small;'>".$Anaesthetics."</span></td>
					</tr>";
					}
					$htm .= '</table>';
					}

					//external participants
					$select = mysqli_query($conn,"select * from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
					while ($data = mysqli_fetch_array($select)) {
					$External_Surgeons  = $data['External_Surgeons'];
					$External_Assistant_Surgeon = $data['External_Assistant_Surgeon'];
					$External_Scrub_Nurse = $data['External_Scrub_Nurse'];
					$External_Anaesthetic = $data['External_Anaesthetic'];
					}

					if(($External_Surgeons != null && $External_Surgeons != '') || ($External_Assistant_Surgeon != null && $External_Assistant_Surgeon != '') || ($External_Scrub_Nurse != null && $External_Scrub_Nurse != '') || ($External_Anaesthetic != null && $External_Anaesthetic != '')){
					$htm .= "<br/><b><span style='font-size: small;'>".++$Number.": EXTERNAL PARTICIPANTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
						<td><span style='font-size: small;'>Participant Name</span></td>
					</tr>";

					if($External_Surgeons != '' && $External_Surgeons != null){
					$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Surgeons</span></td>
							<td><span style='font-size: small;'>".$External_Surgeons."</span></td>
						</tr>";
					}
					if($External_Assistant_Surgeon != '' && $External_Assistant_Surgeon != null){
					$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Assistant Surgeons</span></td>
							<td><span style='font-size: small;'>".$External_Assistant_Surgeon."</span></td>
						</tr>";
					}
					if($External_Scrub_Nurse != '' && $External_Scrub_Nurse != null){
					$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Nurses</span></td>
							<td><span style='font-size: small;'>".$External_Scrub_Nurse."</span></td>
						</tr>";
					}
					if($External_Anaesthetic != '' && $External_Anaesthetic != null){
					$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Anaesthetists</span></td>
							<td><span style='font-size: small;'>".$External_Anaesthetic."</span></td>
						</tr>";
					}
					$htm .= '</table>';
					}
					}
					$htm .="<br/><table width='100%' border=1 style='border-collapse: collapse;'>
						<tr><td><b>".++$Number.". POST OPERATIVE REMARKS</b></td></tr>
						<tr><td>$post_operative_remarks</td></tr>
					</table><br/>";






					//ASCAN


					$select = mysqli_query($conn,"select ascan_RE, ascan_LE, recomm_RE, recomm_LE, inserted_RE, inserted_LE, incision_RE, incision_LE, suture_RE from tbl_surgery where
					Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
					consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					while ($row = mysqli_fetch_array($select)) {
					$ascan_RE = $row['ascan_RE'];
					$ascan_LE = $row['ascan_LE'];
					$recomm_RE = $row['recomm_RE'];
					$recomm_LE = $row['recomm_LE'];
					$inserted_RE = $row['inserted_RE'];
					$inserted_LE = $row['inserted_LE'];
					$incision_RE = $row['incision_RE'];
					$incision_RE = $row['incision_RE'];
					$suture_RE = $row['suture_RE'];
					$suture_LE = $row['suture_LE'];
					}
					}else{
					$ascan_RE = '';
					$ascan_LE = '';
					$recomm_RE ='';
					$recomm_LE ='';
					$inserted_RE = '';
					$inserted_LE = '';
					$incision_RE ='';
					$incision_LE='';
					$suture_RE ='';
					$suture_LE ='';
					}

					$htm .= "<b><span style='font-size: small;'>".++$Number.": A-SCAN</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'></span></td>
					<td colspan='5'><span style='font-size: small;'>RE</span></td>
					<td colspan='5'><span style='font-size: small;'>LE</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> A-Scan</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($ascan_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($ascan_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Recomm</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($recomm_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($recomm_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Inserted</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($inserted_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($inserted_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Incision</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($incision_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($incision_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Suture</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($suture_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($suture_LE))."</span></td>
					</tr>
					</table><br/>";


					//CHANGES AND CHALLANGES POSSIBLY AFFECTING THE OURTCOME
					$select = mysqli_query($conn,"SELECT corneal_LE, corneal_RE, synechinae_LE, synechinae_RE, macular_LE, macular_RE, glaucoma_RE, glaucoma_LE, non_glaucoma_LE, non_glaucoma_RE, lens_LE, lens_RE, loose_LE, loose_RE, deep_LE, deep_RE, small_pupil_RE, small_pupil_LE, insufficient_RE, insufficient_LE from tbl_surgery where
					Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
					consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					while ($row = mysqli_fetch_array($select)) {
					$corneal_RE = $row['corneal_RE'];
					$corneal_LE = $row['corneal_LE'];
					$synechinae_LE = $row['synechinae_LE'];
					$synechinae_RE = $row['synechinae_RE'];
					$macular_LE = $row['macular_LE'];
					$macular_RE = $row['macular_RE'];
					$glaucoma_LE = $row['glaucoma_LE'];
					$glaucoma_RE = $row['glaucoma_RE'];
					$non_glaucoma_RE = $row['non_glaucoma_RE'];
					$non_glaucoma_LE = $row['non_glaucoma_LE'];
					$lens_RE = $row['lens_RE'];
					$lens_LE = $row['lens_LE'];
					$loose_LE = $row['loose_LE'];
					$loose_RE = $row['loose_RE'];
					$deep_RE = $row['deep_RE'];
					$deep_LE = $row['deep_LE'];
					$small_pupil_RE = $row['small_pupil_RE'];
					$small_pupil_LE = $row['small_pupil_LE'];
					$insufficient_RE = $row['insufficient_RE'];
					$insufficient_LE = $row['insufficient_LE'];
					}
					}else{
					$corneal_LE = '';
					$corneal_RE = '';
					$synechinae_LE ='';
					$synechinae_RE ='';
					$macular_LE = '';
					$macular_RE = '';
					$glaucoma_RE ='';
					$glaucoma_LE = '';
					$non_glaucoma_RE ='';
					$non_glaucoma_LE ='';
					$lens_LE ='';
					$lens_RE = '';
					$loose_LE = '';
					$loose_RE ='';
					$small_pupil_RE ='';
					$small_pupil_LE = '';
					$insufficient_RE='';
					$insufficient_LE='';
					}

					$htm .= "<b><span style='font-size: small;'>".++$Number.": CHANGERS AND CHALLANGES POSSIBLY AFFECTING THE OURTCOME</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'></span></td>
					<td colspan='5'><span style='font-size: small;'>RE</span></td>
					<td colspan='5'><span style='font-size: small;'>LE</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Corneal Opasity</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($corneal_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($corneal_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Synechinae</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($synechinae_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($synechinae_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Fibrin</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($fibrin_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($fibrin_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Macular/Ratinal Disease</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($macular_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($macular_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Glaucoma</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($glaucoma_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($glaucoma_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Non glaucoma disc disese</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($non_glaucoma_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($non_glaucoma_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Dislocated Lens/span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($lens_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($lens_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Loose zonules Opasity</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($loose_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($loose_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Deep Socket</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($deep_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($deep_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Small pup</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($small_pupil_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($small_pupil_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Insuffient Block</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($insufficient_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($insufficient_LE))."</span></td>
					</tr>
					</table><br/>";


					//COMPLICATIONS


					$select = mysqli_query($conn,"select none_LE,none_RE, pc_tear_RE, pc_tear_LE, vitrelous_RE, vitrelous_LE, other_specify_RE, other_specify_LE from tbl_surgery where
					Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
					consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					while ($row = mysqli_fetch_array($select)) {
					$none_LE = $row['none_LE'];
					$none_RE = $row['none_RE'];
					$pc_tear_RE = $row['pc_tear_RE'];
					$pc_tear_LE = $row['pc_tear_LE'];
					$vitrelous_RE = $row['vitrelous_RE'];
					$vitrelous_LE = $row['vitrelous_LE'];
					$other_specify_RE = $row['other_specify_RE'];
					$other_specify_LE = $row['other_specify_LE'];


					}
					}else{
					$none_RE = '';
					$none_LE = '';
					$pc_tear_RE ='';
					$pc_tear_lE ='';
					$vitrelous_RE = '';
					$vitrelous_LE = '';
					$other_specify_RE ='';
					$other_specify_RE='';


					}

					$htm .= "<b><span style='font-size: small;'>".++$Number.": COMPLICATIONS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'></span></td>
					<td colspan='5'><span style='font-size: small;'>RE</span></td>
					<td colspan='5'><span style='font-size: small;'>LE</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> None</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($none_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($none_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> PC Tear w/o v-loss</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($pc_tear_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($pc_tear_RE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Vitreous loss</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($vitrelous_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($vitrelous_LE))."</span></td>
					</tr>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Other,Specify</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($other_specify_RE))."</span></td>
					<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($other_specify_LE))."</span></td>
					</tr>
					</table><br/>";




					//COMPLICATIONS


					$select = mysqli_query($conn,"select sticker,notes from tbl_surgery where
					Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
					consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
					while ($row = mysqli_fetch_array($select)) {
					$sticker = $row['sticker'];
					$notes = $row['notes'];


					}
					}else{
					$sticker = '';
					$notes = '';


					}
					$htm .= "<b><span style='font-size: small;'>".++$Number.": ATTACHMENT</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'> Sticker</span></td>
					<td colspan='5'><img style='width:600px; height:60px;' src='./upload/".$sticker."'></td>
					</tr>
					</table><br/>";

			}




















			else{

								//get patient details
					$select = mysqli_query($conn,"select Patient_Name, Guarantor_Name, Member_Number, pr.Phone_Number, Gender,
					Date_Of_Birth from tbl_patient_registration pr, tbl_sponsor sp where
					pr.Sponsor_ID = sp.Sponsor_ID and
					pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
				$Patient_Name = $data['Patient_Name'];
				$Guarantor_Name = $data['Guarantor_Name'];
				$Member_Number = $data['Member_Number'];
				$Phone_Number = $data['Phone_Number'];
				$Gender = $data['Gender'];
				$Date_Of_Birth = $data['Date_Of_Birth'];
				}
				$date1 = new DateTime($Today);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$age = $diff->y." Years, ";
				$age .= $diff->m." Months, ";
				$age .= $diff->d." Days";
				}else{
				$Patient_Name = '';
				$Guarantor_Name = '';
				$Member_Number = '';
				$Phone_Number = '';
				$Gender = '';
				$Date_Of_Birth = '';
				$age = '';
				}


				$htm = "<table width ='100%'>
				<tr>
				<td>
				<img src='./branchBanner/branchBanner.png' width=100%>
				</td>
				</tr>
				<tr>
				<td style='text-align: center;'><b>POST OPERATIVE NOTES</b></td>
				</tr></table><br/>"; // border=1 style='border-collapse: collapse;'

				$htm .= "<b><span style='font-size: small;'>".++$Number.": PATIENT DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Patient Name</span></td>
				<td><span style='font-size: small;'>".ucwords(strtolower($Patient_Name))."</span></td>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Patient #</span></td>
				<td><span style='font-size: small;'>".ucwords(strtolower($Registration_ID))."</span></td>
				</tr>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Gender</span></td>
				<td><span style='font-size: small;'>".ucwords(strtolower($Gender))."</span></td>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Age</span></td>
				<td><span style='font-size: small;'>".$age."</span></td>
				</tr>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Sponsor Name</span></td>
				<td><span style='font-size: small;'>".strtoupper($Guarantor_Name)."</span></td>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Member #</span></td>
				<td><span style='font-size: small;'>".ucwords(strtolower($Member_Number))."</span></td>
				</tr>
				</table><br/>";

				//get Product_Name
				$select = mysqli_query($conn,"select i.Product_Name from tbl_item_list_cache ilc, tbl_items i where
							ilc.Item_ID = i.Item_ID and
							ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
				$nm = mysqli_num_rows($select);
				if($nm > 0){
				while ($row = mysqli_fetch_array($select)) {
				$Product_Name = $row['Product_Name'];
				}
				}else{
				$Product_Name = '';
				}

				//get surgery details
				$select = mysqli_query($conn,"select consultation_ID,cutting_time,end_cutting_time,pos.Surgery_Date,pos.duration_of_surgery, pos.Incision, pos.Position, pos.Type_Of_Anesthetic, pos.Post_operative_ID, pos.consultation_ID
							from tbl_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i where
							pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
							ilc.Item_ID - i.Item_ID and
							ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
				$no = mysqli_num_rows($select);
				if($no > 0){
				while ($row = mysqli_fetch_array($select)) {
				$Surgery_Date = $row['Surgery_Date'];
				$duration_of_surgery = $row['duration_of_surgery'];
				$Incision = $row['Incision'];
				$Position = $row['Position'];
				$Type_Of_Anesthetic = $row['Type_Of_Anesthetic'];
				$Post_operative_ID = $row['Post_operative_ID'];
				$cutting_time = $row['cutting_time'];
				$end_cutting_time = $row['end_cutting_time'];
				$consultation_ID = $row['consultation_ID'];
				}
				}else{
				$Surgery_Date = '';
				$Incision = '';
				$Position = '';
				$Type_Of_Anesthetic = '';
				$Post_operative_ID = 0;
				$consultation_ID = 0;
				$duration_of_surgery = '';
				}

				$htm .= "<b><span style='font-size: small;'>".++$Number.": SURGERY DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Name</span></td>
				<td colspan='5'><span style='font-size: small;'>".ucwords(strtolower($Product_Name))."</span></td>
				</tr>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Date</span></td>
				<td><span style='font-size: small;'>".$Surgery_Date."</span></td>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Surgery Duration</span></td>
				<td width='3%'><span style='font-size: small;'>".$duration_of_surgery."</span></td>
				<td width='10%' style='text-align: right;'><span style='font-size: small;'>Incision</span></td>
				<td><span style='font-size: small;'>".$Incision."</span></td>
				</tr>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Position</span></td>
				<td colspan='2'><span style='font-size: small;'>".$Position."</span></td>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Type Of Anesthetic</span></td>
				<td colspan='2'><span style='font-size: small;'>".$Type_Of_Anesthetic."</span></td>
				</tr>
						<tr>
							<td style='text-align:right'><span style='font-size: small;'>Cutting Time</span></td>
							<td colspan='2'><span style='font-size: small;'>$cutting_time</span></td>
							<td style='text-align:right'><span style='font-size: small;'>Ending Cutting Time</span></td>
							<td colspan='2'><span style='font-size: small;'>$end_cutting_time</span></td>
						</tr>
				</table><br/>";

				$select = mysqli_query($conn,"select post_operative_remarks,Procedure_Description, surgery_status, Identification_Of_Prosthesis, Estimated_Blood_loss, Complications,
							Drains, Specimen_sent, Postoperative_Orders from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
				$nop = mysqli_num_rows($select); 
				if($nop > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": COMMENTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
				<td><span style='font-size: small;'>Comments</span></td>
				</tr>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
				$Procedure_Description = $data['Procedure_Description'];
				$Identification_Of_Prosthesis = $data['Identification_Of_Prosthesis'];
				$Estimated_Blood_loss = $data['Estimated_Blood_loss'];
				$Complications = $data['Complications'];
				$Drains = $data['Drains'];
				$Specimen_sent = $data['Specimen_sent'];
				$Postoperative_Orders = $data['Postoperative_Orders'];
				$surgery_status = $data['surgery_status'];
				$post_operative_remarks = $data['post_operative_remarks'];

				$htm .= "<tr><td><span style='font-size: small;'>Surgery Status</span></td>
							<td><span style='font-size: small;'>".$surgery_status."</span></td></tr>";

				//if($Procedure_Description != null && $Procedure_Description != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Procedure Description And Closure</span></td>
							<td><span style='font-size: small;'>".$Procedure_Description."</span></td></tr>";
				//}

				//if($Identification_Of_Prosthesis != null && $Identification_Of_Prosthesis != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Identification Of Prosthesis</span></td>
							<td><span style='font-size: small;'>".$Identification_Of_Prosthesis."</span></td></tr>";
				//}

				//if($Estimated_Blood_loss != null && $Estimated_Blood_loss != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Estimated Blood Loss</span></td>
							<td><span style='font-size: small;'>".$Estimated_Blood_loss."</span></td></tr>";
				//}

				//if($Complications != null && $Complications != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Problems / Complications</span></td>
							<td><span style='font-size: small;'>".$Complications."</span></td></tr>";
				//}

				//if($Drains != null && $Drains != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Drains</span></td>
							<td><span style='font-size: small;'>".$Drains."</span></td></tr>";
				//}

				//if($Specimen_sent != null && $Specimen_sent != ''){
				$htm .= "<tr><td><span style='font-size: small;'>Specimen Taken</span></td>
							<td><span style='font-size: small;'>".$Specimen_sent."</span></td></tr>";
				//}
				}
				//get post operative orders
				$Pharmacy = '';
				$Laboratory = '';
				$Radiology='';
				$Num_Pharmacy = 0;
				$Num_Laboratory = 0;

				$slct = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
				$nm_slct = mysqli_num_rows($slct);
				if($nm_slct > 0){
				while($dt = mysqli_fetch_array($slct)){
					$Payment_Cache_ID = $dt['Payment_Cache_ID'];
				}
				}else{
				$Payment_Cache_ID = 0;
				}
				if($Payment_Cache_ID != 0 && $Payment_Cache_ID != null && $Payment_Cache_ID != ''){
				$select_items = mysqli_query($conn,"select i.Product_Name, ilc.Check_In_Type from tbl_item_list_cache ilc, tbl_items i where
											i.Item_ID = ilc.Item_ID and
											ilc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select_items);
				if($num > 0){
					while($dts = mysqli_fetch_array($select_items)){
						if(strtolower($dts['Check_In_Type']) == 'pharmacy'){
							$Pharmacy .= "<span style='font-size: small;'>".++$Num_Pharmacy.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
						}
						if(strtolower($dts['Check_In_Type']) == 'laboratory'){
							$Laboratory .= "<span style='font-size: small;'>".++$Num_Laboratory.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
						}
						if(strtolower($dts['Check_In_Type']) == 'radiology'){
							$Radiology .= "<span style='font-size: small;'>".++$Num_Laboratory.'<b>.</b>&nbsp;&nbsp;'.$dts['Product_Name'].'</span><br/>';
						}
					}
				}
								if($Pharmacy != '' || $Laboratory != ''||$Radiology !=''){
					$htm .= "<tr><td><span style='font-size: small;'>Postoperative Orders</span></td>
								<td>";
					if($Pharmacy != ''){
						$htm .= "<span style='font-size: small;'>PHARMACEUTICAL</span><br/>".$Pharmacy.'<br/>';
					}
					if($Laboratory != ''){
						$htm .= "<span style='font-size: small;'>INVESTIGATIONS-LABORATORY</span><br/>".$Laboratory;
					}
					if($Radiology != ''){
						$htm .= "<span style='font-size: small;'>INVESTIGATIONS-RADIOLOGY</span><br/>".$Radiology;
					}
					$htm .= "</td></tr>";
				}
				}
								
				$htm .= "</table><br/>";
				}

				$select = mysqli_query($conn,"select  d.disease_code, d.disease_name
							from tbl_post_operative_diagnosis pod, tbl_disease d where
							d.disease_ID = pod.disease_ID and
							pod.Post_operative_ID = '$Post_operative_ID' and
							pod.Diagnosis_Type = 'Preoperative Diagnosis'") or die(mysqli_error($conn));
				$no = mysqli_num_rows($select);
				if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": PREOPERATIVE DIAGNOSIS (INDICATION)</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Disease Code</span></td>
				<td colspan='3'><span style='font-size: small;'>Disease Name</span></td>
				</tr>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
				$htm .=	"<tr>
						<td width='20%' style='text-align: right;'><span style='font-size: small;'>".strtoupper($data['disease_code'])."</span></td>
						<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($data['disease_name']))."</span></td>
					</tr>";
				}
				$htm .= "</table><br/>";
				}


				$select = mysqli_query($conn,"select  d.disease_code, d.disease_name
							from tbl_post_operative_diagnosis pod, tbl_disease d where
							d.disease_ID = pod.disease_ID and
							pod.Post_operative_ID = '$Post_operative_ID' and
							pod.Diagnosis_Type = 'Postoperative Diagnosis'") or die(mysqli_error($conn));
				$no = mysqli_num_rows($select);
				if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": POSTOPERATIVE DIAGNOSIS (FINDINGS)</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: right;'><span style='font-size: small;'>Disease Code</span></td>
				<td colspan='3'><span style='font-size: small;'>Disease Name</span></td>
				</tr>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
				$htm .=	"<tr>
						<td width='20%' style='text-align: right;'><span style='font-size: small;'>".strtoupper($data['disease_code'])."</span></td>
						<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($data['disease_name']))."</span></td>
					</tr>";
				}
				$htm .= "</table><br/>";
				}
				//get participants
				$Surgeons = '';
				$Assisitant_Surgeons = '';
				$Nurses = '';
				$Anaesthetics = '';

				$select = mysqli_query($conn,"select pop.Employee_Type, emp.Employee_Name from 
						tbl_post_operative_participant pop, tbl_employee emp where
						emp.Employee_ID = pop.Employee_ID and
						pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
				$Counter_Surgeon = 0;
				$Counter_Assistant_Surgeon = 0;
				$Counter_Nurse = 0;
				$Counter_Anaesthetics = 0;

				while ($data = mysqli_fetch_array($select)) {
				if($data['Employee_Type'] == 'Surgeon'){
				$Surgeons .= ++$Counter_Surgeon.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
				}else if($data['Employee_Type'] == 'Assistant Surgeon'){
				$Assisitant_Surgeons .= ++$Counter_Assistant_Surgeon.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
				}else if($data['Employee_Type'] == 'Nurse'){
				$Nurses .= ++$Counter_Nurse.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
				}else if($data['Employee_Type'] == 'Anaesthetics'){
				$Anaesthetics .= ++$Counter_Anaesthetics.'. '.ucwords(strtolower($data['Employee_Name'])).'<br/>';
				}
				}

				$htm .= "<b><span style='font-size: small;'>".++$Number.": PARTICIPANTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
				<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
				<td><span style='font-size: small;'>Participant Name</span></td>
				</tr>";
				if($Surgeons != ''){
				$htm .= "<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Surgeons</span></td>
					<td><span style='font-size: small;'>".$Surgeons."</span></td>
				</tr>";
				}
				if($Assisitant_Surgeons != ''){
				$htm .= "<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Assistant Surgeons</span></td>
					<td><span style='font-size: small;'>".$Assisitant_Surgeons."</span></td>
				</tr>";
				}
				if($Nurses != ''){
				$htm .= "<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Nurses</span></td>
					<td><span style='font-size: small;'>".$Nurses."</span></td>
				</tr>";
				}
				if($Anaesthetics != ''){
				$htm .= "<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Anaesthetists</span></td>
					<td><span style='font-size: small;'>".$Anaesthetics."</span></td>
				</tr>";
				}
				$htm .= '</table>';
				}

				//external participants
				$select = mysqli_query($conn,"select * from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
				$External_Surgeons  = $data['External_Surgeons'];
				$External_Assistant_Surgeon = $data['External_Assistant_Surgeon'];
				$External_Scrub_Nurse = $data['External_Scrub_Nurse'];
				$External_Anaesthetic = $data['External_Anaesthetic'];
				}

				if(($External_Surgeons != null && $External_Surgeons != '') || ($External_Assistant_Surgeon != null && $External_Assistant_Surgeon != '') || ($External_Scrub_Nurse != null && $External_Scrub_Nurse != '') || ($External_Anaesthetic != null && $External_Anaesthetic != '')){
				$htm .= "<br/><b><span style='font-size: small;'>".++$Number.": EXTERNAL PARTICIPANTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
					<td><span style='font-size: small;'>Participant Name</span></td>
				</tr>";

				if($External_Surgeons != '' && $External_Surgeons != null){
				$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Surgeons</span></td>
						<td><span style='font-size: small;'>".$External_Surgeons."</span></td>
					</tr>";
				}
				if($External_Assistant_Surgeon != '' && $External_Assistant_Surgeon != null){
				$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Assistant Surgeons</span></td>
						<td><span style='font-size: small;'>".$External_Assistant_Surgeon."</span></td>
					</tr>";
				}
				if($External_Scrub_Nurse != '' && $External_Scrub_Nurse != null){
				$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Nurses</span></td>
						<td><span style='font-size: small;'>".$External_Scrub_Nurse."</span></td>
					</tr>";
				}
				if($External_Anaesthetic != '' && $External_Anaesthetic != null){
				$htm .= "<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>External Anaesthetists</span></td>
						<td><span style='font-size: small;'>".$External_Anaesthetic."</span></td>
					</tr>";
				}
				$htm .= '</table>';
				}
				}
				$htm .="<br/><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr><td><b>".++$Number.". POST OPERATIVE REMARKS</b></td></tr>
					<tr><td>$post_operative_remarks</td></tr>
				</table><br/>";			
	}
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
