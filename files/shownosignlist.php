<style>
    .dates{
        color:#cccc00;
    }
    .changepatientstatus{
        width: 100%;
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
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
?>
<a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
    BACK
</a>
<br/><br/><br/>
<center>
    <fieldset>  
        <table width="100%">
            <tr>
                <td style="text-align:center">
                    <input type="text" autocomplete="off" value='<?php echo (isset($_GET['Date_From']) ? $_GET['Date_From'] : '') ?>' style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" value='<?php echo (isset($_GET['Date_To']) ? $_GET['Date_To'] : '') ?>' style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id='employee_id' class="select2-default" style='text-align: center;width:15%;display:inline'>
                        <option value="0">Select Doctor</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor'  ORDER BY Employee_Name ASC
                                ") or die(mysqli_error($conn));

                        echo $options;
                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            if (isset($_GET['employee_id']) && $_GET['employee_id'] == $data['Employee_ID']) {
                                ?>
                                <option selected value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <select id="patDirection" style='text-align: center;width:12%;display:inline'>
                        <option >Direct To Doctor</option>
                        <option >Direct To Doctor Via Nurse Station</option>
                        <option >Direct To Clinic</option>
                        <option >Direct To Clinic Via Nurse Station</option>
                    </select>
                    <select id="patStatus" style='text-align: center;width:9%;display:inline'>
                        <option value=''>ALL</option>
                        <option value='no show'>No Show</option>
                        <option value='signedoff'>Signed-Off</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient('noth')">
                    <input type='text' name='Search_Patient' style='text-align: center;width:13%;display:inline' id='Search_Patient' oninput="filterPatient('name')" placeholder='~~~~~Search Patient Name~~~~~'>
                    <input type='text' name='Search_Patient_number' style='text-align: center;width:12%;display:inline' id='Search_Patient_number' oninput="filterPatient('number')" placeholder='~~~Search Pat. Number~~~'>
                </td>
            </tr>

        </table>
    </fieldset>  
</center>
<br/>
<fieldset>  
    <legend align=center ><b id="dateRange">PATIENT LIST</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'shownosignlist_Iframe.php'; ?>
                    </div>
                    <input type="hidden" value='' id='querystring'/>
                    <!--<iframe width='100%' height=380px src='doctorcurrentpatientlist_Pre_Iframe.php'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>

<script>
    function filterPatient($from) {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;//
        var employee_id = document.getElementById('employee_id').value;
        var Patient_number = document.getElementById('Search_Patient_number').value;
        var patStatus = document.getElementById('patStatus').value;
        var patDirection = document.getElementById('patDirection').value;

        if ($from == 'name') {
            document.getElementById('Search_Patient_number').value = '';
        } else if ($from == 'number') {//
            document.getElementById('Search_Patient').value = '';
        } else if ($from == 'noth') {
            document.getElementById('Search_Patient_number').value = '';
            document.getElementById('Search_Patient').value = '';
        }

        if (Date_From != '' && Date_To != '') {
            document.getElementById('dateRange').innerHTML = "PATIENT LIST FROM<span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        var datastring = 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&employee_id=' + employee_id + '&Patient_number=' + Patient_number + '&patStatus=' + patStatus + '&patDirection=' + patDirection;

        $('#querystring').val(datastring);

        $.ajax({
            type: "GET",
            url: "shownosignlist_Iframe.php",
            data: datastring,
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('.changepatientstatus').select2();
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#myPatients').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>
<script type='text/javascript'>
    function changestatus(Patient_Payment_ID, status) {
      if(status != 'Change'){  
        $.Zebra_Dialog('<strong>The Patient will be returned to the Doctor!</strong>, <br/>Are you sure you want to continue?', {
            'type': 'warning',
            'title': 'Wait for a sec(s)',
            'width':600,
            'overlay_close': false,
            'buttons': [
                {caption: 'Yes', callback: function () {
                        $.ajax({
                            type: "GET",
                            url: "updatepatientconsultinfo.php",
                            data: 'Patient_Payment_ID=' + Patient_Payment_ID + '&status=' + status,
                            success: function (html) {
                                if (html == 'changed') {
                                    window.location = window.location.href + '&' + $('#querystring').val();
                                    ;
                                } else {
                                    alert(html);
                                }
                            }
                        });
                    }},
                {caption: 'No', callback: function () {
                       $('#'+Patient_Payment_ID).val("Change").trigger('change');
                    }}
            ],
          'onClose':function(){
              $('#'+Patient_Payment_ID).val("Change").trigger('change');
          }
        });
    }
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myPatients').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});

        $('select').select2();

        $('#Search_Patient_number').keydown(function (e) {
            -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || /65|67|86|88/.test(e.keyCode) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
        });
    });

</script>



<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="css/jquery-ui.js"></script>