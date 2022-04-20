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
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Radiology_Works'])) {
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
?>
<a href='radiologyworkspage.php' class='art-button-green'>
    BACK
</a>
<br/><br/><br/>
<center>
    <fieldset>  
        <table width='100%'>
            <tr>
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select name='Sponsor_ID' id='Sponsor_ID' class="select2-default"  style='text-align: center;width:17%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id='employee_id' class="select2-default" style='text-align: center;width:17%;display:inline'>
                        <option value="All">All Doctors</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor' ORDER BY Employee_Name
                                ") or die(mysqli_error($conn));
                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id="testNameResults" style='text-align: center;width:21%;display:inline'>
                        
                    </select>
                    <!--<input type='text' name='testNameResults'  id='testNameResults' oninput="filterPatient()" placeholder=''>-->
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                    <a href="previewdoctortestperfm.php" id="previewdoctorperfm" style="display:none" target="_blank"  class="art-button-green" >Preview</a>
                </td>

            </tr>

        </table>
    </fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d')         ?></span></b></legend>-->
    <legend align='center' style="text-align: center;"><b id="dateRange">DOCTOR'S TEST PERFORMANCE SUMMARY </b></legend>

    <center>
        <table width='100%' border='1'>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
                        <?php include 'rad_doctor_test_report_Iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<div id="DoctorsTestDetails" style="width:90%;overflow-x:hidden; overflow-y: scroll;display:none; " >
    <table width="100%" border=0>
        <tr><td style="text-align: right">
            <a href="previewdoctortestperfmdetails.php" id="previewdoctorperfmdetails" style="display:none;text-align: right;" target="_blank"  class="art-button-green" >Preview</a>
            </td>
        </tr>
        <tr>
            <td>
             <fieldset id='showData' style="height:auto;background-color:white;">
             </fieldset>
            </td>
        </tr>
    </table>
   
</div>
<script>
    function filterPatient() {
        document.getElementById('Date_From').style.border = "1px solid #C0C1C6";
        document.getElementById('Date_To').style.border = "1px solid #C0C1C6";

        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var testNameResults = document.getElementById('testNameResults').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var employee_id = document.getElementById('employee_id').value;
        var selectedTestName = $('#testNameResults option:selected').text();
        var range = '';

        if (testNameResults == '') {
            alert("Select test name to continue");
            document.getElementById('testNameResults').style.border = "2px solid red";
            $('#testNameResults').focus();
            exit;
        }

        if (Date_From != '' && Date_To != '') {
            range = "FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        if (Date_From == '' && Date_To != '') {
            alert("Please enter start date");

            document.getElementById('Date_From').style.border = "2px solid red";
            exit;
        }
        if (Date_From != '' && Date_To == '') {
            alert("Please enter end date");
            document.getElementById('Date_To').style.border = "2px solid red";
            exit;
        }
        
        var datastring='Date_From=' + Date_From + '&Date_To=' + Date_To + '&testNameResults=' + testNameResults + '&Sponsor=' + Sponsor + '&employee_id=' + employee_id + '&check_in_type=Radiology';

      $('#previewdoctorperfm').attr('href','previewdoctortestperfm.php?'+datastring).show();
      
      
        document.getElementById('dateRange').innerHTML = "DOCTOR'S TEST PERFORMANCE SUMMARY " + range + " <br> FOR <span class='dates'>" + selectedTestName + "</span";
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "rad_doctor_test_report_Iframe.php",
            data: datastring,
            success: function (html) {
                if (html != '') {

                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#doctosTestResult').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#doctosTestResult').DataTable({
            "bJQueryUI": true

        });

        $("#DoctorsTestDetails").dialog({autoOpen: false, width: '90%', height: 500, title: 'Doctor Test Details', modal: true});

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

        //autocomplete search box;

        $('.select2-default').select2();

        initializeRemoteSelect2();
    });
</script>
<script>
    function showDetails(query_string, Employee_Name,testName) {
         $('#showData').html('');
        $("#DoctorsTestDetails").dialog("option", "title","DR. "+ Employee_Name + " TEST DETAILS FOR "+testName);
        
     
      $('#previewdoctorperfmdetails').attr('href','previewdoctortestperfmdetails.php?'+query_string).show();
      
        
        $.ajax({
            type: 'GET',
            url: 'requests/showDoctoTestDetails.php',
            data: query_string,
            beforeSend: function (xhr) {
                        $('#showData').html('<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                    },
            success: function (result) {
                //alert(result);
             if(result != ''){
                $('#showData').html(result);
                 
             }
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
        $("#DoctorsTestDetails").dialog('open');
    }
</script>
<script>
    function initializeRemoteSelect2() {

        //remote search test name select2

        $("#testNameResults").select2({
            ajax: {
                url: "search_items_for_select2.php",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        consultationType: 'Radiology' //consultation type
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: "~~~~~~~Select Test Name~~~~~~~",
            allowClear: true
        });
    }
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>