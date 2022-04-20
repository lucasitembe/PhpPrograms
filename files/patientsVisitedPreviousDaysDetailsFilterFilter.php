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

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
 
 </style> 
<fieldset style="background-color:white;overflow-y:scroll;height:450px;">
    <?php
                $Date_From=date('Y-m-d H:i:s',strtotime($_POST['Date_From']));
                $Date_To=date('Y-m-d H:i:s',strtotime($_POST['Date_To']));
		$ageFrom=$_POST['ageFrom'];
		$ageTo=$_POST['ageTo'];
		$sponsorID=$_POST['sponsorID'];
		
		
		
		$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
					      $sponsorName=$sponsorRow['Guarantor_Name'];
    ?>
            <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS VISIT AGED BETWEEN </b><b style="color: yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color: yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y H:i:s',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y H:i:s',strtotime($Date_To));?></b> <b>FOR</b> <b style="color: yellow"><?php echo $sponsorName;?></b><b> SPONSOR</b></legend>
        <center>
<br>
    <form action='patientsVisitedPreviousDaysDetailsFilterFilter.php?VisitedPatientsFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <td style="text-align: center"><b>BRANCH</b></td>
	    <td style="text-align: center"><b>REGION</b></td>
	    <td style="text-align: center"><b>DISTRICT</b></td>
	    <td style="text-align: center"><b>AGE</b></td>
            <td style="text-align: center"><b>FROM</b></td>
            <td style="text-align: center"><b>TO</b></td>
            
        </tr>
		<tr><td colspan="7"><hr></td></tr>
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
	    <td>
		From<input type="text" name="ageFrom" id="ageFrom" style="width: 40px;text-align: center;background-color:#eeeeee;" required="required"/>
		To<input type="text" name="ageTo" id="ageTo" style="width: 40px;text-align:center;background-color:#eeeeee;" required="required"/>
	    </td>
	    <td><input type='text' name='Date_From' id='date_From' style="background-color:#eeeeee;" required='required'></td>
	    <td><input type='text' name='Date_To' id='date_To' style="background-color:#eeeeee;" required='required'>
	    <input type="hidden" name="sponsorID" value="<?php echo $_POST['sponsorID']?>"/>
	    </td>
		<td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
           </td>
	</tr>	
    </table>
    </form>    
</center>
</form>
</center>
<br/><br/>

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
	
	<center>
            <?php
		echo '<center><table width =100% border=0>';
    echo "<tr>
                 <td style='width:3%;'><b>SN</b></td>
                <td ><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td style='width:8%;'><b>PATIENT NO</b></td>
				<td style='width:8%;'><b>GENDER</b></td>
				<td style='width:20%;'><b>AGE</b></td>
                <td style='width:12%;'><b>REGION</b></td>
				<td style='width:9%;'><b>DISTRICT</b></td>
				<td style='width:10%;'><b>REG DATE</b></td>
               
         </tr>";
    echo "<tr>
                <td colspan=8	><hr></td></tr>";
		$sponsorID=$_POST['sponsorID'];
                $Date_From=$_POST['Date_From'];
                $Date_To=$_POST['Date_To'];
		$branchID=$_POST['branchID'];
		$regionID=$_POST['Region_ID'];
		$districtID=$_POST['District_ID'];
		$ageFrom=$_POST['ageFrom'];
		$ageTo=$_POST['ageTo'];
    //run the query to select all data from the database according to the branch id
   $currentDate=date('Y-m-d');
    
    if($branchID == 0){//no branch is selected
                if($regionID == 0){//if no region is selected  
                         $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND .ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));               
                }
		else{//region is selected
		    if($districtID == 0){//if no district is selectd
			 $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)  ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));
            }else{//district is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND pr.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));               
            }
        }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Branch_ID='$branchID'
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));               
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));               
        }else{//district is selected
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,pr.Registration_ID,Gender ASC
							") or die(mysqli_error($conn));               
        }//end else district is selected
     }//end else region is selected   
}//else if branch is selected
    
   
    if(mysqli_num_rows($select_patient) > 0){
            $res=mysqli_num_rows($select_patient);
            for($i=0;$i<$res;$i++){
                $row=mysqli_fetch_array($select_patient);
                //return rows
                $registration_ID=$row['Registration_ID'];
                $patientName=$row['Patient_Name'];
                $Registration_ID=$row['Registration_ID'];
                $Region=$row['Region'];
                $District=$row['District'];
                $Gender=$row['Gender'];
                $dob=$row['Date_Of_Birth'];
                $Registration_Date_And_Time=$row['Registration_Date_And_Time'];
                $Visit_Date=$row['Check_In_Date_And_Time'];
                //these codes are here to determine the age of the patient
                $date1 = new DateTime(date('Y-m-d'));
                $date2 = new DateTime($dob);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                
                echo "<tr><td>".($i+1)."</td>";
                echo "<td >".ucwords(strtolower($patientName))."</td>";
                echo "<td >".$Registration_ID."</td>";
				 echo "<td >".$Gender."</td>";
                echo "<td >".$age."</td>";
                echo "<td >".$Region."</td>";
                echo "<td >".$District."</td>";
               
                echo "<td >".substr($Registration_Date_And_Time,0,10)."</td>";
            //    echo "<td style='text-align:left; width:10%'>".$Visit_Date."</td>";
        }
//        echo "<form action='printPatientsVisitedPreviousDays.php?PrintPatientsVisitedPreviousThisPage' method='POST'>
//		<tr>
//		    <td style='border:0'><input type='submit' name='printPatientsVisitedToday' value='Print'/></td>
//		</tr>
//	    </form>";
    }
    else{
        echo "<tr><td colspan='9' style='text-align:center'><b style='color:red'>No patient visited on the dates specified.</b></td></tr>";
    }
	    ?></table></center>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>