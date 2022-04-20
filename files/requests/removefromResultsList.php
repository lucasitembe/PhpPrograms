<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['removefromList'])){
  $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
  $payID=$_POST['payID'];
  $getnumbers=mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='".$payID=$_POST['payID']."' AND Check_In_Type='Laboratory'");
  $querynums=  mysqli_fetch_assoc($getnumbers);
  
 $paymentcacheID=$querynums['Payment_Cache_ID'];

 $getcacheListIds=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID=".$paymentcacheID." AND Check_In_Type='Laboratory'");
 $itemListCacheID=array();
 while ($result=  mysqli_fetch_assoc($getcacheListIds)){
  $itemListCacheID[]=$result['Payment_Item_Cache_List_ID'];
  }
  
  $listItems=implode(',',$itemListCacheID);
  $updatetble=  mysqli_query($conn,"UPDATE tbl_test_results SET date_removed=NOW(),staff_removed='$Employee_ID',removed_status='Yes' WHERE payment_item_ID IN ($listItems)");

 }elseif (isset ($_POST['returnToList'])) {
  $payID=$_POST['payID'];
  $getnumbers=mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='".$payID=$_POST['payID']."' AND Check_In_Type='Laboratory'");
  $querynums=  mysqli_fetch_assoc($getnumbers);
  
 $paymentcacheID=$querynums['Payment_Cache_ID'];

 $getcacheListIds=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID=".$paymentcacheID." AND Check_In_Type='Laboratory'");
 $itemListCacheID=array();
 while ($result=  mysqli_fetch_assoc($getcacheListIds)){
  $itemListCacheID[]=$result['Payment_Item_Cache_List_ID'];
  }
  
  $listItems=implode(',',$itemListCacheID);
  $updatetble=  mysqli_query($conn,"UPDATE tbl_test_results SET removed_status='No' WHERE payment_item_ID IN ($listItems)");
}
?>