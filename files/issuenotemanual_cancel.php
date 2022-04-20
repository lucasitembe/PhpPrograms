<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Issue_ID'])) {
        $Issue_ID = $_GET['Issue_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Issue_ID > 0) {
        Start_Transaction();

        $Issue_Note_Manual_Item_List = Get_Issue_Note_Manual_Items($Issue_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Issue_Note_Manual_Item_List
        );

        $Issue_Note_Manual = Get_Issue_Note_Manual($Issue_ID);
        if ($Issue_Note_Manual['status'] == "edited") {
            $Previous_Edit = jsonToArray($Issue_Note_Manual['Previous_Issue_Note_Manual_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Issue_Note_Manual_Data" => $Current_Edit));
        }
        $Previous_Issue_Note_Manual_Data = toJson($Previous_Edit);

        $Document_Date = Get_Time_Now();
        foreach($Issue_Note_Manual_Item_List as $To_Cancel_INM_Item) {
            $Item_ID = $To_Cancel_INM_Item['Item_ID'];
            $Quantity_Issued = $To_Cancel_INM_Item['Quantity_Issued'];
            $Store_Issuing = $Issue_Note_Manual['Store_Issuing'];
            $Store_Need = $Issue_Note_Manual['Store_Need'];

            if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual", $Store_Need, null, null, $Issue_ID, $Document_Date, $Quantity_Issued, true)){
                $error_code = "no";
                break;
            }
        }

        if ($error_code == "yes" && Remove_Issue_Note_Manual_Items_By_Issue_Note_Manual_ID($Issue_ID)) {
            $Update_Status = Update_Issue_Note_Manual($Issue_ID, array(
                "Previous_Issue_Note_Manual_Data" => $Previous_Issue_Note_Manual_Data,
                "status" => "canceled"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes";
                Commit_Transaction();
                if (isset($_SESSION['Issue_ID'])) {
                    unset($_SESSION['Issue_ID']);
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