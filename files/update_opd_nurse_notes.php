<?php 
session_start();
include("./includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Refugees=$_POST['Refugees'];
$current_nurse=$Employee_ID;
$duty_nurse=$_POST['duty_nurse'];
$duty_ward=$_POST['duty_ward'];
$Doctor_round=$_POST['Doctor_round'];
$select_round=$_POST['select_round'];
$current_inpatient=$_POST['current_inpatient'];
$received_inpatient=$_POST['received_inpatient'];
$discharged_inpatient=$_POST['discharged_inpatient'];
$death_inpatient=$_POST['death_inpatient'];
$debt_inpatient=$_POST['debt_inpatient'];
$Abscondees=$_POST['Abscondees'];
$transferIn=$_POST['transferIn'];
$transferOut=$_POST['transferIn'];
$lodgers=$_POST['lodgers'];
$major_round=$_POST['major_round'];
$nurse_notes = str_replace("'", "&#39;",$_POST['nurse_notes']);
$serious_inpatient=$_POST['serious_inpatient'];
$Ward_Type=$_POST['Ward_Type'];
$Prisoner=$_POST['Prisoner'];


$duty_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT duty_ID FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$duty_ward' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['duty_ID'];

    if($duty_ID > 0){
        $Insert_notes = mysqli_query($conn, "INSERT INTO tbl_opd_nurse_duty_nurse(duty_ID, Employee_ID, Nurse_Notes, Updated_Date_Time) VALUES('$duty_ID', '$Employee_ID', '$nurse_notes', NOW())");
        if($Insert_notes){
            echo 200;
        }else{
            echo 201;
        }
    }else{
        $save_nurse_handler = mysqli_query($conn, "INSERT INTO tbl_opd_nurse_duties (Refugees,current_nurse,duty_nurse,duty_ward,Doctor_round,select_round,current_inpatient,received_inpatient,discharged_inpatient,death_inpatient,nurse_notes,Abscondees,transferIn,transferOut,lodgers,major_round,debt_inpatient,serious_inpatient, Ward_Type,Prisoner, Process_Status) VALUES ('$Refugees','$current_nurse','$duty_nurse','$duty_ward','$Doctor_round','$select_round','$current_inpatient','$received_inpatient','$discharged_inpatient','$death_inpatient','$nurse_notes','$Abscondees','$transferIn','$transferOut','$lodgers','$major_round','$debt_inpatient','$serious_inpatient', '$Ward_Type', '$Prisoner', 'pending')");
        
        if($save_nurse_handler){
            $duty_ID = mysqli_insert_id($conn);
            $Insert_notes = mysqli_query($conn, "INSERT INTO tbl_opd_nurse_duty_nurse(duty_ID, Employee_ID, Nurse_Notes, Updated_Date_Time) VALUES('$duty_ID', '$Employee_ID', '$nurse_notes', NOW())");
            if($Insert_notes){
                echo 200;
            }else{
                echo 201;
            }
        }
    }
    

mysqli_close($conn);

?>