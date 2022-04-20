<?php
    include("./includes/header.php");
    include("./includes/connection.php"); 
    $section =''.$_GET['section'];
    $Registration_ID = $_GET['Registration_ID'];
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
	    }
	    elseif($_SESSION['userinfo']['Radiology_Works'] == 'yes'){
	    }
	    else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }
    if($section=='Admission'){
        echo "<a href='admissionworkspage.php?section=".$section."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
        </a>";
    }    
?>
<?php
    if(isset($_SESSION['userinfo'])){
        $Registration_ID = $_GET['Registration_ID'];
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	$section= $_GET['section'];
?>
    
    <?php if($section == 'Admission'){ ?>
	<a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
	    PATIENT CARE
	</a>
    <?php }else{ ?>
	<a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
	    WORKPAGE
	</a>
    <?php } ?>
    <?php
    }
    ?>
    <a class='art-button-green' href='departmentpatientfile.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'>
        PATIENT FILE
    </a>
    <br/><br/>
    <center>
    <?php
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Select_Patients = "select Patient_Name from tbl_patient_registration where registration_id = '$Registration_ID'";
        $result = mysqli_query($conn,$Select_Patients);
        $row = mysqli_fetch_array($result);
    ?>
        <table width='100%'>
	    <tr>
		<td>
		<fieldset>
		    <legend align=center><b>Patient (<?php echo $row['Patient_Name']; ?>) Attachments</b></legend>
                    <iframe width='100%' src='patientattachmentsiframe.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=All_Patients&Visit_Date=Today' width='100%' height=340px></iframe>
                    </iframe>
		</fieldset><br/>
		</td>
	    </tr>
        </table>
    </center>
<?php
    include("./includes/footer.php");
?>