<?php
@session_start();
include("./includes/connection.php");

$filter_date = "";
$filter_clinic = "";
$filter_doctor = "";

if (isset($_GET['fromDate']) && $_GET['fromDate'] != "" && isset($_GET['toDate']) && $_GET['toDate'] != "") {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $filter_date = " a.date_time BETWEEN '$fromDate' AND '$toDate'";
} else {
    $fromDate = '';
    $toDate = '';
}

if (isset($_GET['clinic']) && $_GET['clinic'] != "") {
    $clinic = $_GET['clinic'];
    $filter_clinic = " AND a.Clinic = '$clinic'"; 
} else {
    $clinic = "";
}

if(isset($_GET['doctor']) && $_GET['doctor'] != "") {
    $doctor = $_GET['doctor'];
    $filter_doctor = " AND a.doctor = '$doctor'";
} else {
    $doctor = "";
}

$htm =  "<center>
            <table width ='100%' height = '30px'>
                <tr style='text-align: center;'>
                <td colspan='5' style='text-align: center;'><b>APPOINTMENT REPORT</b></td>
                </tr>
                <tr style='text-align: center;'>
                <td colspan='5' style='text-align: center;'><b>FROM ".$fromDate." TO ".$toDate."</b></td>
                </tr>
            </table>
        </center>";
$htm .= "
        <center>
            <table width=100% id='myList'>
                <thead>
                    <tr>
                        <td width=10% style='text-align: center;'><b>SN</b></td>
                        <td width=25%><b>DOCTOR NAME</b></td>
                        <td width=30% style='text-align: center;'><b>DOCTOR PHONE NUMBER</b></td>
                        <td width=35% style='text-align: center;'><b>NUMBER OF APPOINTMENTS</b></td>
                    </tr>
                </thead>";

$sn = 1;
$total = 0;
$select_appointment_details = mysqli_query($conn, "SELECT DISTINCT(doctor) FROM tbl_appointment a WHERE $filter_date $filter_clinic $filter_doctor GROUP BY doctor");
$no_of_doctor_appointment = 1;
$Employee_Name = "";
$Phone_Number = "";

while($row_doctor = mysqli_fetch_array($select_appointment_details)) {
    $doctor = $row_doctor['doctor'];
    if($doctor != 0) {
        $select_appointment_details2 = mysqli_query($conn, "SELECT a.doctor,e.Employee_Name,e.Phone_Number FROM tbl_appointment a, tbl_employee e WHERE $filter_date $filter_clinic AND doctor = '$doctor' AND a.doctor=e.Employee_ID");

        // $select_doctor_details = mysqli_query($conn, "SELECT Employee_Name,Phone_Number FROM tbl_employee WHERE Employee_ID='$doctor'");
        // while($appoinment = mysqli_fetch_array($select_doctor_details)) {
        //     $Employee_Name = $appoinment['Employee_Name'];
        //     $Phone_Number = $appoinment['Phone_Number'];
        // }  
        $Employee_Name = mysqli_fetch_assoc($select_appointment_details2)['Employee_Name'];  
        $Phone_Number = mysqli_fetch_assoc($select_appointment_details2)['Phone_Number'];  
        
        $no_of_doctor_appointment = mysqli_num_rows($select_appointment_details2);
        $total = $total + $no_of_doctor_appointment;
        
        $htm .= "
            <tr>
                <td style='text-align: center;'>".$sn++."</td>
                <td>".$Employee_Name."</td>
                <td>".$Phone_Number."</td>
                <td style='text-align: center;'>".$no_of_doctor_appointment."</td>
            </tr>
        ";
    }
    

}
$htm .= "
            <tr>
                <td style='text-align: right;' colspan='3'><b>Total Appointment:</b></td>
                <td style='text-align: center;'>".$total."</td>
            </tr>";

$htm .= "
            </table>
        </center>
";

header('Content-Type:application/xls');
header("Content-Disposition:attachment;filename=patient_appointment_report.xls");
echo $htm;
mysqli_close($conn);
?>


