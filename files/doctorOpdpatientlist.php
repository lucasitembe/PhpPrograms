<style>
    .dates{
        color:#cccc00;
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
                }  else if (patientlist == 'OPD PATIENT LIST'){
                    document.location = "doctorOpdpatientlist.php?" + url;
                }else{
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
                 <option>
                    OPD PATIENT LIST
                </option>
                <option>
                    FROM NURSE STATION
                </option>
                
            </select>
            <input type='button' value='VIEW' onclick='gotolink()'>
        </label>  

        <a href='patientsignoff.php?<?php
        if ($Registration_ID != '') {
            echo "Registration_ID=" . $Registration_ID . "&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            SIGN OFF
        </a>
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

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='doctorOpdpatientlist_Iframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>
<br/><br/><br/>
<center>
  <fieldset> 
    <table width="100%">
        <tr>
            <td>
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                <input type='text' name='Search_Patient' style='text-align: center;width:50%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>

    </table>
  </fieldset> 
</center>
<br/>
<fieldset>  
    <legend align="center" ><b id="dateRange">CONSULTED OPD PATIENT LIST </b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                       
<?php include 'doctorOpdpatientlist_Pre_Iframe.php'; ?>
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
        var Patient_Name = document.getElementById('Search_Patient').value;
        
         if(Date_From !='' && Date_To !=''){
         document.getElementById('dateRange').innerHTML ="CONSULTED PATIENT LIST FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
     }
    document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "doctorOpdpatientlist_Iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name,
            
            success: function (html) {
              if(html != ''){
               
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
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>