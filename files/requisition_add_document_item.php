<?php
    include_once("./includes/connection.php");
    include_once("./functions/requisition.php");

    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if ($Requisition_ID > 0 && $Item_ID > 0 ) {
        $Insert_Requisition_Item = Insert_DB("tbl_requisition_items", array(
            "Quantity_Required" => 0,
            "Item_ID" => $Item_ID,
            "Requisition_ID" => $Requisition_ID
        ));

        $hasError = $Insert_Requisition_Item["error"];
        if (!$hasError) {
            $Insert_Requisition_Item_ID = $Insert_Requisition_Item["id"];
        } else {
            echo $Insert_Requisition['errorMsg'];
        }

        include_once("./requisition_show_items.php");
    }

?>