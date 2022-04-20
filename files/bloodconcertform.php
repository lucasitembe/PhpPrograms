<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
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

    <!-- <a href='Pre_Operative_Patients.php?PreOperativeList=PreOperativeListThisPage' class='art-button-green'>
	REGISTERED
    </a> -->
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<div style='float: right;'>
<span style='font-weight: bold; font-size: 18px;'>COLOR CODE KEY: <span>
<input type="button"  style='background: green; padding: 10px; color: #fff; border: none;' value='AGREED' name="" id="">
<input type="button"  style='background: #ff8080; padding: 10px; border: none;' value='DISAGREED' name="" id="">
</div>




<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<script language="javascript" type="text/javascript">
    function searchPatient(){
		Patient_Name = document.getElementById('Patient_Name').value;
		Patient_Number = document.getElementById('Patient_Number').value;
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='bloodconcertform_iframe.php?Patient_Name="+Patient_Name+"&Patient_Number="+Patient_Number+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width="60%">
        <tr>
            <td width="50%"> 
                <input type='text' name='Patient_Name' id='Patient_Name' style='text-align: center;' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
            <td width="50%"> 
                <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;'  onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~Search Patient Number~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
    </table>
</center>
<br>
<fieldset>  
    <legend align=center><b>PATIENT LIST FOR BLOOD TRANSFUSION REQUEST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
		    <td id='Search_Iframe'>
			<iframe width='100%' height=380px src='bloodconcertform_iframe.php?Patient_Name='></iframe>
		    </td>
		</tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>