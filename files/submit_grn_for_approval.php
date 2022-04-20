<?php

@session_start();

include("./includes/connection.php"); 
include_once("./functions/items.php");
if(isset($_GET['lpo_items_for_grn'])){
    $lpo_items_for_grn=$_GET['lpo_items_for_grn'];
}else{
   $lpo_items_for_grn=""; 
}
if(isset($_GET['receiver_Employee_ID'])){
    $receiver_Employee_ID=$_GET['receiver_Employee_ID'];
}else{
   $receiver_Employee_ID=""; 
}
if(isset($_GET['local_purchase_order_id'])){
    $local_purchase_order_id=$_GET['local_purchase_order_id'];
}else{
   $local_purchase_order_id=""; 
}
if(isset($_GET['Sub_Department_ID'])){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
   $Sub_Department_ID=""; 
}
if(isset($_GET['Supplier_ID'])){
    $Supplier_ID=$_GET['Supplier_ID'];
}else{
   $Supplier_ID=""; 
}
if(isset($_GET['Delivery_Note_Number'])){
    $Delivery_Note_Number=$_GET['Delivery_Note_Number'];
}else{
   $Delivery_Note_Number=""; 
}
if(isset($_GET['Invoice_Number'])){
    $Invoice_Number=$_GET['Invoice_Number'];
}else{
   $Invoice_Number=""; 
}
if(isset($_GET['Delivery_Date'])){
    $Delivery_Date=$_GET['Delivery_Date'];
}else{
   $Delivery_Date=""; 
}
if(isset($_GET['Delivery_Person'])){
    $Delivery_Person=mysqli_real_escape_string($conn,$_GET['Delivery_Person']);
}else{
   $Delivery_Person=""; 
}
if(isset($_GET['RV_Number'])){
    $RV_Number=$_GET['RV_Number'];
}else{
   $RV_Number=""; 
}


$Grn_Statuses_to_string = (!empty(isset($_GET['Grn_Statuses_to_string']))) ? $_GET['Grn_Statuses_to_string'] : '';


$HAS_ERROR = false;
Start_Transaction();

//insert to cache
$sql_insert_to_cache_result=mysqli_query($conn,"INSERT INTO tbl_grn_local_purchase_order_cache(receiver_Employee_ID,local_purchase_order_id,Sub_Department_ID,Supplier_ID,Delivery_Note_Number,Invoice_Number,Delivery_Date,Delivery_Person,RV_Number,saved_date_time)
        VALUES('$receiver_Employee_ID','$local_purchase_order_id','$Sub_Department_ID','$Supplier_ID','$Delivery_Note_Number','$Invoice_Number','$Delivery_Date','$Delivery_Person','$RV_Number',(select now()))
        ") or  die(mysqli_error($conn));
if(!$sql_insert_to_cache_result){
    $HAS_ERROR = true;
} 

$grn_local_purchase_order_cache_id="";
$sql_select_grn_local_purchase_order_cache_id_result=mysqli_query($conn,"SELECT grn_local_purchase_order_cache_id FROM tbl_grn_local_purchase_order_cache WHERE receiver_Employee_ID='$receiver_Employee_ID' AND Sub_Department_ID='$Sub_Department_ID' AND local_purchase_order_id='$local_purchase_order_id'") or die(mysqli_error($conn));
     if(mysqli_num_rows($sql_select_grn_local_purchase_order_cache_id_result)>0){
         $grn_local_purchase_order_cache_id=mysqli_fetch_assoc($sql_select_grn_local_purchase_order_cache_id_result)['grn_local_purchase_order_cache_id'];
     }else{
         $HAS_ERROR = true;
     }  
        $count_inserted=0;
         foreach ($lpo_items_for_grn as $items_data){
            $lpo_items_for_grn_array= explode("kiunganishi", $items_data);
            $Item_ID=$lpo_items_for_grn_array[0];
            $container_quantity=$lpo_items_for_grn_array[1];
            $item_per_container=$lpo_items_for_grn_array[2];
            $quantity_required=$lpo_items_for_grn_array[3];
            $expiredate=$lpo_items_for_grn_array[4];
            $batch_no=$lpo_items_for_grn_array[5];
            $Grn_Status=$lpo_items_for_grn_array[6];
            $rejected_quantity=$lpo_items_for_grn_array[7];
            $rejection_reason=$lpo_items_for_grn_array[8];
            $buying_price=$lpo_items_for_grn_array[9];
            $lpo_id_of_item=$lpo_items_for_grn_array[10]; 
            $bar_code=$lpo_items_for_grn_array[11]; 

            if($Grn_Status == 'RECEIVED'){
                # update items status in requisition table
                $update_item_in_requisitions_table = mysqli_query($conn,"UPDATE tbl_purchase_requisition_items SET item_status = 'recieved' WHERE purchase_requisition_items_id='$lpo_id_of_item' ") or die(mysqli_errno($conn));
                if(!$update_item_in_requisitions_table){
                    echo "Not Updated";
                }
            }else if($Grn_Status == "OUTSTANDING" || $Grn_Status == "PENDING"){
                # UPDATE REMAINING ITEMS QUANTITY
                $update_remaining_quantity = mysqli_query($conn,"SELECT quantity_required,item_per_container,Item_ID FROM tbl_purchase_requisition_items WHERE purchase_requisition_items_id = '$lpo_id_of_item' ") or die(mysqli_errno($conn));
                while($outstanding_data = mysqli_fetch_assoc($update_remaining_quantity)){
                    $quantity_required_from_requisition = $outstanding_data['quantity_required'];
                    $item_per_container_from_requisition = $outstanding_data['item_per_container'];
                    $Item_ID = $outstanding_data['Item_ID'];

                    $remaing_quantity = $quantity_required_from_requisition - $quantity_required;

                    # udpate requisition items
                    $update_items_with_outstanding_orders = mysqli_query($conn,"UPDATE tbl_purchase_requisition_items SET quantity_required = '$remaing_quantity', item_status = 'OUTSTANDING' WHERE purchase_requisition_items_id = '$lpo_id_of_item' ") or die(mysqli_errno($conn));
                    if(!$update_items_with_outstanding_orders){
                        echo "Outstanding";
                    }
                }
            }

            $sql_insert_items_to_cache_result=mysqli_query($conn,"INSERT INTO tbl_grn_local_purchase_order_items_cache(Item_ID,container_quantity,item_per_container,quantity_required_,expiredate,Grn_Status,rejected_quantity,rejection_reason,grn_local_purchase_order_cache_id,buying_price)
                VALUES('$Item_ID','$container_quantity','$item_per_container','$quantity_required','$expiredate','$Grn_Status','$rejected_quantity','$rejection_reason','$grn_local_purchase_order_cache_id','$buying_price')
                    ") or die(mysqli_error($conn));

            if($sql_insert_items_to_cache_result)$count_inserted++;
         }

        if($count_inserted<=0){
            $HAS_ERROR = true;  
        }


    
    if (!$HAS_ERROR) {

        $Grn_Statuses_to_string_array = explode(",",$Grn_Statuses_to_string);

        if(in_array("OUTSTANDING",$Grn_Statuses_to_string_array)){
            $sql_update_lpo_status_result=mysqli_query($conn,"UPDATE tbl_local_purchase_order SET submitted_for_grn_approval_status='outstanding' WHERE local_purchase_order_id='$local_purchase_order_id'") or die(mysqli_error($conn));

            if(!$sql_update_lpo_status_result){
                $HAS_ERROR = true; 
            }
        }else if(in_array("PENDING",$Grn_Statuses_to_string_array)){
        	$sql_update_lpo_status_result=mysqli_query($conn,"UPDATE tbl_local_purchase_order SET submitted_for_grn_approval_status='pending' WHERE local_purchase_order_id='$local_purchase_order_id'") or die(mysqli_error($conn));

            if(!$sql_update_lpo_status_result){
                $HAS_ERROR = true; 
            }
        }else{
            $sql_update_lpo_status_result=mysqli_query($conn,"UPDATE tbl_local_purchase_order SET submitted_for_grn_approval_status='submitted' WHERE local_purchase_order_id='$local_purchase_order_id'") or die(mysqli_error($conn));

            if(!$sql_update_lpo_status_result){
                $HAS_ERROR = true; 
            }
        }
    }


    if (!$HAS_ERROR) {
        Commit_Transaction();
        echo 1;
    } else {
        Rollback_Transaction();
        echo "fail";
    }

?>
