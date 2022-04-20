<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (!isset($_SESSION['Procedure_Supervisor'])) {
    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procedure_Works'])) {
        if ($_SESSION['userinfo']['Procedure_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}
?>
<a href="procedurelistreport.php" class="art-button-green">BACK</a>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>PATIENT SENT TO PROCEDURE REPORT</b></legend>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>

    <div id="patientProcItemDiv" style="width:500px;height:300px; overflow-x: hidden;overflow-y:scroll; display:none" >
        <div id="patientItems"></div>
        <center> <a href="#" target="_blank" id="printItemsPreview" class="art-button-green">PRINT PATIENT PROCEDURE ITEMS</a></center>

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
                    <?php include 'Patients_Sent_To_Procedure_Iframe.php'; ?>
                </div>
            </td>
        </tr>
    </table>
</center>
</fieldset>
<center>   
    <a href="print_patients_sent_to_procedure.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a></center>


<?php
include("./includes/footer.php");
?>
<script type='text/javascript'>

    $(document).ready(function () {
        $("#patientProcItemDiv").dialog({autoOpen: false, width: '90%', height: '300', title: 'Patient Laboratory Items', modal: true});
        $('#patient-sent-to-proc').dataTable({
            "bJQueryUI": true
        });
    });
</script> 
<script>
    function Show_Items_Taken(Registration_ID, Patient_Name, fromdate, todate) {
        //alert(Registration_ID+' '+Patient_Name+' '+fromdate+' '+todate);
        $('#printItemsPreview').attr('href', 'print_patient_sent_proc_item.php?Registration_ID=' + Registration_ID + '&fromdate=' + fromdate + '&todate=' + todate);

        $.ajax({
            type: 'GET',
            url: "getPatientSentProcItems.php",
            data: 'action=true&Registration_ID=' + Registration_ID + '&fromdate=' + fromdate + '&todate=' + todate,
            beforeSend: function (xhr) {
                $("#patientProcItemDiv").dialog('option', 'title', Patient_Name + '  ' + '#.' + Registration_ID);

            },
            success: function (html) {
                //alert(html);
                $('#patientItems').html(html);
            }, complete: function (jqXHR, textStatus) {
                $("#patientProcItemDiv").dialog('open');
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
        $('#printPreview').attr('href', 'print_patients_sent_to_procedure.php?fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID);

        $.ajax({
            type: 'POST',
            url: 'Patients_Sent_To_Procedure_Iframe.php',
            data: 'action=filter&fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('#patient-sent-to-proc').dataTable({
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
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

