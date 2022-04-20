<?php
/**
 * Created by PhpStorm.
 * User: GPITG-CODERS
 * Date: 2/3/2015
 * Time: 4:21 PM
 */
include("./includes/connection.php");
if($_GET['Status_From'] !=''){
    $Status_From=$_GET['Status_From'];
}else{
    $Status_From='';
}

if($_GET['Patient_Payment_Item_List_ID'] !=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}

if($_GET['Item_ID']){
    $Item_ID=$_GET['Item_ID'];
}else{
    $Item_ID='';
}

//run the query to update
if(strtolower($Status_From)=='payment'){
    $update_query=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET
											Status='result',
											Process_Status='image taken'
											 WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND Item_ID='$Item_ID' ") or die(mysqli_error($conn));
    if($update_query){
        echo "success";
    }else{
        echo "failure";
    }
}

if(strtolower($Status_From)=='cache'){
    $update_query=mysqli_query($conn,"UPDATE tbl_item_list_cache SET
											Status='result',
											Process_Status='image taken'
											 WHERE Payment_Item_Cache_List_ID='$Patient_Payment_Item_List_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
    if($update_query){
        echo "success";
    }else{
        echo "failure";
    }
}

