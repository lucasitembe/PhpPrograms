<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save'){ 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID = mysql_real_escape_string($_POST['patient_ID']);
        $delivery_date = mysql_real_escape_string($_POST['delivery_date']);
        $rapture = mysql_real_escape_string($_POST['rapture']);
        $placenta_removed= mysql_real_escape_string($_POST['placenta_removed']);
        $membraneremoved = mysql_real_escape_string($_POST['membraneremoved']);
        $blood_loss = mysql_real_escape_string($_POST['blood_loss']);
        $oxctocin= mysql_real_escape_string($_POST['oxctocin']);
        $perineum = mysql_real_escape_string($_POST['perineum']);
        $bp= mysql_real_escape_string($_POST['bp']);
        $stage1 = mysql_real_escape_string($_POST['stage1']);
        $stage2 = mysql_real_escape_string($_POST['stage2']);
        $stage3 = mysql_real_escape_string($_POST['stage3']);
        $remarks = mysql_real_escape_string($_POST['remarks']);
        $babySex = mysql_real_escape_string($_POST['babySex']);
        $weight = mysql_real_escape_string($_POST['weight']);
        $apgar1 = mysql_real_escape_string($_POST['apgar1']);
        $apgar5 = mysql_real_escape_string($_POST['apgar5']);
        $risk = mysql_real_escape_string($_POST['risk']);
        $BCG = mysql_real_escape_string($_POST['BCG']);
        $polio = mysql_real_escape_string($_POST['polio']);
        $artreason=  mysql_real_escape_string($_POST['artreason']);
        $ARV= mysql_real_escape_string($_POST['ARV']);
        $checkExist=  mysql_query("SELECT * FROM tbl_delivery_information WHERE Patient_ID='$patient_ID'");
        $num_rows=  mysql_num_rows($checkExist);
        if($num_rows>0){
            $insert = mysql_query("UPDATE tbl_delivery_information SET delivery_Date='$delivery_date',delivery_methode='$rapture',artificial_reason='$artreason',placenta_removed='$placenta_removed',completely_removed='$membraneremoved',Blood_lost='$blood_loss',Ergometrine='$oxctocin',Perineum='$perineum',Bp_after_delivery='$bp',Stage_1='$stage1',Stage_2='$stage2',Stage_3='$stage3',Remarks='$remarks',Baby_sex='$babySex',Baby_weight='$weight',Apgar_1='$apgar1',Apgar_5='$apgar5',ARV='$ARV',Risk='$risk',BCG='$BCG',Polio='$polio' WHERE Patient_ID='$patient_ID'");
         
            
        }else{
          $insert = mysql_query("INSERT INTO tbl_delivery_information (Patient_ID,delivery_Date,delivery_methode,artificial_reason,placenta_removed,completely_removed,Blood_lost,Ergometrine,Perineum,Employee,Bp_after_delivery,Stage_1,Stage_2,Stage_3,Delivered_By,Remarks,Baby_sex,Baby_weight,Apgar_1,Apgar_5,ARV,Risk,BCG,Polio)
           VALUES ('$patient_ID','$delivery_date','$rapture','$artreason','$placenta_removed','$membraneremoved','$blood_loss','$oxctocin','$perineum','$Employee_ID','$bp','$stage1','$stage2','$stage3','$Employee_ID','$remarks','$babySex','$weight','$apgar1','$apgar5','$ARV','$risk','$BCG','$polio')");
  
        }
        
        
       if ($insert) {
            echo 'Data saved successfully';
            
        } else {
            echo 'Data saving error';
        }
    }
}