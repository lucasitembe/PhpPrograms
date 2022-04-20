<?php
include("./includes/connection.php");
if(isset($_GET['AuthorizationNo']) && $_GET['AuthorizationNo']!=""){
   $AuthorizationNo=$_GET['AuthorizationNo'];    
}
if(isset($_GET['Check_In_ID']) && $_GET['Check_In_ID']!=""){
    $Check_In_ID=$_GET['Check_In_ID'];
}
$status=mysqli_query($conn,"UPDATE tbl_check_in SET AuthorizationNo='$AuthorizationNo' WHERE Check_In_ID='$Check_In_ID' ");
if($status){
    echo "Authorization # Updated successfully";
} else {
  echo "Ooops..!!! Something went wrong, Please try again..";  
}