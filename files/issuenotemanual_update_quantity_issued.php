<?php
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Requisition_Item_ID'])){
        $Requisition_Item_ID = $_GET['Requisition_Item_ID'];
    }else{
        $Requisition_Item_ID = 0;
    }

    if(isset($_GET['Quantity_Issued'])){
        $Quantity_Issued = $_GET['Quantity_Issued'];
    }else{
        $Quantity_Issued = 0;
    }

    if ($Requisition_Item_ID > 0 && $Quantity_Issued >= 0) {
        $Update_Quantity_Issued = Update_Quantity_Issued($Requisition_Item_ID, $Quantity_Issued);
        $hasError = $Update_Quantity_Issued["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Quantity_Issued["errorMsg"];
        }
    }
?>