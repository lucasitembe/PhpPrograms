<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/stocktaking.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Stock_Taking_ID > 0) {
        Start_Transaction();

        $Document_Date = Get_Time_Now();
        $Stock_Taking_Item_List = Get_Stock_Taking_Items($Stock_Taking_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Stock_Taking_Item_List
        );

        $Stock_Taking = Get_Stock_Taking($Stock_Taking_ID);
        if ($Stock_Taking['Stock_Taking_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Stock_Taking['Previous_Stock_Taking_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Stock_Taking_Data" => $Current_Edit));
        }
        $Previous_Stock_Taking_Data = toJson($Previous_Edit);

        foreach($Stock_Taking_Item_List as $To_Cancel_Stock_Taking_Item) {
            $Item_ID = $To_Cancel_Stock_Taking_Item['Item_ID'];
            $Over_Quantity = $To_Cancel_Stock_Taking_Item['Over_Quantity'];
            $Under_Quantity = $To_Cancel_Stock_Taking_Item['Under_Quantity'];
            $Item_Remark = $To_Cancel_Stock_Taking_Item['Item_Remark'];
            $Stock_Taking_Location = $Stock_Taking['Sub_Department_ID'];

            if ($Over_Quantity > 0) {
                if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity, false)){
                    $error_code = "no";
                    break;
                }
            } else if ($Under_Quantity > 0) {
                if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity, true)){
                    $error_code = "no";
                    break;
                }
            }

        }

        if ($error_code == "yes" && Remove_Stock_Taking_Items_By_Stock_Taking_ID($Stock_Taking_ID)) {
            $Update_Status = Update_Stock_Taking($Stock_Taking_ID, array(
                "Previous_Stock_Taking_Data" => $Previous_Stock_Taking_Data,
                "Stock_Taking_Status" => "canceled"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes";
                Commit_Transaction();
                if (isset($_SESSION['Stock_Taking_ID'])) {
                    unset($_SESSION['Stock_Taking_ID']);
                }
            } else {
                $error_code = "no - unable to cancel stock taking";
                echo $Update_Status["errorMsg"];
                Rollback_Transaction();
            }
        } else {
            Rollback_Transaction();
            $error_code = "no - unable to update balance";
        }
    } else {
        $error_code = "no- no stock taking id";
    }

    echo $error_code;