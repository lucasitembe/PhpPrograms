<?php
//**********@gkcchief
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
  
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<fieldset>
    <legend align=center><b>SYSTEM AUDIT TRAIL</b></legend>
    <div class='row'>
        <div class="col-md-12">
            <div class="col-md-3">
                <input type='text' placeholder="Search Employee Name" onkeyup="fetch_system_audit_trail_logs()" id='Employee_Name' style="text-align: center">
            </div>
            <div class="col-md-3">
                <input type='text' placeholder="Start Date"id='start_date' class='form-control date_and_time'readonly='readonly' style="text-align: center;background:#FFFFFF">
            </div>
            <div class="col-md-3">
                <input type='text' placeholder="End Date"id='end_date'  class='form-control date_and_time'readonly='readonly'style="text-align: center;background:#FFFFFF">
            </div>
            <div class="col-md-2">
                <a href="#" class="art-button-green btn-block" onclick="fetch_system_audit_trail_logs()">FILTER</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-top: 20px">
        <div class="box box-primary" style="height: 70vh;overflow-y: scroll;overflow-x: hidden">
            <table class="table">
                <tr>
                    <th width='50px'>S/No.</th>
                    <th>Employee Name</th>
                    <th>Login Time</th>
                    <th>Logout Time Time</th>
                    <th>Computer IP Address</th>
                    <th style="width: 50%;">Performed Activities</th>
                </tr>
                <tbody id="audit_trail_display_tbody"></tbody>
            </table>
        </div>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('.date_and_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.date_and_time').datetimepicker({value: '', step: 01});
</script>
<?php
    include("./includes/footer.php");
?>
<script>
    function fetch_system_audit_trail_logs(){
        var Employee_Name=$("#Employee_Name").val();
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var validate=0;
        if(start_date==""){
           $("#start_date").css("border","2px solid red");
           $("#start_date").focus();
           validate++;
        }
        if(end_date==""){
           $("#end_date").css("border","2px solid red");
           $("#end_date").focus();
           validate++;
        }
        if(validate<=0){
            $("#audit_trail_display_tbody").html("<tr style='background:ghostwhite'><th colspan='6'>Loading Please wait . . .</th></tr>");
           $.ajax({
               type:'POST',
               url:'ajax_fetch_system_audit_trail_logs.php',
               data:{Employee_Name:Employee_Name,start_date:start_date,end_date:end_date},
               success:function(data){
                   $("#audit_trail_display_tbody").html(data);
               }
           }); 
        }
    }
</script>