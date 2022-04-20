
<?php
    include("../includes/connection.php");
       $priority=$_POST['priority'];  
       $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
     if(isset($Payment_Item_Cache_List_ID)&& isset($priority)){

    //   die("UPDATE tbl_item_list_cache SET Service_Date_And_Time='$DateOfService' WHERE Payment_Item_Cache_List_ID='$priority'");
       $query=  mysqli_query($conn,"UPDATE tbl_item_list_cache SET priority='$priority' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
       if($query){
           if($priority == 'Urgent'){
            //    die("UPDATE tbl_surgery_appointment SET Surgery_Status='Active' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
                $query=  mysqli_query($conn,"UPDATE tbl_surgery_appointment SET Surgery_Status='Active', Approved_Date = NOW(), Final_Decision = 'Accepted' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
           }
           echo 'Successfully changed!';
       }  else {
           echo 'Changing error,try again!';
       }
 }
 
 mysqli_close($conn);
?>
