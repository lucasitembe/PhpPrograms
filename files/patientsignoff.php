<?php
include("./includes/header.php");
include("./includes/connection.php");
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
                } else if (patientlist == 'CLINIC PATIENT LIST') {
                    document.location = "clinicpatientlist.php?" + url;
                } else if (patientlist == 'CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>
        <label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
            <select id='patientlist' name='patientlist'>
                <!--	<option></option>-->
                <option>
                    MY PATIENT LIST
                </option>
                <option>
                    CLINIC PATIENT LIST
                </option>
                <option>
                    CONSULTED PATIENT LIST
                </option>
            </select>
            <input type='button' value='VIEW' onclick='gotolink()'>
        </label>
        <?php
        ?>
        <a href='doctorspageoutpatientwork.php?<?php
        if (isset($Registration_ID)) {
            echo "Registration_ID=$Registration_ID&";
        }
        ?><?php
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>PatientBilling=PatientBillingThisPage' class='art-button-green'>
            BACK
        </a>
    <?php
    }
}
?>
<style>
    .hover tr:hover{
        background-color: #ccc;
    }
    table.nobordertable tr{
        border: 0 !important;
    }

    table.nobordertable{
        border: 0 !important;
    }

    table.nobordertable td{
        border: 0 !important;

    } 
</style>
<br/><br/>
<fieldset>
    <center>
        <table width="80%" class="nobordertable">

            <tr>
                <td style="text-align: left;width:20%"> <input type='checkbox'  style="text-align: center" onclick='checkAllPatient(this)' id="idsof" /><label for="idsof"><b>Check All</b></label></td>
                <td style="text-align: center;">
                    <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='searchPatient(this.value)' placeholder='Search Patient Name'>
                </td>
                <td style="text-align: center;">
                    <input type='text' name='Search_Patient_number' id='Search_Patient_number' style="text-align: center;" oninput='searchPatient(this.value)' placeholder='Search Patient Number'>
                </td>
                <td style="text-align: right;width:30% ">
                    <input type='button'  onclick="signOffAllPatient()" class="art-button-green" value="Sign-Off Selected Patient(s)"/>
                </td>
            </tr>

        </table>
    </center>
</fieldset>

<br/>
<fieldset>
    <legend align=center><b>PATIENTS FOR SIGNOFF</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div align="center"  id="progressStatus"  style="display: none "><img src="images/ajax-loader_1.gif" style="border-color:white;"></div>
                    <div style="width:100%;height: 320px; background-color: white;overflow-x: hidden;overflow-y: scroll" id="Search_Iframe">
<?php include 'patientsignoff_Iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>

<script type='text/javascript'>
                        $(document).ready(function () {
                            $('select').select2();
                        });
</script>

<script>
    function searchPatient(Patient_Name) {
        var Patient_Name=$("#Search_Patient").val();
        var Search_Patient_number=$("#Search_Patient_number").val();
        $.ajax({
            type: 'GET',
            url: 'patientsignoff_Iframe.php',
            data: {Patient_Name:Patient_Name,Search_Patient_number:Search_Patient_number},
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                $("#Search_Iframe").html(html);
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });
    }
</script>
<script type='text/javascript'>
    function patientsignoff(Registration_ID, Patient_Payment_ID, Patient_Name) {
        var Confirm_Signoff = confirm("Are you sure you want to signoff " + Patient_Name + "?");
        if (Confirm_Signoff) {
            var Employee_ID = "<?php echo $_SESSION['userinfo']['Employee_ID']; ?>";
            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }
            mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
            mm.open('GET', 'patientsignoff_ajax.php?Patient_Payment_ID=' + Patient_Payment_ID + '&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
            mm.send();
            return true
        } else {
            return false;
        }
    }
    function AJAXP() {

        if (mm.readyState == 4) {
            var data = mm.responseText;
            if (data == 'signedoff') {
                document.location.reload();
            } else {
                alertMsg("An error has occured! Please Contact administrator for support", "No Patient Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            }
        }
    }
</script>
<script>
    function checkAllPatient(instance) {
        if ($(instance).is(":checked")) {
            //alert('Checked');
            $(".signedoff_check").each(function () {
                // $(this).attr('checked',true);
                this.checked = true;
            });
        } else {
            $(this).attr('checked', false);
            $(".signedoff_check").each(function () {
                this.checked = false;
            });
        }
    }
</script>
<script>
    function signOffAllPatient() {
        var dataInfo = '';
        var count = 0;
        var i = 1;
        $(".signedoff_check").each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select patient(s) to sign-off", "No Patient Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }

        $.ajax({
            type: 'GET',
            url: 'patientsignoff_ajax.php',
            data: 'dataInfos=' + dataInfo,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html == 'signedoff') {
                    document.location.reload();
                } else {
                    alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                }
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });

    }
</script>
<?php
include("./includes/footer.php");
?>