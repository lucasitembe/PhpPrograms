
<?php
	require_once('includes/connection.php');
	if(isset($_GET['Registration_ID'])) { $Registration_ID = $_GET['Registration_ID']; } else { $Registration_ID = 0 ;}
	if(isset($_GET['Date_From'])) { $Date_From = $_GET['Date_From']; } else { $DateFrom = ''; }
	if(isset($_GET['Date_To'])) { $Date_To = $_GET['Date_To']; } else { $DateTo = ''; }
        if(isset($_GET['consultation_ID'])) { $consultation_ID = $_GET['consultation_ID']; } else { $consultation_ID = ''; }
//	
	$apID = 0;
	$filter=" AND DATE(rpt.Date_Time) = DATE(NOW())    AND pc.consultation_ID='".$consultation_ID."'";
        
        
        if(isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)){
          $filter="  AND rpt.Date_Time BETWEEN '". $Date_From."' AND '".$Date_To."'  AND pc.consultation_ID='".$consultation_ID."'";
        }
	
	//SELECTING PATIENTS LIST
	$select_patients = "
		SELECT rpt.Item_ID,rpt.Registration_ID,Date_Time,Radiologist_ID,Sonographer_ID,Product_Name,rpt.Patient_Payment_Item_List_ID,rpt.Remarks 			
			FROM
			tbl_radiology_patient_tests rpt, 
			tbl_items i,
                        tbl_consultation tc,
			tbl_payment_cache pc,
			tbl_item_list_cache tlc
				WHERE
				rpt.Item_ID = i.Item_ID AND
                                tlc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND
				tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
				tc.consultation_ID= pc.consultation_id AND
				pc.Registration_ID = '$Registration_ID' AND
				rpt.Status = 'done' $filter";
        
        //die($select_patients);

	
	echo '<table width="100%">';
	echo '<tr style="text-transform:uppercase; font-weight:bold;" id="thead">';	
		echo '<td style="width:3%;">SN</td>';	
		echo '<td>Test Name</td>';	
		echo '<td>Doctor Comment</td>';	
		echo '<td>Remarks</td>';	
		echo '<td>Radiologist</td>';	
		echo '<td>Sonographer</td>';	
		echo '<td>Consultation Date</td>';
        echo '<td>Results</td>';	
	echo '</tr>';	
	
	$select_patients_qry = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
	$sn = 1;
	
	$clinickNote="<a href='doctorspageinpatientwork.php?Registration_ID=".$Registration_ID."&consultation_ID=$consultation_ID' class='art-button-green'>
		    CLINICAL NOTES
		    </a>";
	
	while($patient = mysqli_fetch_assoc($select_patients_qry)){
		
		$patient_numeber = $patient['Registration_ID'];
		$test_name = $patient['Product_Name'];
		$remarks = '<textarea  readonly="readonly">'.$patient['Remarks'].'</textarea>';
		 if(empty($patient['Remarks'])){
		  $remarks = '<textarea  readonly="readonly">NONE</textarea>';
		 }
		$Registration_ID = $patient['Registration_ID'];
		$served_date = $patient['Date_Time'];
		$Radiologist = $patient['Radiologist_ID'];
		$Sonographer = $patient['Sonographer_ID'];
		$Payment_Item_Cache_List_ID = $patient['Patient_Payment_Item_List_ID'];
		$Item_ID = $patient['Item_ID'];
		
		$href="II=".$Item_ID."&PPILI=".$Payment_Item_Cache_List_ID."&RI=".$Registration_ID;
		 
		$imaging='<button style="width:81%;" class="art-button-green" onclick="radiologyviewimage(\''.$href.'\',\''.$test_name.'\')">Imaging</button>';
		$commentsDescription='<button style="width:81%;" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">Report</button>';
		$results_url ="radiologyviewimage_Doctor.php?II=".$Item_ID."&PPILI=".$Payment_Item_Cache_List_ID."&RI=".$Registration_ID;
		
		$view_results =$imaging.$commentsDescription;
		
		//Getting Radiologist Name
		$select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
		$select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
		if(mysqli_num_rows($select_radi_qry) > 0){
			while($radist = mysqli_fetch_assoc($select_radi_qry)){
				$Radiologist_Name = $radist['Employee_Name'];
			}
		} else {
			$Radiologist_Name = 'N/A';			
		}
		
		//Getting Sonographer Name
		$select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
		$select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
		if(mysqli_num_rows($select_sono_qry) > 0){
			while($sonog = mysqli_fetch_assoc($select_sono_qry)){
				$Sonographer_Name = $sonog['Employee_Name'];
			}
		} else {
			$Sonographer_Name = 'N/A';			
		}
		
		//Getting Doctors Comments
		$select_docomments = "SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
		$select_docomments_qry = mysqli_query($conn,$select_docomments) or die(mysqli_error($conn));
		
		if(mysqli_num_rows($select_docomments_qry) > 0){
			while($docom = mysqli_fetch_assoc($select_docomments_qry)){
				$thecomm = $docom['Doctor_Comment'];
				if($thecomm == ''){ $newcom = 'NONE'; } else { $newcom =  $thecomm; }
				$Doctor_Comment = "<textarea style='color:#000;' />".$newcom." </textarea/>";
			}
		} else {
			$Doctor_Comment = "<input type='text' style='color:#000;' disabled='disabled' value='NONE' />";
			$Doctor_Comment = "<textarea style='color:#000;' />NONE</textarea/>";
		}
		
		$style = 'style="text-decoration:none;"';

		echo '<tr>';	
                echo '<td>'.$sn.'</td>';	
                echo '<td>'.$test_name.'</td>';	
                echo '<td>'.$Doctor_Comment.'</td>';	
                echo '<td>'.$remarks.'</td>';	
                echo '<td>'.$Radiologist_Name.'</td>';	
                echo '<td>'.$Sonographer_Name.'</td>';	
                echo '<td>'.$served_date.'</td>';	
                echo '<td>'.$view_results.'</td>';	
		echo '</tr>';		
		$sn++;
		}
		

	echo '</table>';
		
?>	
