<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='doctorsworkspage.php?DoctorsWorksPage=DoctorsWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/>

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 
 
<fieldset>  
         <legend style="background-color:#006400;color:white;padding:5px;" align="center"><b>DOCTOR'S APPOINTMENTS</b></legend>
        <center>
			<table width="50%">
			<tr><td colspan=8><hr></td></tr>
			<tr>
				<td>
					
				</td>
				<td>
					<b>APPOINTMENTS DATE</b>
				</td>
				<td>
					<b>PATIENT NAME</b>
				</td>
				<td>
					<b>STATUS</b>
				</td>
			</tr>
			<tr><td colspan=8><hr></td></tr>
			<?php
			
				$doctor_id = $_GET['doc'];
				//$select_appoints = "SELECT * FROM tbl_appointment WHERE doctor_id = '$doctor_id' ";
				$select_appoints = "
					SELECT 
					tbl_appointments_users.client_name, 
					tbl_appointment.date,
					tbl_appointment.status,
					tbl_appointment.appointment_id
					FROM tbl_appointment 
					INNER JOIN tbl_appointments_users
					ON tbl_appointments_users.client_id = tbl_appointment.client_id
					WHERE tbl_appointment.doctor_id = $doctor_id
				";
				$appoints_selected = mysqli_query($conn,$select_appoints) or die(mysqli_error($conn));
				if(mysqli_num_rows($appoints_selected) > 0){
					$i = 1;
					while($row = mysqli_fetch_assoc($appoints_selected)){
						$date = $row['date'];
						$status = $row['status'];
						$appointment_id = $row['appointment_id'];
						$newdate = date("M j, Y. g:i a", strtotime($date));
						$client_name = $row['client_name'];
						$aprove = "<input type='button' name='approve' id='appr".$i."' value='Approve' onClick='ApproveThis(".$appointment_id.");' />";
						echo "<tr>";
						
							echo "<td>";
								echo $i;
							echo "</td>";
							
							echo "<td>";
								echo $newdate;
							echo "</td>";
							
							echo "<td>";
								echo $client_name;
							echo "</td>";
							
							echo "<td>";
								echo $status .' &nbsp; ';
								echo $aprove;
							echo "</td>";
						
						echo "</tr>";
						$i++;
					}								
				}					
			?>
			
					<script>
						function ApproveThis(appoint_id){
							$.ajax({
								url: "approve_appoint.php",
								type: "POST",
								data: { 'appoint_id': appoint_id, 'status': 'approved' },                   
								success: function(){
												alert("Appointment Approved!");                                    
											}
							});
							//alert(appoint_id);
						}
					</script>
			
			</table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>reports