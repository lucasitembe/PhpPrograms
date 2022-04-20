<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        
//      remarks='+remarks+'&admission='+admission
        $dateandtime=  mysql_real_escape_string($_POST['dateandtime']);
        $temp=  mysql_real_escape_string($_POST['temp']);
        $pr= mysql_real_escape_string($_POST['pr']);
        $resp= mysql_real_escape_string($_POST['resp']);
        $so= mysql_real_escape_string($_POST['so']);
        $bwt= mysql_real_escape_string($_POST['bwt']);
        $feedamt= mysql_real_escape_string($_POST['feedamt']);
        $feedleftincup= mysql_real_escape_string($_POST['feedleftincup']);
        $orallytaken= mysql_real_escape_string($_POST['orallytaken']);
        $amtVommited= mysql_real_escape_string($_POST['amtVommited']);
        $amtTakenByngt= mysql_real_escape_string($_POST['amtTakenByngt']);
        $diarrhoea= mysql_real_escape_string($_POST['diarrhoea']);
        $remarks= mysql_real_escape_string($_POST['remarks']);
        $admission= mysql_real_escape_string($_POST['admission']);
        $insert=mysql_query("INSERT INTO tbl_mulnutrition_observation (Admission_ID,date_time,temp,Pr,Resp,So,daily_bwt,feed_amount,cup_Amount,oral_taken,ngt_taken,vomitted_mls,diarrhoea,Remarks) VALUES ('$admission','$dateandtime','$temp','$pr','$resp','$so','$bwt','$feedamt','$feedleftincup','$orallytaken','$amtTakenByngt','$amtVommited','$diarrhoea','$remarks')");
        if($insert){
            echo 'Saved successfully';
        }  else {
            echo 'Saving error';  
        }
    }else if($_POST['action']=='savepaediatric'){
//    remarks='+remarks+'&admission='+admission,
        $dateandtime=  mysql_real_escape_string($_POST['dateandtime']);
        $temp=  mysql_real_escape_string($_POST['temp']);
        $pr= mysql_real_escape_string($_POST['pr']);
        $resp= mysql_real_escape_string($_POST['resp']);
        $so= mysql_real_escape_string($_POST['so']);
        $Problem= mysql_real_escape_string($_POST['Problem']);
        $diagnosis= mysql_real_escape_string($_POST['diagnosis']);
        $expected_outcome= mysql_real_escape_string($_POST['expected_outcome']);
        $implementation= mysql_real_escape_string($_POST['implementation']);
        $outcome= mysql_real_escape_string($_POST['outcome']);
        $investigation= mysql_real_escape_string($_POST['investigation']);
        $remarks= mysql_real_escape_string($_POST['remarks']);
        $admission= mysql_real_escape_string($_POST['admission']);
        $insert=mysql_query("INSERT INTO tbl_paediatric_observation (observation_admission_Id,date_time,temp,Pr,Resp,So,problem,diagnosis,exp_outcome,implememntation,outcome,investigation,Remarks) VALUES ('$admission','$dateandtime','$temp','$pr','$resp','$so','$Problem','$diagnosis','$expected_outcome','$implementation','$outcome','$investigation','$remarks')");
        if($insert){
            echo 'Saved successfully';
        }  else {
            echo 'Saving error';  
        }
        
        
    }
}