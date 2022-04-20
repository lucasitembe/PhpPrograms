<?php
 include("./includes/connection.php");

$sponsor_id=$_POST['sponsor_id'];
$item_id=$_POST['item_id'];
$duration=$_POST['duration'];

#check if item exist
$check_query = mysqli_query($conn,"SELECT item_alert_control_id FROM tbl_items_alert_control WHERE item_id='$item_id' AND sponsor_id='$sponsor_id'");
$item_alert_control_data = mysqli_fetch_assoc($check_query);
$item_alert_control_id = $item_alert_control_data['item_alert_control_id'];

if(mysqli_num_rows($check_query)>0){
    #update
    $update_query = mysqli_query($conn,"UPDATE `tbl_items_alert_control` SET `item_id`='$item_id',`sponsor_id`='$sponsor_id',`duration`='$duration' WHERE item_alert_control_id='$item_alert_control_id'");
    if($update_query){echo 'Duration updated successfully';}else{echo 'Sorry, Duration not updated. Please try again';}
}else{
    #insert
   $insert_query = mysqli_query($conn,"INSERT INTO `tbl_items_alert_control`(`item_id`, `sponsor_id`, `duration`) VALUES('$item_id','$sponsor_id','$duration')");
    if($insert_query){echo 'Duration inserted successfully';}else{echo 'Sorry, Duration not inserted. Please try again';}
}