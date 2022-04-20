<?php
include("./includes/functions.php");
include("./includes/header.php");

@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$Sub_Department_Name = $_SESSION['Admission'];

$qr = "SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                            $ward_results = mysqli_query($conn,$qr);
                            if(mysqli_num_rows($ward_results)>0){
                                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                                    
                                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";
    
                                }
                            }

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
<a href="./nurse_duty.php" class="art-button-green">BACK</a>
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
</style>
<br/><br/>
<center>
    <fieldset>  
        <table width='100%'>
            <tr>
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" value='<?php echo $Date_From; ?>' placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To"  value='<?php echo $Date_To; ?>' placeholder="End Date"/>&nbsp;
                    <select id='employee_id' class="select2-default" onchange='Search_nurse_duty()' style='text-align: center;width:10%;display:inline'>

                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT po.current_nurse, em.Employee_Name FROM tbl_employee em, tbl_nurse_duties po WHERE em.Employee_ID = po.current_nurse GROUP BY po.current_nurse") or die(mysqli_error($conn));

                        echo $options;
                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['current_nurse']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id='Shift' class="select2-default" onchange='Search_nurse_duty()' style='text-align: center;width:10%;display:inline'>
                        <option value='All'>All Shifts</option>
                        <option>Morning Shift</option>
                        <option>Evening Shift</option>
                        <option>Night Shift</option>
                    </select>
                    <select id='Ward_Type' class="select2-default" onchange='Search_nurse_duty()' style='text-align: center;width:10%;display:inline'>
                        <option value='All'>All Ward Types</option>
                        <option>General</option>
                        <option>HDU</option>
                    </select>
                    <select id='Ward_ID' class="select2-default" onchange='Search_nurse_duty()' style='text-align: center;width:10%;display:inline'>
                        <option value='All'>All Wards</option>
                        <?php
                            $Wards_Included = mysqli_query($conn, "SELECT Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward WHERE ward_type = 'ordinary_ward' ORDER BY Hospital_Ward_Name ASC");
                            echo $Display;
                            while($wards = mysqli_fetch_array($Wards_Included)){
                                $Hospital_Ward_Name = $wards['Hospital_Ward_Name'];
                                $Hospital_Ward_ID = $wards['Hospital_Ward_ID'];
                            echo "<option value='".$Hospital_Ward_ID."'>".$Hospital_Ward_Name."</option>";
                            }
                        ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="Search_nurse_duty()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='margin-top:15px;'>
    <legend align='center' style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>WARD HANDLING REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
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
            Search_nurse_duty();
	})
    function Search_nurse_duty() {
        var fromDate = document.getElementById('Date_From').value;// $('#date_From').val(); 
        var toDate = document.getElementById('Date_To').value;
        var employee_id = $("#employee_id").val();
        var Ward_ID = $("#Ward_ID").val();
        var Shift = document.getElementById('Shift').value;
        var Ward_Type = $("#Ward_Type").val();

        $('#printPreview').attr('href', 'surgery_performance_report_print.php?fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id + '&Shift=' + Shift);

        $.ajax({
            type: 'GET',
            url: 'duty_handle_frame.php',
            data: 'fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id + '&Shift=' + Shift + '&Ward_ID=' + Ward_ID + '&Ward_Type=' + Ward_Type,
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

