<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) != 'yes' && strtolower($_SESSION['userinfo']['Mtuha_Reports']) != 'yes'){
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
        if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes' || strtolower($_SESSION['userinfo']['Mtuha_Reports']) == 'yes'){
?>
    <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<script language="javascript" type="text/javascript">
    function searchDisease(Disease_Group_Name){
        document.getElementById('Search_Iframe').src = "Edit_Disease_Group_List_Iframe.php?Disease_Group_Name="+Disease_Group_Name;
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Disease' id='Search_Disease' onclick='searchDisease(this.value)' onkeyup='searchDisease(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Disease Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
    </table>
</center>
<fieldset>  
    <legend align=center>LIST OF DISEASES GROUPS</legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
        	       <iframe id='Search_Iframe' width='100%' height=320px src='Edit_Disease_Group_List_Iframe.php'></iframe>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>