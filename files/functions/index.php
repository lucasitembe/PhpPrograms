<?php
$indexPage = true;
include("./includes/header.php");
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<style media="screen">
  .button_container{
    width: 90%;
    border: 1px solid silver;
    margin: 10px;
  }
  .button_alignment{
    width: 33%;
    border: 1px solid silver;
    position: relative;
    display: inline-block;
    margin: auto;
  }
</style>
<br/><br/>
<fieldset>
    <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
    <center>
      <div class="button_container">
                <?php if (getButtonStatus("admission_works") == "visible") { ?>

                        <?php if (isset($_SESSION['userinfo']['Admission_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Admission_Works'] == 'yes') { ?>
                              <div class="button_alignment">
                                <a href='admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>
                                    <!--<a href='#?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>-->
                                    <button style='width: 100%; height: 100%'>
                                        Admission Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Admission Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Admission Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <!--
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                <?php if (isset($_SESSION['userinfo']['Family_Planning_Works'])) { ?>
                    <?php if ($_SESSION['userinfo']['Family_Planning_Works'] == 'yes') { ?>
                                                                    <a href='family_planningworkspage.php?section=Family_Planning&FamilyPlanningWorks=FamilyPlanningWorksThisPage'>
                                                                        <button style='width: 100%; height: 100%'>
                                                                            Family Planning Works
                                                                        </button>
                                                                    </a>
                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Family Planning Works
                                                                        </button>
                        <?php
                    }
                } else {
                    ?>
                                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                Family Planning Works
                                            </button>
                <?php } ?>
                </td>
                -->
                <?php if (getButtonStatus("wagonjwa_wa_msamaha") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Msamaha_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Msamaha_Works'] == 'yes') { ?>
                                <a href='msamahapanel.php?RegisteredPatient=RegisterPatientThisPage' >
                                    <button style='width: 100%; height: 100%'>
                                        Msamaha
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Msamaha
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Cost Sharing
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("procedure_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Procedure_Works'] == 'yes') { ?>
                                <a href='Procedure.php?PatientsBillingWorks=PatientsBillingWorks'>
                                    <button style='width: 100%; height: 100%'>
                                        Procedures Works
                                    </button>
                                    <!--</a>-->
                                <?php } else { ?>
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                        Procedures Works
                                    </button>
                                    <?php
                                }
                            } else {
                                ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Procedures Works
                                </button>
                            <?php } ?>
                          </div>
                <?php } ?>

                <?php if (getButtonStatus("optical_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Eye_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Eye_Works'] == 'yes') { ?>
                                <a href='opticalworkspage.php?OpticalWorks=OpticalWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Optical Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Optical Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Optical Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("general_ledger_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['General_Ledger'])) { ?>
                            <?php if ($_SESSION['userinfo']['General_Ledger'] == 'yes') { ?>
                                <!--<a href='financeworks.php?FinanceWorks=FinanceWorksThiPage'>-->
                                <a href='generalledgercenter.php'>
                                    <button style='width: 100%; height: 100%'>
                                        General Ledger Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    General Ledger Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                General Ledger Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("procurement_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Procurement_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') { ?>
                                <a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Procurement Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Procurement Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Procurement Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <!--
        <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                <?php if (isset($_SESSION['userinfo']['Dental_Works'])) { ?>
                    <?php if ($_SESSION['userinfo']['Dental_Works'] == 'yes') { ?>
                            <a href='#?section=Dental&DentalWorks=DentalWorksThisPage'>
                        <!--<a href='dentalworkspage.php?section=Dental&DentalWorks=DentalWorksThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Dental Works
                            </button>
                        </a>
                    <?php } else { ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Dental Works
                            </button>
                        <?php
                    }
                } else {
                    ?>
        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
            Dental Works
        </button>
                <?php } ?>
</td>
                -->
                <?php if (getButtonStatus("hiv_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Hiv_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Hiv_Works'] == 'yes') { ?>
                                <a href='hivworkspage.php?section=Hiv&HivWorks=HivWorksThisForm'>
                                    <button style='width: 100%; height: 100%'>
                                        HIV Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    HIV Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                HIV Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("reception_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php
                        if (isset($_SESSION['userinfo']['Reception_Works'])) {
                            if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
                                ?>
                                <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Reception Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Reception Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Reception Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("theater_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Theater_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Theater_Works'] == 'yes') { ?>
                                <a href='theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Theater Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Theater Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Theater Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>
                <?php if (getButtonStatus("dhis2_report") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Mtuha_Reports'])) { ?>
                            <?php if ($_SESSION['userinfo']['Mtuha_Reports'] == 'yes') { ?>
                                <a href='dhisworkpage.php?DhisWork=DhisWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        DHIS2 Reports
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    DHIS2 Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                DHIS2 Works
                            </button>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if (getButtonStatus("laboratory_works") == "visible") { ?>
                  <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') { ?>
                                <!--<a href='departmentworkspage.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
                                <a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>
                                    <!--<a href='#?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
                                    <!--<a href='laboratoryauthentication.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>-->
                                    <button style='width: 100%; height: 100%'>
                                        Laboratory Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Laboratory Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Laboratory Works
                            </button>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if (getButtonStatus("rch_works") == "visible") { ?>
                  <div class="button_alignment">
                         <?php if (isset($_SESSION['userinfo']['Rch_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Rch_Works'] == 'yes') { ?>
                                <a href='rchworkspace.php?RchWorksPage=RchWorksPageThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        RCH Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    RCH Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                RCH Works
                            </button>
                        <?php } ?>
                      </div>
                <?php } ?>



                <?php if (getButtonStatus("dialysis_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Dialysis_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                                <!--<a href='#?section=Dialysis&DialysisWorks=DialysisWorksThisPage'>-->
                                <!-- <a href='#' onclick="alert('* This button * is under repair. Sorry for inconvenience!')">
                                    <button style='width: 100%; height: 100%'>
                                        Dialysis Works
                                    </button>
                                </a> -->
                               <a href='dialysisworkspage.php?section=Dialysis&DialysisWorks=DialysisWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Dialysis Works
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Dialysis Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Dialysis Works
                            </button>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if (getButtonStatus("woman_carer_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Maternity_Works'])) { ?>
                            <?php if ($_SESSION['userinfo']['Maternity_Works'] == 'yes') { ?>
                            
                    <?php 
                       $emp_username=$_SESSION['userinfo']['Given_Username'];
                       $emp_password=$_SESSION['userinfo']['Given_Password'];
                       $coc_url = "/index.php/account/authenticate_user_from_ehms/".$emp_username."/".$emp_password;
                       $sql_select_url_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='GaccountingUrl'") or die(mysqli_error($conn));
                       if(mysqli_num_rows($sql_select_url_result)==1){
                           $url=mysqli_fetch_assoc($sql_select_url_result)['configvalue'].$coc_url;
                       }else{
                           $url='#';
                       }
                    ?>

                                <!-- <a  href='#' onclick="alert('* This button * is under repair. Sorry for inconvenience!')">
                                    <button style='width: 100%; height: 100%'>
                                        Finance and Accounting
                                    </button>
                                </a> -->
                       <a target="blank_" href='<?= $url; ?>'>
                                    <button style='width: 100%; height: 100%'>
                                        Finance and Accounting
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Finance and Accounting
                                </button>
                            <?php }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Finance and Accounting
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("revenue_center_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') { ?>
                                <a href='revenuecenterworkpage.php?RevenueCenterWork=RevenueCenterWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Revenue Center Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Revenue Center Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Revenue Center Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>

                    <?php if (getButtonStatus("doctor_page_outpatient_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']) || isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) { ?>
        <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes' || $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == "yes") { ?>
                                <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Doctor's Works Page </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Doctor's Works Page
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Works Page
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("management_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Management_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                                <a href='managementworkspage.php?ManagementWorksPage=ThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Management Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Management Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Management Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("storage_and_supply_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) { ?>
        <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                                <a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Storage And Supply Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Storage And Supply Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Storage And Supply Works
                            </button>
                    <?php } ?>
                  </div>
<?php } ?>

                    <?php if (getButtonStatus("doctor_page_inpatient_works") == "visible") { ?>
                    <div class="button_alignment hide">
                        <?php if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) { ?>
        <?php if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') { ?>

                                <a href='doctorsinpatientworkspage.php?DoctorsInpatient=DoctorsInpatientThisPage' >
                                    <button style='width: 100%; height: 100%'>
                                        Doctor's Page Inpatient Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Doctor's Page Inpatient Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Doctor's Page Inpatient Works
                            </button>
                    <?php } ?>
                  </div>
<?php } ?>


                    <?php if (getButtonStatus("nurse_station_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Nurse_Station_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes') { ?>

                                <a href='searchnurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Nurse Station Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Nurse Station Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Nurse Station Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("radiology_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Radiology_Works'] == 'yes') { ?>
                                <a href='radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Radiology Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Radiology Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Radiology Works
                            </button>
                    <?php } ?>
                  </div>
<?php } ?>


            
            <div class="button_alignment">
            <?php if (isset($_SESSION['userinfo']['can_acess_oncology_button'])) { ?>
            <?php if ($_SESSION['userinfo']['can_acess_oncology_button'] == 'yes') { ?>
                    <a href='can_acess_oncology_button.php?section=nuclearmedicine&nuclearmedicine=can_acess_oncology_button'>
                        <button style='width: 100%; height: 100%'>
                            Oncology Works
                        </button>
                    </a>
            <?php } else { ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Oncology Works
                    </button>
                    <?php
                }
            } else {
                ?>
                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    Nuclear Medicine Works
                </button>
            <?php } ?>
            </div>


            <tr>
                <!--
        <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                <?php if (isset($_SESSION['userinfo']['Ear_Works'])) { ?>
    <?php if ($_SESSION['userinfo']['Ear_Works'] == 'yes') { ?>
                                            <a href='earworkspage.php?section=Ear&EarWorks=EarWorksThisPage'>
                                                <button style='width: 100%; height: 100%'>
                                                    Ear Works
                                                </button>
                                            </a>
    <?php } else { ?>
                                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                    Ear Works
                                                </button>
                        <?php
                    }
                } else {
                    ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Ear Works
                            </button>
<?php } ?>
        </td>

        <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                <?php if (isset($_SESSION['userinfo']['Patients_Billing_Works'])) { ?>
    <?php if ($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes') { ?>
                            <a href='patientbill.php?PatientsBillingWorks=PatientsBillingWorks'>
                                <button style='width: 100%; height: 100%'>
                                    Patient Billing Works
                                </button>
                            </a>
    <?php } else { ?>
                                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                    Patient Billing Works
                                                </button>
                        <?php
                    }
                } else {
                    ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Billing Works
                            </button>
                <?php } ?>
        </td>-->
                    <?php if (getButtonStatus("patient_billing_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Patients_Billing_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes') { ?>
                                <a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks'>
                                    <button style='width: 100%; height: 100%'>
                                        Patient Billing Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Patient Billing Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Billing Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php //if (getButtonStatus("engineering_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php //if (isset($_SESSION['userinfo']['Eye_Works'])) { ?>
                            <?php //if ($_SESSION['userinfo']['Eye_Works'] == 'yes') { ?>
                                <!-- <a href='#' onclick="alert('* This button * is under repair. Sorry for inconvenience!')">
                                    <button style='width: 100%; height: 100%'>
                                        Engineering Works 
                                    </button>
                                </a> -->
                               <a href='engineering_works.php?engineering_works=engineering_WorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Engineering Works 
                                    </button>
                                </a>
                            <?php //} else { ?>
                                <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Engineering Works
                                </button> -->
                                <?php
                        //     }
                        // } else {
                            ?>
                            <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Engineering Works 
                            </button> -->
                    <?php //} ?>
                  </div>
                <?php //} ?>
            <?php if (getButtonStatus("legal_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['legal_works'])) { ?>
        <?php if ($_SESSION['userinfo']['legal_works'] == 'yes') { ?>
                                <a href='engineering_works.php?engineering_works=engineering_WorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Legal Works 
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Legal Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Legal Works 
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("morgue_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Morgue_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Morgue_Works'] == 'yes') { ?>
                                <!-- <a href='#' onclick="alert('* This button * is under repair. Sorry for inconvenience!')">
                                    <button style='width: 100%; height: 100%'>
                                        Morgue Works
                                    </button>
                                </a> -->
                               <a href='morguelogin.php?MorgueWork=MorgueWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Morgue Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Morgue Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Morgue Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>

                    <?php if (getButtonStatus("human_resource_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Appointment_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Appointment_Works'] == 'yes') { ?>
                                <a href='human_resource.php?HRWork=HRWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Human Resources
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Human Resources
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Appointment Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
            <?php if (getButtonStatus("quality_assuarance_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Quality_Assurance'])) { ?>
        <?php if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') { ?>
                                <a href='qualityassuarancework.php?QualityAssuaranceWork=QualityAssuaranceWorkThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Quality Assuarance Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Quality Assuarance Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Quality Assuarance Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("pharmacy_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Pharmacy'])) { ?>
        <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                                <!--<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>-->
                                <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Dispensing Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Dispensing Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Pharmacy Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
                    <?php if (getButtonStatus("patient_records") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Patient_Record_Works'])) { ?>
        <?php if ($_SESSION['userinfo']['Patient_Record_Works'] == 'yes') { ?>
                                <a href='patientrecords.php?PatientFile=PatientFileThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Patient Records
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Patient Records
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Records
                            </button>
                    <?php } ?>
                  </div>
<?php } ?>
                    <?php if (getButtonStatus("church_works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Pharmacy'])) { ?>
        <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                                <!--<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>-->
                                <a href='muumini.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Church Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Church Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Pharmacy Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
            
            
               <?php if (getButtonStatus("Oncology_Works") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['can_acess_oncology_button'])) { ?>
        <?php if ($_SESSION['userinfo']['can_acess_oncology_button'] == 'yes') { ?>
                               
                                <a href='oncologyworks.php?section=oncologyworks=oncologyworks'>
                                    <button style='width: 100%; height: 100%'>
                                       Oncology Works
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                   Oncology Works
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                               Oncology Works
                            </button>
                    <?php } ?>
                  </div>
                <?php } ?>
            
            
                    <?php if (getButtonStatus("add_new_costumer") == "visible") { ?>
                    <div class="button_alignment">
                        <?php if (isset($_SESSION['userinfo']['Pharmacy'])) { ?>
        <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                                <!--<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage'>-->
                                <a href=http://localhost/Final_One/files/church_member_buy_item.php?RegisterPatient=RegisterPatientThisPage>
                                    <button style='width: 100%; height: 100%'>
                                        Add New Customer
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Add New Customer
                                </button>
                                <?php
                            }
                        } else {
                            ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Add New Costumer
                            </button>
                    <?php } ?>
                  </div>
<?php } ?>
</div>
</center>
        
<?php
include("./includes/footer.php");
?>
