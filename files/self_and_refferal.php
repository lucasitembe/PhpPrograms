<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<style>
    .region_name_cls:hover{
        background: #DEDEDE;
        cursor: pointer;
        color: #0088CC;
        font-size: 14px;
        font-weight: bold;
    }
    .region_name_cls:active{
        background: #CCCCCC;
        cursor: pointer;
        color: #0088CC;
        font-size: 14px;
        font-weight: normal; 
    }
</style>
<a href='governmentReports.php' class='art-button-green'>
    BACK
</a>
<fieldset>
    <legend align="center"><b>SELF & REFERRAL REPORT</b></legend>
    <center>
        <table class="">
            <tr>
                <td>Start Date</td>
                <td><input type="text" placeholder="Start Date" readonly='readonly' id='start_date' style='text-align:center'/></td>
                <td>End Date</td>
                <td><input type="text" placeholder="End Date" readonly='readonly'id='end_date' style='text-align:center'/></td>
                <td>
                    <input type="button" value="FILTER" onclick="filter_report()" class="art-button-green"/>
                </td>
                <td>
                    <input type="button" value="PDF PREVIEW" onclick="pdf_filter_report()" class="art-button-green"/>
                </td> 
                <!-- <td>
                    <input type="button" value="EXCEL PREVIEW" class="art-button-green"/>
                </td> -->
            </tr>
        </table>
    </center>
    <div style="height:500px;overflow-y: scroll;overflow-x: hidden">
    <table class="table" style="text-align: center;background: #FFFFFF">
        <tr>
            <td colspan="2"></td>
            <td style="text-align: center" colspan='3'><b>REFERRAL</b></td>
            <td style="text-align: center" colspan='3'><b>SELF REFERRAL</b></td>
            <td style="text-align: center" colspan='3'><b>EMERGENCY</b></td>
            <td style="text-align: center" colspan='3'><b>ROUTINE</b></td>
            <td style="text-align: center" colspan='3'><b>START</b></td>
            <td style="text-align: center" colspan='3'><b>TOTAL</b></td>
        </tr>
        <tr>
            <td rowspan="2" width="50px"><b>S/No.</b></td>
            <td rowspan="2" style="text-align: center"><b>REGIONS</b></td>
            <td colspan="15" style="text-align: center"><b>CATCHMENT AREA</b></td>
        </tr>
        <tr>
            <td style="text-align: center"><b>M</b></td>
            <td style="text-align: center"><b>F</b></td>
            <td style="text-align: center"><b>T</b></td>
            
            <td style="text-align: center"><b>M</b></td>
            <td style="text-align: center"><b>F</b></td>
            <td style="text-align: center"><b>T</b></td>
            
            <td style="text-align: center"><b>M</b></td>
            <td style="text-align: center"><b>F</b></td>
            <td style="text-align: center"><b>T</b></td>
            
            <td style="text-align: center"><b>M</b></td>
            <td style="text-align: center"><b>F</b></td>
            <td style="text-align: center"><b>T</b></td>
            
            <td style="text-align: center"><b>M</b></td>
            <td style="text-align: center"><b>F</b></td>
            <td style="text-align: center"><b>T</b></td>
        </tr>
        <tbody id="self_and_refl_rpt_body">
            
        </tbody>
    </table>
    </div>
</fieldset>
<div id="selected_region">
    <div class="row" id="header"></div>
    <div class="row" id="data"></div>
</div>
<?php
include("./includes/footer.php");
?>
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
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>

function open_selected_region_details(Region_ID,Region_Name){
       var Region_Name_up=Region_Name.toUpperCase();
       var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
       $.ajax({
            type:'POST',
            url:'self_refferal_director.php',
            data:{
                Region_ID:Region_ID, 
                Region_Name:Region_Name,
                start_date:start_date,
                end_date:end_date
           },
           success:function(dataResult){
                    $("#selected_region").dialog({
                        title: Region_Name_up+' :- REGION DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    });
                    $("#header").html(dataResult);
           }
       });
   
    }



    function open_selected_region_details_iframe(Region_Name,Vid){
       var Region_Name_up=Region_Name.toUpperCase();
       var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
       $.ajax({
            type:'POST',
            url:'self_refferal_iframe.php',
            data:{ 
                Region_Name:Region_Name,
                start_date:start_date,
                end_date:end_date,
                Vid:Vid
           },
           success:function(dataResult){
               
                    $("#data").html(dataResult);
           }
       });
   
    }

    function filter_report(){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        if(start_date==""){
            $("#start_date").css("border","2px solid red");
            exit;
        }else{
           $("#start_date").css("border",""); 
        }
        if(end_date==""){
            $("#end_date").css("border","2px solid red");
            exit;
        }else{
           $("#end_date").css("border",""); 
        }
        document.getElementById('self_and_refl_rpt_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_filter_self_and_referral_report.php',
            data:{start_date:start_date,end_date:end_date},
            success:function(data){
                $("#self_and_refl_rpt_body").html(data);
            }
        });
    }

    function pdf_filter_report(){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        if(start_date==""){
            $("#start_date").css("border","2px solid red");
            exit;
        }else{
           $("#start_date").css("border",""); 
        }
        if(end_date==""){
            $("#end_date").css("border","2px solid red");
            exit;
        }else{
           $("#end_date").css("border",""); 
        }
        window.open("self_and_refferal_pdf.php?start_date="+start_date+"&end_date="+end_date+ "");
    }
</script>
