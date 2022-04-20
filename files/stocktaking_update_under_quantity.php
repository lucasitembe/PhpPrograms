<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Stock_Taking_Item_ID'])){
        $Stock_Taking_Item_ID = $_GET['Stock_Taking_Item_ID'];
    }else{
        $Stock_Taking_Item_ID = 0;
    }

    if(isset($_GET['Under_Quantity'])){
        $Under_Quantity = $_GET['Under_Quantity'];
    }else{
        $Under_Quantity = 0;
    }

    if ($Stock_Taking_Item_ID > 0 && $Under_Quantity >= 0) {
        $Update_Under_Quantity = Update_Under_Quantity($Stock_Taking_Item_ID, $Under_Quantity);
        $hasError = $Update_Under_Quantity["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Under_Quantity["errorMsg"];
        }
    }
?>