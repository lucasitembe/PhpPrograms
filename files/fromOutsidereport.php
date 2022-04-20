<?php
include("./includes/header.php");
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";

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
if ($section == 'Admission') {
    echo "<a href='admissionworkspage.php?section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
            </a>
            <a href='admissionreports.php?section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION REPORTS
            </a>
            ";
}

echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>BACK
				  </a>";
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

    <fieldset style="background-color:white;">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS' OWN MEDICINE REPORT</b></legend> 
        <center>
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>&nbsp;
                        <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
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

<!--                        <select width="20%"  name='Ward_id' style='text-align: center;width:10%;display:inline' onchange="filterPatient()" id="Ward_id">
                            <option value="All">All Ward</option>
                            <?php
                            $Select_Ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward");
                            while ($Ward_Row = mysqli_fetch_array($Select_Ward)) {
                                $ward_id = $Ward_Row['Hospital_Ward_ID'];
                                $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                                ?>
                                <option value="<?php echo $ward_id ?>"><?php echo $Hospital_Ward_Name ?></option>
                            <?php }
                            ?>
                        </select>-->
                        <input type="button" value="Filter" id="Filter_values" class="art-button-green">
                        <a href="dischargepatientreportprint.php" id='print_preview' class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
                    </td>
                </tr>
            </table>
        </center>

        <center>
            <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden;">
                <?php include 'fromOutsidereport_iframe.php'; ?>
            </div>
        </center>
    </fieldset><br/>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });


            $('#nurse_medicine').DataTable({
                'bJQueryUI': true
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
            var Sponsor = document.getElementById("Sponsor_ID").value;

            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }

            $('#print_preview').attr('href', 'dischargepatientreportprint.php?Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&Sponsor=' + Sponsor);

            document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


            $.ajax({
                type: 'GET',
                url: 'dischargepatientreport_iframe.php',
                data: 'Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&Sponsor=' + Sponsor,
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
    
    <script>
        $('#Filter_values').on('click',function(){
            var start_date=$('#start_date').val();
            var end_date=$('#end_date').val();
            var Sponsor_ID=$('#Sponsor_ID').val();
            // var Ward_id=$('#Ward_id').val();

            document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
           $.ajax({
                type: "POST",
                url: "fromOutsidereport_iframe.php",
                data: 'action=filterData&start_date='+start_date+'&end_date=' + end_date+'&Sponsor_ID='+Sponsor_ID,
                success: function (html) {
                 $('#Search_Iframe').html(html);
                }
            });
        });
        
        $('#print_preview').on('click',function(e){
            e.preventDefault();
           var start_date=$('#start_date').val();
            var end_date=$('#end_date').val();
            var Sponsor_ID=$('#Sponsor_ID').val();
            window.open('fromOutsidereport_iframe_Print.php?action&start_date=' + start_date + '&end_date=' + end_date+'&Sponsor_ID='+Sponsor_ID+'');
            
        });
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