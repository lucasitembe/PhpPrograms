<?php
include("./includes/connection.php");
if(isset($_POST['dhis2_dataelement_id'])&&isset($_POST['dataset_id'])&&isset($_POST['Item_ID_disease_ID'])){
   $dhis2_dataelement_id=$_POST['dhis2_dataelement_id'];
   $dataset_id=$_POST['dataset_id'];
   $Item_ID_disease_ID=$_POST['Item_ID_disease_ID'];
   
   $sql_delete_item_merge_to_data_source_result=mysqli_query($conn,"DELETE FROM tbl_dhis2_data_value_merge WHERE dataset_id='$dataset_id' AND dhis2_dataelement_id='$dhis2_dataelement_id' AND data_element_value_source_id='$Item_ID_disease_ID'") or die(mysqli_error($conn));
}