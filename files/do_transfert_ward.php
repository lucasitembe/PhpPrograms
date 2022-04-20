<?php
session_start();
include("./includes/connection.php");

 $bed_id =  mysqli_real_escape_string($conn,$_GET['bed_id']) ;
 $ward_id =  mysqli_real_escape_string($conn,$_GET['ward_id']) ;
 $admission_id =  mysqli_real_escape_string($conn,$_GET['admission_id']) ;
  

 $sql="UPDATE tbl_admission SET Hospital_Ward_ID=$ward_id,Bed_ID=$bed_id WHERE Admision_ID=$admission_id";
 
 $result=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
 
 if($result){
     echo 1;
 }else{
     echo 0;
 }