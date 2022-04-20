<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
        if ($_SESSION['userinfo']['Laboratory_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        ?>

        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        ?>
        <button class="art-button-green" style='' onclick='orderedItem()'>ORDERED ITEMS</button>
        <a href='admissionworkspage.php?BacktoAdmission=BacktoAdmission' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = $Today - $original_Date;
}

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}

//Lab subcategory
$query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=I.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));

$sub_category = '<option value="All">All Department</option>';

while ($row = mysqli_fetch_array($query_sub_cat)) {
    $sub_category.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient() {
        ward_id = document.getElementById('ward').value;
        Search_Patient = document.getElementById('Search_Patient').value;
        document.getElementById('Search_Iframe').innerHTML =
                "<iframe width='100%' height=380px src='searchpartientward.php?Search_Patient=" + Search_Patient + "&ward_id=" + ward_id + "'></iframe>";
    }
</script>
<br/><br/>
<fieldset>  

    <!--<legend align=center><b id="dateRange">LIST OF ADMITTED PATIENTS TODAY <span class='dates'><?php //echo date('Y-m-d')         ?></span></b></legend>-->
    <legend align=center><b id="dateRange"> PATIENT REJECTED SAMPLE REPORT</b></legend>

    <div id="showdataResult" style="width:100%;overflow:hidden;background-color:white; border:none">
        <div id="myRs">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="text-align: center" >
                        <select onchange="filterOptions(this.value)" id="filterOptions" style="padding:5px;margin:5px;font-size:18px;font-weight:100">
                            <option value=''>Filter Options</option>
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="fromyesterday">From yesterday</option>
                            <option value="daterange">Date range</option>
                        </select>
                    </td>
                    <td id="dateranges" style="text-align: center;display: none">
                        <input type="text" autocomplete="off" style="text-align: center;width:45%;padding:5px;margin:5px;font-size:18px;font-weight:100" id="start_date_op" placeholder="Start Date"/><input type="text" autocomplete="off" style="text-align: center;width:45%;padding:5px;margin:5px;font-size:18px;font-weight:100" id="end_date_op" placeholder="End Date"/>
                    </td>
                    <td style='text-align:center'>
                        <select id="sponsorID" style='padding:5px;margin:5px;font-size:18px;font-weight:100'>
                            <?php echo $dataSponsor ?>
                        </select>
                        <select id="subcategory_ID" style='padding:5px;margin:5px;font-size:18px;font-weight:100'>
                            <?php echo $sub_category ?>
                        </select>
                        <select  id="groupBy" style="padding:5px;margin:5px;font-size:18px;font-weight:100">
                            <option value=''>Group by all</option>
                            <option value="Registration_ID">Patient name</option>
                        </select>
                    </td>
                    <td style='text-align:center'>
                        <input type="button" value="GET PATIENTS" class="art-button-green" onclick="getPatient()">
                        <a href="#" id="previewList" target="_blank"><input type="button" value="PREVIEW" class="art-button-green" onclick="prevPatient()"></a>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="Dialog_Search_Iframe" style="width:100%;text-align:center;overflow-x:hidden;height:560px;overflow-y:scroll">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</fieldset>
<br/>

<script type="text/javascript">
    $(document).ready(function () {
        $('#admittedpatientslist').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From,#start_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From,#start_date_op').datetimepicker({value: '', step: 30});
        $('#Date_To,#end_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To,#end_date_op').datetimepicker({value: '', step: 30});


    });
</script>
<script>
    function consultResult(consultType) {
        $('#previewList').attr('href', '#');
        if (consultType != '') {
            var filterOptions = $('#filterOptions').val();
            $('#patientLocation').val('')
            if (filterOptions == '') {
                alert('Select filter option');
                $('#consType,#patientLocation').val('');
                $('#filterOptions').css('border', '1px solid red');
                exit;
            } else {
                $('#filterOptions').css('border', '1px solid #ccc');
            }

            var start_date_op = $('#start_date_op').val();
            var end_date_op = $('#end_date_op').val();

            checkIfCanContinue(filterOptions, start_date_op, end_date_op);

            var datastring = 'filterOptions=' + filterOptions + '&start_date_op=' + start_date_op + '&end_date_op=' + end_date_op + '&consultType=' + consultType;

            $.ajax({
                type: 'GET',
                url: 'requests/wardPatientLocations.php',
                data: datastring,
                success: function (result) {
                    if (result != '') {
                        $("#patientLocation").html(result);
                    }
                    $("#patientLocation").html(result);
                }, error: function (err, msg, errorThrows) {
                    alert(err);
                }
            });
        } else {
            $('#patientLocation').html('<option value="">Select location</option><option >All</option>');
        }
    }
</script>
<script>
    function checkIfCanContinue(filterOptions, start_date_op, end_date_op) {
        if (filterOptions == 'daterange') {
            if (start_date_op == '' || end_date_op == '') {
                alert('Please enter date ranges');
                $('#consType,#patientLocation').val('');
                $('#start_date_op,#end_date_op').css('border', '1px solid red');
                exit;
            } else {
                $('#start_date_op,#end_date_op').css('border', '1px solid #ccc');
            }
        }
    }
</script>
<script>
    function orderedItem() {
        $('#start_date_op,#end_date_op').val('');
        $('#previewList').children().attr('disabled', true);
        $("#showdataResult").dialog("open");
    }
</script>
<script>
    function filterOptions(option) {
        $('#start_date_op,#end_date_op').val('');
        $('#consType').val('');
        $('#patientLocation').html('<option value="">Select location</option><option >All</option>');
        if (option == 'daterange') {
            $('#dateranges').show();
        } else {
            $('#dateranges').hide();
        }
    }
</script>
<script>
    function getPatient() {
        var filterOptions = $('#filterOptions').val();
        var groupBy = $('#groupBy').val();
        var sponsorID = $('#sponsorID').val();
        var subcategory_ID = $('#subcategory_ID').val();

        if (filterOptions == '') {
            alert('Select filter option');
            $('#consType,#patientLocation').val('');
            $('#filterOptions').css('border', '1px solid red');
            exit;
        } else {
            $('#filterOptions').css('border', '1px solid #ccc');
        }

        
        var start_date_op = $('#start_date_op').val();
        var end_date_op = $('#end_date_op').val();

        checkIfCanContinue(filterOptions, start_date_op, end_date_op);

        var datastring = 'filterOptions=' + filterOptions + '&start_date_op=' + start_date_op + '&end_date_op=' + end_date_op + '&sponsorID=' + sponsorID + '&subcategory_ID=' + subcategory_ID + '&groupBy=' + groupBy;
        
        $('#previewList').attr('href', 'wardPatientTestsListPrint.php?' + datastring);
        $('#previewList').children().attr('disabled', false);

        $.ajax({
            type: 'GET',
            url: 'requests/patientsamplerejectlist.php',
            data: datastring,
            beforeSend: function (xhr) {
                document.getElementById('Dialog_Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (result) {

                if (result != '') {
                    $("#Dialog_Search_Iframe").html(result);
//                    $.fn.dataTableExt.sErrMode = 'throw';
//                    $('#patientslist').DataTable({
//                        'bJQueryUI': true
//                    });
                }

            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/footer.php");
?>

