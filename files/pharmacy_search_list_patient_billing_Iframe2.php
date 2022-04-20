<link rel="stylesheet" href="table.css" media="screen"> 
<?php
	session_start();
    include("./includes/connection.php");
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
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
	
	$Yest = new DateTime('yesterday');
    $Yesterday = $Yest->format('Y-m-d').substr($original_Date, 10);
?>

<!-- CUSTOM STYLES -->
<style>
    .head-section{
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }
    .head-section td{
        padding: 10px !important;
        border: 1px solid #cacaca;
        font-size: 16px !important;
    }
    .head-section tr{
        background-color: #f5f5f5;;
    }
    .head-section a:hover{
        color: #e1e1e1;
    }
</style>
<!-- CUSTOM STYLES -->
	<center><table width =100% class="head-section">
	<tr id="thead">
		<td style="width:5%;"><b>SN</b></td>
		<td><b>PATIENT NAME</b></td>
		<td><b>PATIENT NUMBER</b></td>
		<td><b>SPONSOR</b></td>
		<td><b>AGE</b></td>
		<td><b>GENDER</b></td>
		<td><b>PHONE NUMBER</b></td>
		<td><b>MEMBER NUMBER</b></td>
	</tr>

<?php
	if(isset($_SESSION['systeminfo']['Pharmacy_Patient_List_Displays_Only_Current_Checked_In']) && strtolower($_SESSION['systeminfo']['Pharmacy_Patient_List_Displays_Only_Current_Checked_In']) == 'yes'){
		if($Patient_Name != '' && $Patient_Name != null){
			$select_Filtered_Patients = mysqli_query($conn,

            "SELECT * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
			pr.registration_id = ci.registration_id and
			sp.sponsor_id = pr.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
			pr.Patient_Name like '%$Patient_Name%' limit 200") or die(mysqli_error($conn));

	    }elseif($Patient_Number != '' && $Patient_Number != null){
			$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
			pr.registration_id = ci.registration_id and
			sp.sponsor_id = pr.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
			pr.Registration_ID = '$Patient_Number' limit 200") or die(mysqli_error($conn));
			
	    }elseif($Phone_Number != '' || $Phone_Number != null){
			$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
			pr.registration_id = ci.registration_id and
			sp.sponsor_id = pr.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			Check_In_Date_And_Time between '$Yesterday' and '$original_Date' and
			pr.Phone_Number like '%$Patient_Number%' limit 200") or die(mysqli_error($conn));
	    }else{
			$select_Filtered_Patients = mysqli_query($conn,"SELECT * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
			pr.registration_id = ci.registration_id and
			sp.sponsor_id = pr.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			Check_In_Date_And_Time between '$Yesterday' and '$original_Date' limit 200") or die(mysqli_error($conn));
	    }
	}else{
		if($Patient_Name != '' && $Patient_Name != null){
			$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
	    }elseif($Patient_Number != '' && $Patient_Number != null){
			$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			pr.Registration_ID = '$Patient_Number' order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
	    }elseif($Phone_Number != '' || $Phone_Number != null){
			$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			pr.Phone_Number like '%$Patient_Number%' order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
	    }else{
			$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
			from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id AND pr.Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 20") or die(mysqli_error($conn));
	    }
	}

		if(mysqli_num_rows($select_Filtered_Patients) > 0){
			while($row = mysqli_fetch_array($select_Filtered_Patients)){
				$date1 = new DateTime($Today);
				$date2 = new DateTime($row['Date_Of_Birth']);
				$diff = $date1 -> diff($date2);
				$age = $diff->y." Years, ";
				$age .= $diff->m." Months, "; 
				$age .= $diff->d." Days";
			
				echo "<tr><td width ='2%'>".$temp."</td>
				<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
				echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				$temp++;
			}
		}else{
			if(!empty($Patient_Name)){
				$filter = " pr.Patient_Name like '%$Patient_Name%'";
			}elseif(!empty($Phone_Number)){
				$filter = " pr.Phone_Number like '%$Patient_Number%'";
			}elseif(!empty($Patient_Number)){
				$filter = " pr.Registration_ID = '$Patient_Number'";
			}

			if(!empty($Patient_Number) || !empty($Patient_Name) || !empty($Phone_Number)){
				$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
				from tbl_patient_registration pr, tbl_sponsor sp where $filter AND pr.sponsor_id = sp.sponsor_id AND pr.Registration_ID IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Status <> 'Discharged') order by Registration_ID Desc limit 200") or die(mysqli_error($conn));

				if(mysqli_num_rows($select_Filtered_Patients) > 0){
					while($data = mysqli_fetch_assoc($select_Filtered_Patients)){
						$Patient_Name = $data['Patient_Name'];
						$Registration_ID = $data['Registration_ID'];

						$admission_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT hw.Hospital_Ward_Name FROM tbl_admission ad, tbl_hospital_ward hw WHERE ad.Admission_Status <> 'Discharged' AND ad.Registration_ID = '$Registration_ID' AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID ORDER BY ad.Admision_ID DESC LIMIT 1"))['Hospital_Ward_Name'];

						echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 22px;' colspan='8'>THE PATIENT NAMED ".$Patient_Name." WITH REGISTRATION NUMBER ".$Registration_ID." WAS ADMITTED IN eHMS IN ".$admission_data."</td></tr>";
					}
				}else{
					echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 22px;' colspan='8'>THIS PATIENT WAS NOT REGISTERED eHMS</td></tr>";
				}
			}
		}
		mysqli_close($conn);

?></table></center>

