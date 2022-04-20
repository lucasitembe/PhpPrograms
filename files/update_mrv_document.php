<?php
include("includes/connection.php");
$job_notes=$_POST['job_notes'];
$spare_required=$_POST['spare_required'];
$part_date=$_POST['part_date'];
$procurerer=$_POST['procurerer'];
$issue_date=$_POST['issue_date'];
$issuer=$_POST['issuer'];
$engineers=$_POST['engineers'];
$procurement_order=$_POST['procurement_order'];
$client_info=$_POST['client_info'];
$visual_test=$_POST['visual_test'];
$electrical_test=$_POST['electrical_test'];
$functional_test=$_POST['functional_test'];
$engineer_sign=$_POST['engineer_sign'];
$comments_recon=$_POST['comments_recon'];
$Requisition_ID = $_POST['Requisition_ID'];
$Action = $_POST['Action'];
$Assigned_Engineer_ID = $_POST['Assigned_Engineer_ID'];
$Mrv_Description = $_POST['Mrv_Description'];
$assistance_engineer = implode(', ', $_POST['assistance_engineer']);
$Indicated_Days = $_POST['Indicated_Days'];

if($procurement_order == 'yes'){
    $part_date = "CURDATE()";
}else{
    $part_date = "NULL";
}

    if(!empty($Requisition_ID) && $Action == 'Submit' && (!empty($Assigned_Engineer_ID) && $Assigned_Engineer_ID != 0)){
        $update_requisition_for_engineering = mysqli_query($conn, "UPDATE tbl_engineering_requisition SET job_notes='$job_notes', spare_required='$spare_required', part_date=$part_date, procurerer='$procurerer',issue_date= NOW(), issuer='$issuer', engineers='$engineers', procurement_order='$procurement_order', form_id='$form_id', client_info='$client_info', visual_test='$visual_test', electrical_test='$electrical_test', functional_test='$functional_test', engineer_sign='$engineer_sign', job_progress='performed', recommendations = '$comments_recon' WHERE requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));

            if($update_requisition_for_engineering){
                echo "The Document Was Submitted Successfully!";
            }else{
                echo "Failed To Submit the Document, Please Contact ICT Personnel for further Assistance";
            }

    }elseif(!empty($Requisition_ID) && $Action == 'Update' && (!empty($Assigned_Engineer_ID) && $Assigned_Engineer_ID != 0)){
        $update_requisition_for_engineering = mysqli_query($conn, "UPDATE tbl_engineering_requisition SET job_notes='$job_notes', spare_required='$spare_required', part_date=$part_date, procurerer='$procurerer',issue_date= NOW(), issuer='$issuer', engineers='$engineers', procurement_order='$procurement_order', form_id='$form_id', client_info='$client_info', visual_test='$visual_test', electrical_test='$electrical_test', functional_test='$functional_test', engineer_sign='$engineer_sign', Mrv_Description = '$Mrv_Description', Assigned_Engineer_ID = '$Assigned_Engineer_ID', recommendations = '$comments_recon', Indicated_Days = '$Indicated_Days', assistance_engineer = '$assistance_engineer' WHERE requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));

    }elseif($Assigned_Engineer_ID == 0 || $Assigned_Engineer_ID == ''){
            echo "The System was Idle and Terminated your Session, Please Log IN to process this Document!";
    }

mysqli_close($conn);
?>