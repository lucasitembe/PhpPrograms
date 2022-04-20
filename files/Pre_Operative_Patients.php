\<?php
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

    <a href='searchPatients.php?CheckList=CheckListThisPage' class='art-button-green'>
	    BACK
    </a>




<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<?php
if(isset($_GET['Registration_ID'])){
$Registration_ID=$_GET['Registration_ID'];

$query=mysqli_query($conn,"select Registration_ID,Patient_Name from tbl_patient_registration 
			where Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
while($row=mysqli_fetch_array($query))
$patient_name=$row['Patient_Name'];
}
else{
$patient_name='';
}

?>

<script language="javascript" type="text/javascript">
    function searchPatient(){
	var Patient_Name=document.getElementById('Patient_Name').value;
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='Pre_Operative_Patients_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width="50%">
        <tr>
            <td>
                <input type='text' name='searchPatient' id='Patient_Name'  oninput='searchPatient(this.value)' value=<?php echo $patient_name;?> >
            </td>
        </tr>
   </table>
</center>
<fieldset>  
    <legend align=right><b>PRE~OPERATIVE PATIENTS LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
		    <td id='Search_Iframe'>
			<iframe width='100%' height=380px src='Pre_Operative_Patients_Iframe.php?Patient_Name='></iframe>
		    </td>
		</tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>