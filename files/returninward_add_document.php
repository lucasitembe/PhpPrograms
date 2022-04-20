<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/returninward.php");

    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    if(isset($_GET['Store_Sub_Department_ID'])){
        $Store_Sub_Department_ID = $_GET['Store_Sub_Department_ID'];
    }else{
        $Store_Sub_Department_ID = 0;
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Return_Sub_Department_ID'])){
        $Return_Sub_Department_ID = $_GET['Return_Sub_Department_ID'];
    }else{
        $Return_Sub_Department_ID = 0;
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

    if ($Inward_ID == "new"){
        $Insert_Return_Inward = Insert_DB("tbl_return_inward", array(
            "Inward_Date" => $Transaction_Date,
            "Sent_Date_Time" => Get_Time_Now(),
            "Employee_ID" => $Employee_ID,
            "Store_Sub_Department_ID" => $Store_Sub_Department_ID,
            "Return_Sub_Department_ID" => $Return_Sub_Department_ID
        ));

        $hasError = $Insert_Return_Inward["error"];
        if (!$hasError) {
            $_SESSION['Inward_ID'] = $Insert_Return_Inward["id"];
            echo $Insert_Return_Inward["id"];
        } else {
            echo $Insert_Return_Inward['errorMsg'];
        }
    }
?>