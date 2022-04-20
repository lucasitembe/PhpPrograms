<?php
    include("./includes/connection.php");
    @session_start();
   
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    //die($employee_ID);
    $fieldName = mysqli_real_escape_string($conn,$_GET['fieldName']);
    $fieldValue = mysqli_real_escape_string($conn,$_GET['fieldValue']);
    $Round_ID  = mysqli_real_escape_string($conn,$_GET['Round_ID']);
   
    
      $update_query = "UPDATE tbl_ward_round SET $fieldName='$fieldValue'
		          WHERE Round_ID = '$Round_ID'";
      
            if (mysqli_query($conn,$update_query)) {
                echo 1;
            }else{
                die(mysqli_error($conn));
            }

?>