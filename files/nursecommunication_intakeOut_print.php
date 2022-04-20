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

$filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND DATE(Date)=DATE(NOW())";

if (!empty($start_date) && !empty($end_date)) {
    $filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND Date BETWEEN '$start_date' AND '$end_date'";
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

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>INTAKE AND OUTPUT CHART " . $betweenDate . ""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";

//die("SELECT DATE(Date) AS dateRange FROM tbl_input_output_nursecommunication WHERE $filter GROUP BY DATE(Date)");
$getDates = mysqli_query($conn,"SELECT DATE(Date) AS dateRange FROM tbl_input_output_nursecommunication WHERE $filter GROUP BY DATE(Date)") or die(mysqli_error($conn));

while ($rowDate = mysqli_fetch_array($getDates)) {
    $thisDate = date('F jS Y l', strtotime($rowDate['dateRange']));

    $Transaction_Items_Qry = "SELECT Registration_ID,consultation_ID,i.employee_ID,Date,Day_Fluid,Amount_Fluid,Amount_Oral,Urine,Stool,Vomit,Remarks,Employee_Name FROM tbl_input_output_nursecommunication i  JOIN tbl_employee e ON i.employee_ID=e.Employee_ID WHERE  Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND DATE(Date)='" . $rowDate['dateRange'] . "' ";
$htm .= "<div style='text-transform: uppercase;margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: small;font-weight: bold;background-color:#ccc;padding:4px'>" . $thisDate . "<span style='float:right'> </span></div>";
    
$htm.= '<table style="width:100%" border="0" id="intake_out">';
$htm.= '
               <tr nobr="true">
                 <td style="width:5%;"><b>S/N</b></td>
                 <td><b>DATE & TIME</b></td>
                 <td><b>TYPE OF FLUID</b></td>
                 <td><b>AMOUNT I/V</b></td>
                 <td><b>AMOUNT-ORAL</b></td>
                 <td><b>URINE</b></td>
                 <td><b>STOOL</b></td>
                 <td><b>VOMIT</b></td>
		 <td><b>REMARKS</b></td>
                  <td><b>PREPARED BY</b></td>
               </tr>  
                ';
$totalIntake=0;
$totalOutake=0;
$select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $htm.= "<tr ><td>" . $temp . "</td>";
    $htm.= "<td>" . $row['Date'] . "</td>";
    $htm.= "<td>" . $row['Day_Fluid'] . "</td>";
    $htm.= "<td>" . $row['Amount_Fluid'] . "</td>";
    $htm.= "<td>" . $row['Amount_Oral'] . "</td>";
    $htm.= "<td>" . $row['Urine'] . "</td>";
    $htm.= "<td>" . $row['Stool'] . "</td>";
    $htm.= "<td>" . $row['Vomit'] . "</td>";//
    $htm.= "<td >" . $row['Remarks'] . "</td>";
    $htm.= "<td >" . $row['Employee_Name'] . "</td>";
    $htm.= "</tr>";
    
   
    
    $totalIntake +=((int)$row['Amount_Fluid']+(int)$row['Amount_Oral']);
    $totalOutake +=((int)$row['Urine']+(int)$row['Stool']+(int)$row['Vomit']);
    
    $temp++;
}
$htm.= "<tr>
              <td colspan='4' style='text-align:center'>TOTAL INTAKE IN 24 HRS:<b>$totalIntake</b></td>
              <td colspan='3' style='text-align:center'>TOTAL OUTAKE IN 24 HRS:<b>$totalOutake</b></td>
              <td colspan='3' style='text-align:center'>BALANCE IN 24 HRS:<b>".($totalIntake-$totalOutake)."</b></td>
            <tr/>";
$htm.= "</table>";
}






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
