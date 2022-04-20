<?php
    include("./includes/connection.php");
    $selected_patient=$_POST['selected_patient'];
    $selected_employee=$_POST['selected_employee'];
    $Admision_ID=0;
    $Employee_ID=0;
  foreach($selected_patient as $Admision_ID){
      
  }
  foreach($selected_employee as $Employee_ID){
      
  }
  $Employee_ID_assign=$_SESSION['userinfo']['Employee_ID'];
  $sql_assign_doctor_to_patient_result=mysqli_query($conn,"UPDATE tbl_admission SET `assigned_doctor`='$Employee_ID', `assigned_by`='$Employee_ID_assign' WHERE Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
  if($sql_assign_doctor_to_patient_result){
      echo "Assigned Successfully";
  }else{
      echo "Process Fail!Try again...";
  }