<?php
include("./includes/connection.php"); 
if(isset($_GET['Requisition_Item_ID'])&&isset($_GET['Last_Buying_Price'])){
   $Last_Buying_Price=$_GET['Last_Buying_Price'];
   $Requisition_Item_ID=$_GET['Requisition_Item_ID'];
   $Last_Buying_Price=mysqli_real_escape_string($conn,$Last_Buying_Price);
   $Requisition_Item_ID=mysqli_real_escape_string($conn,$Requisition_Item_ID);
 
    //mysqli_query($conn,"START TRANSACTION");
    mysqli_query($conn,"UPDATE tbl_requisition_items SET `Last_Buying_Price`='$Last_Buying_Price' WHERE `Requisition_Item_ID`='$Item_Requisition_Item_ID'") or die(mysqli_error($conn));//
    //mysqli_query($conn,"COMMIT"); 
    echo "Number of rows affected: " . mysql_affected_rows() . "==>$Item_Requisition_Item_ID";
}
