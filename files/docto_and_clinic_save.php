<?php
include("./includes/connection.php");
if (isset($_POST['timepicker'])) {
  $timepicker = $_POST['timepicker'];
   $timepicker  = date("g:i:s", strtotime($timepicker));
} else {
    $timepicker = "";
}
if (isset($_POST['basicExample'])) {
    $basicExample = $_POST['basicExample'];
    $basicExample  =  date("g:i:s", strtotime($basicExample));
} else {
    $basicExample= "";
}

if (isset($_POST['fromDate'])) {
    $fromDate = $_POST['fromDate'];
} else {
    $fromDate= "";
}
$fromDate = date("Y-m-d", strtotime($fromDate));
if (isset($_POST['selected_clinic_or_doctor_id'])) {
    $selected_clinic_or_doctor_id = $_POST['selected_clinic_or_doctor_id'];
} else {
    $selected_clinic_or_doctor_id= "";
}
if (isset($_POST['total_number'])) {
    $total_number = $_POST['total_number'];
} else {
    $total_number= "";
}
if (isset($_POST['status_doctors_clinic'])) {
    $status_doctors_clinic = $_POST['status_doctors_clinic'];
} else {
    $status_doctors_clinic= "";
}
//if (isset($_POST['basicExample'])) {
//    $basicExample = $_POST['basicExample'];
//} else {
//    $basicExample= "";
//}

 if($status_doctors_clinic == "clinics"){
     
     $App_ID_new="";
$time_from ="";
$time_to ="";
   $check_if_exist = mysqli_query($conn,"SELECT App_ID FROM tbl_date_appointment WHERE Date='$fromDate' AND Clinic_ID='$selected_clinic_or_doctor_id' AND Status='$status_doctors_clinic'");
   
     if(mysqli_num_rows($check_if_exist) > 0){
         $App_ID=0;
         while($row = mysqli_fetch_assoc($check_if_exist)){
                      $App_ID_new=$row['App_ID'];
                      
//              $fetch_time = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new'"); 
//             
//              while($time=mysqli_fetch_assoc($fetch_time)){
//                              echo  $time_from =$time['time_from'];
//                              echo  $time_to =$time['time_to'];
//                  
//              }
             
         }
                  
         echo $basicExample;
         echo  $timepicker;
         $check_if_time_exist = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new' AND time_from='$basicExample' AND time_to='$timepicker'");
         
        echo "hapa".$number = mysqli_num_rows($check_if_time_exist);
          if(mysqli_num_rows($check_if_time_exist) > 0){
               echo "hapa panatosha";
               }else{
         echo "jamani hapa";
         $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID_new','$basicExample','$timepicker','$total_number')") or die(mysqli_error($conn)); 
     }
         
//     $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID_new','$basicExample','$timepicker','$total_number')");
         
     }else{
         
   $insert_appointment = mysqli_query($conn,"INSERT INTO tbl_date_appointment(Clinic_ID,Date,Status)VALUES('$selected_clinic_or_doctor_id','$fromDate','$status_doctors_clinic')") or die(mysqli_error($conn));
 
   $App_ID= mysqli_insert_id($conn);
   
//  echo "hapa".$check_if_time_exist = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new' AND time_from='$basicExample' AND time_to='$timepicker'"));

       $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID','$basicExample','$timepicker','$total_number')") or die(mysqli_error($conn)); 
     }
 
//     }



//timepicker:timepicker,basicExample:basicExample,fromDate:fromDate,selected_clinic_or_doctor_id:selected_clinic_or_doctor_id


     
 }else{
 $App_ID_new="";
$time_from ="";
$time_to ="";
   $check_if_exist = mysqli_query($conn,"SELECT App_ID FROM tbl_date_appointment WHERE Date='$fromDate' AND Clinic_ID='$selected_clinic_or_doctor_id' AND Status='doctors'");
   
     if(mysqli_num_rows($check_if_exist) > 0){
         $App_ID=0;
         while($row = mysqli_fetch_assoc($check_if_exist)){
                      $App_ID_new=$row['App_ID'];
                      
//              $fetch_time = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new'"); 
//             
//              while($time=mysqli_fetch_assoc($fetch_time)){
//                              echo  $time_from =$time['time_from'];
//                              echo  $time_to =$time['time_to'];
//                  
//              }
             
         }
                  
         echo $basicExample;
         echo  $timepicker;
         $check_if_time_exist = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new' AND time_from='$basicExample' AND time_to='$timepicker'");
         
        echo "hapa".$number = mysqli_num_rows($check_if_time_exist);
          if(mysqli_num_rows($check_if_time_exist) > 0){
               echo "hapa panatosha";
               }else{
         echo "jamani hapa";
         $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID_new','$basicExample','$timepicker','$total_number')") or die(mysqli_error($conn)); 
     }
         
//     $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID_new','$basicExample','$timepicker','$total_number')");
         
     }else{
         
   $insert_appointment = mysqli_query($conn,"INSERT INTO tbl_date_appointment(Clinic_ID,Date,Status)VALUES('$selected_clinic_or_doctor_id','$fromDate','$status_doctors_clinic')") or die(mysqli_error($conn));
 
   $App_ID= mysqli_insert_id($conn);
   
//  echo "hapa".$check_if_time_exist = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID_new' AND time_from='$basicExample' AND time_to='$timepicker'"));

       $insert_appointment_schedule = mysqli_query($conn,"INSERT INTO tbl_time_appointment(App_ID,time_from,time_to,Appointment_Idadi)VALUES('$App_ID','$basicExample','$timepicker','$total_number')") or die(mysqli_error($conn)); 
     }
 
//     }



//timepicker:timepicker,basicExample:basicExample,fromDate:fromDate,selected_clinic_or_doctor_id:selected_clinic_or_doctor_id


     
 }
