<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//added on 14/02/2019 @MfoyDN
$SubDepWardID = $_SESSION['Admission_Sub_Department_ID'];
$check_sub_department_ward = mysqli_query($conn,"SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$SubDepWardID'");
    if (mysqli_num_rows($check_sub_department_ward)>0) {
        $data = mysqli_fetch_assoc($check_sub_department_ward);
        $WardID = $data['ward_id'];
    }
//end on 14/02/2019 @MfoyDN
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>

        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
        <button class="art-button-green" style='' onclick='orderedItem()'>ORDERED ITEMS</button>
        <a href='searchpatientinwarddischaged.php' class='art-button-green'>
            DISCHARGED PATIENTS
        </a>
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
        Search_Patient_by_number = document.getElementById('Search_Patient_by_number').value;
        document.getElementById('Search_Iframe').innerHTML =
                "<iframe width='100%' height=380px src='searchpartientward.php?Search_Patient=" + Search_Patient + "&ward_id=" + ward_id + "&Search_Patient_by_number="+Search_Patient_by_number+"'></iframe>";
    }
</script>
<br/><br/>
<fieldset>
    <center>
        <table width='100%'>
            <tr>
                <td> <center>   
                    <input type="text" autocomplete="off" class="form-control"style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" class="form-control" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:20%;display:inline'>
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
                    <select width="20%"  name='Ward_id' style='text-align: center;width:20%;display:inline' onchange="filterPatient()" id="Ward_id">
                        <!--<option value="All"> All Ward</option>-->
                         <?php
                        $SubDepWardID = $_SESSION['Admission_Sub_Department_ID'];
                        $check_sub_department_ward = mysqli_query($conn,"SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$SubDepWardID'");
                            if (mysqli_num_rows($check_sub_department_ward)>0) {
                                $data = mysqli_fetch_assoc($check_sub_department_ward);
                                $WardID = $data['ward_id'];
                            }
                        
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID') AND ward_status='active')");
                        while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                            $ward_id=$Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                            if($WardID==$ward_id){$selected="selected='selected'";}else{$selected="";}
                            ?>
                            <option value="<?php echo $ward_id?>" <?= $selected ?>><?php echo $Hospital_Ward_Name?></option>
                        <?php }
                    ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
            </center>
                </td>
               

            </tr>
            <tr>
                <td colspan="5">
                    <center>
                        <table style="width:50%">
                            <tr>
                                <td>
                                    <input type='text' name='Search_Patient'class="form-control" style='text-align: center;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                                </td>
                                <td>
                                    <input type='text' name='Search_Patient_by_number' class="form-control" style='text-align: center;display:inline' id='Search_Patient_by_number' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Number~~~~~~~'>  
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>

        </table>
    </center>
</fieldset>
<br>
<fieldset>  

    <!--<legend align=center><b id="dateRange">LIST OF ADMITTED PATIENTS TODAY <span class='dates'><?php //echo date('Y-m-d')            ?></span></b></legend>-->
    <legend align=center><b id="dateRange">LIST OF ADMITTED PATIENTS</b></legend>

    <center>
        <table width='100%' border='1'>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'searchpartientward.php'; ?>
                    </div>
                   <!--<iframe width='100%' height=380px src='admittedpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<div id="showdataResult" style="width:100%;overflow:hidden;display:none;background-color:white; border:none">
    <div id="myRs">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="text-align: center" >
                    <select onchange="filterOptions(this.value)" id="filterOptions" style="width:100%;padding:5px;margin:5px;font-size:18px;font-weight:100">
                        <option value=''>Filter Options</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="fromyesterday">From yesterday</option>
                        <option value="daterange">Date range</option>
                    </select>
                </td>
                <td id="dateranges" style="text-align: center;display: none">
                    <input type="text" autocomplete="off" style="text-align: center;width:35%;display:inline" id="start_date_op" placeholder="Start Date"/><input type="text" autocomplete="off" style="text-align: center;width:35%;display:inline" id="end_date_op" placeholder="End Date"/>
                </td>
                <td style='text-align:center'>
                    <select onchange="consultResult(this.value)" id="consType" style="width:22%;padding:5px;margin:5px;font-size:18px;font-weight:100">
                        <option value="">Select consultation type</option>
                        <option >All</option>
                        <option>Pharmacy</option>
                        <option>Laboratory</option>
                        <option>Radiology</option>
                        <option>Procedure</option>
                    </select>
                    <select  id="patientLocation" style="width:22%;padding:5px;margin:5px;font-size:18px;font-weight:100">
                        <option value=''>Select location</option>
                        <option >All</option>
                    </select>
                    <select  id="groupBy" style="width:23%;padding:5px;margin:5px;font-size:18px;font-weight:100">
                        <option value=''>Group by all</option>
                        <option value="Registration_ID">Patient name</option>
                    </select>
                    <select  name='ward_ordered_id' style="width:25%;padding:5px;margin:5px;font-size:18px;font-weight:100"  id="ward_ordered_id">
                        <option value="">All Ward</option>
                        <?php
                        $Select_Ward = mysqli_query($conn, "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE ward_status='active'");
                        while ($Ward_Row = mysqli_fetch_array($Select_Ward)) {
                            $ward_id = $Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                            ?>
                            <option value="<?php echo $ward_id ?>"><?php echo $Hospital_Ward_Name ?></option>
                        <?php }
                        ?>
                    </select>
                </td>
                <td style='text-align:center'>
                    <input type="button" value="GET PATIENTS" class="art-button-green" onclick="getPatient()">
                    <a href="#" id="previewList" target="_blank"><input type="button" value="PREVIEW" class="art-button-green" onclick="prevPatient()"></a>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white;"></div>
                    <div id="Dialog_Search_Iframe" style="width:100%;text-align:center;overflow-x:hidden;height:400px;overflow-y:scroll">
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<br/>
<script>
    $(document).ready(function() { filterPatient(); });
</script>
<script>
    function filterPatient() {

        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Search_Patient_by_number = document.getElementById('Search_Patient_by_number').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var ward = document.getElementById('Ward_id').value;


        document.getElementById('dateRange').innerHTML = "LIST OF ADMITTED PATIENTS FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "searchpartientward.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&Sponsor=' + Sponsor + '&ward=' + ward+"&Search_Patient_by_number="+Search_Patient_by_number,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
            },
            success: function (html) {
                if (html != '') {

                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#admittedpatientslist').DataTable({
                        'bJQueryUI': true
                    });
                }
            },
            complete: function (jqXHR, textStatus) {
                $('#progressStatus').hide();
            }
        });
    }
</script>
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
        $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 550, title: 'PATIENT ORDERED ITEMS', modal: true});

        //autocomplete search box;

        $('select').select2();

    });
</script>
<script>
    function consultResult(consultType) {
        $('#previewList').attr('href', '#');
        if (consultType != '') {
            var filterOptions = $('#filterOptions').val();
            $('#patientLocation').select2('val', '');
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
            var Ward_id = $('#ward_ordered_id').val();

            checkIfCanContinue(filterOptions, start_date_op, end_date_op);

            var datastring = 'filterOptions=' + filterOptions + '&start_date_op=' + start_date_op + '&end_date_op=' + end_date_op + '&consultType=' + consultType + '&Ward_id=' + Ward_id;

            $.ajax({
                type: 'GET',
                url: 'requests/wardPatientLocations.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                },
                success: function (result) {
                    if (result != '') {
                        $("#patientLocation").html(result);
                    }
                    $("#patientLocation").html(result);
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
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
        $("#Dialog_Search_Iframe").html('');
        $("#showdataResult").dialog("open");

    }
</script>
<script>
    function filterOptions(option) {
        $('#start_date_op,#end_date_op').val('');
        $('#consType').select2("val", "");
        $('#patientLocation').select2("val", "");

        //$('#patientLocation').html('<option value="">Select location</option><option >All</option>');
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
        var consultType = $('#consType').val();
        var patientLocation = $('#patientLocation').val();
        var groupBy = $('#groupBy').val();
        var Ward_id = $('#ward_ordered_id').val();

        if (filterOptions == '') {
            alert('Select filter option');
            $('#consType,#patientLocation').val('');
            $('#filterOptions').css('border', '1px solid red');
            exit;
        } else {
            $('#filterOptions').css('border', '1px solid #ccc');
        }

        if (consultType == '') {
            alert('Select consultation type');
            $('#consType').css('border', '1px solid red');
            exit;
        } else {
            $('#consType').css('border', '1px solid #ccc')
        }

        if (patientLocation == '') {
            alert('Select consultation location');
            $('#patientLocation').css('border', '1px solid red');
            exit;
        } else {
            $('#patientLocation').css('border', '1px solid #ccc');
        }

        var start_date_op = $('#start_date_op').val();
        var end_date_op = $('#end_date_op').val();

        checkIfCanContinue(filterOptions, start_date_op, end_date_op);

        var datastring = 'filterOptions=' + filterOptions + '&start_date_op=' + start_date_op + '&end_date_op=' + end_date_op + '&consultType=' + consultType + '&patientLocation=' + patientLocation + '&groupBy=' + groupBy + '&Ward_id=' + Ward_id;
        $('#previewList').attr('href', 'wardPatientTestsListPrint.php?' + datastring);
        $('#previewList').children().attr('disabled', false);

        $.ajax({
            type: 'GET',
            url: 'requests/wardPatientTestsList.php',
            data: datastring,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
            },
            success: function (result) {
                $("#Dialog_Search_Iframe").html(result);
            },
            complete: function (jqXHR, textStatus) {
                $('#progressStatus').hide();
            },
            error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>

