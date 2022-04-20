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
	<legend align="right" style="background-color:#006400;color:white;padding:5px;"><b> RADIOLOGY PATIENT REPORT </b></legend>
		<script language="javascript" type="text/javascript">
			function searchPatient(Patient_Name=''){
				var DateFrom = document.getElementById('date_From').value;
				var DateTo = document.getElementById('date_To').value;
				var Sub_Department_ID="<?php echo $Sub_Department_ID;?>";
				var PatientType="<?php echo $PatientType;?>";
				var Search_Iframe = document.getElementById('Search_Iframe');
				Search_Iframe.innerHTML = "<iframe width='100%' height=350px src='Radiology_Report_Iframe.php?PatientType="+PatientType+"&Patient_Name="+Patient_Name+"&DateFrom="+DateFrom+"&DateTo="+DateTo+"'></iframe>";
			}
		</script>
		
	<center>
		<table  class="hiv_table" style="width:98%;margin-top:5px;">
			<tr>
				
				<td style="text-align:right;width:80px"><b>Date From<b></td>
				<td width="150px"><input type='text' name='Date_From' id='date_From' required="required" autocomplete="off" ></td>
				
				<td style="text-align:right;width:80px"><b>Date To<b></td>
				<td width="150px"><input type='text' name='Date_To' id='date_To' autocomplete="off" required="required"></td>
				
				<td><input type="submit" name="submit" value="Filter" onclick='searchPatient()' class="art-button-green"/></td>
				<td width=''>
				<input type='text' name='Search_Patient' id='Search_Patient' oninput='searchPatient(this.value)'
				 placeholder='~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~' />
				</td>
			</tr>
		</table>
	</center>

	<center>
		<div style="width:100%;" id="ListTable">
			<table  class="hiv_table" style="width:100%;">
				<tr>
					<td id='Search_Iframe' >
						<!-- src='search_patient_radiology_list_iframe.php' -->
						<iframe id="PatientListIframe" width='100%' height=350px src='Radiology_Report_Iframe.php'></iframe>
					</td>
				</tr>
			</table>
		</div>
	</center>
</fieldset>

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