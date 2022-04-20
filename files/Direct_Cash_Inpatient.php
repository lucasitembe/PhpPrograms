<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
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

    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
    	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
    	$Branch_ID = 0;
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

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
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
    <option>
	Patient From Outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> -->

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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='cash_deposit.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/>
 

 

<script language="javascript" type="text/javascript">
   /* function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='search_list_direct_cash_patient_billing_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }*/
</script>
<br/>
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
				<select name="Sponsor_ID" id="Sponsor_ID" onchange="Filter_Patients()">
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

<br/>
<?php
	$Title = '<tr><td colspan="8"><hr></td></tr>
    			<tr>
		    	<td width="5%"><b>SN</b></td>
		    	<td><b>PATIENT NAME</b></td>
		    	<td width="10%"><b>PATIENT#</b></td>
				<td width="15%"><b>SPONSOR</b></td>
				<td width="14%"><b>DATE OF BIRTH</b></td>
				<td width="10%"><b>GENDER</b></td>
				<td width="10%"><b>PHONE NUMBER</b></td>
				<td width="12%"><b>MEMBER NUMBER</b></td>
			</tr>
			<tr><td colspan="8"><hr></td></tr>';
?>

<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Patient_List_Fieldset'>
	<legend align="right"><b>DIRECT CASH ~ INPATIENT LIST: REVENUE CENTER</b></legend>
	<table width = "100%">
<?php
	echo $Title;
	 $select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Gender, pr.Member_Number, pr.Phone_Number, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, ci.Check_In_ID, ad.Admision_ID  from tbl_patient_registration pr , tbl_admission ad,tbl_check_in ci, tbl_sponsor sp where
												pr.Registration_ID = ad.Registration_ID  and
												sp.Sponsor_ID = pr.Sponsor_ID and
												ci.Registration_ID=pr.Registration_ID AND
												ci.branch_id = '$Branch_ID' and
												ci.Check_In_Status IN ('saved','pending') and    
												ad.admission_status IN ('Admitted','pending') GROUP BY pr.Registration_ID limit 20") or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($select_Filtered_Patients)){
		$Admision_ID = $row['Admision_ID'];
		$Check_In_ID = $row['Check_In_ID'];

		echo "<tr><td id='thead'>".++$temp."</td><td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='billing_direct_inpatient.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
    	if(($temp%21) == 0){
    		echo $Title;
    	}
    }
?>
	<!-- <iframe width='100%' height=380px src='search_list_direct_cash_in_patient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe> -->

	</table>
</fieldset>

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
        
        myObjectFilterPatient.open('GET','Direct_Cash_Inpatient_Filter.php?Sponsor_ID='+Sponsor_ID,true);
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
        
        myObjectSearchByName.open('GET','Direct_cash_inpatient_search.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
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
        
        myObjectSearchByNumber.open('GET','Direct_Cash_inpatient_search_Number.php?Registration_ID='+Registration_ID,true);
        myObjectSearchByNumber.send();
	}
</script>
<?php
    include("./includes/footer.php");
?>