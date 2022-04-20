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
    function searchDepartment(Department_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=440px src='edit_Clinic_Department_Iframe.php?Department_Name="+Department_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Clinic' id='Search_Clinic' oninput='searchDepartment(this.value)'  placeholder='Search Department Name'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>DEPARTMENT LIST</b></legend>
        <center>
            <table width=60% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=440px src='edit_Clinic_Department_Iframe.php?Clinic_Department_Name="+Clinic_Department_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>