<?php

include("./includes/connection.php");
 
if(isset($_POST['selected_departments'])&&isset($_POST['selected_wards'])){
    $selected_departments=$_POST['selected_departments'];$selected_wards=$_POST['selected_wards'];

    foreach ($selected_departments as $department){
        foreach ($selected_wards as $ward){
            #kuondoa data duplication
            $check_if_exist = mysqli_query($conn,"SELECT department_ward_id FROM tbl_department_ward WHERE department_id='$department' AND ward_id='$ward'");
            if (mysqli_num_rows($check_if_exist)==0) {
                #kama haipo add
                mysqli_query($conn,"INSERT INTO tbl_department_ward(`department_id`, `ward_id`) VALUES ('$department','$ward')");
            } 
        }
    }
}