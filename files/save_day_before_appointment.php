<?php

 include("./includes/connection.php");
 @$day =$_POST['day'];
 
  $select_day_if_exist = mysqli_query($conn,"SELECT appointmed_day FROM tbl_appointment_day");
  
  if(mysqli_num_rows($select_day_if_exist) > 0){
     
      $update_day_appointment=mysqli_query($conn,"UPDATE tbl_appointment_day SET appointmed_day='$day' WHERE day_ID='1'");
      
  }else{
     $save_day_appointment = mysqli_query($conn,"INSERT INTO tbl_appointment_day(appointmed_day) VALUES('$day')"); 
  }
  
 
// $clinic = mysqli_fetch_assoc(mysqli_query($conn,"SELECT il.Clinic_ID FROM tbl_item_list_cache il,tbl_payment_cache pc WHERE il.Payment_Cache_ID= pc.Payment_Cache_ID AND pc.Registration_ID='$Registration_ID' ORDER BY il.Created_Date_Time DESC LIMIT 1 "))['Clinic_ID'];
 

                   

                     
  

