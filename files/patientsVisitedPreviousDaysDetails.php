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
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./visitedPatients.php?Reception=ReceptionThisPage' class='art-button-green'>
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
<fieldset>
    <?php
	$sponsorID=$_GET['sponsorID'];
		
		
		
		$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
					      $sponsorName=$sponsorRow['Guarantor_Name'];
    ?>
            <legend align=center><b>PATIENTS VISITED FOR</b> <b style="color: blue"><?php echo $sponsorName;?></b><b>SPONSOR</b></legend>
        <center>
	    <form action='patientsVisitedPreviousDaysFilter.php?VisitedPatientsFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <td style="text-align: center"><b>Branch</b></td>
	    <td style="text-align: center"><b>Region</b></td>
	    <td style="text-align: center"><b>District</b></td>
	    <td style="text-align: center"><b>Age</b></td>
            <td style="text-align: center"><b>From</b></td>
            <td style="text-align: center"><b>To</b></td>
	    <td style="text-align: center"><b>Gender</b></td>
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>
	<tr>
	    <td>
		<select name="branchID" id="branchID">
		    <option selected="selected" value="0">All</option>
		    <?php
			$select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
			while($branchRow=mysqli_fetch_array($select_branch)){
			    ?>
			    <option value="<?php echo $branchRow['Branch_ID']?>"><?php echo $branchRow['Branch_Name']?></option>
			<?php }
			
		    ?>
		</select>
	    </td>
	    <td style='text-align: center; width: 15%'>
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
	    <td style='text-align: center; width: 15%'>
		<select name='District_ID' id='District_ID'>
		    <option selected='selected' value='0'>All</option>
					
                                    </select>	    
		</select>
	    </td>
	    <td>
		From<input type="text" name="ageFrom" id="ageFrom" style="width: 30%;text-align: center" required="required"/>
		To<input type="text" name="ageTo" id="ageTo" style="width: 30%;text-align: center" required="required"/>
	    </td>
	    <td><input type='text' name='Date_From' id='date_From' required='required'></td>
	    <td><input type='text' name='Date_To' id='date_To' required='required'>
	    <input type='hidden' name='sponsorID' id='sponsorID' value="<?php echo $_GET['sponsorID']?>">
	    </td>
	    <td>
		<select name="gender" id="gender">
		    <option selected="selected" value="All">All</option>
		    <option value="Male">Male</option>
		    <option value="Female">Female</option>
		</select>
	    </td>
	</tr>	
    </table>
    </form>
</center>
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
		$sponsorID=$_GET['sponsorID'];
	    ?>
           <iframe src="patientsVisitedPreviousDaysDetails_Iframe.php?sponsorID=<?php echo $sponsorID?>" width="100%" height="340px"></iframe> 
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>