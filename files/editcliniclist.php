<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='clinicpage.php?ClinicConfiguration=ClinicConfigurationThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script language="javascript" type="text/javascript">
    function searchClinic(Clinic_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=440px src='edit_Clinic_Frame.php?Clinic_Name="+Clinic_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Clinic' id='Search_Clinic' oninput='searchClinic(this.value)'  placeholder='Search Clinic Name'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>CLINIC LIST</b></legend>
        <center>
            <table width=60% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=440px src='edit_Clinic_Pre_Frame.php?Clinic_Name="+Clinic_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>