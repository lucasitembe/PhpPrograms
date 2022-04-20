<?php
session_start();
require_once '../includes/connection.php';
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

<head>
    <meta charset="utf-8">
    <title>eHMS SYSTEM</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
    <link rel="shortcut icon" href="images/icon.png">
    <link rel="stylesheet" href="./lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./lib/jquery-datetimepicker/build/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="./lib/jquery-toast-plugin/dist/jquery.toast.min.css">
    <link rel="stylesheet" href="./lib/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="./lib/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./lib/datatables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="./lib/icheck-bootstrap/icheck-bootstrap.min.css">
</head>

<div class="container-fluid">
    <?php
        if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Branch_Name']) && ($_SESSION['userinfo']['Employee_Name'])) {
            ?>
    <div class="row bg-success text-light p-3 mb-3">
        <span>
            <b class="fs-6">Branch : </b> <?php echo $_SESSION['userinfo']['Branch_Name'] ?>
            ~~~ <b class="fs-6">Department : </b>
            <span class="fs-6">
                <?php echo $_SESSION['userinfo']['Department_Name']?>
            </span>
            ~~~ <b class="fs-6">Employee : </b><span class="fs-6">
                <?php echo $_SESSION['userinfo']['Employee_Name']?>
            </span>
        </span>
    </div>
    <?php } else {
        echo "Unknown System User - Access Denied";
        }
            }
                $chktosee = mysqli_query($conn,"SELECT employee_id FROM tbl_attendance WHERE employee_id='" . $_SESSION['userinfo']['Employee_ID'] . "' AND DATE(check_in)=CURDATE() AND check_out IS NULL") or die(mysqli_error($conn));
            ?>
    <div class="row">
        <?php if (isset($_SESSION['userinfo'])) { ?>
        <div class="col-1">
            <a href='./logout.php?emp_id=<?=$_SESSION['userinfo']['Employee_ID'];?>' class='btn btn-success btn-sm'
                id="ConfirmLogout" onclick='return ConfirmLogout()'>LOGOUT</a>
        </div>
        <?php } ?>
        <?php if (isset($_SESSION['userinfo']) && mysqli_num_rows($chktosee) == 1) { ?>
        <div class="col-1">
            <button type="button" class='btn btn-success btn-sm' id="" onclick='checkout()'>
                CHECK OUT
            </button>
        </div>
        <?php } ?>

        <?php
            if (isset($_SESSION['userinfo'])) {
                if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                    //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
        ?>
        <div class="col-1 me-4">
            <a href='index.php?Bashboard=BashboardThisPage' class='btn btn-success btn-sm'>
                DASHBOARD
            </a>
        </div>
        <?php }
        } //} ?>
        <?php
            if (isset($_SESSION['userinfo'])) {
                if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
        ?>
        <div class="col-2">
            <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage'
                class='btn btn-success btn-sm'>
                SETUP & CONFIG
            </a>
        </div>
        <?php }}}?>
        <div class="col-1">
            <button type="button" onclick="history.back()" class='btn btn-success btn-sm'>
                BACK
            </button>
        </div>
    </div>
</div>