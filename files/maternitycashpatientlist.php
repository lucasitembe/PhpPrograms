<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Maternity_Works'])){
	    if($_SESSION['userinfo']['Maternity_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Maternity_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Maternity&InvalidSupervisorAuthentication=yes");
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
	    document.location = "./maternitycashpatientlist.php";
	}else if (patientlist=='Outpatient credit') {
	    document.location = "maternitycreditpatientlist.php?MaternityCreditPatientlist=MaternityCreditPatientlistThispage";
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
<a href='./maternityclinicalnotes.php' class='art-button-green'>BACK</a>
<center>
    
    
    
 

 

<script language="javascript" type="text/javascript">
    function seamaternityPatient(Patient_Name){
        document.getElementById('Seamaternity_Iframe').innerHTML = "<iframe width='100%' height=320px src='maternitycashpatientlist_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>


<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Seamaternity_Patient' id='Seamaternity_Patient' onclick='seamaternityPatient(this.value)' onkeyup='seamaternityPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>FAMILY PLANNING CASH PATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Seamaternity_Iframe'>
		<iframe width='100%' height=320px src='maternitycashpatientlist_Iframe.php?Patient_Name='></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>