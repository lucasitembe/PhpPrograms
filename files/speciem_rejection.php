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
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>SPECIMEN REJECTED</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:20%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
<!--                    <select id="subCatID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php //echo $dataSucategory ?>
                    </select>-->
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
                                                            <?php // include './number_of_test_taken_iframe.php'; ?> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                        </center>
                                        </fieldset>
                                        <center> 
                                            <a href="print_speciem_rejection.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>
                                        </center> 

                                        <br/>
                                        <?php
                                        include("./includes/footer.php");
                                        ?>


                                        <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
                                        <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
                                        <script src="media/js/jquery.js"></script>
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

                                           function filterLabpatient(){
                                              var fromDate =document.getElementById('date_From').value;// $('#date_From').val();
                                              var toDate = document.getElementById('date_To').value;//$('#date_To').val();
                                              var Sponsor = document.getElementById('sponsorID').value;


                                                if (fromDate == '' || toDate == '') {
                                                    alert('Please enter both dates to filter');
                                                    exit;
                                                }

                                                $('#printPreview').attr('href', 'print_speciem_rejection.php?fromDate=' + fromDate + '&toDate=' + toDate+ '&Sponsor=' + Sponsor);

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'speciem_rejection_iframe.php',
                                                    data: 'action=getItem&fromDate=' + fromDate + '&toDate=' + toDate+ '&Sponsor=' + Sponsor,
                                                    beforeSend: function (xhr) {
                                                        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                                                    },
                                                    success: function (html) {
                                                        if (html != '') {
                                                            $('#Search_Iframe').html(html);
                                                        }
                                                    }

                                                });

                                           }


                                        </script>

                                        <script>
                                            $(document).ready(function () {
                                                $('#numberTests').dataTable({
                                                    "bJQueryUI": true
                                                });
                                            });
                                        </script>

