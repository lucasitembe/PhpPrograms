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
    
    
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = "Admission";
    }
  //  if($section=='Admission'){
        echo "<a href='admissionworkspage.php' class='art-button-green'>
            BACK
        </a>";
   // }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/>
<fieldset>
        <legend align=center><b>ADMISSION REPORTS</b></legend>
        <center>
	    <table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='currentinpatientreport.php?section=<?php echo $section;?>&InpatientReport=InpatientReportThisPage'>
			<button style='width: 100%; height: 100%'>
                            Current Inpatient
                        </button>
		    </a>
		    <?php }}else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Current Inpatient
                        </button>
                  
                    <?php }?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href="admissionreport.php?section=<?php echo $section;?>&AdmissionReport=AdmissionReportThisPage">
			<button style='width: 100%; height: 100%'>
                            Admission Report
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Admission Report
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='dischargereport.php?section=<?php echo $section;?>&status=discharge&PatientFile=PatientFileThisPage'>
			<button style='width: 100%; height: 100%'>
                           Patient Discharge Report
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Patient Discharge Report
                        </button>
                  
                    <?php } ?>
                </td>
        
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='DoctorRoundReport.php'>
			<button style='width: 100%; height: 100%'>
                            Doctor Rounds Reports
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor Rounds Reports
                        </button>
                    <?php } ?>
                </td>
        </tr>
		
	    <!----<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='#'>
			<button style='width: 100%; height: 100%'>
                            Other Reports
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Other Reports
                        </button>
                    <?php } ?>
                </td>
            </tr>
			
			----->
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='fromOutsidereport.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			<button style='width: 100%; height: 100%'>
                            From Outside Reports
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            From Outside Reports
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
		            <a href='ipd_report_new_nurse.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			            <button style='width: 100%; height: 100%'>
                            Bed State Report
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
		            <a href='death_reports_home_nurse.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			            <button style='width: 100%; height: 100%'>
                            Death Report
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
		            <a href='daily_census_summary_nurse.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			            <button style='width: 100%; height: 100%'>
                            Daily Census Report
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
		            <a href='hospital_statistics_report_nurse.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			            <button style='width: 100%; height: 100%'>
                            Hospital Statistics
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
		            <a href='early_waning_score_report.php?section=&status=discharge&PatientFile=PatientFileThisPage'>
			            <button style='width: 100%; height: 100%'>
                            Early Warning Score
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='grossdischargereport.php?section=<?php echo $section;?>&status=discharge&PatientFile=PatientFileThisPage'>
			<button style='width: 100%; height: 100%'>
                           Gross Patient Discharge Report
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Gross Patient Discharge Report
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                        <a href='discharged_patientwithunclearedbills.php'>
                            <button style='width: 100%; height: 100%;' class="btn btn-danger btn-sm">
                                <span  style="color:#fff; font-size:20px;"> PATIENT WAITING FINAL DISCHARGE </span> 
                            </button>
                        </a>
		            <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        PATIENT WAITING FINAL DISCHARGE 
                        </button>
                  
                    <?php } ?>
                </td>
        </tr>
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                        <a href='uprocessed_items.php'>
                            <button style='width: 100%; height: 100%;' class="btn btn-danger btn-sm">
                                <span  style="color:#fff; font-size:20px;"> UNPROCESSED SERVICES REPORT </span> 
                            </button>
                        </a>
		            <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        UNPROCESSED SERVICES REPORT 
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                        <a href='patient_overstay_list.php'>
                            <button style='width: 100%; height: 100%;' class="btn btn-danger btn-sm">
                                <span  style="color:#fff; font-size:20px;"> PATIENT OVERSTAY FORMS </span> 
                            </button>
                        </a>
		            <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        PATIENT OVERSTAY FORMS 
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
