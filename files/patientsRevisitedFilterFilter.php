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
    <a href='./revisitedPatients.php?Reception=ReceptionThisPage' class='art-button-green'>
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
    <form action='patientsRevisitedFilterFilter.php.php?PatientsVisitedPreviousDaysThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=60%>
        <tr>
            <td>From</td><td><input type='text' name='Date_From' id='date_From' required='required'></td> 
            <td>To</td><td><input type='text' name='Date_To' id='date_To' required='required'></td>
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
		<input type="hidden" name="sponsorID" value="<?php echo $_POST['sponsorID']?>"/>
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
<fieldset>
    <?php
         $Date_From=date('Y-m-d',strtotime($_POST['Date_From']));
         $Date_To=date('Y-m-d',strtotime($_POST['Date_To']));
    ?>
            <legend align=center><b>REVISITED PATIENTS FROM </b><b style='color: blue;'><?php echo date('j F, Y',strtotime($Date_From))?></b><b> TO </b><b style='color: blue;'><?php echo date('j F, Y',strtotime($Date_To))?></b></legend>
        <center>
            <?php
		echo '<center><table width =100% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NUMBER</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
		<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
		<td style='text-align: left;' width=10%><b>GENDER</b></td>
		<td style='text-align: left;' width=10%><b>AGE</b></td>
		<td style='text-align: left;' width=10%><b>REGISTRATION DATE</b></td>
                <td style='text-align: left;' width=10%><b>CHECK IN DATE</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
		$sponsorID=$_POST['sponsorID'];
    //run the query to select all data from the database according to the branch id
   $currentDate=date('Y-m-d');
    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
				    WHERE pr.Registration_Date <> ci.Check_In_Date
                                    AND pr.Registration_ID=ci.Registration_ID
				    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
				    AND pr.Sponsor_ID='$sponsorID'
				    ") or die(mysqli_error($conn));
    
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
                $Visit_Date=$row['Visit_Date'];
                //these codes are here to determine the age of the patient
                $date1 = new DateTime(date('Y-m-d'));
                $date2 = new DateTime($dob);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                
                echo "<tr><td>".($i+1)."</td>";
                echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
                echo "<td style='text-align:left; width:10%'>".$Registration_ID."</td>";
                echo "<td style='text-align:left; width:10%'>".$Region."</td>";
                echo "<td style='text-align:left; width:10%'>".$District."</td>";
                echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
                echo "<td style='text-align:left; width:15%'>".$age."</td>";
                echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
                echo "<td style='text-align:left; width:10%'>".$Visit_Date."</td>";
        }
        echo "<form action='printPatientsRevisited.php?&sponsorID=$sponsorID&PrintPatientsRevisitedThisPage' method='POST'>
		<tr>
		    <td style='border:0'><input type='submit' name='printPatientsVisitedToday' value='Print'/></td>
		</tr>
	    </form>";
    }
    else{
        echo "<tr><td colspan='9' style='text-align:center'><b style='color:red'>No patient visited  on previous days</b></td></tr>";
    }
	    ?></table></center>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>