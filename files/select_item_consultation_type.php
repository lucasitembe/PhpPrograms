<?php
include("./includes/connection.php");
if(isset($_GET['Item_ID'])){
    $Item_ID=$_GET['Item_ID'];
}else{
    $Item_ID='';
}
$sql_select_consultation_type="SELECT Consultation_Type FROM tbl_items WHERE Item_ID='$Item_ID'";
$sql_select_consultation_type_result=mysqli_query($conn,$sql_select_consultation_type) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_consultation_type_result)>0){
   $consultation_row=mysqli_fetch_assoc($sql_select_consultation_type_result);
   $consultation_type=$consultation_row['Consultation_Type'];
   echo "<option>$consultation_type</option>";
}
