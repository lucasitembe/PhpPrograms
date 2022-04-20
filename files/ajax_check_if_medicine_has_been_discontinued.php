<?php
include("./includes/connection.php");
if(isset($_POST['Item_ID'])){
$Item_ID=$_POST['Item_ID'];
$Registration_ID=$_POST['Registration_ID'];
   $sql_check_if_the_selected_medicine_has_been_discontinued_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_inpatient_medicines_given WHERE Registration_ID='$Registration_ID' AND Discontinue_Status='yes' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));     

   if(mysqli_num_rows($sql_check_if_the_selected_medicine_has_been_discontinued_result)>0){
       echo "yes";
   }else{
       echo "no$Payment_Item_Cache_List_ID";
   }
}