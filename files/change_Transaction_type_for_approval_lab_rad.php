<?php

include("./includes/connection.php");
 
if(isset($_GET['Transaction_type'])){
  $Transaction_type= $_GET['Transaction_type']; 
}else{
   $Transaction_type=''; 
}
if(isset($_GET['Payment_Item_Cache_List_ID'])){
  $Payment_Item_Cache_List_ID= $_GET['Payment_Item_Cache_List_ID']; 
}else{
  $Payment_Item_Cache_List_ID=''; 
}
$sql_update_Transaction_type="UPDATE tbl_item_list_cache SET Transaction_type='$Transaction_type' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'";

$sql_update_Transaction_type_result=mysqli_query($conn,$sql_update_Transaction_type) or die(mysqli_error($conn));

if($sql_update_Transaction_type_result){
    echo "changed";
}else{
    echo "fail";
}