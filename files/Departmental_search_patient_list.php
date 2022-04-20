<link rel="stylesheet" href="table.css" media="screen"> 
<?php
	session_start();
    include("./includes/connection.php");
    $Yest = new DateTime('yesterday');
    $Yesterday = $Yest->format('Y-m-d-H-i-s');
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];
    }else{
        $Patient_Name = '';
    }
    
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }
    if(isset($_GET['Phone_Number'])){
	$Phone_Number = $_GET['Phone_Number'];
    }else{
	$Phone_Number = '';
    }
    
     $location='';
	//  DIE($_GET['location']);
	if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
           $location='location=otherdepartment&';
        }
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
	
	

    echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
	    <td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
		    <td><b>SPONSOR</b></td>
			<td><b>AGE</b></td>
			    <td><b>GENDER</b></td>
				<td><b>PHONE NUMBER</b></td>
				    <td><b>MEMBER NUMBER</b></td></tr>';


if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){
    if($Patient_Name != '' && $Patient_Name != null && $Patient_Number != '' && $Patient_Number != null && $Phone_Number != '' && $Phone_Number != null){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' and
			    pr.Registration_ID like '%$Patient_Number%' and
				pr.Phone_Number like '%$Phone_Number%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Patient_Name != '' && $Patient_Name != null && ($Patient_Number == '' || $Patient_Number == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Patient_Number != '' && $Patient_Number != null && ($Patient_Name == '' || $Patient_Name == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Registration_ID = '$Patient_Number' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Phone_Number != '' && $Phone_Number !=null && ($Patient_Name == '' || $Patient_Name == null) && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Phone_Number = '$Phone_Number' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }
}else{
	if($Patient_Name != '' && $Patient_Name != null && $Patient_Number != '' && $Patient_Number != null && $Phone_Number != '' && $Phone_Number != null){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_Date_And_Time
				from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
				pr.sponsor_id = sp.sponsor_id and
				ci.Registration_ID = pr.Registration_ID and
				ci.check_in_status = 'saved' and Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
				pr.Patient_Name like '%$Patient_Name%' and
				pr.Registration_ID like '%$Patient_Number%' and
				pr.Phone_Number like '%$Phone_Number%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Patient_Name != '' && $Patient_Name != null && ($Patient_Number == '' || $Patient_Number == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_Date_And_Time
				from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
				pr.sponsor_id = sp.sponsor_id and
				ci.Registration_ID = pr.Registration_ID and
				ci.check_in_status = 'saved' and Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
				pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Patient_Number != '' && $Patient_Number != null && ($Patient_Name == '' || $Patient_Name == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_Date_And_Time
				from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
			    pr.sponsor_id = sp.sponsor_id and
			    ci.Registration_ID = pr.Registration_ID and
			    ci.check_in_status = 'saved' and Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
				pr.Registration_ID like '%$Patient_Number%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }elseif($Phone_Number != '' && $Phone_Number !=null && ($Patient_Name == '' || $Patient_Name == null) && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_Date_And_Time
				from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
			    pr.sponsor_id = sp.sponsor_id and
			    ci.Registration_ID = pr.Registration_ID and
			    ci.check_in_status = 'saved' and Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
				pr.Phone_Number like '%$Phone_Number%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_Date_And_Time
				from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci where
			    pr.sponsor_id = sp.sponsor_id and
			    ci.Registration_ID = pr.Registration_ID and
			    ci.check_in_status = 'saved' and Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
				pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
    }
}
    

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
    	$Registration_ID = $row['Registration_ID'];
    	//check if not inpatient
        $check_p = mysqli_query($conn,"select Registration_ID from tbl_admission where Admission_Status = 'Admitted' and Discharge_Clearance_Status = 'not cleared' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $check_num = mysqli_num_rows($check_p);
        if($check_num == 0){
			//AGE FUNCTION
			 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
			// if($age == 0){
			
			$date1 = new DateTime($Today);
			$date2 = new DateTime($row['Date_Of_Birth']);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";

			if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){
		        echo "<tr><td width ='2%' id='thead'>".$temp."</td><td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
				echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		        echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		        echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		        echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		        echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		        echo "<td><a href='departmental_outpatient_otherworkspage.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				$temp++;
			}else{
				
				//generate adhoc time limit (only 24 hours)
	            $dateA = date_create($original_Date);
	            $dateB = date_create($row['Check_In_Date_And_Time']);
	            $diff = date_diff($dateB, $dateA);
	            $h_duration = $diff->h;
	            $d_duration = $diff->d;
	            if($h_duration < 24 && $d_duration < 1){
	        echo "<tr><td width ='2%' id='thead'>".$temp."</td><td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
			echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
	        echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
	        echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
	        echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
	        echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
	        echo "<td><a href='departmental_outpatient_otherworkspage.php?".$location."Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
			$temp++;
				}
			}
		}
    }
?></table></center>

