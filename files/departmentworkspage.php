<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $section = $_GET['section'];
    $Registration_ID = '';
    $Patient_Payment_ID = '';
    $patient_name = '';
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['Registration_ID'])){
	if(isset($_GET['Patient_Payment_ID'])){
	$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}
	$Select_Patient = "select Patient_Name from tbl_patient_registration where registration_id = '$Registration_ID'";
	$result = mysqli_query($conn,$Select_Patient);
	$row = mysqli_fetch_array($result);
	$patient_name = $row['Patient_Name'];
    }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php if($section=='Admission'){?>
    <a href='admissionworkspage.php?section=<?php echo $section;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        ADMISSION MAIN WORKPAGE
    </a>
<?php }?>
<br>
<br>
<br>
<fieldset>
        <legend align=center><b><?php echo strtoupper($section);?> WORKS</b></legend>
        <center>
		<br>
		
	    <table width = 70%>
	    <tr>
		<td>
		    <center>
		    Patient Name <input type='text' value='<?php echo $patient_name;?>'style='width: 30%;' required='required' disabled='disabled'>
		    &nbsp;&nbsp;
		    <?php if($section!='Admission'){?>
		    <a href='servedpatients.php?section=<?php echo $section;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
			Select Outpatient
		    </a><?php }?>
		    
		    <a href='servedinpatients.php?section=<?php echo $section;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
			Select Inpatient
		    </a>
		    <a href='departmentallpatients.php?section=<?php echo $section;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
			All Patients (No Payment)
		    </a>
		    </center>
		</td>
	    </tr> 
		<tr><td><br></td></tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($Registration_ID!=''){ ?>
		    <a href='departmentpatientfile.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&PatientFile=PatientFileThisPage'>
			<button style='width: 100%; height: 100%'>
                            Patient File
                        </button>
		    </a>
		    <?php }else{?>
			<button style='width: 100%; height: 100%' onclick="alert('Choose patient Please !')">
                            Patient File 
                        </button>
                    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient File 
                        </button>
                  
                    <?php }?>
                </td>
	    </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($Registration_ID!=''){ ?>
		    <a href='departmentupload.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&PatientFile=PatientFileThisPage'>
			<button style='width: 100%; height: 100%'>
                            Upload Results
                        </button>
                    </a>
		    <?php }else{?>
		    <button style='width: 100%; height: 100%' onclick="alert('Choose patient Please !')">
                            Upload Results 
                        </button>
                    <?php } }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Upload Results
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($Registration_ID!=''){
			?>
			<a href='#?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&PatientFile=PatientFileThisPage'>
			    <button style='width: 100%; height: 100%'>
				Doctors Test Recomendations
			    </button>
			</a>
		    <?php }else{?>
			<button style='width: 100%; height: 100%' onclick="alert('Choose patient Please !')">
                            Doctors Test Recomendations 
                        </button>
                    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctors Test Recomendations 
                        </button>
		    <?php } ?>
                </td>
            </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($Registration_ID!=''){
			    if(!isset($_GET['Patients'])){
			?>
			<a href='departmentpatientbilling.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&CP=True&NR=True&PatientFile=PatientFileThisPage'>
			    <button style='width: 100%; height: 100%'>
				Payments
			    </button>
			</a>
		    <?php }else{
			?>
			<button style='width: 100%; height: 100%' onclick="alert('Access Denied !\n Choose Patient From Other List.');">
                            Payments 
                        </button>
			<?php
			}}else{?>
			<button style='width: 100%; height: 100%' onclick="alert('Choose patient Please !')">
                            Payments 
                        </button>
                    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Payments
                        </button>
		    <?php } ?>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>