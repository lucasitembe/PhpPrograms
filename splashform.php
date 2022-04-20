<?php
session_start();
session_destroy();
include("./includes/connection.php");
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <!-- Created by GPITG LIMITED (eHMS 4.0) -->
    <meta charset="utf-8">
    <title>EHMS SYSTEM</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <link rel="shortcut icon" href="images/icon.png">
    <link rel="stylesheet" href="./files/style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="./files/style.responsive.css" media="all">

    <link href="./themes/js-image-slider.css" rel="stylesheet" type="text/css" />
    <script src="./themes/js-image-slider.js" type="text/javascript"></script>
    <link href="generic.css" rel="stylesheet" type="text/css" />

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


    <style>
    .art-content .art-postcontent-0 .layout-item-0 {
        margin-bottom: 10px;
    }

    .art-content .art-postcontent-0 .layout-item-1 {
        padding-right: 10px;
        padding-left: 10px;
    }

    .ie7 .art-post .art-layout-cell {
        border: none !important;
        padding: 0 !important;
    }

    .ie6 .art-post .art-layout-cell {
        border: none !important;
        padding: 0 !important;
    }
    </style>

    <style type="text/css">
    table,
    tr,
    td {
        border: solid 1px #ccc ! important;
    }
    </style>
</head>

<script type="text/javascript">
function Login_Process() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var Branch_Name = document.getElementById("branch").value;

    if (username != null && username != '' && password != null && password != '') {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function() {
            dataDetails = myObject.responseText;
            if (myObject.readyState == 4) {
                var feedback = dataDetails
                console.log(feedback);
                if (feedback == 100) {
                    window.open("./files/index.php?Welcome", "_parent");
                } else if (feedback == 200) {
                    document.getElementById("username").value = '';
                    document.getElementById("password").value = '';
                    $("#Blocked_Dialog").dialog("open");
                } else if (feedback == 300) {
                    document.getElementById("username").value = '';
                    document.getElementById("password").value = '';
                    $("#Login_Error_Dialog").dialog("open");
                } else {
                    document.getElementById("username").value = '';
                    document.getElementById("password").value = '';
                    $("#Login_Error_Dialog").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', './files/Login_Process.php?username=' + username + '&password=' + password +
            '&Branch_Name=' + Branch_Name, true);
        myObject.send();
    } else {
        if (username == '' || username == null) {
            document.getElementById("username").style = 'border: 2px solid red';
        }
        if (password == '' || password == null) {
            document.getElementById("password").style = 'border: 2px solid red';
        }
    }
}
</script>

<script type="text/javascript">
function Change_Password_Process() {
    var username = document.getElementById("P_Username").value;
    var oldpassword = document.getElementById("oldpassword").value;
    var newpassword = document.getElementById("newpassword").value;
    var confirmpassword = document.getElementById("confirmpassword").value;
    if (username != null && username != '' && oldpassword != '' && oldpassword != null && newpassword != null &&
        newpassword != '' && confirmpassword != null && confirmpassword != '') {
        document.getElementById("P_Username").style = 'border: 1px solid black';
        document.getElementById("oldpassword").style = 'border: 1px solid black';
        document.getElementById("newpassword").style = 'border: 1px solid black';
        document.getElementById("confirmpassword").style = 'border: 1px solid black';
        if (oldpassword == newpassword) {
            $("#Unchanged_Password").dialog("open");
        } else if (newpassword == confirmpassword) {
            if (window.XMLHttpRequest) {
                myObjectGet = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGet = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGet.overrideMimeType('text/xml');
            }

            myObjectGet.onreadystatechange = function() {
                data12 = myObjectGet.responseText;
                if (myObjectGet.readyState == 4) {
                    var feedback2 = data12;
                    if (feedback2 == 'yes') {
                        document.getElementById("P_Username").value = '';
                        document.getElementById("oldpassword").value = '';
                        document.getElementById("newpassword").value = '';
                        document.getElementById("confirmpassword").value = '';
                        $("#Change_Password").dialog("close");
                        $("#Success_Changed").dialog("open");
                    } else if (feedback2 == 'no') {
                        document.getElementById("General_Error").innerHTML =
                            "Invalid username or password. Please enter correct information";
                        $("#General_Error").dialog("open");
                    } else {
                        document.getElementById("General_Error").innerHTML = data12;
                        $("#General_Error").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........

            myObjectGet.open('GET', './files/Change_Password_Process.php?username=' + username + '&oldpassword=' +
                oldpassword + '&newpassword=' + newpassword + '&confirmpassword=' + confirmpassword, true);
            myObjectGet.send();
        } else {
            document.getElementById("newpassword").value = '';
            document.getElementById("confirmpassword").value = '';
            document.getElementById("newpassword").style = 'border: 2px solid red';
            document.getElementById("confirmpassword").style = 'border: 2px solid red';
            $("#Password_Mismatch").dialog("open");
        }
    } else {
        if (username == '' || username == null) {
            document.getElementById("P_Username").style = 'border: 2px solid red';
        } else {
            document.getElementById("P_Username").style = 'border: 1px solid black';
        }
        if (oldpassword == '' || oldpassword == null) {
            document.getElementById("oldpassword").style = 'border: 2px solid red';
        } else {
            document.getElementById("oldpassword").style = 'border: 1px solid black';
        }
        if (newpassword == '' || newpassword == null) {
            document.getElementById("newpassword").style = 'border: 2px solid red';
        } else {
            document.getElementById("newpassword").style = 'border: 1px solid black';
        }
        if (confirmpassword == '' || confirmpassword == null) {
            document.getElementById("confirmpassword").style = 'border: 2px solid red';
        } else {
            document.getElementById("confirmpassword").style = 'border: 1px solid black';
        }
    }
}
</script>

<body>
    <div id="art-main">

        <header class="art-header">
            <div class="art-shapes"></div>
        </header>
        <style>
        table,
        tr,
        td {
            border-collapse: collapse !important;
            border: none !important;
        }
        </style>
        <nav class="art-nav">
            <ul class="art-hmenu"></ul>
        </nav>
        <div class="art-sheet clearfix">
            <div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content">
                            <article class="art-post art-article">
                                <br /><br /><br />
                                <center>
                                    <div id="sliderFrame">
                                        <div id="sider">
                                            <img src="images/image-slider-1.png" alt="#s1" width="100%" height="200px">
                                            <!--                                             <!--<img src="images/image-slider-1.png" alt="#s2" width="100%" height="200px">
<!--                                             <img src="images/image-slider-1.jpg" alt="#s2" width="100%">
                                                <img src="images/image-slider-1.jpg" alt="#s3" width="100%">
                                                <img src="images/image-slider-1.jpg" alt="#s4" width="100%">-->
                                        </div>
                                        <div id="s1" style="display: none;">
                                            <b>eHMS PILOT AT LUGALO REFERRAL HOSPITAL - THEMED TITLING, SLIDE SHOW,
                                                MESSAGING ETC</b>
                                        </div>
                                        <div id="s2" style="display: none;">
                                            <b>MAJENGO YA KULAZA WAGONJWA - WARDS</b>
                                        </div>
                                        <div id="s3" style="display: none;">
                                            <b>MRADI HUU UTALETA ONGEZEKO LA UFANISI NA HUDUMA BORA KATIKA HOSPITALI</b>
                                        </div>
                                        <div id="s4" style="display: none;">
                                            <b>JENGO LA UONGOZI WA HOSPITALI</b>
                                        </div>
                                    </div>
                                    <br /><br /><br />
                                    <input type="button" name="Login_Button" id="Login_Button" class="art-button-green"
                                        value="LOGIN" onclick="Login_Dialogy()">
                                    <!-- <input type="button" name="Login_Button" id="Login_Button" class="art-button-green" value="CHANGE PASSWORD" onclick="Change_Password_Dialogy()"> -->
                                    <button type="button" class="art-button-green" onclick="download_browser()">Download
                                        Compatible Browser</button>
                                    <a href="files/supported_browser/OPRAS_FORM.pdf" style="background: red"
                                        class="art-button-green"><b>Download OPRAS Form</b></a>
                                </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="download_Form">
            <table style="width:100%;background: #FFFFFF;font-family: cambria">
                <tr>
                    <td colspan="2">
                        <h2>DESCRIPTION</h2>
                        <p style="font-size: 13px;margin-left: 15px">This link provide the access to download browser
                            that are compatible with eHMS system.</p>
                        <p style="font-size: 13px;margin-left: 15px">To be able to use all the available feature in this
                            system you have to install this browser to your computer</p>
                        <br />
                        <h2>INSTALLATION</h2>
                        <ul style="font-size: 13px;margin-left: 25px">
                            <li><b>First:</b> you have to uninstall the mozilla firefox version greater tha 43.0 on your
                                computer</li>
                            <li><b>Second:</b> Install the browser that you have download from the link below.which is
                                version 43.0</li>
                        </ul> <br />
                        <h2>DISABLE THE BROWSER TO CHECK FOR UPDATE</h2>
                        <p style="font-size: 13px;margin-left: 15px">The browser has setting for checking for updates
                            automatically, so in order to restrict this browser to check for updates automatically,you
                            have to disable it,so that the browser to stay to the version downloaded from the links
                            below. </p>
                        <br />
                        <ul style="font-size: 13px;margin-left: 25px">
                            <li><b>FOLLOW THE STEPS BELOW</b></li>
                            <li><b>First:</b> start the browser</li><br />
                            <li><b>Second:</b> open menu on the top right side of your browser</li><br />
                            <li><b>Third:</b>on the menu, click option</li><br />
                            <li><b>Fourth:</b>on the left side,select Advanced</li><br />
                            <li><b>Fifth:</b>click on update menu</li><br />
                            <li><b>Sixth:</b>select <b>Never check for updates</b></li><br />
                            <li><b>Seventh:</b>close the tab, and continue use the eHMS</li>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="color:#C72300;font-size:15px;text-align: center">Dowland from these link
                        below</td>
                </tr>
                <tr>
                    <td><br /></td>
                </tr>
                <tr>
                    <td>
                        <style>
                        #download_tbl {
                            width: 100%;

                        }

                        #download_tbl tr td,
                        #download_tbl tr th {
                            border: 1px solid #cccccc !important;
                            padding: 10px;
                        }
                        </style>
                        <table id="download_tbl" cellspacing="10">
                            <tr>
                                <th colspan="2">FOR WINDOWS OPERATING SYSTEM</th>
                                <th colspan="2">FOR LINUX OPERATING SYSTEM </th>
                            </tr>
                            <tr>
                                <td style="color:#C72300;font-size:15px;text-align: center"><a
                                        href="files/supported_browser/Firefox_Setup_43_64bits.exe"
                                        style="font-size:15px;color:#000000">Firefox for 64bits computer</a></td>
                                <td style="color:#C72300;font-size:15px;text-align: center"><a
                                        href="files/supported_browser/Firefox_Setup_43_32bits.exe"
                                        style="font-size:15px;color:#000000">Firefox for 32bits computer</a></td>
                                <td style="color:#C72300;font-size:15px;text-align: center"><a
                                        href="files/supported_browser/linux_Firefox_Setup_43_64bits.bz2"
                                        style="font-size:15px;color:#000000">Firefox for 64bits computer</a></td>
                                <td style="color:#C72300;font-size:15px;text-align: center"><a
                                        href="files/supported_browser/linux_Firefox_Setup_43_32bits.bz2"
                                        style="font-size:15px;color:#000000">Firefox for 32bits computer</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><br /><br /></td>
                </tr>
            </table>
        </div>
        <div id="Login_Form">
            <table width="100%">
                <tr>
                    <td class="border-class-right" style="text-align: right" width="25%"><b>Employee Username</b></td>
                    <td class="border-class-right"><input type='text' name='username' autocomplete='off' id='username'
                            size=30></td>
                </tr>
                <tr>
                    <td class="border-class-right" style="text-align: right"><b>Employee Password</b></td>
                    <td class="border-class-right"><input type='password' name='password' id='password' size='30'></td>
                </tr>
                <tr class='hide'>
                    <td class="border-class-right" style="text-align: right"><b>Select Branch</b></td>
                    <select name='branch' id='branch'>
                        <?php
                        $data = mysqli_query($conn,"select * from tbl_branches");
                        while ($row = mysqli_fetch_array($data)) {
                            echo '<option>' . $row['Branch_Name'] . '</option>';
                        }
                        ?>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <input type="button" value="LOGIN" class="art-button-green" onclick="Login_Process()">
                        <input type="button" value="CHANGE PASSWORD" class="art-button-green"
                            onclick="Go_To_Change_Password()">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <span style='font-size: small; color: #037CB0;'><b>Powered By GPITG LTD</b></span>
                    </td>
                </tr>
            </table>
        </div>

        <div id="Blocked_Dialog">
            Account blocked!! Please contact system admin
        </div>

        <div id="Login_Error_Dialog">
            Invalid username or password. Please enter correct information
        </div>

        <div id="Password_Mismatch">
            New Passwords mismatch. Please enter same password
        </div>

        <div id="Unchanged_Password">
            Your old and new passwords are the same. Please change new passwords
        </div>

        <div id="Success_Changed">
            Password changed successfully
        </div>

        <div id="General_Error">

        </div>

        <div id="Change_Password">
            <table width="100%">
                <tr>
                    <td style="text-align: right;" width="25%"><b>USERNAME</b></td>
                    <td><input type='text' size=30 name='username' id='P_Username' placeholder="Enter Your Username" />
                    </td>
                </tr>
                <tr>
                    <td align='right'><b>OLD PASSWORD</b></td>
                    <td><input type='password' size=30 name='oldpassword' id='oldpassword'
                            placeholder="Enter Your Old Password" /></td>
                </tr>
                <tr>
                    <td align='right'><b>NEW PASSWORD</b></td>
                    <td><input type='password' size=30 name='newpassword' id='newpassword'
                            placeholder="Enter New Password" /></td>
                </tr>
                <tr>
                    <td align='right'><b>CONFIRM NEW PASSWORD</b></td>
                    <td><input type='password' size=30 name='confirmpassword' id='confirmpassword'
                            placeholder="Confirm New Password" /></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td style='text-align: right;' colspan=2>
                        <input type="button" value="CHANGE PASSWORD" class="art-button-green"
                            onclick="Change_Password_Process()">
                        <input type="button" value="GO TO LOGIN" class="art-button-green" onclick="Go_To_Login()">
                        <input type="button" value="CANCEL" class="art-button-green" onclick="Close_Dialog()">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <span style='font-size: small; color: #037CB0'><b>Powered By GPITG LTD</b></span>
                    </td>
                </tr>
            </table>
        </div>

        <footer class="art-footer">
            <div class="art-footer-inner">
                <p>Copyright &copy <?php echo date('Y'); ?>. All Rights Reserved. <a href='http://www.gpitg.co.tz/'
                        title='GPITG LIMITED' target='_blank'><b>GPITG LIMITED</b></a></p>
            </div>
        </footer>
        <script type="text/javascript">
        function Login_Dialogy() {
            $("#Login_Form").dialog("open");
        }

        function download_browser() {
            $("#download_Form").dialog("open");
        }
        </script>

        <script type="text/javascript">
        function Change_Password_Dialogy() {
            $("#Change_Password").dialog("open");
        }
        </script>

        <script type="text/javascript">
        function Close_Dialog() {
            $("#Change_Password").dialog("close");
        }
        </script>

        <script type="text/javascript">
        function Go_To_Login() {
            $("#Change_Password").dialog("close");
            $("#Login_Form").dialog("open");
        }
        </script>

        <script type="text/javascript">
        function Go_To_Change_Password() {
            $("#Change_Password").dialog("open");
            $("#Login_Form").dialog("close");
        }
        </script>

        <script src="./files/js/jquery-1.8.0.min.js"></script>
        <script src="./files/js/jquery-ui-1.8.23.custom.min.js"></script>
        <link rel="stylesheet" href="./files/js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

        <script>
        $(document).ready(function() {
            $("#Change_Password").dialog({
                autoOpen: false,
                width: '55%',
                height: 270,
                title: 'eHMS 4.0 ~ CHANGE PASSWORD',
                modal: true
            });
            $("#Login_Form").dialog({
                autoOpen: false,
                width: '55%',
                height: 220,
                title: 'eHMS 4.0 ~ LOGIN SCREEN',
                modal: true
            });
            $("#download_Form").dialog({
                autoOpen: false,
                width: '70%',
                height: 550,
                title: 'eHMS 4.0 ~ DOWNLOAD SYSTEM COMPATIBLE BROWSER',
                modal: true
            });

            $("#Login_Error_Dialog").dialog({
                autoOpen: false,
                width: '40%',
                height: 120,
                title: 'eHMS 4.0 ~ INVALID INPUT',
                modal: true
            });
            $("#Blocked_Dialog").dialog({
                autoOpen: false,
                width: '40%',
                height: 120,
                title: 'eHMS 4.0 ~ BLOCKED ACCOUNT',
                modal: true
            });
            $("#Password_Mismatch").dialog({
                autoOpen: false,
                width: '40%',
                height: 120,
                title: 'eHMS 4.0 ~ NEW PASSWORDS MISMATCH',
                modal: true
            });
            $("#Unchanged_Password").dialog({
                autoOpen: false,
                width: '45%',
                height: 120,
                title: 'eHMS 4.0 ~ UNCHANGED PASSWORD',
                modal: true
            });
            $("#Success_Changed").dialog({
                autoOpen: false,
                width: '35%',
                height: 120,
                title: 'eHMS 4.0 ~ PASSWORD CHANGED',
                modal: true
            });
            $("#General_Error").dialog({
                autoOpen: false,
                width: '35%',
                height: 120,
                title: 'eHMS 4.0 ~ ERROR INFORMATION',
                modal: true
            });
        });
        </script>
        <script>
        $("#password,#username").keypress(function(e) {
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if (keyCode == '13') {
                Login_Process();
            }
        });
        </script>
    </div>
</body>

</html>