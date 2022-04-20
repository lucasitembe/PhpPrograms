<?php
session_start();
include("includes/connection.php");
if (isset($_POST['payment_id'])) {
   $payment_id=$_POST['payment_id'];
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   //check if parameter exist
   $sql_check_if_test_result=mysqli_query($conn,"SELECT ref_test_result_ID FROM tbl_tests_parameters_results WHERE ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$payment_id')") or die(mysqli_error($conn));

   if(mysqli_num_rows($sql_check_if_test_result)>0){
       echo "update";
       //update validate status
       $sql_update_validate_status_for_this_page_result=mysqli_query($conn,"UPDATE tbl_tests_parameters_results SET Validated='Yes',modified='Yes',ValidatedBy='$Employee_ID',TimeValidate=NOW() WHERE ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$payment_id')") or die(mysqli_error($conn));
   }else{
       echo "new";
       //insert validate status
       $test_result_ID_result=mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$payment_id'") or die(mysqli_error($conn));
       $test_result_ID=mysqli_fetch_assoc($test_result_ID_result)['test_result_ID'];
       
       $ref_parameter_ID_result=mysqli_query($conn,"SELECT ref_parameter_ID FROM tbl_tests_parameters WHERE ref_item_ID IN(SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$payment_id')") or die(mysqli_error($conn));
       $ref_parameter_ID=mysqli_fetch_assoc($ref_parameter_ID_result)['ref_parameter_ID'];
       
       $sql_insert_validate_status_result=mysqli_query($conn,"INSERT INTO tbl_tests_parameters_results (ref_test_result_ID,parameter,Validated,ValidatedBy,TimeValidate) VALUES('$test_result_ID','$ref_parameter_ID','yes','$Employee_ID',NOW())") or die(mysqli_error($conn));
   }
}