<?php
include("includes/header.php");
include("includes/connection.php");
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href='DoctorRoundReport.php' class='art-button-green'> BACK </a>
<style>
    table,tr,td{
        border:none!important;
    }
    
    input[type="radio"] {
        -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
        -moz-appearance: checkbox;    /* Firefox */
        -ms-appearance: checkbox;     /* not currently supported */
    }
</style>
<fieldset>
    <legend align="center"> ASSIGN DOCTOR TO PATIENT </legend>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header" style="border-bottom:1px solid #328CAF!important;">
                    <h4>LIST OF ALL EMPLOYEE</h4><br>
                                   <input type="text" id='employee_search_value' onkeyup="search_employee()"placeholder="search Employee Name" class="form-control" style="width:90%; text-align:center;"/>
                </div>
                <div class="box-body" style="height: 390px;overflow-y: auto;overflow-x: auto">
                    <div id="employee_list">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                  <h4>LIST OF PATIENT</h4>
                </div>
                <input type="text" id='patient_name' onkeyup="filter_patient_list()"placeholder="Search patient name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>
                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                         <td>
                            <?php 
                                $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                                $select_wards = mysqli_query($conn, "SELECT * FROM tbl_hospital_ward WHERE ward_type='ordinary_ward' AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))" ) or die(mysqli_error($conn));
                                $count_wards = mysqli_num_rows($select_wards);
                                echo "<select id='Hospital_Ward_ID' onchange='filter_patient_list()'>";
                                echo "<option style='width:200px; height:30px;' value=''>Select Ward</option>";
                                if($count_wards > 0){
                                        while($ward = mysqli_fetch_assoc($select_wards)){

                                                $WName = $ward['Hospital_Ward_Name'];
                                                $WID = $ward['Hospital_Ward_ID'];
                                                
                                                echo "<option ".$selected." value='".$WID."'>".$WName."</option>";
                                        }
                                }
                                echo "</select>";
                            ?>
                        </td> 
                        <td width="5%">
                        <a class="btn btn-default pull-right btn-sm" onclick="assign_doctor_to_patient()" style="text-decoration:none!important;"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-1x"></i> ASSIGN </a>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 350px;overflow-y: auto;overflow-x: hidden">
                    <div id="patient_list">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h5>LIST OF DOCTOR ASSIGNED PATIENT</h5>
                </div>

                <input type="text" id='search_assigned_doctor_patient' onkeyup="display_patient_assigned_doctor()" placeholder="search . . ." class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>

                <table class="table">
<!--                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_department_word'>SELECT ALL ASSIGNED DOCTOR
                            </label>
                        </td>
                        <td width="5%">
                        <a href="#" class="btn btn-default pull-right btn-sm" onclick="delete_assigned_doctor_patient()" style="color:red;text-decoration:none!important;"><i id="attach_cat_icon"  class="fa fa-trash fa-1x"></i> DELETE ALL SELECTED</a>
                        </td>
                    </tr>-->
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td colspan="2">
                            <center class="text-info">
                                <span id="msg"></span>
                            </center>
                        </td>
                    </tr>
                </table>

                <div class="loading" style="display:none">
                    <center>
                        <img style="border: none;" src="images/please_wait_loading.gif" alt="Loading gif" class="img img-responsive">
                    </center>
                </div>

                <div class="box-body" id="box-body"  style="height: 435px; overflow-y: auto; overflow-x: hidden">
                    <div id='assigned_doctor_patient_list'>
                       
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>
<script>
    
    function search_employee(){
       var employee_search_value=$("#employee_search_value").val()
       $.ajax({
           type:'POST',
           url:'ajax_search_employee_to_assign_pat.php',
           data:{employee_search_value:employee_search_value},
           success:function(data){
              $("#employee_list").html(data) 
           }
       });
       
    }
    function filter_patient_list(){
        var patient_name=$("#patient_name").val();
        var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
        $.ajax({
            type:'POST',
            url:'ajax_filter_patient_list.php',
            data:{patient_name:patient_name,Hospital_Ward_ID:Hospital_Ward_ID},
            success:function(data){
                $("#patient_list").html(data);
            }
        });
    }
function assign_doctor_to_patient(){
     var selected_employee = []; 
    $(".Employee_ID:checked").each(function() {
        selected_employee.push($(this).val());
    });

    var selected_patient = []; 
    $(".Admision_ID:checked").each(function() {
        selected_patient.push($(this).val());
    });

    if(selected_employee==""){
        alert("Employee must be selected first.");
    }else if(selected_patient==""){
        alert("Patient must be selected before merging.");
    }else{
      $('.box-body').hide();$('.loading').show();
      $.ajax({
          type:'POST',
          url:'ajax_assign_doctor_to_patient.php',
          data:{selected_employee:selected_employee,selected_patient:selected_patient},
          success:function(data){
             
             $('.loading').hide();$('.box-body').show();
             display_patient_assigned_doctor();
             alert(data); 
             
          }
      });  
    }
}
function display_patient_assigned_doctor(){
    var search_assigned_doctor_patient=$("#search_assigned_doctor_patient").val()
    $.ajax({
        type:'POST',
        url:'ajax_display_patient_assigned_doctor.php',
        data:{search_assigned_doctor_patient:search_assigned_doctor_patient},
        success:function(data){
            $("#assigned_doctor_patient_list").html(data);
        }
    });
}
$(document).ready(function(){
    search_employee();
    filter_patient_list();
    $("select").select2();
    display_patient_assigned_doctor()
});
</script>