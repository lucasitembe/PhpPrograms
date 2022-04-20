<?php
include("includes/header.php");
include("includes/connection.php");
$consultation = 0;
$investigation = 0;
$pharamcy = 0;
$total = 0;
$female = 0;
$male = 0;

$pharmacy_discount = 0;
$investigation_discount = 0;
if(!isset($_SESSION['userinfo']['Employee_ID'])){
    header("Location:index.php");
}

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $Today = $row['today'];
}
$today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
    $today_start=$start_dt_row['cast(current_date() as datetime)'];
}
?>
<a href="msamahareports.php?MsamahaReports=MsamahaReportsThisForm" class="art-button-green">BACK</a>

<br/>
<br/>

<style>
    table{
        width:100%;
        text-align:center;
        border:none;
        border-collapse:collapse;
    }
    table,tr,td{
        border:none;
        border-collapse:collapse;
        text-align:center;
    }
  
    .rowlist{ 
        cursor: pointer; 
    }
    .rowlist:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rowlist:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }

#report_cover{
    height:550px;
}
#cover_report{
    background:#fff;
    height:490px;
    overflow-y:scroll;
    overflow-x:hidden;
}
#inside_report{
    width:100%;
    
}
#inside_report td{
    padding:6px;
    width:23%;
}
</style>
<center>

<table >
<td><input class="input" type="text" value="<?= $today_start ?>" class="form-control" id="start_date" autocomplete="off"></td>
<td><input class="input" type="text" value="<?= $Today ?>" class="form-control" id="end_date" autocomplete="off"></td>

<td>

</td>
<td>
<!-- <select name="" id="select" class="clinic_id">
        <option value="">SELECT CLINIC</option>
        <?php 
        $result =mysqli_query($conn,"SELECT Clinic_Name,Clinic_ID FROM tbl_Clinic");
        while($row = mysqli_fetch_assoc($result)){
            $clinic_name = $row['Clinic_Name'];
            $clinic_id = $row['Clinic_ID'];
            echo "<option value='$clinic_id'>".$clinic_name."</option>";
        }
        ?>
       
    </select> -->
</td>
<td>
<input type="button" value="FILTER" class='art-button-green' onclick="filter_msamaha_report()">
<input type="button" value="SUMMARY DISCOUNT REPORT" class='art-button-green' onclick="filter_msamaha_discount_report()">
    
</td>
    

</table> 
</center>
<fieldset id="report_cover">
<legend align='center'>DISCOUNT REPORT</legend>
        
</table>

<div id="cover_report">
    <table id="report" style='width:100%;box-sizing:border-box;' class="table table-hover">
    <thead>
        <tr>
            <td style='width:5%;box-sizing:border-box; background:#ccc;'><b>SN</b></td>
            <td style='width:19%;box-sizing:border-box; background:#ccc;'><b>Patient Name</b></td>
            <td style='width:7%;box-sizing:border-box; background:#ccc;'><b>Reg No. #</b></td>
            <td style='width:8%;box-sizing:border-box; background:#ccc;'><b>Gender</b></td>
            <td style='width:8%;box-sizing:border-box; background:#ccc;'><b>Age</b></td>
            <td style='width:7%;box-sizing:border-box; background:#ccc;'><b>Phone Number</b></td>
            <td style='width:8%;box-sizing:border-box; background:#ccc;'><b>Total amount</b></td>
            <td style='width:8%;box-sizing:border-box; background:#ccc;'><b>Discount</b></td>
            <td style='width:10%;box-sizing:border-box; background:#ccc;'><b>Amount Paid</b></td>
            <th style='width:10%;box-sizing:border-box; background:#ccc;'>Employee Name</th>
        </tr>
    </thead>
    <tbody id="discountbody">

    </tbody>
    </table>
</div>
<div id="Discountdialogy"></div>
<div id="Discountsummarydialogy"></div>
<?php


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
    $("#select").change(function(){
        $("#cover_report").html("Report")
    })

    $(document).ready(function(){
        filter_msamaha_report()
    })

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
       
            $.ajax({
            method:"GET",
            url:"exemption_discount_report_filter.php",
            data:data,
            success:function(data){
                console.log(data)
                $("#cover_report").html("");
                $("#cover_report").append(data);
            }
        })
    })
    function view_patent_dialog(Patient_Payment_ID){
        $.ajax({
            method:"POST",
            url:"exemption_discount_report_filter.php",
            data:{Patient_Payment_ID:Patient_Payment_ID, viewitemdiscounted:''},
            success:function(data){
                $("#Discountdialogy").dialog({
                        title: 'ITEM DISCOUNTED ',
                        width: '80%',
                        height: 650,
                        modal: true,
                    });
                $("#Discountdialogy").html(data);
            }
        })
    }

    function filter_msamaha_report(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $.ajax({
            method:"POST",
            url:"exemption_discount_report_filter.php",
            data:{start_date:start_date,end_date:end_date, discountreport:''},
            success:function(data){
                $("#discountbody").html(data);
            }
        })
    }
    function filter_msamaha_discount_report(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        window.open('exemption_discount_report_filter_pdf.php?start_date='+start_date+'&end_date='+end_date+'&Discount=discountpage','_blank');
        // $.ajax({
        //     method:"POST",
        //     url:"exemption_discount_report_filter.php",
        //     data:{start_date:start_date,end_date:end_date, discountsummaryreport:''},
        //     success:function(data){
        //         $("#Discountsummarydialogy").dialog({
        //                 title: 'SUMMARY  DISCOUNT REPORT ',
        //                 width: '80%',
        //                 height: 550,
        //                 modal: true,
        //             });
        //         $("#Discountsummarydialogy").html(data);
        //     }
        // })
    }
</script>