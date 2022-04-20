<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Patient($Registration_ID) {
        $Patient = array();

        $Patient_Result = Get_From("tbl_patient_registration", array("Registration_ID", "=", $Registration_ID), array(), 1);
        $hasError = $Patient_Result["error"];
        if (!$hasError) {
            $Patient = $Patient_Result["data"][0];
        } else {
            echo $Patient_Result["errorMsg"];
        }

        return $Patient;
    }
?>