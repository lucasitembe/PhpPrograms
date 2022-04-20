<?php 

include("../includes/connection.php");

include("../handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$patient_id = mysqli_real_escape_string($conn, trim($_GET['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_GET['admission_id']));

$x = mysqli_real_escape_string($conn, trim($_GET['x']));

$y = mysqli_real_escape_string($conn, trim($_GET['y']));

$baby_no = mysqli_real_escape_string($conn, trim($_GET['baby_no']));

$time = mysqli_real_escape_string($conn, trim($_GET['time']));

$data = array();

$insert_fetal_heart_rate = "INSERT INTO tbl_fetal_heart_rate_cache (patient_id, admission_id, x, y, baby_no, date_time, time_hours, Employee_ID)
                            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_fetal_heart_rate);

mysqli_stmt_bind_param($insert_stmt, "iiidisi", $patient_id, $admission_id, $x, $y, $baby_no, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) {

    list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

    array_push($data, $y);

    array_push($data, $x);

    array_push($data, $time);

    array_push($data, $baby_no);

    array_push($data, $Patient_Name);

    echo json_encode($data);
    
}else{

    echo "Data insertion failure";

}
