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
			$select = mysqli_query($conn,"select pos.Surgery_Date, pos.Payment_Item_Cache_List_ID, pos.Indication, pos.vocal_cords, pos.Trachea, pos.Carina, pos.Rt_Bronchial_tree, pos.Rt_UL_Bronchus, pos.Rt_ML_Bronchus, pos.Rt_LL_Bronchus, pos.Lt_Bronchial_tree, pos.Lt_UL_Bronchus, pos.Lt_LL_Bronchus, pos.Liangula, pos.Biopsy, pos.Impression, pos.Bal, pos.Premedication, pos.Comments, i.Product_Name, pos.Bronchoscopy_Notes_ID, emp.Employee_Name
									from tbl_Bronchoscopy_notes pos, tbl_item_list_cache ilc, tbl_items i, tbl_employee emp where
									emp.Employee_ID = pos.Employee_ID and
									pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
									ilc.Item_ID - i.Item_ID and
									ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				while ($row = mysqli_fetch_array($select)) {
					$Bronchoscopy_Notes_ID = $row['Bronchoscopy_Notes_ID'];
                    $Indication = $row['Indication'];
                    $Premedication = $row['Premedication'];
                    $Surgery_Date = $row['Surgery_Date'];
                    $Employee_Name = $row['Employee_Name'];
                }
			}else{
				    $Bronchoscopy_Notes_ID = 0;
                    $Surgery_Date = '';
                    $Indication = '';
                    $Premedication = '';
                    $Surgery_Date = '';
					$Employee_Name = '';
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
					<td colspan='3'><span style='font-size: small;'>".$Indication."</span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Premedications</span></td>
					<td colspan='3'><span style='font-size: small;'>".$Premedication."</span></td>
				</tr>
			</table><br/>";



			$select = mysqli_query($conn,"select Indication, vocal_cords, Trachea, Carina, Rt_Bronchial_tree, Rt_UL_Bronchus, Rt_ML_Bronchus, Rt_LL_Bronchus, Lt_Bronchial_tree, Lt_UL_Bronchus, Lt_LL_Bronchus, Liangula, Biopsy, Impression, Bal, Premedication, Comments
            from tbl_Bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select); 
			if($no > 0){
				$htm .= "<b><span style='font-size: small; text-align: center;'>".++$Number.": EXAMINATION FINDINGS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'><b>Title Name</b></span></td>
						<td><span style='font-size: small; text-align: center;'><b>Findings</b></span></td>
					</tr>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
					$vocal_cords = $data['vocal_cords'];
                    $Trachea = $data['Trachea'];
                    $Carina = $data['Carina'];
                    $Rt_Bronchial_tree = $data['Rt_Bronchial_tree'];
                    $Rt_UL_Bronchus = $data['Rt_UL_Bronchus'];
                    $Rt_ML_Bronchus = $data['Rt_ML_Bronchus'];
                    $Rt_LL_Bronchus = $data['Rt_LL_Bronchus'];
                    $Lt_Bronchial_tree = $data['Lt_Bronchial_tree'];
                    $Lt_UL_Bronchus = $data['Lt_UL_Bronchus'];
                    $Lt_LL_Bronchus = $data['Lt_LL_Bronchus'];
                    $Liangula = $data['Liangula'];
                    $Impression = $data['Impression'];
                    $Biopsy = $data['Biopsy'];
                    $Bal = $data['Bal'];
                    $Comments = $data['Comments'];

						$htm .= "<tr><td><span style='font-size: small;'>Vocal Cords</span></td><td><span style='font-size: small;'>".$vocal_cords."</span></td></tr>";
					//}

					//if($PR != null && $PR != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Trachea</span></td><td><span style='font-size: small;'>".$Trachea."</span></td></tr>";
					//}
					
					
					//if($Haemoral != null && $Haemoral != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Carina</span></td><td><span style='font-size: small;'>".$Carina."</span></td></tr>";
					//}
					
					//if($rectum != null && $rectum != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Rt Bronchial Tree</span></td><td><span style='font-size: small;'>".$Rt_Bronchial_tree."</span></td></tr>";
					//}
					
					
					//if($Symd != null && $Symd != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Rt UL Bronchus</span></td><td><span style='font-size: small;'>".$Rt_UL_Bronchus."</span></td></tr>";
					//}

					
					//if($Dex_colon != null && $Dex_colon != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Rt ML Bronchus</span></td><td><span style='font-size: small;'>".$Rt_ML_Bronchus."</span></td></tr>";
					//}

					
					//if($Splenic != null && $Splenic != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Rt LL Bronchus</span></td><td><span style='font-size: small;'>".$Rt_LL_Bronchus."</span></td></tr>";
					//}
						
						//if($Ple_Tran_Col != null && $Ple_Tran_Col != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Lt Bronchial Trees</span></td><td><span style='font-size: small;'>".$Lt_Bronchial_tree."</span></td></tr>";
					//}
					
					
					//if($Hepatic_Flexure != null && $Hepatic_Flexure != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Lt UL Bronchus</span></td><td><span style='font-size: small;'>".$Lt_UL_Bronchus."</span></td></tr>";
					//}
						
						
								
					//if($Ascending_Colon != null && $Ascending_Colon != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Lt ll Bronchus</span></td><td><span style='font-size: small;'>".$Lt_LL_Bronchus."</span></td></tr>";
					//}
					
					
						
					//if($Caecum != null && $Caecum != ''){
						$htm .= "<tr><td><span style='font-size: small;'>Liangula</span></td><td><span style='font-size: small;'>".$Liangula."</span></td></tr>";
					//}

			
					//if($Terminal_Ileum != null && $Terminal_Ileum != ''){
                        $htm .= "<tr><td><span style='font-size: small;'>Impression</span></td><td><span style='font-size: small;'>".$Impression."</span></td></tr>";
                        

                        $htm .= "<tr><td><span style='font-size: small;'>Biopsy</span></td><td><span style='font-size: small;'>".$Biopsy."</span></td></tr>";
					//}
				}
				$htm .= "</table><br/>";
			}

				$htm .= "</table><br/>";

			//MANAGEMENT RECOMMENDATION
			$htm .= "<b><span style='font-size: small;'>".++$Number.": PROCEDURE COMMENT(s)</span></b>
						<table width='100%' border=1 style='border-collapse: collapse;'>
							<tr><td colspan='3'><span style='font-size: small;'>".$Comments."</span></td></tr>
						</table>";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,35,15,30, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
