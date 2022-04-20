<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){
?>
    <script type="text/javascript">
	    function gotolink(){
		var url = "<?php
		if(isset($Registration_ID)){ 
		    if($Registration_ID!=''){
			echo "Registration_ID=$Registration_ID&";
		    }
		}
		if(isset($_GET['Patient_Payment_ID'])){
		    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		    }
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		    }
		?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		var patientlist = document.getElementById('patientlist').value;
		
		/*if(patientlist=='MY PATIENT LIST'){
		    document.location = "doctorcurrentpatientlist.php?"+url;
		}else if (patientlist=='CLINIC PATIENT LIST') {
		    document.location = "clinicpatientlist.php?"+url;
		}else if (patientlist=='CONSULTED PATIENT LIST') {
		    document.location = "doctorconsultedpatientlist.php?"+url;
		}else*/ if(patientlist == 'INPATIENT LIST'){
		    document.location = "inpatientlist.php?"+url;
		}else{
		    alert("Choose Type Of Patients To View");
		}
	    }
	</script>
	
	<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
	<select id='patientlist' name='patientlist'>
	<option></option>
	<!--<option>-->
	<!--    MY PATIENT LIST-->
	<!--</option>-->
	<!--<option>-->
	<!--    CLINIC PATIENT LIST-->
	<!--</option>-->
	<!--<option>-->
	<!--    CONSULTED PATIENT LIST-->
	<!--</option>-->
	<option>
	    INPATIENT LIST
	</option>
	</select>
	<input type='button' value='VIEW' onclick='gotolink()'>
	</label>  
<?php 
?>
<a href='patientsignoff.php?<?php
	if($Registration_ID!=''){
	    echo "Registration_ID=$Registration_ID&";
	}
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	    }
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        SIGN OFF
    </a>
    <a href='doctorspageinpatientwork.php?<?php if(isset($Registration_ID)){echo "Registration_ID=$Registration_ID&";} ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	} ?>PatientBilling=PatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='inpatientlist_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onkeyup='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>INPATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=320px src='inpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>