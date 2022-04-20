<?php

include("./includes/connection.php");
 
if(isset($_POST['selected_departments'])&&isset($_POST['selected_wards'])){
    $selected_departments=$_POST['selected_departments'];$selected_wards=$_POST['selected_wards'];
    $Ward_Store_ID =$_POST['Ward_Store_ID'];
    $result="";
    foreach ($selected_departments as $department){
        foreach ($selected_wards as $ward){
            #kuondoa data duplication
            $check_if_exist = mysqli_query($conn,"SELECT sub_department_ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$department' AND ward_id='$ward'  ");
            if (mysqli_num_rows($check_if_exist)==0) {
                $updateStore = mysqli_query($conn, "UPDATE tbl_sub_department_ward SET Ward_Store_ID='$Ward_Store_ID' WHERE sub_department_id='$department' AND ward_id='$ward'") or die(mysqli_error($conn));

                $check_department = mysqli_query($conn,"SELECT tbl_sub_department_ward.sub_department_ward_id,Sub_Department_Name FROM tbl_sub_department_ward,tbl_sub_department WHERE tbl_sub_department_ward.Sub_Department_ID = tbl_sub_department.sub_department_id AND tbl_sub_department_ward.sub_department_id='$department'");
                $check_ward = mysqli_query($conn,"SELECT sub_department_ward_id,Hospital_Ward_Name FROM tbl_sub_department_ward,tbl_hospital_ward WHERE tbl_hospital_ward.Hospital_Ward_ID=tbl_sub_department_ward.ward_id AND ward_id='$ward'");
                if (mysqli_num_rows($check_department)>0) {
                    $department_data = mysqli_fetch_assoc($check_department);
                    $department_name = $department_data['Sub_Department_Name'];
                    $result = strtoupper($department_name);

                }elseif (mysqli_num_rows($check_ward)>0) {
                    $ward_data = mysqli_fetch_assoc($check_ward);
                    $ward_name = $ward_data['Hospital_Ward_Name'];
                    $result = strtoupper($ward_name);

                    
                }else{
                #kama haipo add
                    $insertsub= mysqli_query($conn,"INSERT INTO `tbl_sub_department_ward`(`sub_department_id`, `ward_id`, Ward_Store_ID) VALUES ('$department','$ward', '$Ward_Store_ID')") or die(mysqli_errno($conn));
                    if($insertsub){
                    $result = 'inserted';
                    }
                }
            }
            else{
                $updateStore = mysqli_query($conn, "UPDATE tbl_sub_department_ward SET Ward_Store_ID='$Ward_Store_ID' WHERE sub_department_id='$department' AND ward_id='$ward'") or die(mysqli_error($conn));
                $result = 'exist';
            } 
        }
    } echo $result;
}
