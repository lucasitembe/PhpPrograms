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
<fieldset style="background-color:white;overflow-y:scroll;height:450px;">
    <?php
	$Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
                $Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
		$ageFrom=$_GET['ageFrom'];
		$ageTo=$_GET['ageTo'];
		$sponsorID=$_GET['sponsorID'];
		
		
		
		$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
					      $sponsorName=$sponsorRow['Guarantor_Name'];
    ?>
            <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>DETAILS ~ DEMOGRAPHIC REPORTS AGED BETWEEN </b><b style="color: yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color: yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y H:i:s',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y H:i:s',strtotime($Date_To));?></b> <b>FOR</b> <b style="color: yellow"><?php echo $sponsorName;?></b><b> SPONSOR</b></legend>
			<br>
        <center>
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
		<tr><td colspan="12"><hr></td></tr>
	<tr>
	    <td>
		<select name="branchID" id="branchID">
		   
                            <?php
                                         if(!isset($_GET['branchID'])){
                                            echo "<option selected='selected' value='0'>All</option>";
                                         }else{
                                            echo "<option value='0'>All</option>";  
                                         }
					    $data = mysqli_query($conn,"select * from tbl_branches");
					        while($row = mysqli_fetch_array($data)){
                                                    if(isset($_GET['branchID']) && !empty($_GET['branchID'])){
                                                        if($_GET['branchID']==$row['Branch_ID']){
                                                            echo '<option selected="selected" value="'.$row['Branch_ID'].'">'.$row['Branch_Name'].'</option>';
                                                        }  else {
                                                            echo '<option  value="'.$row['Branch_ID'].'">'.$row['Branch_Name'].'</option>';
                                                        }
                                                    }else{
					    ?>
					    <option value='<?php echo $row['Branch_ID'];?>'>
						<?php echo $row['Branch_Name']; ?>
					    </option>
					<?php
					    }
                                                }
					?>
		</select>
	    </td>
	    <td style='text-align: center; width: 15%'><b>Region</b>
		<select name='Region_ID' id='Region_ID' onchange='getDistrictsList(this.value)'>
                                       
					<?php
                                         if(!isset($_GET['Region_ID'])){
                                            echo "<option selected='selected' value='0'>All</option>";
                                         }else{
                                            echo "<option value='0'>All</option>";  
                                         }
					    $data = mysqli_query($conn,"select * from tbl_regions");
					        while($row = mysqli_fetch_array($data)){
                                                    if(isset($_GET['Region_ID']) && !empty($_GET['Region_ID'])){
                                                        if($_GET['Region_ID']==$row['Region_ID']){
                                                            echo '<option selected="selected" value="'.$row['Region_ID'].'">'.$row['Region_Name'].'</option>';
                                                        }  else {
                                                            echo '<option  value="'.$row['Region_ID'].'">'.$row['Region_Name'].'</option>';
                                                        }
                                                    }else{
					    ?>
					    <option value='<?php echo $row['Region_ID'];?>'>
						<?php echo $row['Region_Name']; ?>
					    </option>
					<?php
					    }
                                                }
					?>
                                    </select>	    
	    </td>
	    <td style='text-align: center; width: 15%'><b>District</b>
		<select name='District_ID' id='District_ID'>
		   	<?php
                                         if(!isset($_GET['District_ID'])){
                                            echo "<option selected='selected' value='0'>All</option>";
                                         }else{
                                            echo "<option value='0'>All</option>";  
                                         }
					    $data = mysqli_query($conn,"select * from tbl_district WHERE Region_ID='".$_GET['Region_ID']."'");
					        while($row = mysqli_fetch_array($data)){
                                                    if(isset($_GET['District_ID']) && !empty($_GET['District_ID'])){
                                                        if($_GET['District_ID']==$row['District_ID']){
                                                            echo '<option selected="selected" value="'.$row['District_ID'].'">'.$row['District_Name'].'</option>';
                                                        }  else {
                                                            echo '<option  value="'.$row['District_ID'].'">'.$row['District_Name'].'</option>';
                                                        }
                                                    }else{
					    ?>
					    <option value='<?php echo $row['District_ID'];?>'>
						<?php echo $row['District_Name']; ?>
					    </option>
					<?php
					    }
                                                }
					?>	    
		</select>
	    </td>
	    <td>
		From<input type="text" name="ageFrom" id="ageFrom" style="width:40px;text-align: center;background-color:#eeeeee;" required="required"/>
		To<input type="text" name="ageTo" id="ageTo" style="width:40px;text-align:center;background-color:#eeeeee;" required="required"/>
	    </td>
	    <td><input type='text' name='Date_From' id='date_From' required='required' style="background-color:#eeeeee;"></td>
	    <td><input type='text' name='Date_To' id='date_To' style="background-color:#eeeeee;" required='required'>
	    <input type="hidden" name="sponsorID" value="<?php echo $_GET['sponsorID']?>"/>
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
</form>
		<br>
		<center>
            <?php
		echo '<center><table width ="100%" border="1" id="demographicreportnew" class="display">';
    echo "<thead><tr>
                <th style='width:3%;'>SN</th>
                <th >PATIENT NAME</th>
                <th style='width:8%;'>PATIENT NO</th>
				<th style='width:6%;'> GENDER </th>
				<th style='width:15%;'>AGE</th>
                <th style='width:8%;'>PHONE NO</th>
                <th style='width:8%;'>WARD</th>
                <th style='width:8%;'>MEMBER NO</th>
                <th style='width:6%;'>CATEGORY</th>
				<th style='width:8%;'>REGION</th>
				<th style='width:7%;'>DISTRICT</th>
				<th style='width:6%;'>REG DATE</th>
         </tr></thead>";
   
		$sponsorID=$_GET['sponsorID'];
		$Date_From=$_GET['Date_From'];
		$Date_To=$_GET['Date_To'];
		$branchID=$_GET['branchID'];
		$regionID=$_GET['Region_ID'];
		$districtID=$_GET['District_ID'];
		$ageFrom=$_GET['ageFrom'];
		$ageTo=$_GET['ageTo'];
    //run the query to select all data from the database according to the branch id
   $currentDate=date('Y-m-d');
    
    if($branchID == 0){//no branch is selected
                if($regionID == 0){//if no region is selected  
                         $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci
							WHERE pr.Registration_ID=ci.Registration_ID
							AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
                }
		else{//region is selected
		    if($districtID == 0){//if no district is selectd
			 $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
            }else{//district is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND pr.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
            }
        }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_ID=ci.Registration_ID
							AND ci.Branch_ID='$branchID'
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
    }
    else{//region is selected
        if($districtID == 0){//if no district is selected
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
        }else{//district is selected
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
							AND ci.check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
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
				$Phone_Number=$row['Phone_Number'];
				$VoteNo=$row['Employee_Vote_Number'];
				$Ward=$row['Ward'];
				$Member_Number=$row['Member_Number'];
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

				$Member_Numbers=substr($Member_Number,0,2);
				if($Member_Numbers !='01'){
					$Member_Cat='Dependant';
				}else{
					$Member_Cat='Insured';
				}
                
                echo "<tr><td>".($i+1)."</td>";
                echo "<td>".ucwords(strtolower($patientName))."</td>";
                echo "<td>".$Registration_ID."</td>";
				echo "<td>".$Gender."</td>";
				echo "<td>".$age."</td>";
				echo "<td>".$Phone_Number."</td>";
				//echo "<td>".$VoteNo."</td>";
				echo "<td>".$Ward."</td>";
				echo "<td>".$Member_Number."</td>";
				echo "<td>".$Member_Cat."</td>";
				echo "<td>".$Region."</td>";
                echo "<td>".$District."</td>";
				echo "<td>".substr($Registration_Date_And_Time,0,10 )."</td>";
             //   echo "<td >".substr($Visit_Date,0,10)."</td>";
        }?>
			
    <?php }
    else{
        echo "<tr><td colspan='9' style='text-align:center'><b style='color:red'>No patient visited on the dates specified.</b></td></tr>";
    }
	    ?></table></center>
        </center>
</fieldset>
<table width="100%">
		<tr>
				<td style="text-align:right;width:30%;">
					<a href="printPatientsVisitedPreviousDaysDetailsFilter.php?sponsorID=<?php echo $sponsorID?>&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" target="_blank">
						<input type='submit' name='graph' id='Graph' class='art-button-green' value='PRINT'>
					</a>
				</td>
			</tr>
</table>

<?php
    include("./includes/footer.php");
?>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
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
<script>
   $('#demographicreportnew').dataTable({
    "bJQueryUI":true,
	});
</script>

	<!--End datetimepicker-->