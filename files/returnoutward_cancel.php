<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/returnoutward.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Outward_ID'])) {
        $Outward_ID = $_GET['Outward_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Outward_ID > 0) {
        Start_Transaction();

        $Document_Date = Get_Time_Now();
        $Return_Outward_Item_List = Get_Return_Outward_Items($Outward_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Return_Outward_Item_List
        );

        $Return_Outward = Get_Return_Outward($Outward_ID);
        if ($Return_Outward['Outward_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Return_Outward['Previous_Return_Outward_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Return_Outward_Data" => $Current_Edit));
        }
        $Previous_Return_Outward_Data = toJson($Previous_Edit);

        foreach($Return_Outward_Item_List as $To_Cancel_INM_Item) {
            $Item_ID = $To_Cancel_INM_Item['Item_ID'];
            
            $Quantity_Returned = $To_Cancel_INM_Item['Quantity_Returned'];
            $Sub_Department_ID = $Return_Outward['Sub_Department_ID'];
            $Supplier_ID = $Return_Outward['Supplier_ID'];

            if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned, true)){
                $error_code = "no";
                break;
            }
        }

        if ($error_code == "yes" && Remove_Return_Outward_Items_By_Return_Outward_ID($Outward_ID)) {
            $Update_Status = Update_Return_Outward($Outward_ID, array(
                "Previous_Return_Outward_Data" => $Previous_Return_Outward_Data,
                "Outward_Status" => "canceled"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes";
                Commit_Transaction();
                if (isset($_SESSION['Outward_ID'])) {
                    unset($_SESSION['Outward_ID']);
                }
            } else {
                $error_code = "no - unable to cancel document";
                echo $Update_Status["errorMsg"];
                Rollback_Transaction();
            }
        } else {
            Rollback_Transaction();
            $error_code = "no - unable to update balance";
        }
    } else {
        $error_code = "no- no disposal id";
    }

    echo $error_code;