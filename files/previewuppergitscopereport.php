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
		    <td style='text-align: center;'><b>UPPER GI  ENDOSCOPY REPORT</b></td>
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
			$select = mysqli_query($conn,"select pos.Employee_ID,pos.Surgery_Date,pos.summary_of_assessment_bfr_procedure, pos.Indication_Of_Procedure,pos.Others,pos.Comorbidities,ilc.Doctor_Comment, pos.Management, pos.Recommendations, pos.Medication_Used, pos.Biopsy_Tailen, pos.Git_Post_operative_ID
									from tbl_git_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i where
									
									pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
									ilc.Item_ID = i.Item_ID and
									ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				while ($row = mysqli_fetch_array($select)) {
					$Surgery_Date = $row['Surgery_Date'];
					$Indication_Of_Procedure = $row['Indication_Of_Procedure'];
					$Medication_Used = $row['Medication_Used'];
					$Comorbidities = $row['Comorbidities'];
					$Git_Post_operative_ID = $row['Git_Post_operative_ID'];
					$Recommendations = $row['Recommendations'];
					$Management = $row['Management'];
					$Comorbidities = $row['Comorbidities'];
					$Biopsy_Tailen = $row['Biopsy_Tailen'];
					$Employee_ID = $row['Employee_ID'];
                                        
                                        $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
					$Others=$row['Others'];
					$Doctor_Comment=$row['Doctor_Comment'];
					$summary_of_assessment_bfr_procedure=$row['summary_of_assessment_bfr_procedure'];
				}
			}else{
				$Surgery_Date = '';
				$Indication_Of_Procedure = '';
				$Medication_Used = '';
				$Comorbidities = '';
				$Git_Post_operative_ID = 0;
				$Recommendations = '';
				$Management  = '';
				$Comorbidities = '';
				$Biopsy_Tailen = '';
				$Employee_Name = '';
				$Others='';
				$Doctor_Comment='';
                                $summary_of_assessment_bfr_procedure='';
			}
			
			$htm .= "<b><span style='font-size: small;'>".++$Number.": PROCEDURE DETAILS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
					<td width='30%' style='text-align: left;'><span style='font-size: small;'>Procedure Name</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".ucwords(strtolower($Product_Name))."</i></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Procedure Date</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".$Surgery_Date."</i></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Doctor Name</span></td>
					<td colspan='3'><span style='font-size: small;'><b><i>".strtoupper($Employee_Name)."</i></b></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Indication Of Procedure</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".$Indication_Of_Procedure."  i.e ".$Others."</i></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Biopsy Taken</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".$Biopsy_Tailen."</i></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Medication Used</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".$Medication_Used."</i></span></td>
				</tr>
				<tr>
					<td style='text-align: left;'><span style='font-size: small;'>Comorbidities</span></td>
					<td colspan='3'><span style='font-size: small;'><i>".$Comorbidities."</i></span></td>
				</tr>
			</table><br/>";
                        $htm .= " <b><span style='font-size: small;'>".++$Number.":  RELEVANT CLINICAL DATA </span></b>
                                        <br/>
                                        <span><i>$Doctor_Comment</i></span>
                                   <br/>
                                   <br/> 
                                 <b><span style='font-size: small;'>".++$Number.":  PROVISIONAL DIAGNOSIS</span></b> <br/> 
                                ";
                        ////////////////////////////////////////////////////////////
                             $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID,ilc.Doctor_Comment from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                            $no = mysqli_num_rows($select);
                            if($no > 0){
                                while ($data = mysqli_fetch_array($select)) {
                                    $consultation_id = $data['consultation_id'];
                                    $consultation_id_to_use=$consultation_id;
                                }
                            }else{
                                $Payment_Cache_ID = 0;
                                $consultation_id = 0;
                            }
                                $provisional_diagnosis="";
                                $sql_select_provisional_diagnosis_result=mysqli_query($conn,"SELECT disease_code,disease_name FROM tbl_disease d INNER JOIN tbl_disease_consultation dc ON d.disease_ID=dc.disease_ID WHERE dc.consultation_ID='$consultation_id_to_use'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_provisional_diagnosis_result)>0){
                                    while($provisional_diagn_rows=mysqli_fetch_assoc($sql_select_provisional_diagnosis_result)){
                                       $disease_code=$provisional_diagn_rows['disease_code'];
                                       $disease_name=$provisional_diagn_rows['disease_name'];
                                       $provisional_diagnosis.="$disease_name  <b>($disease_code)</b>,";
                                    }
                                }
                        $htm .="<i>".$provisional_diagnosis."</i><br/><br/>";
                        ///////////////////////////////////////
                        
                         $htm .= " <b><span style='font-size: small;'>".++$Number.":  SUMMARY OF ASSESSMENT BEFORE THE PROCEDURE </span></b>
                                        <br/>
                                        <span><i>$summary_of_assessment_bfr_procedure</i></span>
                                   <br/>
                                   <br/>
                                ";
                        
                        //////////////////////////
			if($Indication_Of_Procedure=='Others' && $Others!=''){
				$other_Val=$Indication_Of_Procedure.' i.e '.$Others;
			}else{
				//$other_Val=$Indication_Of_Procedure;
                                $other_Val=$Indication_Of_Procedure.' i.e '.$Others;
				
			}


			$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name, pod.Employee_Type from tbl_git_post_operative_participant pod, tbl_employee emp where
									pod.Employee_ID = emp.Employee_ID and pod.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			
			$Endos = '';
			$Endos_No = 0;
			$Anaes = '';
			$Anaes_No = 0;
			if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": PARTICIPANTS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>";
				while ($dt = mysqli_fetch_array($select)) {
					if($dt['Employee_Type'] == 'Endoswrist'){
						$Endos .= ++$Endos_No.'<b>.</b> '.ucwords(strtolower($dt['Employee_Name'])).'<br/>';
					}else if($dt['Employee_Type'] == 'Anaesthesia'){
						$Anaes .= ++$Anaes_No.'<b>.</b> '.ucwords(strtolower($dt['Employee_Name'])).'<br/>';						
					}
				}
				if($Endos != 0){
					$htm .= "<tr>
								<td width='20%' style='text-align: left; valign: top;'><span style='font-size: small;'>Endoscopist</span></td>
								<td colspan='3'><span style='font-size: small;'><i>".$Endos."</i></span></td>
							</tr>";
				}

				if($Endos != 0){
					$htm .= "<tr>
								<td width='20%' style='text-align: left; valign: top;'><span style='font-size: small;'>Anaesthesia</span></td>
								<td colspan='3'><span style='font-size: small;'><i>".$Anaes."</i></span></td>
							</tr>";
				}
				$htm .= "</table><br/>";
			}
			 
			$select = mysqli_query($conn,"select upper_git_normal,Upper_Point, OG_Junction, Hiatus_Hernia,Middle_Point, Other_Lesson, Cardia, Fundus, Body, Antrum, Pyloms, D1, D2, D3
							from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
                               
				$htm .= "<b><span style='font-size: small;'>".++$Number.": EXAMINATION FINDINGS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<thead><tr>
						<td width='30%' style='text-align: left;'><span style='font-size: small;'><b>Title</b></span></td>
						<td><span style='font-size: small;'><b>Finding</b></span></td>
					</tr></thead>";
				while ($data2 = mysqli_fetch_array($select)) {
				    if($data2['Upper_Point'] != null && $data2['Upper_Point'] != ''){
                                        $htm .="<tr><td colspan='2' style='border-left:1px solid white;border-right:1px solid white;'><b>UPPER GIT NORMAL</b></td> </tr>";
				        $htm .= "<tr><td><span style='font-size: small;'>Normal</span></td><td><span style='font-size: small;'>".$data2['upper_git_normal']."</span></td></tr>";
                                        $htm .="<tr><td colspan='2' style='border-left:1px solid white;border-right:1px solid white;'><b>OESOPHAGUS</b></td> </tr>";
                                        $htm .= "<tr><td><span style='font-size: small;'>Upper Part</span></td><td><span style='font-size: small;'>".$data2['Upper_Point']."</span></td></tr>";
				    }
					
					  if($data2['Upper_Point'] != null && $data2['Upper_Point'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Middle Part</span></td><td><span style='font-size: small;'>".$data2['Middle_Point']."</span></td></tr>";
				    }
					
				    if($data2['OG_Junction'] != null && $data2['OG_Junction'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>OG Junction</span></td><td><span style='font-size: small;'>".$data2['OG_Junction']."</span></td></tr>";
				    }
				    if($data2['Hiatus_Hernia'] != null && $data2['Hiatus_Hernia'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Hiatus Hernia</span></td><td><span style='font-size: small;'>".$data2['Hiatus_Hernia']."</span></td></tr>";
				    }
				    if($data2['Other_Lesson'] != null && $data2['Other_Lesson'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Other Lesion</span></td><td><span style='font-size: small;'>".$data2['Other_Lesson']."</span></td></tr>";
				    }
                                   
                                        
                                        $htm .="<tr><td colspan='2' style='border-left:1px solid white;border-right:1px solid white;'><b>STOMACH</b></td> </tr>";
                                    
                                    
				    if($data2['Cardia'] != null && $data2['Cardia'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Cardia</span></td><td><span style='font-size: small;'>".$data2['Cardia']."</span></td></tr>";
				    }
				    if($data2['Fundus'] != null && $data2['Fundus'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Fundus</span></b></td><td><span style='font-size: small;'>".$data2['Fundus']."</span></td></tr>";
				    }
				    if($data2['Body'] != null && $data2['Body'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Body</span></td><td><span style='font-size: small;'>".$data2['Body']."</span></td></tr>";
				    }
				    if($data2['Antrum'] != null && $data2['Antrum'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Antrum</span></td><td><span style='font-size: small;'>".$data2['Antrum']."</span></td></tr>";
				    }
				    if($data2['Pyloms'] != null && $data2['Pyloms'] != ''){
				        $htm .= "<tr><td><span style='font-size: small;'>Pylorus</span></td><td><span style='font-size: small;'>".$data2['Pyloms']."</span></td></tr>";
				       
                                        $htm .="<tr><td colspan='2' style='border-left:1px solid white;border-right:1px solid white;'><b>DUODENUM</b></td> </tr>";
                                        
                                        $htm .= "<tr><td><span style='font-size: small;'>Duodenum - D1</span></td><td><span style='font-size: small;'>".$data2['D1']."</span></td></tr>";
				        $htm .= "<tr><td><span style='font-size: small;'>Duodenum - D2</span></td><td><span style='font-size: small;'>".$data2['D2']."</span></td></tr>";
				        $htm .= "<tr><td><span style='font-size: small;'>Duodenum - D3</span></td><td><span style='font-size: small;'>".$data2['D3']."</span></td></tr>";
				    }
				}
				$htm .= "</table><br/>";
			}


			 
            $select = mysqli_query($conn,"select d.procedure_dignosis_code, d.procedure_dignosis_name, po.Diagnosis_ID 
										from tbl_procedure_diagnosis d, tbl_gti_post_operative_diagnosis po where
										d.procedure_diagnosis_id = po.procedure_diagnosis_id and
										po.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				$htm .= "<b><span style='font-size: small;'>".++$Number.": DIAGNOSIS</span></b><table width='100%' border=1 style='border-collapse: collapse;'>
					<thead><tr>
						<td width='20%' style='text-align: left;'><span style='font-size: small;'>Diagnosis Code</span></td>
						<td colspan='3'><span style='font-size: small;'>Diagnosis Name</span></td>
					</tr></thead>";
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
					$htm .=	"<tr>
								<td width='20%' style='text-align: left;'><span style='font-size: small;'>".strtoupper($data['procedure_dignosis_code'])."</span></td>
								<td colspan='3'><span style='font-size: small;'>".ucwords(strtolower($data['procedure_dignosis_name']))."</span></td>
							</tr>";
				}
				$htm .= "</table><br/>";
			}


			//MANAGEMENT RECOMMENDATION
			$htm .= "<b><span style='font-size: small;'>".++$Number.": ENDOSCOPIC PROCEDURES</span></b>
					 <br/>
						 <i><span style='font-size: small;'>".$Recommendations."</span></i>
					 ";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 12,15,20,35,10,30, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
