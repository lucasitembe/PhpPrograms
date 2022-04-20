<?php
    include("./includes/connection.php");
    $Select_Size='';
    if(isset($_GET['Sub_Department_ID'])&&($_GET['Sub_Department_ID']!='')){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $Item_ID = $_GET['Item_ID'];
        
        $Select_size = "select COUNT(Distinct pc.Registration_ID) as queue_size from tbl_payment_cache pc,tbl_item_list_cache ilc
                        where pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND
                        ilc.Sub_Department_ID = $Sub_Department_ID AND Item_ID = '$Item_ID' AND
                        ilc.Status = 'active' ";
        
        
                $result = @mysqli_query($conn,$Select_size);
                $row = @mysqli_fetch_assoc($result);
    echo $row['queue_size'];
    }
?>