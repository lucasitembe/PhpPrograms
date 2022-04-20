<?php

include '../includes/connection.php';
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$testNameResults = filter_input(INPUT_GET, 'testNameResults');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$currEmployeeID = filter_input(INPUT_GET, 'currEmployeeID');
$check_in_type = filter_input(INPUT_GET, 'check_in_type');

$filter = "";
//$filter = "   AND DATE(il.Transaction_Date_And_Time)=DATE(NOW())";
$todayqr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
$today = $todayqr; //date('Y-m-d H:m:s');

$dataRange = returnBetweenDates($today, $today);

$daterange= date('d, M Y');


if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
     $daterange= 'FROM '.date('d, M Y', strtotime($Date_From)).' TO '.date('d, M Y', strtotime($Date_To));
    //$filter = "  AND  il.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' ";
    $dataRange = returnBetweenDates($Date_From, $Date_To);
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}
//$data .= $ward;exit;
if (!empty($currEmployeeID) && $currEmployeeID != 'All') {
    $filter .= " AND il.Consultant_ID  = $currEmployeeID";
}


if (!empty($testNameResults)) {
    if ($testNameResults == 'ct-scan') {
        $filter .="  AND il.Item_ID IN (SELECT Item_ID FROM tbl_items WHERE Ct_Scan_Item = 'yes')";
    } else {
        $filter .="  AND il.Item_ID='$testNameResults'";
    }
}

//$data .= $filter;
//exit();
//GET BRANCH ID

$dataRange = returnBetweenDates($Date_From, $Date_To);
$totalPPP = 0;

//date manipulation to get the patient age
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$testName= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$testNameResults'"))['Product_Name'];
$doctorName= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$currEmployeeID'"))['Employee_Name'];

//$data .= $filter;
//exit();

//GET BRANCH ID

$data= '<img src="branchBanner/branchBanner1.png" width="100%" >';

$data .='<div style="padding:5px; width:99%;font-size:small;border:0px solid #000;text-align:center  ">
            <b align="center">
                DR. '.$doctorName.' '. strtoupper($check_in_type).' TEST DETAILS REPORT</b>
              <p>
                 <b align="center"> '.$daterange.'</b>
               </p>  
               <p>
                 <b align="center"> '.$testName.'</b>
               </p> 
            
        </div>';

foreach ($dataRange as $value) {
    $thisDate = date('d, M y', strtotime($value)) . '';

    $select_Filtered_Patients = "SELECT pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,i.Product_Name,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,pc.Billing_Type,il.Transaction_Type
                                            FROM tbl_item_list_cache as il INNER JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            WHERE Check_In_Type = '$check_in_type' AND DATE(il.Transaction_Date_And_Time)='$value'  $filter ORDER BY Transaction_Date_And_Time ASC ";
//$data .= $select_Filtered_Patients;exit;
    $result = mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        $data .= "<div style='margin:10px 0px 10px 0px;width:99.4%;text-align:left;font-family: times;font-size: large;font-weight: bold;background-color:#ccc;padding:4px'>" . $thisDate . "<span style='float:right'> </span></div>";

        $data .= '<center>
            <table width =100% border=0 class="display" id="doctosTestResult">
                <tr>
                    <th><b>SN</b></th>
                    <th style="width:38%;"><b>PATIENT</b></th>
                    <th style="width:5%;text-align:center"><b>REG#</b></th>
                    <th style="width:17%;text-align:center"><b>SPONSOR</b></th>
                    <th style="width:18%;text-align:center"><b>AGE</b></th>
                    <th style="width:10%;text-align:center"><b>GENDER</b></th>
                    <th style="width:18%;text-align:center"><b>TEST NAME</b></th>
                    <th style="text-align:center"><b>TRANS DATE</b></th>
                </tr>';

        $temp = 1;
        while ($row = mysqli_fetch_array($result)) {
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);

            $diff = $date1->diff($date2);

            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

            $data .= "<tr><td>" . $temp . "</td>
              <td>" . $row['Patient_Name'] . "</td>";

            $data .= "<td style='text-align:center'>" . $row['registration_number'] . "</td>";
            $data .= "<td style='text-align:center'>" . $row['Sponsor_Name'] . "</td>";
            $data .= "<td style='text-align:center'>" . $age . "</td>";
            $data .= "<td style='text-align:center'>" . $row['Gender'] . "</td>";
            $data .= "<td style='text-align:center'>" . $row['Product_Name'] . "</td>";
            $data .= "<td style='text-align:center'>" . $row['Transaction_Date_And_Time'] . "</td>";
            $data .= '</tr>';
            $temp++;
            $totalPPP=$totalPPP+1;
        }

        $data .= "</table><br/>";
    }
}

$data .="<p style='text-align:right;font-size:larger;'><b>Total Patient(s)</b> ".number_format($totalPPP)."</p>";


include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4-L');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();

function returnBetweenDates($startDate, $endDate) {
    $startStamp = strtotime($startDate);
    $endStamp = strtotime($endDate);

    if ($endStamp > $startStamp) {
        while ($endStamp >= $startStamp) {

            $dateArr[] = date('Y-m-d', $startStamp);

            $startStamp = strtotime(' +1 day ', $startStamp);
        }
        return $dateArr;
    } else {
        return $startDate;
    }
}
