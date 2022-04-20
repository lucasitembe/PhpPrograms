<link rel="stylesheet" href="table.css" media="screen"> 

<?php
	require_once('includes/connection.php');
	if(isset($_GET['Sub_Department_ID'])) $Sub_Department_ID = $_GET['Sub_Department_ID'];
	if(isset($_GET['Patient_Name'])) { $Patient_Name = $_GET['Patient_Name']; } else { $Patient_Name = '' ;}
	if(isset($_GET['DateFrom'])) { $DateFrom = $_GET['DateFrom']; } else { $DateFrom = ''; }
	if(isset($_GET['DateTo'])) { $DateTo = $_GET['DateTo']; } else { $DateTo = ''; }
	if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }
	if(isset($_GET['SI'])) { $Supervisor_ID = $_GET['SI']; } else { $Supervisor_ID = 0; }
	if(isset($_GET['listtype'])) { $listtype = $_GET['listtype']; } else { $listtype = 'FromRec'; }
	$apID = 0;
	$filter = '';
	
	@session_start();
	isset($_SESSION['Radiology_Sub_Dep_ID']) ? $Radiology_Sub_Dep_ID = $_SESSION['Radiology_Sub_Dep_ID'] : $Radiology_Sub_Dep_ID = 0;
	//echo $_SESSION['Radiology_Sub_Dep_ID'];
	
	if($Patient_Name != ''){
		$filter = $filter . " AND pr.Patient_Name LIKE '%$Patient_Name%'";
	} 
	
	if ($DateFrom != '' && $DateTo != ''){
		$filter = $filter . ' AND ppil.Transaction_Date_And_Time BETWEEN "$DateFrom" AND "$DateTo" ';
	}
	
	
	//SELECTING PATIENTS LIST
	// $select_patients = "
		// SELECT *			
			// FROM
			// tbl_patient_payment_item_list ppil, 
			// tbl_items i,
			// tbl_item_subcategory tis,
			// tbl_patient_registration pr,
			// tbl_sponsor sp,
			// tbl_patient_payments pp
				// WHERE
				// ppil.Item_ID = i.Item_ID AND
				// ppil.Patient_Payment_ID = pp.Patient_Payment_ID AND
				// pp.Registration_ID = pr.Registration_ID AND
				// i.Item_Subcategory_ID = tis.Item_Subcategory_ID AND
				// ppil.Status = 'served' AND
				// ppil.Check_In_Type = 'Radiology' "; 
				
		$select_patients1="SELECT *			
			FROM
			tbl_radiology_patient_tests rpt, 
			tbl_items i,
			tbl_item_subcategory tis,
			tbl_patient_registration pr,
			tbl_patient_payment_item_list tp,
			tbl_consultation tc,
			tbl_payment_cache pc,
			tbl_item_list_cache tlc
				WHERE
				rpt.Item_ID = i.Item_ID AND
				tis.Item_Subcategory_ID = i.Item_Subcategory_ID AND
				tlc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND
				tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
				tc.consultation_ID= pc.consultation_id AND
				tc.Patient_Payment_Item_List_ID= tp.Patient_Payment_Item_List_ID
				AND
				rpt.Registration_ID = pr.Registration_ID AND
				rpt.Status = 'done' AND tp.Process_Status !='signedoff' GROUP BY Patient_Name";		
				
	//Filtering By Sub_Department
	// if($Radiology_Sub_Dep_ID != 0){
		// $select_patients .= " AND i.Item_Subcategory_ID = '$Radiology_Sub_Dep_ID' ";
	// } elseif($Radiology_Sub_Dep_ID == 0){
		// $select_patients .= " AND i.Item_Subcategory_ID <> '21' AND i.Item_Subcategory_ID <> '22'";
	// }				
	
	// if($PatientType == 'in'){
		// $select_patients .= " AND pp.Billing_Type LIKE '%Inpatient%' ";
	// } elseif($PatientType == 'out'){
		// $select_patients .= " AND pp.Billing_Type LIKE '%Outpatient%' ";
	// }
	
	// $group_by = 'GROUP BY pr.Patient_Name';

	// if($filter != ''){
		// $select_patients_new = $select_patients.$filter.$group_by;
	// } else {
		// $select_patients_new = $select_patients.$group_by;		
	// }
	
	echo '<table width="100%">';
	
	echo '<tr style="text-transform:uppercase; font-weight:bold;" class="thead">';	
		echo '<td style="width:3%;">SN</td>';	
		echo '<td>Patient Name</td>';	
		echo '<td>Patient Number</td>';	
		echo '<td>Spnsor</td>';	
		echo '<td>Gender</td>';	
		echo '<td>Sub Department</td>';	
		echo '<td>Sent Date and Time</td>';	
	echo '</tr>';	
	
	$select_patients_qry = mysqli_query($conn,$select_patients1) or die(mysqli_error($conn));
	$sn = 1;
	$listtype='FromDocConsul';
	while($patient = mysqli_fetch_assoc($select_patients_qry)){
		$patient_name = $patient['Patient_Name'];
		$patient_numeber = $patient['Registration_ID'];
		$sponsor = $patient['Sponsor_Name'];
		$test_name = $patient['Product_Name'];
		$phone_number = $patient['Phone_Number'];
		$gender = $patient['Gender'];
		$subcat = $patient['Item_Subcategory_Name'];
		$Registration_ID = $patient['Registration_ID'];
		$sent_date = $patient['Transaction_Date_And_Time'];
		$hide = "<button>Hide</button>";
		$href = "RadiologyPatientTests.php?Registration_ID=".$Registration_ID."&PatientType=".$PatientType."&listtype=".$listtype." target='_parent'";
		$style = 'style="text-decoration:none;"';

		echo '<tr>';	
			echo '<td>'.$sn.'</td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$patient_name.'</a></td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$patient_numeber.'</a></td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$sponsor.'</a></td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$gender.'</a></td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$subcat.'</a></td>';	
			echo '<td><a href = '.$href.' '.$style.'>'.$sent_date.'</a></td>';	
		echo '</tr>';
		$sn++;

	}
	echo '</table>';
		
?>	