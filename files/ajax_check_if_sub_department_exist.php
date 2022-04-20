<?php

include("./includes/connection.php");
$sub_department_id=$_POST['sub_department'];

$check_if_exist = mysqli_query($conn,"SELECT Hospital_Ward_Name,Sub_Department_Name FROM tbl_sub_department_ward,tbl_sub_department,tbl_hospital_ward WHERE tbl_hospital_ward.Hospital_Ward_ID=tbl_sub_department_ward.ward_id AND tbl_sub_department_ward.sub_department_id=tbl_sub_department.Sub_Department_ID AND tbl_sub_department_ward.sub_department_id='$sub_department_id'");
    if (mysqli_num_rows($check_if_exist)>0) {
        while($row = mysqli_fetch_assoc($check_if_exist)){
            $Hospital_Ward_Name =$row ['Hospital_Ward_Name'];
            $Sub_Department_Name =$row ['Sub_Department_Name'];
        }
        echo '{"code":"200","Hospital_Ward_Name":"'.$Hospital_Ward_Name.'","Sub_Department_Name":"'.$Sub_Department_Name.'"}';
    }