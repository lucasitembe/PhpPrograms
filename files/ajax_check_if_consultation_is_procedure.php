<?php
include("./includes/connection.php");
if(isset($_POST['Patient_Payment_ID'])){
   $Patient_Payment_ID=$_POST['Patient_Payment_ID']; 
   $sql_check_is_this_item_is_on_procedure_setup_result=mysqli_query($conn,"SELECT Patient_Payment_Item_List_ID FROM tbl_procedure_setup ps,tbl_patient_payment_item_list ppl WHERE ps.Item_ID=ppl.Item_ID AND Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_check_is_this_item_is_on_procedure_setup_result)>0){
      echo $Patient_Payment_Item_List_ID=mysqli_fetch_assoc($sql_check_is_this_item_is_on_procedure_setup_result)['Patient_Payment_Item_List_ID'];
   }else{
       echo "item_haipo"; 
   }
} 
