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
    .rows_list{
    color: #328CAF!important;
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<a href='governmentReports.php' class='art-button-green'>
    BACK
</a>
<fieldset>
    <legend align="center"><b>REFERRAL LETTERS FROM DIFFERENT FACILITIES REPORT</b></legend>
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
                    <input type="button" value="PDF PREVIEW" class="art-button-green"/>
                </td>
                <td>
                    <input type="button" value="EXCEL PREVIEW" class="art-button-green"/>
                </td>
            </tr>
        </table>
    </center>
    <div style="height:500px;overflow-y: scroll;overflow-x: hidden">
    <table class="table" style="text-align: center;background: #FFFFFF">
        <tr>
            <td width="50px">S/No.</td>
            <td style="text-align: center"><b>HEALTH FACILITIES</b></td>
            <td width="200px" style="text-align: center"><b>MALE</b></td>
            <td width="200px" style="text-align: center"><b>FEMALE</b></td>
            <td width="200px" style="text-align: center"><b>TOTAL</b></td>
        </tr>
        <tbody id="self_and_refl_rpt_body">
            
        </tbody>
    </table>
    </div>
</fieldset>
<div id="selected_region"></div>
<div id="refferal_contetes"></div>
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
        $("#selected_region").dialog({
                        title: Region_Name_up+' :- REGION DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
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
            url:'ajax_filter_referral_letters_report.php',
            data:{start_date:start_date,end_date:end_date},
            success:function(data){
                $("#self_and_refl_rpt_body").html(data);
            }
        });
    }
    function open_refferal_male_form(referred_from_hospital_id_for_male,hospital_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        $.ajax({
            type:'POST',
            url:'refferal_report_iframe.php',
            data:{
                referred_from_hospital_id_for_male:referred_from_hospital_id_for_male,
                start_date:start_date,
                end_date:end_date,
                hospital_name:hospital_name,
                },
            success:function(data){
                
                $("#refferal_contetes").dialog({
                        title: 'REFFERAL PATIENTS INFORMATIONS',
                        width: '60%',
                        height: 500,
                        modal: true,
                    });
                    $("#refferal_contetes").html(data);
            }
        });
    }
    function open_refferal_female_form(referred_from_hospital_id_for_female,hospital_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        $.ajax({
            type:'POST',
            url:'refferal_report_iframe.php',
            data:{
                referred_from_hospital_id_for_female:referred_from_hospital_id_for_female,
                start_date:start_date,
                end_date:end_date,
                hospital_name:hospital_name,
                },
            success:function(data){
                
                $("#refferal_contetes").dialog({
                        title: 'REFFERAL PATIENTS INFORMATIONS',
                        width: '70%',
                        height: 500,
                        modal: true,
                    });
                    $("#refferal_contetes").html(data);
            }
        });
    }
</script>
