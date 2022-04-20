<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/itemsdisposal.php");

    if(isset($_GET['Disposal_ID'])){
        $Disposal_ID = $_GET['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }

    if(isset($_GET['Disposal_Location'])){
        $Disposal_Location = $_GET['Disposal_Location'];
    }else{
        $Disposal_Location = 0;
    }

    if(isset($_GET['Disposing_Officer_ID'])){
        $Disposing_Officer_ID = $_GET['Disposing_Officer_ID'];
    }else{
        $Disposing_Officer_ID = 0;
    }

    if(isset($_GET['Disposal_Description'])){
        $Disposal_Description = $_GET['Disposal_Description'];
    }else{
        $Disposal_Description = 0;
    }

    if(isset($_GET['Branch_ID'])){
        $Branch_ID = $_GET['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['Disposal_Date'])){
        $Disposal_Date = $_GET['Disposal_Date'];
    }else{
        $Disposal_Date = Get_Time_Now();
    }

    if(isset($_GET['reason_for_adjustment'])){
        $reason_for_adjustment = $_GET['reason_for_adjustment'];
    }

    $Today = Get_Time_Now();

    if ($Disposal_ID == "new"){
        $Insert_Items_Disposal = Insert_DB("tbl_disposal", array(
            "Sub_Department_ID" => $Disposal_Location,
            "Employee_ID" => $Disposing_Officer_ID,
            "Created_Date" => $Today,
            "Created_Date_And_Time" => $Today,
            "Disposed_Date" => $Disposal_Date,
            "Disposal_Description" => $Disposal_Description,
            "Branch_ID" => $Branch_ID,
            "reason_for_adjustment" => $reason_for_adjustment
        ));

        $hasError = $Insert_Items_Disposal["error"];
        if (!$hasError) {
            $_SESSION['Disposal_ID'] = $Insert_Items_Disposal["id"];
            echo $Insert_Items_Disposal["id"];
        } else {
            echo $Insert_Items_Disposal['errorMsg'];
        }
    }
?>