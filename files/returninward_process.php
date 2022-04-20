<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/returninward.php");
    include_once("./functions/items.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Inward_ID'])) {
        $Inward_ID = $_GET['Inward_ID'];
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        $Return_Inward_Item_List = Get_Return_Inward_Items($Inward_ID);
        //$Return_Inward = Get_Return_Inward($Inward_ID);

        Start_Transaction();
        $Document_Date = Get_Time_Now();
        foreach($Return_Inward_Item_List as $Return_Inward_Item) {
            $Quantity_Returned = $Return_Inward_Item['Quantity_Returned'];
            $Item_ID = $Return_Inward_Item['Item_ID'];
            $Store_Sub_Department_ID = $Return_Inward_Item['Store_Sub_Department_ID'];
            $Return_Sub_Department_ID = $Return_Inward_Item['Return_Sub_Department_ID'];
            $Store_Balance = $Return_Inward_Item['Store_Balance'];

            /**if ($Quantity_Returned > $Store_Balance) { allow negative balance
                $error_code = 2;
                break;
            } else {**/
                if ($Quantity_Returned != 0) {
                    if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, true)){
                        $error_code = 3;
                        break;
                    }

                    $List_Of_Sub_Department = Get_Sub_Department_By_List_Of_Department_Nature(
                        array(
                            array("nature" => "Storage And Supply"),
                            array("nature" => "Pharmacy")
                        ));
                    $exist_in_the_list = false;
                    foreach($List_Of_Sub_Department as $Sub_Department) {
                        if($Return_Sub_Department_ID == $Sub_Department['Sub_Department_ID']) {
                            $exist_in_the_list = true;
                            break;
                        }
                    }

                    $not_bad = true;
                    if ($exist_in_the_list) {
                        $Return_Sub_Department_Balance = Get_Item_Balance($Item_ID, $Return_Sub_Department_ID);
                        if ($Return_Sub_Department_Balance['Item_Balance'] < $Quantity_Returned) {
                            $error_code = 2; $not_bad = false;
                            break;
                        }
                    }


                    if ($not_bad && !Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, false)){
                        $error_code = 3;
                        break;
                    }
                }
            //} allow negative balance
        }

        if ($error_code == 0) {
            $Update_Status = Update_Return_Inward_Status($Inward_ID, "saved");
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                if (isset($_SESSION['Inward_ID'])) {
                    unset($_SESSION['Inward_ID']);
                }
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