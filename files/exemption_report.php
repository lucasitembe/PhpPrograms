<?php
include("includes/header.php");
include("includes/connection.php");

    $consultation = 0;
    $investigation = 0;
    $overal_total_investigation = 0;
    $pharamcy = 0;
    $total = 0;
    $female = 0;
    $male = 0;
    $overal_total_pharmacy=0;
    $total_consultaion = 0;
    $total_bill = 0;
    $overal_total_consultation = 0;

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
    $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
        $today_start=$start_dt_row['cast(current_date() as datetime)'];
    }

    if(isset($_GET['from']) && $_GET['from'] == "exemption") {
        echo "<a href='ExemptionListtoDHS.php' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'>BACK</a>";
    }
?>


<br/>
<br/>

<style>
    table{
        width:100%;
        text-align:center;
        border:none;
        border-collapse:collapse;
    }
    table.tr,td{
        border:none;
        border-collapse:collapse;

    }
   
   


    #report td{
        text-align:center;
        
    }
    th{
        font-size: 10px;
    }
    #report_cover{
        height:500px;
    }
    #cover_report{
        background:#fff;
        height:450px;
        overflow-y:scroll;
        overflow-x:hidden;
    }
    #report td{
        width:21%;
    }
    #inside_report{
        width:100%;
        
    }
</style>
<center>

<table >

<td><input class="input" type="text" value="<?= $today_start ?>" class="form-control" id="start_date" autocomplete="off"></td>
<td><input class="input" type="text" value="<?= $Today ?>" class="form-control" id="end_date" autocomplete="off"></td>

<td>
    <select name="" id='msamaha_Items' class="form-control">
        <option value="">~~~~ Chagua aina ya msamaha ~~~~</option>
    <?php

     $selectmsamaha = mysqli_query($conn,"SELECT msamaha_aina, msamaha_Items from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
        if(mysqli_num_rows($selectmsamaha)>0){
            while($rdt = mysqli_fetch_assoc($selectmsamaha)){
                $msamaha_aina = $rdt['msamaha_aina'];
                $msamaha_Items = $rdt['msamaha_Items'];
                echo "<option value='$msamaha_Items'>$msamaha_aina</option>";
            }
        }
    ?>
    </select>
</td>
<td>
    <input type="button" value="FILTERR" class='art-button-green' onclick="filter_msamaha_report()">
    
</td>
    

</table>
</center>
<br />
<fieldset id="report_cover">
        <legend align='center'>Wagonjwa Waliopata Msamaha</legend>
<div id="cover_report">
<?php

echo "<table id='inside_report'>";
echo "<tr style='background:#ccc'>
            <th rowspan='2'>Kundi La msamaha</th>
            <th colspan='3'>Idadi</th>
            <th colspan='14'>Thamani (Shilingi)</th>
        </tr>
        <tr>
            <th width='4%'>Me</th>
            <th width='4%'>Ke</th>
            <th width='4%'>Jumla</th>
            <th width='7%'>Usajiri</th>
            <th width='7%'>Maabara</th>
            <th width='7%'>Radiolojia</th>
            <th width='7%'>Dawa</th>
            <th width='7%'>Procedures</th>
            <th width='7%'>Upasuaji</th>
            <th width='7%'>Kulazwa</th>
            <th width='7%'>Kujifungua</th>            
            <th width='7%'>Huduma Nyingine </th>
            <th width='7%'>Jumla</th>
        </tr><tbody id='msamahareportbody'>";
       
        echo "<tr><th>Jumla</th></tr>";
echo "</tbody></table>";
        // <th width='10%'>Huduma za Utengamao (Rehabilitative)</th>
        //     <th width='5%'>Gari la Wagonjwa (Ambulance)</th>
        //     <th width='5%'>Fomu (PF3 , Medical Examination etc)</th>
        //     <th width='5%'>Chumba cha Maiti (Mortuary)</th>
?>
</div>
</fieldset>


<script src="css/jquery.datetimepicker.js"></script>
<script>

// $("#select").select2()
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>

<script>
    $("#exemption").change(function(){
        $("#cover_report").load("discount_report.php");
    })
    $(document).ready(function(){
        filter_msamaha_report();
    })

    function filter_msamaha_report(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var msamaha_Items = $("#msamaha_Items").val();
        $('#msamahareportbody').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader-focus.gif" width="" style="border-color:white "></div>');
       
        $.ajax({
            method:"POST",
            url:"exemption_report_filter.php",
            data:{start_date:start_date,end_date:end_date,msamaha_Items:msamaha_Items, msamahareport:''},
            success:function(data){
                $("#msamahareportbody").html(data);
            }
        })
    }

    $("#filter_report").click(function(e){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var clinic_id = $(".clinic_id").val();
        var exemption = $("#exemption").val();


        var data = {
            start_date:start_date,
            end_date:end_date,
            clinic_id:clinic_id,
        }
        $('#cover_report').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader-focus.gif" width="" style="border-color:white "></div>');
       
            $.ajax({
            method:"GET",
            url:"msamaha_report_filter.php",
            data:data,
            success:function(data){
                console.log(data)
                $("#cover_report").html("");
                $("#cover_report").html(data);
            }
        })
        

        
    })
</script>