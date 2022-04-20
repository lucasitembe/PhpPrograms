<?php 
include("includes/connection.php");
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : "";
$sponsor_id = isset($_GET['sponsor_id']) ? $_GET['sponsor_id'] : "";
$billing_type = isset($_GET['billing_type']) ? $_GET['billing_type'] : "";
$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : "";
$sponsor_name = isset($_GET['sponsor_name']) ? $_GET['sponsor_name'] : "";
$transaction_type = isset($_GET['transaction_type']) ? $_GET['transaction_type'] : "";
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : "";
$transaction_status = isset($_GET['transaction_status']) ? $_GET['transaction_status'] : "";
$Sub_Department_ID = isset($_GET['location']) ? $_GET['location'] : "";
// $Payment_Date_And_Time = Date("Y-m-d h:i:s");
$Receipt_Date = Date("Y-m-d");
$Payment_Date_And_Time = '(SELECT NOW())';
$today = Date("Y-m-d");
if (isset($_GET['quantity'])) {
    $quantity = $_GET['quantity'];
} else {
    $quantity = 1;
}
// item list cache data

$Check_In_Type = "Laboratory";
$price = isset($_GET['price']) ? $_GET['price'] : "";
$Patient_Direction = "others";
$Status = 'active';
$Transaction_Date_And_Time = '(SELECT NOW())';
$Service_Date_And_Time = '(SELECT NOW())';
$Process_Status = 'inactive';
$Doctor_Comment = isset($_GET['doctor_comment']) ? $_GET['doctor_comment'] : "";
$doctors_selected_clinic = 21;

$response = array();
$branch_id = 1;
// array_push($response, $patient_id, $sponsor_id, $billing_type, $sponsor_name, $transaction_type, $employee_id, $transaction_status, $Sub_Department_ID);

if (!empty(checkForPaymentCacheIdChange($today, $Check_In_Type, $patient_id))) {
    $payment_cache_ID = checkForPaymentCacheIdChange($today, $Check_In_Type, $patient_id);

    #if data are in cache then insert into item list cache
    array_push($response, getItemName($item_id));
    $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID, Price,Quantity, Patient_Direction,               Status,Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,              Transaction_Type,Service_Date_And_Time,Clinic_ID)
            VALUES ('$Check_In_Type', '$item_id', '$price', '$quantity','$Patient_Direction','$Status','$payment_cache_ID',NOW(),'$Process_Status', '$Doctor_Comment','$Sub_Department_ID','$transaction_type','$Service_Date_And_Time','$doctors_selected_clinic')";

    $result2 = mysqli_query($conn,$insert_query2) or die(mysqli_error($conn));
    if ($result2) {
        echo json_encode($response);
    } else {
        array_push($response, "failed");
    }

} else {


#add to item cache table for temporary storage;
    $sql = "INSERT INTO tbl_payment_cache(Registration_ID,Employee_ID,Payment_Date_And_Time,Sponsor_ID, Sponsor_Name, Billing_Type, Receipt_Date,Transaction_status, Transaction_type, branch_id) 
        VALUES('$patient_id','$employee_id',$Payment_Date_And_Time,'$sponsor_id', '$sponsor_name','$billing_type', $Receipt_Date,'$transaction_status','$transaction_type','$branch_id')";

    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    $payment_cache_ID = mysql_insert_id();


    if ($result) {
        #if data are in cache then insert into item list cache
        array_push($response, getItemName($item_id));
        $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID, Price,Quantity, Patient_Direction,               Status,Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,              Transaction_Type,Service_Date_And_Time,Clinic_ID)
                VALUES ('$Check_In_Type', '$item_id', '$price', '$quantity','$Patient_Direction','$Status','$payment_cache_ID',NOW(),'$Process_Status', '$Doctor_Comment','$Sub_Department_ID','$transaction_type','$Service_Date_And_Time','$doctors_selected_clinic')";

        $result2 = mysqli_query($conn,$insert_query2) or die(mysqli_error($conn));
        if ($result2) {
            echo json_encode($response);
        } else {
            array_push($response, "failed");
        }
    }


}



function getItemName($item_id)
{
    $sql = "SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id'";
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $Product_Name;
}

// print_r(checkForPaymentCacheIdChange($today, $Check_In_Type, $patient_id));
function checkForPaymentCacheIdChange($today, $Check_In_Type, $patient_id)
{
    $payment_cache_id = "";
    $sql = "SELECT pc.Payment_Cache_ID FROM tbl_payment_cache pc JOIN tbl_item_list_cache ilc WHERE Date(pc.Payment_Date_And_Time)='$today' AND Check_In_Type='$Check_In_Type' AND pc.Registration_ID='$patient_id'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        $payment_cache_id = $Payment_Cache_ID;
    }
    return $payment_cache_id;
}

?>