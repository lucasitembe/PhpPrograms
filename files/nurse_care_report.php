<?php

include("./includes/connection.php");

$temp = 1;
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
}
if (isset($_GET['end'])) {
    $end_date = $_GET['end'];
} if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

$filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "'";


if (empty($start_date) && empty($end_date)) {
    $betweenDate = "<br/><br/>Today " . date('Y-m-d');
} else {
    $betweenDate = "<br/><br/>FROM</b> " . $start_date . " <b>TO</b> " . $end_date;
}

$select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_patien_details);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_patien_details)) {
        $Member_Number = $row['Member_Number'];
        $Patient_Name = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Gender = $row['Gender'];
        $Sponsor = $row['Guarantor_Name'];
        $DOB = $row['Date_Of_Birth'];
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}
$age = date_diff(date_create($DOB), date_create('today'))->y;
$dateRages = array();
$htm = "<center><img src='branchBanner/branchBanner1.png' width='100%' ></center>";
$htm.="<p align='center'><b>NURSE CARE REPORT</b>"
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";

$getDateMed = mysqli_query($conn,"SELECT DATE(Time_Given) AS dateRange FROM tbl_inpatient_medicines_given WHERE $filter GROUP BY DATE(Time_Given)") or die(mysqli_error($conn));
while ($rowDateMed = mysqli_fetch_array($getDateMed)) {
    $dateRages[] = $rowDateMed['dateRange'];
}

$getDateServ = mysqli_query($conn,"SELECT DATE(Time_Given) AS dateRange FROM tbl_inpatient_services_given sg WHERE $filter GROUP BY DATE(Time_Given)") or die(mysqli_error($conn));
while ($rowDateServ = mysqli_fetch_array($getDateServ)) {
    $dateRages[] = $rowDateServ['dateRange'];
}

$dateRages = array_unique($dateRages);

foreach ($dateRages as $value) {

    $thisDate = date('F jS Y l', strtotime($value));

    $medication_qry = "SELECT Product_Name,Time_Given,Amount_Given,Employee_Name FROM tbl_inpatient_medicines_given sg JOIN tbl_items it ON it.Item_ID = sg.Item_ID  JOIN tbl_employee e ON sg.employee_ID=e.Employee_ID WHERE  Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND DATE(Time_Given)='" . $value . "' ";
    $htm .= "<div style='text-transform: uppercase;margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: small;font-weight: bold;background-color:#ccc;padding:4px'>" . $thisDate . "<span style='float:right'> </span></div>";

    $htm.= '<table style="width:100%" border="0" id="intake_out">';
    $htm.= '
               <tr nobr="true">
                 <td style="width:20%"><b>DATE & TIME</b></td>
                 <td><b>PARTICULAR</b></td>
		 <td style="width:5%;text-align:center"><b>QTY</b></td>
                 <td style="width:22%;"><b>ISSUED BY</b></td>
               </tr> 
                ';
    
    $select_medication = mysqli_query($conn,$medication_qry) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_medication)) {
        $htm.= "<tr><td>" . $row['Time_Given'] . "</td>";
        $htm.= "<td>" . $row['Product_Name'] . "</td>";
        $htm.= "<td style='text-align:center'>" . $row['Amount_Given'] . "</td>";
        $htm.= "<td >" . $row['Employee_Name'] . "</td>";
        $htm.= "</tr>";
    }
    
     $Qry_Service = "SELECT Product_Name,Time_Given,Employee_Name FROM tbl_inpatient_services_given sg JOIN tbl_items it ON it.Item_ID = sg.Service_ID  JOIN tbl_employee e ON sg.employee_ID=e.Employee_ID WHERE  Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND DATE(Time_Given)='" . $value . "' ";
   
   $select__services = mysqli_query($conn,$Qry_Service) or die(mysqli_error($conn));
    while ($rowSevices = mysqli_fetch_array($select__services)) {
        $htm.= "<tr><td>" . $rowSevices['Time_Given'] . "</td>";
        $htm.= "<td>" . $rowSevices['Product_Name'] . "</td>";
        $htm.= "<td style='text-align:center'>1</td>";
        $htm.= "<td >" . $rowSevices['Employee_Name'] . "</td>";
        $htm.= "</tr>";
    }
    $htm.= "</table>";
}






include("MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
