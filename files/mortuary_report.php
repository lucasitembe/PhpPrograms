<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
//    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Admission_Works'])){
//	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	    }
//	}else{
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	    header("Location: ../index.php?InvalidPrivilege=yes");
//    }
//    
    
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = "Admission";
    }
  //  if($section=='Admission'){
        echo"<a href='morguepage.php?MorgueSupervisorsPage=MorguePanelPage' class='art-button-green'>
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
        <legend align=center><b>MORTUARY ADMISSION REPORTS</b></legend>
        <center>
	    <table width = 60%>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href="mortuaryadmissionreport.php?section=<?php echo $section;?>&AdmissionReport=AdmissionReportThisPage&mortuary=yes">
			<button style='width: 100%; height: 100%'>
                            Mortuary Admission Report
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Mortuary Admission Report
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
		    <a href='mortuarydischargereport.php?section=<?php echo $section;?>&status=discharge&PatientFile=PatientFileThisPage&mortuary=yes'>
			<button style='width: 100%; height: 100%'>
                           Mortuary Discharge Report
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Mortuary Discharge Report
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