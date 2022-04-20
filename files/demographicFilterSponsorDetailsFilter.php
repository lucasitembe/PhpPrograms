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
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
 
 </style> 

<br/>
<br/>

<center>
    
<fieldset style="background-color:white;">
<br>

    <center>
	<form action='demographicFilterSponsorDetailsFilter.php?DemographicReportFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <!--<td style="text-align: center"><b>Branch</b></td>-->
	    <td style="text-align: center"><b>REGION</b></td>
	    <td style="text-align: center"><b>DISTRICT</b></td>
	    <td style="text-align: center"><b>AGE</b></td>
            <td style="text-align: center"><b>FROM</b></td>
            <td style="text-align: center"><b>TO</b></td>
	    <td style="text-align: center"><b>GENDER</b></td>
            
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
	    <td style='text-align: center; width: 15%'><b>Region</b>
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
	    <td style='text-align: center; width: 15%'><b>District</b>
		<select name='District_ID' id='District_ID'>
		    <option selected='selected' value='0'>All</option>
					
                                    </select>	    
		</select>
	    </td>
	    <td style="text-align: center">
		<b>From</b><input type="text" name="ageFrom" id="ageFrom" style="width: 100px;text-align:center;background-color:#eeeeee;" autocomplete="off" required="required"/>
		<b>To</b><input type="text" name="ageTo" id="ageTo" style="width:100px;text-align: center;background-color:#eeeeee;" autocomplete="off" required="required"/>
	    </td>
	    <td><input type='text' name='Date_From' id='date_From' autocomplete="off" style="background-color:#eeeeee;" required='required'></td>
	    <td><input type='text' name='Date_To' id='date_To' autocomplete="off" style="background-color:#eeeeee;" required='required'>
            <input type="hidden" name="sponsorID" value="<?php echo $sponsorID=$_POST['sponsorID'];?>"/>
            </td>
	    <td>
		<select name="gender" id="gender">
		    <option selected="selected" value="All">All</option>
		    <option value="Male">Male</option>
		    <option value="Female">Female</option>
		</select>
	    </td>
		<td style='text-align: center;'>
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
    </center>
    <br>
    <?php
    
	if(isset($_POST['sponsorID'])){
	    $regionID=$_POST['Region_ID'];
	    $District_ID=$_POST['District_ID'];
            $ageFrom=$_POST['ageFrom'];
            $ageTo=$_POST['ageTo'];
            $Date_From=date('Y-m-d',strtotime($_POST['Date_From']));
            $Date_To=date('Y-m-d',strtotime($_POST['Date_To']));
	    $sponsorID=$_POST['sponsorID'];
	    $gender=$_POST['gender'];
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
             <legend align=center style="background-color:#006400;color:white;padding:5px;"><b>DEMOGRAPHIC REPORT FOR PATIENTS AGED BETWEEN </b><b style="color: yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color: yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y',strtotime($Date_To));?></b><b> FOR <?php echo "<b style='color: yellow'>".strtoupper($sponsorName)."</b>"?> SPONSOR</b></legend>
            <iframe src="demographicFilterSponsorDetailsFilter_Iframe.php?Region_ID=<?php echo $regionID?>&District_ID=<?php echo $District_ID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>&gender=<?php echo $gender?>&sponsorID=<?php echo $sponsorID?>" width="100%" height="340px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>