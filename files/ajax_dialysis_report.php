<?php include ("includes/connection.php");
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;
if (isset($_POST['filterAttendaceData'])) {
    $Start_Date = trim($_POST['Date_From']);
    $End_Date = trim($_POST['Date_To']);
    $output = "";
    $index = 1;
    $Patient_Number = 0;
    $query_result = mysqli_query($conn, "SELECT tbl_sponsor.Sponsor_ID,tbl_sponsor.Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    while ($rows = mysqli_fetch_array($query_result)) {
        $Sponsor_ID = $rows['Sponsor_ID'];
        $Guarantor_Name = $rows['Guarantor_Name'];
        $NuPatient = 0;
        $NumService = 0;
        $NewPatient = 0;
        $ReturnPatient = 0;
        $query = "SELECT
	tbl_patient_registration.Registration_ID,
            COUNT(tbl_patient_payment_item_list.Item_ID) as total,
            tbl_sponsor.Guarantor_Name,
            tbl_check_in.Type_Of_Check_In
        FROM
                `tbl_dialysis_details`,
            tbl_check_in,
            tbl_patient_payment_item_list,
            tbl_patient_registration,
            tbl_sponsor
        WHERE 
                tbl_patient_registration.Registration_ID = tbl_dialysis_details.Patient_reg AND
            tbl_patient_payment_item_list.Patient_Payment_ID = tbl_dialysis_details.Patient_Payment_ID AND
            tbl_sponsor.Sponsor_ID = tbl_patient_registration.Sponsor_ID AND
            tbl_check_in.Registration_ID = tbl_dialysis_details.Patient_reg AND 
            tbl_dialysis_details.Attendance_Date BETWEEN '$Start_Date' AND '$End_Date' AND
            tbl_sponsor.Sponsor_ID = '$Sponsor_ID'
        GROUP by tbl_patient_registration.Registration_ID";
        $query_result2 = mysqli_query($conn, $query) or die(mysqli_error($conn));
        while ($rows = mysqli_fetch_array($query_result2)) {
            $NuPatient++;
            $NumService = $NumService + (int)$rows['total'];
            if ($rows['Type_Of_Check_In'] == 'Afresh') {
                $NewPatient++;
            } else {
                $ReturnPatient++;
            }
        }
        $output.= '<tr>
                        <td>' . $index . '</td>
                        <td>' . $NuPatient . '</td>
                        <td>' . $NumService . '</td>
                        <td>' . $Guarantor_Name . '</td>
                        <td>' . $NewPatient . '</td>
                        <td>' . $ReturnPatient . '</td>';
        $output.= '</tr>';
        $index++;
    }
    echo ($output);
};
