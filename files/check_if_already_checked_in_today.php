<?php
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
    $sql_check_if_checked_in_today="SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' AND DATE(Check_In_Date_And_Time)=CURDATE()";

    $sql_check_if_checked_in_today_result=mysqli_query($conn,$sql_check_if_checked_in_today) or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_check_if_checked_in_today_result)>0){
        echo "already";
    }else{
        echo "notyet";
    }
}