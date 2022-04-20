<?php
    include_once("./functions/itemsdisposal.php");

    if(isset($_GET['Disposal_Item_ID'])){
        $Disposal_Item_ID = $_GET['Disposal_Item_ID'];
    }else{
        $Disposal_Item_ID = 0;
    }

    if(isset($_GET['Quantity_Disposed'])){
        $Quantity_Disposed = $_GET['Quantity_Disposed'];
    }else{
        $Quantity_Disposed = 0;
    }

    if ($Disposal_Item_ID > 0 && $Quantity_Disposed >= 0) {
        $Update_Quantity_Disposed = Update_Quantity_Disposed($Disposal_Item_ID, $Quantity_Disposed);
        $hasError = $Update_Quantity_Disposed["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Quantity_Disposed["errorMsg"];
        }
    }
?>