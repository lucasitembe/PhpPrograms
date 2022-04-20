<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Item_Category_All() {
        $Item_Category_List = array();

        $Item_Category_Result = Get_From("tbl_item_category", array(), array(), 0);
        $hasError = $Item_Category_Result["error"];
        if (!$hasError) {
            $Item_Category_List = array_merge($Item_Category_List, $Item_Category_Result["data"]);
        } else {
            echo $Item_Category_Result["errorMsg"];
        }

        return $Item_Category_List;
    }

    function Get_Item_Category($Item_Category_ID) {
        $Item_Category = array();

        $Item_Category_Result = Get_From("tbl_item_category", array("Item_Category_ID", "=", $Item_Category_ID), array(), 1);
        $hasError = $Item_Category_Result["error"];
        if (!$hasError) {
            $Item_Category = $Item_Category_Result["data"][0];
        } else {
            echo $Item_Category_Result["errorMsg"];
        }

        return $Item_Category;
    }
?>