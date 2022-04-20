<?php
include("../includes/connection.php");
session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if(isset($_GET['remove'])){
$Payment_Cache_ID=$_GET['Payment_Cache_ID'];   
  $query="UPDATE tbl_item_list_cache SET romoving_staff='$Employee_ID',removing_date=NOW(),removing_status='Yes' WHERE Payment_Cache_ID='$Payment_Cache_ID'";
 $runQuery=  mysqli_query($conn,$query) or die(mysqli_error($conn));
}elseif(isset($_GET['retun'])){
  $Payment_Cache_ID=$_GET['Payment_Cache_ID'];   
  $query="UPDATE tbl_item_list_cache SET removing_date='',removing_status='No' WHERE Payment_Cache_ID='$Payment_Cache_ID'";
 $runQuery=  mysqli_query($conn,$query) or die(mysqli_error($conn));  
 
}
