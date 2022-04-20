<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	//if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	//    if(isset($_SESSION['userinfo']['Laboratory_Works']) == 'yes'){
	//	//header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
	//}else{
	//    header("Location: ./index.php?InvalidPrivilege=yes");
	//}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
$section = $_GET['section'];

?>
<br/><br/>
<fieldset><legend align='center'>All Patients</legend>
<iframe width='100%' src='served_patients_iframe.php?section=<?php echo $section;?>&Patient_Payment_ID=All_Patients&Visit_Date=Today' width='100%' height=400px></iframe>
</fieldset>
<?php
    include("./includes/footer.php");
?>