
<?php
@session_start();
include("./includes/connection.php");

//$filter = "   AND Admission_Status='Admitted'  AND DATE(ad.Admission_Date_Time)=DATE(NOW())";

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$clinic = filter_input(INPUT_GET, 'clinic');

//echo $filter;
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
//end

echo '<center><table width ="100%" id="patientslist">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th style='text-align:left'><b>DOCTOR'S NAME</b></th>
                <th><b>NUMBER OF PATIENTS</b></th>
             </tr>
         </thead>";

$select_doctor_query = "SELECT DISTINCT(emp.Employee_ID),emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp INNER JOIN tbl_clinic_employee ce ON ce.Employee_ID=emp.Employee_ID WHERE Employee_Type='Doctor' AND Account_Status='active' ORDER BY Employee_Name ASC";


$select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));

$empSN = 0;
while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
    $employeeID = $select_doctor_row['Employee_ID'];
    $employeeName = $select_doctor_row['Employee_Name'];

    $filter = " DATE(ch.cons_hist_Date)=DATE(NOW())";

    if (isset($_GET['Sponsor'])) {


        if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
            $filter = "  ch.cons_hist_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
        }

        if (!empty($Sponsor) && $Sponsor != 'All') {
            $filter .="  AND pr.Sponsor_ID=$Sponsor";
        }

//echo $ward;exit;
        if (!empty($clinic) && $clinic != 'All') {
            $filter .= " AND pl.Clinic_ID IN (SELECT Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Clinic_ID='$clinic' AND ce.Employee_ID =" . $employeeID . ")";
        }
    }
   
    $sql = "SELECT COUNT(c.Registration_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID 
                                       FROM tbl_consultation_history ch 
                                       LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                                       JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
                                       JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                                       INNER JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
                                       WHERE $filter AND ch.employee_ID='$employeeID'
                                       ";
    //echo($sql).'<br/>';
    $result_patient_no = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    $patient_no_number = mysqli_fetch_assoc($result_patient_no)['numberOfPatients'];

    $empSN ++;
    echo "<tr><td>" . ($empSN) . "</td>";
    echo "<td style='text-align:left'><a href='doctorsclinicperformancedetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor=$Sponsor&clinic=$clinic'>" . $employeeName . "</b></td>";
    echo "<td style='text-align:center'><a href='doctorsclinicperformancedetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor=$Sponsor&clinic=$clinic'>" . number_format($patient_no_number) . "</b></td>";
    echo "</tr>";
}
?></table></center>