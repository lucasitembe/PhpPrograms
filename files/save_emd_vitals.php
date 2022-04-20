<?php
include("includes/connection.php");

$Employee_ID = $_GET['Employee_ID'];
    $Blood_Pressure_Systolic=$_GET['Blood_Pressure_Systolic'];
    $Blood_Pressure_Diastolic=$_GET['Blood_Pressure_Diastolic'];
    $date=$_GET['date'];
    $Pulse_Blood=$_GET['Pulse_Blood'];
    $Temperature=$_GET['Temperature'];
    $Resp_Bpressure=$_GET['Resp_Bpressure'];
    $Fluid_Drug=$_GET['Fluid_Drug'];
    $fbg=$_GET['fbg'];
    $Drainage=$_GET['Drainage'];
    $rbg=$_GET['rbg'];	
    $oxygen_saturation=$_GET['oxygen_saturation'];	
    $Urine=$_GET['Urine'];    //
    $Registration_ID = $_GET['Registration_ID'];
    $consultation_ID = $_GET['consultation_ID'];
    $blood_transfusion = $_GET['blood_transfusion'];
    $body_weight = $_GET['body_weight'];
    $Blood_Pressure = $Blood_Pressure_Systolic."/".$Blood_Pressure_Diastolic;


   $insert_testing_record = mysqli_query($conn, "INSERT INTO tbl_nursecommunication_observation(Registration_ID,employee_ID,date,Blood_Pressure,Pulse_Blood,Temperature,Resp_Bpressure,Fluid_Drug,fbg,Drainage,rbg,Urine,oxygen_saturation,consultation_ID,body_weight,blood_transfusion,Blood_Pressure_Systolic, Blood_Pressure_Diastolic)
                              VALUES('$Registration_ID','$Employee_ID',NOW(),'$Blood_Pressure','$Pulse_Blood','$Temperature','$Resp_Bpressure','$Fluid_Drug','$fbg','$Drainage','$rbg','$Urine','$oxygen_saturation','$consultation_ID','$body_weight','$blood_transfusion','$Blood_Pressure_Systolic','$Blood_Pressure_Diastolic')") or die(mysqli_error($conn));
   
   if($insert_testing_record){
       echo 200;
   }else{
       echo 201;
   }
    
    mysqli_close($conn);