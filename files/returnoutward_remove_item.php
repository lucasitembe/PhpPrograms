<?php
    include_once("./functions/returnoutward.php");

    if(isset($_GET['Outward_Item_ID'])){
        $Outward_Item_ID = $_GET['Outward_Item_ID'];
    }else{
        $Outward_Item_ID = 0;
    }

    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    if ($Outward_Item_ID > 0) {
        if (Remove_Return_Outward_Item($Outward_Item_ID)) {
            include_once("./returnoutward_show_items.php");
        }
    }
?>