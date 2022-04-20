<?php
        /*+++++ Designed and implimented by  Eng. Meshack moscow Since 2019-11-13 ++++++++++*/
    include("./includes/header.php");
    include("./includes/connection.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $DateGiven = date('Y-m-d');

    $query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $dataSponsor = '';
    $dataSponsor.='<option value="All">All Sponsors</option>';

    while ($row = mysqli_fetch_array($query)) {
        $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
    }
?>
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>   
<fieldset style='margin-top:15px;'>
    <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>PATIENT DONE ANAETHESIA  REPORT</b></legend>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>

    <div id="patientLabItemDiv" style="width:500px;height:300px; overflow-x: hidden;overflow-y:scroll; display:none" >
        <div id="patientItems"></div>
        <center> <a href="#" target="_blank" id="printItemsPreview" class="art-button-green">PRINT PATIENT LAB ITEMS</a></center>

    </div>

    <form action="#" method='POST'>
        <center>
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
    <center>
    <hr width="100%">
</center>

<center>
    <table  class="hiv_table" style="width:100%">
        <tr>
            <td>
                <div style="width:100%; height:380px;overflow-x: hidden;overflow-y: auto" id='Search_Iframe'>
                    <?php include 'Ajax_anaesthesia_patient_report.php'; ?>
                </div>
            </td>
        </tr>
    </table>
</center>                                    
</fieldset>
<script>
    
    function goBack(){
        window.history.back();
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
            type: 'POST',
            url: 'Ajax_anaesthesia_patient_report.php',
            data: 'action=filter&fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('#patient-sent-to-lab').dataTable({
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
    $('#date_From').datetimepicker({value: '', step: 01});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now',
        dateFormat: 'yy-mm-dd'
    });
    $('#date_To').datetimepicker({value: '', step: 01});
</script>
<!--End datetimepicker-->

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>


<?php
    include("./includes/footer.php");
?>