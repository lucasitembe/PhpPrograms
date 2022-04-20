<?php
include("./includes/connection.php");
if(isset($_POST['proc_Patient_Payment_ID'])&&isset($_POST['type_of_procedure'])&&isset($_POST['duration_of_procedure'])&&isset($_POST['Type_Of_Anesthetic'])&&isset($_POST['proc_Item_ID'])){
   $proc_Patient_Payment_ID=$_POST['proc_Patient_Payment_ID'];
   $type_of_procedure=$_POST['type_of_procedure'];
   $duration_of_procedure=$_POST['duration_of_procedure'];
   $Type_Of_Anesthetic=$_POST['Type_Of_Anesthetic'];
   $proc_Item_ID=$_POST['proc_Item_ID'];
   $sql_save_procedure_details_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET type_of_procedure='$type_of_procedure',duration_of_procedure='$duration_of_procedure',Type_Of_Anesthetic='$Type_Of_Anesthetic' WHERE Item_ID='$proc_Item_ID' AND Patient_Payment_ID='$proc_Patient_Payment_ID'") or die(mysqli_error($conn));
   if($sql_save_procedure_details_result){
       echo "success";
   }else{
       echo "fail";
   }
}