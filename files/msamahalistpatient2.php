<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ 
?>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ 
?>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ 
?>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ 
?>
    <a href='msamahapanel.php?EditMsamaha=LISTMsamahaPatientsForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


 
<script language="javascript" type="text/javascript">
    function searchProduct(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='msamahalistpatientframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td align="center">
                <input type='text' name='Search_Product' id='Search_Product' onclick='searchProduct(this.value)' onkeyup='searchProduct(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td> 
        </tr>        
    </table>
</center>
<br>
<fieldset>  
            <legend align="center"><b>ALL MSAMAHA REGISTERED LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width="100%" height="380px" src='msamahalistpatientframe.php?PatientsName=MsamahaEditName'></iframe>
            </td>
        </tr>
        <tr>
            <td>
                <center>
                  <input type="button" id="printmsamaha" class="art-button-green" value="Print Preview">
                </center>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>

<script>
    $('#printmsamaha').click(function(){
      var Search_Product=$('#Search_Product').val();
      window.open('PrintMsamahaList.php?Search_Product='+Search_Product+'');  
    });
</script>
