<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
//    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Reception_Works']) || isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
//	    if($_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	    } 
//	}else{
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	    header("Location: ../index.php?InvalidPrivilege=yes");
//    }
?>


<?php
//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href="viewappointmentPage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage&frompage=reception"  class='art-button-green'>
        VIEW APPOINTMENT
    </a>
    <!-- <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'> -->
     <!-- BACK -->
    <!-- </a> -->
<?php //  } } ?>
<?php
    if(isset($_GET['frompage']) && $_GET['frompage'] == "reception"){
        ?>
        <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>BACK</a>
        <?php
            }else{
            ?>
            <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>
        <?php
            }
        ?>
<?php
$back_direction="";

if(isset($_GET['frompage']) && $_GET['frompage'] == "reception"){
    $back_direction="&frompage=reception";
}  
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
    function searchPatient(){
        var Patient_Name=document.getElementById('Search_Patient').value;
        var Patient_Name_No=document.getElementById('Search_Patient_ID').value;
		 document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='searchappointmentPatientList.php?Patient_Name="+Patient_Name+"&Patient_Name_No="+Patient_Name_No+"'></iframe>";
	 }
</script>
<br/><br/>
<center>
    <table width="100%">
        <tr>
            <td>
                <input style='text-align: center;' type='text' name='Search_Patient' id='Search_Patient' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
             
            </td>
            
            <td>
                 <input style='text-align: center;' type='text' name='Search_Patient_ID' id='Search_Patient_ID' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient No~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<br>
<fieldset>  
    <legend align="center"><b>LIST OF PATIENTS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='searchappointmentPatientList.php?FormSearch=searchpartientward<?php echo $back_direction;?>' ></iframe>
            </td>
				</tr>
            </table>
        </center>
</fieldset><br/>
<?php 
  include("./includes/footer.php");
?>