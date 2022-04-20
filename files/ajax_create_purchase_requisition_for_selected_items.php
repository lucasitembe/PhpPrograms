<?php
session_start();
include("./includes/connection.php");
    $Supplier_ID=$_POST['Supplier_ID'];
    $Store_Need=$_POST['Store_Need'];
    $Purchase_Type=$_POST['Purchase_Type'];
    $Store_Order_ID=$_POST['Store_Order_ID'];
    $Purchases_Requisition_Description=mysqli_real_escape_string($conn,$_POST['Purchases_Requisition_Description']);
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $selected_item_for_pr=$_POST['selected_item_for_pr'];
    $reference_document=$_POST['reference_document'];
    $local_foreign = $_POST['local_foreign'];
    $discount = $_POST['discount'];
    $charges_to_string = $_POST['charges_to_string'];
    $success=0;

    if($Supplier_ID == "none"){
        $Supplier_ID = "";
        $Supplier_Status = 'no';
        $pr_status = "waiting_supplier";
    }else{
        $Supplier_ID = $Supplier_ID;
        $Supplier_Status = 'yes';
        $pr_status = "active";
    }

    $sql = "INSERT INTO tbl_purchase_requisition(purchase_requisition_description,created_date_time,store_requesting,employee_creating,Store_Order_ID,Supplier_ID,Purchase_Type,pr_status,reference_document,Requisition_Type,supplier_status) 
            VALUES('$Purchases_Requisition_Description',(select now()),'$Store_Need','$Employee_ID','$Store_Order_ID','$Supplier_ID','$Purchase_Type','$pr_status','$reference_document','$local_foreign','$Supplier_Status')";
   
    # create purchase requisition for selected items
    $sql_create_purchase_requisition_result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($sql_create_purchase_requisition_result){
        # get purchase_requision number
        $sql_select_pr_number_result=mysqli_query($conn,"SELECT purchase_requisition_id FROM tbl_purchase_requisition WHERE employee_creating='$Employee_ID' AND Store_Order_ID='$Store_Order_ID' ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_pr_number_result)>0){
            $purchase_requisition_id=mysqli_fetch_assoc($sql_select_pr_number_result)['purchase_requisition_id'];

            $insert_other_charges = mysqli_query($conn,"INSERT tbl_procurement_others_charges (costs,discount,purchase_requisition_id) VALUES ('$charges_to_string','$discount','$purchase_requisition_id')") or die(mysqli_errno($conn));

            foreach ($selected_item_for_pr as $items_data){
                $items_data_array=explode("item_id_buying_price_separator",$items_data);
                $Item_ID=$items_data_array[0];
                $buying_price=$items_data_array[1];
                $item_quantity=$items_data_array[2];
                $item_per_container=$items_data_array[3];
                $container_quantity=$items_data_array[4];

                # insert pr items
                $sql_insert_pr_items_results=mysqli_query($conn,"INSERT INTO tbl_purchase_requisition_items(Item_ID,container_quantity,item_per_container,quantity_required,buying_price,purchase_requisition_id,item_status) VALUES('$Item_ID','$item_per_container','$container_quantity','$item_quantity','$buying_price','$purchase_requisition_id','active')") or die(mysqli_error($conn));
                if($sql_insert_pr_items_results){
                    # update item procurement_status
                    $update_status_result=mysqli_query($conn,"UPDATE tbl_store_order_items SET Procurement_Status='on_pr_proccess' WHERE Item_ID='$Item_ID' AND Store_Order_ID='$Store_Order_ID'") or die(mysqli_error($conn));
                    $success++;
                }
            }
        }
    }
if($success > 0){
    echo "success";
}