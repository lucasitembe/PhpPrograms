<?php

@session_start();
include("./includes/connection.php");
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())'; 

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$employee_id = filter_input(INPUT_GET, 'employee_id');
$Patient_number = filter_input(INPUT_GET, 'Patient_number'); //
$patStatus = filter_input(INPUT_GET, 'patStatus');
$patDirection = filter_input(INPUT_GET, 'patDirection');

$filter = " DATE(pl.Transaction_Date_And_Time) = DATE(NOW()) ";
if(empty($employee_id)){
  $filter .= " AND pl.Consultant_ID=0 ";
}if(empty($patDirection)){
  $filter .= " AND pl.Patient_Direction = 'Direct To Doctor' ";  
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  pl.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if ($patDirection == 'Direct To Doctor' || $patDirection == 'Direct To Doctor Via Nurse Station') {
    $filter .= " AND  pl.Consultant_ID='$employee_id' ";
} else if ($patDirection == 'Direct To Clinic' || $patDirection == 'Direct To Clinic Via Nurse Station') {
     $filter .= " AND  pl.Clinic_ID IN (SELECT Clinic_ID FROM tbl_clinic_employee WHERE Employee_ID='$employee_id') ";
}

if (!empty($patDirection)) {
    $filter .="   AND pl.Patient_Direction = '$patDirection' ";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($patStatus)) {
    $filter .="  AND pl.Process_Status = '$patStatus'";
}else{
   $filter .="  AND pl.Process_Status IN  ('no show','signedoff')"; 
}

if (!empty($Patient_number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_number'";
}

//echo $filter;exit;

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

echo '<center><table width ="100%" id="myPatients">';
echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
                <th><b>SPONSOR</b></th>
                    <th><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                            <th><b>PHONE NUMBER</b></th>
                                <th><b>MEMBER NUMBER</b></th>
                                <th><b>TRANS DATE</b></th>
                                <th><b>STATUS</b></th>
				<th width='10%'><b>ACTION</b></th>
				</tr>
                                </thead>";

$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];

$sql = "
                SELECT n.emergency,pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name,pl.Process_Status
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                LEFT JOIN tbl_nurse n ON n.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                
                WHERE 
                      $filter
                GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time
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

    $select = "<select class='changepatientstatus'onchange='changestatus(" . $row['Patient_Payment_ID'] . ",this.value)' id='" . $row['Patient_Payment_ID'] . "'>
                           <option>Change</option>
                        ";

    if ($row['Process_Status'] == 'no show') {
        $select .="<option value='not served'>Show</option>";
    } else if ($row['Process_Status'] == 'signedoff') {
        $select .="<option  value='served'>Not Signed-Off</option>";
    }

    $select .="</select>";

    echo "<tr ><td >$startspan" . $n . "$endspan</td><td>$startspan" . ucwords(strtolower($row['Patient_Name'])) . "$endspan</td>";
    echo "<td>$startspan" . $row['Guarantor_Name'] . "$endspan</td>";
    echo "<td>$startspan" . $age . "$endspan</td>";
    echo "<td>$startspan" . $row['Gender'] . "$endspan</td>";
    echo "<td>$startspan" . $row['Phone_Number'] . "$endspan</td>";
    echo "<td>$startspan" . $row['Member_Number'] . "$endspan</td>";
    echo "<td>$startspan" . $row['Transaction_Date_And_Time'] . "$endspan</td>";
    echo "<td>$startspan" . strtoupper($row['Process_Status']) . "$endspan</td>";
    echo "<td>$startspan" . $select . "$endspan</td>";

    echo "</tr>";

    $n++;
}


echo '</table></center>';
