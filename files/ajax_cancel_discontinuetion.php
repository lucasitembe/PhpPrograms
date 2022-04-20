<?php
include_once("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}else{
    $Registration_ID=""; 
}
if(isset($_POST['Item_ID'])){
   $Item_ID=$_POST['Item_ID']; 
}else{
   $Item_ID=""; 
}

$Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
$update_feedback=mysqli_query($conn,"UPDATE tbl_inpatient_medicines_given SET Discontinue_Status='no' WHERE Item_ID='$Item_ID' AND Registration_ID='$Registration_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
if($update_feedback){
    echo "success";
}else{
    echo "fail";
}