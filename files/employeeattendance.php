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
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
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
                    
                    <select id="chkinstatus" style='text-align: center;width:9%;display:inline'>
                        <option value='chkin'>Check In</option>
                        <option value='nochkin'>Not Check In</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPerson('noth')">
                    <a href="attendancepreview.php" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" target="_blank">PREVIEW</a>
                    <input type='text' name='Search_Employee' style='text-align: center;width:20%;display:inline' id='Search_Employee' oninput="filterPerson('name')" placeholder='~~~~~Search Patient Name~~~~~'>
                    <input type='text' name='Search_Employee_Number' style='text-align: center;width:17%;display:inline' id='Search_Employee_Number' oninput="filterPerson('number')" placeholder='~~~Search Pat. Number~~~'>
                </td>
            </tr>

        </table>
    </fieldset>  
</center>
<br/>
<fieldset>  
    <legend align=center ><b id="dateRange">EMPLOYEE ATTENDANCE LIST</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'employeeattendance_Iframe.php'; ?>
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
    function filterPerson($from) {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Search_Employee = document.getElementById('Search_Employee').value;
        var Search_Employee_Number = document.getElementById('Search_Employee_Number').value;
        var chkinstatus = document.getElementById('chkinstatus').value;

        if ($from == 'name') {
            document.getElementById('Search_Employee_Number').value = '';
        } else if ($from == 'number') {//
            document.getElementById('Search_Employee').value = '';
        } else if ($from == 'noth') {
            document.getElementById('Search_Employee_Number').value = '';
            document.getElementById('Search_Employee').value = '';
        }

        if (Date_From != '' && Date_To != '') {
            document.getElementById('dateRange').innerHTML = "EMPLOYEE ATTENDANCE LIST FROM<span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        var datastring = 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Search_Employee=' + Search_Employee + '&Search_Employee_Number=' + Search_Employee_Number + '&chkinstatus=' + chkinstatus ;

        $('#querystring').val(datastring);
         $('#Preview').attr('href', 'attendancepreview.php?'+datastring);


        $.ajax({
            type: "GET",
            url: "employeeattendance_Iframe.php",
            data: datastring,
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#myEmployee').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myEmployee').DataTable({
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