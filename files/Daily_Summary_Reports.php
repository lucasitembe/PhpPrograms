<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    <a href='./managementworkspage.php?ManagementWorksPage=ThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<center>
<fieldset style="background-color:white;">
    
            <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>DAILY HOSPITAL SUMMERY REPORT</b><br/>
			  < The reports are generated everyday at 23:50 PM >
			</legend>
        <center>
		  <table width=100%>
		   <tr>
			  <td style="text-align: center;border: 1px #ccc solid;width: 50%">
			   <b>PF3 Male and Female Report</b>
			   <object data="Daily_reports/pf3_male_female.pdf" type="application/pdf" width="100%" height="100%">
			     <p>
				   It appears you don't have a PDF plugin for this browser. No big deal. You can <a href="Daily_reports/pf3_male_female.pdf">Click here to download the PDF file</a>
				 </p>
			   </object>
			  </td>
			  <td style="text-align: center;border: 1px #ccc solid;width: 50%">
			   <b>Revenue Collection Report</b>
			   <object data="Daily_reports/cash_credit_msamaha.pdf" type="application/pdf" width="100%" height="100%">
			     <p>
				   It appears you don't have a PDF plugin for this browser. No big deal. You can <a href="Daily_reports/cash_credit_msamaha.pdf">Click here to download the PDF file</a>
				 </p>
			   </object>
			  </td>
			  <tr>
			   <td style="text-align: center;border: 1px #ccc solid;width: 50%">
			   <b>Doctor's Performance</b>
			   <object data="Daily_reports/doctors_performance.pdf" type="application/pdf" width="100%" height="100%">
			     <p>
				   It appears you don't have a PDF plugin for this browser. No big deal. You can <a href="Daily_reports/doctors_performance.pdf">Click here to download the PDF file</a>
				 </p>
			   </object>
			  </td>
			  <td style="text-align: center;border: 1px #ccc solid;width: 50%" >
			   <b>Number of Female And Male Report</b>
			   <object data="Daily_reports/number_patient_male_female.pdf" type="application/pdf" width="100%" height="100%">
			     <p>
				   It appears you don't have a PDF plugin for this browser. No big deal. You can <a href="Daily_reports/number_patient_male_female.pdf">Click here to download the PDF file</a>
				 </p>
			   </object>
			  </td>
			  </tr>
			 <tr>
			  <td style="text-align: center;border: 1px #ccc solid;width: 50%" colspan="2">
			    <b>Disease Report</b>
			   <object data="Daily_reports/desease_report.pdf" type="application/pdf" width="100%" height="100%">
			     <p>
				   It appears you don't have a PDF plugin for this browser. No big deal. You can <a href="Daily_reports/desease_report.pdf">Click here to download the PDF file</a>
				 </p>
			   </object>
			  </td>
			 </tr>
			 </table>
       </center>
</fieldset>
</center>
<br/>
<?php
    include("./includes/footer.php");
?>
