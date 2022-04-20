<?php
    include_once("./functions/requisition.php");

    if(isset($_GET['Requisition_Item_ID'])){
        $Requisition_Item_ID = $_GET['Requisition_Item_ID'];
    }else{
        $Requisition_Item_ID = 0;
    }

    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if ($Requisition_Item_ID > 0) {
        if (Remove_Requisition_Item($Requisition_Item_ID)) {
            include_once("./requisition_show_items.php");
        }
    }
?>