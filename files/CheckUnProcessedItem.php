<?php 
@session_start();
include("./includes/connection.php");
//ALTER TABLE `tbl_check_in_details` ADD `Reason_of_Approval` TEXT NOT NULL AFTER `next_of_kin_phone`, ADD `ApprovedBy` INT NULL AFTER `Reason_of_Approval`; 
    if(isset($_POST['UnProcessedItem'])){
        $Registration_ID = $_POST['Registration_ID'];
        $Check_In_ID = $_POST['Check_In_ID'];
        $unprocesseditems = mysqli_real_escape_string($conn, $_POST['unprocesseditems']);
        $Employee_ID =$_SESSION['userinfo']['Employee_ID'];

        $UnprocessedItem= mysqli_query($conn, "UPDATE tbl_check_in_details SET Reason_of_Approval='$unprocesseditems', ApprovedBy='$Employee_ID'  WHERE Check_In_ID='$Check_In_ID' AND  Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
        if($UnprocessedItem){
            echo "Updated";
        }else{
            echo "Error occoured";
        }
    }

    if(isset($_POST['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
        $update_Cache = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Status='removed' WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");

        if($update_Cache){
            if ($update_Cache) {
                echo 'Item removed successfully';
            } else {
                echo 'Item removing failure';
            }
        }
    }

    if(isset($_POST['savenhifresponce'])){
        $nhifresponce = $_POST['nhifresponce'];
        $Bill_ID = $_POST['Bill_ID'];
        $mydata = json_decode($nhifresponce);

        $SubmissionID= $mydata->SubmissionID; 
        $SubmissionNo = $mydata->SubmissionNo;
        $FolioNo = $mydata->FolioNo;
        $AmountClaimed = $mydata->AmountClaimed;
        $AuthorizationNo = $mydata->AuthorizationNo;
        $ClaimYear = $mydata->ClaimYear;
        $ClaimMonth = $mydata->ClaimMonth;
        $BillNo = $mydata->BillNo;
        $Sent_by = $_SESSION['userinfo']['Employee_ID'];


        // ALTER TABLE `tbl_claims_form_nhif` ADD `Bill_ID` INT NULL AFTER `ClaimYear`, ADD `SubmissionID` VARCHAR(250) NOT NULL AFTER `Bill_ID`, ADD `SubmissionNo` INT NOT NULL AFTER `SubmissionID`, ADD `AmountClaimed` INT NOT NULL AFTER `SubmissionNo`; 
        // ALTER TABLE `tbl_claims_form_nhif` ADD `Sent_by` INT NOT NULL AFTER `AmountClaimed`; 

        $insertclaimresponce = mysqli_query($conn, "INSERT INTO tbl_received_claims(Bill_ID,FolioNO, ClaimMonth, ClaimYear,  SubmissionID, SubmissionNo, AmountClaimed, Sent_by) VALUES('$BillNo','$FolioNo', '$ClaimMonth', '$ClaimYear','$SubmissionID', '$SubmissionNo',  '$AmountClaimed', '$Sent_by' )") or die(mysqli_error($conn));
        if($insertclaimresponce){
            $updatclaimdata = mysqli_query($conn, "UPDATE tbl_bills SET SubmissionID='$SubmissionID', SubmissionNo='$SubmissionNo' WHERE Bill_ID='$Bill_ID'") or die(mysqli_error($conn));
            if($updatclaimdata){
               echo "done d"; 
            }else{
                echo "half done";
            }
            
        }else{
            echo "none";
        }
    }