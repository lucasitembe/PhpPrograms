<?php
    include_once("./includes/connection.php");
    include_once("./functions/returninward.php");

    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if ($Inward_ID > 0 && $Item_ID > 0 ) {
        $Insert_Return_Inward_Item = Insert_DB("tbl_return_inward_items", array(
            "Quantity_Returned" => 0,
            "Item_ID" => $Item_ID,
            "Inward_ID" => $Inward_ID
        ));

        $hasError = $Insert_Return_Inward_Item["error"];
        if (!$hasError) {
            $Insert_Return_Inward_Item_ID = $Insert_Return_Inward_Item["id"];
        } else {
            echo $Insert_Return_Inward['errorMsg'];
        }

        include_once("./returninward_show_items.php");
    }

?>