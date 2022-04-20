<?php
include("./includes/connection.php");
include("./includes/header.php");
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href="managementworkspage.php" class="art-button-green">BACK</a>
<br/>
<fieldset>
    <legend align='center'><b>Laboratory Machine Integration Report</b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style='text-align:center' id="start_date" value="<?= $Start_Date ?>" placeholder="Start Date"/></td>
                <td><input type="text" style='text-align:center' id="end_date" value="<?= $End_Date ?>"placeholder="End Date"/></td>
                <td><input type="text" style='text-align:center' id="search_item" onkeyup="filter_lab_machine_integration_report()" placeholder="Search . . ."/></td>
                <td><input type="button" value="FILTER" onclick="filter_lab_machine_integration_report()" class="art-button-green"/></td>
                <td><input type="button" value="PREVIEW" onclick="preview_report()" class="art-button-green"/></td>
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <table class='table'>
            <tr>
                <th>S/No.</th>
                <th>Sample ID</th>
                <th>Lab Tech</th>
                <th>No Of Rows</th>
                <th>Observation Date</th>
                <th>Result Date</th>
                <th>Validated</th>
                <th>Sent To Doctor</th>
                <th>Sample Id Source</th>
            </tr>
            <tbody id='lab_machine_intergration_body'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>
<script>
    function filter_lab_machine_integration_report(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       var search_item= $('#search_item').val();
       document.getElementById('lab_machine_intergration_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_lab_machine_integration_report.php',
           data:{start_date:start_date,end_date:end_date,search_item:search_item},
           success:function(data){
               $("#lab_machine_intergration_body").html(data); 
           }
       });
    }
     $(document).ready(function () {
        filter_lab_machine_integration_report();
    })
    function preview_report(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       var search_item= $('#search_item').val();
        window.open("filter_lab_machine_integration_report_pdf.php?start_date="+start_date+"&end_date="+end_date+"&search_item="+search_item,"_blank");
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>