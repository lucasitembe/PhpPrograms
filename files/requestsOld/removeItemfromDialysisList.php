<?php
include("../includes/connection.php");
session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if(isset($_GET['remove'])){
$Payment_Item_Cache_List_ID=$_GET['Payment_Item_Cache_List_ID'];   
  $query="UPDATE tbl_item_list_cache SET romoving_staff='$Employee_ID',removing_date=NOW(),removing_status='Yes' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'";
 $runQuery=  mysql_query($query) or die(mysql_error());
}elseif(isset($_GET['retun'])){
  $Payment_Item_Cache_List_ID=$_GET['Payment_Item_Cache_List_ID'];   
  $query="UPDATE tbl_item_list_cache SET removing_date='',removing_status='No' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'";
 $runQuery=  mysql_query($query) or die(mysql_error());  
 
}
