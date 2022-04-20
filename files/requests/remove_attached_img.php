<?php

session_start();
include("../includes/connection.php");

$source = $_POST['source'];
$Attachment_ID = $_POST['Attachment_ID'];

if ($source == 'lab') {
  $status=mysqli_query($conn,"DELETE FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'") or die(mysqli_error($conn));  
  
  if($status){
     echo 1;
  }else{
      echo 0;
  }
} else if ($source == 'rad') {
  $status=mysqli_query($conn,"DELETE FROM tbl_radiology_image WHERE Pic_ID='$Attachment_ID'") or die(mysqli_error($conn));  
  
  if($status){
     echo 1;
  }else{
      echo 0;
  }  
}

