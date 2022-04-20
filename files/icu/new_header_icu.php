<?php
session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../../index.php?InvalidPrivilege=yes");
}

require_once '../includes/connection.php';

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
<?php require_once("./audittrail.php") ?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US" style="background-color: #2583af;">
<head>
    <!-- Created by GPITG LIMITED - DAR ES SALAAM, TANZANIA-->
    <meta charset="utf-8">
    <title>eHMS SYSTEM</title>
    <link rel="shortcut icon" href="../images/icon.png">
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--    <link rel="stylesheet" href="../pikaday.css">-->
    <link type="text/css" rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="../css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap4/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/icu.css" />

    <!--    <script src="../jquery.js"></script>-->

    <script src="../js/jquery-1.9.1.js"></script>
    <script src="../js/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="../css/jquery.datetimepicker.js"></script>
    <script src="../css/bootstrap4/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        function ConfirmLogout() {
            //function to confirm users' action to logout
            var logout = confirm("Are you  sure you want to logout?");
            if (logout) {
                location.href = "../logout.php";
                return true;
            } else {
                location.href = "#";
                return false;
            }
        }

        function checkout() {
            if (confirm('Are you sure you want to checkout')) {
                $.ajax({
                    type: 'POST',
                    url: '../check_out.php',
                    data: 'chkout=true',
                    success: function (html) {
                        if (parseInt(html) == 1) {
                            document.location = '../index.php';
                        }
                    }, complete: function () {
                    }, error: function (html, jjj) {
                        //alert(html);
                    }
                });
            }
        }
    </script>

</head>

<body style="background-color: #c0c0c0;" class='hide'>
<div style="background: #006400 ; padding-top: 10px; padding-bottom: 10px;">
    <div class="container-fluid hide">
        <?php
            if (isset($_SESSION['userinfo'])) {
                if (isset($_SESSION['userinfo']['Branch_Name']) && ($_SESSION['userinfo']['Employee_Name'])) {
                    $branch = $_SESSION['userinfo']['Branch_Name'];
                    $department = $_SESSION['userinfo']['Department_Name'];
                    $employee = $_SESSION['userinfo']['Employee_Name'];
                } else {
                    $branch = '';
                    $department = '';
                    $employee = '';
                }
            }

            $chktosee = mysqli_query($conn,"SELECT employee_id FROM tbl_attendance WHERE employee_id='" . $_SESSION['userinfo']['Employee_ID'] . "' AND DATE(check_in)=CURDATE() AND check_out IS NULL") or die(mysqli_error($conn));
        ?>
        <span class="fw-bold header-item">Branch:</span>
        <span class="header-item me-1"> <?= $branch ?> ~~~ </span>
        <span class="fw-bold header-item">Department:</span>
        <span class="header-item me-1"> <?= $department ?> ~~~ </span>
        <span class="fw-bold header-item">Employee:</span>
        <span class="header-item me-1"> <?= $employee ?> </span>
    </div>
</div>

<div class="container-fluid pt-3">

            <?php if (isset($_SESSION['userinfo'])) { ?>
                <a href='./../logout.php' class='btn btn-primary hide' id="ConfirmLogout" onclick='return ConfirmLogout()'>LOGOUT</a>
            <?php } ?>
            <?php if (isset($_SESSION['userinfo']) && mysqli_num_rows($chktosee) == 1) { ?>
                <a type="button" class='btn btn-primary hide' id="" onclick='checkout()'>CHECK OUT</a>
            <?php } ?>
            <?php
            if (isset($_SESSION['userinfo'])) {
                if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                    ?>
                    <a href='../index.php?Bashboard=BashboardThisPage' class='btn btn-primary hide'>
                        DASHBOARD
                    </a>
                <?php }
            }  ?>
            <?php
            if (isset($_SESSION['userinfo'])) {
                if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
                        ?>
                        <a href='../setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='btn btn-primary hide'>
                            SETUP & CONFIG
                        </a>
                    <?php }
                }
            } ?>



