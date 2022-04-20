<?php
include("./includes/header.php");
include("./includes/connection.php");

$Sub_Department_Name = $_SESSION['Admission'];

$qr = "SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                            $ward_results = mysqli_query($conn,$qr);
                            if(mysqli_num_rows($ward_results)>0){
                                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                                    
                                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";
    
                                }
                                echo " <a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>
                                    BACK
                                </a>";
                            }else{
                                echo " <a href='managementworkspage.php?ManagementWorksPage=ThisPage' class='art-button-green'>
                                    BACK
                                </a>";
                            }

$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time,0,11);
$Date_From = $Filter_Value.' 00:00';
$Date_To = $Current_Date_Time;
?>

<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
    #mrv_dashboard{
        width: 100% important;
        overflow-x: scroll;
        overflow-y: scroll;
    }
</style> 
<br/><br/>
<center>

    <fieldset style="background-color: white; height: 660px;">
        <legend align="center" style="background-color:#006400;color:white;padding:5px; position: sticky;font-size: 16px;"><b>UNPROCESSED SERVICES REPORTS</b></legend> 
        <center>
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">

                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" value='<?php echo $Date_From ?>' placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" value='<?php echo $Date_To ?>'  placeholder="End Date"/>&nbsp;
                        <select name='Service_type' id='Service_type' onchange="filterPatient()" style='text-align: center;width:10%;display:inline'>
                            <option value="All" selected>All Services</option>
                            <option value="Pharmacy">Pharmacy</option>
                            <option value="Procedure">Procedure</option>
                            <option value="Radiology">Radiology</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Surgery">Surgery</option>
                            <option value="Others">Others</option>
                        </select>
                        <select id='Ward_ID' class="select2-default" onchange='filterPatient()' style='text-align: center;width:10%;display:inline'>
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

                        <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                        <input type="button" value="Export Excel" class="art-button-green hide" onclick="excel_doc_preview()">
                    </td>
                </tr>
            </table>
        </center>

        <center>
                        <div id="Search_Iframe" style="height: 560px; overflow-y: auto; overflow-x: auto; width: 100%;">
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
        $(document).ready(function(){
            filterPatient();
	    })
        function filterPatient() {
            var Date_From = document.getElementById('start_date').value;
            var Date_To = document.getElementById('end_date').value;
            var Service_type = document.getElementById('Service_type').value;
            var Ward_ID = $("#Ward_ID").val();
            
            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }


            // $('#print_preview').attr('href', 'kazimbalimbaliprint.php?&Date_From=' + Date_From + '&Date_To=' + Date_To + '&satisfy_id=' + satisfy_id + '&Employee_Name=' + Employee_Name);

            document.getElementById('Search_Iframe').innerHTML = '<div align="center"  id="progressStatus"><img src="images/ajax-loader_1.gif" style="border-color:white "></div>';

            var filter_works = "filter works";
            $.ajax({
            type: 'GET',
                url: 'uprocessed_items_iframe.php',
                data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Service_type=' + Service_type + '&filter_works=' + filter_works + '&Ward_ID=' + Ward_ID,
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
        function excel_doc_preview(){
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var Service_type=$('#Service_type').val();

            // check dates 
            if(start_date == ""){
                alert("Enter Start Date");
                exit;
            }else if(end_date == ""){
                alert("Enter End Date");
                exit;
            }else{
                window.open("download_excel_kazi_mbalimbali_data.php?start_date="+start_date+"&end_date="+end_date + "&Employee_Name=" + Employee_Name + "&satisfy_id=" + satisfy_id);
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