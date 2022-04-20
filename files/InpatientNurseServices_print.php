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

$filter = " em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Service_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "' AND DATE(sg.Time_Given)=DATE(NOW()) ORDER BY sg.Time_Given DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = "  em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Service_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "' AND sg.Time_Given BETWEEN '$start_date' AND '$end_date' ORDER BY sg.Time_Given DESC";
}


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

$htm = "<center><img src='branchBanner/branchBanner1.png' width='100%' ></center>";
$htm.="<p align='center'><b>NURSE SERVICES " . $betweenDate . ""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";


$htm.= "<table width='100%' id='inpa_service'>";
$htm.= "<thead>"
        . "<tr>";
$htm.= "<td style='text-align:center;width:5%;'><b>SN</b></td>";
$htm.= "<td><b> Service Name</b> </td>";
$htm.= "<td widtd='11%'><b> Time Given </b></td>";
$htm.= "<td><b>Nurse Remarks </b></td>";
$htm.= "<td widtd='13%'><b> Disc. Status </b></td>";
$htm.= "<td><b> Discontinue Reason </b></td>";
$htm.= "<td><b> Given by </b></td>";



$select_services = "
	SELECT 
		it.Product_Name,
		em.Employee_Name,
		sg.Time_Given,
		sg.Discontinue_Status,
		sg.Discontinue_Reason,
		sg.Nurse_Remarks
			FROM 
				tbl_inpatient_services_given sg,
				tbl_items it,
				tbl_employee em
					WHERE 
						$filter ";



$select_testing_record = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
while ($service = mysqli_fetch_assoc($select_testing_record)) {
    $Product_Name = $service['Product_Name'];
    $Time_Given = $service['Time_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $Employee_Name = $service['Employee_Name'];
    $htm.= "<tr>";
    $htm.= "<td id='tdead'>" . $temp . "</td>";
    $htm.= "<td>" . $Product_Name . "</td>";
    $htm.= "<td>" . $Time_Given . "</td>";
    $htm.= "<td>" . $Nurse_Remarks . "</td>";
    $htm.= "<td>" . $Discontinue_Status . "</td>";
    $htm.= "<td>" . $Discontinue_Reason . "</td>";
    $htm.= "<td>" . $Employee_Name . "</td>";
   
    $temp++;
}
$htm.= "</table></center>";




include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
