<?php 
include("./includes/connection.php");

if($_GET['purchase_requisition_description']){
   $purchase_requisition_description=$_GET['purchase_requisition_description'];
}else{
    $purchase_requisition_description="";
}
if($_GET['Sub_Department_ID']){
   $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID="";
}
if($_GET['Store_Order_ID']){
   $Store_Order_ID=$_GET['Store_Order_ID'];
}else{
    $Store_Order_ID="";
}
if($_GET['Supplier_ID']){
   $Supplier_ID=$_GET['Supplier_ID'];
}else{
   $Supplier_ID="";
}
if($_GET['purchase_requisition_id']){
   $purchase_requisition_id=$_GET['purchase_requisition_id'];
}else{
   $purchase_requisition_id="";
}
if($_GET['employee_creating']){
   $employee_creating=$_GET['employee_creating'];
}else{
   $employee_creating="";
}
//create lpo
$sql_create_lpo_result=mysqli_query($conn,"INSERT INTO tbl_local_purchase_order(purchase_requisition_description,created_date_time,store_requesting,Store_Order_ID,Supplier_ID,lpo_status,purchase_requisition_id,employee_creating)
     VALUES('$purchase_requisition_description',(select now()),'$Sub_Department_ID','$Store_Order_ID','$Supplier_ID','approved','$purchase_requisition_id','$employee_creating')   
    ") or die(mysqli_error($conn));
//update pr status to approved
if($sql_create_lpo_result){
    $sql_update_pr_status_result=mysqli_query($conn,"UPDATE tbl_purchase_requisition SET pr_status='approved_for_lpo' WHERE purchase_requisition_id='$purchase_requisition_id'") or die(mysqli_error($conn));

    if($sql_update_pr_status_result){
        echo "success";
    }
}
?>
