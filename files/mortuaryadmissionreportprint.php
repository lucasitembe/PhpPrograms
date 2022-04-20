<?php

include("./includes/connection.php");
$Branch_ID = $_GET['Branch_ID'];
$Gender = $_GET['Gender'];
$Region = $_GET['Region'];
$case_type = $_GET['case_type'];
$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
$start_age = $_GET['start_age'];
$end_age = $_GET['end_age'];
$row_num = '100';

$Guarantor_Name = "All";
$Branch_Name = 'All';

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

if (empty($Date_From) && empty($Date_To)) {
    $betweenDate = "<b>Today </b>" . date('Y-m-d');
} else {
    $betweenDate = "<b>FROM</b> " . $Date_From . " <b>TO</b> " . $Date_To;
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND  a.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}
if (!empty($start_age) && !empty($end_age)) {
    $filter .= "  AND  TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '" . $start_age . "' AND '" . $end_age . "'";
}
if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";

    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}

if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}
if (!empty($case_type) && $case_type != 'All'){
    $filter .= " AND ma.case_type = '$case_type'";
}
if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";


    $select_branch = "SELECT * FROM tbl_branches WHERE Branch_ID = '$Branch_ID'";
    $result = mysqli_query($conn,$select_branch) or die(mysqli_error($conn));
    $Branch_Name = mysqli_fetch_assoc($result)['Branch_Name'];
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if(isset($row_num) && !empty($row_num)){
   
    if($row_num=='ALL'){
      $filter .="";
    } else {
        $filter .="LIMIT $row_num";
    }
}

$select_Filtered_Patients = "SELECT sp.Guarantor_Name, COALESCE(NULLIF(ma.Corpse_Kin_Phone,''),a.Kin_Phone) AS Kin_Phone,   COALESCE(NULLIF(ma.Corpse_Brought_By,''),a.Kin_Name) AS Kin_Name, pr.Gender,pr.Region,ma.*, pr.Registration_ID, ma.case_type, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, hw.Hospital_Ward_Name, a.Admision_ID, a.Bed_Name, a.Admission_Status, e.Employee_Name, a.Admission_Date_Time FROM tbl_hospital_ward hw, tbl_patient_registration pr, tbl_sponsor sp, tbl_mortuary_admission ma, tbl_admission a, tbl_employee e WHERE a.registration_id = pr.registration_id AND a.registration_id = pr.registration_id AND ma.Admision_ID = a.Admision_ID AND pr.Sponsor_ID = sp.Sponsor_ID AND hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND a.Admission_Employee_ID = e.Employee_ID AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '" . $start_age . "' AND '" . $end_age . "' $filter";

$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="./branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b> LIST OF ADMITTED BODIES</b><br/> </td>
          </tr>';

if (!empty($betweenDate)) {
    $htm .= '<tr>
             <td  style="text-align:center">' . $betweenDate . '<br/><br/></td>
          </tr>';
}

$htm .='<tr>
             <td  style="text-align:center">  <b> Branch:</b> ' . $Branch_Name . '</td>
             </tr>
             <tr>
             <td  style="text-align:center; text-transform: capitalize;">  <b> Case Type:</b> ' .$case_type . '</td>
          </tr>
          <tr>
             <td  style="text-align:center; text-transform: capitalize;">  <b> Age Between:</b> ' .$start_age. ' And ' .$end_age. ' &nbsp;' .$diagnosis_time. 's</td>
          </tr>
        </table><br/>';

$htm.= '<table width ="100%" border="0" style="background-color:white;" id="patients-list">';
$htm.= '<thead>
		<tr>
			<td width="5%"><b>Sn</b></td>
			<td width="15%"><b>Full Name</b></td>
			<td width="9%" style="text-align: left;"><b>Reg #</b></td>
			<td width="8%" style="text-align: left;"><b>Deadline</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
			<td width="7%" style="text-align: left;"><b>Bed</b></td>
			<td width="6%" style="text-align: left;"><b>Gend</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted On</b></td>
			<td width="11%" style="text-align: left;"><b>Admitted By</b></td>
			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
			<td width="10%" style="text-align: left;"><b>Case</b></td>
		</tr>
              
                </thead>';

$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");



$results = mysqli_query($conn,$select_Filtered_Patients);
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
   // $age = '';
}

while ($row = mysqli_fetch_array($results)) {
    $admission_date = $row['Admission_Date_Time']; date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    $Bed_Name = $row['Bed_Name'];

    $htm.= '<tr>';
    $htm.= '<td style="text-align:center;">' . $temp . '</td>';
    $htm.= "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
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
		$htm.= "<td style='text-align: left; font-size:12px;'><b style='color:red;'>". $deadline . "</b></td>";
	} else if($tofauti_ >= 5){
		$htm.= "<td style='text-align: left; font-size:12px;'><b style='color:#ffcd23;'>". $deadline . "</b></td>";
	} else{
		$htm.= "<td  style='text-align: left; font-size:12px;'><b >". $deadline . "</b></td>";
    }
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $Bed_Name . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Gender'] . "</td>";
	$Date_Of_Birth = $row['Date_Of_Birth'];
	$Date_Of_Death = $row['Date_Of_Death'];
	$Date_Death = strtotime($Date_Of_Death);
	$date_death = date("Y-m-d", $Date_Death);
    $date1 = new DateTime($date_death);
    $date2 = new DateTime($Date_Of_Birth);
	$diff = $date1->diff($date2);
	$age = $diff->y . " yrs";
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->m . " Months";
    }
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->d . " Days";
    };
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" .  $row['Employee_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . ucfirst($row['case_type']) . "</td>";
    $htm.= "</tr>";

    $temp++;
}
$htm .= "<tr><td colspan='12' style='text-align: right;'><b> TOTAL ADMITTED : " . number_format($temp - 1) . "</td></tr>";
$htm .= "</table></center>";

include("MPDF/mpdf.php");

$mpdf = new mPDF('a', 'A4');
$mpdf->showImageErrors=true;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();
exit;
?>