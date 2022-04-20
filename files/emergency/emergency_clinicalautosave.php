<?php
    include("../includes/connection.php");
    include './includes/cleaninput.php';
    @session_start();
   
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    //die($employee_ID);
    $consultation_ID = mysqli_real_escape_string($conn,$_GET['consultation_ID']);
    $maincomplain = mysqli_real_escape_string($conn,$_GET['maincomplain']);
   
    
      $update_query = "UPDATE tbl_consultation SET maincomplain='$maincomplain' WHERE consultation_ID = '$consultation_ID'";
     
            if (mysqli_query($conn,$update_query)) {
                $update_query_hist = "UPDATE tbl_consultation_history SET maincomplain='$maincomplain' WHERE consultation_ID = '$consultation_ID'  AND employee_ID='$employee_ID'";
                 $upt=mysqli_query($conn,$update_query_hist) or die(mysqli_error($conn));
                 $upt=mysqli_query($conn,$update_query_hist) or die(mysqli_error($conn));
                 if($upt){
                     echo "updated";
                 }else{
                     echo "Error";
                 }
                // echo 1;
            }else{
                die(mysqli_error($conn));
            }

?>