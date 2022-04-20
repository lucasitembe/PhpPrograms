<?php
include("includes/connection.php");
if(isset($_POST['Payment_Item_Cache_List_ID'])&&isset($_POST['new_specimen_id'])){
    $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
    $new_specimen_id=$_POST['new_specimen_id'];
    $sql_update_specimen_id_result=mysqli_query($conn,"UPDATE tbl_intergrated_lab_results SET sample_test_id='$new_specimen_id' WHERE sample_test_id='$Payment_Item_Cache_List_ID'") or (mysqli_error($conn));

    if($sql_update_specimen_id_result){
      echo "success";  
    }else{
        echo "fail";
    }
}