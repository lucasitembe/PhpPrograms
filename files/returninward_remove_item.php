<?php
    include_once("./functions/returninward.php");

    if(isset($_GET['Inward_Item_ID'])){
        $Inward_Item_ID = $_GET['Inward_Item_ID'];
    }else{
        $Inward_Item_ID = 0;
    }

    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    if ($Inward_Item_ID > 0) {
        if (Remove_Return_Inward_Item($Inward_Item_ID)) {
            include_once("./returninward_show_items.php");
        }
    }
?>