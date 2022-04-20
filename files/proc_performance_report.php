<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
$DateGiven = date('Y-m-d');

echo ' <a href="proc_performance_report_print.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>';
?>
<a href="procedurelistreport.php" class="art-button-green">BACK</a>
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
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id='employee_id' class="select2-default" style='text-align: center;width:17%;display:inline'>
                      <option value="All">All Doctors</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor' $filter ORDER BY Employee_Name ASC
                                ") or die(mysqli_error($conn));

                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='margin-top:15px;'>
    <legend align='center' style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>PROCEDURE PERFORMANCE REPORT</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:300px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                     </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>

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
                        function filterLabpatient() {
                            var fromDate = document.getElementById('Date_From').value;
                            var toDate = document.getElementById('Date_To').value;
                            var employee_id = document.getElementById('employee_id').value;

                            if (fromDate == '' || toDate == '') {
                                alert('Please enter both dates to filter');
                                exit;
                            }

                            $('#printPreview').attr('href', 'proc_performance_report_print.php?fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id);

                            $.ajax({
                                type: 'GET',
                                url: 'proc_performance_report_frame.php',
                                data: 'fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id,
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


