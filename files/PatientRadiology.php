<?php
include("includes/header.php");
include("includes/connection.php");

/* * ***************************SESSION CONTROL****************************** */
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Radiology_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            if ($_SESSION['userinfo']['Radiology_Works'] == 'yes') {
                @session_start();
                if (!isset($_SESSION['Radiology_Supervisor'])) {
                    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Radiology&InvalidSupervisorAuthentication=yes");
                }
            }
        }
    } else {
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

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}

$select_subdep = "
							SELECT *
								FROM
								tbl_item_subcategory isc,
								tbl_item_category ic
									WHERE 
									isc.Item_category_ID = ic.Item_category_ID AND
									ic.Category_Type = 'Radiology'
						";

$select_subdep_qry = mysqli_query($conn,$select_subdep) or die(mysqli_error($conn));
$count = mysqli_num_rows($select_subdep_qry);
//echo $count;
?>
<input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button-green' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button-green' />

<a href='radiologyworkspage.php'class='art-button-green'> BACK </a>
<?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>
<br><br>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>


<fieldset style='margin-top:10px;'>
    <legend align="right" style="background-color:#006400; color:white"><b>  ALL RADIOLOGY PATIENT  LIST <?php if ($PatientType == 'in') { ?> - INPATIENTS <?php } else { ?> - OUTPATIENTS <?php } ?></b></legend>
    <center>

        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="text-align: center">
                    <select style='text-align: center;padding:4px; width:18%;display:inline' onChange="ChangePatientsList(this.value)" id="patientFrom">
                        <option value='FromDoc'>PATIENTS FROM DOCTOR</option>
                        <option value='FromRec'>PATIENTS FROM RECEPTION</option>
                    </select>
                    <select style='text-align: center;padding:4px; width:18%;display:inline' id="ChangeSubDep" onChange="ChangeSubDep(this.value)">
                        <option value='0' selected="selected">All</option>
                        <?php
                        while ($subdep = mysqli_fetch_assoc($select_subdep_qry)) {
                            $Subdep_ID = $subdep['Item_Subcategory_ID'];
                            $Subdep_Name = $subdep['Item_Subcategory_Name'];
                            ?>
                            <option  value='<?php echo $Subdep_ID; ?>'><?php echo $Subdep_Name; ?></option>
                        <?php } ?>

                    </select>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="searchPatient()">
                    <input type='text' autocomplete="off" name='Search_Patient' id='Search_Patient' style='text-align: center;width:35%;display:inline' oninput='searchPatient(this.value)'  placeholder='~~~~~~~~~~~~~  Global Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
                </td>
            </tr>
        </table>
    </center>

    <script>
        function ChangePatientsList(listtype) {

            var DateFrom = document.getElementById('Date_From').value;
            var DateTo = document.getElementById('Date_To').value;
            var Sub_Department_ID = document.getElementById('ChangeSubDep').value;
            var Patient_Name = document.getElementById('Search_Patient').value;
            var Sponsor = document.getElementById('sponsorID').value;
            var url = '';

            if (DateFrom != '' && DateTo == '') {
                alert('Please enter all  Date');
                exit();
            }
            if (DateTo != '' && DateFrom == '') {
                alert('Please enter all  Date');
                exit();
            }
            if (listtype == 'FromDoc') {
                url = 'RadiologyPatientsList_FromDoc.php?PatientType=' + listtype + "&Patient_Name=" + Patient_Name + "&Sub_Department_ID=" + Sub_Department_ID + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
            } else if (listtype == 'FromRec') {
                url = 'RadiologyPatientsList.php?PatientType=' + listtype + "&Patient_Name=" + Patient_Name + "&Sub_Department_ID=" + Sub_Department_ID + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
            }


            document.getElementById('Search_Iframe_test').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }


            mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
            mm.open('GET', url, true);
            mm.send();
        }

        function AJAXP() {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                document.getElementById('Search_Iframe_test').innerHTML = data;
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#radPatientList').DataTable({
                    "bJQueryUI": true

                });
            }
        }

    </script>

    <center>

        <div style="width:100%;height:350px;overflow-y:scroll;overflow-x: hidden " id="ListTable">
            <table  class="hiv_table" style="width:100%;">
                <tr>
                    <td id='Search_Iframe_test' >

                        <!-- src='search_patient_radiology_list_iframe.php' -->
                        <?php
                        $_GET['Sub_Department_ID'] = 0;
                        $_GET['PatientType'] = $PatientType;
                        include 'RadiologyPatientsList_FromDoc.php';
                        ?>
                        <!--<iframe id="PatientListIframe" width='100%' height="350px" src='RadiologyPatientsList_FromDoc.php?Sub_Department_ID=0&PatientType=<?php echo $PatientType; ?>'></iframe>-->
                    </td>
                </tr>
            </table>
        </div>
    </center>
</fieldset>

<br/>
<div style="float:right;">
    <a href='PatientRadiology_Served.php?PatientType=<?php echo $PatientType; ?>' class='art-button-green'> CONSULTED PATIENTS </a>
    <a href='PatientRadiology_Pending.php?PatientType=<?php echo $PatientType; ?>' class='art-button-green'> PENDING TESTS </a>
</div>

<?php
include("./includes/footer.php");
?>

<script>
    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 30});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Date_To').datetimepicker({value: '', step: 30});</script>
<script type='text/javascript'>
    function outpatient() {
        //alert('outpatient');
        var winClose = popupwindow('directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm', 'Outpatient Item Add', 1300, 700);
    }
</script>
<script type='text/javascript'>
    function inpatient() {
        var winClose = popupwindow('adhocinpatientlist.php?location=otherdepartment&AdhocInpatientList=AdhocInpatientListThisPage', 'Intpatient Item Add', 1300, 700);
    }
</script>

<script>
    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;
        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left); //'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }


</script>
<script>
    function ChangeSubDep(subDepartment) {
        var DateFrom = document.getElementById('Date_From').value;
        var DateTo = document.getElementById('Date_To').value;
        var patientFrom = document.getElementById('patientFrom').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var url = '';

        document.getElementById('Search_Iframe_test').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

//<?php // $$Radiology_Sub_Dep_ID =                 ?>subDepartment;
//alert(subDepartment);exit;
        if (patientFrom == 'FromDoc') {
            url = 'RadiologyPatientsList_FromDoc.php?PatientType=' + patientFrom + "&Sub_Department_ID=" + subDepartment + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
        } else if (patientFrom == 'FromRec') {
            url = 'RadiologyPatientsList.php?PatientType=' + patientFrom + "&Sub_Department_ID=" + subDepartment + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
        }

//alert(url);

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', url, true);
        mm.send();


    }

    function AJAXP() {
        var data = mm.responseText;
//alert(data);
        if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Search_Iframe_test').innerHTML = data;
            $.fn.dataTableExt.sErrMode = 'throw';
            $('#radPatientList').DataTable({
                "bJQueryUI": true

            });
        }
    }


</script>
<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name = ''){
        var DateFrom = document.getElementById('Date_From').value;
        var DateTo = document.getElementById('Date_To').value;
        var Sub_Department_ID = document.getElementById('ChangeSubDep').value;
        var patientFrom = document.getElementById('patientFrom').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var url = '';



        if (DateFrom != '' && DateTo == '') {
            alert('Please enter all  Date');
            exit();
        }
        if (DateTo != '' && DateFrom == '') {
            alert('Please enter all  Date');
            exit();
        }
        if (patientFrom == 'FromDoc') {
            url = 'RadiologyPatientsList_FromDoc.php?PatientType=' + patientFrom + "&Patient_Name=" + Patient_Name + "&Sub_Department_ID=" + Sub_Department_ID + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
        } else if (patientFrom == 'FromRec') {
            url = 'RadiologyPatientsList.php?PatientType=' + patientFrom + "&Patient_Name=" + Patient_Name + "&Sub_Department_ID=" + Sub_Department_ID + "&DateFrom=" + DateFrom + "&DateTo=" + DateTo + '&Sponsor=' + Sponsor;
        }

        document.getElementById('Search_Iframe_test').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', url, true);
        mm.send();
    }

    function AJAXP() {
        var data = mm.responseText;
//alert(data);
        if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Search_Iframe_test').innerHTML = data;
            $.fn.dataTableExt.sErrMode = 'throw';
            $('#radPatientList').DataTable({
                "bJQueryUI": true

            });
        }
    }

</script>

<script>
    $(document).ready(function () {
        $('#radPatientList').DataTable({
            "bJQueryUI": true
        });
    });
</script>
