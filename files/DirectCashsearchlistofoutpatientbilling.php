<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
?>


<!-- link menu --> 
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Checked In - Outpatient List'){
	    document.location = "searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
	}else if (patientlist=='Checked In - Inpatient List') {
	    document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Outpatient') {
	    document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Inpatient') {
	    document.location = "DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='AdHOC Payments - Outpatient') {
	    document.location = "continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
	}else if (patientlist=='Patient From Outside') {
	    document.location = "#";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Chagua Orodha Ya Wagonjwa</option>
    <option>
	Checked In - Outpatient List
    </option>
    <option>
	Checked In - Inpatient List
    </option>
    <option>
	Direct Cash - Outpatient
    </option>
    <option>
	Direct Cash - Inpatient
    </option>
    <option>
	AdHOC Payments - Outpatient
    </option>
    <!--<option>
	Patient From Outside
    </option>-->
</select>
	<input type='button' value='VIEW' onclick='gotolink()'>
</label> 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='search_list_direct_cash_patient_billing_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/>

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
<br/><br/>
<center>
	<table width="80%">
	    <tr>
			<td width="45%">
			    <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" onkeypress='Search_Patients();' oninput="Search_Patients();"  autocomplete="off" placeholder='~~~ ~~~ ~~~ Enter Patient Name ~~~ ~~~ ~~~'>
			</td>
			<td width="45%">
				<input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" style="text-align: center;" onkeypress="Search_Patients_Via_Number()" oninput="Search_Patients_Via_Number()" placeholder="~~~ ~~~ ~~~ Enter Patient Number ~~~ ~~~ ~~~ ">
			</td>
			<td>
				<select name="Sponsor_ID" id="Sponsor_ID" onchange="Filter_Patients();">
					<option selected="selected" value="0">All</option>
				<?php
					$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($data = mysqli_fetch_array($select)) {
							echo "<option value='".$data['Sponsor_ID']."'>".strtoupper($data['Guarantor_Name'])."</option>";
						}
					}
				?>
			</select>
			</td>
	    </tr>
	</table>
</center>
<?php
	$Title = '<tr><td colspan="7"><hr></tr>
				<tr>
		        	<td width="5%"><b>SN</b></td>
		            <td width="25%"><b>PATIENT NAME</b></td>
		            <td width="10%"><b>PATIENT NUMBER</b></td>
		            <td width="15%"><b>SPONSOR</b></td>
		            <td width="15%"><b>AGE</b></td>
		            <td width="8%"><b>GENDER</b></td>
		            <td width="10%"><b>MEMBER NUMBER</b></td>
		        </tr>
		        <tr><td colspan="7"><hr></tr>';

?>
<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Patient_List_Fieldset'>
	<legend align="right"><b>DIRECT CASH ~ OUTPATIENT LIST: REVENUE CENTER</b></legend>
		<table width = "100%">
	       <?php
	       		echo $Title; $m_records = 0; $Terminator = 0;
	       		$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, pr.Registration_ID, pr.Member_Number, sp.Sponsor_ID, sp.Guarantor_Name from
	       									tbl_patient_registration pr, tbl_sponsor sp where
	       									sp.Sponsor_ID = pr.Sponsor_ID order by pr.Registration_Date_And_Time desc limit 20") or die(mysqli_error($conn));
	       		$nm = mysqli_num_rows($select);
	       		if($nm > 0){
	       			$temp = 0;
	       			while ($data = mysqli_fetch_array($select)) {
	       				$Registration_ID = $data['Registration_ID'];
	       				//check if inpatient
	       				$check = mysqli_query($conn,"select Registration_ID from tbl_admission where Registration_ID = '$Registration_ID' and admission_status IN ('Admitted','pending')") or die(mysqli_error($conn));
	       				$num_check = mysqli_num_rows($check);
	       				if($num_check == 0){

		       				$Date_Of_Birth = $data['Date_Of_Birth'];
		       				$date1 = new DateTime($Today);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$age = $diff->y." Years, ";
							$age .= $diff->m." Months, ";
							$age .= $diff->d." Days";
	       	?>
		       				<tr>
					        	<td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($data['Patient_Name'])); ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Registration_ID']; ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Guarantor_Name']; ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $age; ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Gender']; ?></a></td>
					            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Member_Number']; ?></a></td>
					        </tr>
	       	<?php
	       					if(($temp%21) == 0){ echo $Title; }
	       				}else{
	       					++$m_records; //number of missed patients during the display process (Inpatient patients)
	       				}
	       			}
	       		}

	       		while ($m_records != 0 && $Terminator < 11) {
	       			$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, pr.Registration_ID, pr.Member_Number, sp.Sponsor_ID, sp.Guarantor_Name from
	       									tbl_patient_registration pr, tbl_sponsor sp where
	       									sp.Sponsor_ID = pr.Sponsor_ID order by pr.Registration_Date_And_Time limit $m_records") or die(mysqli_error($conn));
	       			$nm = mysqli_num_rows($select);
	       			if($nm > 0){
	       				while ($data = mysqli_fetch_array($select)) {
	       					$Registration_ID = $data['Registration_ID'];
		       				//check if inpatient
		       				$check = mysqli_query($conn,"select Registration_ID from tbl_admission where Registration_ID = '$Registration_ID' and admission_status IN ('Admitted','pending')") or die(mysqli_error($conn));
		       				$num_check = mysqli_num_rows($check);
		       				if($num_check == 0){

			       				$Date_Of_Birth = $data['Date_Of_Birth'];
			       				$date1 = new DateTime($Today);
								$date2 = new DateTime($Date_Of_Birth);
								$diff = $date1 -> diff($date2);
								$age = $diff->y." Years, ";
								$age .= $diff->m." Months, ";
								$age .= $diff->d." Days";
			?>
								<tr>
						        	<td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($data['Patient_Name'])); ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Registration_ID']; ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Guarantor_Name']; ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $age; ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Gender']; ?></a></td>
						            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Member_Number']; ?></a></td>
						        </tr>
			<?php
								if(($temp%21) == 0){ echo $Title; }
								$m_records--;
	       					}
	       				}
	       			}
	       			$Terminator++;
	       		}
	       ?>

        	<!--<td id='Search_Iframe'>
				 <iframe width='100%' height=380px src='search_list_direct_cash_patient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe> 
        	</td>-->
    	</tr>
	</table>
</fieldset><br/>


<script type="text/javascript">
	function Filter_Patients(){
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		document.getElementById("Search_Patient").value = '';
		document.getElementById("Patient_Number").value = '';

		if(window.XMLHttpRequest){
            myObjectFilterPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterPatient.overrideMimeType('text/xml');
        }

        myObjectFilterPatient.onreadystatechange = function (){
            data = myObjectFilterPatient.responseText;
            if (myObjectFilterPatient.readyState == 4) {
                document.getElementById('Patient_List_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterPatient.open('GET','Direct_Cash_Outpatient_Filter_Patients.php?Sponsor_ID='+Sponsor_ID,true);
        myObjectFilterPatient.send();
}
</script>

<script type="text/javascript">
	function Search_Patients(){
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		var Patient_Name = document.getElementById("Search_Patient").value;
		document.getElementById("Patient_Number").value = '';

		if(window.XMLHttpRequest){
            myObjectSearchByName = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchByName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchByName.overrideMimeType('text/xml');
        }

        myObjectSearchByName.onreadystatechange = function (){
            data234 = myObjectSearchByName.responseText;
            if (myObjectSearchByName.readyState == 4) {
                document.getElementById('Patient_List_Fieldset').innerHTML = data234;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchByName.open('GET','Direct_Cash_Outpatient_Search_Patients.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
        myObjectSearchByName.send();
	}
</script>

<script type="text/javascript">
	function Search_Patients_Via_Number(){
		var Registration_ID = document.getElementById("Patient_Number").value;
		document.getElementById("Sponsor_ID").value = 0;
		document.getElementById("Search_Patient").value = '';

		if(window.XMLHttpRequest){
            myObjectSearchByNumber = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchByNumber = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchByNumber.overrideMimeType('text/xml');
        }

        myObjectSearchByNumber.onreadystatechange = function (){
            data2349 = myObjectSearchByNumber.responseText;
            if (myObjectSearchByNumber.readyState == 4) {
                document.getElementById('Patient_List_Fieldset').innerHTML = data2349;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchByNumber.open('GET','Direct_Cash_Outpatient_Search_Patients_Via_Number.php?Registration_ID='+Registration_ID,true);
        myObjectSearchByNumber.send();
	}
</script>
<?php
    include("./includes/footer.php");
?>