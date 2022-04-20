<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/itemsdisposal.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Disposal_ID'])) {
        $Disposal_ID = $_GET['Disposal_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Disposal_ID > 0) {
        Start_Transaction();

        $Document_Date = Get_Time_Now();
        $Items_Disposal_Item_List = Get_Items_Disposal_Items($Disposal_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Items_Disposal_Item_List
        );

        $Disposal = Get_Items_Disposal($Disposal_ID);
        if ($Disposal['Disposal_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Disposal['Previous_Disposal_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Disposal_Data" => $Current_Edit));
        }
        $Previous_Disposal_Data = toJson($Previous_Edit);

        foreach($Items_Disposal_Item_List as $To_Cancel_Disposal_Item) {
            $Item_ID = $To_Cancel_Disposal_Item['Item_ID'];
            $Quantity_Disposed = $To_Cancel_Disposal_Item['Quantity_Disposed'];
            $Item_Remark = $To_Cancel_Disposal_Item['Item_Remark'];
            $Disposal_Location = $Disposal['Sub_Department_ID'];

            if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Document_Date, $Quantity_Disposed, true)){
                $error_code = "no";
                break;
            }
        }

        if ($error_code == "yes" && Remove_Disposal_Items_By_Disposal_ID($Disposal_ID)) {
            $Update_Status = Update_Disposal($Disposal_ID, array(
                "Previous_Disposal_Data" => $Previous_Disposal_Data,
                "Disposal_Status" => "canceled"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes";
                Commit_Transaction();
                if (isset($_SESSION['Disposal_ID'])) {
                    unset($_SESSION['Disposal_ID']);
                }
            } else {
                $error_code = "no - unable to cancel disposal";
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