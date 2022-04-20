<?php
 include("./includes/connection.php");
 $Item_ID = $_GET['Item_ID'];
 $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
 $Billing_Type = $_GET['Billing_Type'];
 $Guarantor_Name = $_GET['Guarantor_Name'];
 $Select_Price = '';
 if($Billing_Type=='Outpatient Credit'){
			if(strtolower($Guarantor_Name)=='nhif'){
		            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
						where i.Item_ID = '$Item_ID' ";
			}else{
			    $Select_Price = "select Selling_Price_Credit as price from tbl_items i
						where i.Item_ID = '$Item_ID' ";
			}
		    }
 elseif($Billing_Type=='Outpatient Cash'){
     $Select_Price = "select Selling_Price_Cash as price from tbl_items i
                         where i.Item_ID = '$Item_ID' ";
 }
 
 $qr = "UPDATE tbl_patient_payment_item_list SET
 Price=($Select_Price),
 Category=(SELECT ic.Item_Category_Name FROM tbl_items i, tbl_item_subcategory isc, tbl_item_category ic WHERE isc.Item_Subcategory_ID=i.Item_Subcategory_ID AND ic.Item_Category_ID = isc.Item_Category_ID AND i.Item_ID = $Item_ID),
 Item_ID=$Item_ID
 WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID AND Patient_Payment_Item_List_ID IN (SELECT Patient_Payment_Item_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID')AND Transaction_Type='Credit'";
 
 if(!mysqli_query($conn,$qr)){
  die(mysqli_error($conn));
 }else{
  echo "sent";
 }
?>