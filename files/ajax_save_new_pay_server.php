<?php
include("./includes/connection.php");
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}
if(isset($_POST['clicked_btn'])){
$clicked_btn=$_POST['clicked_btn'];

if(!empty($clicked_btn)){
    $check_status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT extenal_server_status FROM `tbl_epay_server_config` ORDER BY setup_id DESC"))['extenal_server_status'];
            
            if($check_status == 'not active'){
                $sql_update_button_status_result=mysqli_query($conn,"INSERT INTO `tbl_epay_server_config` (`ip_address`, `changed_by`, `changed_at`, `extenal_server_status`) VALUES ('192.168.112.1', '$Employee_ID', NOW(), 'active')") or die(mysqli_error($conn)); 
            }else{
                $sql_update_button_status_result=mysqli_query($conn,"INSERT INTO `tbl_epay_server_config` (`ip_address`, `changed_by`, `changed_at`, `extenal_server_status`) VALUES ('192.168.112.1', '$Employee_ID', NOW(), 'not active')") or die(mysqli_error($conn));
            }
        }

    if($sql_update_button_status_result){
        echo "success";
    }else{
        echo "fail";
    }
}