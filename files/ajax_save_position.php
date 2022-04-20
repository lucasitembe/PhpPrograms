<?php
include("./includes/connection.php");
$location = '';
if (isset($_POST['Position_Value'])) {
    $Position_Value=$_POST['Position_Value'];
    //check if exist
    $sql_query_select_result=mysqli_query($conn,"SELECT Position_name FROM tbl_Position WHERE Position_name='$Position_Value'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_query_select_result)<=0){
        $result=mysqli_query($conn,"INSERT INTO tbl_Position (Position_name) VALUES('$Position_Value')") or die(mysqli_error($conn));
    if($result){
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        echo "exist";
    }
}