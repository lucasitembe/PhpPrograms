
<?php
include("./includes/header.php");
include("./includes/connection.php");
$Title_Control = "False";
$Link_Control = 'False';
$Title = '';
$Transaction_Type = '';
$start_date = '';
$end_date = '';
$Transaction_channel = '';
$transaction_channel_filter="";
$table_card_mobile ="";
$card_mobile_condition="";
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['Section'])){
	    $Section = $_GET['Section'];
        if(strtolower($Section) == 'pharmacy'){
            echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
        }else{
?>
		<a href='performancereports.php?Section=<?php echo $Section; ?>&PerformanceReport=PerformanceReportThisPage' class='art-button-green'>BACK</a>
<?php
        }
	}else{
	    $Section = '';
?>
	    <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>
<?php
	}
    }
?>
<?php

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

$start_date = '';
$end_date = '';
?>


<!--    Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>

<!--<script src="script.js"></script>-->

<!--[if IE]>
<script type="text/javascript" src="js/excanvas.js"></script>
<![endif]-->

<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="js/jquery.signature.js"></script>
<!--<script src="script.js"></script>-->
<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script src="script.responsive.js"></script>-->
<link type="text/css" href="css/jquery.signature.css" rel="stylesheet">



<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

      //get today's date
      $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
      while($date = mysqli_fetch_array($sql_date_time)){
          $Current_Date_Time = $date['Date_Time'];
      }
      $Filter_Value = substr($Current_Date_Time,0,11);
      $start_Date = $Filter_Value.' 00:00';
      $End_Date = $Current_Date_Time;
?>

<br/><br/>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<center>
    <fieldset>
        <legend align='center'><b>ALL EMPLOYEE PERFOMANCE REPORT</b></legend>
        <!--<br/>-->
        <style>
            table,tr,td{
                border-collapse:collapse !important;
                border:none !important;

            }
            tr:hover{
                background-color:#eeeeee;
                cursor:pointer;
            }
        </style>

        <!--<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <table width=100%>
            <tr>
                <td style='width:10%; text-align: center; color:black; border:2px solid #ccc;text-align:center;' >
                    <input type='text' name='start_date' id='start_date' required='required' style='text-align: center;' value="<?php echo $start_Date; ?>"readonly>
                </td>
                <td style='text-align: center;width:10%; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='end_date' id='end_date' required='required' style='text-align: center;' value="<?php echo $End_Date; ?>"readonly>
                </td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Number" id="Registration_ID"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Receipt Number" id="receipt_number"/></td>
                <td>
                    <select id='Transaction_Type' onchange='filter_list_of_patient_sent_to_cashier()'>
                        <option value="All">All Transaction</option>
                        <option value="Cash">Cash</option>
                        <option value="Credit">Credit</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </td>
                <td><select   name='channel' id='channel' style='text-align: center;width:100%;display:inline' onchange="filter_list_of_patient_sent_to_cashier()" id="sangira_status">
                        <option value="All">All Channel</option>
                        <option value="to_nmb">NMB Bank</option>
                        <option value="crdb_pos">CRDB POS</option>
                        <option value="to_crdb">CRDB MOBILE</option>
                        <option value="manual">MANUAL</option>
                    </select></td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='filter_list_of_patient_sent_to_cashier()'>
                </td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <!-- <td><input type='button' onclick='previewrevenuecollection()' value='PREVIEW' class='art-button-green'/></td> -->
                </td>
                
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <td><input type='button' onclick='filter_list_of_patient_sent_to_cashier_excel()' value='PREVIEW-EXCEL' class='art-button-green'/></td>
                </td>
            </tr>
        </table>
</center>
</fieldset>
<fieldset>
<div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">
            <tr  style='background: #dedede;'>
                <td style="width:50px"><b>S/No</b></td>
                <td><b>Date And Time</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Receipt Number</b></td>
                <td><b>Auth No</b></td>
                <td><b>Cash</b></td>
                <td><b>Credit</b></td>
                <td><b>Cancelled</b></td>
                <td><b>Cancel Reason</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<script>
    $(document).ready(function () {
        $('select').select2();
    });
    
    function filter_list_of_patient_sent_to_cashier(){
        // var start_date=$('#start_Date').val();
        var start_date=document.getElementById('start_date').value;
        var end_date=$('#end_date').val();
        var channel=$('#channel').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var receipt_number=$('#receipt_number').val();
        var Transaction_Type=$('#Transaction_Type').val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_all_employee_perfomance_report.php',
            data:{
                start_date:start_date,
                end_date:end_date,
                Registration_ID:Registration_ID,
                Patient_Name:Patient_Name,
                receipt_number:receipt_number,
                Transaction_Type:Transaction_Type,
                channel:channel
            },
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
            }
        });
    }
    
    function filter_list_of_patient_sent_to_cashier_excel(){
        var start_date=document.getElementById('start_date').value;
        var end_date=$('#end_date').val();
        var channel=$('#channel').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var receipt_number=$('#receipt_number').val();
        var Transaction_Type=$('#Transaction_Type').val();
        
        document.location = 'all_employee_perfomance_excell.php?channel='+channel+"&start_date="+start_date+"&end_date="+end_date+"&Registration_ID="+Registration_ID+"&Patient_Name="+Patient_Name+"&receipt_number="+receipt_number+"&Transaction_Type="+Transaction_Type;
    }
    
    function previewrevenuecollection(){
        var start_date=document.getElementById('start_date').value;
        var end_date=$('#end_date').val();
        var channel=$('#channel').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var receipt_number=$('#receipt_number').val();
        var Transaction_Type=$('#Transaction_Type').val();

       window.open('all_employee_perfomance_report_pdf.php?start_date='+start_date+'&end_date='+end_date+'&channel='+channel+"&Registration_ID="+Registration_ID+"&Patient_Name="+Patient_Name+"&receipt_number="+receipt_number+"&Transaction_Type="+Transaction_Type, '_blank');
    }
    $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>
<?php
include("./includes/footer.php");
?>
