<?php
include("./includes/header.php");
include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
    <a href='./jobcard_menu.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>





<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<br/><br/>
<center>
    <table width="95%">
        <tr>
            <td width="20%">
                    <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Jobcard Created From' id='date_From' style="text-align: center">    
            </td>
            <td width="20%">
                <input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Jobcard Created From' id='date_To' style="text-align: center">
            </td>
            <td width="20%"> 
                <input type='text' name='Employee_ID' title='Incase You want to filter by Engineer Name'  id='Employee_ID' style='text-align: center;' onkeyup='filterJobcard()' placeholder='~~~~~~~Search Engineer Name~~~~~~~~'>
            </td>
            <td width="20%"> 
                <input type='text' name='Jobcard_Number' title='Incase You want to filter by Jobcard Number'  id='Jobcard_Number' style='text-align: center;'  onkeyup='filterJobcard()' placeholder='~~~~~~~~Search Jobcard Number~~~~~~~~~'>
            </td>
                <td style='text-align: center;' width='5%'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterJobcard()' class='art-button-green' value='FILTER'>
                </td>
        </tr>
    </table>
</center>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i> Click Jobcard Details in case you want to View it </i></p></center>
<fieldset>
    <legend align=center>LIST OF APPROVED JOBCARD</legend>
<div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">
            <tr  style='background: #dedede;'>
                <td style="width:2%; text-align: center;"><b><h5>SN</h5></b></td>
                <td style="width:7%;"><b><h5>JOBCARD #</h5></b></td>
                <td><b><h5>JOBCARD DATE</h5></b></td>
                <td><b><h5>JOB TRACKER</h5></b></td>
                <td><b><h5>INITIALIZED BY</h5></b></td>
                <td><b><h5>DEPARTMENT</h5></b></td>
                <td><b><h5>APPROVED BY</h5></b></td>                   
                <td><b><h5>APPROVED AT</h5></b></td>                  
                <td style="width:30%;"><b><h5>APPROVAL COMMENT</h5></b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script language="javascript" type="text/javascript">
        $(document).ready(function(){
            filterJobcard();
	})
    function filterJobcard(){
		Employee_ID = document.getElementById('Employee_ID').value;
		Jobcard_Number = document.getElementById('Jobcard_Number').value;
        date_From = $("#date_From").val();
        date_To = $("#date_To").val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('patient_sent_to_cashier_tbl').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'approved_jobcard_iframe.php?Employee_ID='+Employee_ID+'&Jobcard_Number='+Jobcard_Number+'&date_From='+date_From+'&date_To='+date_To, true);
        myObjectPost.send();
    }
</script>

<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
         $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#number").select2();
    });

    $('#Date_Time').on('click',function(){ 
        var id=$(this).attr('id');
        $('#date_From_val').val(id);
        $('#changeDateDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'Change Surgery Date'
//            close: function (event, ui) {
//               
//            }
        });

    });

</script>

<?php
    include("./includes/footer.php");
?>