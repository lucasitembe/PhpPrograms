<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['IssueManual_ID'])){
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }else{
        $IssueManual_ID = 0;
    }

    if(isset($_GET['Store_Issuing_ID'])){
        $Store_Issuing_ID = $_GET['Store_Issuing_ID'];
    }else{
        $Store_Issuing_ID = 0;
    }

    if(isset($_GET['Employee_Issuing_ID'])){
        $Employee_Issuing_ID = $_GET['Employee_Issuing_ID'];
    }else{
        $Employee_Issuing_ID = 0;
    }

    if(isset($_GET['Store_Requesting_ID'])){
        $Store_Requesting_ID = $_GET['Store_Requesting_ID'];
    }else{
        $Store_Requesting_ID = 0;
    }

    if(isset($_GET['Employee_Requesting_ID'])){
        $Employee_Requesting_ID = $_GET['Employee_Requesting_ID'];
    }else{
        $Employee_Requesting_ID = 0;
    }

    if(isset($_GET['Branch_ID'])){
        $Branch_ID = $_GET['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['IV_Number'])){
        $IV_Number = $_GET['IV_Number'];
    }else{
        $IV_Number = 0;
    }


    if(isset($_GET['Issue_Date'])){
        $Issue_Date = $_GET['Issue_Date'];
    }else{
        $Issue_Date = Get_Time_Now();
    }

    if ($IssueManual_ID == "new"){
        $Insert_Issue_Manual = Insert_DB("tbl_issuesmanual", array(
            "Issue_Date_And_Time" => $Issue_Date,
            "Created_Date_And_Time" => Get_Time_Now(),
            "Employee_Issuing" => $Employee_Issuing_ID,
            "Employee_Receiving" => $Employee_Requesting_ID,
            "Store_Issuing" => $Store_Issuing_ID,
            "Store_Need" => $Store_Requesting_ID,
            "Branch_ID" => $Branch_ID,
            "IV_Number" => $IV_Number
        ));

        $hasError = $Insert_Issue_Manual["error"];
        if (!$hasError) {
            $_SESSION['IssueManual_ID'] = $Insert_Issue_Manual["id"];
            echo $Insert_Issue_Manual["id"];
        } else {
            echo $Insert_Issue_Manual['errorMsg'];
        }
    }
?>