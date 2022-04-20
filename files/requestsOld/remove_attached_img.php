<?php

session_start();
include("../includes/connection.php");

$source = $_POST['source'];
$Attachment_ID = $_POST['Attachment_ID'];

if ($source == 'lab') {
  $status=mysql_query("DELETE FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'") or die(mysql_error());  
  
  if($status){
     echo 1;
  }else{
      echo 0;
  }
} else if ($source == 'rad') {
  $status=mysql_query("DELETE FROM tbl_radiology_image WHERE Pic_ID='$Attachment_ID'") or die(mysql_error());  
  
  if($status){
     echo 1;
  }else{
      echo 0;
  }  
}

