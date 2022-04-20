<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Stock_Taking_Item_ID'])){
        $Stock_Taking_Item_ID = $_GET['Stock_Taking_Item_ID'];
    }else{
        $Stock_Taking_Item_ID = 0;
    }

    if(isset($_GET['Over_Quantity'])){
        $Over_Quantity = $_GET['Over_Quantity'];
    }else{
        $Over_Quantity = 0;
    }

    if ($Stock_Taking_Item_ID > 0 && $Over_Quantity >= 0) {
        $Update_Over_Quantity = Update_Over_Quantity($Stock_Taking_Item_ID, $Over_Quantity);
        $hasError = $Update_Over_Quantity["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Over_Quantity["errorMsg"];
        }
    }
?>