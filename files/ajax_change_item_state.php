<?php

    require_once('./includes/connection.php');
    session_start();
    $Emp = $_SESSION['userinfo']['Employee_ID'];
    // die($Emp);
    // if(isset($_POST['medicationChart'])){
        $Statevalue =mysqli_real_escape_string($conn,  $_POST['Statevalue']);
        $Liquid_Item_Value =mysqli_real_escape_string($conn,  $_POST['Liquid_Item_Value']);
        $Item_ID =mysqli_real_escape_string($conn,  $_POST['Item_ID']);
        $route_type = $_POST['route_type'];
        $updateItemState = mysqli_query($conn, "UPDATE tbl_items SET Statevalue='$Statevalue', Liquid_Item_Value='$Liquid_Item_Value', route_type='$route_type'  WHERE Item_ID='$Item_ID'") or mysqli_error($conn);
        if($updateItemState){
            echo "Updated successful";
        }else{
            echo "Did not Update";
        }
    // }


    //ALTER TABLE `tbl_items` ADD `Statevalue` VARCHAR(50) NOT NULL AFTER `time_interval`, ADD `Liquid_Item_Value` FLOAT NOT NULL AFTER `Statevalue`, ADD `route_type` VARCHAR(150) NOT NULL AFTER `Liquid_Item_Value`; 