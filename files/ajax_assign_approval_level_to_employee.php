<?php
include("./includes/connection.php");
session_start();
$assigned_by_Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if(isset($_GET['Employee_ID'])){
  $assgned_Employee_ID=$_GET['Employee_ID']; 
}else{
  $assgned_Employee_ID="";
}
if(isset($_GET['document_approval_level_title_id'])){
  $document_approval_level_title_id=$_GET['document_approval_level_title_id']; 
}else{
  $document_approval_level_title_id="";
}
if(isset($_GET['document_type_to_approve'])){
  $document_type_to_approve=$_GET['document_type_to_approve']; 
}else{
    $document_type_to_approve="";
}
$sql_select_document_approval_level_id_result=mysqli_query($conn,"SELECT document_approval_level_id FROM tbl_document_approval_level WHERE document_type='$document_type_to_approve' AND document_approval_level_title_id='$document_approval_level_title_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_document_approval_level_id_result)>0){
     echo $document_approval_level_id=mysqli_fetch_assoc($sql_select_document_approval_level_id_result)['document_approval_level_id'];
     
     $sql_check_if_already_assigned_result=mysqli_query($conn,"SELECT document_approval_level_id FROM tbl_employee_assigned_approval_level WHERE document_approval_level_id='$document_approval_level_id' AND assgned_Employee_ID='$assgned_Employee_ID'") or die(mysqli_error($conn));
     if(mysqli_num_rows($sql_check_if_already_assigned_result)<=0){
     $sql_assigne_employee_approval_level_result=mysqli_query($conn,"INSERT INTO tbl_employee_assigned_approval_level(document_approval_level_id,assgned_Employee_ID,assigned_by_Employee_ID) VALUES('$document_approval_level_id','$assgned_Employee_ID','$assigned_by_Employee_ID')") or die(mysqli_error($conn));
    
        if($sql_assigne_employee_approval_level_result){
            echo "success";
        }else{
            echo "fail";
        }
     }else{
        echo "already_added";
    }
}