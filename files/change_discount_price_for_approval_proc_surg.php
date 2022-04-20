<?php
include("./includes/connection.php");
 
if(isset($_GET['discount_price'])){
  $discount_price= $_GET['discount_price']; 
}else{
   $discount_price=''; 
}
if(isset($_GET['Payment_Item_Cache_List_ID'])){
  $Payment_Item_Cache_List_ID= $_GET['Payment_Item_Cache_List_ID']; 
}else{
  $Payment_Item_Cache_List_ID=''; 
}
if(isset($_GET['Registration_ID'])){
  $Registration_ID= $_GET['Registration_ID']; 
}else{
  $Registration_ID=''; 
}
if(isset($_GET['Payment_Cache_ID'])){
  $Payment_Cache_ID= $_GET['Payment_Cache_ID']; 
}else{
  $Payment_Cache_ID=''; 
}
if(isset($_GET['Billing_Type'])){
  $Billing_Type= $_GET['Billing_Type']; 
}else{
  $Billing_Type=''; 
}
$sql_update_discount_price="UPDATE tbl_item_list_cache SET Discount='$discount_price' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'";

$sql_update_discount_price_result=mysqli_query($conn,$sql_update_discount_price) or die(mysqli_error($conn));

if($sql_update_discount_price_result){
  
            $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                    ilc.Item_ID = itm.Item_ID and
                                    ilc.Status = 'active' and
                                   
                                    (ilc.Check_In_Type = 'Procedure' or ilc.Check_In_Type = 'Surgery') and
                                    pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                    pc.Registration_ID = '$Registration_ID' and ilc.Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' and
                                    ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select_items);
            if ($no > 0) {
                while ($data = mysqli_fetch_array($select_items)) {
                 //generate Quantity
                    if ($data['Edited_Quantity'] != 0) {
                        $Qty = $data['Edited_Quantity'];
                    } else {
                        $Qty = $data['Quantity'];
                    }
                 echo   $Total = (($data['Price'] - $data['Discount']) * $Qty);
                  
               
                    }
            }
}else{
    echo "fail to update discount price....try again";
}

