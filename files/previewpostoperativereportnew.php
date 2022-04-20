<?php
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

//	if(isset($_GET['Registration_ID'])){
//		$Registration_ID = $_GET['Registration_ID'];
//	}else{
//		$Registration_ID = 0;
//	}
//
//	if(isset($_GET['Patient_Payment_ID'])){
//		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
//	}else{
//		$Patient_Payment_ID = 0;
//	}
//
//	if(isset($_GET['Patient_Payment_Item_List_ID'])){
//		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
//	}else{
//		$Patient_Payment_Item_List_ID = 0;
//	}
//
//	if(isset($_GET['Payment_Item_Cache_List_ID'])){
//		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
//	}else{
//		$Payment_Item_Cache_List_ID = 0;
//	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
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


    $htm = "<table width ='100%' class='table table-striped table-bordered'>
		<tr>
		    <td style='text-align: center;'><b>POST OPERATIVE NOTES</b></td>
		</tr></table><br/>"; // border=1 style='border-collapse: collapse;'

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
			$select = mysqli_query($conn,"select pos.Surgery_Date, pos.Incision, pos.Position, pos.Type_Of_Anesthetic, pos.Post_operative_ID, pos.consultation_ID
									from tbl_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i where
									pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
									ilc.Item_ID - i.Item_ID and
									ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				while ($row = mysqli_fetch_array($select)) {
					$Surgery_Date = $row['Surgery_Date'];
					$Incision = $row['Incision'];
					$Position = $row['Position'];
					$Type_Of_Anesthetic = $row['Type_Of_Anesthetic'];
					$Post_operative_ID = $row['Post_operative_ID'];
					$consultation_ID = $row['consultation_ID'];
				}
			}else{
				$Surgery_Date = '';
				$Incision = '';
				$Position = '';
				$Type_Of_Anesthetic = '';
				$Post_operative_ID = 0;
				$consultation_ID = 0;
			}

			$htm .= "<b><span style='font-size: small;'>".++$Number.": SURGERY DETAILS</span></b><table class='table table-striped table-bordered'>
				<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Surgery Name</b></span></td>
					<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($Product_Name))."</span></td>
				</tr>
				<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Surgery Date</b></span></td>
					<td><span style='font-size: small;'>".$Surgery_Date."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Incision</b></span></td>
					<td><span style='font-size: small;'>".$Incision."</span></td>
				</tr>
				<tr>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Position</b></span></td>
					<td><span style='font-size: small;'>".$Position."</span></td>
					<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Type Of Anesthetic<b></span></td>
					<td><span style='font-size: small;'>".$Type_Of_Anesthetic."</span></td>
				</tr>
			</table><br/>";

			$select = mysqli_query($conn,"select Procedure_Description, Identification_Of_Prosthesis, Estimated_Blood_loss, Complications,
									Drains, Specimen_sent, Postoperative_Orders from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
			$nop = mysqli_num_rows($select); 
			if($nop > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": COMMENTS</span></b><table class='table table-striped table-bordered' width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Title Name</b></span></td>
						<td><span style='font-size: small;'><b>Comments</b></span></td>
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

					//if($Procedure_Description != null && $Procedure_Description != ''){
						$htm .= "<tr><td><span style='font-size: small;'>P<b>rocedure Description And Closure</b></span></td>
									<td><span style='font-size: small;'>".$Procedure_Description."</span></td></tr>";
					//}

					//if($Identification_Of_Prosthesis != null && $Identification_Of_Prosthesis != ''){
						$htm .= "<tr><td><span style='font-size: small;'><b>Identification Of Prosthesis</b></span></td>
									<td><span style='font-size: small;'>".$Identification_Of_Prosthesis."</span></td></tr>";
					//}
					
					//if($Estimated_Blood_loss != null && $Estimated_Blood_loss != ''){
						$htm .= "<tr><td><span style='font-size: small;'><b>Estimated Blood Loss</b></span></td>
									<td><span style='font-size: small;'>".$Estimated_Blood_loss."</span></td></tr>";
					//}
					
					//if($Complications != null && $Complications != ''){
						$htm .= "<tr><td><span style='font-size: small;'><b>Problems / Complications</b></span></td>
									<td><span style='font-size: small;'>".$Complications."</span></td></tr>";
					//}
					
					//if($Drains != null && $Drains != ''){
						$htm .= "<tr><td><span style='font-size: small;'><b>Drains</b></span></td>
									<td><span style='font-size: small;'>".$Drains."</span></td></tr>";
					//}
					
					//if($Specimen_sent != null && $Specimen_sent != ''){
						$htm .= "<tr><td><span style='font-size: small;'><b>Specimen Sent</b></span></td>
									<td><span style='font-size: small;'>".$Specimen_sent."</span></td></tr>";
					//}
				}
					//get post operative orders
					$Pharmacy = '';
					$Laboratory = '';
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
							}
						}
						if($Pharmacy != '' || $Laboratory != ''){
							$htm .= "<tr><td><span style='font-size: small;'><b>Postoperative Orders</b></span></td>
										<td>";
							if($Pharmacy != ''){
								$htm .= "<span style='font-size: small;'><b>PHARMACEUTICAL</b></span><br/>".$Pharmacy.'<br/>';
							}
							if($Laboratory != ''){
								$htm .= "<span style='font-size: small;'><b>INVESTIGATIONS</b></span><br/>".$Laboratory;
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
				$htm .= "<b><span style='font-size: small;'>".++$Number.": PREOPERATIVE DIAGNOSIS (INDICATION)</span></b><table class='table table-striped table-bordered' width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Disease Code</b></span></td>
						<td colspan='3'><span style='font-size: small;'><b>Disease Name</b></span></td>
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
				$htm .= "<b><span style='font-size: small;'>".++$Number.": POSTOPERATIVE DIAGNOSIS (FINDINGS)</span></b><table class='table table-striped table-bordered' width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: right;'><span style='font-size: small;'><b>Disease Code</b></span></td>
						<td colspan='3'><span style='font-size: small;'><b>Disease Name</b></span></td>
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

			$htm .= "<b><span style='font-size: small;'>".++$Number.": PARTICIPANTS</span></b><table class='table table-striped table-bordered' width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
					<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Title Name</b></span></td>
					<td><span style='font-size: small;'><b>Participant Name</b></span></td>
				</tr>";
			if($Surgeons != ''){
				$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Surgeons</b></span></td>
							<td><span style='font-size: small;'>".$Surgeons."</span></td>
						</tr>";
			}
			if($Assisitant_Surgeons != ''){
				$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Assistant Surgeons</b></span></td>
							<td><span style='font-size: small;'>".$Assisitant_Surgeons."</span></td>
						</tr>";
			}
			if($Nurses != ''){
				$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Nurses</b></span></td>
							<td><span style='font-size: small;'>".$Nurses."</span></td>
						</tr>";
			}
			if($Anaesthetics != ''){
				$htm .= "<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Anaesthetists</b></span></td>
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
				$htm .= "<br/><b><span style='font-size: small;'>".++$Number.": EXTERNAL PARTICIPANTS</span></b><table class='table table-striped table-bordered' width='100%' border=1 style='border-collapse: collapse;'>
						<tr>
							<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Title Name</b></span></td>
							<td><span style='font-size: small;'><b>Participant Name</b></span></td>
						</tr>";

				if($External_Surgeons != '' && $External_Surgeons != null){
					$htm .= "<tr>
								<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>External Surgeons</b></span></td>
								<td><span style='font-size: small;'>".$External_Surgeons."</span></td>
							</tr>";
				}
				if($External_Assistant_Surgeon != '' && $External_Assistant_Surgeon != null){
					$htm .= "<tr>
								<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>External Assistant Surgeons</b></span></td>
								<td><span style='font-size: small;'>".$External_Assistant_Surgeon."</span></td>
							</tr>";
				}
				if($External_Scrub_Nurse != '' && $External_Scrub_Nurse != null){
					$htm .= "<tr>
								<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>External Nurses</b></span></td>
								<td><span style='font-size: small;'>".$External_Scrub_Nurse."</span></td>
							</tr>";
				}
				if($External_Anaesthetic != '' && $External_Anaesthetic != null){
					$htm .= "<tr>
								<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>External Anaesthetists</b></span></td>
								<td><span style='font-size: small;'>".$External_Anaesthetic."</span></td>
							</tr>";
				}
				$htm .= '</table>';
			}
		}
echo $htm;

?>
