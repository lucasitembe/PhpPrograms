<?php
include("./includes/connection.php");

if(isset($_POST['image_quality'])){
   $image_quality=$_POST['image_quality']; 
   //check if already saved
   $sql_check_if_exist_result=mysqli_query($conn,"SELECT *FROM tbl_image_quality") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_check_if_exist_result)>0){
       //update the existing quality
       $sql_update_image_quality_result=mysqli_query($conn,"UPDATE tbl_image_quality SET image_quality_value='$image_quality'") or die(mysqli_error($conn));
   
       if($sql_update_image_quality_result){
           echo "success";
       }else{
           echo "fail";
       }
   }else{
       //save new image quality
       $sql_save_new_image_result=mysqli_query($conn,"INSERT INTO tbl_image_quality(image_quality_value) VALUES('$image_quality')") or die(mysqli_error($conn));
       if($sql_save_new_image_result){
           echo "success";
       }else{
           echo "fail";
       }
   }
}else{
    echo "haifiki";
}