<?php
include("./includes/connection.php");
session_start();
if(isset($_POST['selected_Phamathetical_dataelement_id'])){
   $selected_Phamathetical_item_id=$_POST['selected_Phamathetical_dataelement_id']; 
}
$item_array=explode(".unganishana",$selected_Phamathetical_item_id);
$item_id=$item_array[0];
$original_item_id=$item_id;
$item_name=$item_array[1];
///select all merged item
$sql_select_merged_item_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE original_item_id='$original_item_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_merged_item_result)>0){
    $count_sn=1;
    while($merged_items_rows=mysqli_fetch_assoc($sql_select_merged_item_result)){
        $Product_Name=$merged_items_rows['Product_Name'];
        echo "<tr><td>$count_sn.</td><td>$Product_Name</td></tr>";
        $count_sn++;
    }
}
