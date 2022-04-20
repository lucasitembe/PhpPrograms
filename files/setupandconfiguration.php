<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    $indexPage = false;
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    $can_broadcast=$_SESSION['userinfo']['can_broadcast'];
	# system audit trail
	$current_id = $_SESSION['userinfo']['Employee_ID'];
	include 'audit_trail_file.php';
	$Audit = new Audit_Trail($current_id," Accessed ~ <b> Setting And Configuretion </b>");
	$Audit->perfomAuditActivities();
	# system audit trail

?>
<center>
    <table width=100%>
        <tr>
            <td>
                <center>
                    <fieldset>
                        <legend align="center"><b>SETUP AND CONFIGURATION</b></legend>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='companypage.php?CompanyConfiguration=CompanyConfigurationThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            Company Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='branchpage.php?BranchConfiguration=BranchConfigurationThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            Branches Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;'>
                                    <a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            Sponsor/Customer Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;'>
                                    <a href='newsystenusage.php?EmployeeManagement=EmployeeManagementThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            New System
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: center; height: 40px;'>
                                    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            System Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;'>
                                    <a
                                        href='admissionconfiguration.php?AdmissionConfiguration=AdmissionConfigurationThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Admission Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;'>
                                    <a href='clinicpage.php?ClinicConfiguration=ClinicConfigurationThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Clinic Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;'>
                                    <a href='departmentpage.php?Department=DepartmentThisPage'>
                                        <button style='width: 100%; height: 100%'>
                                            Department Configuration
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <!--				    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage'>
					<button style='width: 100%; height: 100%'>
					    Items Configuration
					</button>
				    </a>-->
                                    <a href='login_to_itemsconfiguration.php'>
                                        <button style='width: 100%; height: 100%'>
                                            Items Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Disease/Diagnostic Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='SMSConfiguration.php?SMSConfiguration=SMSConfiguration&from=smsConfig'>
                                        <button style='width: 100%; height: 100%'>
                                            SMS Configuration
                                        </button>
                                    </a>
                                </td>

                                <td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Other Configuration
                                        </button>
                                    </a>
                                </td>

                            </tr>
                            <tr>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='emailpage.php?emailConfigurations=emailConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Email Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='courseinjurypage.php'>
                                        <button style='width: 100%; height: 100%'>
                                            Injury Courses
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a
                                        href='printconfigurations.php?printerConfigurations=printerConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Printing Configuration
                                        </button>
                                    </a>
                                </td>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a
                                        href='epaymentconfigurations.php?EpaymentConfigurations=EpaymentConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            ePayment Configuration
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class='hide' style='text-align: center; height: 40px;width: 25%;'>
                                    <a
                                        href='procurementconfiguration.php?ProcurementConfigurations=ProcurementConfigurationsThisForm'>
                                        <button style='width: 100%; height: 100%'>
                                            Procurement Configuration
                                        </button>
                                    </a>
                                </td>

                                <?php if ($can_broadcast =='yes'){?>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='broadcastmsg.php'>
                                        <button style='width: 100%; height: 100%'>
                                            Broadcast Messages
                                        </button>
                                    </a>
                                </td>
                                <?php }?>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='dashboard_config_login.php'>
                                        <button style='width: 100%; height: 100%'>
                                            Dashboard Configuration
                                        </button>
                                    </a>
                                </td>
                                <?php 
                                    $Given_Username=$_SESSION['userinfo']['Given_Username'];
                                    $sql_check_for_bakup_privilage="SELECT can_take_database_backup FROM tbl_privileges WHERE can_take_database_backup='yes' AND Given_Username='$Given_Username'";
                                     $sql_check_for_bakup_privilage_result=mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error($conn));
                                     if(mysqli_num_rows($sql_check_for_bakup_privilage_result)>0){
                                    ?>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='database_backup.php'>
                                        <button style='width: 100%; height: 100%'>
                                            Take Database Backup
                                        </button>
                                    </a>
                                </td>
                                <?php }else{
                                         
                                         ?>
                                <td style='text-align: center; height: 40px;width: 25%;'>
                                    <a href='#' onclick="alert('Access Denied')">
                                        <button style='width: 100%; height: 100%'>
                                            Take Database Backup
                                        </button>
                                    </a>
                                </td>
                                <?php
                                     } ?>
                            </tr>

                    </fieldset>
    </table>
</center>
</td>
</tr>
</table>
</center>



<?php
    include("./includes/footer.php");
?>