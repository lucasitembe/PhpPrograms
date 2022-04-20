<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/returninward.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Inward_ID'])) {
        $Inward_ID = $_GET['Inward_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Inward_ID > 0) {
        Start_Transaction();

        $Document_Date = Get_Time_Now();
        $Return_Inward_Item_List = Get_Return_Inward_Items($Inward_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Return_Inward_Item_List
        );

        $Return_Inward = Get_Return_Inward($Inward_ID);
        if ($Return_Inward['Inward_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Return_Inward['Previous_Return_Inward_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Return_Inward_Data" => $Current_Edit));
        }
        $Previous_Return_Inward_Data = toJson($Previous_Edit);

        foreach($Return_Inward_Item_List as $To_Cancel_INM_Item) {
            $Item_ID = $To_Cancel_INM_Item['Item_ID'];
            
            $Quantity_Returned = $To_Cancel_INM_Item['Quantity_Returned'];
            $Store_Sub_Department_ID = $Return_Inward['Store_Sub_Department_ID'];
            $Return_Sub_Department_ID = $Return_Inward['Return_Sub_Department_ID'];

            if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, false)){
                $error_code = "no";
                break;
            }

            if (!Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, true)){
                $error_code = "no";
                break;
            }
        }

        if ($error_code == "yes" && Remove_Return_Inward_Items_By_Return_Inward_ID($Inward_ID)) {
            $Update_Status = Update_Return_Inward($Inward_ID, array(
                "Previous_Return_Inward_Data" => $Previous_Return_Inward_Data,
                "Inward_Status" => "canceled"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes";
                Commit_Transaction();
                if (isset($_SESSION['Inward_ID'])) {
                    unset($_SESSION['Inward_ID']);
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