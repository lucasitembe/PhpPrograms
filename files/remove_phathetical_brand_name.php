<?php
include("./includes/connection.php");
if(isset($_POST['Item_ID'])&&isset($_POST['selected_Phamathetical_dataelement_id'])){
   $Brand_ID=$_POST['Item_ID'];
   $Generic_ID=$_POST['selected_Phamathetical_dataelement_id'];
   // 
   // $sql_delete_item_merge_to_data_source_result=mysqli_query($conn,"DELETE FROM tbl_phamathetical_item_brand_name WHERE phamathetical_item_id='$selected_Phamathetical_dataelement_id' AND brand_name_id='$Item_ID'") or die(mysqli_error($conn));

   mysqli_query($conn,"UPDATE tbl_items SET item_kind = 'generic', Generic_ID = NULL WHERE Item_ID = '$Brand_ID'");
}
