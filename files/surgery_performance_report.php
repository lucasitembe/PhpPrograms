<?php
include("./includes/functions.php");
include("./includes/header.php");

@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
// if (isset($_SESSION['userinfo'])) {
//     if (isset($_SESSION['userinfo']['Management_Works']) || isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])  || isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
//         if ($_SESSION['userinfo']['Management_Works'] == 'yes' || $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] =='yes' || $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] =='yes') {
//         }else{
//             header("Location: ./index.php?InvalidPrivilege=yes");
//         }
//     } else {
//         header("Location: ./index.php?InvalidPrivilege=yes");
//     }
// } else {
//     @session_destroy();
//     header("Location: ../index.php?InvalidPrivilege=yes");
// }

$DateGiven = date('Y-m-d');
$filter = '';
$options = '';

if (isset($_GET['loc']) && ($_GET['loc'] == 'doctor' || $_GET['loc'] == 'doctorinp')) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $filter = " AND Employee_ID=$Employee_ID";
} else {
    $options = '<option value="All">All Doctors</option>';
}

echo ' <a href="surgery_performance_report_print.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>';
if (isset($_GET['loc']) && $_GET['loc'] == 'doctor') {
    echo '<a href="doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage" class="art-button-green">BACK</a>';
} elseif (isset($_GET['loc']) && $_GET['loc'] == 'doctorinp') {
    echo '<a href="doctorsinpatientworkspage.php?DoctorsInpatient=DoctorsInpatientThisPage" class="art-button-green">BACK</a>';
} elseif (isset($_GET['loc']) && $_GET['loc'] == 'mangnt') {
    echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage" class="art-button-green">BACK</a>';
} elseif (isset($_GET['ItemsConfiguration']) && $_GET['ItemsConfiguration'] == 'ItemConfigurationThisPage'&&isset($_GET['theater']) && $_GET['theater'] == 'yes') {
    echo '<a href="theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage" class="art-button-green">BACK</a>';
} else {
    echo '<a href="index.php?Bashboard=BashboardThisPage" class="art-button-green">BACK</a>';
}
?>

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

                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT po.Employee_ID, em.Employee_Name FROM tbl_employee em, tbl_post_operative_notes po WHERE em.Employee_ID = po.Employee_ID GROUP BY po.Employee_ID") or die(mysqli_error($conn));

                        echo $options;
                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id='Item_ID' class="select2-default" style='text-align: center;width:17%;display:inline'>

                        <?php
                        $select_surgery = mysqli_query($conn,"SELECT ilc.Item_ID, i.Product_Name FROM tbl_items i, tbl_item_list_cache ilc WHERE ilc.Check_In_Type='Surgery' AND i.Item_ID = ilc.Item_ID AND ilc.Status='served' GROUP BY ilc.Item_ID") or die(mysqli_error($conn));

                            echo "<option value='All'>All Surgery</option>";
                        while ($data = mysqli_fetch_array($select_surgery)) {
                            ?>
                            <option value="<?php echo $data['Item_ID']; ?>"><?php echo ucwords(strtolower($data['Product_Name'])); ?></option>
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
    <legend align='center' style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>SURGERY PERFORMANCE REPORT</b></legend>
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
                        function filterLabpatient() {
                            var fromDate = document.getElementById('Date_From').value;// $('#date_From').val(); 
                            var toDate = document.getElementById('Date_To').value;
                            var employee_id = document.getElementById('employee_id').value;
                            var Item_ID = document.getElementById('Item_ID').value;

                            if (fromDate == '' || toDate == '') {
                                alert('Please enter both dates to filter');
                                exit;
                            }

                            $('#printPreview').attr('href', 'surgery_performance_report_print.php?fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id + '&Item_ID=' + Item_ID);

                            $.ajax({
                                type: 'GET',
                                url: 'surgery_performance_report_frame.php',
                                data: 'fromDate=' + fromDate + '&toDate=' + toDate + '&employee_id=' + employee_id + '&Item_ID=' + Item_ID,
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

