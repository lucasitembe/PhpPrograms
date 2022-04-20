<?php
include("./includes/connection.php");
if(isset($_POST['Patient_Payment_Item_List_ID'])&&isset($_POST['type_of_procedure'])&&isset($_POST['duration_of_procedure'])&&isset($_POST['Type_Of_Anesthetic'])){
  $Patient_Payment_Item_List_ID=$_POST['Patient_Payment_Item_List_ID'];
  $type_of_procedure=$_POST['type_of_procedure'];
  $duration_of_procedure=$_POST['duration_of_procedure'];
  $Type_Of_Anesthetic=$_POST['Type_Of_Anesthetic'];
  $sql_save_info_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET type_of_procedure='$type_of_procedure',duration_of_procedure='$duration_of_procedure',Type_Of_Anesthetic='$Type_Of_Anesthetic' WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

  if($sql_save_info_result){
    echo "success";
  }else{
      echo "fail";
  }
}