<?php
    $indexPage = true;
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

               
 <br/><br/>                
<fieldset>
    <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
        <center>
        <table width = 90% style="border: 0">
            <tr>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>    
                <?php if(isset($_SESSION['userinfo']['Admission_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>
                    <!--<a href='#?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>-->
                        <button style='width: 100%; height: 100%'>
                            Admission Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Admission Works 
                        </button> 
                <?php }}else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Admission Works 
                    </button> 
                <?php } ?>
                </td>
                <!--
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Family_Planning_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Family_Planning_Works'] == 'yes'){ ?>
                    <a href='family_planningworkspage.php?section=Family_Planning&FamilyPlanningWorks=FamilyPlanningWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Family Planning Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Family Planning Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Family Planning Works 
                    </button> 
                <?php } ?>
                </td>
                -->
				<td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Msamaha_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href='msamahapanel.php?RegisteredPatient=RegisterPatientThisPage' >
                        <button style='width: 100%; height: 100%'>
                            Wagonjwa Wa Msamaha
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Wagonjwa Wa Msamaha
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                      Wagonjwa Wa Msamaha
                    </button> 
                <?php } ?>
                </td>
				<td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Dressing_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Dressing_Works'] == 'yes'){ ?>
                    <a href='../account'>
                    <!--<a href='dressingworkspage.php?section=Dressing&DressingWorks=DressingWorksThisPage'>-->
                        <button style='width: 100%; height: 100%'>
                            Finance / Accounting Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Finance / Accounting Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Finance / Accounting Works 
                    </button> 
                <?php } ?>
                </td>
                <!--
				<td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Physiotherapy_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Physiotherapy_Works'] == 'yes'){ ?>
                    <a href='physiotherapyworkspage.php?section=Physiotherapy&PhysiotherapyWorks=PhysiotherapyWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Physiotherapy Works
                        </button>    
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Physiotherapy Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Physiotherapy Works 
                    </button> 
                <?php } ?>
                </td>
				-->
                
            </tr>
            
            <tr>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Eye_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                    <a href='opticalworkspage.php?OpticalWorks=OpticalWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Optical Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Optical Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Optical Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['General_Ledger'])){ ?>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <!--<a href='financeworks.php?FinanceWorks=FinanceWorksThiPage'>-->
                    <a href='generalledgercenter.php'>
                        <button style='width: 100%; height: 100%'>
                            General Ledger Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           General Ledger Works
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        General Ledger Works
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Procurement_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                    <a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Procurement Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Procurement Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Procurement Works 
                    </button> 
                <?php } ?>
                </td>
                
            </tr>  
            <tr>
			<!--
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Dental_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Dental_Works'] == 'yes'){ ?>
                    <a href='#?section=Dental&DentalWorks=DentalWorksThisPage'>
                    <!--<a href='dentalworkspage.php?section=Dental&DentalWorks=DentalWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Dental Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Dental Works 
                        </button> 
                <?php }}else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Dental Works 
                    </button> 
                <?php } ?>
                </td>
              -->  
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Hiv_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Hiv_Works'] == 'yes'){ ?>
                    <a href='hivworkspage.php?section=Hiv&HivWorks=HivWorksThisForm'>
                        <button style='width: 100%; height: 100%'>
                            HIV Works
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            HIV Works
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        HIV Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php
                        if(isset($_SESSION['userinfo']['Reception_Works'])){
                            if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                                <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Reception Works
                                    </button>
                                </a>
                            <?php }else{ ?> 
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Reception Works 
                                </button>
                        <?php } }else{ ?> 
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Reception Works 
                            </button>
                    <?php } ?>
                </td>
				   <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Theater_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Theater Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Theater Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Theater Works 
                    </button> 
                <?php } ?>
                </td>
				
            </tr>
            
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Mtuha_Reports'])){ ?>
                    <?php if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ ?>
                    <a href='dhisworkpage.php?DhisWork=DhisWorkThisPage'>
                        <button style='width: 100%; height: 100%'>
                            DHIS2 Reports
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            DHIS2 Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        DHIS2 Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                    <!--<a href='departmentworkspage.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
                    <a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>
					<!--<a href='#?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
					<!--<a href='laboratoryauthentication.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
                        <button style='width: 100%; height: 100%'>
                            Laboratory Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Laboratory Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Laboratory Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Rch_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='rchworkspace.php?RchWorksPage=RchWorksPageThisPage'>
                        <button style='width: 100%; height: 100%'>
                            RCH Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            RCH Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        RCH Works 
                    </button> 
                <?php } ?>
                </td>
            </tr>  
        </table>
         
        <table width = 90%>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Dialysis_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ ?>
                    <!--<a href='#?section=Dialysis&DialysisWorks=DialysisWorksThisPage'>-->
                    <a href='dialysisworkspage.php?section=Dialysis&DialysisWorks=DialysisWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Dialysis Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Dialysis Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Dialysis Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Maternity_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Maternity_Works'] == 'yes'){ ?>
                    <a href='maternityworkspage.php?section=Maternity&MartenityWorks=MartenityWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                        Women Care Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                             Women Care Works
                        </button> 
                    <?php }}else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                             Women Care Works
                        </button> 
                    <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){ ?>
                        <?php if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ ?>
                            <a href='revenuecenterworkpage.php?RevenueCenterWork=RevenueCenterWorkThisPage'>
                                <button style='width: 100%; height: 100%'>
                                    Revenue Center Works
                                </button>
                            </a>
                        <?php }else{ ?> 
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Revenue Center Works 
                            </button>
                    <?php } }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Revenue Center Works 
                        </button> 
                    <?php } ?>
                </td>
            </tr>
            
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){ ?>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                        <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage'>
                            <button style='width: 100%; height: 100%'>
                               Doctor's Page Outpatient Works </button>
                        </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Doctor's Page Outpatient Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                       Doctor's Page Outpatient Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Management_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Management_Works'] == 'yes'){ ?>
                    <a href='managementworkspage.php?ManagementWorksPage=ThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Management Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Management Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Management Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){ ?>
                    <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                    <a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Storage And Supply Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Storage And Supply Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Storage And Supply Works 
                    </button> 
                <?php } ?>
                </td>
            </tr>
            
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){ ?>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){ ?>
					
                    <a href='doctorsinpatientworkspage.php?DoctorsInpatient=DoctorsInpatientThisPage' >
                        <button style='width: 100%; height: 100%'>
                            Doctor's Page Inpatient Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Page Inpatient Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Doctor's Page Inpatient Works 
                    </button> 
                <?php } ?>
                </td>
			
				
				
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ ?>
                    <a href='nurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Nurse Station Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Nurse Station Works 
                        </button>
                <?php } }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Nurse Station Works 
                        </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Radiology_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Radiology Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Radiology Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Radiology Works 
                    </button> 
                <?php } ?>
                </td>
            </tr>  
            
                
                <!--
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Eye_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                    <a href='eyeworkspage.php?section=Optical&OpticalWorks=OpticalWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Optical(Eye) Works 
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Optical(Eye) Works 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Optical(Eye) Works 
                    </button> 
                <?php } ?>
                </td>
               --> 
             
        
            <tr>
			<!--
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Ear_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Ear_Works'] == 'yes'){ ?>
                    <a href='earworkspage.php?section=Ear&EarWorks=EarWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Ear Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Ear Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Ear Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='patientbill.php?PatientsBillingWorks=PatientsBillingWorks'>
                        <button style='width: 100%; height: 100%'>
                            Patient Billing Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Billing Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Patient Billing Works 
                    </button> 
                <?php } ?>
                </td>-->
				<td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks'>
                        <button style='width: 100%; height: 100%'>
                            Patient Billing Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Billing Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Patient Billing Works 
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Quality_Assurance'])){ ?>
                    <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                    <a href='qualityassuarancework.php?QualityAssuaranceWork=QualityAssuaranceWorkThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Quality Assuarance Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Quality Assuarance Works 
                        </button>
                <?php } }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Quality Assuarance Works 
                        </button> 
                    <?php } ?>
                </td>
                 <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Morgue_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='morguelogin.php?MorgueWork=MorgueWorkThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Morgue Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Morgue Works 
                        </button>
                <?php } }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Morgue Works 
                        </button> 
                    <?php } ?>
                </td>
            </tr>
            <tr>
                 <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Appointment_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Appointment_Works'] == 'yes'){ ?>
                     <a href='../hrp'>
                        <button style='width: 100%; height: 100%'>
<!--Patient Appointment Works-->
                          Human Resources / Payroll
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Appointment Works
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Patient Appointment Works
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Pharmacy'])){ ?>
                    <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                    <!--<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>-->
                    <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Pharmacy Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Pharmacy Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Pharmacy Works 
                    </button> 
                <?php } ?>
                </td>
                
                <!--<td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php //if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){ ?>
                    <?php //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ ?>
                    <a href='setupandconfiguration.php?SetupAndConfiguration=SetupAndConfigurationThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Setup And Configuration Works
                        </button>
                    </a>
                    <?php// }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Setup And Configuration Works 
                        </button>
                <?php //} }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Setup And Configuration Works 
                    </button> 
                <?php //} ?>
                </td>-->
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Patient_Record_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ ?>
                    <a href='patientrecords.php?PatientFile=PatientFileThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Patient Records
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Records 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Patient Records
                    </button> 
                <?php } ?>
                </td>
            </tr>
                        <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Food_And_Laundry_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Food_And_Laundry_Works'] == 'yes'){ ?>
                    <a href='laundryfoodpage.php'>
                        <button style='width: 100%; height: 100%'>
                            Food & Laundry Works
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Food & Laundry Works 
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Food & Laundry Works
                    </button> 
                <?php } ?>
                </td>
                
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Blood_Bank_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Blood_Bank_Works'] == 'yes'){ ?>
                    <a href='supervisor.php?PatientsBillingWorks=PatientsBillingWorks'>
                        <button style='width: 100%; height: 100%'>
                            Blood Bank Works
                        </button>
                    <!--</a>-->
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Blood Bank Works
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Blood Bank Works
                    </button> 
                <?php } ?>
                </td>
                 <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                    <?php if(isset($_SESSION['userinfo']['Procedure_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Procedure_Works'] == 'yes'){ ?>
                    <a href='Procedure.php?PatientsBillingWorks=PatientsBillingWorks'>
                        <button style='width: 100%; height: 100%'>
                            Procedures Works
                        </button>
                    <!--</a>-->
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Procedures Works
                        </button>
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Procedures Works
                    </button> 
                <?php } ?>
                </td>
                
               
               
                
            </tr>
        </table></center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>