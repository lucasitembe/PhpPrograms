<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./SMSConfiguration.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } }
      if($_POST['disable_btn']){
            $reason_id=$_POST['reason_id'];
//              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  
//           $employee_diable = mysqli_fetch_assoc(mysqli_query($conn,"SELECT emp.Employee_Name FROM tbl_employee emp,tbl_reasons_adjustment ad WHERE  emp.Employee_ID=ad.Employee_ID AND ad.reason_id='$reason_id'"))['Employee_Name'];
            
            $sql_disable_reasons=mysqli_query($conn,"UPDATE  tbl_smsalert_templates SET status='disabled' WHERE SMS_Template_ID='$reason_id'") or die(mysqli_error($conn));
            
            if($sql_disable_reasons){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable Reason successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable Reason..please try again
                              </div>"; 
            }
        }
        
       if($_POST['enable_btn']){
            
//              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  
//   $employee_enable = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
            
                $reason_id=$_POST['reason_id'];
            $sql_disable_reasons=mysqli_query($conn,"UPDATE tbl_smsalert_templates SET Status='enabled' WHERE SMS_Template_ID='$reason_id'") or die(mysqli_error($conn));
            
            if($sql_disable_reasons){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable Reason successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable Reason..please try again
                              </div>"; 
            }
        }


?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
	<legend align=center><b>SET DAY BEFORE APPOINTMENT</b></legend>
	<center>
		<table width=40%>
			<tr>
				<?php
                                  $select_day = mysqli_fetch_assoc(mysqli_query($conn,"SELECT appointmed_day FROM tbl_appointment_day WHERE day_ID='1'"))['appointmed_day'];
                                ?>
				<td width="10%">
					<strong>ENTER NEW NUMBER OF DAY NOW IS: <?php echo $select_day ?></strong>
				</td>
				
				<td width="10%">
				</td>
			</tr>
			
			<tr>
				
				<td>
					<input id="Messageday"  name='Messageday'  />
				</td>
				
				<td>
					<input type="button" class='art-button-green' onClick="Savedaybeforeappointment()"  id="Saveday" value="SAVE DAY" style="margin-left:13px !important;" />
				</td>
                                <td style="width:50%">
				 <strong> Allow SMS to Patient</strong>
				</td>
                                <td>
                                    <?php
                                  $allow_sms_to_patient = mysqli_fetch_assoc(mysqli_query($conn,"SELECT allow_sms_to_patient FROM tbl_system_configuration"))['allow_sms_to_patient'];
                                  
//                                  echo $allow_sms_to_patient;
                                  if($allow_sms_to_patient != 'yes'){
                                      
                                 ?>
                                  <input type="checkbox" id="allowsms" onclick="allow_sms_send_to_patient()" style="width:50px;" value="yes">
                                     
                                    
                                     <?php
                                            
                                   }else{ 
                                    ?>  
                                    <input type="checkbox" id="allowsms" onclick="allow_sms_send_to_patient()" style="width:50px;" value="no" checked="checked">
                                     <?php
                                   }
                                ?>
				</td>
                                <td>
				 <strong> Allow SMS to Patient</strong>
				</td>
                                <td>
                                    <?php
                                  $allow_sms_to_patient = mysqli_fetch_assoc(mysqli_query($conn,"SELECT allow_sms_to_patient FROM tbl_system_configuration"))['allow_sms_to_patient'];
                                  
//                                  echo $allow_sms_to_patient;
                                  if($allow_sms_to_patient != 'yes'){
                                      
                                 ?>
                                  <input type="checkbox" id="allowsms" onclick="allow_sms_send_to_patient()" style="width:50px;" value="yes">
                                     
                                    
                                     <?php
                                            
                                   }else{ 
                                    ?>  
                                    <input type="checkbox" id="allowsms" onclick="allow_sms_send_to_patient()" style="width:50px;" value="no" checked="checked">
                                     <?php
                                   }
                                ?>
				</td>
			</tr>
		</table>
<!--		<div id="Cached" style="width:60%;"></div>
		<div id="DelResults"  style="width:60%;"></div>
		<div id="ItemParameters"  style="width:60%;"></div>-->
					
	</center>
            <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">
                         SSMS AREA 
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>AREA</th>
                            <th >DISABLE/ENABLE</th>
                       
                            
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_reasons_result=mysqli_query($conn,"SELECT SMS_Template_ID,Department_Name,SMS_Type,Message,status FROM tbl_smsalert_templates") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_reasons_result)>0){
                                while($reasons_rows=mysqli_fetch_assoc($sql_select_reasons_result)){
                                   $reasons= $reasons_rows['Department_Name'];
                                   $reason_id= $reasons_rows['SMS_Template_ID'];
                                 $Status_reasons=$reasons_rows['status'];
                                   echo "
                                       <tr>
                                        <td>$count</td>
                                        <td>$reasons</td>
                                         </td>
                                        <td>
                                      
                                         <form action='' method='POST'>
                                                        <input type='text' name='reason_id' value='$reason_id' hidden='hidden'>";
                                    
                                                        if($Status_reasons=="enabled"){
                                                           echo "<center><input type='submit' name='disable_btn' value='DISABLE SMS' class='btn art-button btn-sm'></center>";  
                                                        }else{
                                                           echo "<center><input type='submit' name='enable_btn' value='ENABLE SMS' class='btn btn-danger btn-block btn-sm'></center>"; 
                                                        }
                                                       
                                                                echo "
                                                    </form>
                                      
                                         </td>
                                       </tr>
                                        ";
                                   $count++;
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<br/>
</fieldset>
<br/>

<script>
 function Savedaybeforeappointment(){
             var day = $('#Messageday').val();
          $.ajax({
        type:'POST',
        url:'save_day_before_appointment.php',
        data:{day:day},
        success:function(html){
           alert("day saved successfully");
           var day = $('#Messageday').val("");
        }
        });
 }
 function allow_sms_send_to_patient(){
        var allowsms = $('#allowsms').val();
        
          if(allowsms == "yes"){
              
              var checkstatus = "yes";
              
          }else{
              var checkstatus = "no";
          }
          
//           alert(checkstatus);
          $.ajax({
        type:'POST',
        url:'ajax_save_sms_appointment.php',
        data:{allowsms:allowsms,checkstatus:checkstatus},
        success:function(html){
            alert("successfully");
            console.log(allowsms);
          
        }
        });
 }

</script>

<?php
    include("./includes/footer.php");
?>