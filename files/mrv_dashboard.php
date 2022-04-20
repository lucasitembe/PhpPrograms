<?php
include("./includes/header.php");
include("./includes/connection.php");


?>
 <a href='engineering_reports.php' class='art-button-green'>
            BACK
</a>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
    #Mrv_Iframe{
        width: 100%;
    }
</style> 
<br/>
<center>

    <fieldset style="background-color:white">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>MRV DASHBOARD REPORT</b></legend> 
        <center>
            
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>&nbsp;
                        <select name='Employee_Name' id='Employee_Name' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
                            <option value="All">All Engineers</option>
                            <?php
                            $qr = "SELECT * FROM tbl_employee WHERE employee_type='Engineer'";
                            $employee_results = mysqli_query($conn,$qr);
                            while ($employee_rows = mysqli_fetch_assoc($employee_results)) {
                                ?>
                                <option value='<?php echo $employee_rows['Employee_Name']; ?>'><?php echo $employee_rows['Employee_Name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>

                        <select name='satisfy_id' id='satisfy_id' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
                            <option value='All'>All Scores</option>
                            <?php
                            $sqr = "SELECT * FROM tbl_satisfaction";
                            $reason_results = mysqli_query($conn,$sqr);
                            while ($reason_rows = mysqli_fetch_assoc($reason_results)) {
                                ?>
                                <option value='<?php echo $reason_rows['satisfy_id']; ?>'><?php echo $reason_rows['satisfaction']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="button" value="FILTER" name="filter_works" id="filter_works" onclick="filterPatient()" class="art-button-green"/>
                <!-- <input type="button" value="EXPORT TO EXCEL" onclick="export_mrv_details_Excell()" class="art-button-green"/> -->
                    
                        <a href="#" class="art-button-green" style="font-family:arial" onclick="excel_doc_preview()">EXCEL PREVIEW</a>
                    
                    </td>
                </tr>
            </table>
        </center>
        </fieldset>
        <center>
                        <div id="Search_Iframe" style="height: 500px;overflow-y: auto;overflow-x: scroll important; width: 100% important; z-index: 1;">
                        </div>
        </center>
    </fieldset><br/>


    <script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#start_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#start_date').datetimepicker({value: '', step: 1});
            $('#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:'now'
            });
            $('#end_date').datetimepicker({value: '', step: 1});
        });
    </script>



<script>
        function filterPatient() {
            var Date_From = document.getElementById('start_date').value;
            var Date_To = document.getElementById('end_date').value;
            var Employee_Name = document.getElementById('Employee_Name').value;
            var satisfy_id = document.getElementById('satisfy_id').value;
             
            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }

            document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            $.ajax({
                type: 'GET',
                url: 'mrv_dashboard_iframe.php'.php',
                data: 'Employee_Name=' + Employee_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&satisfy_id=' + satisfy_id,
                cache: false,
                success: function (html) {
                    $('#Search_Iframe').html(html)
                   
                }, error: function (html) {

                }
            });

        }
    </script>




        <script>
            function filterPatient(){
                var Date_From = document.getElementById('start_date').value;
                var Date_To = document.getElementById('end_date').value;
                var Employee_Name = document.getElementById('Employee_Name').value;
                var satisfy_id = document.getElementById('satisfy_id').value;

                if (Date_From == '' || Date_To == '') {
                    alert('Please enter both dates to filter');
                    exit;
                }

                $.ajax({
                    type: 'GET',
                    url: 'mrv_dashboard_iframe.php',
                    data: 'Employee_Name=' + Employee_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&satisfy_id=' + satisfy_id,
                    cache: false,
                    success: function (html) {
                        //alert(Employee_Name + ':' + satisfy_id + Date_From + ' To ' + Date_To);
                        $('#Search_Iframe').html(html);
                    }
                });
            }
        </script>



    <!-- <script>
        function export_mrv_details_Excell(){
       var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       var Employee_Name=$('#Employee_Name').val()
       var satisfy_id=$('#satisfy_id').val()
       if (start_date == '' || end_date == '') {
                alert('Please enter both dates to filter');
                exit; 
            }
       window.open("download_excel_mrv_data.php?start_date="+start_date+"&end_date="+end_date+"&Employee_Name="+Employee_Name+"&satisfy_id="+satisfy_id,"_blank")
    }
        $(document).ready(function(){
            load_mrv_report();
        });
    </script> -->

    <script>
        function excel_doc_preview(){
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var Employee_Name=$('#Employee_Name').val();
            var satisfy_id=$('#satisfy_id').val();

            // check dates 
            if(start_date == ""){
                alert("Enter Start Date");
                exit;
            }else if(end_date == ""){
                alert("Enter End Date");
                exit;
            }else{
                window.open("new_mrv_excel_preview_report.php?start_date="+start_date+"&end_date="+end_date + "&Employee_Name=" + Employee_Name + "&satisfy_id=" + satisfy_id);
            }
        }
    </script>

    <?php
    include("./includes/footer.php");
    ?>

    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>