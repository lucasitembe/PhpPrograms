<style>
    .dates{
        color:#cccc00;
    }
    .daterange{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
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
        if (isset($Registration_ID)) {
            if ($Registration_ID != '') {
                echo "Registration_ID=$Registration_ID&";
            }
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
                } else if (patientlist == 'CLINIC PATIENT LIST') {
                    document.location = "clinicpatientlist.php?" + url;
                } else if (patientlist == 'CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?" + url;
                } else if (patientlist == 'FROM NURSE STATION') {
                    document.location = "patientfromnursestation.php?NurseStationPatientList=NurseStationPatientListThisPage" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>
       
        <?php
         echo ' <a href="consult_patient_audit_print.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>';
         echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage" class="art-button-green">BACK</a>';
    }
}
?>

<br/><br/><br/>
<center>
    <fieldset> 
        <table width="100%">
            <tr>
                <td style="text-align:center">
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id='employee_id' class="select2-default" style='text-align: center;width:30%;display:inline'>
                        <option value="0">Select Doctor</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor'  ORDER BY Employee_Name ASC
                                ") or die(mysqli_error($conn));

                        echo $options;
                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id='processstatus' class="select2-default" style='text-align: center;width:10%;display:inline'>
                        <option>All</option>
                        <option>Signed-Off</option>
                        <option>Not-Signed-off</option>  
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                </td>
            </tr>

        </table>
    </fieldset> 
</center>
<br/>
<fieldset style=" background-color:white">  
    <legend align="center" ><b id="dateRange">CONSULTED PATIENT AUDIT</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">

                        <?php include 'consult_patient_audit_frame.php'; ?>
<!--<iframe width='100%' height=380px src='doctorconsultedpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>-->
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>

<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var employee_id = document.getElementById('employee_id').value;
        var processstatus = document.getElementById('processstatus').value;
        
         $('#printPreview').attr('href', 'consult_patient_audit_print.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&employee_id=' + employee_id + '&processstatus=' + processstatus);

        document.getElementById('dateRange').innerHTML = "CONSULTED PATIENT AUDIT FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "consult_patient_audit_frame.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&employee_id=' + employee_id + '&processstatus=' + processstatus,
            success: function (html) {
                if (html != '') {

                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#consultpatients').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#consultpatients').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 1});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 1});
        $('select').select2();
    });
</script>



<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>