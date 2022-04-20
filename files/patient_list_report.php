<?php

@session_start();
include("./includes/connection.php");
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())'; 

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Patient_number = filter_input(INPUT_GET, 'Patient_number'); //
$Old_patient_number = filter_input(INPUT_GET, 'Search_Old_Patient_number');

$filter = " DATE(pr.Registration_Date_And_Time) = DATE(NOW()) ";

$filterdate= "<span><b>TODAY ".DATE('Y-m-d')."<span><b>";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  pr.Registration_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";

    $filterdate= "<span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</b>";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_number'";
}

if (!empty($Old_patient_number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Old_patient_number'";
}

//$htm .= $filter;exit;

$n = 1;

//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$htm = "<table width ='100%' border='0' class='nobordertable'>
            <tr><td style='text-align:center'>
            <img src='./branchBanner/branchBanner.png' width='100%'>
            </td></tr>
            <tr><td style='text-align: center;'><span><b>REGISTERED PATIENT LIST REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'>".$filterdate."</td></tr>
                      </table>";


$htm .= '<center><table width ="100%" id="myPatients">';
$htm .= " <thead>
       <tr ><th style='width:5%;'>SN</th>
            <th><b>PATIENT NAME</b></th>
            <th><b>OLD REG. #</b></th>
            <th><b>NEW REG. #</b></th>
            <th><b>SPONSOR</b></th>
            <th><b>AGE</b></th>
            <th><b>GENDER</b></th>
            <th><b>PHONE NUMBER</b></th>
            <th><b>MEMBER NUMBER</b></th>
            <th><b>DATE REGISTERED</b></th>
         </tr>
       </thead>";

$sql = "
                SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,sp.Guarantor_Name,pr.Registration_Date_And_Time
                FROM  tbl_patient_registration pr 
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                WHERE 
                  $filter
                ORDER BY pr.Old_Registration_Number
            "; 

$select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));


while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $style = "";
    $startspan = "";
    $endspan = "";

    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";

    $htm .= "<tr ><td >$startspan" . $n . "$endspan</td>
    <td>$startspan" . ucwords(strtolower($row['Patient_Name'])) . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Old_Registration_Number'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Registration_ID'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Guarantor_Name'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $age . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Gender'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Phone_Number'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Member_Number'] . "$endspan</td>";
    $htm .= "<td>$startspan" . $row['Registration_Date_And_Time'] . "$endspan</td>";

    $htm .= "</tr>";

    $n++;
}
$htm .= '</table></center>';

include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
