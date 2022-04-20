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
    <a href='foodworkpage.php' class='art-button-green'>
       BACK
    </a>
<?php  } } ?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
//alert("helooooow");
        document.getElementById('Search_Iframe').innerHTML = 
		"<iframe width='100%' height=380px src='foodrestriction_iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>

<center>
<br/>
<center>
    <table width="40%">
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient'  onkeyup='searchPatient(this.value)'
				onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>PATIENT LISTS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height="380px" src='foodrestriction_iframe.php' ></iframe>
            </td>
				</tr>
            </table>
        </center>
</fieldset>
</center>
<?php
    include("./includes/footer.php");
?>