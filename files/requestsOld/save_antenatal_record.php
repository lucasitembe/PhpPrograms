<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID=  mysql_real_escape_string($_POST['patient_ID']);
        $lmp = mysql_real_escape_string($_POST['lmp']);
        $edd = mysql_real_escape_string($_POST['edd']);
        $first_Date = mysql_real_escape_string($_POST['first_Date']);
        $bp= mysql_real_escape_string($_POST['bp']);
        $oedema = mysql_real_escape_string($_POST['oedema']);
        $breast = mysql_real_escape_string($_POST['breast']);
        $hb= mysql_real_escape_string($_POST['hb']);
        $blood_group = mysql_real_escape_string($_POST['blood_group']);
        $lungs = mysql_real_escape_string($_POST['lungs']);
        $rh = mysql_real_escape_string($_POST['rh']);
        $abdomen=  mysql_real_escape_string($_POST['abdomen']);
        $pmtct0 = mysql_real_escape_string($_POST['pmtct0']);
        $pmtct1 = mysql_real_escape_string($_POST['pmtct1']);
        $pmtct2 = mysql_real_escape_string($_POST['pmtct2']);
        $urine=  mysql_real_escape_string($_POST['urine']);
        $ppr = mysql_real_escape_string($_POST['ppr']);
        $remarks= mysql_real_escape_string($_POST['remarks']);
        $tt= mysql_real_escape_string($_POST['tt']);
        $checkExist=mysql_query("SELECT * FROM tbl_antenatal_records WHERE Patient_ID='$patient_ID'");
        $num_rows= mysql_num_rows($checkExist);
        
        if($num_rows>0){
        $insert = mysql_query("UPDATE tbl_antenatal_records SET LMP='$lmp',EDD='$edd',BREASTS='$breast',LUNGS='$lungs',ABDOMEN='$abdomen',URINE='$urine',REMARKS='$remarks',SEENBY='$Employee_ID',EXAM_DATE='$first_Date',BP='$bp',OEDEMA='$oedema',HB='$hb',BLOOD_GROUP='$blood_group',RH='$rh',PMTCT_1='$pmtct0',PMTCT_2='$pmtct1',PMTCT_3='$pmtct2',PPR='$ppr',TT='$tt' WHERE Patient_ID='$patient_ID'");
        
        }  else {

        $insert = mysql_query("INSERT INTO tbl_antenatal_records (Patient_ID,LMP,EDD,BREASTS,LUNGS,ABDOMEN,URINE,REMARKS,SEENBY,EXAM_DATE,BP,OEDEMA,HB,BLOOD_GROUP,RH,PMTCT_1,PMTCT_2,PMTCT_3,PPR,DATE_VAL,EXAM_BY,TT)
        VALUES ('$patient_ID','$lmp','$edd','$breast','$lungs','$abdomen','$urine','$remarks','$Employee_ID','$first_Date','$bp','$oedema','$hb','$blood_group','$rh','$pmtct0','$pmtct1','$pmtct2','$ppr','NOW()','$Employee_ID','$tt')");
   
        }
        
         if ($insert) {
            echo 'Data saved successfully';
            
        } else {
            echo 'Data saving error';
        } 
        

    }
}