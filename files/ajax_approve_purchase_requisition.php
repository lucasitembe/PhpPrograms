<?php 
include("./includes/connection.php");

if($_GET['purchase_requisition_id']){
   $purchase_requisition_id=$_GET['purchase_requisition_id'];
}else{
   $purchase_requisition_id="";
}

    $sql_update_pr_status_result=mysqli_query($conn,"UPDATE tbl_purchase_requisition SET pr_status='approved' WHERE purchase_requisition_id='$purchase_requisition_id'") or die(mysqli_error($conn));

    if($sql_update_pr_status_result){
        echo "success";
    }

?>
