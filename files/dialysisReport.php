<?php
    include("./includes/header.php");
    include("includes/connection.php");
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Filter_Value.' 23:59';
?>
<a href="dialysisworkspage.php" class="art-button-green">BACK</a>
<fieldset>
        <legend align=center><b>DIALYSIS REPORT</b></legend>
        <center>
            <table>
                <tr>
                    <td><input type='text' id='Date_From' style="text-align: center" value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                    <td><input type='text' id="Date_To" style="text-align: center"value="<?= $End_Date ?>" readonly="readonly" placeholder="End Date"/></td>
                    <td>
                        <input type="button" value="FILTER" class="art-button-green" onclick="filter_collected_specimen_patient()">
                    </td>
                </tr>
            </table>
        </center>
        <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
            <table class='table'>
                <tr>
                    <td><b>S/No.</b></td>
                    <td><b>Number Of Patient</b></td>
                    <td><b>Number Of Services</b></td>
                    <td><b>Sponsor</b></td>
                    <td><b>New Patient</b></td>
                    <td><b>Returned Patient</b></td>
                </tr>
                <tbody id='list_of_patient_arleady_collected_specimen_body'>

                </tbody>
           </table>
        </div>
</fieldset>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script>
<script>
    function filter_collected_specimen_patient(){
       var Date_From= $('#Date_From').val();
       var Date_To= $('#Date_To').val();

        document.getElementById('list_of_patient_arleady_collected_specimen_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_dialysis_report.php',
            data:{filterAttendaceData:"",Date_From:Date_From,Date_To:Date_To},
            success:function(data){
                $("#list_of_patient_arleady_collected_specimen_body").html(data);
            }
        });
    }

    filter_collected_specimen_patient();
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
//
</script>
