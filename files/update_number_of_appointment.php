<?php
  include("./includes/connection.php");
  
  
//  Clinic_ID:Clinic_ID,number_of_appointment:number_of_appointment
     if(isset($_POST['Clinic_ID'])){
	echo $Clinic_ID = $_POST['Clinic_ID'];
    }else{
	$Clinic_ID = '';
    }
     if(isset($_POST['number_of_appointment'])){
	echo $number_of_appointment = $_POST['number_of_appointment'];
    }else{
	$number_of_appointment = '';
    }
  
   $check_if_exist = mysqli_query($conn,"SELECT Clinic_ID FROM tbl_number_of_clinic WHERE Clinic_ID='$Clinic_ID'");
   
     if(mysqli_num_rows($check_if_exist) > 0){
         
         $update_clinic_data = mysqli_query($conn,"UPDATE tbl_number_of_clinic SET total_number='$number_of_appointment' WHERE Clinic_ID='$Clinic_ID'");
     }else{
         
         $insert_clinic_data = mysqli_query($conn,"INSERT INTO tbl_number_of_clinic(Clinic_ID,total_number) VALUES('$Clinic_ID','$number_of_appointment')");
     }
   
 