<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
$DateGiven = date('Y-m-d');

$empType = " Employee_Job_Code LIKE '%Sonographer%' || Employee_Job_Code LIKE '%Radiologist%'";
$selectAllRadEmployee = "SELECT Employee_ID, Employee_Name,Employee_Job_Code FROM tbl_employee WHERE $empType GROUP BY Employee_ID";
$selectAllRadEmployee_qry = mysqli_query($conn,$selectAllRadEmployee) or die(mysqli_error($conn));

$radiologist='';
while ($emp = mysqli_fetch_array($selectAllRadEmployee_qry)) {
    $empname = $emp['Employee_Name'];
    $employee_job_code = $emp['Employee_Job_Code'];
    $empid = $emp['Employee_ID'];
    
    $radiologist.="<option value='$empid'>$empname</option>";
}
?>
<a href="radiologyreports.php" class="art-button-green">BACK</a>
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
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>RADIOLOGY PERFORMANCE REPORT</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <select id="rad_emp_ID" style='text-align: center;padding:4px; width:20%;display:inline'>
                        <option value="All">All Radiology Employee</option>
                        <?php echo $radiologist ?>
                    </select>
                    <select id="rad_Type" style='text-align: center;padding:4px; width:15%;display:inline' onchange="getRadEmployees(this.value)">
                        <option>All</option>
                        <option>Sonographer</option>
                        <option>Radiologist</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                </td>

            </tr> 
        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:300px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                        <?php include 'rad_performance_report_frame.php'; ?> 
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>
<center> 
    <a href="rad_performance_report_print.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>
</center> 

<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="js/select2.min.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
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

                        function filterLabpatient() {
                            var fromDate = document.getElementById('date_From').value;// $('#date_From').val();
                            var toDate = document.getElementById('date_To').value;//$('#date_To').val();
                            var rad_emp_ID = document.getElementById('rad_emp_ID').value;
                            var rad_Type = document.getElementById('rad_Type').value;


                            if (fromDate == '' || toDate == '') {
                                alert('Please enter both dates to filter');
                                exit;
                            }

                            $('#printPreview').attr('href', 'rad_performance_report_print.php?fromDate=' + fromDate + '&toDate=' + toDate + '&rad_emp_ID=' + rad_emp_ID + '&rad_Type=' + rad_Type);

                            $.ajax({
                                type: 'GET',
                                url: 'rad_performance_report_frame.php',
                                data: 'fromDate=' + fromDate + '&toDate=' + toDate + '&rad_emp_ID=' + rad_emp_ID + '&rad_Type=' + rad_Type,
                                beforeSend: function (xhr) {
                                    document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                                },
                                success: function (html) {
                                    if (html != '') {

                                        $('#Search_Iframe').html(html);
                                        $('.display').dataTable({
                                            "bJQueryUI": true
                                        });
                                    }
                                }

                            });

                        }
</script>
<script>
    function getRadEmployees(rad_Type) {
        if (rad_Type != 'All') {
            $.ajax({
                type: 'GET',
                url: 'getRadEmployeeType.php',
                data: 'rad_Type=' + rad_Type,
                success: function (data) {
                    if (data != '') {
                        $('#rad_emp_ID').html(data);
                    }
                }
            });
        }else{
             $('#rad_emp_ID').html('<option value="All">All Radiology Employee</option>');
        }

    }
</script>
<script>
    $(document).ready(function () {
        //$.fn.dataTableExt.sErrMode = 'throw';
        $('.display').dataTable({
            "bJQueryUI": true
        });
        
         $('select').select2();
    });
</script>

