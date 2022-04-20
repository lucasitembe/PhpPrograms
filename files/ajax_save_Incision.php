<?php
include("./includes/connection.php");
$location = '';
if (isset($_POST['Incision_Value'])) {
    $Incision_Value=$_POST['Incision_Value'];
    //check if exist
    $sql_query_select_result=mysqli_query($conn,"SELECT Incision_name FROM tbl_incision WHERE Incision_name='$Incision_Value'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_query_select_result)<=0){
        $result=mysqli_query($conn,"INSERT INTO tbl_incision (Incision_name) VALUES('$Incision_Value')") or die(mysqli_error($conn));
    if($result){
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        echo "exist";
    }
}