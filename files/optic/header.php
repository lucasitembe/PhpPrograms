<?php
session_start();
require_once '../includes/connection.php';

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
<?php require_once("./audittrail.php") ?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head><!-- Created by GPITG LIMITED - DAR ES SALAAM, TANZANIA-->
        <meta charset="utf-8">
        <title>eHMS SYSTEM</title>
        <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
        <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script> -->
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" /> -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> -->
        <script src="../css/jquery.datetimepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="../AdminLTE.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>
        <link rel="shortcut icon" href="../images/icon.png">

        <link rel="stylesheet" href="../style.css" media="screen">
        <!-- New Date Picker -->
        <link rel="stylesheet" href="../pikaday.css">
        <link type="text/css" rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.css"/>
        <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->

        <link rel="stylesheet" href="../style.responsive.css" media="all">


        <script src="../jquery.js"></script>
        <script src="../script.js"></script>
        <script src="../script.responsive.js"></script>
        <style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
            .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
            .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
            .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

        </style>
        <script src="../jquery.maskedinput.js" type="text/javascript"></script>
        <script>
            jQuery(function ($) {
                $("#Phone_Number").mask("+999 999 999 999");
                $("#Phone_Number").focus();
                $("#Member_Number").focus();
                $("#Phone_Number").attr("placeholder", " e.g +255 753 xxx xxx");
            });
        </script>

        <!--Script to timeout users-->
        <script>
            //var timer;
            //var wait = 10*60*1000;
            //$(document).ready(function(){
            //    timer = setTimeout(logout,wait);
            //  });
            //
            //document.onkeypress=setTimeOut;
            //document.onmousemove=setTimeOut;
            //
            //function setTimeOut()
            //{
            //    clearTimeout(timer);
            //    timer=setTimeout(logout,wait);
            //}
            //function logout()
            //{
            //    window.location.href='logout.php';
            //}
        </script>




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
        </script>
        <script>
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

        <!--    Datepicker script-->
        <link rel="stylesheet" href="../css/smoothness/jquery-ui-1.10.1.custom.min.css" />
        <script src="../js/jquery-1.9.1.js"></script>
        <script src="../js/jquery-ui-1.10.1.custom.min.js"></script>
        <script>
            $(function () {
                $("#date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showWeek: true,
                    showOtherMonths: true,
                    //buttonImageOnly: true,
                    //showOn: "both",
                    dateFormat: "yy-mm-dd",
                    //showAnim: "bounce"
                });

            });
        </script>

        <!--    end of datepicker script-->

        <!--    Datepicker script-->
        <link rel="stylesheet" href="../css/smoothness/jquery-ui-1.10.1.custom.min.css" />
        <script src="../js/jquery-1.9.1.js"></script>
        <script src="../js/jquery-ui-1.10.1.custom.min.js"></script>
        <script>
            $(function () {
                $("#date2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showWeek: true,
                    showOtherMonths: true,
                    //buttonImageOnly: true,
                    //showOn: "both",
                    dateFormat: "yy-mm-dd",
                    //showAnim: "bounce"
                });

                $("#date3").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showWeek: true,
                    showOtherMonths: true,
                    //buttonImageOnly: true,
                    //showOn: "both",
                    dateFormat: "yy-mm-dd",
                    //showAnim: "bounce"
                });

                $("#date4").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showWeek: true,
                    showOtherMonths: true,
                    //buttonImageOnly: true,
                    //showOn: "both",
                    dateFormat: "yy-mm-dd",
                    //showAnim: "bounce"
                });

            });
        </script>
         <style>
            button{
                color: #2E2E2E!important;
                height:45px!important;
                font-size: 14px;
            }
        </style>
        <!--    end of datepicker script-->
    </head>

    <body>
        <div id="art-main">
            <header class="art-header">

                <h1 class="art-headline" data-left="1.14%">
                    <a href="#">
                        <?php
                        if (isset($_SESSION['userinfo'])) {
                            if (isset($_SESSION['userinfo']['Branch_Name']) && ($_SESSION['userinfo']['Employee_Name'])) {
                                echo "<b>Branch : </b>" . $_SESSION['userinfo']['Branch_Name'] . "  ~~~  <b>Department : </b>" . $_SESSION['userinfo']['Department_Name'] . "   ~~~  <b>Employee : </b>" . $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                echo "Unknown System User - Access Denied";
                            }
                        }

                        $chktosee = mysqli_query($conn,"SELECT employee_id FROM tbl_attendance WHERE employee_id='" . $_SESSION['userinfo']['Employee_ID'] . "' AND DATE(check_in)=CURDATE() AND check_out IS NULL") or die(mysqli_error($conn));
                        ?>
                    </a>
                </h1>
                <div class="art-shapes">


                </div>



            </header>
            <!--<nav class="art-nav">
                <ul class="art-hmenu"></ul>
                </nav>-->
            <div class="art-sheet clearfix">
                <div class="art-layout-wrapper">
                    <div class="art-content-layout">
                        <div class="art-content-layout-row">
                            <div class="art-layout-cell art-content"><article class="art-post art-article">
                                    <div class="art-postcontent art-postcontent-0 clearfix">
                                        <div class="art-content-layout-wrapper layout-item-0">
                                            <div class="art-content-layout">
                                                <?php if (isset($_SESSION['userinfo'])) { ?>
                                                    <span><a href='./logout.php' class='art-button-green' id="ConfirmLogout" onclick='return ConfirmLogout()'>LOGOUT</a></span>
                                                <?php } ?>

                                                <?php if (isset($_SESSION['userinfo']) && mysqli_num_rows($chktosee) == 1) { ?>
                                                    <button type="button" class='art-button-green' id="" onclick='checkout()'>CHECK OUT</button>
                                                <?php } ?>

                                                <?php
                                                if (isset($_SESSION['userinfo'])) {
                                                    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                                                        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){
                                                        ?>
                                                        <a href='../index.php?Bashboard=BashboardThisPage' class='art-button-green'>
                                                            DASHBOARD
                                                        </a>
                                                    <?php }
                                                } //} ?>
                                                <?php
                                                if (isset($_SESSION['userinfo'])) {
                                                    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
                                                        if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
                                                            ?>
                                                            <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
                                                                SETUP & CONFIG
                                                            </a>
        <?php }
    }
} ?>
                                                <style type="text/css">
                                                    table,tr,td{
                                                        border: solid 1px #ccc ! important;
                                                    }
                                                </style>
