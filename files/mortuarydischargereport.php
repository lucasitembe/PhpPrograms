<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Admission_Supervisor'])) {
                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = "Admission";
}
// if ($section == 'Admission') {
//     echo "<a href='admissionworkspage.php?from=mogueReporteee&section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
//             ADMISSION MAIN WORKPAGE
//             </a>
//             <a href='admissionreports.php?from=mogueReporteee&section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
//             ADMISSION REPORTS
//             </a>
//             ";
// }
if(isset($_GET['mortuary'])){
    echo "<a href='mortuary_report.php' class='art-button-green'>BACK</a>";
}else{
echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>BACK</a>";
}
?>
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
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>DISCHARGED BODIES</b></legend> 
        <center>
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>&nbsp;
                        <!--select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
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
                        </select-->
                        <select id='Branch_ID' name='Branch_ID'  onchange="filterPatient()">
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
                        <select id='Gender' name='Gender'  onchange="filterPatient()">
                            <option value="All">ALL</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                        <select id='case_type' name='case_type'  onchange="filterPatient()">
                            <option value="All">All Cases</option>
                            <option value="home">Home Case</option>
                            <option value="hospital">Hospital Case</option>
                            <option value="police">Police Case</option>
                        </select>
                        <select width="20%"  name='Ward_id' style='text-align: center;width:10%;display:inline' onchange="filterPatient()" id="Ward_id">
                            <option value="All">All Ward</option>
                            <?php
                            $Select_Ward_Morgue = mysqli_query($conn, "SELECT Hospital_Ward_ID, Hospital_Ward_Name FROM tbl_hospital_ward WHERE ward_type = 'mortuary_ward' AND ward_status = 'active'") or die(mysqli_error($conn));
                            while ($Ward_Row = mysqli_fetch_array($Select_Ward_Morgue)) {
                                $ward_id = $Ward_Row['Hospital_Ward_ID'];
                                $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                                ?>
                                <option value="<?php echo $ward_id ?>"><?php echo $Hospital_Ward_Name ?></option>
                            <?php 
                            }
                            ?>
                        </select>
                            <select width="20%"  name='row_num' style='text-align: center;width:10%;display:inline' onchange="filterPatient()" id="row_num">
                            <option value="100">100 Rows</option>
                            <option value="250">250 Rows</option>
                            <option value="500">500 Rows</option>
                            <option value="1000">1000 Rows</option>
                            <option value="ALL">All Rows</option>
                        </select>
                        <input type="number" id="start_age" name="start_age" placeholder="Start age" class="numberonly" style='text-align: center;width:10%;display:inline;padding: 4px'/>
                        <input type="number" id="end_age" name="end_age" placeholder="End age" class="numberonly" style='text-align: center;width:10%;display:inline;padding: 4px'/>
                        <select id='diagnosis_time' style='text-align:center;padding:4px; width:5%;display:inline'>
<!--                            <option value='all'>ALL</option>-->
                            <option value='YEAR'>Year</option>
                            <option value='MONTH'>Month</option>
                            <option value='DAY'>Days</option>
                        </select>
                        <input type='text' name='Search_Patient' style='text-align: center;width:10%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~Search Patient Name~~~'>
                        <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                        <a href="dischargedmortuaryreportprintlist.php" id='print_preview' class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
                    </td>
                </tr>
            </table>
        </center>

        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                            <?php include 'dischargemortuaryreport_iframe.php'; ?>
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
            var ward = document.getElementById('Ward_id').value;
            var Gender = document.getElementById('Gender').value;
            var Branch_ID = document.getElementById('Branch_ID').value;
            var Patient_Name = document.getElementById("Search_Patient").value;
            var case_type = document.getElementById('case_type').value;
            var start_age = document.getElementById('start_age').value;
            var end_age = document.getElementById('end_age').value;
            //var Sponsor = document.getElementById("Sponsor_ID").value;
            var row_num = document.getElementById('row_num').value;
            var diagnosis_time = document.getElementById('diagnosis_time').value;
             
            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }

            $('#print_preview').attr('href', 'dischargedmortuaryreportprintlist.php?Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&case_type=' + case_type + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&row_num='+row_num + '&start_age=' + start_age + '&end_age=' + end_age + '&diagnosis_time=' + diagnosis_time);

            document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


            $.ajax({
                type: 'GET',
                url: 'dischargemortuaryreport_iframe.php',
                data: 'Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&case_type=' + case_type + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&row_num=' + row_num + '&start_age=' + start_age + '&end_age=' + end_age + '&diagnosis_time=' + diagnosis_time,
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