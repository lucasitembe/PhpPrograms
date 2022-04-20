<?php

include("./includes/connection.php");
$end_date = $_GET['end_date'];
$start_date = $_GET['start_date'];
$Registration_ID = $_GET['Registration_ID'];
$Patient_Name = $_GET['Patient_Name'];

$Guarantor_Name = "All";
$Branch_Name = 'All';

$filter = "   AND DATE(a.Admission_Date_Time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $end_date = filter_input(INPUT_GET, 'end_date');
    $start_date = filter_input(INPUT_GET, 'start_date');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Registration_ID = filter_input(INPUT_GET, 'Registration_ID');
}

if (empty($start_date) && empty($end_date)) {
    $betweenDate = "<b>Today </b>" . date('Y-m-d');
} else {
    $betweenDate = "<b>FROM</b> " . $start_date . " <b>TO</b> " . $end_date;
}

if (isset($end_date) && !empty($end_date) && isset($start_date) && !empty($start_date)) {
    $filter = "  AND  ed.Edit_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
}


if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}
if (!empty($Registration_ID)) {
    $filter .="  AND ed.Registration_ID = '$Registration_ID'";
}
$select_Filtered_Patients = "SELECT ed.Registration_ID, pr.Patient_Name, emp.Employee_Name, ed.Edit_date, ed.Old_name, ed.Sponsor_ID, ed.Old_sponsor FROM tbl_patient_edit ed, tbl_patient_registration pr, tbl_employee emp WHERE emp.Employee_ID = ed.Employee_ID AND pr.Registration_ID = ed.Registration_ID $filter ORDER BY ed.Edit_date ASC";

$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="./branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>AUDIT TRAIL - EDIT PATIENT REPORT</b><br/> </td>
          </tr>';

if (!empty($betweenDate)) {
    $htm .= '<tr>
             <td  style="text-align:center">' . $betweenDate . '<br/><br/></td>
          </tr>';
}

$htm.= '<table width ="100%" border="0" style="background-color:white;" id="patients-list">';
$htm.= '<thead>
            <tr style="background: #dedede;">
            <td style="width:50px"><b>S/No</b></td>
            <td><b>Patient Number</b></td>
            <td><b>Curent Patient Name</b></td>
            <td><b>Old Patient Name</b></td>
            <td><b>Current Sponsor</b></td>
            <td><b>Old Sponsor</b></td>
            <td><b>Edited DateTime Created</b></td>
            <td><b>Edited Employee</b></td>
            </tr>
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
    
    $htm.= '<tr>';
        $htm.='<td>'.$count_sn.'</td>';
        $htm.='<td>'.$Registration_ID.'</td>';
        $htm.='<td>'.$Patient_Name.'</td>';
        $htm.='<td>'.$Old_name.'</td>';
        $htm.='<td>'.$Guarantor_Name.'</td>';
        $htm.='<td>'.$Previous_Guarantor_Name.'</td>';
        $htm.='<td>'.$Employee_Name.'</td>';
        $htm.='<td>'.$Edit_date.'</td>';

    $htm.= "</tr>";

    $temp++;
}
$htm .= "<tr><td colspan='12' style='text-align: right;'><b> TOTAL EDITING ATTEMP : " . number_format($temp - 1) . "</td></tr>";
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