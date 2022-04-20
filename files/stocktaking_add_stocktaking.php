<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Stock_Taking_ID'])){
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }else{
        $Stock_Taking_ID = 0;
    }

    if(isset($_GET['Stock_Taking_Location'])){
        $Stock_Taking_Location = $_GET['Stock_Taking_Location'];
    }else{
        $Stock_Taking_Location = 0;
    }

    if(isset($_GET['Stock_Taking_Officer_ID'])){
        $Stock_Taking_Officer_ID = $_GET['Stock_Taking_Officer_ID'];
    }else{
        $Stock_Taking_Officer_ID = 0;
    }

    if(isset($_GET['Stock_Taking_Description'])){
        $Stock_Taking_Description = $_GET['Stock_Taking_Description'];
    }else{
        $Stock_Taking_Description = 0;
    }

    if(isset($_GET['Branch_ID'])){
        $Branch_ID = $_GET['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['Stock_Taking_Date'])){
        $Stock_Taking_Date = $_GET['Stock_Taking_Date'];
    }else{
        $Stock_Taking_Date = Get_Time_Now();
    }

    $Today = Get_Time_Now();

    if ($Stock_Taking_ID == "new"){
        $Insert_Items_Stock_Taking = Insert_DB("tbl_stocktaking", array(
            "Sub_Department_ID" => $Stock_Taking_Location,
            "Employee_ID" => $Stock_Taking_Officer_ID,
            "Created_Date" => $Today,
            "Created_Date_And_Time" => $Today,
            "Stock_Taking_Date" => $Stock_Taking_Date,
            "Stock_Taking_Description" => $Stock_Taking_Description,
            "Branch_ID" => $Branch_ID,
            "Stock_Taking_Status" => "pending"
        ));

        $hasError = $Insert_Items_Stock_Taking["error"];
        if (!$hasError) {
            $_SESSION['Stock_Taking_ID'] = $Insert_Items_Stock_Taking["id"];
            echo $Insert_Items_Stock_Taking["id"];
        } else {
            echo $Insert_Items_Stock_Taking['errorMsg'];
        }
    }
?>