<?php session_start();
include ("./includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$payMent = $_POST['payVal'];
// die("-------------------$payMent");
$specimen = $_POST['id'];
if (isset($_POST['rejectSpecimen'])) {
    $reason = filter_input(INPUT_POST, 'reason');
    $check_if_exit = mysqli_query($conn, "SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $payMent . "' AND Specimen_ID='" . $specimen . "' ") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($check_if_exit);
    $status = 'Rejected';
    if (mysqli_num_rows($check_if_exit) > 0) {
        $specimenRejectionQr = "UPDATE tbl_specimen_results SET Rejection_Status='$status',rejected_reason='$reason',Date_Rejected=NOW(),rejected_by='" . $Employee_ID . "' WHERE result_ID='" . $row['result_ID'] . "'";
    } else {
        $specimenRejectionQr = "INSERT INTO tbl_specimen_results (payment_item_ID,Specimen_ID,collection_Status,specimen_results_Employee_ID,TimeCollected,BarCode,rejected_reason,rejected_by,Date_Rejected,Rejection_Status) VALUES ('$payMent','$specimen','','$Employee_ID','','','$reason','$Employee_ID',NOW(),'Rejected')";
    }
    $Query = mysqli_query($conn, $specimenRejectionQr) or die(mysqli_error($conn));
    if ($Query) {
        echo 1;
    } else {
        echo 0;
    }
} elseif (isset($_POST['Undocheck'])) {
    $deleteSpecimen = "UPDATE tbl_specimen_results SET collection_Status='' WHERE payment_item_ID='" . $payMent . "' AND Specimen_ID='" . $specimen . "'";
    $Query = mysqli_query($conn, $deleteSpecimen);
    if ($Query) {
        echo "success";
    } else {
        echo "failure";
    }
    
} elseif (isset($_POST['receiveSpecimen'])) {
    $sample_quality = $_POST['sample_quality'];
    $explaination = $_POST['explaination'];
    $check_if_exit = mysqli_query($conn, "SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $payMent . "' AND Specimen_ID='" . $specimen . "' ") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($check_if_exit);
    $rows_number = mysqli_num_rows($check_if_exit);
    $status = "received";
    if ($rows_number > 0) {
        $specimenRejectionQr = "UPDATE tbl_specimen_results SET explaination='$explaination',sample_quality='$sample_quality',received_status='$status',time_received=NOW(),Employee_ID_receive='" . $Employee_ID . "' WHERE result_ID='" . $row['result_ID'] . "'";
    }
    $results = mysqli_query($conn, $specimenRejectionQr);
    if ($results) {
        echo "Received Successfully";
    } else {
        echo 'Fail to receive specimen';
    }
} else {
    $manualBarcode = $_POST['manualBarcode'];
    $Patient_Payment_Test_ID = $_POST['Patient_Payment_Test_ID'];
    $Payment_ID = $_POST['payment_id'];
    $Laboratory_Test_specimen_ID = $_POST['Patient_Payment_Item_List_ID'];
    $patient_Id = $_POST['patient_id'];
    $status = 'collected';
    $dateTime = date('Y-m-d H:i:s');
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'submit') {
            $check_if_exit = mysqli_query($conn, "SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $payMent . "' AND Specimen_ID='" . $specimen . "' ") or die(mysqli_error($conn));
            $row = mysqli_fetch_assoc($check_if_exit);
            $rows_number = mysqli_num_rows($check_if_exit);
            if ($rows_number > 0) {
                $specimenRejectionQr = "UPDATE tbl_specimen_results SET collection_Status='$status',TimeCollected=NOW(),specimen_results_Employee_ID='" . $Employee_ID . "' WHERE result_ID='" . $row['result_ID'] . "'";
            } else {
                $specimenRejectionQr = "INSERT INTO tbl_specimen_results (payment_item_ID,Specimen_ID,collection_Status,specimen_results_Employee_ID,TimeCollected,BarCode) VALUES ('$payMent','$specimen','$status','$Employee_ID',NOW(),'')";
            }
            $results = mysqli_query($conn, $specimenRejectionQr);
            if ($results) {
                echo "success";
            } else {
                echo 'Specimen collection failed';
            }
        }
    }
}
exit();
echo $id = $_POST['id'] . '  ' . $Employee_ID . '  ' . $Patient_Payment_Test_ID . '  ' . $Payment_ID . '  ' . $Laboratory_Test_specimen_ID . ' ' . $patient_Id;
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Patient_Payment_Test_ID = filter_input(INPUT_GET, 'Patient_Payment_Test_ID');
$Payment_ID = filter_input(INPUT_GET, 'payment_id');
$Laboratory_Test_specimen_ID = filter_input(INPUT_GET, 'Laboratory_Test_specimen_ID');
if (isset($_GET['Status_From'])) if (filter_input(INPUT_GET, 'Status_From') == 'payment') {
    $sql1 = mysqli_query($conn, "INSERT INTO tbl_patient_payment_test_specimen ( Specimen_Status, Time_Taken, Employee_ID, Patient_Payment_Test_ID, Laboratory_Test_specimen_ID,Payment_ID)
             VALUES ( 'Collected',(SELECT NOW()) , '$Employee_ID', '$Patient_Payment_Test_ID', '$Laboratory_Test_specimen_ID','$Payment_ID')");
    if ($sql1) {
        $update_specimen_taken = mysqli_query($conn, "UPDATE tbl_patient_payment_test
                                                        set Specimen_Taken =Specimen_Taken+1 where Patient_Payment_Item_List_ID = '" . filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID') . "' ");
    } else {
        echo "Specimen Fail To Be Registered";
    }
} else if (filter_input(INPUT_GET, 'Status_From') == 'cache') {
    $sql1 = mysqli_query($conn, "INSERT INTO tbl_patient_cache_test_specimen ( Specimen_Status, Time_Taken, Employee_ID, Patient_Cache_Test_ID, Laboratory_Test_specimen_ID,Payment_Item_Cache_List_ID)
             VALUES ( 'Collected',(SELECT NOW()) , '$Employee_ID', '$Patient_Payment_Test_ID', '$Laboratory_Test_specimen_ID','" . filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID') . "')");
    if ($sql1) {
        $update_specimen_taken = mysqli_query($conn, "UPDATE tbl_patient_cache_test
                                                        set     Specimen_Taken =Specimen_Taken+1 where Payment_Item_Cache_List_ID = '" . filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID') . "' ");
    } else {
        echo "Specimen Fail To Be Registered";
    }
};