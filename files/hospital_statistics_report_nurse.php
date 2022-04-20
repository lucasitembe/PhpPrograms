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
    th.rotate {
  /* Something you can count on */
    height: 140px;
}

th.rotate {
  transform: 
    /* Magic Numbers */
    translate(22px, 35px)
    /* 45 is really 360 - 45 */
    rotate(325deg);
}
th.rotate {
  border-bottom: 1px solid #ccc;
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
<link rel='stylesheet' href='fixHeader.css'>

<?php 
    if(isset($_GET['from_admission'])){
      echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports'  class='art-button-green'>BACK</a>";  
    }
    else if(isset($_GET['from_doctors'])){
         echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage'  class='art-button-green'>BACK</a>";  
    }
    else{
?>
<a href='admissionreports.php' class='art-button-green'>
    BACK
</a>
<?php } ?>
<fieldset>
    <legend align="center"><b>HOSPITAL ADMINISTRATIVE STATISTICS REPORT</b></legend>
    <center>
        <table class="">
            <tr>
                <td>Start Date</td>
                <td><input type="text" placeholder="Start Date" readonly='readonly' id='start_date' style='text-align:center'/></td>
                <td>End Date</td>
                <td><input type="text" placeholder="End Date" readonly='readonly'id='end_date' style='text-align:center'/></td>
                <td>
                <b>Age</b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="Start age" class="numberonly" />
                </td>
                <td>
                 <b>Age &ge; </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="End age" class="numberonly"/> 
                </td>
                <td>
                <select id='ipd_time'>
                            <option value=''>Age type</option>
                            <option value='YEAR'>Year</option>
                            <option value='MONTH'>Month</option>
                            <option value='DAY'>Days</option>
                        </select>
                </td>
                <td>
                <b>Gender</b>
                    <select id='gender' name="report_type" onchange="Clear_Fieldset()">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td>
                    <input type="button" value="FILTER" onclick="filter_report()" class="art-button-green"/>
                </td>
            </tr>
        </table>
    </center>
    <div style="height:500px;overflow-y: scroll;">
    <table class="fixTableHead" style="text-align: center;background: #FFFFFF;">
        <thead style="color: white;">
            <tr style="background-color: #ccc; color: white;">
                <td class="rotate"><b>S/No.</b></td>
                <td class="rotate"><b><div><span>Wodi/idara</span></div></b></td>
                <td class="rotate"><b><div><span>Number of beds</span></div></b></td>
                <td class="rotate"><b><div><span>Admission</span></div></b></td>
                <td class="rotate"><b><div><span>Discharges live</span></div></b></td>
                <td class="rotate"><b><div><span>Deatds</span></div></b></b></td>
                <td class="rotate"><b><div><span>Abscondee</span></div></b></b></td>
                <td class="rotate"><b><div><span>%Deatds</span></div></b></td>
                <td class="rotate"><b><div><span>Avrg daily Admision(ADA)</span></div></b></td>
                <td class="rotate"><b><div><span>Avrg daily Discharge</span></div></b></td>
                <td class="rotate"><b><div><span>Inpatients/occupied Beds Days(OBD)</span></div></b></td>
                <td class="rotate"><b><div><span>Avrg daily Inpatients Census</span></div></b></td>
                <td class="rotate"><b><div><span>Bed occupancy Rate %</span></div></b></td>
                <td class="rotate"><b><div><span>Avrg Lengtd of stay in Hospital(days)</span></div></b></td>
                <td class="rotate"><b><div><span>Turn over Interval(TOI)</span></div></b></td>
                <td class="rotate"><b><div><span>Turn over per Bed(TOB)</span></div></b></td>
            </tr>
        </thead>
        <tbody id="self_and_refl_rpt_body">
        
        </tbody>
    </table>
    </div>
</fieldset>
<div id="selected_region"></div>
<div id="more_details"></div>

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
        $("#selected_region").dialog({
                        title: Region_Name_up+' :- REGION DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    });
    }
    function filter_report(){
        var filter_gender = "";
        var filter_age = "";
        var ipd_time = $("#ipd_time").val();;
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var gender=$("#gender").val();
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
        if(start_age != "" && end_age == ""){
            $("#end_age").css("border","2px solid red");
            exit;
        }
        else if(start_age == "" && end_age != ""){
            $("#start_age").css("border","2px solid red");
            exit;
        }
        else if(start_age != "" && end_age != ""){
            if(ipd_time==""){
                $("#ipd_time").css("border","2px solid red");
            exit;
            }
            else{
                var filter_age = "AND TIMESTAMPDIFF("+ipd_time+",reg_table.Date_Of_Birth,CURDATE()) BETWEEN "+ start_age+ " AND " + end_age;
            }
        }
        else{
            var filter_age = "";
        }
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
            url:'ajax_filter_hospital_statistics.php',
            data:{
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                filter_age:filter_age,
                },
            success:function(data){
                $("#self_and_refl_rpt_body").html(data);
            }
        });
    }

    function open_ward_details(ward_id,ward_name){
        $.ajax({
            type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                ward_id:ward_id,
                ward_name:ward_name,
                },
            success:function(data){
                
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '60%',
                        height: 500,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
        });
    }

    function open_admission_details(Ward_id_for_admission,ward_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var gender=$("#gender").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time=$("#ipd_time").val();

        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
        $.ajax({
            type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                Ward_id_for_admission:Ward_id_for_admission,
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                start_age:start_age,
                end_age:end_age,
                ipd_time:ipd_time,
                ward_name:ward_name,
                },
            success:function(data){
                
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '80%',
                        height: 500,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
        });
    }

    function open_alive_dischargina_patient(Ward_id_for_alive_discharg,ward_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var gender=$("#gender").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time=$("#ipd_time").val();

        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
        $.ajax({
            type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                Ward_id_for_alive_discharg:Ward_id_for_alive_discharg,
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                start_age:start_age,
                end_age:end_age,
                ipd_time:ipd_time,
                ward_name:ward_name,
                },
            success:function(data){
                
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '80%',
                        height: 500,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
        });
    }

    function open_dead_dischargina_patient(Ward_id_for_dead_admission,ward_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var gender=$("#gender").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time=$("#ipd_time").val();

        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
        $.ajax({
            type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                Ward_id_for_dead_admission:Ward_id_for_dead_admission,
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                start_age:start_age,
                end_age:end_age,
                ipd_time:ipd_time,
                ward_name:ward_name,
                },
            success:function(data){
                
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '80%',
                        height: 500,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
        });
    }
function open_abscond_discharged_patient(ward_id_for_absoncond, ward_name){
    var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var gender=$("#gender").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time=$("#ipd_time").val();

        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
        $.ajax({
        type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                ward_id_for_absoncond:ward_id_for_absoncond,
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                start_age:start_age,
                end_age:end_age,
                ipd_time:ipd_time,
                ward_name:ward_name,
                },
            success:function(data){
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '80%',
                        height: 600,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
       });
}
    function open_obd_form(Ward_id_for_obd,ward_name){
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var gender=$("#gender").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time=$("#ipd_time").val();

        if(gender == ""){
            filter_gender = ""; 
        }
        else{
            filter_gender = "AND reg_table.Gender = "+"'"+gender+"'";
        }
       $.ajax({
        type:'POST',
            url:'ajax_hospital_statistics_iframe.php',
            data:{
                Ward_id_for_obd:Ward_id_for_obd,
                start_date:start_date,
                end_date:end_date,
                filter_gender:filter_gender,
                start_age:start_age,
                end_age:end_age,
                ipd_time:ipd_time,
                ward_name:ward_name,
                },
            success:function(data){
                $("#more_details").dialog({
                        title: 'Ward Details',
                        width: '80%',
                        height: 600,
                        modal: true,
                    });
                    $("#more_details").html(data);
            }
       });
      
    }
</script>


