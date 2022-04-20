<?php
    include_once("./functions/returninward.php");

    if(isset($_GET['Inward_Item_ID'])){
        $Inward_Item_ID = $_GET['Inward_Item_ID'];
    }else{
        $Inward_Item_ID = 0;
    }

    if(isset($_GET['Quantity_Returned'])){
        $Quantity_Returned = $_GET['Quantity_Returned'];
    }else{
        $Quantity_Returned = 0;
    }

    if ($Inward_Item_ID > 0 && $Quantity_Returned >= 0) {
        $Update_Quantity_Returned = Update_Quantity_Returned($Inward_Item_ID, $Quantity_Returned);
        $hasError = $Update_Quantity_Returned["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Quantity_Returned["errorMsg"];
        }
    }
?>