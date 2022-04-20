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
		$Emp_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Emp_Name = '';
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
		$age ='';
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


    $htm = "<table width ='100%'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		    <td style='text-align: center;'><b>BRONCHOSCOPY REPORT</b></td>
		</tr></table><br/>"; // border=1 style='border-collapse: collapse;'

	$htm .= "<b><span style='font-size: small;'>".++$Number.": PATIENT DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Patient Name</span></td>
					<td><span style='font-size: small;'><b>".ucwords(strtolower($Patient_Name))."</b></span></td>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Patient #</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Registration_ID))."</span></td>
				</tr>
				<tr>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Gender</span></td>
					<td><span style='font-size: small;'>".ucwords(strtolower($Gender))."</span></td>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Age</span></td>
					<td><span style='font-size: small;'>".$age."</span></td>
				</tr>
				<tr>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Sponsor Name</span></td>
					<td><span style='font-size: small;'>".strtoupper($Guarantor_Name)."</span></td>
					<td width='17%' style='text-align: right;'><span style='font-size: small;'>Member #</span></td>
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
			$select = mysqli_query($conn,"select pos.Surgery_Date, pos.Indication_Of_Procedure,pos.Others,pos.Quality_Of_Bowel, pos.Adverse_Event_Resulting, i.Product_Name, pos.Ogd_Post_operative_ID,
									pos.Endoscorpic_Internvention, pos.Type_And_Dose, Management_Recommendation, Commobility, Extent_Of_Examination, emp.Employee_Name
									from tbl_ogd_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i, tbl_employee emp where
									emp.Employee_ID = pos.Employee_ID and
									pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
									ilc.Item_ID - i.Item_ID and
									ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				while ($row = mysqli_fetch_array($select)) {
					$Surgery_Date = $row['Surgery_Date'];
					$Indication_Of_Procedure = $row['Indication_Of_Procedure'];
					$Quality_Of_Bowel = $row['Quality_Of_Bowel'];
					$Adverse_Event_Resulting = $row['Adverse_Event_Resulting'];
					$Ogd_Post_operative_ID = $row['Ogd_Post_operative_ID'];
					$Endoscorpic_Internvention = $row['Endoscorpic_Internvention'];
					$Type_And_Dose = $row['Type_And_Dose'];
					$Management_Recommendation = $row['Management_Recommendation'];
					$Commobility = $row['Commobility'];
					$Extent_Of_Examination = $row['Extent_Of_Examination'];
					$Employee_Name = $row['Employee_Name'];
					$Others=$row['Others'];
				}
			}else{
				$Surgery_Date = '';
				$Indication_Of_Procedure = '';
				$Quality_Of_Bowel = '';
				$Adverse_Event_Resulting = '';
				$Ogd_Post_operative_ID = 0;
				$Endoscorpic_Internvention = '';
				$Type_And_Dose = '';
				$Management_Recommendation = '';
				$Commobility = '';
				$Extent_Of_Examination = '';
				$Employee_Name = '';
				$Others='';
			}

			if($Indication_Of_Procedure=='Others' && $Others!=''){
				$other_Val=$Indication_Of_Procedure.' i.e '.$Others;
			}else{
				$other_Val=$Indication_Of_Procedure;
				
			}
			$htm .= "<b><span style='font-size: small;'>".++$Number.": PROCEDURE DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
					<td width='30%' style='text-align: left;'><span style='font-size: small;'>Procedure Name</span></td>
					<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($Product_Name))."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Procedure Date</span></td>
					<td colspan='3'><span style='font-size: small;'>".@date("d F Y",strtotime($Surgery_Date))."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Doctor Name</span></td>
					<td colspan='3'><span style='font-size: small;'><b>".strtoupper($Employee_Name)."</b></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Indication Of Procedure</span></td>
					<td colspan='3'><span style='font-size: small;'>".$other_Val."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Quality Of Bowel Preparation</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Quality_Of_Bowel."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Endoscorpic Internvention Done</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Endoscorpic_Internvention."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Type And Dose Of Sedation</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Type_And_Dose."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Adverse Event Resulting Intervention</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Adverse_Event_Resulting."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Comorbidity</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Commobility."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Extent Of Examination</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Extent_Of_Examination."</span></td>
				</tr>
			</table><br/>";



			$select = mysqli_query($conn,"select Anal_lessor, Haemoral, PR,rectum,Symd, Dex_colon, Splenic, Ple_Tran_Col, Hepatic_Flexure, Ascending_Colon, Caecum, Terminal_Ileum
									from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select); 
			if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": EXAMINATION FINDINGS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Title Name</span></td>
						<td><span style='font-size: small;'>Finding</span></td>
					</tr>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
					$Anal_lessor = $data['Anal_lessor'];
					$Haemoral = $data['Haemoral'];
					$PR = $data['PR'];
					$Symd = $data['Symd'];
					$Ple_Tran_Col = $data['Ple_Tran_Col'];
					$Dex_colon = $data['Dex_colon'];
					$Splenic = $data['Splenic'];
					$Hepatic_Flexure = $data['Hepatic_Flexure'];
					$Ascending_Colon = $data['Ascending_Colon'];
					$Caecum = $data['Caecum'];
					$Terminal_Ileum = $data['Terminal_Ileum'];
					$rectum=$data['rectum'];
					//if($Anal_lessor != null && $Anal_lessor != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Anal Lesions</span></td><td><span style='font-size: small;'>".$Anal_lessor."</span></td></tr>";
					//}

					//if($PR != null && $PR != ''){
						$htm .= "<tr><td><span style='font-size: small;'>PR</span></td><td><span style='font-size: small;'>".$PR."</span></td></tr>";
					//}
					
					
					//if($Haemoral != null && $Haemoral != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Haemorroids</span></td><td><span style='font-size: small;'>".$Haemoral."</span></td></tr>";
					//}
					
					//if($rectum != null && $rectum != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Rectum</span></td><td><span style='font-size: small;'>".$rectum."</span></td></tr>";
					//}
					
					
					//if($Symd != null && $Symd != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Sigmoid Colon</span></td><td><span style='font-size: small;'>".$Symd."</span></td></tr>";
					//}

					
					//if($Dex_colon != null && $Dex_colon != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Descending Colon</span></td><td><span style='font-size: small;'>".$Dex_colon."</span></td></tr>";
					//}

					
					//if($Splenic != null && $Splenic != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Splenic Flexure</span></td><td><span style='font-size: small;'>".$Splenic."</span></td></tr>";
					//}
						
						//if($Ple_Tran_Col != null && $Ple_Tran_Col != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Transverse Colon</span></td><td><span style='font-size: small;'>".$Ple_Tran_Col."</span></td></tr>";
					//}
					
					
					//if($Hepatic_Flexure != null && $Hepatic_Flexure != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Hepatic Flexure</span></td><td><span style='font-size: small;'>".$Hepatic_Flexure."</span></td></tr>";
					//}
						
						
								
					//if($Ascending_Colon != null && $Ascending_Colon != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Ascending Colon</span></td><td><span style='font-size: small;'>".$Ascending_Colon."</span></td></tr>";
					//}
					
					
						
					//if($Caecum != null && $Caecum != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Caecum</span></td><td><span style='font-size: small;'>".$Caecum."</span></td></tr>";
					//}

			
					//if($Terminal_Ileum != null && $Terminal_Ileum != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Terminal Ileum</span></td><td><span style='font-size: small;'>".$Terminal_Ileum."</span></td></tr>";
					//}
				}
				$htm .= "</table><br/>";
			}


			$select = mysqli_query($conn,"select  d.disease_code, d.disease_name
									from tbl_ogd_post_operative_diagnosis pod, tbl_disease d where
									d.disease_ID = pod.disease_ID and
									pod.Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": DIAGNOSIS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<thead><tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Disease Code</span></td>
						<td><span style='font-size: small;'>Disease Name</span></td>
					</tr></thead>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
					$htm .=	"<tr>
								<td style='text-align: left;'><span style='font-size: small;'>".strtoupper($data['disease_code'])."</span></td>
								<td><span style='font-size: small;'>".ucwords(strtolower($data['disease_name']))."</span></td>
							</tr>";
				}
				$htm .= "</table><br/>";
			}

			//MANAGEMENT RECOMMENDATION
			$htm .= "<b><span style='font-size: small;'>".++$Number.": MANAGEMENT RECOMMENDATION</span></b>
						<table width='100%' border=1 style='border-collapse: collapse;'>
							<tr><td colspan='3'><span style='font-size: small;'>".$Management_Recommendation."</span></td></tr>
						</table>";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,35,15,30, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
