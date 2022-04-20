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
    <a href='emailpage.php?emailConfigurations=emailConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<script language="javascript" type="text/javascript">
    function searchRecepient(Recepient_Name){
	document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=430px src='edit_Email_Pre_Iframe.php?Recepient_Name="+Recepient_Name+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Employee' id='Search_Recepient' oninput='searchRecepient(this.value)' placeholder='Enter Recepient Name'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>LIST OF RECEPIENT</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=430px src='edit_Email_Pre_Iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>