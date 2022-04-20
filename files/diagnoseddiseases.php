<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Mtuha_Reports'])) {
    if ($_SESSION['userinfo']['Mtuha_Reports'] != 'yes' && $_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
//back button
if (isset($_SESSION['userinfo'])) {
    echo "<a href='dhisworkpage.php?DhisWork=DhisWorkThisPage' class='art-button-green'>BACK</a>";
}
?>
<br/><br/>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<center>
    <fieldset>  
    <table width="100%">
        <tr>
            <td>
              <input type="text" autocomplete="off" style='text-align: center;width:13%;display:inline;' id="date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:13%;display:inline;' id="date_To" placeholder="End Date"/>&nbsp;
                <select id="bill_type" style='text-align: center;padding:4px; width:15%;display:inline'>
                    <option>All</option>
                    <option>Outpatient</option>
                    <option>Inpatient</option>
                </select>
                <input type="number" id="start_age" name="start_age" placeholder="Start age" class="numberonly" style='text-align: center;width:10%;display:inline;padding: 4px'/>
                <input type="number" id="end_age" name="end_age" placeholder="End age" class="numberonly" style='text-align: center;width:10%;display:inline;padding: 4px'/>
                <input type='text' style='text-align: center;;width:20%;display:inline' id='Disease_Name' name='Disease_Name' autocomplete='off' oninput='Diagnosed_Diseases_Filtered()' placeholder='Search Disease'>
         
                <input type='button' name='Filter' id='Filter' value='FILTER' title='FILTER' class='art-button-green' onclick='Diagnosed_Diseases_Filtered()'>
          
            </td>
        </tr>

    </table>
        </fieldset>  
</center>
    <br/>
    <fieldset style="width:98%;height:400px;overflow-y: scroll" id='Disease_Details'>
        <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PROVISIONAL & DIAGNOSED DISEASES</b></legend>
        <table width="100%"  style='overflow-y: scroll;height: 40px;background-color:white' border="0" id='Disease_Fieldset'>
            <thead>
                <tr>
                    <th style="background-color:#006400;color:white;" width="5%">SN</th>
                    <th style="background-color:#006400;color:white;">DISEASE NAME</th>
                    <th width="15%"  style='text-align: left; background-color:#006400;color:white;'>DISEASE CODE</th>
                    <th width="15%" style='text-align: left; background-color:#006400;color:white;'>PROVISIONAL QUANTITY</th>
                    <th width="15%" style='text-align: left; background-color:#006400;color:white;'>FINAL QUANTITY</th>
                </tr>
            </thead>
           
        </table>   


    </fieldset>
    <table width="90%">
        <tr>
            <td style="text-align:right " id="showPreview">

            </td>
        </tr>
    </table>

</center>

<script>
    function Diagnosed_Diseases_Filtered() {
        var Date_From = document.getElementById("date_From").value;
        var Date_To = document.getElementById("date_To").value;
        var Disease_Name = document.getElementById("Disease_Name").value;
        var bill_type = document.getElementById("bill_type").value;
        var start_age = document.getElementById("start_age").value;
        var end_age = document.getElementById("end_age").value;
        
      //  alert(bill_type);


        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != '' && start_age != null && start_age != '' && end_age != null && end_age != '') {
            document.getElementById('Disease_Details').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }

            myObject2.onreadystatechange = function () {
                data2 = myObject2.responseText;

                if (myObject2.readyState == 4) {
                    document.getElementById('Disease_Details').innerHTML = data2;

                    document.getElementById('showPreview').innerHTML = '<a href="Diagnosed_Diseases_List_print.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&bill_type=' + bill_type+ '&Disease_Name=' + Disease_Name + '&start_age=' + start_age+ '&end_age=' + end_age+'" class="art-button-green" target="_blank">Preview</a>';


                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#Disease_Fieldset,#viewDiagnosisdd').DataTable({
                        "bJQueryUI": true

                    });
                }
            };
            
            myObject2.open('GET', 'Diagnosed_Diseases_List.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&bill_type=' + bill_type+ '&Disease_Name=' + Disease_Name + '&start_age=' + start_age+ '&end_age=' + end_age, true);
                myObject2.send();
            
        } else {
            if (Date_From == '' || Date_From == null) {
                document.getElementById("date_From").style = 'border: 1px solid red;text-align: center;width:13%;display:inline';
            }
            if (Date_To == '' || Date_To == null) {
                document.getElementById("date_To").style = 'border: 1px solid red;text-align: center;width:13%;display:inline';
            }
              if (start_age == '' || start_age == null) {
                document.getElementById("start_age").style = 'border: 1px solid red;text-align: center;width:10%;display:inline;padding: 4px';
            }
            if (end_age == '' || end_age == null) {
                document.getElementById("end_age").style = 'border: 1px solid red;text-align: center;width:10%;display:inline;padding: 4px';
            }
        }
    }
</script>

<div id="Display_Diagnosed_Disease_Details" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>


<script>
    function open_Dialog(disease_ID, Date_From, Date_To, diagnosis_type,bill_type,start_age,end_age) {
        var name = '';

        if (diagnosis_type == 'provisional_diagnosis') {
            name = 'PROVISIONAL DIAGNOSIS DISEASE DETAILS ';
        } else if (diagnosis_type == 'diagnosis') {
            name = 'DIAGNOSED DISEASE DETAILS ';
        }
        var myObjectGetDetails;

        $("#Display_Diagnosed_Disease_Details").dialog('option', 'title', name);
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function () {
            var data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Diagnosed_Disease_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'diagnoseddiseasesdetails.php?disease_ID=' + disease_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&diagnosis_type=' + diagnosis_type + '&bill_type=' + bill_type+'&start_age='+start_age+'&end_age='+end_age, true);
        myObjectGetDetails.send();
    }
</script>
<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">-->
<!--<script src="script.js"></script>-->
<script src="css/jquery.datetimepicker.js"></script>

<script>
    $(document).ready(function () {
        $("#Display_Diagnosed_Disease_Details").dialog({autoOpen: false, width: '90%', height: 550, title: 'DIAGNOSED DISEASE DETAILS', modal: true});

//      $('.ui-dialog-titlebar-close').click(function(){
//	 Get_Transaction_List();
//      });

    });
</script>

<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
//startDate:	'now'
    });
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
//startDate:'now'
    });
    $('#date_To').datetimepicker({value: '', step: 30});
</script>






<center>
    <!--<table width=82%>
        <tr>
            <td>-->
    <br>
    <span style='color: #037CB0;'><b>Click Provisional diagnosis / Final diagnosis above to view details when needed</b></span>

    <!--</td>
</tr>
</table>-->
</center>
<script>
$(document).ready(function() {
    $(".numberonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>


<?php
include("./includes/footer.php");
?>
