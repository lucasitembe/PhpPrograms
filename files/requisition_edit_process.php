<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/requisition.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        Start_Transaction();

        $Requisition_Item_List = Get_Requisition_Items($Requisition_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Previous_Data" => $Requisition_Item_List
        );

        $Requisition = Get_Requisition($Requisition_ID);
        if ($Requisition['Requisition_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Requisition['Previous_Requisition_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Requisition_Data" => $Current_Edit));
        }
        $Previous_Requisition_Data = toJson($Previous_Edit);


        if (Remove_Requisition_Items_By_Requisition_ID($Requisition_ID)) {
            $Edited_Requisition_Items = $_SESSION['Requisition']['Items'];
            foreach($Edited_Requisition_Items as $Edited_Requisition_Item) {
                $Item_ID = $Edited_Requisition_Item['Item_ID'];
                $Quantity_Required = $Edited_Requisition_Item['Quantity_Required'];
                $Item_Remark = $Edited_Requisition_Item['Item_Remark'];

                $Insert_Requisition_Item = Insert_DB("tbl_requisition_items", array(
                    "Quantity_Required" => $Quantity_Required,
                    "Items_Qty" => $Quantity_Required,
                    "Item_Remark" => $Item_Remark,
                    "Item_ID" => $Item_ID,
                    "Requisition_ID" => $Requisition_ID
                ));
                $hasError = $Insert_Requisition_Item["error"];
                if ($hasError) {
                    $error_code = 3;
                    echo $Insert_Requisition_Item['errorMsg'];
                }
            }
        }

        if ($error_code == 0) {
            $Update_Status = Update_Requisition($Requisition_ID, array(
                "Previous_Requisition_Data" => $Previous_Requisition_Data,
                "Requisition_Status" => "edited"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                /*if (isset($_SESSION['Requisition_ID'])) {
                    unset($_SESSION['Requisition_ID']);
                }*/
            } else {
                $error_code = 4;
                echo $Update_Status["errorMsg"];
                Rollback_Transaction();
            }
        } else {
            Rollback_Transaction();
        }
    } else {
        $error_code = 0;
    }

    echo $error_code;