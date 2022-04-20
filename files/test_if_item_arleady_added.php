<?php
session_start();
include("./includes/connection.php");
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '0';
}
if(isset($_GET['Item_ID'])){
   $Item_ID = $_GET['Item_ID'];
}else{
   $Item_ID = '';
}
if(isset($_GET['Registration_ID'])){
   $Registration_ID = $_GET['Registration_ID'];
}else{
   $Registration_ID = '';
}
$sql_check_if_this_item_arleady_added_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_direct_cash_cache WHERE Item_ID='$Item_ID' AND Registration_ID='$Registration_ID' AND Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_if_this_item_arleady_added_result)>0){
    echo "item_exist";
}else{
     echo "item_not_exist";
}

