<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];
    }else{
        $Patient_Name = '';
    }
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }else{
	$section='Admission';
    }
    
    
 	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end
	
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">
			<td style="width:5%;"><b>SN</b></td>
			<td><b>PATIENT NAME</b></td>
			<td><b>PATIENT NO</b></td>
            <td><b>GENDER</b></td>
            <td><b>AGE</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>NEXT OF KIN</b></td>
            <td><b>NEXT OF KIN NO</b></td>
		</tr>';
		/* 
    $select_Filtered_Patients = mysqli_query($conn,
        "SELECT * FROM 
			tbl_patient_registration pr,
			tbl_sponsor s, 
			tbl_admission ad 
			WHERE
				pr.registration_id = ad.registration_id AND 
				ad.Admission_Status = 'Admitted' AND
				s.Sponsor_ID = pr.Sponsor_ID AND 
				ad.Discharge_Clearance_Status = 'not cleared' AND 
				pr.Patient_Name LIKE '%$Patient_Name%'
		") or die(mysqli_error($conn)); */
    $select_Filtered_Patients = mysqli_query($conn,
        "SELECT * FROM 
			tbl_patient_registration pr,
			tbl_sponsor s, 
			tbl_admission ad 
			WHERE
				pr.registration_id = ad.registration_id AND 
				ad.Admission_Status = 'pending' AND
				s.Sponsor_ID = pr.Sponsor_ID AND 
				 pr.Patient_Name LIKE '%$Patient_Name%'") or die(mysqli_error($conn));
	$sn = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
	//we use (&NR=true) to generate new receipt
        echo "<tr>
		<td id='thead' >".$sn."</td>
		<td>
		<a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a>
		</td>";
        echo "<td>
		<a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a>
		</td>";
		
		  echo "<td>
		<a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a>
		</td>";
		
		
        echo "<td><a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		
		
		  echo "<td>
		<a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		
        echo "<td><a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Kin_Name']))."</a></td>";
		
        echo "<td><a href='discharge.php?section=$section&Registration_ID=".$row['Registration_ID']."&Admision_ID=".$row['Admision_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Kin_Phone']."</a></td>";
		$sn++;
    }

	echo "</tr>";
?>
</table></center>