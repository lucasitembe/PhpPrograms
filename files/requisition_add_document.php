<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/requisition.php");

    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if(isset($_GET['Requisition_Description'])){
        $Requisition_Description = $_GET['Requisition_Description'];
    }else{
        $Requisition_Description = 0;
    }

    

    if(isset($_GET['Store_Need'])){
        $Store_Need = $_GET['Store_Need'];
    }else{
        $Store_Need = 0;
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Store_Issue'])){
        $Store_Issue = $_GET['Store_Issue'];
    }else{
        $Store_Issue = 0;
    }

    if(isset($_GET['Branch_ID'])){
        $Branch_ID = $_GET['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['Transaction_Date'])){
        $Transaction_Date = $_GET['Transaction_Date'];
    }else{
        $Transaction_Date = Get_Time_Now();
    }

    if ($Requisition_ID == "new"){
        $Insert_Requisition = Insert_DB("tbl_requisition", array(
            "Created_Date" => $Transaction_Date,
            "Created_Date_Time" => Get_Time_Now(),
            "Branch_ID" => $Branch_ID,
            "Employee_ID" => $Employee_ID,
            "Store_Need" => $Store_Need,
            "Store_Issue" => $Store_Issue,
            "Requisition_Description" => $Requisition_Description
        ));

        $hasError = $Insert_Requisition["error"];
        if (!$hasError) {
            $_SESSION['Requisition_ID'] = $Insert_Requisition["id"];
            echo $Insert_Requisition["id"];
        } else {
            echo $Insert_Requisition['errorMsg'];
        }
    }
?>