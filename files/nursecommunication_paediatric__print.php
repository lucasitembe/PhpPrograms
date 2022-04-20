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

$filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $_GET['consultation_ID'] . "' AND DATE(date_time)=DATE(NOW()) ORDER BY date_time DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND date_time BETWEEN '$start_date' AND '$end_date' ORDER BY date_time DESC";
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
$htm.="<p align='center'><b>PAEDIATRIC WARD-OBSERVATION CHART " . $betweenDate . ""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";


$htm.= '<center><table width =100% border="0" id="nurse_mulnitrution">';
$htm.= '<thead>
                  <tr>
                    <td style="width:5%;"><b>S/N</b></td>
                    <td><b>DATE &amp; TIME</b></td>
                    <td><b>TEMP(c)</b></td>
                    <td><b>PR</b></td>
                    <td><b>RESP(bpm)</b></td>
                    <td><b>SO2</b></td>
                    <td><b>PATIENT PROBLEM</b></td>
                    <td><b>NURSING DIAGNOSIS</b></td>
                    <td><b>EXPECTED OUTCOME</b></td>
                    <td><b>NURSING IMPLEMENTATION</b></td>               
                    <td><b>OUTCOME</b></td>
                    <td><b>INVESTIGATION</b></td>
                    <td><b>REMARKS</b></td>
                 </tr>
                 <tr><td colspan="13"><hr width="100%"/></td><tr/>
                </thead>  
                ';

$Transaction_Items_Qry = "SELECT * FROM tbl_nursecommunication_paediatric WHERE $filter";


$select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $htm.= "<tr ><td id='thead'>" . $temp . ".</td>";
    $htm.= "<td>" . $row['date_time'] . "</td>";
    $htm.= "<td>" . $row['temp'] . "</td>";
    $htm.= "<td>" . $row['Pr'] . "</td>";
    $htm.= "<td>" . $row['Resp'] . "</td>";
    $htm.= "<td>" . $row['So'] . "</td>";
    $htm.= "<td>" . $row['patient_Problem'] . "</td>";
    $htm.= "<td>" . $row['nursing_diagnosis'] . "</td>";
    $htm.= "<td>" . $row['expected_outcome'] . "</td>";
    $htm.= "<td>" . $row['implementation'] . "</td>";
    $htm.= "<td>" . $row['outcome'] . "</td>";
    $htm.= "<td>" . $row['investigation'] . "</td>";
    $htm.= "<td>" . $row['Remarks'] . "</td>";
    $htm.= "</tr>";

    $htm .=" <tr><td colspan='13'><hr width='100%'/></td><tr/>";
    $temp++;
}
$htm.= "</table></center>";




include("MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4-L');

$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
