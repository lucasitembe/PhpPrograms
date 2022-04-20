<?php 
session_start();
include("./includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Refugees=$_GET['Refugees'];
$current_nurse=$Employee_ID;
$duty_nurse=$_GET['duty_nurse'];
$duty_ward=$_GET['duty_ward'];
$Doctor_round=$_GET['Doctor_round'];
$select_round=$_GET['select_round'];
$current_inpatient=$_GET['current_inpatient'];
$received_inpatient=$_GET['received_inpatient'];
$discharged_inpatient=$_GET['discharged_inpatient'];
$death_inpatient=$_GET['death_inpatient'];
$debt_inpatient=$_GET['debt_inpatient'];
$Abscondees=$_GET['Abscondees'];
$transferIn=$_GET['transferIn'];
$transferOut=$_GET['transferIn'];
$lodgers=$_GET['lodgers'];
$major_round=$_GET['major_round'];
// $general_notes = str_replace("'", "&#39;",$_GET['general_notes']);
$serious_inpatient=$_GET['serious_inpatient'];
$Ward_Type=$_GET['Ward_Type'];


        $duty_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT duty_ID FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$duty_ward' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['duty_ID'];

            if($duty_ID > 0){
                $Update_Nurse = mysqli_query($conn, "UPDATE tbl_opd_nurse_duties SET duty_nurse = '$duty_nurse', major_round = '$major_round', Employee_Submitted = '$current_nurse', Process_Status = 'Submitted', Saved_Date_Time = NOW() WHERE duty_ID = '$duty_ID'");
            }

            if ($Update_Nurse){
                echo 200;
            }else{
                echo 201;
            }

mysqli_close($conn);

?>