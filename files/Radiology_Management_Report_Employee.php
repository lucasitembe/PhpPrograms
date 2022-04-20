<?php
include("includes/header.php");
include("includes/connection.php");

if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }
if(isset($_GET['Item_ID'])) { $Item_ID = $_GET['Item_ID']; } else { $Item_ID = ''; }
if(isset($_GET['Registration_ID'])) { $Registration_ID = $_GET['Registration_ID']; } else { $Registration_ID = ''; }

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
 <a href='Radiology_Management_Report.php' class='art-button-green'> BACK </a>
 <?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>
<br><br>

<fieldset style='margin-top:10px;'>
	<legend align="center"><b> RADIOLOGY PATIENT REPORT </b></legend>
	<?php
		$Test_Name = '';
		$select_item = mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID = '0'");
		while($item = mysqli_fetch_assoc($select_item)){
			$Test_Name = $item['Product_Name'];
		}
	?>	
	<center>
	<strong style="text-transform:uppercase;"><?php echo "TEST NAME: ". $Test_Name; ?></strong>
		<div style="width:100%;" id="ListTable">
			<table  class="hiv_table" style="width:100%;">
				<tr>
					<td id='Search_Iframe' >
						<!-- src='search_patient_radiology_list_iframe.php' -->
						<iframe frameborder="0" id="PatientListIframe" width='100%' height=350px src='Radiology_Management_Report_Iframe_Employee.php?Item_ID=<?php echo $Item_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?><?php if($Sponsor != '') echo "&Sponsor=".$Sponsor; ?>'></iframe>
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