<?php
include "../../includes/connection.php";
session_start();
$employee_session_id=session_id();
$Employee_logedin_id=$_SESSION['userinfo']['Employee_ID'];
$employee_session_time=time();
//error_reporting(0);

$autoupdatesignedoff = mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='AutoLogout'") or die(mysqli_error($conn));

if (mysqli_num_rows($autoupdatesignedoff) > 0) { //signedoff not updated today?
    //signed off all according to hours
    $configvalue = trim(mysqli_fetch_assoc($autoupdatesignedoff)['configvalue']);

    if (!empty($configvalue) && is_numeric($configvalue) && $configvalue > 0) {
       
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $configvalue)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }
}
?>

<link rel="stylesheet" href="./lib/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
<link rel="stylesheet" href="./lib/jquery-datetimepicker/build/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="./lib/jquery-toast-plugin/dist/jquery.toast.min.css">
<link rel="stylesheet" href="./lib/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="./lib/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="./lib/datatables/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="./lib/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./laboratory/static/css/global_style.css">