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

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button hide'>
	<input type='button' value='VIEW' onclick='gotolink()'>
</label> 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='revenuecenterworkpage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<script language="javascript" type="text/javascript">
    function searchPatient(Customer_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='search_list_direct_cash_patient_billing_Iframe.php?Customer_Name="+Customer_Name+"'></iframe>";
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
			    <input type='text' name='Search_Customer' id='Search_Customer' style="text-align: center;" onkeyup='Search_Customer();' oninput="Search_customer();"  autocomplete="off" placeholder='~~~ ~~~ ~~~ Enter Customer Name ~~~ ~~~ ~~~'>
			</td>
			<td width="45%">
				<input type="text" name="Customer_Number" id="Customer_Number" autocomplete="off" style="text-align: center;" onkeyup="Search_Customers_Via_Number()" oninput="Search_Customers_Via_Number()" placeholder="~~~ ~~~ ~~~ Enter Customer Number ~~~ ~~~ ~~~ ">
			</td>
	    </tr>
	</table>
</center>
<?php
	$Title = '<tr><td colspan="7"><hr></tr>
				<tr>
		        	<td width="5%"><b>SN</b></td>
		            <td width="25%"><b>CUSTOMER NAME</b></td>
		            <td width="10%"><b>CUSTOMER NUMBER</b></td>
		            <td width="15%"><b>PHONE NUMBER</b></td>
		            <td width="15%"><b>EMAIL ADDRESS</b></td>
		            <td width="15%"><b>CUSTOMER  TYPE</b></td>
		        </tr>
		        <tr><td colspan="7"><hr></tr>';

?>
<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Patient_List_Fieldset'>
	<legend align="right"><b>CUSTOMER BILLING LIST: REVENUE CENTER</b></legend>
		<table width = "100%">
	       <?php
	       		echo $Title; $m_records = 0; $Terminator = 0;
	       		$select = mysqli_query($conn,"SELECT pr.Registration_ID AS REGNO, pr.Patient_Name AS CUSTOMER_NAME, pr.Phone_Number AS PHONE ,pr.Email_Address AS ADDRESS, 'CUSTOMER' AS CUSTOMER_TYPE FROM tbl_patient_registration pr WHERE pr.registration_type='customer' UNION ALL SELECT sp.Sponsor_ID AS REGNO, sp.Guarantor_Name AS CUSTOMER_NAME, sp.Phone_Number AS PHONE , ' ' AS ADDRESS, 'SPONSOR' AS CUSTOMER_TYPE FROM tbl_sponsor sp
") or die(mysqli_error($conn));
	       		$nm = mysqli_num_rows($select);
	       		if($nm > 0){
	       			$temp = 0;
	       			while ($data = mysqli_fetch_array($select)){
	       				?>
		       				<tr>
					        	<td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($data['CUSTOMER_NAME'])); ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['REGNO']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['PHONE']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['ADDRESS']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['CUSTOMER_TYPE']; ?></a></td>
					        </tr>
	       				<?php
	       					if(($temp%21) == 0){ echo $Title; }
	       			}
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
	function Search_Customer(){
		var Customer_Name = document.getElementById("Search_Customer").value;
		document.getElementById("Customer_Number").value = '';
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
        
        myObjectSearchByName.open('GET','Customer_Payment_Search_Patients.php?Customer_Name='+Customer_Name,true);
        myObjectSearchByName.send();
	}
</script>

<script type="text/javascript">
	function Search_Customers_Via_Number(){
		var Registration_ID = document.getElementById("Customer_Number").value;
		document.getElementById("Search_Customer").value = '';

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
        
        myObjectSearchByNumber.open('GET','Customer_Payment_Search_Patients.php?Registration_ID='+Registration_ID,true);
        myObjectSearchByNumber.send();
	}
</script>
<?php
    include("./includes/footer.php");
?>