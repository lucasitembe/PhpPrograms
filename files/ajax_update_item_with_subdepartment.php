<?php
include("./includes/connection.php");
if(isset($_POST['Sub_Department_ID'])){
   $Sub_Department_ID=$_POST['Sub_Department_ID'];
   $Check_In_Type=$_POST['Check_In_Type'];
   if($Sub_Department_ID!=0){
       $sql_update_sub_depatment_id_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Sub_Department_ID='$Sub_Department_ID' WHERE Check_In_Type='$Check_In_Type' AND Sub_Department_ID='0'") or die(mysqli_error($conn));
   
       if($sql_update_sub_depatment_id_result){
          echo "success"; 
       }else{
          echo "fail";
       }
   }else{
       echo "zero";
   }
}

