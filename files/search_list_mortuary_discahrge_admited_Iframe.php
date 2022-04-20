<?php
include("./includes/connection.php");

$filter = "   AND Discharge_Clearance_Status = 'cleared' AND Admission_Status != 'Discharged' ";

if (isset($_GET['Sponsor'])) {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Discharge_Clearance_Status = 'cleared'  AND Admission_Status != 'Discharged'  AND ad.Clearance_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
//echo $ward;exit;
    if (!empty($ward) && $ward != 'All') {
        $filter .= " AND ad.Hospital_Ward_ID  = $ward";
    }

    if (!empty($Patient_Name)) {
        $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
    }
}


//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end
echo '<center><table width ="100%" id="patientList">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>ADMISSION DATE</b></th>
                <th><b>MORTUARY DEALINE</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>WARD</b></th>
                <th><b>NEXT OF KIN</b></th>
                <th><b>NEXT OF KIN NO</b></th>
             </tr>
         </thead>";

$select_Filtered_Patients = mysqli_query($conn,
        "SELECT  pr.Registration_ID,ad.Admision_ID,ma.*,pr.Patient_Name,pr.Gender,ad.Admission_Date_Time,pr.Date_Of_Birth,sp.Guarantor_Name,pr.Phone_Number,hw.Hospital_Ward_Name FROM 
			tbl_patient_registration pr,tbl_mortuary_admission ma,
                        tbl_admission ad,
                        tbl_check_in_details cd,
                        tbl_sponsor sp,
                        tbl_hospital_ward hw
			WHERE
				pr.Registration_ID=ad.Registration_ID AND 
                                pr.Sponsor_ID=sp.Sponsor_ID AND 
                                ad.Admision_ID=cd.Admission_ID AND 
                                ad.Admision_ID=ma.Admision_ID AND 
                                ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $filter GROUP BY pr.Registration_ID LIMIT 100") or die(mysqli_error($conn));
$sn = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
		//DEALINE
		$date = $row['Admission_Date_Time'];
		$date = strtotime($date);
		$date = strtotime("+7 day", $date);
		//echo date('d/m/Y', strtotime('+7 days'));
		$deadline = date('Y-M-d', strtotime('+7 days'));
		//echo date('M d, Y', $date);
        $Corpse_Brought_By = $row['Corpse_Brought_By'];
        $Corpse_Kin_Phone = $row['Corpse_Kin_Phone'];

        // echo "<td>" . $Corpse_Brought_By . "</td>";
        // echo "<td>" . $Corpse_Kin_Phone . "</td>";
    //AGE FUNCTION
   // $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){
	$date1=strtotime($row['Date_Of_Death']);
	$date2=strtotime($row['Date_Of_Birth']);	
	$dated = date('Y-m-d',$date1);
	$dateb = date('Y-m-d',$date2);
    $dated = new DateTime($dated);
    $dateb = new DateTime($dateb);
    $diff = $dated->diff($dateb);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
    
     echo "<tr><td>" . $sn . "</td>";                                                                                                
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";

    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
	echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $row['Admission_Date_Time'] . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $deadline . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $row['Hospital_Ward_Name'] . "</a></td>";
    
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($Corpse_Brought_By)) . "</a></td>";
    echo "<td><a href='mortuarydischarge.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID'] . "&from_morgue=yes' target='_parent' style='text-decoration: none;'>" . $Corpse_Kin_Phone . "</a></td>";

    
       $sn++;
}

echo "</tr>";
?>
</table></center>