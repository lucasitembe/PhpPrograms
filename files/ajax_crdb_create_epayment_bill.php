<?php

session_start();
include("./includes/connection.php");
include_once("./functions/items.php");
$Amount = 0;
$HAS_ERROR = false;
$Invoice_Number = "0";
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$selected_Payment_Item_Cache_List_ID = $_POST['selected_Payment_Item_Cache_List_ID'];
$Registration_ID = $_POST['Registration_ID'];
$Payment_Cache_ID = $_POST['Payment_Cache_ID'];
$grand_total_price = $_POST['grand_total_price'];

$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysql_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}

if (isset($_POST['clear'])) {
    
} else {
    $count_processed_item = 0;
    foreach ($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID) {
        ///verify if this transaction already created
        if (isset($_SESSION['Transaction_ID'])) {
            $Transaction_ID = $_SESSION['Transaction_ID'];
            $sql_verify_if_transaction_already_created_result = mysqli_query($conn, "SELECT Transaction_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));

            if (mysqli_num_rows($sql_verify_if_transaction_already_created_result) > 0) {
                $count_processed_item++;
            }
        }
    }
}

if ($count_processed_item <= 0) {
    Start_Transaction();
    require_once './includes/Folio_Number_Generator.php';
    $insert = mysqli_query($conn, "insert into tbl_bank_transaction_cache(Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date, Source) values ('$Registration_ID','$grand_total_price','$Employee_ID',(select now()),(select now()),'Revenue Center')") or die(mysqli_error($conn) . 'One');
    if (!$insert) {
        $HAS_ERROR = true;
        die("4");
    }
    $select_result = mysqli_query($conn, "select Transaction_ID from tbl_bank_transaction_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn) . 'two');
    $no = mysqli_num_rows($select_result);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_result)) {
            $Transaction_ID = $row['Transaction_ID'];
        }
    } else {
        $Transaction_ID = 0;
    }
    if ($Transaction_ID != 0) {
        $retrieve_rs = mysqli_query($conn, "SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
        $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
        $hospital_id = $data_hosp_id['hospital_id'];

        $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT) . str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);

        //update code
        $update = mysqli_query($conn, "update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
        if (!$update) {
            $HAS_ERROR = true;
            die("5");
        }

        foreach ($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID) {
            $result = mysqli_query($conn, "update tbl_item_list_cache set Transaction_ID = '$Transaction_ID', ePayment_Status = 'Served' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
            if (!$result) {
                $HAS_ERROR = true;
                die("6");
            }
        }

        if (!$HAS_ERROR) {
            $_SESSION['Transaction_ID'] = $Transaction_ID;
            $customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Name FROM tbl_patient_registration where Registration_ID = '$Registration_ID'"))['Patient_Name'];
            $qlp = "INSERT INTO billdetails (`BillID`,`Customer`,`CurrencyCode`,`BillAmount`,`UserID`) VALUES ('$Transaction_ID','$customer','TZS','$grand_total_price','$Employee_ID')";
            $qrl = mysqli_query($conn, $qlp) or die(mysqli_error($conn));
            if ($qrl)
                echo $Transaction_ID;
            else
                echo 'erroro error';
            Commit_Transaction();
        } else {
            Rollback_Transaction();
            echo "fail";
        }
    } else {
        echo "fail";
    }
} else {
    echo $Transaction_ID;
}