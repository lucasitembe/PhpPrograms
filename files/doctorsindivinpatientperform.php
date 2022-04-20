<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
        ?>
<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/>
<?php


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $End_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($End_Date));
    $Start_Date = $new_Date . ' 00:00:00';
}

if (isset($_POST['Date_From'])) {
    $Date_From = mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_POST['Date_To']);
    $Sponsor= mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
}elseif (isset($_GET['Date_From'])) {
    $Date_From = mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_GET['Date_To']);
    $Sponsor= mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Date_From = '';
    $Date_To = '';
    $Sponsor='';
}

$employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Employee_Name=$_SESSION['userinfo']['Employee_Name'];

$filter="  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND wr.Employee_ID='$employee_ID'  AND wr.Process_Status='served'";

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter .="  AND pr.Sponsor_ID=$Sponsor";
     
}
?>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
    .rowlist{
        cursor: pointer; 
    }
    .rowlist:active {
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rowlist:hover{
        color:#00416a;
        background: #88c9ff;
        font-weight:bold;
    }
</style> 
<br/>
<fieldset style='overflow-y:scroll; height:440px' >
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;">
            <form action='doctorsindivinpatientperform.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <br/>
        <table width='69%'>
            <tr>
                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required' value="<?= $Start_Date ?>">    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To'    value="<?= $End_Date ?>"    required='required'></td>   
                <td style="text-align: center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
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
                </td>
                <td style='text-align: center;'>
                    <input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' onclick="filter_report()" value='FILTER'>
                </td>
            </tr>	
        </table>
        </form> 
    </center>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTOR'S ROUND REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b></legend>
    <center id="roundresponce">
        
    </center>
</center>
</fieldset>
<table width="100%">
    <tr>
        <td style='text-align: left;'>
            <a href="previewFilterDoctorPerformanceInpatient.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor=<?php echo $Sponsor ?>" target="_blank">

                <input type='submit' name='previewFilterDoctorPerformance' id='previewFilterDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>

            </a>
        </td>
        <td style='text-align: right;'>
            <h1 id="sumValues" style="text-align:right;font-size:18px;padding-right: 40px"></h1>
        </td>

    </tr>
</table>
<div id="patientoncalls"></div>
<?php
    include("./includes/footer.php");
?>
   
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
        
    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">    
    <script src="media/js/jquery.js"></script>
    <script src="media/js/jquery.dataTables.js"></script>
    <script src="media/js/sum().js"></script>
    <script src="css/jquery.datetimepicker.js"></script>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script>
        $('#date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_From').datetimepicker({value: '', step: 01});
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({value: '', step: 01});

</script>
<script>
    $(document).ready(function(){
        filter_report() 
    })
    function filter_report(){
        var date_From = $("#date_From").val();
        var date_To = $("#date_To").val();
        var Sponsor = $("#Sponsor_ID").val();
        $.ajax({
            type:'POST',
            url:'patients_oncall_report_iframe.php',
            data:{date_From:date_From,Sponsor:Sponsor, date_To:date_To,RoundReport:''},
            success:function(responce){                
               $("#roundresponce").html(responce);
            }
        });
    }
    function open_total_calls_claims(employeeID){        
        var date_From = $("#date_From").val();
        var date_To = $("#date_To").val();
        var Sponsor = $("#Sponsor_ID").val();
        var Employee_Name = '<?= $Employee_Name?>';
        $.ajax({
            type:'POST',
            url:'patients_oncall_report_iframe.php',
            data:{  employeeID:employeeID,date_From:date_From,Sponsor:Sponsor, date_To:date_To,filterdoctorcalls:''              },
            success:function(responce){                
                $("#patientoncalls").dialog({
                        title: 'Total Patients On call Claims For '+Employee_Name,
                        width: '80%',
                        height: 550,
                        modal: true,
                    });
                    $("#patientoncalls").html(responce);
            }
        });
    }
    $('#doctorsperformancetbl').dataTable({
        "bJQueryUI": true,
    });
</script>