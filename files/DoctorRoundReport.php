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
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen"> 
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<link rel='stylesheet' href='fixHeader.css'>
<!-- <script src="media/js/jquery.js"></script> -->
<script src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('#reportID').DataTable();
});


$(function () { 
	$("#TheDate").datepicker({ 
		changeMonth: true,
		changeYear: true,
		showWeek: true,
		showOtherMonths: true,
		dateFormat: "yy-mm-dd"
	});
	
});

</script>
<link rel='stylesheet' href='fixHeader.css'>
<style>
    .rows_list{
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

<?php
//get sub department id
$Sub_Department_ID = '';
if(isset($_SESSION['Radiology'])){
	$Sub_Department_Name = $_SESSION['Radiology'];
	$select_sub_department = mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name = '$Sub_Department_Name'");
	while($row = mysqli_fetch_array($select_sub_department)){
		$Sub_Department_ID = $row['Sub_Department_ID'];
	}
} else {
	$Sub_Department_ID = '';
}
	isset($_SESSION['RadiologyReportSponsor']) ? $Sponsor = $_SESSION['RadiologyReportSponsor'] : $Sponsor = '';

	
$Sub_Department_Name = $_SESSION['Admission'];


?>
<a href='assign_doctor_to_patient.php' class='art-button-green'> ASSIGN DOCTOR TO PATIENT </a>


<?php 
$qr = "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                            $ward_results = mysqli_query($conn,$qr);
                            if(mysqli_num_rows($ward_results)>0){
                                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                                    
                                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";
    
                                }
                                echo "<a href='admissionreports.php' class='art-button-green'> BACK </a>";
                            } 
	if(isset($_POST['select_ward'])){
		$WardSelected = $_POST['select_ward'];
	} else {
		$WardSelected = '0';
	}
?>
<br><br>
<div class="row">
    <div class="col-md-12"> 
<fieldset style='margin-top:10px;'>
<?php 
	if(isset($_POST['SumitTheDate'])){
		$Date = $_POST['TheDate'];
	} else {
		$Date = date('Y-m-d');
	}
?>
	<legend style="background-color:#006400;color:white;padding:5px;" align="center"><b> DOCTOR ROUND REPORT </b></legend>	
	<center>
        <table>
			<form method="POST" style="widht:200px; float:left;">
				<td><b><?php echo $Date; ?></b></td>
				<td><input style="width:218px; height:25px; text-align:center;" required type="text" name="TheDate" id="TheDate" style="width:100px;" /></td>
				<td><input type="hidden" name="select_ward" value="<?php echo $WardSelected; ?>" /></td>
				<td><input style="width:100px; height:27px;" type="submit" class="art-button-green" value="Filter" name="SumitTheDate" id="SubmitTheDate" /></td>
				<td><form method="POST">
					<?php

							$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
						$select_wards = mysqli_query($conn, "SELECT * FROM tbl_hospital_ward WHERE ward_type='ordinary_ward' AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID')) AND ward_status='active'" ) or die(mysqli_error($conn));
						$count_wards = mysqli_num_rows($select_wards);
						echo "<select name='select_ward' id='select_ward' onchange='this.form.submit()'>";
						echo $Display;
						if($count_wards > 0){
							while($ward = mysqli_fetch_assoc($select_wards)){
							
								$WName = $ward['Hospital_Ward_Name'];
								$WID = $ward['Hospital_Ward_ID'];
								if ($WardSelected == $WID) {
									$selected = "selected=selected";
								} else {
									$selected = "";
								}
								echo "<option ".$selected." value='".$WID."'>".$WName."</option>";
							}
						}
						echo "</select>";
					?>
					</form>
				</td>
				<td><input type='text' placeholder="Search . . ." style="text-align: center" id='search_table'></td>
			</form>
		</table>
	</center>
	<center>
		<div style="width:100%;" id="ListTable">
                <div class="box box-primary" style="overflow-y: scroll; height:500px">
			
					<?php
					
					//SELECTING PATIENTS LIST				
					$SelectRadiItems = "
						SELECT *
							FROM 
							tbl_admission ad,
							tbl_patient_registration pr,
							tbl_hospital_ward hw,
                                                        tbl_ward_rooms wr
								WHERE
                                                                ad.ward_room_id=wr.ward_room_id AND
								ad.Registration_ID = pr.Registration_ID AND
								ad.Admission_Status = 'Admitted' AND 
								ad.Hospital_Ward_ID = hw.Hospital_Ward_ID 
                                                                AND  ward_type='ordinary_ward'
					";	

					//if($WardSelected != ''){
						$SelectRadiItems .= " AND ad.Hospital_Ward_ID = '$WardSelected'";
					//}
					
					echo '<table width="100%" id="my_table" style="background:#FFFFFF;" class="fixTableHead">
						<thead>
							<tr style="text-transform:uppercase; font-weight:bold;background-color: #ccc;">	
								<th style="width:3% !important;">SN</th>	
								<th>Patient Name</th>
								<th>Ward Room/Bed</th>
								<th>Patient No.</th>	
								<th>Ward Round</th>	
								<th>Medicine</th>	
								<th>Radiology</th>	
								<th>Laboratory</th>	
								<th>Doctors Plan</th>	
								<th>Nurse Plan</th>	
								<th>Patient File</th>	
							</tr>
						</thead>
						<tbody id="round_tbody">';	
					
					$SelectRadiItems_qry = mysqli_query($conn,$SelectRadiItems) or die(mysqli_error($conn));
					$sn = 1;
					while($RadiTests = mysqli_fetch_assoc($SelectRadiItems_qry)){
						$PatientName = $RadiTests['Patient_Name'];
						$PatientNo = $RadiTests['Registration_ID'];
						$Ward_Name = $RadiTests['Hospital_Ward_Name'];
						$Bed_Name = $RadiTests['Bed_Name'];
						$room_name = $RadiTests['room_name'];
						
						$select_ward_round = mysqli_query($conn,
							"SELECT * FROM tbl_ward_round WHERE Registration_ID = '$PatientNo' AND Ward_Round_Date_And_Time LIKE '$Date%'"
							) or die(mysqli_error($conn));
						$results_count = mysqli_num_rows($select_ward_round);
						if($results_count > 0){
							$ward_round = mysqli_fetch_assoc($select_ward_round);
							$WardRound = 'Yes';
						} else {
							$WardRound = 'Not Done';
						}
						
						$Radiology = 'Not Ordered';
						$Laboratory = 'Not Ordered';
						$Medicine = 'Not Ordered';	
						
						$select_inv = mysqli_query($conn,
							"SELECT * FROM 
							tbl_item_list_cache ilc,
							tbl_payment_cache pc
							WHERE 
							pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
							pc.Registration_ID = '$PatientNo' AND 
							ilc.Service_Date_And_Time LIKE '$Date%'") or die(mysqli_error($conn));
						$inv_count = mysqli_num_rows($select_inv);
						if($inv_count > 0){
							while($inv = mysqli_fetch_assoc($select_inv)){
								$CheckInType = $inv['Check_In_Type'];
								if($CheckInType == 'Radiology'){
									$Radiology = '<a href="#" style="text-decoration: none;" onclick="odered_radiology('.$PatientNo.',\''.$Date.'\')">Ordered</a>';
								} elseif($CheckInType == 'Laboratory'){
									$Laboratory =  '<a href="#" style="text-decoration: none;" onclick="odered_investigation('.$PatientNo.',\''.$Date.'\')">Ordered</a>';
								} elseif($CheckInType == 'Pharmacy'){
									$Medicine =  '<a href="#" style="text-decoration: none;" onclick="odered_medicine('.$PatientNo.',\''.$Date.'\')">Ordered</a>';
								}
							}
						} else {
							$Radiology = 'Not Ordered';
							$Laboratory = 'Not Ordered';
							$Medicine = 'Not Ordered';
						}
						
						$doctors_plan="";
						$nurse_plan="";
						//doctor plan
						$sql_select_doctor_plan_result=mysqli_query($conn,"SELECT remarks FROM tbl_ward_round WHERE DATE(Ward_Round_Date_And_Time)=DATE('$Date') AND  Registration_ID='$PatientNo' ORDER BY Round_ID DESC LIMIT 1") or die(mysqli_error($conn));
						if(mysqli_num_rows($sql_select_doctor_plan_result)){
							$doctors_plan=mysqli_fetch_assoc($sql_select_doctor_plan_result)['remarks'];
						}
						
						//nurse plan
						
						$sql_select_nurse_plan_result = mysqli_query($conn,"select Notes from tbl_nurse_notes where DATE(Notes_Date_Time) = DATE('$Date') and Registration_ID = '$PatientNo' ORDER BY Notes_ID DESC LIMIT 1") or die(mysqli_error($conn));
						if(mysqli_num_rows($sql_select_nurse_plan_result)>0){
							$nurse_plan=mysqli_fetch_assoc($sql_select_nurse_plan_result)['Notes']; 
						}
						
						$PatientFile  = "<a href='all_patient_file_link_station.php?Registration_ID=$PatientNo' target='_blank' class='art-button-green'>Preview</a>";
						$doctorplan  = "<a href='#' onclick='doctorplan(".$PatientNo.",\"".$Date."\")' class='art-button-green'>Doctor`s Plan</a>";
						$nurseplan  = "<a href='#' onclick='btnnurseplan(".$PatientNo.",\"".$Date."\")' class='art-button-green'>Nurse`s Plan</a>";

						//select assigned doctor
						$assigned_doctor=$RadiTests['assigned_doctor'];
						$Employee_Name="";

						$sql_select_assigned_doctor_result=mysqli_query($conn,"SELECT Employee_Name From tbl_employee WHERE Employee_ID='$assigned_doctor'") or die(mysqli_error($conn));
						if(mysqli_num_rows($sql_select_assigned_doctor_result)>0){
							$Employee_Name=mysqli_fetch_assoc($sql_select_assigned_doctor_result)['Employee_Name'];
						}


						echo '<tr>';	
							echo '<td>'.$sn.'</td>';	
							echo '<td>'.$PatientName.'<b>(~assigned to:-'.$Employee_Name.')</b></td>';	
							echo '<td>'.$room_name.' <b> ('.$Bed_Name.') </b> '.'</td>';	
							echo '<td>'.$PatientNo.'</td>';	
							echo '<td>'.$WardRound.'</td>';	
							echo '<td>'.$Medicine.'</td>';	
							echo '<td>'.$Radiology.'</td>';;	
							echo '<td>'.$Laboratory.'</td>';;	
							echo '<td style="width: 3%;">'.$doctorplan.'</td>';;	
							echo '<td style="width: 3%;">'.$nurseplan.'</td>';;	
							echo '<td style="width: 3%;">'.$PatientFile.'</td>';;	
						echo '</tr>';
                                                
						$sn++;
					}
						
						
					echo '</tbody></table>';
					?>
				
			</div>
		</div>
	</center>
</fieldset>
</div>
</div>

<div id="odered_medicine" style="width:50%;margin-top: 5px;" >
	<div class="box box-primary" style="overflow-y: scroll; height:500px">
		<table width="100%" id="" class="table" style="background:#FFFFFF;">
			<thead>
				<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
					<th style="width:3% !important;">SN</th>	
					<th>Product Name</th>
					<th>Dosage</th>
					<th>Ordered By</th>	
					<th>Status</th>	
				</tr>
			</thead>
			<tbody id="medicine_ordered_data"></tbody>
		</table>
	</div>
</div>

<div id="odered_investigation" style="width:50%;margin-top: 5px;" >
	<div class="box box-primary" style="overflow-y: scroll; height:500px">
		<table width="100%" id="" class="table" style="background:#FFFFFF;">
			<thead>
				<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
					<th style="width:3% !important;">SN</th>	
					<th>Product Name</th>
					<th>Remarks</th>
					<th>Ordered By</th>	
					<th>Status</th>	
				</tr>
			</thead>
			<tbody id="investigation_ordered_data"></tbody>
		</table>
	</div>
</div>

<div id="odered_radiology" style="width:50%;margin-top: 5px;" >
	<div class="box box-primary" style="overflow-y: scroll; height:500px">
		<table width="100%" id="" class="table" style="background:#FFFFFF;">
			<thead>
				<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
					<th style="width:3% !important;">SN</th>	
					<th>Product Name</th>
					<th>Remarks</th>
					<th>Ordered By</th>	
					<th>Status</th>	
				</tr>
			</thead>
			<tbody id="radiology_ordered_data"></tbody>
		</table>
	</div>
</div>

<div id="doctorplan" style="width:50%;margin-top: 5px;" >
	<div class="box box-primary" style="overflow-y: scroll; height:500px">
		<table width="100%" id="" class="table" style="background:#FFFFFF;">
			<thead>
				<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
					<th style="width:3% !important;">SN</th>	
					<th>Doctor Name</th>
					<th>Doctor`s Plan</th>	
				</tr>
			</thead>
			<tbody id="doctorplan_data_notes"></tbody>
		</table>
	</div>
</div>

<div id="nurseplan" style="width:50%;margin-top: 5px;" >
	<div class="box box-primary" style="overflow-y: scroll; height:500px">
		<table width="100%" id="" class="table" style="background:#FFFFFF;">
			<thead>
				<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
					<th style="width:3% !important;">SN</th>	
					<th>Nurse Name</th>
					<th>Nurse`s Plan</th>	
				</tr>
			</thead>
			<tbody id="nurseplan_data_notes"></tbody>
		</table>
	</div>
</div>

<script>
    function odered_medicine(Registration_ID, Round_Date) {
        $("#odered_medicine").dialog("open");
		$.ajax({
            type: 'POST',
            url: 'ajax_doctor_round_report.php',
            data: {
				Round_Date: Round_Date,
				Registration_ID: Registration_ID,
				filter: "Pharmacy"
			},
            cache: false,
            success: function (html) {
                $('#medicine_ordered_data').html(html);
            }
        });
    }
</script>

<script>
    function odered_investigation(Registration_ID, Round_Date){
        $("#odered_investigation").dialog("open");
		$.ajax({
            type: 'POST',
            url: 'ajax_doctor_round_report.php',
            data: {
				Round_Date: Round_Date,
				Registration_ID: Registration_ID,
				filter: "Laboratory"
			},
            cache: false,
            success: function (html) {
                $('#investigation_ordered_data').html(html);
            }
        });
    }

	function doctorplan(Registration_ID, Round_Date){
        $("#doctorplan").dialog("open");
		$.ajax({
            type: 'POST',
            url: 'ajax_doctor_round_report.php',
            data: {
				Round_Date: Round_Date,
				Registration_ID: Registration_ID,
				filter: "doctorplan"
			},
            cache: false,
            success: function (html) {
                $('#doctorplan_data_notes').html(html);
            }
        });
    }

	function btnnurseplan(Registration_ID, Round_Date){
        $("#nurseplan").dialog("open");
		$.ajax({
            type: 'POST',
            url: 'ajax_doctor_round_report.php',
            data: {
				Round_Date: Round_Date,
				Registration_ID: Registration_ID,
				filter: "nurseplan"
			},
            cache: false,
            success: function (html) {
                $('#nurseplan_data_notes').html(html);
            }
        });
    }

</script>

<script>
    function odered_radiology(Registration_ID, Round_Date)  {
        $("#odered_radiology").dialog("open");
		$.ajax({
            type: 'POST',
            url: 'ajax_doctor_round_report.php',
            data: {
				Round_Date: Round_Date,
				Registration_ID: Registration_ID,
				filter: "Radiology"
			},
            cache: false,
            success: function (html) {
                $('#radiology_ordered_data').html(html);
            }
        });
    }
</script>
<script>
    $(document).ready(function () {
        $("#odered_medicine").dialog({autoOpen: false, width: '85%', height: 400, title: 'PHARMACY ORDER', modal: true});
        $("#odered_investigation").dialog({autoOpen: false, width: '85%', height: 400, title: 'LABORATORY ORDER', modal: true});
        $("#odered_radiology").dialog({autoOpen: false, width: '85%', height: 400, title: 'RADIOLOGY ORDER', modal: true});
		$("#doctorplan").dialog({autoOpen: false, width: '85%', height: 400, title: 'DOCTOR`S PLAN', modal: true});
		$("#nurseplan").dialog({autoOpen: false, width: '85%', height: 400, title: 'NURSE`S PLAN', modal: true});
		$("#select_ward").select2();
    });
</script>
<?php
include"ajax_doctor_round_report.php";
?>
<script>
    $("select").select2();
</script>
 <script>
$(document).ready(function(){
  $("#search_table").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#round_tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script> 