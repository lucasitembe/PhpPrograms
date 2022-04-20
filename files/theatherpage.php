<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Theater_Works'])){
	    if($_SESSION['userinfo']['Theater_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ 
?>
    <a href='theatherpagework.php' class='art-button-green'>
       CHECKED
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ 
?>
    <a href='theaterworkspage.php' class='art-button-green'>
       BACK
    </a>
<?php  } } ?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = 
		"<iframe width='100%' height=320px src='theatherpageworks2.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<center>
<br/>
<center>
    <table width="40%">
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient'  onkeyup='searchPatient(this.value)'
				onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<br>
<fieldset>  
            <legend align=center><b>PATIENTS LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
					<td id='Search_Iframe'>
				<iframe width='100%' height=350px src='theatherpageworks2.php' ></iframe>
					</td>
				</tr>
            </table>
        </center>
</fieldset>
<br>
<table>
	<tr>
			<td><a href="doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage" class='art-button-green'>TO DOCTOR'S PAGE</a></td>
	
	</tr>
</table>
</center>

<?php
    include("./includes/footer.php");
?>