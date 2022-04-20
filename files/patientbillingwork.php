<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $_SESSION['outpatient_nurse_com'] = 'no';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
	    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
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

<style>
        table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        
        }
    tr:hover{
    background-color:#eeeeee;
    cursor:pointer;
    }
 </style> 

<?php
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
?>
<br/><br/><br/>
<fieldset>
    <legend align=left><b>PATIENT BILLING WORKS</b></legend>
        <center>
            <table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='billingwork.php?BillingWork=BillingWorkThisPage&from=cleared'>
                        <button style='width: 100%; height: 100%'>
                            PENDING BILLS - INPATIENTS
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            PENDING BILLS - INPATIENTS
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='patientspaymentslist.php?from_billing=yes'>
                        <button style='width: 100%; height: 100%'>
                            PATIENT PAYMENTS PREVIEW
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            PATIENT PAYMENTS PREVIEW
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='clearedpatientbillingwork.php?ClearedPatientsBillingWorks=ClearedPatientsBillingWorks&from=cleared'>
                        <button style='width: 100%; height: 100%'>
                            CLEARED BILLS - INPATIENTS
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            CLEARED BILLS - INPATIENTS
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
                    <a href='patientbillingreports.php?PatientBillingReport=PatientBillingReportThisPage'>
                        <button style='width: 100%; height: 100%'>
                            PENDING AND CLEARED BILLS REPORTS
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            PENDING AND CLEARED BILLS REPORTS
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='prepaidpendingbills.php?PrePaidPendingBills=PrePaidPendingBillsThisPage'>
                        <button style='width: 100%; height: 100%'>
                            <!--PRE / POST PAID PENDING BILLS-->
                            OUTPATIENT  PENDING BILLS
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='prepaidclearedbills.php?PrePaidClearedBills=PrePaidClearedBillsThisPage'>
                        <button style='width: 100%; height: 100%'>
                                <!-- PRE / POST PAID CLEARED BILLS-->
                                OUTPATIENT CLEARED BILLS
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                                <td style='text-align: center; height: 40px; width: 33%;' colspan='2'>
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
            </tr>
        </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>
