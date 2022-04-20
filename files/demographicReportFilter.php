<?php
include("./includes/header.php");
include("./includes/connection.php");
if(!isset($_SESSION['userinfo'])){
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
if(isset($_SESSION['userinfo']['Reception_Works'])){
if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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
if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
<a href='./demographicReport.php?Reception=ReceptionThisPage' class='art-button-green'>
BACK
</a>
<?php  } } ?>
<script type="text/javascript" language="javascript">
function getDistrictsList(Region_ID) {
if(window.XMLHttpRequest) {
mm = new XMLHttpRequest();
}
else if(window.ActiveXObject){ 
mm = new ActiveXObject('Micrsoft.XMLHTTP');
mm.overrideMimeType('text/xml');
}

mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
mm.open('GET','getDistrictsList.php?Region_ID='+Region_ID,true);
mm.send();
}
function AJAXP() {
var data = mm.responseText;
document.getElementById('District_ID').innerHTML = data;	
}

//    function to verify NHIF STATUS
function nhifVerify(){
//code
}
</script>
<br/><br/>
<center>
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
 
 </style>   

<fieldset style="background-color:white;">
<?php
if(isset($_POST['Print_Filter'])){
//$branchID=$_POST['branchID'];
$ageFrom=$_POST['ageFrom'];
$ageTo=$_POST['ageTo'];
$Date_From=$_POST['Date_From'];
$Date_To=$_POST['Date_To'];
$regionID=$_POST['Region_ID'];
$districtID=$_POST['District_ID'];
}
if(isset($_GET[''])){
//$branchID=$_POST['branchID'];
$ageFrom=$_GET['ageFrom'];
$ageTo=$_GET['ageTo'];
$Date_From=$_GET['Date_From'];
$Date_To=$_GET['Date_To'];
$regionID=$_GET['Region_ID'];
$districtID=$_GET['District_ID'];
}

?>
<legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>SUMMARY ~ DEMOGRAPHIC REPORT (NEW VISITS) AGED BETWEEN </b><b style="color:yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color:yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y H:i:s',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y H:i:s',strtotime($Date_To));?></b></legend>
<center>

<form action='demographicReportFilter.php?DemographicReportFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<table width=100% style="border:0">
<tr><td><br></td></tr>
<tr>
<!--<td style="text-align: center"><b>Branch</b></td>-->
<td style="text-align: center; border: 1px #ccc solid; width: 15%"><b>REGION</b></td>
<td style="text-align: center; border: 1px #ccc solid; width: 15%"><b>DISTRICT</b></td>
<td style="text-align: center; border: 1px #ccc solid; width: 15%"><b>AGE</b></td>
<td style="text-align: center; border: 1px #ccc solid; width: 15%"><b>FROM</b></td>
<td style="text-align: center; border: 1px #ccc solid; width: 15%"><b>TO</b></td>

</tr>
<tr><td colspan="7"><hr></td></tr>
<tr>
<!--<td>
<select name="branchID" id="branchID">
<option selected="selected" value="0">All</option>
<?php
/*$select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
while($branchRow=mysqli_fetch_array($select_branch)){
?>
<!-- <option value="<?php echo $branchRow['Branch_ID']?>"><?php echo $branchRow['Branch_Name']?></option>-->
<?php }
*/
?>
</select>
</td>-->
<td style="text-align: center; border: 1px #ccc solid;width: 15%">
<select name='Region_ID' id='Region_ID' onchange='getDistrictsList(this.value)'>
						<option selected='selected' value='0'>All</option>
	<?php
		$data = mysqli_query($conn,"select * from tbl_regions");
			while($row = mysqli_fetch_array($data)){
		?>
		<option value='<?php echo $row['Region_ID'];?>'>
		<?php echo $row['Region_Name']; ?>
		</option>
	<?php
		}
	?>
					</select>	    
</td>
<td style="text-align: center; border: 1px #ccc solid;width: 15%">
<select name='District_ID' id='District_ID'>
<option selected='selected' value='0'>All</option>
	
					</select>	    
</select>
</td>
<td>
<b>From</b><input type="text" name="ageFrom" id="ageFrom" style="width:30%;text-align: center;background-color:#eeeeee;" required="required"/>
<b>To</b><input type="text" name="ageTo" id="ageTo" style="width: 30%;text-align:center;background-color:#eeeeee;" required="required"/>
</td>
<td style="text-align: center; border: 1px #ccc solid;width:15%;"><input type='text' name='Date_From' id='date_From' required='required' style="background-color:#eeeeee;"></td>
<td style="text-align: center; border: 1px #ccc solid;width:15%;"><input type='text' name='Date_To' id='date_To' required='required' style="background-color:#eeeeee;"></td>

<td style="text-align: center; border: 1px #ccc solid; width: 15%">
<input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
</td>
</tr>	
</table>
</form>    
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:	'now'
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
<br>

<?php
echo '<center><table width =100% border=0>';
echo "<tr>
<td style='text-align:left;width:3%;border: 1px #ccc solid;'><b>SN</b></td>
<td style='text-align:left;width:3%;border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>MALE</b></td>
<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>FEMALE</b></td>
<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>TOTAL</b></td>
</tr>";
echo "<tr><td colspan='7'><hr></td></tr>";
echo "<br>";
echo "<hr>";
echo "<tr>
<td colspan=5></td></tr>";
if(isset($_POST['Print_Filter'])){
//$branchID=$_POST['branchID'];
$ageFrom=$_POST['ageFrom'];
$ageTo=$_POST['ageTo'];
$Date_From=$_POST['Date_From'];
$Date_To=$_POST['Date_To'];
$regionID=$_POST['Region_ID'];
$districtID=$_POST['District_ID'];
}
if(isset($_GET[''])){
//$branchID=$_POST['branchID'];
$ageFrom=$_GET['ageFrom'];
$ageTo=$_GET['ageTo'];
$Date_From=$_GET['Date_From'];
$Date_To=$_GET['Date_To'];
$regionID=$_GET['Region_ID'];
$districtID=$_GET['District_ID'];
}
//The following are testing conditions to display the data according to filters
if($regionID == 0){//if no region is selected
    
     //echo 'Registration ID';exit;
	$select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
										(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr, tbl_check_in ci 
			WHERE 
                         pr.Sponsor_ID=sp.Sponsor_ID
                         AND pr.Registration_ID=ci.Registration_ID
                         AND  pr.Gender='Male'
		         AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
											AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
										) as male,
										(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr, tbl_check_in ci 
			WHERE pr.Sponsor_ID=sp.Sponsor_ID
                        AND pr.Registration_ID=ci.Registration_ID
                        AND  pr.Gender='Female'
				AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
											AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
										) as female
									FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
			) or die(mysqli_error($conn));
}
else{//region is selected
if($districtID == 0){//if no district is selectd
$select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
									(
									SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r, tbl_check_in ci 
									WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                                                        AND pr.Registration_ID=ci.Registration_ID
                                                                        AND  pr.Gender='Male'
										AND pr.District_ID=d.District_ID
										AND d.Region_ID=r.Region_ID
										AND d.Region_ID='$regionID'
										AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
			AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									) as male,
									(
									SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r, tbl_check_in ci 
									WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                                                        AND pr.Registration_ID=ci.Registration_ID
                                                                        AND  pr.Gender='Female'
										AND pr.District_ID=d.District_ID
										AND d.Region_ID=r.Region_ID
										AND d.Region_ID='$regionID'
										AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
			AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									) as female
								FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
		) or die(mysqli_error($conn));               
}else{//district is selected
$select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
									(
									SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r, tbl_check_in ci 
									WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                                                        AND pr.Registration_ID=ci.Registration_ID
                                                                        AND pr.Gender='Male'
										AND pr.District_ID=d.District_ID
										AND d.Region_ID=r.Region_ID
										AND d.Region_ID='$regionID'
			AND d.District_ID='$districtID'
										AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
			AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									) as male,
									(
									SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r,tbl_check_in ci 
									WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                                                        AND pr.Registration_ID=ci.Registration_ID
                                                                        AND  pr.Gender='Female'
										AND pr.District_ID=d.District_ID
										AND d.Region_ID=r.Region_ID
										AND d.Region_ID='$regionID'
			AND d.District_ID='$districtID'
										AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
			AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									) as female
								FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
		) or die(mysqli_error($conn));               
}
}




//$Date_From=date('Y-m-d',strtotime($Date_From));
//$Date_To=date('Y-m-d',strtotime($Date_To));

$total_Male=0;
$total_Female=0;
$res=mysqli_num_rows($select_demograph);
for($i=0;$i<$res;$i++){
$row=mysqli_fetch_array($select_demograph);
//return rows
$sponsorID=$row['Sponsor_ID'];
$sponsorName=$row['Guarantor_Name'];
$male=$row['male'];
$female=$row['female'];
echo "<tr><td style='text-align:left;width:3%;border: 1px #ccc solid;'>".($i+1)."</td>";
echo "<td style='text-align:left;width:3%;border: 1px #ccc solid;'><a href='demographicFilterSponsorDetails.php?sponsorID=$sponsorID&Region_ID=$regionID&District_ID=$districtID&ageFrom=$ageFrom&ageTo=$ageTo&Date_From=$Date_From&Date_To=$Date_To&SponsorDetails=SponsorDetailsThisPage'>".$row['Guarantor_Name']."</a></td>";
$total_Male=$total_Male + $male;
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'>".number_format($male)."</td>";
$total_Female=$total_Female + $female;
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'>".number_format($female)."</td>";
$total=$male+$female;
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'>".number_format($total)."</td>";
}//end for loop
echo "<tr><td colspan=5><hr></td></tr>";
echo "<tr><td colspan=2 style='text-align:right;width:3%;border: 1px #ccc solid;'><b>&nbsp;&nbsp;TOTAL</b></td>";
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>".number_format($total_Male)."</b></td>";
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>".number_format($total_Female)."</b></td>";
$total_Male_Female=$total_Male+$total_Female;
echo "<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>".number_format($total_Male_Female)."</b></td></tr>";
?>
</table></center>
</center>
</fieldset>

	<table width="100%" style="twxt-align:right;">
		<tr>
				<td style='text-align:right;width:100%;'>
					<a href="demographicFilterMultipleBarChart.php?Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
						<input type='submit' name='graph' id='praph' class='art-button-green' value='GRAPHS'>
					</a>
				</td>
				
				<!-- <form action="sponsorPreview.php?DemographicPage=ThisPage" method="POST">-->
 
				<td style='text-align:right;width:100%;'>
					<a href="demographicpdfreport.php?Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" class="art-button-green" target="_blank">
						PREVIEW ALL
					</a>
				</td>
	
				<!-- </form> -->
			</tr>
	</table>
<?php
include("./includes/footer.php");
?>