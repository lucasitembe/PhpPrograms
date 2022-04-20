<?php
include("./includes/connection.php");
$temp = 1;
$filter = "   AND Admission_Status='Discharged'";

if (isset($_GET['Sponsor'])) {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Admission_Status='Discharged' AND ad.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
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

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

echo '<center><table width ="100%" id="admittedpatientslist">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>NEXT OF KIN</b></th>
                <th><b>NEXT OF KIN NO</b></th>
                <th><b>BED NO</b></th>
                <th><b>DISCHARED ON</b></th>
                <th><b>DISCHARED BY</b></th>
             </tr>
         </thead>";

$sql = "SELECT * 
				FROM 
				tbl_patient_registration pr,
				tbl_admission ad,
                                tbl_beds bd,
				tbl_check_in_details cd,
				tbl_sponsor sp,
				tbl_hospital_ward hw,
                                tbl_employee e
					WHERE 
					pr.Registration_ID=ad.Registration_ID AND 
					pr.Sponsor_ID=sp.Sponsor_ID AND 
					ad.Admision_ID=cd.Admission_ID AND
                                        bd.Bed_ID = ad.Bed_ID AND
                                        e.Employee_ID=ad.Discharge_Employee_ID AND
					ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $filter LIMIT 10";


$select_patient_ward = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_patient_ward)) {
    //AGE FUNCTION
    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";


    echo "<tr><td id='thead'>" . $temp . "</td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID'] . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID'] . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
    
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
    
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Kin_Name'])) . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Kin_Phone'] . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Bed_Name'] . "</a></td>";
    
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Discharge_Date_Time'] . "</a></td>";

    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&discharged=discharged' target='_parent' style='text-decoration: none;'>" . $row['Employee_Name'] . "</a></td>";
   
    $temp++;
} echo "</tr>";
?></table></center>
