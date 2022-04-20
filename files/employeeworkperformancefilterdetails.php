<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='./employeeworkperformancesummary.php?EmployeeWork=EmployeeWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>

<?php
    if(isset($_GET['Date_From'])){
	$Date_From = $_GET['Date_From'];
    }else{
	$Date_From = '';
    }
    
    if(isset($_GET['Date_To'])){
	$Date_To = $_GET['Date_To'];
    }else{
	$Date_To = '';
    }
    
    if(isset($_GET['Employee_ID'])){
	$doctorID = $_GET['Employee_ID'];
	$employeeID = $_GET['Employee_ID'];
    }else{
	$doctorID = 0;
	$employeeID = 0;
    }
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
    
?>


    <center>
    <table width=60% align="center";>
    <tr>
	<td style="text-align: center"><b>Branch</b></td>
	<td style="text-align: center">
	    <select name="branchID" id="branchID">
		<option value="All">All</option>
		<?php
		    $select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
		    while($select_branch_row=mysqli_fetch_array($select_branch)){
			$branchID=$select_branch_row['Branch_ID'];
			$branchName=$select_branch_row['Branch_Name'];
			echo "<option value='$branchID'>$branchName</option>";
		    }
		?>
	    </select>  
	</td>
	<td style="text-align: center"><b>From</b></td>
	<td style="text-align: center">
	    <input type='text' name='Date_From' id='date_From' required='required' value="<?php echo $Date_From; ?>" readonly='readonly'>    
	</td>
	<td style="text-align: center">To</td>
	<td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required' value="<?php echo $Date_To; ?>"  readonly='readonly'></td>    
	<td style='text-align: center;'>
	    <input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick='getPerformanceDetails()'>
		<input type='hidden' name='Employee_ID' value='<?php echo $doctorID?>'/>
	</td>
    </tr>	
    </table>
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
        $doctorID=$_GET['Employee_ID'];
        $select_doctor=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$doctorID'");
        $row=mysqli_fetch_array($select_doctor);
        $doctorName=$row['Employee_Name'];
    ?>
    
    <fieldset style='overflow-y: scroll; height: 440px;' id='Details_Area'>
    <legend style='background-color:#006400;color:white;padding:5px;' align=center><b>EMPLOYEE PERFORMANCE DETAILS. ~ </b><b style="color:yellow;"><?php echo strtoupper($doctorName)?></b></legend>
	<table width =100% id="performancedetails" class="display">
	    <thead> <tr>
		<th style='width:5%;'>SN</th>
		<th >PATIENT NAME</th>
		<th >PATIENT NO</th>
		<th >GENDER</th>
		<th >AGE</th>
		<th >PHONE NO</th>
		<th >CHECK IN DATE AND TIME</th>
	    </tr></thead>

	
<?php	
	$select_employee_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE emp.Employee_ID='$employeeID'");
	//return employee details
	while($select_employee_query_row=mysqli_fetch_array($select_employee_query)){
	    //return data
	    $employeeID = $select_employee_query_row['Employee_ID'];
	    $Employee_Name=$select_employee_query_row['Employee_Name'];
	    
	    //run the query to select patients from the check in table
	    $select_patient_list=mysqli_query($conn,"SELECT * FROM tbl_check_in ci,tbl_patient_registration pr,tbl_employee emp
						WHERE ci.Registration_ID=pr.Registration_ID
						AND ci.Employee_ID=emp.Employee_ID
						AND ci.Employee_ID='$employeeID'
						AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						");
	    //loop to find details
	    $PatientSN=1;
	    while($select_patient_list_row=mysqli_fetch_array($select_patient_list)){
		//AGE FUNCTION
		$age = floor( (strtotime(date('Y-m-d')) - strtotime($select_patient_list_row['Date_Of_Birth'])) / 31556926)." Years";
		// if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($select_patient_list_row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
			
		//return data
		$Registration_ID=$select_patient_list_row['Registration_ID'];
		$Gender=$select_patient_list_row['Gender'];
		$Phone_Number=$select_patient_list_row['Phone_Number'];
		$employeeID=$select_patient_list_row['Employee_ID'];
		$Patient_Name=$select_patient_list_row['Patient_Name'];
		$Check_In_Date_And_Time=$select_patient_list_row['Check_In_Date_And_Time'];
		
		//display data
		echo "<tr><td id='thead'>".($PatientSN)."</td>";
	   
		echo "<td>".ucwords(strtolower($Patient_Name))."</td>";
		echo "<td>".$Registration_ID."</td>";
		echo "<td>".$Gender."</td>";
		echo "<td>".$age."</td>";
		echo "<td>".$Phone_Number."</td>";
		echo "<td>".$Check_In_Date_And_Time."</td>";
		echo "</tr>";
		$PatientSN++;
	    }
	}
?>
</table>
<!--<iframe src="employeeworkperformancefilterdetails_Iframe.php?Employee_ID=<?php echo $doctorID?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" width="100%" height="380px"></iframe>-->
	
</fieldset>

<script>
    function getPerformanceDetails(){
	var Date_From = document.getElementById("date_From").value;
	var Date_To = document.getElementById("date_To").value;
	var branchID = document.getElementById("branchID").value;
	var Employee_ID = '<?php echo $doctorID; ?>';
	
	if(window.XMLHttpRequest) {
	    myObjectGetDetails = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObjectGetDetails.overrideMimeType('text/xml');
	}
	
	myObjectGetDetails.onreadystatechange = function (){
	    data = myObjectGetDetails.responseText;
	    if (myObjectGetDetails.readyState == 4) {
		document.getElementById('Details_Area').innerHTML = data;
	    }
	}; //specify name of function that will handle server response........
	myObjectGetDetails.open('GET','employeeworkperformancefilterdetails_Iframe.php?Employee_ID='+Employee_ID+'&Date_From='+Date_From+'&Date_To='+Date_To,true);
	myObjectGetDetails.send();
    }
</script>

<?php
    include("./includes/footer.php");
?>



<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script>
    $('#performancedetails').dataTable({
       "bJQueryUI":true,
    });
</script>