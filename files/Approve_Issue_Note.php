<?php

session_start();
include("./includes/connection.php");
include_once("./functions/database.php");
include_once("./functions/issuenotes.php");
include_once("./functions/items.php");

if (isset($_SESSION['HAS_ERROR'])) {
    unset($_SESSION['HAS_ERROR']);
}

$Controler = 'yes';

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get employee Given_Username
if (isset($_SESSION['userinfo']['Given_Username'])) {
    $Given_Username = $_SESSION['userinfo']['Given_Username'];
} else {
    $Given_Username = 0;
}

//get employee Given_Password
if (isset($_SESSION['userinfo']['Given_Password'])) {
    $Given_Password = $_SESSION['userinfo']['Given_Password'];
} else {
    $Given_Password = 0;
}

if (isset($_GET['Supervisor_Username'])) {
    $Supervisor_Username = $_GET['Supervisor_Username'];
} else {
    $Supervisor_Username = '';
}

if (isset($_GET['Supervisor_Password'])) {
    $Supervisor_Password = md5($_GET['Supervisor_Password']);
} else {
    $Supervisor_Password = md5('');
}

if (isset($_GET['Supervisor_Comment'])) {
    $Supervisor_Comment = $_GET['Supervisor_Comment'];
} else {
    $Supervisor_Comment = '';
}

if (isset($_GET['Issue_ID'])) {
    $Issue_ID = $_GET['Issue_ID'];
} else {
    $Issue_ID = 0;
}

$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];

//if (strtolower($Given_Username) == strtolower($Supervisor_Username) && $Given_Password == $Supervisor_Password || true) {
    Start_Transaction();
    $_SESSION['HAS_ERROR']=false;

    $Store_Issue = 0;
    $Store_Need = 0;
    $Requisition_ID = 0;


    $Requisition_Data_Result = Query_DB("SELECT rq.Store_Issue, rq.Store_Need, ri.Requisition_ID FROM tbl_requisition rq, tbl_requisition_items ri WHERE  rq.Requisition_ID = ri.Requisition_ID
                                         AND rq.Requisition_Status = 'Not Approved' AND ri.Issue_ID = '$Issue_ID' limit 1");

    $hasError = $Requisition_Data_Result["error"];

    if (!$hasError) {
        if (!empty($Requisition_Data_Result["data"])) {
            $Store_Issue = $Requisition_Data_Result["data"][0]['Store_Issue'];
            $Store_Need = $Requisition_Data_Result["data"][0]['Store_Need'];
            $Requisition_ID = $Requisition_Data_Result["data"][0]['Requisition_ID'];
        }
    } else {
        echo $Requisition_Data_Result["errorMsg"];
    }

    if ($Store_Issue != 0 && $Store_Need != 0) {
        $Document_Date = Get_Time_Now();

        $Item_List = Get_Active_Items_For_Issue_Note($Requisition_ID, $Issue_ID);

        if (!empty($Item_List)) {
            foreach ($Item_List as $Item) {
                $Item_ID = $Item['Item_ID'];
                $Quantity_Issued = $Item['Quantity_Issued'];
                $Requisition_Item_ID = $Item['Requisition_Item_ID'];
                $Store_Issue_Balance = Get_Item_Balance($Item_ID, $Store_Issue);
                $Store_Balance = $Store_Issue_Balance['Item_Balance'];
                $Bar_Code = $Store_Issue_Balance['Bar_Code'];
                $Last_Buying_Price = Get_Last_Buy_Price($Item_ID);
                
                mysqli_query($conn,"UPDATE tbl_requisition_items SET Last_Buying_Price = '$Last_Buying_Price' WHERE Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));

                //get pre balance
                $select_pre_balance = mysqli_query($conn,"SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($select_pre_balance);
                if ($nm > 0) {
                    while ($dt = mysqli_fetch_array($select_pre_balance)) {
                        $Pre_Balance = $dt['Item_Balance'];
                    }
                } else {
                    $Pre_Balance = 0;
                }

                //update balance by adding items received
                $updt_query = mysqli_query($conn,"UPDATE tbl_items_balance SET Item_Balance = Item_Balance - '$Quantity_Issued' WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));

                if (!$updt_query) {
                    $_SESSION['HAS_ERROR'] = true;
                }

                $Deparment_Requesting_ID = $_GET['Deparment_Requesting_ID'];

                $select_appropriate_qty = mysqli_query($conn,"SELECT * FROM Stock_Movement_Control WHERE Item_Id = '$Item_ID' AND Status = 'recieved' AND Sub_Deparment_Id = '$Sub_Department_ID' AND Item_Balance > 0 ORDER BY Expire_Date ASC LIMIT 1");
                while($stock_data = mysqli_fetch_assoc($select_appropriate_qty)){
                    $remaning = $stock_data['Item_Balance'] - $Quantity_Issued;
                    $ts_id = $stock_data['Id'];
                    $Batch_No = $stock_data['Batch_No'];
                    $Buying_Price = $stock_data['Buying_Price'];
                    $Expire_Date = $stock_data['Expire_Date'];
                    $Bar_Code = $stock_data['Bar_Code'];
                }

                $insert_audit = mysqli_query($conn,"INSERT into tbl_stock_ledger_controler(Item_ID, Sub_Department_ID,Internal_Destination, Movement_Type, Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
                                                    VALUES('$Item_ID','$Store_Issue','$Store_Need','Issue Note','$Pre_Balance',($Pre_Balance - $Quantity_Issued),(select now()),(select now()),'$Issue_ID')") ;

                if (!$insert_audit) { $_SESSION['HAS_ERROR'] = true; }
            }

            if (!$_SESSION['HAS_ERROR']) {
                $update_status = mysqli_query($conn,"UPDATE tbl_requisition SET Requisition_Status = 'Served', Supervisor_ID = '$Employee_ID',Supervisor_Comment = '$Supervisor_Comment',Approval_Date_Time = (select now()) 
                                                    WHERE Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                Commit_Transaction();
                 echo 1;  //Process 
            } else {
                Rollback_Transaction();
                echo 'no no'; //Process not successful .
                die("UPDATE tbl_requisition SET Requisition_Status = 'Served',Supervisor_ID = '$Employee_ID',Supervisor_Comment = '$Supervisor_Comment',Approval_Date_Time = (select now()) 
                WHERE Requisition_ID = '$Requisition_ID'");

                die(mysqli_error($conn));
            }
        } else {
            Rollback_Transaction();
            echo "non"; //no items found .
        }
    } else {
        Rollback_Transaction();
        echo "Invalid"; // Store issue is missing .
    }
?>