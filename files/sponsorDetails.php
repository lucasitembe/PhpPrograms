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

<fieldset>
    <?php
	if(isset($_GET['sponsorID'])){
	    $sponsorID=$_GET['sponsorID'];
	}else{
	    $sponsorID=0;
	}
    ?>
    <center>
    <form action='sponsorDetailsFilter.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <br>
    <table width=100% style="border: 0;">
        <tr>
	    <td style="text-align: center;border: 1px #ccc solid;"><b>Region</b></td>
	    <td style="text-align: center;border: 1px #ccc solid;"><b>District</b></td>
	    <td style="text-align: center;border: 1px #ccc solid;"><b>Age</b></td>
            <td style="text-align: center;border: 1px #ccc solid;"><b>From </b></td>
            <td style="text-align: center;border: 1px #ccc solid;"><b>To</b></td>
	    <td style="text-align: center;border: 1px #ccc solid;"><b>Gender</b></td>
            <td style='text-align: center;border: 1px #ccc solid;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>
	<tr>
	    <td style='text-align: center; width: 15%;border: 1px #ccc solid;'>
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
	    <td style='text-align: center; width: 15%;border: 1px #ccc solid;'>
		<select name='District_ID' id='District_ID'>
		    <option selected='selected' value='0'>All</option>
					
                                    </select>	    
		</select>
	    </td>
	    <td style="border: 1px #ccc solid;text-align: center;">
		From&nbsp;<input type="text" name="ageFrom" id="ageFrom" style="width: 30%;text-align: center" required="required"/>&nbsp&nbsp
		To&nbsp;<input type="text" name="ageTo" id="ageTo" style="width: 30%;text-align: center;" required="required"/>
	    </td>
	    <td style="border: 1px #ccc solid"><input type='text' name='Date_From' id='date_From' required='required'></td>
	    <td style="border: 1px #ccc solid">
		<input type='text' name='Date_To' id='date_To' required='required'>
		<input type='hidden' name='sponsorID' id='sponsorID' value="<?php echo $sponsorID; ?>">
	    </td>
	    <td style="border: 1px #ccc solid;text-align: center;">
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
    
</center>
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
    <?php
	if(isset($_GET['sponsorID'])){
	    $sponsorID=$_GET['sponsorID'];
	$select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}else{
	    $sponsorID=0;
	    $select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}
    ?>
    <br>
            <legend align=center><b>DEMOGRAPHIC REPORT FOR <?php echo "<b style='color: blue'>".strtoupper($sponsorName)."</b>"?> SPONSOR</b></legend>
		<br>
		<iframe src="sponsorDetails_Iframe.php?sponsorID=<?php echo $sponsorID?>" width="100%" height="360px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>