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

//$filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' ORDER BY date DESC";

//remove consultation id
$filter = " Registration_ID='$Registration_ID' ORDER BY date DESC";


if (!empty($start_date) && !empty($end_date)) {
  //  $filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";


    //remove consultation id
    $filter = " Registration_ID='$Registration_ID' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";

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
$htm.="<p align='center'><b>OBSERVATION CHART " . $betweenDate . ""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";


$htm.= '<center><table width =100% border="0" id="nurse_obsv">';
$htm.= '<thead>
               <tr>
                <td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE &amp; TIME</b></td>
                <td><b>TEMP(c)</b></td>
                <td><b>BP 1/2hr (mmhg)</b></td>
                <td><b>Pulse 1/2hr (bpm)</b></td>
                <td><b>Resp(bpm)</b></td>
                <td><b>FBG</b></td>
                <td><b>Drainage (ccc)</b></td>
                <td><b>rbg</b></td>

                <td><b>Oxygen Saturation</b></td>

                <td><b>Blood Transfusion</b></td>
                <td><b>Body Weight</b></td>
                <td><b>Prepared By</b></td>
              </tr>
              <tr>
                <td colspan="15"><hr width="100%"/></td>
              <tr/>
             </thead>
                ';

$Transaction_Items_Qry = "SELECT body_weight,blood_transfusion,Registration_ID,consultation_ID,n.employee_ID,date,Blood_Pressure,Pulse_Blood,
    Temperature,oxygen_saturation,Resp_Bpressure,Fluid_Drug,fbg,Drainage,rbg,Urine,Employee_Name
         FROM tbl_nursecommunication_observation n JOIN tbl_employee e ON e.Employee_ID=n.employee_ID WHERE $filter";

//die($Transaction_Items_Qry);
$select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $htm.= "<tr >";
    $htm.= "<td id='thead'>" . $temp . ".</td>";
    $htm.= "<td>" . $row['date'] . "</td>";
    $htm.= "<td>" . $row['Temperature'] . "</td>";
    $htm.= "<td>" . $row['Blood_Pressure'] . "</td>";
    $htm.= "<td>" . $row['Pulse_Blood'] . "</td>";
    $htm.= "<td>" . $row['Resp_Bpressure'] . "</td>";
//    $htm.= "<td>" . $row['Fluid_Drug'] . "</td>";
    $htm.= "<td>" . $row['fbg'] . "</td>";
    $htm.= "<td>" . $row['Drainage'] . "</td>";
    $htm.= "<td>" . $row['rbg'] . "</td>";
//    $htm.= "<td>" . $row['Urine'] . "</td>";
    $htm.= "<td>" . $row['oxygen_saturation'] . "</td>";

    $htm.= "<td>" . $row['blood_transfusion']. "</td>";
    $htm.= "<td>" . $row['body_weight']. "</td>";
    $htm.= "<td>" . $row['Employee_Name'] . "</td>";
    $htm.= "</tr>
            <tr>
                <td colspan='15'><hr width='100%'/></td>
            <tr/>";
    $temp++;
}

$htm.= "</table></center>";



//
include("MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4-L');

$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
