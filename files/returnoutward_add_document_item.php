<?php
    include_once("./includes/connection.php");
    include_once("./functions/returnoutward.php");

    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if ($Outward_ID > 0 && $Item_ID > 0 ) {
        $Insert_Return_Outward_Item = Insert_DB("tbl_return_outward_items", array(
            "Quantity_Returned" => 0,
            "Item_ID" => $Item_ID,
            "Outward_ID" => $Outward_ID
        ));

        $hasError = $Insert_Return_Outward_Item["error"];
        if (!$hasError) {
            $Insert_Return_Outward_Item_ID = $Insert_Return_Outward_Item["id"];
        } else {
            echo $Insert_Return_Outward['errorMsg'];
        }

        include_once("./returnoutward_show_items.php");
    }

?>