<?php

 include("./includes/connection.php");
  echo @$allowsms =$_POST['allowsms'];
  echo @$checkstatus =$_POST['checkstatus'];
 
//  $allow_sms_to_patient = mysqli_fetch_assoc(mysqli_query($conn,"SELECT allow_sms_to_patient FROM tbl_system_configuration"))['allow_sms_to_patient'];
  
        
   $update_sms=mysqli_query($conn,"UPDATE tbl_system_configuration SET allow_sms_to_patient='$allowsms' WHERE Configuration_ID='1'");
        
  
  
  
     
    
      
 