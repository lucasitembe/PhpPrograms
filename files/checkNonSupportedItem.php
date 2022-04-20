<?php
    include("./includes/connection.php");
    $Select_Size='';
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
        $Sponsor_name = $_GET['Sponsor_name'];
        $sponsor_id = "(SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Sponsor_name')";
        $Select_item = "SELECT sponsor_id, item_ID FROM tbl_sponsor_non_supported_items WHERE item_ID=$Item_ID and sponsor_id=$sponsor_id";
        $result = @mysqli_query($conn,$Select_item);
        if(mysql_numrows($result)>0){
         echo "not supported";
        }else{
         echo "supported";
        }
    }
?>