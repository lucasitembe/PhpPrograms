<?php
require_once '../includes/connection.php';
$employee_session_id=$_GET['employee_session_id'];
$Employee_logedin_id=$_GET['Employee_logedin_id'];
$employee_session_time=$_GET['employee_session_time'];
//check if user loged in
$sql_check_if_user_logged_in=mysqli_query($conn,"SELECT employee_session_id FROM tbl_user_multiple_login_contorl WHERE Employee_logedin_id='$Employee_logedin_id' and employee_session_id='$employee_session_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_if_user_logged_in)>0){
//  $employee_session_id;
}else{
    $sql_store_user_login_session_result=mysqli_query($conn,"INSERT INTO tbl_user_multiple_login_contorl(Employee_logedin_id,employee_session_id,employee_session_time)
            VALUES('$Employee_logedin_id','$employee_session_id','$employee_session_time')
            ") or die(mysqli_error($conn));
}

    $sql_check_if_user_logged_in2=mysqli_query($conn,"SELECT employee_session_id FROM tbl_user_multiple_login_contorl WHERE Employee_logedin_id='$Employee_logedin_id'") or die(mysqli_error($conn));

    if(mysqli_num_rows($sql_check_if_user_logged_in2)>1){
        if(mysqli_num_rows($sql_check_if_user_logged_in2)>2){
            $sql_delete_this_user_session_result=mysqli_query($conn,"DELETE FROM tbl_user_multiple_login_contorl WHERE Employee_logedin_id='$Employee_logedin_id' or Employee_logedin_id='0'") or die(mysqli_error($conn));
        }
        $sql_select_maximum_time_for_this_this_user_result=mysqli_query($conn,"SELECT MIN(employee_session_time) AS employee_session_time,employee_session_id FROM tbl_user_multiple_login_contorl WHERE Employee_logedin_id='$Employee_logedin_id'") or die(mysqli_error($conn));
    
        if(mysqli_num_rows($sql_select_maximum_time_for_this_this_user_result)>0){
            while($rows=mysqli_fetch_assoc($sql_select_maximum_time_for_this_this_user_result)){
                 $employee_session_id_loged=$rows['employee_session_id'];
                  $employee_session_time=$rows['employee_session_time'];
                 if($employee_session_id_loged==$employee_session_id){
                     $sql_delete_this_user_session_result=mysqli_query($conn,"DELETE FROM tbl_user_multiple_login_contorl WHERE Employee_logedin_id='$Employee_logedin_id' or Employee_logedin_id='0'") or die(mysqli_error($conn));
                     echo "logout_this_user";
                 }else{
                     echo "do_nothing";
                 } 
            }
            
        }
    }