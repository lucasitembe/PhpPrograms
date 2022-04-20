<?php
    include("./includes/header.php");
    include("./includes/connection.php"); 
    $section ='';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo'])){
	    /*if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
	    }
	    elseif($_SESSION['userinfo']['Radiology_Works'] == 'yes'){
	    }
	    else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	    }*/
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
    <br/><br/>
    <br/><br/>
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
		    <legend align=center><b>PATIENT (<?php echo $row['Patient_Name']; ?>) FILE</b></legend>
		    <center><table width = 60%>
			<tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
				<a href='#?section=<?php echo $section;?>&patient_id=<?php echo $patient_id?>&bill_id=<?php echo $bill_id;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'>
				    <button style='width: 100%; height: 100%'>
					Doctor Review(s)
				    </button>
				</a>
				<?php }elseif($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
				<a href='department_patient_reviews.php?section=<?php echo $section;?>&patient_id=<?php echo $patient_id?>&bill_id=<?php echo $bill_id;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'>
				    <button style='width: 100%; height: 100%'>
					Doctor Review(s)
				    </button>
				</a>
				<?php }else{ ?>
				    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
					Doctor Review(s)
				    </button>
				<?php } ?>
			    </td>
			    </tr><tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
				<a href='departmentpatientattachments.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'>
				    <button style='width: 100%; height: 100%'>
					Attachments
				    </button>
				</a>
				<?php }elseif($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
				<a href='departmentpatientattachments.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'>
				    <button style='width: 100%; height: 100%'>
					Attachments
				    </button>
				</a>
				<?php }else{ ?>
				    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
					Attachments 
				    </button>
				<?php } ?>
			    </td>
			</tr> 
		    </table>
		    </center>
		</fieldset><br/>
		</td>
	    </tr>
        </table>
    </center>
<?php
    include("./includes/footer.php");
?>