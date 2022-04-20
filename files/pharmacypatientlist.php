<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include("./button_configuration.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }


    //get menu additional information
    if(isset($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) && 
        strtolower($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) == 'yes' && 
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != null &&
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != ''){
        $Additional_Instruction = '('.strtoupper($_SESSION['systeminfo']['Pharmacy_Additional_Instruction']).')';
    }else{
        $Additional_Instruction = '';
    }
?>


<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "pharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "pharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENTS LIST') {
	    //document.location = "pharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
	    document.location = "pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm";
	}else if(patientlist == 'DISPENSED LIST'){
	    document.location = "dispensedlist.php?Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' class='btn-default'>
<select id='patientlist' name='patientlist'> 
    <?php if (getButtonStatus("outpatient_cash_opt") == "visible") { ?>     
        <option value="OUTPATIENT CASH">
            OUTPATIENT CASH <?php echo $Additional_Instruction; ?>
        </option>
    <?php } ?>    
    <?php if (getButtonStatus("outpatient_credit_opt") == "visible") { ?>    
        <option value="OUTPATIENT CREDIT">
            OUTPATIENT CREDIT <?php echo $Additional_Instruction; ?>
        </option>
       <?php } ?>   
    <?php if (getButtonStatus("inpatient_cash_opt") == "visible") { ?>      
        <option value="INPATIENT CASH">
            INPATIENT CASH <?php echo $Additional_Instruction; ?>
        </option>
     <?php } ?>  
    <?php if (getButtonStatus("inpatient_credit_opt") == "visible") { ?>         
        <option value="INPATIENT CREDIT">
            INPATIENT CREDIT <?php echo $Additional_Instruction; ?>
        </option>
      <?php } ?> 
      <?php if (getButtonStatus("patient_lists_op") == "visible") { ?>      
        <option>
            COSTUMER LIST
        </option>
   <?php } ?>   
    <?php if (getButtonStatus("dispensed_lists_op") == "visible") { ?>       
        <option>
            DISPENSED LIST
        </option>
    <?php } ?>    
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 


<?php if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){ ?>
    <a href='pharmacyregisterpatient.php?PharmacyRegisterPatient=PharmacyRegisterPatientThisPage' class='art-button-green'>
        ADD NEW PATIENT
    </a>
<?php } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
		document.getElementById("Patient_Number").value = '';
		document.getElementById("Phone_Number").value = '';
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='pharmacy_search_list_patient_billing_Iframe2.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>

<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number){
		document.getElementById("Search_Patient").value = '';
		document.getElementById("Phone_Number").value = '';
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='pharmacy_search_list_patient_billing_Iframe2.php?Patient_Number="+Patient_Number+"'></iframe>";
    }
</script>

<script language="javascript" type="text/javascript">
    function Search_Patient_Phone_Number(Phone_Number){
		document.getElementById("Search_Patient").value = '';
		document.getElementById("Patient_Number").value = '';
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='pharmacy_search_list_patient_billing_Iframe2.php?Phone_Number="+Phone_Number+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td width=30%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient(this.value)'  placeholder='~~~~~~~~~~~~~  Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
            </td>
	    <td width=30%>
		<input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' oninput='Search_Patient_Using_Number(this.value)'  placeholder='~~~~~~~~~~~  Search Patient Number  ~~~~~~~~~~~~~~~'>
	    </td>
	    <td width=30%>
		<input type='text' name='Phone_Number' id='Phone_Number' style='text-align:center;' oninput='Search_Patient_Phone_Number(this.value)' placeholder='~~~~~~~~~~~~~  Search Phone Number  ~~~~~~~~~~~~~~~'>
	    </td>
        </tr>
        
    </table>
</center>
<br/>
<fieldset>  
        <legend align=center><b>COSTUMER LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=400px src='pharmacy_search_visitors_outpatient_list_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset>
<br/>
<?php
    include("./includes/footer.php");
?>