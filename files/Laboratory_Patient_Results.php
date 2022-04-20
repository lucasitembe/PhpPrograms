<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID = '';
if (isset($_SESSION['Laboratory'])) {
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
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
?>
<a href="Laboratory_Reports.php?LaboratoryReportThisPage=ThisPage" class="art-button-green">BACK</a>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS LABORATORY RESULTS REPORT</b></legend>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>

    <div id="patientLabItemDiv" style="width:500px;height:300px; overflow-x: hidden;overflow-y:scroll; display:none" >
        <div id="patientResults"></div>
        <center> <a href="#" target="_blank" id="printItemsPreview" class="art-button-green">PRINT PATIENT LAB RESULTS</a></center>

    </div>

    <form action="#" method='POST'>
        <center>
<!--            <table  class="hiv_table" style="width:100%;margin-top:5px;">
                <tr> 

                    <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                                <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                                <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                                            <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                                            <td width="50px"><input type="submit" name="submit" id="filterDate" value="Filter" class="art-button-green" /></td>

                                            </tr> -->
            <!--</table>-->
            <table  class="hiv_table" style="width:100%">
                <tr>

                    <td style="text-align: center">
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_From' id='date_From' placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_To' id='date_To' placeholder="End Date"/>&nbsp;
                        <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                            <?php echo $dataSponsor ?>
                        </select>
                        <input type="submit" name="submit" id="filterDate" value="Filter" class="art-button-green" />
                    </td>
                </tr></table>
        </center>
    </form>                                    




</center>
<center>
    <hr width="100%">
</center>

<center>
    <table  class="hiv_table" style="width:100%">
        <tr>
            <td>
                <div style="width:100%; height:380px;overflow-x: hidden;overflow-y: auto" id='Search_Iframe'>
                    <?php include 'Laboratory_Patients_Result_Iframe.php'; ?>
                </div>
            </td>
        </tr>
    </table>
</center>
</fieldset>
<center>   
    <a href="print_patients_sent_to_laboratory.php" style="display:none " target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a></center>


<?php
include("./includes/footer.php");
?>
<script type='text/javascript'>

    $(document).ready(function () {
        $("#patientLabItemDiv").dialog({autoOpen: false, width: '90%', height: '600', title: 'Patient Lab Result', modal: true});
        $('#patient-lab-result').dataTable({
            "bJQueryUI": true
        });
       
        $('.fancybox').fancybox();
    });
</script> 
<script>
//    function Show_Items_Taken(Registration_ID, Patient_Name, fromdate, todate) {
//        //alert(Registration_ID+' '+Patient_Name+' '+fromdate+' '+todate);
//        $('#printItemsPreview').attr('href', 'print_patient_sent_lab_item.php?Registration_ID=' + Registration_ID + '&fromdate=' + fromdate + '&todate=' + todate);
//
//        $.ajax({
//            type: 'GET',
//            url: "getPatientSentLabItems.php",
//            data: 'action=true&Registration_ID=' + Registration_ID + '&fromdate=' + fromdate + '&todate=' + todate,
//            beforeSend: function (xhr) {
//                $("#patientLabItemDiv").dialog('option', 'title', Patient_Name + '  ' + '#.' + Registration_ID);
//
//            },
//            success: function (html) {
//                //alert(html);
//                $('#patientItems').html(html);
//            }, complete: function (jqXHR, textStatus) {
//                $("#patientLabItemDiv").dialog('open');
//            }, error: function (jqXHR, textStatus, errorThrown) {
//                alert(textStatus);
//            }
//        });
//
//    }
 function openPatientResults(parameters,Patient_Name,Registration_ID){
    $('#patientResults').html('');
      $('#printItemsPreview').attr('href', 'print_patient_lab_result.php?Patient_Name=' + Patient_Name + '&' + parameters);

      $.ajax({
            type: 'GET',
            url: 'testResultIndividualPatient.php',
            data: parameters,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#patientResults').html(html);
            }, complete: function (jqXHR, textStatus) {
                $("#patientLabItemDiv").dialog('open');
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
  }
</script>
<script type="text/javascript">
    $('#filterDate').click(function (e) {
        e.preventDefault();
        var fromDate = $('#date_From').val();
        var toDate = $('#date_To').val();
        var sponsorID = $('#sponsorID').val();

        if (fromDate == '' || toDate == '') {
            alert('Please enter both dates to filter');
            exit;
        }
        
        $('#printPreview').attr('href', 'print_patients_sent_to_laboratory.php?fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID);

        $.ajax({
            type: 'GET',
            url: 'Laboratory_Patients_Result_Iframe.php',
            data: 'filterlabpatientdate=true&fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('#patient-lab-result').dataTable({
                        "bJQueryUI": true
                    });
                }
            }
        });


    });


    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now',
        dateFormat: 'yy-mm-dd'
    });
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now',
        dateFormat: 'yy-mm-dd'
    });
    $('#date_To').datetimepicker({value: '', step: 30});
</script>
<!--End datetimepicker-->

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>

