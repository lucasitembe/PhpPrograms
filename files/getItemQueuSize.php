<?php
    include("./includes/connection.php");
    $Select_Size='';
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='')){
        $Item_ID = $_GET['Item_ID'];
        
        $Select_size = "select COUNT(Item_ID) as Queue_Size from tbl_item_list_cache
                        WHERE Item_ID = '$Item_ID' AND Status = 'active' ";
        
                $result = @mysqli_query($conn,$Select_size);
                $row = @mysqli_fetch_assoc($result);
        echo $row['Queue_Size'];
    }
?>