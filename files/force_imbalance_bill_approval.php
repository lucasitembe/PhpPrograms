<?php
session_start();
include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}else{
        $Employee_ID = 0;
}


if(isset($_GET['Admision_ID'])){
   $Admision_ID=$_GET['Admision_ID'];
}else{
    $Admision_ID="null"; 
}

if(isset($_GET['imbalance_approval_reason'])){
   $imbalance_approval_reason=$_GET['imbalance_approval_reason'];
}else{
    $imbalance_approval_reason="null";
}

if(isset($_GET['approve_bill_status'])){
   $approve_bill_status=$_GET['approve_bill_status'];
}else{
    $approve_bill_status="null";
}
if(isset($_GET['excemtion_attachment'])){
   $excemtion_attachment=$_GET['excemtion_attachment'];
}else{
    $excemtion_attachment="0";
}
if(isset($_GET['excemption_number'])){
   $excemption_number=$_GET['excemption_number'];
}else{ 
    $excemption_number="";
}
$Exemption_ID = $_GET['excemption_number'];
     
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
 }else{
     $Registration_ID="0";
 }
 if(isset($_GET['Patient_Bill_ID'])){
    $Patient_Bill_ID=$_GET['Patient_Bill_ID'];
 }else{ 
     $Patient_Bill_ID="";
 }

 if(isset($_GET['Grand_Total'])){
    $Grand_Total=$_GET['Grand_Total'];
 }else{ 
     $Grand_Total="";
 }
 if(isset($_GET['Grand_Total_Direct_Cash'])){
    $Grand_Total_Direct_Cash=$_GET['Grand_Total_Direct_Cash'];
 }else{ 
     $Grand_Total_Direct_Cash="";
 }
 
$update = mysqli_query($conn,"UPDATE tbl_admission set excemtion_attachment='$excemtion_attachment',excemption_number='$excemption_number',Cash_Bill_Status = 'cleared',Credit_Bill_Status='cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(),Credit_Clearer_ID='$Employee_ID', Cash_Clearer_ID = '$Employee_ID',imbalance_bill_approval_reason='$imbalance_approval_reason',approve_bill_status='$approve_bill_status' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));

if($update){
   
    if($approve_bill_status == 'dept' && $Grand_Total > $Grand_Total_Direct_Cash){
        $select_bill =  mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_patient_debt WHERE Patient_Bill_ID='$Patient_Bill_ID' AND Registration_ID='$Registration_ID' AND Admision_ID='$Admision_ID' ") or die(mysqli_error($conn));
       
        if(mysqli_num_rows($select_bill)>0 ){           
           $clear_bill =mysqli_query($conn, "UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
            if($clear_bill){
               $clear_bill =mysqli_query($conn, "UPDATE tbl_prepaid_details SET Status='cleared', Cleared_by='$Employee_ID', Cleared_Date_Time=NOW() WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
               if($clear_bill){
                   echo "success";
               }              
            }
        }else{
            $sql_insert_dept = mysqli_query($conn, "INSERT INTO tbl_patient_debt(Admision_ID,Patient_Bill_ID,Registration_ID, Grand_Total, Grand_Total_Direct_Cash, Employee_ID )VALUES('$Admision_ID', '$Patient_Bill_ID', '$Registration_ID', '$Grand_Total', '$Grand_Total_Direct_Cash', '$Employee_ID')") or die(mysqli_error($conn));
            if($sql_insert_dept){
                
                $clear_bill =mysqli_query($conn, "UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
                if($clear_bill){
                    $clear_bill =mysqli_query($conn, "UPDATE tbl_prepaid_details SET Status='cleared', Cleared_by='$Employee_ID', Cleared_Date_Time=NOW() WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
                    if($clear_bill){
                        echo "success";
                    }
                   
                }
            }else{
                echo "failed to create debt";
            }
        }
    }else{
       
        $clear_bill =mysqli_query($conn, "UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
        if($clear_bill){
            $clear_bill =mysqli_query($conn, "UPDATE tbl_prepaid_details SET Status='cleared', Cleared_by='$Employee_ID', Cleared_Date_Time=NOW() WHERE Patient_bill_ID='$Patient_Bill_ID'" ) or die(mysqli_error($conn));
            if($clear_bill){
                echo "success";
            }
           
        }
    }
}else{
    echo "Failed to approve";
} 