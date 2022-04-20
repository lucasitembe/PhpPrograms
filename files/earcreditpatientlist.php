<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Ear_Works'])){
	    if($_SESSION['userinfo']['Ear_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Ear_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Ear&InvalidSupervisorAuthentication=yes");
                    }
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Outpatient cash'){
	    document.location = "./earcashpatientlist.php";
	}else if (patientlist=='Outpatient credit') {
	    document.location = "earcreditpatientlist.php?EarCreditPatientlist=EarCreditPatientlistThispage";
	}else if (patientlist=='Inpatient cash') {
	    document.location = "#";
	}else if (patientlist=='Inpatient credit') {
	    document.location = "#";
	}else if (patientlist=='Patient from outside') {
	    document.location = "#";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>
<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option>Chagua Mgonjwa</option>
    <option>
	Outpatient cash
    </option>
    <option>
	Outpatient credit
    </option>
    <option>
	Inpatient cash
    </option>
    <option>
	Inpatient credit
    </option>
    <option>
	Patient from outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 
<a href='./earclinicalnotes.php' class='art-button-green'>BACK</a>
<center>
    
    
    
 

 

<script language="javascript" type="text/javascript">
    function seaearPatient(Patient_Name){
        document.getElementById('Seaear_Iframe').innerHTML = "<iframe width='100%' height=320px src='earcreditpatientlist_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>


<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Seaear_Patient' id='Seaear_Patient' onclick='seaearPatient(this.value)' onkeyup='seaearPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>EAR CREDIT PATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Seaear_Iframe'>
		<iframe width='100%' height=320px src='earcreditpatientlist_Iframe.php?Patient_Name='></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>