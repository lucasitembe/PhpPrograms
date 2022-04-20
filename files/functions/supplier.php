<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Supplier_All() {
        $Supplier_List = array();
    
        $Supplier_Result = Get_From("tbl_supplier", array(), array(), 0);
        $hasError = $Supplier_Result["error"];
        if (!$hasError) {
            $Supplier_List = array_merge($Supplier_List, $Supplier_Result["data"]);
        } else {
            echo $Supplier_Result["errorMsg"];
        }
    
        return $Supplier_List;
    }

    function Get_Supplier($Supplier_ID) {
        $Supplier = array();

        $Supplier_Result = Get_From("tbl_supplier", array("Supplier_ID", "=", $Supplier_ID), array(), 1);
        $hasError = $Supplier_Result["error"];
        if (!$hasError) {
            if (!empty($Supplier_Result["data"])) {
                $Supplier = $Supplier_Result["data"][0];
            }
        } else {
            echo $Supplier_Result["errorMsg"];
        }

        return $Supplier;
    }
?>