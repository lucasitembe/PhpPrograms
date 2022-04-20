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
	isset($_SESSION['RadiologyReportSponsor']) ? $Sponsor = $_SESSION['RadiologyReportSponsor'] : $Sponsor = '';
	//$Sponsor = 'NHIF';

?>
 <a href='radiologyworkspage.php'class='art-button-green'> BACK </a>
 <?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>
<br><br>

<fieldset style='margin-top:10px;'>
	<legend align="center"><b> RADIOLOGY PATIENT REPORT </b></legend>
		<script language="javascript" type="text/javascript">
			function searchPatient(Product_Name=''){
				var DateFrom = document.getElementById('date_From').value;
				var DateTo = document.getElementById('date_To').value;
				var Sub_Department_ID="<?php echo $Sub_Department_ID;?>";
				var PatientType="<?php echo $PatientType;?>";
				var Sponsor="<?php echo $Sponsor;?>";
				var Search_Iframe = document.getElementById('Search_Iframe');
				Search_Iframe.innerHTML = "<iframe width='100%' height=350px src='Radiology_Management_Report_Iframe.php?PatientType="+PatientType+"&Product_Name="+Product_Name+"&DateFrom="+DateFrom+"&DateTo="+DateTo+"&Sponsor="+Sponsor+"'></iframe>";
			}
		</script>
		
	<center>
		<table  class="hiv_table" style="width:98%;margin-top:5px;">
			<tr>
				
				<td><b>SPONSOR</b></td>
				<td>
					<?php
						$select_sponsors = "SELECT Guarantor_Name, Sponsor_ID FROM tbl_sponsor";
						
						$select_sponsors_qry = mysqli_query($conn,$select_sponsors) or die(mysqli_error($conn));
						$count = mysqli_num_rows($select_sponsors_qry);
					?>
					<select style='height:28px; width:190px; padding-left:8px;' id="ChangeSponsor" onChange="ChangeSponsor(this.value)">
						<option value=''>SELECT SPONSOR</option>
						<?php 
							while($sp = mysqli_fetch_assoc($select_sponsors_qry)){ 
								$Sponsor_ID = $sp['Sponsor_ID'];
								$Sponsor_Name = $sp['Guarantor_Name'];
						?>
							<option <?php if($Sponsor == $Sponsor_Name) echo "selected=selected"; ?> value='RadiologyReportSponsorSession.php?Sponsor=<?php echo $Sponsor_Name; ?>'><?php echo $Sponsor_Name; ?></option>
						<?php } ?>
						<option <?php if($Sponsor_ID == '') echo "selected=selected"; ?> value='RadiologyReportSponsorSession.php?Sponsor= '>ALL SPONSORS</option>
					</select>
					
					<script>
						function ChangeSponsor(url){
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
				<td width=''>
				<input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)'
				onkeyup='searchPatient(this.value)' placeholder='Search By Test Name' style='padding-left:12px;'/>
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
						<iframe id="PatientListIframe" width='100%' height=350px src='Radiology_Management_Report_Iframe.php<?php if($Sponsor != '') echo "?Sponsor=".$Sponsor; ?>'></iframe>
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