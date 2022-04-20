<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID=  mysqli_real_escape_string($conn,$_POST['patient_ID']);
        $lmp = mysqli_real_escape_string($conn,$_POST['lmp']);
        $edd = mysqli_real_escape_string($conn,$_POST['edd']);
        $first_Date = mysqli_real_escape_string($conn,$_POST['first_Date']);
        $bp= mysqli_real_escape_string($conn,$_POST['bp']);
        $oedema = mysqli_real_escape_string($conn,$_POST['oedema']);
        $breast = mysqli_real_escape_string($conn,$_POST['breast']);
        $hb= mysqli_real_escape_string($conn,$_POST['hb']);
        $blood_group = mysqli_real_escape_string($conn,$_POST['blood_group']);
        $lungs = mysqli_real_escape_string($conn,$_POST['lungs']);
        $rh = mysqli_real_escape_string($conn,$_POST['rh']);
        $abdomen=  mysqli_real_escape_string($conn,$_POST['abdomen']);
        $pmtct0 = mysqli_real_escape_string($conn,$_POST['pmtct0']);
        $pmtct1 = mysqli_real_escape_string($conn,$_POST['pmtct1']);
        $pmtct2 = mysqli_real_escape_string($conn,$_POST['pmtct2']);
        $urine=  mysqli_real_escape_string($conn,$_POST['urine']);
        $ppr = mysqli_real_escape_string($conn,$_POST['ppr']);
        $remarks= mysqli_real_escape_string($conn,$_POST['remarks']);
        $tt= mysqli_real_escape_string($conn,$_POST['tt']);
        $checkExist=mysqli_query($conn,"SELECT * FROM tbl_antenatal_records WHERE Patient_ID='$patient_ID'");
        $num_rows= mysqli_num_rows($checkExist);
        
        if($num_rows>0){
        $insert = mysqli_query($conn,"UPDATE tbl_antenatal_records SET LMP='$lmp',EDD='$edd',BREASTS='$breast',LUNGS='$lungs',ABDOMEN='$abdomen',URINE='$urine',REMARKS='$remarks',SEENBY='$Employee_ID',EXAM_DATE='$first_Date',BP='$bp',OEDEMA='$oedema',HB='$hb',BLOOD_GROUP='$blood_group',RH='$rh',PMTCT_1='$pmtct0',PMTCT_2='$pmtct1',PMTCT_3='$pmtct2',PPR='$ppr',TT='$tt' WHERE Patient_ID='$patient_ID'");
        
        }  else {

        $insert = mysqli_query($conn,"INSERT INTO tbl_antenatal_records (Patient_ID,LMP,EDD,BREASTS,LUNGS,ABDOMEN,URINE,REMARKS,SEENBY,EXAM_DATE,BP,OEDEMA,HB,BLOOD_GROUP,RH,PMTCT_1,PMTCT_2,PMTCT_3,PPR,DATE_VAL,EXAM_BY,TT)
        VALUES ('$patient_ID','$lmp','$edd','$breast','$lungs','$abdomen','$urine','$remarks','$Employee_ID','$first_Date','$bp','$oedema','$hb','$blood_group','$rh','$pmtct0','$pmtct1','$pmtct2','$ppr','NOW()','$Employee_ID','$tt')");
   
        }
        
         if ($insert) {
            echo 'Data saved successfully';
            
        } else {
            echo 'Data saving error';
        } 
        

    }
}