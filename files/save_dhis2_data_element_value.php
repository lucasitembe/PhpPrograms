<?php
include("./includes/connection.php");
if(isset($_GET['data_element_value'])&&isset($_GET['dhis2_dataelement_auto_id'])){
   $data_element_value=$_GET['data_element_value'];
   $dhis2_dataelement_auto_id=$_GET['dhis2_dataelement_auto_id'];
   $sql_update_data_element_value_result=mysqli_query($conn,"UPDATE tbl_dhis2_dataelements SET dataelement_value='$data_element_value' WHERE dhis2_dataelement_auto_id='$dhis2_dataelement_auto_id'") or die(mysqli_error($conn));
}
