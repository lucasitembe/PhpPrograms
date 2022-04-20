<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Sponsor_All() {
        $Sponsor_List = array();
    
        $Sponsor_Result = Get_From("tbl_sponsor", array(), array(), 0);
        $hasError = $Sponsor_Result["error"];
        if (!$hasError) {
            $Sponsor_List = array_merge($Sponsor_List, $Sponsor_Result["data"]);
        } else {
            echo $Sponsor_Result["errorMsg"];
        }
    
        return $Sponsor_List;
    }

    function Get_Sponsor($Sponsor_ID) {
        $Sponsor = array();

        $Sponsor_Result = Get_From("tbl_sponsor", array("Sponsor_ID", "=", $Sponsor_ID), array(), 1);
        $hasError = $Sponsor_Result["error"];
        if (!$hasError) {
            if (!empty($Sponsor_Result["data"])) {
                $Sponsor = $Sponsor_Result["data"][0];
            }
        } else {
            echo $Sponsor_Result["errorMsg"];
        }

        return $Sponsor;
    }
?>