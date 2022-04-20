<?php
include("includes/header.php");
include("includes/connection.php");

/* * ***************************SESSION CONTROL****************************** */
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
/* * *************************** SESSION ********************************** */

if (isset($_GET['PatientType'])) {
    $PatientType = $_GET['PatientType'];
} else {
    $PatientType = '';
}
?>

<?php
//get sub department id
$Sub_Department_ID = '';
if (isset($_SESSION['Radiology'])) {
    $Sub_Department_Name = $_SESSION['Radiology'];
    $select_sub_department = mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}

$goback = $_SERVER["HTTP_REFERER"];
$docURL = basename($goback);
$docu = substr($docURL, 0, 10);

if ($docu == 'doctorspag') {
    $_SESSION['BackToDoctorPage2'] = $_SERVER["HTTP_REFERER"];
}
?>
<a href='./doctorspageinpatientwork.php?<?php echo $_SERVER['QUERY_STRING']; ?>' class='art-button-green'> BACK </a>
<?php $Supervisor_ID = $_SESSION['userinfo']['Employee_ID']; ?>
<br><br>

<fieldset style='margin-top:10px;'>
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>  RADIOLOGY RESULTS INPATIENT</b></legend>
    

     <center>
        <table width="100%" align="center">
            <tr>
                <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">

                    <input type="text" name="Search_Patient" id="Search_Patient" style="text-align: center;width:35%;display:inline" placeholder="~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~" oninput="filterPatient()"  />
                </td>
            </tr>
        </table>
    </center>

                                        <center>
                                            <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                                            <table  class="hiv_table" style="width:100%">
                                                <tr>
                                                    <td >
                                                        <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                                                            <?php include 'RadiologyPatientsList_Served_Doctor_Inpatient.php'; ?>
                                                        </div>
                                                                        <!--<iframe id="PatientListIframe" width='100%' height=350px src='RadiologyPatientsList_Served_Doctor.php?Sub_Department_ID=<?php echo $Sub_Department_ID ?>&PatientType=<?php echo $PatientType; ?>'></iframe>-->
                                                    </td>
                                                </tr>
                                            </table>
                                        </center>
                                        </fieldset>

                                        <?php
                                        include("./includes/footer.php");
                                        ?>

                                        <script>
                                            $(document).ready(function () {
                                                $('#patientsResultInfo').DataTable({
                                                    "bJQueryUI": true

                                                });

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
                                        <script>
                                            function filterPatient() {
                                                //alert('dd');
                                                var Date_From = document.getElementById('Date_From').value;
                                                var Date_To = document.getElementById('Date_To').value;
                                                var Patient_Name = document.getElementById("Search_Patient").value;
                                                var consultation_ID = '<?php echo $_GET['consultation_ID'] ?>';
        

                                                if (Date_From == '' || Date_To == '') {
                                                    alert('Please enter both dates to filter');
                                                    exit;
                                                }

                                                $.ajax({
                                                    type: 'GET',
                                                    url: 'RadiologyPatientsList_Served_Doctor_Inpatient.php',
                                                    data: 'Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To+ '&consultation_ID=' + consultation_ID,
                                                    cache: false,
                                                    beforeSend: function (xhr) {
                                                        $("#progressStatus").show();
                                                    },
                                                    success: function (html) {
                                                        if (html != '') {
                                                            $("#progressStatus").hide();
                                                            $("#Search_Iframe").html(html);

                                                            $.fn.dataTableExt.sErrMode = 'throw';
                                                            $('#patientsResultInfo').DataTable({
                                                                "bJQueryUI": true

                                                            });
                                                        }
                                                    }, error: function (html) {

                                                    }
                                                });

                                            }
                                        </script>
                                        <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
                                        <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
                                        <script src="media/js/jquery.js" type="text/javascript"></script>
                                        <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
                                        <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
                                        <script src="css/jquery-ui.js"></script>