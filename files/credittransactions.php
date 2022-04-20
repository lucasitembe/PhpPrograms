<?php
    $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
       include("./includes/header_general.php"); 
       header("Location:directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm");
    }
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    

    if(isset($_GET['Section'])){
        $Section = "Section=".$_GET['Section']."&";
    }else{
        $Section = '';
    }
?>

<?php
    if(isset($_GET['Section']) && strtolower($_GET['Section']) == 'msamaha'){
        echo "<a href='msamahapanel.php?EditMsamaha=LISTMsamahaPatientsForm' class='art-button-green'>BACK</a>";
    }else{        
	    echo "<a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
    }
?>

<br/><br/><br/>
<fieldset>  
    <legend align="right"><b>CREDIT TRANSACTIONS</b></legend>
        <center>
            <table width = "60%">
            <tr>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='credittransactionsapproval.php?<?php echo $Section; ?>Newapproval=NewapprovalThisPage&from=newApproval'>
                            <button style='width: 100%; height: 100%'>
        NEW APPROVAL - ALL PATIENT APPROVAL MUST BE DONE HERE
                            </button>
                        </a>
                    </td>
                </tr>

               <tr class='hide'>

                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='investigationcredit.php?<?php echo $Section; ?>InvestigationCredit=InvestigationCreditThisPage'>
                            <button style='width: 100%; height: 100%'>
                                INVESTIGATIONS (LABORATORY & RADIOLOGY)
                            </button>
                        </a>
                    </td>
                </tr>
                <tr class='hide'>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='procedurecredits.php?<?php echo $Section; ?>ProcedureCredits=ProcedureCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                PROCEDURES, Nuclear Medicine & SURGERY
                            </button>
                        </a>
                    </td>
                </tr>
                <tr class='hide'>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='glassescredits.php?<?php echo $Section; ?>GlassesCredits=GlassesCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                GLASSES / OPTICAL
                            </button>
                        </a>
                    </td>
                </tr>
            <?php //if(isset($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes'){ ?>
                <tr class='hide'>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='pharmacycredits.php?<?php echo $Section; ?>PharmacyCredits=PharmacyCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                PHARMACY
                            </button>
                        </a>
                    </td>
                </tr>
            <?php //} ?>
                 <tr class='hide'>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='otherscredits.php?<?php echo $Section; ?>OthersCredits=OthersCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                OTHERS
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px;' colspan="2">
                    <?php 
                        $patient_debt= 0;
                        $select_patient_debt = mysqli_query($conn, "SELECT COUNT(Debt_social_ID) as social_ID from tbl_patient_debt_to_socialwalfare  WHERE Debt_social_ID NOT IN (SELECT Debt_social_ID FROM tbl_social_reduce_debt)") or die(mysqli_error($conn));
                        while($countID = mysqli_fetch_assoc($select_patient_debt)){
                            $patient_debt = $countID['social_ID'];
                        }
                    ?>
                        <a href='patient_sent_from_reception.php?<?php echo $Section; ?>credittransaction=credittransactionThisPage'>
                            <button style='width: 100%; height: 100%'>
                                PATIENT WITH DEBT <span class="badge " style="background-color: red;"><?php echo $patient_debt; ?></span>
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='approval_filter_report.php?<?php echo $Section; ?>OthersCredits=OthersCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                APPROVAL REPORT
                            </button>
                        </a>
                    </td>
                </tr>
                <tr class='hide'>
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='outpatient_bill_approvel_report.php?<?php echo $Section; ?>OthersCredits=OthersCreditsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                OUTPATIENT BILL APPROVAL REPORT
                            </button>
                        </a>
                    </td>
                </tr>
    	  </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>