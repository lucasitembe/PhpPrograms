<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Stock_Taking_ID'])){
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }else{
        $Stock_Taking_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $Stock_Taking_ID > 0) {
        if (Check_Stock_Taking_Item($Item_ID, $Stock_Taking_ID)) {
            $Exists = "yes";
        }
    }

    echo $Exists;
?>