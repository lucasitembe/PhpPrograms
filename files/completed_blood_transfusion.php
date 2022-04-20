<?php
include("./includes/header.php");

@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$Sub_Department_Name = $_SESSION['Laboratory'];



$DateGiven = date('Y-m-d');
$filter = '';
$options = '';

if (isset($_GET['loc']) && ($_GET['loc'] == 'doctor' || $_GET['loc'] == 'doctorinp')) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $filter = " AND Employee_ID=$Employee_ID";
} else {
    $options = '<option value="All">All Nurses</option>';
}

$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time,0,11);
$Date_From = $Filter_Value.' 00:00';
$Date_To = $Current_Date_Time;

?>
<a href="./Laboratory_Reports.php?LaboratoryResultsThisPage=ThisPage" class="art-button-green">BACK</a>
<style>
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
    .hiv_table tr:hover{
        background: #dedede;;
    }
</style>
<br/><br/>
<center>
    <fieldset>  
        <table width='100%' class="">
            <tr style='background: #fff !important;'>
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" value='<?php echo $Date_From; ?>' placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To"  value='<?php echo $Date_To; ?>' placeholder="End Date"/>
                    <input type='text' name='Patient_Name' title='Incase You want to filter by Patient Name'  id='Patient_Name' style='text-align: center; width: 18%;'  onkeyup='filterPatient()' placeholder='~~~~~~~~Search Patient Name~~~~~~~~~'>
                <input type='text' name='Patient_Number' title='Incase You want to filter by Patient Number'  id='Patient_Number' style='text-align: center; width: 18%;'  onkeyup='filterPatient()' placeholder='~~~~~~~~Search Patient Number~~~~~~~~~'>

                    &nbsp;
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='margin-top:15px;'>
    <legend align='center' style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>LIST OF PROCESSED BLOOD TRANSFUSION REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr style='background: #fff !important;'>
                <td>
                    <div style="width:100%; height:450px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<center> 
</center> 

<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script>
    	$(document).ready(function(){
            filterPatient();
	})
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;// $('#date_From').val(); 
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = $("#Patient_Name").val();
        var Patient_Number = $("#Patient_Number").val();

        // $('#printPreview').attr('href', 'surgery_performance_report_print.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&employee_id=' + employee_id + '&Shift=' + Shift);

        $.ajax({
            type: 'GET',
            url: 'completed_blood_transfusion_iframe.php',
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&Patient_Number=' + Patient_Number,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '' && html != '0') {

                    $('#Search_Iframe').html(html);
                    $('.display').dataTable({
                        "bJQueryUI": true
                    });
                } else if (html == '0') {
                    $('#Search_Iframe').html('');
                }
            }

        });

    }
</script>

<script>
    $(document).ready(function () {
        //$.fn.dataTableExt.sErrMode = 'throw';
        $('.display').dataTable({
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

