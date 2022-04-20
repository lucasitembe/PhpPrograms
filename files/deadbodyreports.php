<?php
    include("./includes/header.php");
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

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./receptionReports.php?Section=Reception&ReceptionReportThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>


<br/><br/>
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 

<center>
    <table width=80%>
	<tr>
	    <td>
	    	<input type='text' id='Search_Value' name='Search_Value' style='text-align: center;' placeholder='Enter Patient Name' autocomplete='off' oninput='Get_Filtered_Patients_Filter()'>
	    </td>
	    <td style='text-align: right;' width=7%><b>From</b></td>
	    <td width=20%>
		<input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;' autocomplete='off' value='<?php echo $Today; ?>'>
	    </td>
	    <td style='text-align: right;' width=7%><b>To</b></td>
	    <td width=20%>
		<input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;' autocomplete='off' value='<?php echo $Today; ?>'>
	    </td>
	    <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Filtered_Patients()'></td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 440px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>LIST OF DEAD PATIENTS</b></legend>
		<table width=100%>
	    <tr><td colspan="10"><hr></td></tr>
		<tr>
		<td width=4%><b>SN</b></td>
		<td><b>PATIENT NAME</b></td>
		<td width=8%><b>PATIENT#</b></td>
		<td width=7%><b>SPONSOR</b></td>
		<td width=15%><b>CHECKED IN DATE</b></td>
		<td width=10%><b>EMPLOYEE NAME</b></td>
	    </tr>
		<tr><td colspan="10"><hr></td></tr>
	    <?php
		$temp = 0; $Destination = '';
		//get list of checked in patients
				$select = mysqli_query($conn,"select sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID
										from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where
										pr.Registration_ID = ci.Registration_ID and
										ci.Employee_ID = emp.Employee_ID and
										pp.Check_In_ID = ci.Check_In_ID and
										sp.Sponsor_ID = pr.Sponsor_ID and
										ci.Check_In_Date between '$Today' and '$Today' and
										pr.Diseased = 'yes'
                            			group by ci.Check_In_ID order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
			    while($row = mysqli_fetch_array($select)){
		             echo "<tr>
					    <td>".++$temp."</td>
					    <td>".$row['Patient_Name']."</td>
					    <td>".$row['Registration_ID']."</td>
					    <td>".$row['Guarantor_Name']."</td>
					    <td>".$row['Check_In_Date_And_Time']."</td>
					    <td>".$row['Employee_Name']."</td>
					</tr>";
			    }
			}
	    ?>
	</table>
</fieldset>
<script>
    function Get_Filtered_Patients(){
	
	var Date_From = document.getElementById("date").value;
	var Date_To = document.getElementById("date2").value;
	var Search_Value = document.getElementById("Search_Value").value;
	
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
	
	if (Search_Value) {
	    My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List2.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value,true);
	}else{
	    My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List2.php?Date_From='+Date_From+'&Date_To='+Date_To,true);
	}
	
	My_Object_Filter_Patient.send();
    }
</script>


<script>
    function Get_Filtered_Patients_Filter(){
	
	var Date_From = document.getElementById("date").value;
	var Date_To = document.getElementById("date2").value;
	var Search_Value = document.getElementById("Search_Value").value;
	
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
		
	My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List2.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value,true);
	My_Object_Filter_Patient.send();
    }
</script>

<?php
    include("./includes/footer.php");
?>