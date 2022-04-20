
<?php
    include("../includes/connection.php");
       if(isset($_POST['action'])){
     if($_POST['action']=='update'){
       $Item_ID=$_POST['pay_ID'];  
       $DateOfService=$_POST['DateOfService'];
//       echo "UPDATE tbl_item_list_cache SET Service_Date_And_Time='$DateOfService' WHERE Payment_Item_Cache_List_ID='$Item_ID'";
       $query=  mysqli_query($conn,"UPDATE tbl_item_list_cache SET Service_Date_And_Time='$DateOfService' WHERE Payment_Item_Cache_List_ID='$Item_ID'");
       if($query){
           echo 'Successfully changed!';
       }  else {
           echo 'Changing error,try again!';
       }
 }
 }
?>
