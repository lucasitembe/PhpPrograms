<?php
include("./includes/functions.php");
include("./includes/laboratory_specimen_collection_header.php");
$DateGiven = date('Y-m-d');

?>
<?php
//get sub department id
$Sub_Department_ID = '';
if (isset($_SESSION['Laboratory'])) {
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
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
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] .'">' . $row['Guarantor_Name'] . '</option>';
}

$query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));

$sub_category ='<option value="All">All Department</option>';

while ($row = mysqli_fetch_array($query_sub_cat)) {
    $sub_category.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
////for urgent count
//$select_Filtered_Patients ="SELECT Priority FROM tbl_item_list_cache WHERE Check_In_Type ='Laboratory' AND (Status='active' OR Status='paid') AND Priority='Urgent' AND removing_status='No' AND DATE(Transaction_Date_And_Time)=DATE(NOW()) GROUP BY Payment_Cache_ID";    
//
//
//    $result=mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn));
//       
//
//    $num_of_urgent_p=  mysqli_num_rows($result);
?>
<div id="number_of_urgent_patien"></div>
<fieldset style='margin-top:30px;'>
    <legend id="resultsconsultationLablist" style="font-weight: bold;font-size: 16px;background-color: #006400; padding:10px; color:white;width: auto">SPECIMEN COLLECTION - UNCONSULTED PATIENTS </legend>
    <center>
        <hr width="100%">
    </center>

    <center>

        <table  class="hiv_table" style="width:100%">
            <tr>

                <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <select id="subcategory_ID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $sub_category ?>
                    </select>
                    <select id="patient_priority_status" style='text-align: center;padding:4px; width:15%;display:inline' onchange="filterLabpatient()">
                        <option value="all">Filter Agent Patient</option>
                        <option value="urgent_patient">Urgent</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
               </td>
            </tr>
            <tr>
                <td>
                    <center>
                        <table style="width: 70%">
                            <td>   
                                <input type='text' autocomplete="off" name='Search_Patient' id='Search_Patient' style='text-align: center;display:inline' oninput='filterLabpatient()'  placeholder='~~~~~~~~~~~~~  Global Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
                            </td>
                            <td>
                                <input type='text' autocomplete="off" name='Search_Patient_by_number' id='Search_Patient_by_number' style='text-align: center;display:inline' oninput='filterLabpatient()'  placeholder='~~~~~~~~~~~~~  Search Patient By Number  ~~~~~~~~~~~~~~~~~~~'>
                           </td>
                        </table>
                    </center>
                </td>
            </tr>
            <tr>
                <td id='Search_Iframe'>
                    <div id="iframeDiv" style="height: 460px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'requests/Search_Inpatient.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<div id="doctorReview" style="display: none;height:340px;overflow-x:hidden;overflow-y:scroll   ">
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

    <div id="doctorReviewData"></div>

</div>
<br/>
<center>
    <?php
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo '<a href="searchpatientlaboratorylist.php" class="art-button-green hidemehere">UNCONSULTED PATIENTS</a>';
    }
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo '<button id="attendedlist" class="art-button-green">CONSULTED PATIENTS</button>';
    }

    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo '<a href="removedspecimenCollectionPatients.php" class="art-button-green hidemehere">REMOVED PATIENTS</a>';
    }
    ?>
</center> 
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
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }


</script>
<?php
include("./includes/footer.php");
?>
  <?php 
        $from_doctor_pg_ipd=".";
        if(isset($_GET['from_doctor_pg_ipd'])){
           $from_doctor_pg_ipd="from_doctor_pg_ipd"; 
        }
        $from_doctor_pg_opd=".";
        if(isset($_GET['from_doctor_pg_opd'])){
           $from_doctor_pg_opd="from_doctor_pg_opd"; 
        }
        $from_nurse_pg_opd=".";
        if(isset($_GET['from_nurse_pg_opd'])){
           $from_nurse_pg_opd="from_nurse_pg_opd"; 
        }
         ?>
<script>

    function filter_urgent(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var subcategory_ID = document.getElementById('subcategory_ID').value;
        var patient_priority_status = "urgent_patient";
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Search_Patient_by_number = document.getElementById('Search_Patient_by_number').value;

        document.getElementById('iframeDiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = function () {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                document.getElementById('iframeDiv').innerHTML = data;
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientLabList').DataTable({
                    "bJQueryUI": true

                });
            }


        }; //specify name of function that will handle server response........

        var rom_doctor_pg_ipd='<?= $from_doctor_pg_ipd ?>';
        var rom_doctor_pg_opd='<?= $from_doctor_pg_opd ?>';
        var from_nurse_pg_opd='<?= $from_nurse_pg_opd ?>';
        mm.open('GET', 'requests/Search_Inpatient.php?Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor+ '&subcategory_ID=' + subcategory_ID+"&Search_Patient_by_number="+Search_Patient_by_number+'&'+rom_doctor_pg_ipd+'&'+rom_doctor_pg_opd+'&'+from_nurse_pg_opd+'&patient_priority_status='+patient_priority_status, true);
        mm.send();

    }
    function filterLabpatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var subcategory_ID = document.getElementById('subcategory_ID').value;
        var patient_priority_status = document.getElementById('patient_priority_status').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Search_Patient_by_number = document.getElementById('Search_Patient_by_number').value;
        
        if(Date_From != '' && Date_To == ''){
           alert('Please end date to filter');
            exit; 
        }

        if (Date_From == '' && Date_To != '') {
            alert('Please start date to filter');
            exit;
        }

        document.getElementById('iframeDiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = function () {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                document.getElementById('iframeDiv').innerHTML = data;
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientLabList').DataTable({
                    "bJQueryUI": true

                });
            }


        }; //specify name of function that will handle server response........

        var rom_doctor_pg_ipd='<?= $from_doctor_pg_ipd ?>';
        var rom_doctor_pg_opd='<?= $from_doctor_pg_opd ?>';
        var from_nurse_pg_opd='<?= $from_nurse_pg_opd ?>';
        mm.open('GET', 'requests/Search_Inpatient.php?Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor+ '&subcategory_ID=' + subcategory_ID+"&Search_Patient_by_number="+Search_Patient_by_number+'&'+rom_doctor_pg_ipd+'&'+rom_doctor_pg_opd+'&'+from_nurse_pg_opd+'&patient_priority_status='+patient_priority_status, true);
        mm.send();
    }

</script>   
<script>
    function doctorReview(Patient_Name, Registration_ID,payment_cache_id) {
        var filter = $('#reg_' + Registration_ID).attr('filter');
        
        var dataString = 'doctorReviewSpecColl=true&consulted=true&Registration_ID=' + Registration_ID +'&payment_cache_id='+payment_cache_id+ '&filter=' + filter;
        
        $('#doctorReview').dialog({
            modal: true,
            width: '70%',
            height: 440,
            resizable: true,
            draggable: true
        });

        //alert('doctorReview=true&Registration_ID=' + Registration_ID + '&filter=' + filter);
        $.ajax({
            type: 'GET',
            url: 'requests/labDoctorReview.php',
            data: dataString,
            success: function (result) {
                $('#doctorReviewData').html(result);
            }
        });



        $("#doctorReview").dialog('option', 'title', Patient_Name + '  ' + 'No.' + Registration_ID);
    }
</script>
<script type="text/javascript">
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 30});

    $('#viewPatientCategory').click(function () {
        alert('am visible');
    });


    $("#patientlist").change(function () {
        $('#consultationLablist').text('SPECIMEN COLLECTION - UNCONSULTED PATIENTS');
        var txt = $(this).val();
        $.ajax({
            type: 'POST',
            url: "requests/Search_Inpatient.php",
            data: "action=changed&patient=" + txt,
            success: function (html) {
                $('#iframeDiv').html(html);
            }
        });
    });


    setInterval(
            function () {
                // loadlab();
            }, 900);

    function loadlab() {
        $('#iframeDiv').load("requests/Search_Inpatient.php");
    }

    $('#attendedlist').click(function () {
//            $('#SearchingResults').hide();
//            $('#displaySearchresults').show();
        $('#consultationLablist').text('SPECIMEN COLLECTION - CONSULTED PATIENTS');
        $.ajax({
            type: 'POST',
            url: "requests/Search_Inpatient.php",
            data: "viewAttended=attended&",
            success: function (html) {
                $('#iframeDiv').html(html);
            }
        });
    });




</script>
<script type="text/javascript">
    $('#dates_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_From').datetimepicker({value: '', step: 30});
    $('#dates_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_To').datetimepicker({value: '', step: 30});

</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#viewlabPatientList,#patientLabList').DataTable({
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
<!--End datetimepicker-->