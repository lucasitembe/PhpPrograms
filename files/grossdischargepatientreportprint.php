<?php

include("./includes/connection.php");
$Branch_ID = $_GET['Branch_ID'];
$Gender = $_GET['Gender'];
$Region = $_GET['Region'];
$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
$start_age = $_GET['start_age'];
$end_age = $_GET['end_age'];

$Guarantor_Name = "All";
$Branch_Name = 'All';
  $row_num = '';
$filter = "   AND a.Admission_Status IN ('pending','Discharged') AND DATE(a.pending_set_time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Discharge_Reason = filter_input(INPUT_GET,'Discharge_Reason_ID');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
}

if (empty($Date_From) && empty($Date_To)) {
    $betweenDate = "<b>Today </b>" . date('Y-m-d');
} else {
    $betweenDate = "<b>FROM</b> " . $Date_From . " <b>TO</b> " . $Date_To;
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND  a.Admission_Status IN ('pending','Discharged') AND DATE(a.pending_set_time) BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID='$Sponsor'";

    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = '$ward'";
}else{
    $filter .= "";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
    $select_branch = "SELECT * FROM tbl_branches WHERE Branch_ID = '$Branch_ID'";
    $result = mysqli_query($conn,$select_branch) or die(mysqli_error($conn));
    $Branch_Name = mysqli_fetch_assoc($result)['Branch_Name'];
}
if (!empty($Discharge_Reason) && $Discharge_Reason != 'All') {
    $filter .="  AND a.Discharge_Reason_ID='$Discharge_Reason'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}



$select_Filtered_Patients = "SELECT  
					sp.Guarantor_Name, 
                                        a.Discharge_Reason_ID,
					pr.Gender,pr.Region,
					pr.Registration_ID,
					pr.Date_Of_Birth,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Admission_Status,
					a.Kin_Name,
					a.Admission_Date_Time,
					a.pending_set_time,
					a.Kin_Phone,
                    a.Discharge_Date_Time,
                    cd.consultation_ID,
                    em.Employee_Name as Discharger

				FROM 	
					tbl_hospital_ward hw,
					tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_discharge_reason dr,
					tbl_admission a,
                    tbl_employee em,
                    tbl_check_in_details cd
					WHERE 
						a.registration_id = pr.registration_id AND
						pr.Sponsor_ID = sp.Sponsor_ID AND 
                        em.Employee_ID = a.pending_setter AND
						hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND
						a.Discharge_Reason_ID = dr.Discharge_Reason_ID AND
                        cd.Admission_ID = a.Admision_ID
                                                $filter
                                                GROUP BY a.Admision_ID ORDER BY a.pending_set_time ASC";

//die($select_Filtered_Patients);
$temp=1;
$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>DISCHARGED PATIENT LIST</b><br/> </td>
          </tr>';

if (!empty($betweenDate)) {
    $htm .= '<tr>
             <td  style="text-align:center">' . $betweenDate . '<br/><br/></td>
          </tr>';
}

$htm .='<tr>
             <td  style="text-align:center"><b>Sponsor: </b>' . $Guarantor_Name . ' <b> | <b> Branch:</b> ' . $Branch_Name . '<br/><br/></td>
          </tr>
        </table><br/>';

$htm.= '<table width ="100%" border="0" style="background-color:white;" id="patients-list">';
$htm.= '<thead>
		<tr>
			<td width="5%"><b>Sn</b></td>
			<td width="15%"><b>Patient Name</b></td>
			<td width="9%" style="text-align: left;"><b>Patient #</b></td>
			<td width="8%" style="text-align: left;"><b>Sponsor</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
			<td width="6%" style="text-align: left;"><b>Gend</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted</b></td>
            <td width="10%" style="text-align: center;"><b>Discharged</b></td>
            <td width="10%" style="text-align: center;"><b>Discharged By</b></td>
            <td width="10%" style="text-align: center;"><b>Bill Status</b></td>
            <td width="10%" style="text-align: center;"><b>Clearance Date</b></td>
			<td width="7%" style="text-align: left;"><b>Reason</b></td>
			<td width="11%" style="text-align: left;"><b>Next of kin</b></td>
			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
			<td width="10%" style="text-align: left;"><b>Region</b></td>
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
    $age = '';
}

while ($row = mysqli_fetch_array($results)) {
    $Discharge_Reason_ID = $row['Discharge_Reason_ID'];
    $Admission_Status = $row['Admission_Status'];

    $Discharge_Date_Time = $row['Discharge_Date_Time'];
    $Discharger = $row['Discharger'];

    if($Discharge_Date_Time != NULL) $Cleared = date("j/m/Y g:i a", strtotime($Discharge_Date_Time));
    else $Cleared = "<b>Not Cleared</b>";

    $get_Discharge_Reason = "SELECT * FROM tbl_discharge_reason WHERE Discharge_Reason_ID = '$Discharge_Reason_ID'";
    $got_Discharge_Reason = mysqli_query($conn,$get_Discharge_Reason) or die(mysqli_error($conn));
    while ($rowb = mysqli_fetch_assoc($got_Discharge_Reason)) {
        $Discharge_Reason = $rowb['Discharge_Reason'];
    }
    $admission_date =$row['Admission_Date_Time'];// date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));//
    $Discharge_Date_Time = $row['pending_set_time'];//date("j/m/Y. g:i a", strtotime($row['Discharge_Date_Time']));
    $bed_id = $row['Bed_ID'];
    $get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = '$bed_id'";
    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
    $bed_name = '';
    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $rowb['Bed_Name'];
    }

    $htm.= '<tr>';
    $htm.= '<td style="text-align:center;">' . $temp . '</td>';
    $htm.= "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Guarantor_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Gender'] . "</td>";

    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
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

    if ($Admission_Status == 'Discharged'){
        $Bill_Status = 'Bill Cleared';
    }else{
        $Bill_Status = 'Bill Pending';
    };


    $htm.= "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $Discharge_Date_Time . "</td>";
    $htm.= "<td style='text-align: center; font-size:12px;'>" . ucwords($Discharger) . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $Bill_Status . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $Cleared . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $Discharge_Reason . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . ucwords(strtolower($row['Kin_Name'])) . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Region'] . "</td>";
    $htm.= "</tr>";

    $temp++;
}
$htm .= "<tr><td colspan='14' style='text-align: right;'><b> TOTAL DISCHARGED : " . number_format($temp - 1) . "</td></tr>";
$htm .= "</table></center>";

include("MPDF/mpdf.php");

$mpdf = new mPDF('L', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();
exit;
?>