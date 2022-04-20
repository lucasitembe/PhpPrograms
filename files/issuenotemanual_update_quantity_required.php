<?php
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Requisition_Item_ID'])){
        $Requisition_Item_ID = $_GET['Requisition_Item_ID'];
    }else{
        $Requisition_Item_ID = 0;
    }

    if(isset($_GET['Quantity_Required'])){
        $Quantity_Required = $_GET['Quantity_Required'];
    }else{
        $Quantity_Required = 0;
    }

    if ($Requisition_Item_ID > 0 && $Quantity_Required >= 0) {
        $Update_Quantity_Required = Update_Quantity_Required($Requisition_Item_ID, $Quantity_Required);
        $hasError = $Update_Quantity_Required["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Quantity_Required["errorMsg"];
        }
    }
?>