<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Employee_All() {
        global $conn;
        $Employee_List = array();

        $Employee_Result = Get_From("tbl_employee", array(), array(), 0);
        $hasError = $Employee_Result["error"];
        if (!$hasError) {
            $Employee_List = array_merge($Employee_List, $Employee_Result["data"]);
        } else {
            echo $Employee_Result["errorMsg"];
        }

        return $Employee_List;
    }

    function Get_Employee($Employee_ID) {
        global $conn;
        $Employee = array();

        $Employee_Result = Get_From("tbl_employee", array("Employee_ID", "=", $Employee_ID), array(), 1);
        $hasError = $Employee_Result["error"];
        if (!$hasError) {
            $Employee = $Employee_Result["data"][0];
        } else {
            echo $Employee_Result["errorMsg"];
        }

        return $Employee;
    }

    function Is_Logged_In_User($Given_Username, $Given_Password) {
        global $conn;
        if((isset($_SESSION['userinfo']['Given_Username'])) &&
           (strtolower($_SESSION['userinfo']['Given_Username']) == strtolower($Given_Username))
            && (isset($_SESSION['userinfo']['Given_Password'])) && ($_SESSION['userinfo']['Given_Password'] == $Given_Password)){
            return true;
        } else {
            return false;
        }
    }
?>