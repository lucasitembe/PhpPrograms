<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }
    else{
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<br/><br/>
<fieldset><legend align='center'>All Patients</legend>
<iframe width='100%' src='departmentallpatients_iframe.php?section=<?php echo $section;?>&Patient_Payment_ID=All_Patients&Visit_Date=Today' width='100%' height=400px></iframe>
</fieldset>
<?php
    include("./includes/footer.php");
?>