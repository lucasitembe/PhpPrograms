<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");

$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $End_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($End_Date));
    $Start_Date = $new_Date . ' 00:00:00';
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>
        <script type="text/javascript">
            function gotolink() {
                var url = "<?php
        if (isset($_GET['Registration_ID'])) {
            echo "Registration_ID=" . $_GET['Registration_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById('patientlist').value;

                if (patientlist == 'MY PATIENT LIST') {
                    document.location = "doctorcurrentpatientlist.php?" + url;
                } else if (patientlist == 'OPD PATIENTS LIST') {
                    document.location = "clinicpatientlist.php?" + url;
                } else if (patientlist == 'CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?" + url;
                } else if (patientlist == 'FROM NURSE STATION') {
                    document.location = "patientfromnursestation.php?NurseStationPatientList=NurseStationPatientListThisPage" + url;
                } else if (patientlist == 'OPD PATIENT LIST') {
                    document.location = "doctorOpdpatientlist.php?" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

        <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<script language="javascript" type="text/javascript">
    function searchClinic(Clinic_ID) {
        //var Clinic_ID = document.getElementById('Clinic_ID').value;
        document.getElementById('Search_Patient').value = '';
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height='380px' src='clinicpatientlist_Iframe.php?Patient_Name=" + Patient_Name + "&Sponsor_ID=" + Sponsor_ID + "&Clinic_ID=" + Clinic_ID + "'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function searchPatient() {
        var Clinic_ID = document.getElementById('Clinic_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
//        alert(vv"");
        var Patient_Name1 = Patient_Name.replace(/^\s+/, '').replace(/\s+$/, '');
        if (Patient_Name1 == "") {
            exit;
        }
        $.ajax({
            type: 'GET',
            url: 'clinicpatientlist_Iframe.php',
            data: {Patient_Name: Patient_Name, Clinic_ID: Clinic_ID, Sponsor_ID: Sponsor_ID, Date_From: Date_From, Date_To: Date_To},
            success: function (data) {
                document.getElementById('Search_Iframe').innerHTML = data;
            }
        });
        // document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height='380px' src='clinicpatientlist_Iframe.php?Patient_Name=" + Patient_Name + "&Clinic_ID=" + Clinic_ID + "&Sponsor_ID=" + Sponsor_ID + "&Date_From=" + Date_From + "&Date_To=" + Date_To + "'></iframe>";
    }

</script>
<script language="javascript" type="text/javascript">
    function searchSponsor(Sponsor_ID) {
        document.getElementById('Search_Patient').value = '';
        var Clinic_ID = document.getElementById('Clinic_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;

        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height='380px' src='clinicpatientlist_Iframe.php?Patient_Name=" + Patient_Name + "&Sponsor_ID=" + Sponsor_ID + "&Clinic_ID=" + Clinic_ID + "'></iframe>";
    }
</script>
<?php
if (isset($_POST['fromDate'])) {
    $fromDate = $_POST['fromDate'];
} else {
    $fromDate = '';
}

if (isset($_POST['toDate'])) {
    $toDate = $_POST['toDate'];
} else {
    $toDate = '';
}

if (isset($_POST['Sponsor_ID'])) {
    $Sponsor_ID = $_POST['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

function getClinicSelected() {
    global $conn;
    $clinic_ID = $_SESSION['doctors_selected_clinic'];
    $sql = "SELECT Clinic_ID,Clinic_Name FROM tbl_clinic WHERE Clinic_Id='$clinic_ID'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $response = array();
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        $response['clinicID'] = $clinic_ID;
        $response['clinicName'] = $Clinic_Name;
    }
    return $response;
}

// echo getClinicSelected()['clinicID'].' '. getClinicSelected()['clinicName'];
?>
<br/>
<fieldset>  
    <legend align="center" ><b id="dateRange">CLINIC PATIENT LIST TODAY <span class='dates'><?php echo date('Y-m-d') ?></span></b></legend>
    <center>
        <table width='100%'>
            <tr>
                <td> 
                    <?php
                    $Today_Date = mysqli_query($conn, "select now() as today");
                    while ($row = mysqli_fetch_array($Today_Date)) {
                        $Today = $row['today'];
                    }
                    $today_start_date = mysqli_query($conn, "select cast(current_date() as datetime)");
                    while ($start_dt_row = mysqli_fetch_assoc($today_start_date)) {
                        $today_start = $start_dt_row['cast(current_date() as datetime)'];
                    }
                    ?>
                    <input type="text" autocomplete="off"value="<?= $today_start ?>"  id="Date_From" placeholder="Start Date"/>
                </td><td>
                    <input type="text" autocomplete="off" value="<?= $Today ?>" id="Date_To" placeholder="End Date"/>&nbsp;
                </td><td>
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()">
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn, $qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td><td>
                    <select name='Clinic_ID' id='Clinic_ID'  onchange="filterPatient()"
                            >
                        <option value="All">All Clinics</option>  
                        <?php
                        if (!empty(getClinicSelected())) {
                            echo "<option value=" . getClinicSelected()['clinicID'] . ">" . getClinicSelected()['clinicName'] . "</option>";
                        } else {
                            ?>  
                        <?php }
                        ?>
                        <option value="All">All Clinics</option> 
                        <?php
                        $qr = "SELECT * FROM tbl_clinic";
                        $clinic_results = mysqli_query($conn, $qr);
                        while ($clinic_rows = mysqli_fetch_assoc($clinic_results)) {
                            ?>
                            <option value='<?php echo $clinic_rows['Clinic_ID']; ?>'><?php echo $clinic_rows['Clinic_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td><td>
                    <input type='text' name='Search_Patient' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'></td><td>
                    <input type='text' name='Search_Patient' id='Search_Patient_Number' oninput="filterPatient()" placeholder='~~Search Patient Number~~'></td><td>
                    <input type='text' name='Search_Patient' id='Search_Patient_Old_Number' oninput="filterPatient()" placeholder='~~Search Patient Old Number~~'></td><td>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">

                </td>

            </tr>

        </table>
    </center>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">

                        <?php
                        //include 'clinicpatientlist_Pre_Iframe.php';
                        include 'clinicpatientlist_Iframe.php';
                        ?>
<!--<iframe width='100%' height=380px src='doctorconsultedpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>-->
                    </div>
                <!--<iframe width='100%' height=380px src='clinicpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>

<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Clinic = document.getElementById('Clinic_ID').value;
        var Patient_Number = document.getElementById('Search_Patient_Number').value;
        var Patient_Old_Number = document.getElementById('Search_Patient_Old_Number').value;


//        var Patient_Name1 = Patient_Name.replace(/^\s+/, '').replace(/\s+$/, '');
//        var Patient_Number1 = Patient_Number.replace(/^\s+/, '').replace(/\s+$/, '');
//        if(Patient_Name1==""&&Patient_Number1==""){
//            exit;
//        }

        document.getElementById('dateRange').innerHTML = "CLINIC PATIENT LIST FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "clinicpatientlist_Iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&Sponsor=' + Sponsor + '&Clinic=' + Clinic + '&Patient_Number=' + Patient_Number + '&Patient_Old_Number=' + Patient_Old_Number,

            success: function (html) {
                if (html != '') {

                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#clinicpatients').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#clinicpatients').DataTable({
            "bJQueryUI": true

        });
        // filterPatient();
        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>



<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script type="text/javascript" src="js/afya_card.js"></script>
<script type="text/javascript" src="js/finger_print.js"></script>
<!--<div id="verify_by_finger_print">
    <table class="table" style="background:#FFFFFF">
        <caption><b>Verify this patient by finger print</b></caption>
        <tr>
            <td width="50%"><b>Patient Name</b></td>
            <td id="from_finger_patient_name"></td>
        </tr>
        <tr>
            <td width="50%"><b>Patient Registration Id</b></td>
            <td id="from_finger_registration_id"></td>
        </tr>
        <tr>
            <td colspan="2" id="verify_finger_print_btn_area">

            </td>
        </tr>
    </table>
</div>-->
<script>
    $(document).ready(function () {
        check_if_afya_card_config_is_on()
        //alert("load");
    });

    function check_if_afya_card_config_is_on() {
        // alert("imeitwa");
        $.ajax({
            type: 'POST',
            url: 'ajax_check_if_afya_card_config_is_on.php',
            data: {function_module: "afya_card_module"},
            success: function (data) {
                // alert(data)
                if (data == "enabled") {
                    read_afya_card_infomation_and_process_patient();
                } else {
//                $("#afya_card_btn").val("AFYA CARD OFF");
//                $("#afya_card_btn").prop("class","hide");
                }
            }
        });
    }
    function check_for_afyacard_configuration(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Patient_Name) {
        window.location = "emergencyclinicalnotes.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&NR=true&PatientBilling=PatientBillingThisForm";
    }

</script>

<?php
include("./includes/footer.php");
?>
