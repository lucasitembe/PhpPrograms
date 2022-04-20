<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $_SESSION['outpatient_nurse_com'] = 'no';
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
            @session_start();
            if(!isset($_SESSION['Admission_Supervisor'])){
                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
            }
        }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	
    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

	//to get section
    if(isset($_GET['section'])){
        $section = $_GET['section'];
	}else{
        $section='';
	}
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Admission&ChangeLocationAdmission=ChangeLocationAdmissionThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>

<?php
    if(isset($_GET['from']) && $_GET['from'] == "mogueReport") {
        echo "<a href='mortuaryadmissionreport.php?section=Admission&AdmissionReport=AdmissionReportThisPage&mortuary=yes' class='art-button-green'>BACK</a>";
    } else if(isset($_GET['from']) && $_GET['from'] == "mogueReporteee") {
        echo "<a href='mortuarydischargereport.php?section=Admission&status=discharge&PatientFile=PatientFileThisPage&mortuary=yes' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='index.php' class='art-button-green'>BACK</a>";
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/>
<br/>
<br/>
<?php

		?>
<fieldset>
    <legend style="width:18%;font-size:13px" align="center"><b>ADMISSION MAIN MENU</b></legend>
    <div class="row"><div class="col-md-3"></div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <table width = 50% class="table">
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    
                    <a href='searchlistofoutpatientadmission.php?section=&Continue<?php if(isset($section)) echo 'section='.$section;?>&PatientBilling=ContinuePatientBillingThisPage<?php if(isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage']=='fromDoctorPage'){echo '&fromDoctorPage=fromDoctorPage';}?>'>
			<button style='width: 100%; height: 100%'>
                            Admit Patient
                        </button>
		    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Admit Patient
                        </button>
                  
                    <?php }?>
                    
                </td>
	    </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='searchlistofoutpatientadmited.php?section=<?php echo $section;?>&ContinuePatientBilling=ContinuePatientBillingThisPage'>
			<button style='width: 100%; height: 100%'>
                            Discharge Patient
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Discharge Patient
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>

	    <tr>
                <td class='hide' style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='searchlistofmortuaryadmited.php?section=<?php echo $section;?>&ContinuePatientBilling=ContinuePatientBillingThisPage'>
			<button style='width: 100%; height: 100%'>
                            Mortuary Discharge&nbsp;&nbsp;<?php if($deceasedDischarge > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $deceasedDischarge; ?></span><?php } ?>
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Mortuary Discharge
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php 
                        //create link
                        $slct = mysqli_query($conn,"select Transfer_Patient_Module_Status from tbl_system_configuration where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($slct);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($slct)) {
                                $Module_Status = $data['Transfer_Patient_Module_Status'];
                                $_SESSION['systeminfo']['Transfer_Patient_Module_Status'] = $Module_Status;
                            }
                        }else{
                            $Module_Status = '';
                        }

                        if(strtolower($Module_Status) == 'enabled'){
                            echo "<a href='wardtransferpage.php?WardTransfer=WardTransferThisPage'>";
                        }else{
                            echo "<a href='ward_transfer.php?section=".$section."&ContinuePatientBilling=ContinuePatientBillingThisPage'>";
                        }
                    ?>
                    <button style='width: 100%; height: 100%'>
                            Ward Transfer
                        </button>
                    </a>
                </td>
            </tr>
         <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
            <?php
                if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='patientstobedischarged.php?PatientsToBeDischarged=PatientsToBeDischargedThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Patients on initial discharge state
                        </button>
                    </a>
            <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Patients on initial discharge state
                    </button>
            <?php } ?>
            </td>
        </tr>
        <tr>
        <td style='text-align: center; height: 40px;width: 50%'>
                        <a href='admissionconfiguration.php?AdmissionConfiguration=AdmissionConfigurationThisForm&frompage=addmission'> 
                            <button style='width: 100%; height: 100%'>
                                Admission Configuration
                            </button>
                        </a>
                    </td> 
        </tr>
        <tr>
        <td style='text-align: center; height: 40px;width: 50%'>
                        <a href='nurse_duty.php?nurse_duty=nurse_dutyThisForm&frompage=addmission'> 
                            <button style='width: 100%; height: 100%'>
                                Nurse Duty Handling
                            </button>
                        </a>
                    </td> 
   
        </tr>
                            </table>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <table width = 50% class="table">        
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Admission_Works'] == 'yes'){
            ?>
            <a href='searchpatientinward.php?section=<?php echo $section;?>&AdmisionWorks=AdmisionWorksThisPage'>
                <button style='width: 100%; height: 100%'>
                Nurse Communication / Documentation
                </button>
            </a>
            <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Nurse Communication / Documentation
                        </button>
            <?php } ?>
                </td>
            </tr>

	  <!----  <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Admission_Works'] == 'yes'){
			?>
			<a href='departmentworkspage.php?section=<?php echo $section;?>&AdmisionWorks=AdmisionWorksThisPage'>
			    <button style='width: 100%; height: 100%'>
				Patient Care
			    </button>
			</a>
		    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Care 
                        </button>
		    <?php } ?>
                </td>
            </tr>
			------>
			
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Admission_Works'] == 'yes'){
			?>
			<a href='searchPatients.php?section=<?php echo $section;?>&AdmisionWorks=AdmisionWorksThisPage&from_to=admission'>
			    <button style='width: 100%; height: 100%'>
				Pre-Operative Checklist
			    </button>
			</a>
		    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Pre-Operative Checklist
                        </button>
		    <?php } ?>
                </td>
            </tr>
<?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
            <tr>
                <td class='hide'style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='admissionrequisition.php?status=new&NPO=True&Requisition=RequisitionThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Requisitions
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisitions 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td class='hide'style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='admissiongoodreceivednote.php?status=new&NPO=True&Requisition=RequisitionThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Good Receiving Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Good Receiving Note 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
<?php } ?>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Admission_Works'] == 'yes'){
			?>
			<a href='admissionreports.php?section=<?php echo $section;?>&Admissionreports=AllReports&ActiveReports'>
			    <button style='width: 100%; height: 100%'>
				Reports
			    </button>
			</a>
		    <?php }else{?>
			<button style='width: 100%; height: 100%' onclick="alert('Choose patient Please !')">
                            Reports 
                        </button>
                    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports 
                        </button>
		    <?php } ?>
                </td>
            </tr>
            <tr>
                   
                    <td style='text-align: center; height: 40px;'>
                        <a href='inpatientSpecimentCollection.php'> 
                            <button style='width: 100%; height: 100%'>
                                Specimen Collection
                            </button>
                        </a>
                    </td> 
                   
            </tr>
            <tr class='hide'>
                <td style='text-align: center; height: 40px;'>
                    <a href='nurse_report_by_ward.php'> 
                        <button style='width: 100%; height: 100%'>
                            Nurse Report By Ward
                        </button>
                    </a>
                </td>  
            </tr>
        <tr>
        <td style='text-align: center; height: 40px;width: 50%'>
                        <a href='collective_consent_forms.php?nurse_duty=nurse_dutyThisForm&frompage=addmission'> 
                            <button style='width: 100%; height: 100%'>
                                Consent Form
                            </button>
                        </a>
                    </td> 
   
        </tr>
        </table>
        </div>
    </div>
</fieldset>
<?php
    include("./includes/footer.php");
?>
