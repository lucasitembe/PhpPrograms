<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        
//      remarks='+remarks+'&admission='+admission
        $dateandtime=  mysqli_real_escape_string($conn,$_POST['dateandtime']);
        $temp=  mysqli_real_escape_string($conn,$_POST['temp']);
        $pr= mysqli_real_escape_string($conn,$_POST['pr']);
        $resp= mysqli_real_escape_string($conn,$_POST['resp']);
        $so= mysqli_real_escape_string($conn,$_POST['so']);
        $bwt= mysqli_real_escape_string($conn,$_POST['bwt']);
        $feedamt= mysqli_real_escape_string($conn,$_POST['feedamt']);
        $feedleftincup= mysqli_real_escape_string($conn,$_POST['feedleftincup']);
        $orallytaken= mysqli_real_escape_string($conn,$_POST['orallytaken']);
        $amtVommited= mysqli_real_escape_string($conn,$_POST['amtVommited']);
        $amtTakenByngt= mysqli_real_escape_string($conn,$_POST['amtTakenByngt']);
        $diarrhoea= mysqli_real_escape_string($conn,$_POST['diarrhoea']);
        $remarks= mysqli_real_escape_string($conn,$_POST['remarks']);
        $admission= mysqli_real_escape_string($conn,$_POST['admission']);
        $insert=mysqli_query($conn,"INSERT INTO tbl_mulnutrition_observation (Admission_ID,date_time,temp,Pr,Resp,So,daily_bwt,feed_amount,cup_Amount,oral_taken,ngt_taken,vomitted_mls,diarrhoea,Remarks) VALUES ('$admission','$dateandtime','$temp','$pr','$resp','$so','$bwt','$feedamt','$feedleftincup','$orallytaken','$amtTakenByngt','$amtVommited','$diarrhoea','$remarks')");
        if($insert){
            echo 'Saved successfully';
        }  else {
            echo 'Saving error';  
        }
    }else if($_POST['action']=='savepaediatric'){
//    remarks='+remarks+'&admission='+admission,
        $dateandtime=  mysqli_real_escape_string($conn,$_POST['dateandtime']);
        $temp=  mysqli_real_escape_string($conn,$_POST['temp']);
        $pr= mysqli_real_escape_string($conn,$_POST['pr']);
        $resp= mysqli_real_escape_string($conn,$_POST['resp']);
        $so= mysqli_real_escape_string($conn,$_POST['so']);
        $Problem= mysqli_real_escape_string($conn,$_POST['Problem']);
        $diagnosis= mysqli_real_escape_string($conn,$_POST['diagnosis']);
        $expected_outcome= mysqli_real_escape_string($conn,$_POST['expected_outcome']);
        $implementation= mysqli_real_escape_string($conn,$_POST['implementation']);
        $outcome= mysqli_real_escape_string($conn,$_POST['outcome']);
        $investigation= mysqli_real_escape_string($conn,$_POST['investigation']);
        $remarks= mysqli_real_escape_string($conn,$_POST['remarks']);
        $admission= mysqli_real_escape_string($conn,$_POST['admission']);
        $insert=mysqli_query($conn,"INSERT INTO tbl_paediatric_observation (observation_admission_Id,date_time,temp,Pr,Resp,So,problem,diagnosis,exp_outcome,implememntation,outcome,investigation,Remarks) VALUES ('$admission','$dateandtime','$temp','$pr','$resp','$so','$Problem','$diagnosis','$expected_outcome','$implementation','$outcome','$investigation','$remarks')");
        if($insert){
            echo 'Saved successfully';
        }  else {
            echo 'Saving error';  
        }
        
        
    }
}