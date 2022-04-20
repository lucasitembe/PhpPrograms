<?php
include("./includes/header.php");
include("./includes/connection.php");


?>
<a href='engineering_works.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
            BACK
        </a>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<br/><br/>
<center>

    <fieldset style="background-color:white">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>ENGINEERING DEPARTMENT REPORTS</b></legend> 
        <center>
            
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
            <?php 
            // $satisfy = msyqli_query($conn,"SELECT satisfy FROM tbl_engineering_requisition");
            // $satisfaction = '';
            // if ($satisfy = '5')
            // {
            //     $satisfaction = 'Excellent Service';
            // }elseif($satisfy = '4')
            // {
            //     $satisfaction = 'Above Expectation';
            // }elseif($satisfy = '3')
            // {
            //     $satisfaction = 'Met Expectation';
            // }elseif($satisfy = '2')
            // {
            //     $satisfaction = 'Below Expectation';
            // }else
            // {
            //     $satisfaction = 'Poor Service';
            // }
            // ?>

                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>&nbsp;
                        <select name='Employee_ID' id='Employee_ID' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
                            <option value="All">All Engineers</option>
                            <?php
                            $qr = "SELECT * FROM tbl_employee WHERE employee_type='Engineer'";
                            $employee_results = mysqli_query($conn,$qr);
                            while ($employee_rows = mysqli_fetch_assoc($employee_results)) {
                                ?>
                                <option value='<?php echo $employee_rows['Employee_ID']; ?>'><?php echo $employee_rows['Employee_Name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <select id='Department_ID' name='Branch_ID'  onchange="filterPatient()">
                            <option value="All">ALL</option>
                            <?php
                            $select_branch = "SELECT * FROM tbl_branches";
                            $result = mysqli_query($conn,$select_branch);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <option value='<?php echo $row['Branch_ID']; ?>'><?php echo strtoupper($row['Branch_Name']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <select name='satisfy_id' id='satisfy_id' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
                            <option value="All">All Scores</option>
                            <?php
                            $sqr = "SELECT * FROM tbl_satisfaction";
                            $reason_results = mysqli_query($conn,$sqr);
                            while ($reason_rows = mysqli_fetch_assoc($reason_results)) {
                                ?>
                                <option value='<?php echo $reason_rows['satisfy_ID']; ?>'><?php echo $reason_rows['satisfaction']; ?></option>
                                <?php
                            }
                            ?>
                        <!-- <input type='text' name='Search_Patient' style='text-align: center;width:21%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'> -->
                        <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                        <a href="engineeringreportprint.php" id='print_preview' class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
                    </td>
                </tr>
            </table>
        </center>

        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                            <?php include 'engineering_report_iframe.php'; ?>
                        </div>
                    </td>
                </tr>
            </table>
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
            var ward = document.getElementById('Employee_ID').value;
            var Gender = document.getElementById('satisfy_id').value;
            var Branch_ID = document.getElementById('Branch_ID').value;
             
            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }

            $('#print_preview').attr('href', 'dischargepatientreportprint.php?Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&Sponsor=' + Sponsor+ '&Discharge_Reason_ID='+Discharge_Reason +'&row_num='+row_num);

            document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


            $.ajax({
                type: 'GET',
                url: 'engineering_report_iframe.php',
                data: '&Requisition_ID=' + Requisition_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Employee_ID=' + Employee_ID + '&satisfy_id=' + satisfy_id,
                cache: false,
                beforeSend: function (xhr) {
                    // $("#progressStatus").show();
                },
                success: function (html) {
                    if (html != '') {
                        $("#Search_Iframe").html(html);

                        $.fn.dataTableExt.sErrMode = 'throw';
                        $('#patients-list').DataTable({
                            "bJQueryUI": true

                        });
                    }
                }, error: function (html) {

                }
            });

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