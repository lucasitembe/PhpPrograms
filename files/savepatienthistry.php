<?php

include("./includes/connection.php");
@session_start();

$famscohist = mysqli_real_escape_string($conn,$_GET['famscohist']);
$pastobshist =  mysqli_real_escape_string($conn,$_GET['pastobshist']);
$pastpaedhist =  mysqli_real_escape_string($conn,$_GET['pastpaedhist']);
$pastmedhist =  mysqli_real_escape_string($conn,$_GET['pastmedhist']);
$pastdenthist =  mysqli_real_escape_string($conn,$_GET['pastdenthist']);
$pastsurghist =  mysqli_real_escape_string($conn,$_GET['pastsurghist']);
$Registration_ID =  mysqli_real_escape_string($conn,$_GET['Registration_ID']);


//echo $famscohist.' '.$pastobshist.' '.$pastpaedhist.' '.$pastmedhist.' '.$pastdenthist.' '.$pastsurghist;
 $qrHistry=mysqli_query($conn,'SELECT famscohist,pastobshist,pastpaedhist,pastmedhist,pastdenthist,pastsurghist FROM tbl_patient_histrory WHERE registration_id="'.$Registration_ID.'"') or die(mysqli_error($conn));
$sql='';
 if(mysqli_num_rows($qrHistry) > 0){
     $sql="UPDATE tbl_patient_histrory SET famscohist='$famscohist',pastobshist='$pastobshist',pastpaedhist='$pastpaedhist',pastmedhist='$pastmedhist',pastdenthist='$pastdenthist',pastsurghist='$pastsurghist',employee_id='".$_SESSION['userinfo']['Employee_ID']."',histr_date=NOW() WHERE registration_id=$Registration_ID";
 }else{
    $sql="INSERT INTO tbl_patient_histrory (registration_id,famscohist,pastobshist,pastpaedhist,pastmedhist,pastdenthist,pastsurghist,employee_id,histr_date)
           VALUES($Registration_ID,'$famscohist','$pastobshist','$pastpaedhist','$pastmedhist','$pastdenthist','$pastsurghist','".$_SESSION['userinfo']['Employee_ID']."',NOW())
         "           
         ; 
 }
$rest=  mysqli_query($conn,$sql) ;

if($rest){
    echo 1;
}else{
    die(mysqli_error($conn));
}

