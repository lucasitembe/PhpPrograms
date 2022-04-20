<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){
	    if($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    
<?php  } } ?>



<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='searchPatients.php' class='art-button-green'>
      PATIENTS LISTS
    </a>
<?php  } } ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = 
		"<iframe width='100%' height=380px src='viewchecked.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>CHECKED LISTS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='viewchecked.php?Patient_Name='></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>