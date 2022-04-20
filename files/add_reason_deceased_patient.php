<?php
// data:{nurse_id:nurse_id,ward_id:ward_id,patitent_id:patitent_id,doctor_id:doctor_id,sponsor_id:sponsor_id,dept_id:dept_id},

	include("./includes/connection.php");

   $deceased_reasons=$_POST['deceased_reason_ID'];
    
   $sql_insert_d_course_result=mysqli_query($conn,"INSERT INTO tbl_deceased_reasons(deceased_reasons) VALUES('$deceased_reasons')") or die(mysqli_error($conn));

    if($insert){
        echo 'ok';
    }else{
        echo 'no';
    }