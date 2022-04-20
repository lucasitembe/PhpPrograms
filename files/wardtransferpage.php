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
    if(isset($_SESSION['systeminfo']['Transfer_Patient_Module_Status']) && strtolower($_SESSION['systeminfo']['Transfer_Patient_Module_Status']) != 'enabled'){
        header("Location: ./ward_transfer.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage");
    }
?>
<a href="admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage" class="art-button-green">BACK</a>
<br/><br/><br/><br/><br/>
<fieldset>
    <legend align=center><b>PATIENT WARDS TRANSFER</b></legend>
    <center>
        <table width="50%">
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='patienttransferout.php?PatientTransferOut=PatientTransferOutThisPage'>
                        <button style='width: 100%; height: 100%'>
                                PATIENT TRANSFER OUT
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='patienttransferin.php?PatientTransferIn=PatientTransferInThisPage'>
                        <button style='width: 100%; height: 100%'>
                                PATIENT TRANSFER IN
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='wardtransfer_reportpage.php?PatientTransferIn=PatientTransferInThisPage'>
                        <button style='width: 100%; height: 100%'>
                                PATIENT TRANSFER IN AND OUT REPORTS
                        </button>
                    </a>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>