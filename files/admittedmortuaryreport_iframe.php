<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
	red{
		color:red;
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
$row_num = '100';
$case_type = '';
$start_age = '';
$end_age = '';

$filter = "   AND DATE(a.Admission_Date_Time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
    $row_num = filter_input(INPUT_GET, 'row_num');
    $case_type = filter_input(INPUT_GET, 'case_type');
    $start_age = filter_input(INPUT_GET, 'start_age');
    $end_age = filter_input(INPUT_GET, 'end_age');
    $diagnosis_time = filter_input(INPUT_GET, 'diagnosis_time');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND a.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}
// if (!empty($start_age) && !empty($end_age)) {
//     $filter .= "  AND  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '" . $start_age . "' AND '" . $end_age . "'";
// }
if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .= "  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($case_type) && $case_type != 'All') {
    $filter .= " AND ma.case_type = '$case_type'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
}

if (!empty($Patient_Name)) {
    $filter .= "  AND pr.Patient_Name like '%$Patient_Name%'";
}
if (isset($row_num) && !empty($row_num)) {

    if ($row_num == 'ALL') {
        $filter .= "";
    } else {
        $filter .= " LIMIT $row_num";
    }
}


$select_Filtered_Patients = "SELECT sp.Guarantor_Name, COALESCE(NULLIF(ma.Corpse_Kin_Phone,''),a.Kin_Phone) AS Kin_Phone,   COALESCE(NULLIF(ma.Corpse_Brought_By,''),a.Kin_Name) AS Kin_Name, pr.Gender,pr.Region,ma.*, pr.Registration_ID, ma.case_type, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, hw.Hospital_Ward_Name, a.Admision_ID, a.Bed_Name, a.Admission_Status, e.Employee_Name, a.Admission_Date_Time FROM tbl_hospital_ward hw, tbl_patient_registration pr, tbl_sponsor sp, tbl_mortuary_admission ma, tbl_admission a, tbl_employee e WHERE a.registration_id = pr.registration_id AND a.registration_id = pr.registration_id AND ma.Admision_ID = a.Admision_ID AND pr.Sponsor_ID = sp.Sponsor_ID AND hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND a.Admission_Employee_ID = e.Employee_ID AND hw.ward_type = 'mortuary_ward' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '" . $start_age . "' AND '" . $end_age . "' $filter";

//echo $select_Filtered_Patients; exit();
echo '<center><table width ="100%" border="0" style="background-color:white;" id="patients-list">';
echo '<thead>
		<tr>
			<td width="3%"><b>Sn</b></td>
			<td width="15%"><b> Name</b></td>
			<td width="9%" style="text-align: left;"><b>Reg #</b></td>
                        <td width="9%" style="text-align: left;"><b>Admission ID</b></td>
			<td width="8%" style="text-align: left;"><b>Deadline</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
			<td width="7%" style="text-align: left;"><b>Bed</b></td>
			<td width="6%" style="text-align: left;"><b>Gender</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted On</b></td>
			<td width="11%" style="text-align: left;"><b>Admitted By</b></td>
			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
			<td width="10%" style="text-align: left;"><b>Case</b></td>
		</tr>
                </thead>';

$results = mysqli_query($conn,$select_Filtered_Patients);
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    
}

while ($row = mysqli_fetch_array($results)) {
    $admission_date = $row['Admission_Date_Time']; // date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    $Bed_Name = $row['Bed_Name'];


    echo '<tr>';
    echo '<td style="text-align:center;">' . $temp . '</td>';
    $Registration_ID = $row['Registration_ID'];
    echo "<td style='font-size:13px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Registration_ID'] . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Admision_ID'] . "</a></td>";
    $date = $row['Admission_Date_Time'];
    $date_ = strtotime($date);
    $date = strtotime("+7 day", $date_);
    $deadline = date('Y-M-d', $date);
	$leo = new DateTime($Today);
	$tarehe = date("Y-m-d", $date_);
	$_admitDate = new DateTime($tarehe);
	$tofauti = $leo->diff($_admitDate);
	$tofauti_ = $tofauti->d;
	if ($tofauti_ >= 7){
		echo "<td  style='text-align: left; font-size:12px;'><a  target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'><b style='color:red;'>". $deadline . "</b></a></td>";
	} else if($tofauti_ >= 5){
		echo "<td  style='text-align: left; font-size:12px;'><a  target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'><b style='color:#ffcd23;'>". $deadline . "</b></a></td>";
	} else{
		echo "<td  style='text-align: left; font-size:12px;'><a  target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'><b >". $deadline . "</b></a></td>";
    }
	echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Hospital_Ward_Name'] . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $Bed_Name . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Gender'] . "</a></td>";

	$Date_Of_Death = $row['Date_Of_Death'];
        $date1 = new DateTime($Date_Of_Death);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
    echo "<td style='text-align: center; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $age . "</a></td>";
    echo "<td style='text-align: center; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $admission_date . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Employee_Name'] . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . $row['Kin_Phone'] . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'><a target='_blank' href='individual_admission_report.php?mortuary=print&Registration_ID=" . $Registration_ID . "'>" . ucwords(strtolower($row['case_type'])) . "</a></td>";
    echo "</tr>";

    $temp++;
}
echo '</table>';
?></center>
