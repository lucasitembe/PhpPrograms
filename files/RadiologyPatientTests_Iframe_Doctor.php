
<?php
	require_once('includes/connection.php');
	if(isset($_GET['Registration_ID'])) { $Registration_ID = $_GET['Registration_ID']; } else { $Registration_ID = 0 ;}
	if(isset($_GET['Date_From'])) { $Date_From = $_GET['Date_From']; } else { $DateFrom = ''; }
	if(isset($_GET['Date_To'])) { $Date_To = $_GET['Date_To']; } else { $DateTo = ''; }
//	
	$apID = 0;
	$filter=' AND DATE(rpt.Date_Time) = DATE(NOW()) ';
        
        
        if(isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)){
          $filter="  AND rpt.Date_Time BETWEEN '". $Date_From."' AND '".$Date_To."'";
        }
	
	//SELECTING PATIENTS LIST
	$select_patients = "
		SELECT *			
			FROM
			tbl_radiology_patient_tests rpt, 
			tbl_items i,
			tbl_patient_registration pr
				WHERE
				rpt.Item_ID = i.Item_ID AND
				rpt.Registration_ID = pr.Registration_ID AND
				rpt.Registration_ID = '$Registration_ID' AND
				rpt.Status = 'done' $filter";
        
        //die($select_patients);

	$group_by = '';
	
	if($filter != ''){
		$select_patients_new = $select_patients.$filter.$group_by;
	} else {
		$select_patients_new = $select_patients.$group_by;		
	}
	
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
	
	$select_patients_qry = mysqli_query($conn,$select_patients_new) or die(mysqli_error($conn));
	$sn = 1;
	
	$clinickNote="<a href='clinicalnotes.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
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
		$Patient_Payment_Item_List_ID = $patient['Patient_Payment_Item_List_ID'];
		$Item_ID = $patient['Item_ID'];
		
		$Patient_Payment_Item_List_ID=$patient['Patient_Payment_Item_List_ID'];
		
		$rs=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
        
		$ppID=mysqli_fetch_assoc($rs);
		$Patient_Payment_ID=$ppID['Patient_Payment_ID'];
		if(mysqli_num_rows($rs)==0){
		  $Patient_Payment_ID=0;
		}
		//die($href);
		
		$href="II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&PPI=".$Patient_Payment_ID."&RI=".$Registration_ID;
		
		/* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
		$imaging='<button style="width:81%;" class="art-button-green" onclick="radiologyviewimage(\''.$href.'\',\''.$test_name.'\')">Imaging</button>';
		$commentsDescription='<button style="width:81%;" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">Report</button>';
		$results_url ="radiologyviewimage_Doctor.php?II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&RI=".$Registration_ID;
		
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
		$select_docomments = "SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List_ID'";
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
		echo '</tr>';;			
		$sn++;
		}
		

	echo '</table>';
		
?>	
