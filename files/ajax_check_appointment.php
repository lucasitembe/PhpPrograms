
<?php
include("./includes/connection.php");
if (isset($_POST['clinic'])) {
    $clinic = $_POST['clinic'];
} else {
    $clinic= "0";
}
if (isset($_POST['timefrom'])) {
    $timefrom = $_POST['timefrom'];
} else {
    $timefrom= "";
}
if (isset($_POST['timeto'])) {
    $timeto = $_POST['timeto'];
} else {
    $timeto= "";
}
if (isset($_POST['appdate'])) {
    $appdate = $_POST['appdate'];
} else {
    $appdate= "";
}
if (isset($_POST['status_clinics_doctors'])) {
   $status = $_POST['status_clinics_doctors'];
} else {
    $status= "";
}

//appdate:appdate status:status

  if($status == "clinics"){
//        echo "ndani ya clinics";
       $status_appointment="";
  
           $appointment_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT App_ID FROM tbl_date_appointment WHERE Date='$appdate' AND Status='$status'"))['App_ID'];
           $Time_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Time_ID FROM tbl_time_appointment WHERE App_ID='$appointment_ID' AND time_from='$timefrom' AND time_to='$timeto'"))['Time_ID'];
   
   $fetch_appointment_time = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$appointment_ID' AND Time_ID='$Time_ID' ");
    
    while($row = mysqli_fetch_assoc($fetch_appointment_time)){
            $time_from_new=$row['time_from'];
           $time_to_new=$row['time_to'];
           $Appointment_Idadi=$row['Appointment_Idadi'];
//          echo $clinic ;
//            echo "idadi====>$Appointment_Idadi";
          $available_appointment_coverd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(patient_No) as idaditayar FROM tbl_appointment WHERE Clinic='$clinic' AND timefrom='$time_from_new' AND timeto='$time_to_new'  AND DATE(date_time)=DATE('$appdate')"))['idaditayar'];
//           echo "available===>$available_appointment_coverd<===SELECT count(patient_No) as idaditayar FROM tbl_appointment WHERE Clinic='$clinic' AND timefrom='$time_from_new' AND timeto='$time_to_new' AND DATE(date_time)=DATE('$appdate')"; 
     if(  $available_appointment_coverd  >= $Appointment_Idadi){
          
         $status_appointment=1;
          
        }else{
         $status_appointment= 0;
      }

      echo $status_appointment;
        
    }
                                   
//  $total_number = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Appointment_Idadi FROM tbl_time_appointment WHERE Clinic='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to'"))['Appointment_Idadi'];
                                   
//    $availabe_number = $total_number - $available_appointment_coverd;
    

      
  }else{
       $status_appointment="";
  
   $appointment_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT App_ID FROM tbl_date_appointment WHERE Date='$appdate' AND Status='$status'"))['App_ID'];
   $Time_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Time_ID FROM tbl_time_appointment WHERE App_ID='$appointment_ID' AND time_from='$timefrom' AND time_to='$timeto'"))['Time_ID'];
   
   $fetch_appointment_time = mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$appointment_ID' AND Time_ID='$Time_ID' ");
    
    while($row = mysqli_fetch_assoc($fetch_appointment_time)){
            $time_from_new=$row['time_from'];
           $time_to_new=$row['time_to'];
           $Appointment_Idadi=$row['Appointment_Idadi'];
//          echo $clinic ;
            
       $available_appointment_coverd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(patient_No) as idaditayar FROM tbl_appointment WHERE doctor='$clinic' AND timefrom='$time_from_new' AND timeto='$time_to_new' AND DATE(date_time)=DATE('$appdate')"))['idaditayar'];
            
     if(  $available_appointment_coverd >= $Appointment_Idadi){
          
         $status_appointment=1;
          
        }else{
         $status_appointment= 0;
      }

      echo $status_appointment;
        
    }
                                   
//  $total_number = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Appointment_Idadi FROM tbl_time_appointment WHERE Clinic='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to'"))['Appointment_Idadi'];
                                   
//    $availabe_number = $total_number - $available_appointment_coverd;
    

      
  }
 