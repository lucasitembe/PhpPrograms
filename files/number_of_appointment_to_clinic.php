<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//	header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
	}else{
	    //header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
            if(isset($_GET['numberofappointment'])){
?>
    <a href='all_appointment_configuration.php' class='art-button-green'>
        BACK
    </a>
<?php  }else{

    echo "<a href='searchappointmentPatient.php' class='art-button-green'>
            BACK
        </a>";
}} //} ?>
<?php  //}       //get today's date
  $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link href="css/jquery-ui.css" rel="stylesheet" />
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>



	<!--<script type="text/javascript" src="css/jquery.min.js"></script>-->
	<script type="text/javascript" src="css/jquery.timepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />

<!--   Core JS Files   -->

<!--<br/><br/><br/><br/><br/><br/><br/><br/>
 <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">
                         Clinic List 
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th width="40%">Clinic Name</th>
                            <th width="20%">Number of Appointment</th>
                            <th>Update</th>
                        </tr>
                                          <?php 
//                            $Clinic_ID=0;
//                            $mysqli_select_number_of_appointment=0;
//                            $count=1;
//                            $sql_select_clinic=mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic") or die(mysqli_error($conn));
//                            if(mysqli_num_rows($sql_select_clinic)>0){
//                                while($Clinic_ID_rows=mysqli_fetch_assoc($sql_select_clinic)){
//                                   $Clinic_ID= $Clinic_ID_rows['Clinic_ID'];
//                                   $Clinic_Name= $Clinic_ID_rows['Clinic_Name'];
//                                   
//                                    $mysqli_select_number_of_appointment= mysqli_fetch_assoc(mysqli_query($conn,"SELECT total_number FROM tbl_number_of_clinic WHERE Clinic_ID='$Clinic_ID'"))['total_number'];  
//                                    
//                                     
//                                    $data = '{"Clinic_ID":"'.$Clinic_ID.'","mysqli_select_number_of_appointment":"'.$mysqli_select_number_of_appointment.'"}';
//                            
//                                    echo "
//                                       <tr>
//                                        <td>$count</td>
//                                        <td>$Clinic_Name</td>
//                                        <td>
//                                           <input type='text' value='$mysqli_select_number_of_appointment' style='text-align:center;' name='appointment' id='appointment$Clinic_ID'/>
//                                        </td>
//                                        </td>
//                                        <td>
//                                           ";
//                                            echo "<center><input type='button' value='UPDATE'  onclick='save_or_update_appointment_number($Clinic_ID)' class='btn art-button btn-sm'></center>";  
//                                            echo "      
//                                        </td>
//                                       </tr>
//                                        ";
//                                   $count++;
//                                }
//                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<br/>-->


       <fieldset>
    <legend align="center"> APPOINTMENT SETUP TO CLINIC'S AND DOCTOR'S</legend>
 
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>Select doctors or clinic</h4>
                </div>
                
                             
                <select name='Item_Category_ID' id='Item_Category_ID' style="width:150px;margin-left:40px;" onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                    <option selected='selected'></option>
                    <option value="1">Doctors</option>
                    <option value="2">Clincs</option>
   
                </select>
                            
                <input type="text" id='search_value' onkeyup="search_item()" placeholder="Search" class="form-control" style="width:100%;"/></span></caption>      
                <div class="box-body" style="height: 530px;overflow-y: auto;overflow-x: auto">
                    <table class="table">
                        <tr>
                            <td width="5%">Action</td>
                            <td width='40%'>Doctor/Clinic  Appointment</td>
                           
                        </tr>
                        <tbody id="table_search">
                     
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>Merging Area</h4>
                    <h3 id="selected_clinic_or_doctor"></h3>
                    <input type="text" name="id" id="selected_clinic_or_doctor_id" hidden="hidden">
                    <input type="text" name="id" id="status_doctors_clinic" hidden="hidden">
                </div>
                <div class="box-body" style="">
                    <div class='row' style='height:100px;overflow-y: auto;overflow-x: hidden'>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                <caption><b>Date of Appointment</b></caption>
                                </tr>
                                <tr>
                                    <td><input type="text" id="fromDate" required="true" value="<?= date("Y-m-d",strtotime($Start_Date)) ?>" name="fromDate" style="width: 200px"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr/>
                        </div>
                    </div>
                    <div class='row' style='height:200px;overflow-y: auto;overflow-x: hidden'>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                <caption><b>TIME SETING</b></caption>
                                </tr>
                                <tr>
                                    <td>Start Time</td>
                                    <td>End Time</td>
                                    <td>Number of Patient</td>
                                </tr>
                                <tr>
                                    <td>
					<input id="basicExample" type="text" class="time" />

                                    </td>
                                    <td>
                                    <input id="timepicker" type="text" class="time" />
                                    </td>
                                    <td>
                                    <input type="text" class="time"  id="total_number"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
               
                    <div><input type="button" class="art-button-green pull-right"value="SAVE" onclick="save_appointment_schedule()"/></div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header">

                    <!--<div class="col-md-12"><button type='button' style='color:white !important;' class='art-button-green' onclick='addorganism();'>Add Organism</button></div></div>-->
                </div>
                <div class="box-body" style="height: 510px;overflow-y: auto;overflow-x: hidden">
                    <table class="table">
                    
                        <tbody id="list_of_appointment_schedule">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>
 <div id="showdataEdit" style="width:100%;overflow:hidden;display:none;">
            <div id="parametersEdit">
            </div>
        </div>

<script>
   
       
</script>

     <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

<script>
 
    $('#fromDate').datepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#fromDate').datepicker({value:'',step:1});
    
    function getItemsList(Item_ID){
         $.ajax({
          type:'POST',
          url:'Get_clinic_doctor_list.php',
          data:{Item_ID:Item_ID},
          success:function(data){
             $("#table_search").html(data); 
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
        
    }
    
   function disiable_schedule(date_to_appointment){
    $.ajax({
        type:'post',
        url:'appointment_schedule_available.php',
        data:{App_ID:date_to_appointment,diableapointment:''},
        success:function(responce){
//                fetch_appointment_schedule.php
            alert(responce)
            appointment_schedule()
        }
        });
   }

    function enable_schedule(date_to_appointment){
        $.ajax({
            type:'post',
            url:'appointment_schedule_available.php',
            data:{App_ID:date_to_appointment,enableapointment:''},
            success:function(responce){
                alert(responce)     
                appointment_schedule()   
            }
        });
    }
    
        function search_item(){
        var search_value=$("#search_value").val();
        var Item_Category_ID=$("#Item_Category_ID").val();
       
          $.ajax({
          type:'POST',
          url:'doctor_or_clinic_search.php',
          data:{Item_Category_ID:Item_Category_ID,search_value:search_value},
          success:function(data){
             $("#table_search").html(data); 
//             phamathetical_search.php
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
        function save_appointment_schedule(){
        var timepicker=$("#timepicker").val();
        var total_number=$("#total_number").val();
        var basicExample=$("#basicExample").val();
        var fromDate=$("#fromDate").val();
        var selected_clinic_or_doctor_id=$("#selected_clinic_or_doctor_id").val();
        var status_doctors_clinic=$("#status_doctors_clinic").val();

          $.ajax({
          type:'POST',
          url:'docto_and_clinic_save.php',
          data:{timepicker:timepicker,basicExample:basicExample,fromDate:fromDate,selected_clinic_or_doctor_id:selected_clinic_or_doctor_id,total_number:total_number,status_doctors_clinic:status_doctors_clinic},
          success:function(data){
              console.log(data);
               alert("successfully schedule inserted!!");
               appointment_schedule(selected_clinic_or_doctor_id,status_doctors_clinic);
               
                   
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }

</script>

<script>

  function save_or_update_appointment_number(Clinic_ID){
      
      
      var number_of_appointment=$("#appointment"+Clinic_ID).val();
      
//       alert(number_of_appointment);
      
          $.ajax({
            url:'update_number_of_appointment.php',
            type:'post',
            data:{Clinic_ID:Clinic_ID,number_of_appointment:number_of_appointment},
            success:function(result){
                  console.log(result);
                alert("update successfully");
         
            }
        });
       
   }
   $(document).ready(function(){
  $('.timepicker').timepicker();
});
 //timepicker
    function update_display(Clinic_Name,Clinic_ID,status){
//          alert(status);
        $("#selected_clinic_or_doctor").html(Clinic_Name);
        $("#selected_clinic_or_doctor_id").val(Clinic_ID);
        $("#status_doctors_clinic").val(status);
         appointment_schedule(Clinic_ID,status);
    }  
//    function update_display_doctors(Clinic_Name,Clinic_ID){
////          alert(Clinic_ID);
//        $("#selected_clinic_or_doctor").html(Clinic_Name);
//        $("#selected_clinic_or_doctor_id").val(Clinic_ID);
//         appointment_schedule(Clinic_ID);
//    }  
    
    function appointment_schedule(Clinic_ID,status){
              $.ajax({
            type:'post',
            url:'fetch_appointment_schedule.php',
            data:{Clinic_ID:Clinic_ID,status:status}, 
            success:function(result){
                console.log(result);
                 $("#list_of_appointment_schedule").html(result);
         
            }
        });
    }
</script>
	<script>
			$(function() {
				$('#basicExample').timepicker();
			});
			$(function() {
				$('#timepicker').timepicker();
			});
                        
                         $(document).ready(function(){
                             
                      getItemsList();
                      
});
			
      $(document).ready(function () {
//           $("#showdataEdit").dialog({autoOpen: false, width: '50%', title: '', modal: true, position: 'center'});
           
         $("#showdataEdit").dialog({autoOpen: false, width: 400, height: 500, title: 'Details of Cancer', modal: true});
    
         $('select').select2();
    });	
    
    function editThisParam(Time_ID) {
                var Time_ID = Time_ID;
                $("#showdataEdit").dialog("option", "title", "Edit time schedule");
                //var datastring='Parameter_ID='+Parameter_ID+'&edit=yes';
//                  alert(id);
                $.ajax({
                    type: 'POST',
                    url: "Editappointment_schedule.php",
                    data: {Time_ID:Time_ID},
                    success: function (data) {
//                        alert(data);
                        $("#showdataEdit").html(data);
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });

                $("#showdataEdit").dialog("open");
    }
    
    function Edite_appointment_schedule(App_ID,Time_ID){
       var edit_time_from=$("#edit_time_from").val()
       var edit_time_to=$("#edit_time_to").val()
       var edit_total_number=$("#edit_total_number").val()
       var edit_date=$("#edit_date").val()
                  $.ajax({
                    type: 'POST',
                    url: "save_Editappointment_schedule.php",
                    data: {time_from:edit_time_from,time_to:edit_time_to,App_ID:App_ID,Time_ID:Time_ID,edit_total_number:edit_total_number,edit_date:edit_date},
                    success: function (data) {
                        alert(data);
//                        $("#showdataEdit").html(data);
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
    
    }
</script>
