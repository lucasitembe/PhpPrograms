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
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient('noth')">
                    <!--input type='text' name='Search_Patient' style='text-align: center;width:15%;display:inline' id='Search_Patient' oninput="filterPatient('name')" placeholder='~~~~~Search Patient Name~~~~~'>
                    <input type='text' name='Search_Patient_number' style='text-align: center;width:15%;display:inline' id='Search_Patient_number' oninput="filterPatient('number')" placeholder='~~~Search Pat. Number~~~'>
                    <input type='text' name='Search_Old_Patient_number' style='text-align: center;width:15%;display:inline' id='Search_Old_Patient_number' oninput="filterPatient('oldnumber')" placeholder='~~~Search Old Pat. Number~~~'-->
                </td>
            </tr>

        </table>
    </fieldset>  
</center>
<br/>
<fieldset>  
    <legend align=center ><b id="dateRange">DISCONTINUED MEDICINES LIST</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'patient_medication_list_iframe.php'; ?>
                    </div>
                    <input type="hidden" value='' id='querystring'/>
                    <!--<iframe width='100%' height=380px src='doctorcurrentpatientlist_Pre_Iframe.php'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<div id='Display_Medicine_Discontinued_Patient_List'>
    

</div>
<?php
include("./includes/footer.php");
?>

<script>
    function filterPatient($from) {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        /* var Patient_Name = document.getElementById('Search_Patient').value;
        var Patient_number = document.getElementById('Search_Patient_number').value;
        var Search_Old_Patient_number = document.getElementById('Search_Old_Patient_number').value;

        if ($from == 'name') {
            document.getElementById('Search_Patient_number').value = '';
            document.getElementById('Search_Old_Patient_number').value = '';
        } else if ($from == 'number') {//
            document.getElementById('Search_Patient').value = '';
            document.getElementById('Search_Old_Patient_number').value = '';
        } else if ($from == 'oldnumber') {//
            document.getElementById('Search_Patient').value = '';
            document.getElementById('Search_Patient_number').value = '';
        } else if ($from == 'noth') {
            document.getElementById('Search_Patient_number').value = '';
            document.getElementById('Search_Patient').value = '';
        }*/

        if (Date_From != '' && Date_To != '') {
            document.getElementById('dateRange').innerHTML = "DISCONTINUED MEDICINES LIST FROM<span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        //var datastring = 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name +  '&Patient_number=' + Patient_number + '&Search_Old_Patient_number=' + Search_Old_Patient_number;

        //$('#printPreview').attr("href","patient_list_report.php?"+ datastring);
        var Search_Date=true;
        $.ajax({
            type: "GET",
            url: "patient_medication_list_iframe.php",
            data: {Search_Date:Search_Date,Date_From:Date_From,Date_To:Date_To},
            beforeSend: function (){
              $('#progressStatus').show();
            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('.changepatientstatus').select2();
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#myPatients').DataTable({
                        'bJQueryUI': true
                    });
                }
            },complete: function(rs){
               $('#progressStatus').hide();
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myPatients').DataTable({
            "bJQueryUI": true

        });
                $("#Display_Medicine_Discontinued_Patient_List").dialog({autoOpen: false, width: '90%', height:600, title: ' DISCONTINUED MEDICINES PATIENTS LIST', modal: true});

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
<script type="text/javascript">
    function preview_list(id){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        $.ajax({
            url:'medicine_discontinued_patient_list.php',
            method:'post',
            data:{list_id:id,Date_From:Date_From,Date_To:Date_To},
            beforeSend: function (){
              $('#progressStatus').show();
            },
            success:function(result){
                $("#Display_Medicine_Discontinued_Patient_List").html(result);
            },complete: function(rs){
               $('#progressStatus').hide();
            }

        });
        $("#Display_Medicine_Discontinued_Patient_List").dialog("open");
    }
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