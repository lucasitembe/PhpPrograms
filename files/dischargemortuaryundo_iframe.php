<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style>

<?php
include("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';

$filter = "   AND Admission_Status='Discharged'  AND DATE(a.Discharge_Date_Time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Admission_Status='Discharged' AND a.Discharge_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}


$select_Filtered_Patients = "
				SELECT 
					pr.Gender,pr.Registration_ID, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, 
					hw.Hospital_Ward_Name, a.bed_name, a.Admission_Date_Time, a.Discharge_Date_Time,ma.Kin_Out_Phone, 
					 ma.Admision_ID, e.Employee_Name, ma.Kin_Out,ma.Taken_By,ma.Date_Of_Death,ma.case_type
					FROM 
					tbl_hospital_ward hw, tbl_patient_registration pr, tbl_admission a, tbl_mortuary_admission ma, 
					tbl_employee e 
					WHERE 
					a.registration_id = pr.registration_id AND ma.Admision_ID = a.Admision_ID 
					AND hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND a.Admission_Status = 'Discharged' 
				AND a.Discharge_Employee_ID = e.Employee_ID
                                                $filter
                                                LIMIT 100
                                                ";

//die($select_Filtered_Patients);


echo '<center><table width ="100%" style="background-color:white;" id="patients-list">';
echo '<thead>
                <tr style="font-size:13px;">
			<td width=2%><b>Sn</b></td>
			<td width="11%"><b>Full Name</b></td>
			<td width="4%" style="text-align: center;"><b>Reg #</b></td>
			<td width="6%" style="text-align: center;"><b>Case Type</b></td>
			<td width="8%" style="text-align: center;"><b>Ward</b></td>
			<td width="7%" style="text-align: center;"><b>Bed</b></td>
			<td width="4%" style="text-align: center;"><b>Gender</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="9%" style="text-align: center;"><b>Admitted</b></td>
			<td width="10%" style="text-align: center;"><b>Discharged</b></td>
            <td width="10%" style="text-align: center;"><b>Discharged By</b></td>
			<td width="10%" style="text-align: left;"><b>Taken By</b></td>
			<td width="10%" style="text-align: left;"><b>Name</b></td>
			<td width="9%" style="text-align: left;"><b>Phone</b></td>
		</tr>
                </thead>
                ';

$results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
$temp = 1;

// $Today_Date = mysqli_query($conn,"select now() as today");

// while ($row = mysqli_fetch_array($Today_Date)) {
    // $original_Date = $row['today'];
    // $new_Date = date("Y-m-d", strtotime($original_Date));
    // $Today = $new_Date;
    // $age = '';
// }

while ($row = mysqli_fetch_array($results)) {

    $admission_date = $row['Admission_Date_Time']; // date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));//
    $discharge_date = $row['Discharge_Date_Time']; //date("j/m/Y. g:i a", strtotime($row['Discharge_Date_Time']));


    $bed_name = $row['bed_name'];
//    $get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = $bed_id";
//    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
//    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
//        $bed_name = $rowb['Bed_Name'];
//    }

    echo '<tr><td>' . $temp . '</td>';
    echo "<td style='font-size:13px;'><a href='mortuary_discharge_undo_info.php?intent=print&Registration_ID=".$row['Registration_ID']."' title='Click to go to discharge patient Summer'>" . ucwords($row['Patient_Name']) . "</a></td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . ucfirst($row['case_type']) . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $bed_name . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Gender'] . "</td>";

     $Date_Of_Birth = $row['Date_Of_Birth'];
	$Date_Of_Death = $row['Date_Of_Death'];
	$Date_Death = strtotime($Date_Of_Death);
	$date_death = date("Y-m-d", $Date_Death);
    $date1 = new DateTime($date_death);
    $date2 = new DateTime($Date_Of_Birth);
	$diff = $date1->diff($date2);
	$age = $diff->y . " yrs";
    if ($age == 0) {
        // $date1 = new DateTime($Today);
        // $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->m . " Months";
    }
    if ($age == 0) {
        // $date1 = new DateTime($Today);
        // $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->d . " Days";
    };
    echo "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $discharge_date . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . ucwords($row['Employee_Name']) . "</td>";
    echo "<td style='font-size:12px;'>" . ucwords($row['Taken_By']) . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . ucwords($row['Kin_Out']) . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Out_Phone'] . "</td>";

    $temp++;
}

echo '</table>'
 . '</center>';
//echo "<tr><td colspan=14><hr></td></tr>";
//echo "<tr><td colspan=14 style='text-align: right;'><b> TOTAL DISCHARGED : " . number_format($temp - 1) . "</td></tr>";
//echo "<tr><td colspan=14><hr></td></tr>";
?>
