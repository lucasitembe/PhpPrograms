<?php
   include("./includes/connection.php");
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
//    $time_min=date("h:m:s");
   
   $consultation_ID=  mysqli_real_escape_string($conn,$_POST['consultation_ID']);
   $Registration_ID=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
   $time=  mysqli_real_escape_string($conn,$_POST['time']);
   $heart_rate= mysqli_real_escape_string($conn,$_POST['heart_rate']);
   $respiratory_rate= mysqli_real_escape_string($conn,$_POST['respiratory_rate']);
   $pso2= mysqli_real_escape_string($conn,$_POST['pso2']);
   $temperature= mysqli_real_escape_string($conn,$_POST['temperature']);
   $blood_pressure_sytolic= mysqli_real_escape_string($conn,$_POST['blood_pressure_sytolic']);
   $VA_WGLASSES_LE= mysqli_real_escape_string($conn,$_POST['VA_WGLASSES_LE']);
   $blood_pressure_diasotlic= mysqli_real_escape_string($conn,$_POST['blood_pressure_diasotlic']);
   $pulse_pressure= mysqli_real_escape_string($conn,$_POST['pulse_pressure']);
   $map= mysqli_real_escape_string($conn,$_POST['map']);

             $pediatric=mysqli_query($conn,"INSERT INTO pediatric_graph(heart_rate, respiratory_rate, pso2, temperature, blood_pressure_sytolic, blood_pressure_diasotlic, pulse_pressure, map, time_min, Registration_ID, Employee_ID, consultation_ID) VALUES ('$heart_rate','$respiratory_rate','$pso2','$temperature','$blood_pressure_sytolic','$blood_pressure_diasotlic','$pulse_pressure','$map','$time','$Registration_ID','$Employee_ID','$consultation_ID')")  or die(mysqli_error($conn));
             if($pediatric){
                 echo "Saved";
             }
             else{
                 echo "Not Saved";
             }
         
  
?>