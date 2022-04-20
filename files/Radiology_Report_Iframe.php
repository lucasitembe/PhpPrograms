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
	
	if($Patient_Name != ''){
		$filter = $filter . " AND pr.Patient_Name LIKE '%$Patient_Name%'";
	} 
	
	if ($DateFrom != '' && $DateTo != ''){
		$filter = $filter . ' AND rpt.Date_Time BETWEEN "$DateFrom" AND "$DateTo" ';
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
				rpt.Status = 'done' ";

	$group_by = '';
	
	if($filter != ''){
		$select_patients_new = $select_patients.$filter.$group_by;
	} else {
		$select_patients_new = $select_patients.$group_by;		
	}
	
	echo '<table width="100%">';
	echo '<tr style="text-transform:uppercase; font-weight:bold;" id="thead">';	
		echo '<td style="width:5%;">SN</td>';	
		echo '<td>Patient Name</td>';	
		echo '<td>Test Name</td>';	
		echo '<td>Gender</td>';	
		echo '<td>Radiologist</td>';	
		echo '<td>Sonographer</td>';	
		echo '<td>visit Date</td>';	
	echo '</tr>';	
	
	$select_patients_qry = mysqli_query($conn,$select_patients_new) or die(mysqli_error($conn));
	$sn = 1;
	while($patient = mysqli_fetch_assoc($select_patients_qry)){
		$patient_name = $patient['Patient_Name'];
		$patient_numeber = $patient['Registration_ID'];
		$test_name = $patient['Product_Name'];
		$gender = $patient['Gender'];
		$Registration_ID = $patient['Registration_ID'];
		$served_date = $patient['Date_Time'];
		$Radiologist = $patient['Radiologist_ID'];
		$Sonographer = $patient['Sonographer_ID'];
		
		$select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
		$select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
		while($radist = mysqli_fetch_assoc($select_radi_qry)){
			$Radiologist_Name = $radist['Employee_Name'];
		}
		
		$select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
		$select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
		while($sonog = mysqli_fetch_assoc($select_sono_qry)){
			$Sonographer_Name = $sonog['Employee_Name'];
		}
		
		$href = "RadiologyPatientTests.php?Registration_ID=".$Registration_ID."&PatientType=".$PatientType."&listtype=".$listtype." target='_parent'";
		$style = 'style="text-decoration:none;"';

		echo '<tr>';	
			echo '<td id="thead">'.$sn.'</td>';	
			echo '<td>'.$patient_name.'</td>';	
			echo '<td>'.$test_name.'</td>';	
			echo '<td>'.$gender.'</td>';	
			echo '<td>'.$Radiologist_Name.'</td>';	
			echo '<td>'.$Sonographer_Name.'</td>';	
			echo '<td>'.$served_date.'</td>';	
		echo '</tr>';;			
		$sn++;
		}

	echo '</table>';
		
?>	