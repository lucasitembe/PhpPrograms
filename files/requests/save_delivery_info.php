<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save'){ 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID = mysqli_real_escape_string($conn,$_POST['patient_ID']);
        $delivery_date = mysqli_real_escape_string($conn,$_POST['delivery_date']);
        $rapture = mysqli_real_escape_string($conn,$_POST['rapture']);
        $placenta_removed= mysqli_real_escape_string($conn,$_POST['placenta_removed']);
        $membraneremoved = mysqli_real_escape_string($conn,$_POST['membraneremoved']);
        $blood_loss = mysqli_real_escape_string($conn,$_POST['blood_loss']);
        $oxctocin= mysqli_real_escape_string($conn,$_POST['oxctocin']);
        $perineum = mysqli_real_escape_string($conn,$_POST['perineum']);
        $bp= mysqli_real_escape_string($conn,$_POST['bp']);
        $stage1 = mysqli_real_escape_string($conn,$_POST['stage1']);
        $stage2 = mysqli_real_escape_string($conn,$_POST['stage2']);
        $stage3 = mysqli_real_escape_string($conn,$_POST['stage3']);
        $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
        $babySex = mysqli_real_escape_string($conn,$_POST['babySex']);
        $weight = mysqli_real_escape_string($conn,$_POST['weight']);
        $apgar1 = mysqli_real_escape_string($conn,$_POST['apgar1']);
        $apgar5 = mysqli_real_escape_string($conn,$_POST['apgar5']);
        $risk = mysqli_real_escape_string($conn,$_POST['risk']);
        $BCG = mysqli_real_escape_string($conn,$_POST['BCG']);
        $polio = mysqli_real_escape_string($conn,$_POST['polio']);
        $artreason=  mysqli_real_escape_string($conn,$_POST['artreason']);
        $ARV= mysqli_real_escape_string($conn,$_POST['ARV']);
        $checkExist=  mysqli_query($conn,"SELECT * FROM tbl_delivery_information WHERE Patient_ID='$patient_ID'");
        $num_rows=  mysqli_num_rows($checkExist);
        if($num_rows>0){
            $insert = mysqli_query($conn,"UPDATE tbl_delivery_information SET delivery_Date='$delivery_date',delivery_methode='$rapture',artificial_reason='$artreason',placenta_removed='$placenta_removed',completely_removed='$membraneremoved',Blood_lost='$blood_loss',Ergometrine='$oxctocin',Perineum='$perineum',Bp_after_delivery='$bp',Stage_1='$stage1',Stage_2='$stage2',Stage_3='$stage3',Remarks='$remarks',Baby_sex='$babySex',Baby_weight='$weight',Apgar_1='$apgar1',Apgar_5='$apgar5',ARV='$ARV',Risk='$risk',BCG='$BCG',Polio='$polio' WHERE Patient_ID='$patient_ID'");
         
            
        }else{
          $insert = mysqli_query($conn,"INSERT INTO tbl_delivery_information (Patient_ID,delivery_Date,delivery_methode,artificial_reason,placenta_removed,completely_removed,Blood_lost,Ergometrine,Perineum,Employee,Bp_after_delivery,Stage_1,Stage_2,Stage_3,Delivered_By,Remarks,Baby_sex,Baby_weight,Apgar_1,Apgar_5,ARV,Risk,BCG,Polio)
           VALUES ('$patient_ID','$delivery_date','$rapture','$artreason','$placenta_removed','$membraneremoved','$blood_loss','$oxctocin','$perineum','$Employee_ID','$bp','$stage1','$stage2','$stage3','$Employee_ID','$remarks','$babySex','$weight','$apgar1','$apgar5','$ARV','$risk','$BCG','$polio')");
  
        }
        
        
       if ($insert) {
            echo 'Data saved successfully';
            
        } else {
            echo 'Data saving error';
        }
    }
}