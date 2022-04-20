<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/requisition.php");
    include_once("./functions/items.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }

    if (isset($_GET['description'])) {
        $description = $_GET['description'];
    }


    if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }

    $error_code = 0;
//    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        Start_Transaction();

        $Requisition_Item_List = Get_Requisition_Items($Requisition_ID);
        foreach($Requisition_Item_List as $Requisition_Item) {
            Update_DB("tbl_requisition_items",
                array("Container_Qty" => 1, "Items_Qty" => $Requisition_Item['Quantity_Required']),
                array("Requisition_Item_ID", "=", $Requisition_Item['Requisition_Item_ID']), array());
        }

        if ($error_code == 0) {
            $Update_Status = Update_Requisition_Status($Requisition_ID, "saved");
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                mysqli_query($conn,"UPDATE tbl_requisition SET Sent_Date_Time = (select now()), Requisition_Description = '$description' where Requisition_ID = '$Requisition_ID'");
                if (isset($_SESSION['Requisition_ID'])) {
                    unset($_SESSION['Requisition_ID']);
                }
            } else {
                $error_code = 4;
                echo $Update_Status["errorMsg"];
                Rollback_Transaction();
            }
        } else {
            Rollback_Transaction();
        }
//    } else {
//        $error_code = 0;
//    }

    echo $error_code;