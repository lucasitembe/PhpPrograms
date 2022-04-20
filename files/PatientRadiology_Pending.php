<?php
include("includes/header.php");
include("includes/connection.php");

if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }

?>

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
?>
 <a href='radiologyworkspage.php'class='art-button-green'> BACK </a>
 <?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>
<br><br>

<fieldset style='margin-top:10px;'>
	<legend align="center"><b>  ALL RADIOLOGY CONSULTED PATIENT LIST <?php if($PatientType == 'in'){ ?> - INPATIENTS <?php } else { ?> - OUTPATIENTS <?php } ?></b></legend>
		<script language="javascript" type="text/javascript">
			function searchPatient(Patient_Name=''){
				var DateFrom = document.getElementById('date_From').value;
				var DateTo = document.getElementById('date_To').value;
				var Sub_Department_ID="<?php echo $Sub_Department_ID;?>";
				var PatientType="<?php echo $PatientType;?>";
				var Search_Iframe = document.getElementById('Search_Iframe');
				Search_Iframe.innerHTML = "<iframe width='100%' height=350px src='RadiologyPatientsList_Pending.php?PatientType="+PatientType+"&Sub_Department_ID="+Sub_Department_ID+"&Patient_Name="+Patient_Name+"&DateFrom="+DateFrom+"&DateTo="+DateTo+"'></iframe>";
			}
		</script>
		
	<center>
		<table  class="hiv_table" style="width:98%;margin-top:5px;">
			<tr>

				<?php 
					isset($_SESSION['Radiology_Sub_Dep_ID']) ? $RSI = $_SESSION['Radiology_Sub_Dep_ID'] : $RSI = '';
					//echo $_SESSION['Radiology_Sub_Dep_ID'];
				?>
				<td width="150px">
					<?php
						$select_subdep = "
							SELECT *
								FROM
								tbl_item_subcategory isc,
								tbl_item_category ic
									WHERE 
									isc.Item_category_ID = ic.Item_category_ID AND
									ic.Category_Type = 'Radiology'
						";
						
						$select_subdep_qry = mysqli_query($conn,$select_subdep) or die(mysqli_error($conn));
						$count = mysqli_num_rows($select_subdep_qry);
						//echo $count;
					?>
					<select style='height:28px; width:190px; padding-left:8px;' id="ChangeSubDep" onChange="ChangeSubDep(this.value)">
						<option value=''>SELECT SUB DEPARTMENT</option>
						<?php 
							while($subdep = mysqli_fetch_assoc($select_subdep_qry)){ 
								$Subdep_ID = $subdep['Item_Subcategory_ID'];
								$Subdep_Name = $subdep['Item_Subcategory_Name'];
						?>
							<option <?php if($RSI == $Subdep_ID) echo "selected=selected"; ?> value='RadiologySubDepSession.php?Radiology_Sub_Dep_ID=<?php echo $Subdep_ID; ?>'><?php echo $Subdep_Name; ?></option>
						<?php } ?>
						<option <?php if($RSI == 0) echo "selected=selected"; ?> value='RadiologySubDepSession.php?Radiology_Sub_Dep_ID=0'>OTHERS</option>
					</select>
					
					<script>
						function ChangeSubDep(url){
							if(url){
								window.location = url; // redirect
							}
						}					
					</script>					
					
				</td>
			
				<td style="text-align:right;width:80px"><b>Date From<b></td>
				<td width="150px"><input type='text' name='Date_From' id='date_From' required="required" ></td>
				
				<td style="text-align:right;width:80px"><b>Date To<b></td>
				<td width="150px"><input type='text' name='Date_To' id='date_To' required="required"></td>
				
				<td><input type="submit" name="submit" value="Filter" onclick='searchPatient()' class="art-button-green"/></td>
				<td width='40%'>
				<input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'
				onkeyup='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~' />
				</td>
			</tr>
		</table>
	</center>

	<center>
		<table  class="hiv_table" style="width:100%">
			<tr>
				<td id='Search_Iframe'>
					<!-- src='search_patient_radiology_list_iframe.php' -->
					<iframe id="PatientListIframe" width='100%' height=350px src='RadiologyPatientsList_Pending.php?Sub_Department_ID=<?php echo $Sub_Department_ID?>&PatientType=<?php echo $PatientType; ?>'></iframe>
				</td>
			</tr>
		</table>
	</center>
</fieldset>

<!-- 
<br/>
<div style="float:right;">
 <a href='#' class='art-button-green'> CONSULTED PATIENTS </a>
 <a href='#' class='art-button-green'> PENDING TESTS </a>
</div> 
-->

<?php
include("./includes/footer.php");
?>
    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
		$('#date_From').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
		startDate:  'now'
		});
		$('#date_From').datetimepicker({value:'',step:30});
		$('#date_To').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
		startDate:'now'
		});
		$('#date_To').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->