<?php
 include("./includes/connection.php");
 if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
}
//check if patient is on admited patient list
$sql_check_if_in_patient_list_result=mysqli_query($conn,"SELECT Admit_Status FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID'
        AND ToBe_Admitted = 'yes' AND Admit_Status = 'not admitted'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_if_in_patient_list_result)>0){
     die("admit_list");
}

//check if doctor suggest to admitted or orleady admitted
$check_if_doctor_suggest_admit_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_admission ad INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted'") or die(mysqli_error($conn));
if(mysqli_num_rows($check_if_doctor_suggest_admit_result)>0){
    die(mysqli_fetch_assoc($check_if_doctor_suggest_admit_result)['Hospital_Ward_Name']);
}
echo "free_to_admit";
?>