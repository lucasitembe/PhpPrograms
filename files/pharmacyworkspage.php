<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
//@session_start();
include("./includes/header.php");
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}

///////////////////////////////////////////////////////////////////////////////////////
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {

            if (!isset($_SESSION['Pharmacy_Supervisor'])) {
                header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


//Approval Setting
if (isset($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical'])) {
    $Allow_Cashier_To_Approve_Pharmaceutical = $_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical'];
} else {
    $Allow_Cashier_To_Approve_Pharmaceutical = 'no';
}

//get menu additional information
if (isset($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) &&
        strtolower($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) == 'yes' &&
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != null &&
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != '') {
    $Additional_Instruction = '(' . strtoupper($_SESSION['systeminfo']['Pharmacy_Additional_Instruction']) . ')';
} else {
    $Additional_Instruction = '';
}
?>




<?php
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
    $_SESSION['Transaction_Type'] = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $_SESSION['Payment_Cache_ID'] = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['from_billing_work'])) {
    $from_billing_work = $_GET['from_billing_work'];
} else {
    $from_billing_work = '';
}
if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}
?>

<!--get sub department name-->
<?php
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

$select = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}
//$show_make_payment="";
//$sql_select_make_payment_configuration="SELECT 	configname FROM tbl_config WHERE configvalue='show' AND configname='showMakePaymentButton'";
//$sql_select_make_payment_configuration_result=mysqli_query($conn,$sql_select_make_payment_configuration) or die(mysqli_error($conn));
//if(mysqli_num_rows($sql_select_make_payment_configuration_result)>0){
//    //show button
//}else{
// $show_make_payment="style='display:none'";
//}
?>

<!-- link menu -->
<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'OUTPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
        }
        else if (patientlist == 'removed list') {
            document.location = "pharmacylistremoved.php?PharmacyList=PharmacyListThisForm";
        }
         else if (patientlist == 'OUTPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'PATIENTS LIST') {
            //document.location = "pharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
            document.location = "pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm";
        } else if (patientlist == 'DISPENSED LIST') {
            document.location = "dispensedlist.php?Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm";
        } else {
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' class='btn-default'>
    <select id='patientlist' name='patientlist' onchange='gotolink()'>
        <option>
            SELECT BILLING TYPE
        </option>
        <?php if (getButtonStatus("outpatient_cash_opt") == "visible") { ?>
            <option value="OUTPATIENT CASH">
                OUTPATIENT CASH <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>
        <?php if (getButtonStatus("outpatient_credit_opt") == "visible") { ?>
            <option value="OUTPATIENT CREDIT">
                OUTPATIENT CREDIT <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>
        <?php if (getButtonStatus("inpatient_cash_opt") == "visible") { ?>
            <option value="INPATIENT CASH">
                INPATIENT CASH <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>
        <?php if (getButtonStatus("inpatient_credit_opt") == "visible") { ?>
            <option value="INPATIENT CREDIT">
                INPATIENT CREDIT <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>
        <?php if (getButtonStatus("patient_lists_op") == "visible") { ?>
            <option value="PATIENTS LIST">
                COSTUMER LIST
            </option>
        <?php } ?>
        <?php if (getButtonStatus("dispensed_lists_op") == "visible") { ?>
            <option>
                DISPENSED LIST
            </option>
        <?php } ?>

        <?php if (getButtonStatus("dispensed_lists_op") == "visible") { ?>
            <option value='removed list'>
                REMOVED LIST
            </option>
        <?php } ?>

    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label>
<?php if (getButtonStatus("inpatient_list") == "visible") { ?>
    <a href='pharmacyinpatientlist.php?PharmacyInpatientList=PharmacyInpatientListThisPage' class='art-button-green'>INPATIENTS LIST</a>
<?php } ?>
<?php if (getButtonStatus("medication_history") == "visible") { ?>
    <input type="button" value="MEDICATION HISTORY" class='art-button-green' onclick="Preview_Medication_History(<?php echo $Payment_Cache_ID; ?>)">
<?php } ?>
<?php if (getButtonStatus("pharmacy_works_pharmacy") == "visible") { ?>
    <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>PHARMACY WORKS</a>
<?php }
if($from_billing_work=="from_billing_work"){ ?>
    <a href='previewpatientbilldetails.php?Check_In_ID=<?= $Check_In_ID ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage' class='art-button-green'>BACK</a>
   <?php
}else{ ?>
<a href='pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm' class='art-button-green'>BACK</a>
<!-- new date function (Contain years, Months and days)-->
<?php
}
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->




<!--Approved message-->
<script type='text/javascript'>
    function approved_Message() {
    }

    function approved_Message2() {
        alert('Payment Information is already sent to CASHIER!');
        return false;
    }

    function Payment_approved_Message() {
        alert('    Patient\'s medication is not yet paid. Please advice PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');
    }


</script>
<!-- end of approved message-->
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
    label{
        font-weight: normal;
    }
</style>



<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php

// select patient information
function dateDiff($d1, $d2) {
// Return the number of days between the two dates:
    return round(abs(strtotime($d1) - strtotime($d2)) / ( 60 * 60 ));
}

// end function dateDiff

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $select_Patient = mysqli_query($conn,"SELECT * from tbl_payment_cache pc,tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
                                            ilc.Payment_Cache_ID=pc.Payment_Cache_ID and
						    ilc.Consultant_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Payment_Method = $row['payment_method'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Temp_Billing_Type = $row['Billing_Type'];
            $Consultant = $row['Employee_Name'];
            $Folio_Number = $row['Folio_Number'];
            $Require_Document_To_Sign_At_receiption = $row['Require_Document_To_Sign_At_receiption'];
            $Exemption = $row['Exemption'];
            $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
            $allow_dispense_control = $row['allow_dispense_control'];

            if (strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit') {
                $Billing_Type = 'Outpatient ' . $Transaction_Type;
            } elseif (strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit') {
                $Billing_Type = 'Inpatient ' . $Transaction_Type;
            }


            // echo $Ward."  ".$District."  ".$Ward; exit;
        }


        //$Today=date('Y-m-d H:i:s');

        $Today = $original_Date;

        if (isset($_SESSION['systeminfo']['Dispense_Credit_Patients_after_24_hrs']) && strtolower($_SESSION['systeminfo']['Dispense_Credit_Patients_after_24_hrs']) == 'yes') {
            $numberDays = dateDiff($Transaction_Date_And_Time, $Today);
        } else {

            $numberDays = 0;
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
        $Payment_Cache_ID = '';
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Payment_Method = "";
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Consultant = '';
        $Folio_Number = '';
        $Billing_Type = '';
        $Require_Document_To_Sign_At_receiption = '';
        $Exemption = 'no';
        $allow_dispense_control = 'no';
    }
} else {
    $Payment_Cache_ID = '';
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Payment_Method = "";
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Consultant = '';
    $Folio_Number = '';
    $Billing_Type = '';
    $Require_Document_To_Sign_At_receiption = '';
    $Exemption = 'no';
    $allow_dispense_control = 'no';
}
if(isset($_GET['Check_In_Type'])){
    $Check_In_Type=$_GET['Check_In_Type'];
}else{
   $Check_In_Type="";
}
if (strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'inpatient cash') {
    //check if inpatient then make sure admitted
    $get_status = mysqli_query($conn,"select Discharge_Clearance_Status,Admission_Status,Clearance_Date_Time from
									tbl_admission ad, tbl_check_in_details cid, tbl_payment_cache pc where
									pc.consultation_id = cid.consultation_ID and
									ad.Admision_ID = cid.Admission_ID and
									pc.Registration_ID = '$Registration_ID' and
									pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    $num_get = mysqli_num_rows($get_status);
    if ($num_get > 0) {
        while ($dataz = mysqli_fetch_array($get_status)) {
            $Discharge_Clearance_Status = $dataz['Discharge_Clearance_Status'];
            $Clearance_Date_Time = @date("d F Y H:i:s", strtotime($dataz['Clearance_Date_Time']));
            $Admission_Status = $dataz['Admission_Status'];
        }
    } else {
        $Discharge_Clearance_Status = '';
        $Clearance_Date_Time = '';
        $Admission_Status = '';
    }
} else {
    $Discharge_Clearance_Status = '';
    $Clearance_Date_Time = '';
    $Admission_Status = '';
}
?>


<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>

<!-- get receipt number and date -->
<?php
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    //get Receipt Date & Time
    $slcts = mysqli_query($conn,"select Payment_Date_And_Time from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    $nmz = mysqli_num_rows($slcts);
    if ($nmz > 0) {
        while ($dts = mysqli_fetch_array($slcts)) {
            $Payment_Date_And_Time = $dts['Payment_Date_And_Time'];
        }
    } else {
        $Payment_Date_And_Time = '';
    }
} else {
    if (isset($_GET['Payment_Cache_ID'])) {
        $Get_Receipt = mysqli_query($conn,"
        		select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'dispensed' and
        		    Payment_Cache_ID = '$Payment_Cache_ID' and

        			Transaction_Type = '$Transaction_Type' and Check_In_Type='$Check_In_Type' group by Patient_Payment_ID limit 1");
        $no_of_rows = mysqli_num_rows($Get_Receipt);
        if ($no_of_rows > 0) {
            while ($row = mysqli_fetch_array($Get_Receipt)) {
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            }
        } else {
            $Get_Receipt = mysqli_query($conn,"
        		select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'paid' and
        		    Payment_Cache_ID = '$Payment_Cache_ID' and

        			Transaction_Type = '$Transaction_Type' and Check_In_Type='$Check_In_Type' group by Patient_Payment_ID limit 1");
            $no_of_rows = mysqli_num_rows($Get_Receipt);
            if ($no_of_rows > 0) {
                while ($row = mysqli_fetch_array($Get_Receipt)) {
                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                }
            } else {
                $Patient_Payment_ID = '';
                $Payment_Date_And_Time = '';
            }
        }
    } else {
        $Patient_Payment_ID = '';
        $Payment_Date_And_Time = '';
    }
}

//get folio number
if ($Patient_Payment_ID > 0) {
    $slct = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    $nmz = mysqli_num_rows($slct);
    if ($nmz > 0) {
        while ($dt = mysqli_fetch_array($slct)) {
            $Folio_Number = $dt['Folio_Number'];
        }
    } else {
        $Folio_Number = '';
    }
} else {
    $Folio_Number = '';
}
?>
<!-- end of process (getting receipt number)-->




<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

    <style>
        .nshwbdr table{
            border-collapse:collapse !important;
            border:none !important;
        }.nshwbdr tr{
            border-collapse:collapse !important;
            border:none !important;
        }.nshwbdr td{
            border-collapse:collapse !important;
            border:none !important;
        }

    </style>

    <fieldset>
        <legend style='background-color:#006400;color:white;padding:8px;' align=right><b><?php
                if (isset($_SESSION['Pharmacy_ID'])) {
                    echo $Sub_Department_Name;
                }
                ?></b></legend>
        <center>
            <table  width="100%">
                <tr>
                    <td width='10%' style='text-align: right;'>Costumer Name</td>
                    <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td width='12%' style='text-align: right;'>Card Expire Date</td>
                    <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                    <td width='11%' style='text-align: right;'>Gender</td>
                    <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                    <td style='text-align: right;'>Receipt Number</td>
                    <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Billing Type</td>
                    <td>
                        <select name='Billing_Type' id='Billing_Type'>
                            <option selected='selected'><?php echo $Billing_Type; ?></option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Claim Form Number</td>

                    <!-- Select the last claim form number-->
                    <?php
//select the last claim form number
                    $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
									Folio_number = '$Folio_Number' and
									    Registration_ID = '$Registration_ID' and
										Sponsor_ID = '$Sponsor_ID'
										    order by patient_payment_id desc limit 1");
                    $nm = mysqli_num_rows($select_claim_form);
                    if ($nm > 0) {
                        while ($row = mysqli_fetch_array($select_claim_form)) {
                            $Claim_Form_Number = $row['Claim_Form_Number'];
                        }
                    } else {
                        $Claim_Form_Number = '';
                    }
                    ?>


<!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
                    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='<?php echo $Claim_Form_Number; ?>'></td>
                    <td style='text-align: right;'>Occupation</td>
                    <td>
                        <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                    </td>

                    <td style='text-align: right;'>Receipt Date & Time</td>
                    <td>
                        <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
                        <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Type Of Check In</td>
                    <td>
                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'>
                            <option selected='selected'>Pharmacy</option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                    <td style='text-align: right;'>Registered Date</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                    <td style='text-align: right;'>Folio Number</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Costumer Direction</td>
                    <td>
                        <select id='direction' name='direction' required='required'>
                            <option selected='selected'>Others</option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Sponsor Name</td>
                    <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                    <td style='text-align: right;'>Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>

                    <td style='text-align: right;'>Prepared By</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Consultant</td>
                    <td>
                        <select name='Consultant' id='Consultant'>
                            <option selected='selected'><?php echo $Consultant; ?></option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Registration Number</td>
                    <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                    <td style='text-align: right;'>Member Number</td>
                    <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>

                    <td style='text-align: right;'>Supervised By</td>

                    <?php
                    if (isset($_SESSION['Pharmacy_Supervisor'])) {
                        if (isset($_SESSION['Pharmacy_Supervisor']['Session_Master_Priveleges'])) {
                            if ($_SESSION['Pharmacy_Supervisor']['Session_Master_Priveleges'] = 'yes') {
                                $Supervisor = $_SESSION['Pharmacy_Supervisor']['Employee_Name'];
                            } else {
                                $Supervisor = "Unknown Supervisor";
                            }
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                    ?>
                    <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset class="nshwbdr">
        <center>
            <table  width=100%>
                <tr>
                    <td style='text-align: center;'>
                        <?php
                        if (isset($_GET['Payment_Cache_ID'])) {
                            $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
                        } else {
                            $Payment_Cache_ID = '';
                        }if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }
                        $Transaction_Status_Title = '';


                        if (isset($_GET['Payment_Cache_ID'])) {
                            // echo " P: " .$_GET['Payment_Cache_ID'];
                        }
                        if (isset($_GET['Registration_ID'])) {    //check if patient selected
                            //create sql
                            // echo "<script>alert(".$_GET['Registration_ID'].")</script>";
                            // echo " R: ".$_GET['Registration_ID'];

                            $Check_Status = "SELECT Status, Transaction_Type from tbl_item_list_cache where
							Transaction_Type = '$Transaction_Type' and
						    Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type='$Check_In_Type' and
							   status = ";

                            $sql_Dispensed = $Check_Status . "'dispensed'";
                            $select_Status = mysqli_query($conn,$sql_Dispensed);
                            $no = mysqli_num_rows($select_Status);

                            if ($no > 0) {
                                $sql_Active = $Check_Status . "'active'";
                                //check for active medication
                                $select_Status = mysqli_query($conn,$sql_Active);
                                $no = mysqli_num_rows($select_Status);

                                if ($no > 0) {
                                    $Transaction_Status_Title = 'NOT YET APPROVED';
                                } else {
                                    //check if there is no any paid medication
                                    $sql_Paid = $Check_Status . "'paid'";
                                    $select_Status = mysqli_query($conn,$sql_Paid);
                                    $no = mysqli_num_rows($select_Status);
                                    if ($no > 0) {
                                        $Transaction_Status_Title = 'PAID';
                                    } else {
                                        if (isset($_GET['Status'])) {
                            // echo $_GET['Status'];
                            $Transaction_Status_Title = $_GET['Status'];
                        }else{
                                        $Transaction_Status_Title = 'DISPENSED';
                                    }
                                    }
                                }
                            } else {
                                $sql_Active = $Check_Status . "'active'";
                                //check for active medication
                                $select_Status = mysqli_query($conn,$sql_Active);
                                $no = mysqli_num_rows($select_Status);

                                if ($no > 0) {
                                    $Transaction_Status_Title = 'NOT YET APPROVED';
                                } else {
                                    //check for removed but no approved medication
                                    $sql_Removed = $Check_Status . "'removed'";
                                    $select_Status = mysqli_query($conn,$sql_Removed);
                                    $no = mysqli_num_rows($select_Status);

                                    if ($no > 0) {
                                        //check if there is no any approved
                                        $sql_Approved = $Check_Status . "'approved'";
                                        $select_Status = mysqli_query($conn,$sql_Approved);
                                        $no = mysqli_num_rows($select_Status);

                                        if ($no > 0) {
                                            //check if there is no any paid medication
                                            $sql_Paid = $Check_Status . "'paid'";
                                            $select_Status = mysqli_query($conn,$sql_Paid);
                                            $no = mysqli_num_rows($select_Status);
                                            if ($no > 0) {
                                                $Transaction_Status_Title = 'PAID';
                                            } else {
                                                $Transaction_Status_Title = 'APPROVED';
                                            }
                                        } else {
                                            //check if there is no paid medication
                                            $sql_Paid = $Check_Status . "'paid'";
                                            $select_Status = mysqli_query($conn,$sql_Paid);
                                            $no = mysqli_num_rows($select_Status);
                                            if ($no > 0) {
                                                $Transaction_Status_Title = 'PAID';
                                            } else {
                                                $Transaction_Status_Title = 'ALL MEDICATION REMOVED';
                                            }
                                        }
                                    } else {
                                        //check for approved
                                        $sql_Approved = $Check_Status . "'approved'";
                                        $select_Status = mysqli_query($conn,$sql_Approved);
                                        $no = mysqli_num_rows($select_Status);
                                        if ($no > 0) {
                                            //check if there is no paid medication
                                            $sql_Paid = $Check_Status . "'paid'";
                                            $select_Status = mysqli_query($conn,$sql_Paid);
                                            $no = mysqli_num_rows($select_Status);
                                            if ($no > 0) {
                                                $Transaction_Status_Title = 'PAID';
                                            } else {
                                                $Transaction_Status_Title = 'APPROVED';
                                            }
                                        } else {
                                            $Transaction_Status_Title = 'PAID';
                                        }
                                    }
                                }
                            }
                        } else {
                            $Transaction_Status_Title = 'NO PATIENT SELECTED';
                        }
                        if ($Transaction_Status_Title == 'NOT YET APPROVED' && isset($_SESSION['systeminfo']['Change_Medication_Location']) && strtolower($_SESSION['systeminfo']['Change_Medication_Location']) == 'yes') {
//                            echo "<input type='button' name='Swap_Medication' id='Swap_Medication' value='CHANGE ITEM LOCATION' class='art-button-green' onclick='Change_Pharmacy()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                        if (strtolower($Transaction_Status_Title) == 'approved' && strtolower($Exemption) == 'yes' && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit')) {
                            //Get employee approved
                            $get_det = mysqli_query($conn,"SELECT emp.Employee_Name, ilc.Approval_Date_Time from
                                                    tbl_employee emp, tbl_item_list_cache ilc where
                                                    emp.Employee_ID = ilc.Approved_By and
                                                    ilc.Payment_Cache_ID = '$Payment_Cache_ID' limit 1") or die(mysqli_error($conn));
                            $nm_det = mysqli_num_rows($get_det);
                            if ($nm_det > 0) {
                                while ($rw = mysqli_fetch_array($get_det)) {
                                    $Approved_Title = ' - <b>(Approved By ' . $rw['Employee_Name'] . ' - ' . @date("d F Y H:i:s", strtotime($rw['Approval_Date_Time'])) . ')</b>';
                                }
                            } else {
                                $Approved_Title = '';
                            }
                        } else {
                            $Approved_Title = '';
                        }


                        echo 'STATUS : ' .$Transaction_Status_Title . $Approved_Title;

                        if ($_SESSION['systeminfo']['Enable_Add_More_Medication'] == 'yes') {
                            if ($Transaction_Status_Title == 'NOT YET APPROVED') {
                                echo '<button class="art-button-green" type="button" style="float:left;" onclick="openItemDialog()">ADD MORE ITEMS</button><img id="loader" style="float:left;display:none" src="images/22.gif"/>';
                            }
                        }

                        ?>
                    </td>
                    
                    <td style='text-align: right;' width=60%>
                        <?php
                        $Check_Status = mysqli_query($conn,"select status from tbl_item_list_cache where status = 'approved' and
					    Payment_Cache_ID = '$Payment_Cache_ID' and
						Transaction_Type = '$Transaction_Type'");
                        $Check_Status2 = mysqli_query($conn,"SELECT status from tbl_item_list_cache where status = 'active' and
					    Payment_Cache_ID = '$Payment_Cache_ID' and
						Transaction_Type = '$Transaction_Type'
						    ");

                        $no = mysqli_num_rows($Check_Status);
                        $no2 = mysqli_num_rows($Check_Status2);

//check if system setting is centralized and/or departmental
//get branch id
                        if (isset($_SESSION['userinfo']['Branch_ID'])) {
                            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                        } else {
                            $Branch_ID = 0;
                        }

                        if (isset($_SESSION['systeminfo'])) {
                            $Centralized_Collection = $_SESSION['systeminfo']['Centralized_Collection'];
                            $Departmental_Collection = $_SESSION['systeminfo']['Departmental_Collection'];
                        } else {
                            $Centralized_Collection = 'yes1';
                            $Departmental_Collection = 'no1';
                        }

                        if (strtolower($Centralized_Collection) == 'yes' || strtolower($Departmental_Collection) == 'no') {
                            if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                                if ($Transaction_Status_Title != 'DISPENSED') {
                                    if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                                        if ($Transaction_Status_Title != 'PAID') {
                                            if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { //this setting allows system to display both (send to cashier) and (make payment) buttons
                                             //   echo "<input type='button' name='Make_Pay' $show_make_payment id='Make_Pay' class='art-button-green' value='Make Payment' onclick='Make_Payment()'>";
                                            }
                                            ?>
                        <input type="button" value="Create Outpatient Bill" class="art-button-green">
                                            <?php
                                            if ($no <= 0 || $no2 > 0) {
                                                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment') {
                                                    ?>
                                                    <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                                                <?php } if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment') {?>
                                                    <input type='button'  class='art-button-green' value='Make Payment' onclick='Make_Payment()'>
                                                        <?php } ?>
                                                <input type="button" name="s_2_Cashier" id="s_2_Cashier" onclick="Send_To_Cashier()" class="art-button-green" value="Send To Cashier"/>
                                          <!--  <a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return approved_Message();'>Send To Cashier</a> -->
                                            <?php } else { ?>
                                                <input type="button" name="s_2_Cashier" id="s_2_Cashier" onclick="Send_To_Cashier2()" class="art-button-green" value="Send To Cashier"/>
                                                      <!--  <a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return approved_Message2();'>Send To Cashier.</a> -->
                                                <?php
                                            }

                                            if (strtolower($Billing_Type) == 'inpatient cash') {
                                                //show dispense and bill medication for inpmatient cash
                                                echo '<input type="button" name="D_Credit" id="D_Credit" value="Bill & Dispence Medication" class="art-button-green" onclick="dispence_and_bill()">';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($Transaction_Status_Title != 'DISPENSED') {
                                    if (strtolower($Billing_Type) == 'outpatient cash') {
                                        if ($Transaction_Status_Title != 'PAID') {
                                            if ($no <= 0 || $no2 > 0) {
                                                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment') {
                                                    ?>
                                                    <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                                                <?php } if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment') { ?>
                                                    <input type='button'  class='art-button-green' value='Make Payment' onclick='Make_Payment()'>
                                                        <?php } ?>
                                              <!-- comment kuondoa 2 button  <input type="button" name="s_2_Cashier" id="s_2_Cashier" onclick="Send_To_Cashier()" class="art-button-green" value="Send To Cashier,,,,,">-->
                                                <!-- <a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return confirm("are you sure you want to send to cashier ?");return approved_Message();'>Send To Cashier</a>
                                                    --><button onclick="Send_To_Cashier()" type="button" class='art-button-green'>Send To Cashier</button>
 <?php } else { ?>
                                              <!-- comment kuondoa 2 button  <input type="button" name="s_2_Cashier2" id="s_2_Cashier2" onclick="Send_To_Cashier2()" class="art-button-green" value="Send To Cashier ---">-->
                                              <a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return approved_Message2();'>Send To Cashier</a>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif (strtolower($Departmental_Collection) == 'yes' && $Transaction_Status_Title != 'DISPENSED' && $Transaction_Status_Title != 'PAID' && $Transaction_Status_Title != 'NO PATIENT SELECTED') {
                            if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                                if ($Transaction_Status_Title != 'APPROVED' && $Transaction_Status_Title != 'PAID' && (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash')) {
                                    if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
                                        if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment') {
                                        echo "<input type='button' name='Make_Pay' id='Make_Pay' class='art-button-green'   value='Make Payment' onclick='Make_Payment()'>";
                                        }
                                        //echo "<a href='Departmental_Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Billing_Type."' class='art-button-green'>Make Payment</a>";
                                    } else {
                                        echo '<input type="button" name="s_2_Cashier" id="s_2_Cashier" onclick="Send_To_Cashier()" class="art-button-green" value="Send To Cashier">';
                                        //echo "<a href='Send_To_Cashier.php?Payment_Cache_ID= ".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_Name=".$Sub_Department_Name."&Registration_ID=".$Registration_ID."' class='art-button-green' onclick='return approved_Message();'>Send To Cashier</a> ";
                                    }
                                }
                            } else {
                                if ($Transaction_Status_Title != 'APPROVED' && $Transaction_Status_Title != 'PAID' && strtolower($Billing_Type) == 'outpatient cash') {
                                    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
                                    if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
                                        if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment') {
                                        echo "<input type='button' name='Make_Pay' id='Make_Pay' class='art-button-green' value='Make Payment'   onclick='Make_Payment()'>";
                                        }
                                        //echo "<a href='Departmental_Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Billing_Type."' class='art-button-green'>Make Payment</a>";
                                    } else {
                                        echo '<input type="button" name="s_2_Cashier" id="s_2_Cashier" onclick="Send_To_Cashier()" class="art-button-green" value="Send To Cashier">';
                                        //echo "<a href='Send_To_Cashier.php?Payment_Cache_ID= ".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_Name=".$Sub_Department_Name."&Registration_ID=".$Registration_ID."' class='art-button-green' onclick='return approved_Message();'>Send To Cashier</a> ";
                                    }
                                }
                            }
                        }
                        ?>
                        <?php
                        //get sub department id
                        if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }

                        $disp = '';

                        if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                            $disp = 'Dispense Item';
                        } else {
                            $disp = 'Dispense & Bill Item';
                        }
                        ?>
                        <?php
                        if ($Transaction_Status_Title != 'DISPENSED') {
                            if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash' || strtoupper($Transaction_Status_Title) == 'PAID') {
                                ?>

                                <?php if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') { ?>
                                    <?php if (strtoupper($Transaction_Status_Title) == 'PAID' && $Transaction_Status_Title != 'NO PATIENT SELECTED') { ?>
                                        <input type="button" name="Disp" id="Disp" onclick="Dispense_Medication();" class="art-button-green" value="<?php echo $disp; ?>">
                                            <!-- <a href='Dispense_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>' class='art-button-green'><?php echo $disp; ?></a> -->
                                    <?php } else { ?>
                                        <button class='art-button-green' onclick='return Payment_approved_Message()'><?php echo $disp; ?></button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if (strtoupper($Transaction_Status_Title) == 'PAID' && $Transaction_Status_Title != 'NO PATIENT SELECTED') { ?>
                                        <input type="button" name="Disp" id="Disp" onclick="varidate_dosage_duration('after_bill')" class="art-button-green" value="Dispense">
                                            <!-- <a href='Dispense_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>' class='art-button-green'><?php echo $disp; ?></a> -->
                                    <?php } else if (strtolower($Billing_Type) == 'inpatient cash') { ?>
                                        <input type="button" name="D_Credit" id="D_Credit" value="<?php echo $disp; ?>" class="art-button-green" onclick="varidate_dosage_duration();">
                                    <?php } else { ?>
                                        <button class='art-button-green' onclick='return Payment_approved_Message()'><?php echo $disp; ?></button>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else { ?>
                                <?php if ($Transaction_Status_Title != 'ALL MEDICATION REMOVED' && $Transaction_Status_Title != 'NO PATIENT SELECTED') { ?>
                                    <input type="button" name="D_Credit" id="D_Credit" value="<?php echo $disp; ?>" class="art-button-green" onclick="varidate_dosage_duration('before_bill');">
                                    <!-- <a href='Dispense_Credits_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>' class='art-button-green'><?php echo $disp; ?></a> -->
                                <?php } ?>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if ($Patient_Payment_ID != '' && ($Transaction_Status_Title == 'PAID' || $Transaction_Status_Title == 'DISPENSED')) {
                            if (isset($_GET['Transaction_Type'])) {
                                $Type = $_GET['Transaction_Type'];
                            } else {
                                $Type = 'Cash';
                            }

                            if ($Type != 'Cash') {
                                //echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
                                //Print Debit Note
                                //</a>";
                                echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Debit Note' onclick='Print_Receipt_Payment()' class='art-button-green'>";
                            } else {
                                //echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
                                //Print Receipt
                                //</a>";
                                echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Receipt' onclick='Print_Receipt_Payment()' class='art-button-green'>";
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset class="nshwbdr">
    <?php
    if (isset($_GET['Check_In_Type'])) {
        // echo $_GET['Check_In_Type'];
    }
     ?>
        <center>
            <table width=100%>
                <tr>
                    <td>
                        <form id="saveDiscount">
                            <fieldset id="patientItemsList" style='height:200px;overflow-y:scroll;'>
                                <?php
                                include "Pharmacy_Works_Page_Iframe.php";
                                ?>
                            </fieldset>
                        </form>
<!--			<iframe src='Patient_Billing_Laboratory_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>' width='100%' height=270px></iframe>-->
                    </td>
                </tr>

            </table>
        </center>
        <div id="totalAmounts" style="text-align:right;padding-right:30px  ">
            <div id="Total_Area"><?php echo $dataAmount; ?></div>
        </div>
    </fieldset>
    <!--Dialog div-->
    <div id="addTests" style="width:100%;overflow:hidden; display: none;" >
        <fieldset>
            <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
            <center>
                <table width = "100%"  border="1">
                    <tr>
                        <td width="40%" style="text-align: center"><input type="text" name="search" id="search_medicene" placeholder="-----------------------------------------Search Item-------------------------------------------" onkeyup="searchMedicene(this.value)"></td><td width="50%" style="text-align: center"><button style="width:90%;font-size:20px; " name="submitadded" class="art-button-green" type="button" onclick="submitAddedItems()">Add Item more(s)</button></td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: center"><b>Items</b></td><td width="50%" style="text-align: center"><b>Chosen Tests</b></td>
                    </tr>
                    <tr>
                        <td width="40%">
                            <!--Show tests for the section-->
                            <div id="items_to_choose" style="height:400px;overflow-x:hidden;overflow-y: scroll ">
                                <table id="loadDataFromItems">
                                </table>
                            </div>
                        </td>
                        <td width="50%">
                            <!--Display selected tests for the section-->
                            <div id="displaySelectedTests"  style="height:400px;width:100% ">
                                <form id="addedItemForm" action="" method="post">
                                    <table width="100%" id="getSelectedTests">
                                        <tr>
                                            <td style="width:35%" ><b>Description</b></td><td style="width:15%"><b>Price</b></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
            </center>
        </fieldset><br/>

    </div>

    <div id="ePayment_Window" style="width:50%;">
        <span id='ePayment_Area'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </span>
    </div>
    <div id="testwindow">

    </div>


    <script>
        $(document).ready(function () {
//            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
//            //$("#testwindow").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
        });
    </script>

    <!--<fieldset>
            <center>
                <table width=100%>
                    <tr>
                        <td>

                        </td>
                    </tr>
                </table>
            </center>
    </fieldset>-->

    <div id="Previous_History">
        <span id="Patient_Details_Area">

        </span>
    </div>

    <div id="Add_New_Item">
        <span id='Add_New_Items_Area'>

        </span>
    </div>
    <div id="Non_Supported_Item">
        <center>
            Selected Item is not supported by <?php echo $Guarantor_Name; ?><br/>
            Select only supported items.
        </center>
    </div>
    <div id="Change_Medication_Location_Confirm">
        <center>Are you sure you want to update?</center><br/>
        <table width="100%">
            <tr>
                <td style="text-align: right;">
                    <input type="button" value="YES" class="art-button-green" onclick="Change_Pharmaceutical_Location()">
                    <input type="button" value="CANCEL" class="art-button-green" onclick="Change_Medication_Location_Confirm_Close()">
                </td>
            </tr>
        </table>
    </div>


    <div id="Change_Medication_Location">
        <center id="Change_Pharmacy_Area">

        </center>
    </div>

    <div id="Inpatient_Notification">
        Mgonjwa uliyemchagua bili yake ilishafungwa tarehe <?php echo $Clearance_Date_Time; ?>
        <br/><br/>
        <table width="100%">
            <tr>
                <td style="text-align: right;">
                    <input type="button" value="CLOSE" class="art-button-green" onclick="Close_Notification()">
                </td>
            </tr>
        </table>
    </div>
    <div id="Approval_Required">
        Approval Required!<br/> Please inform patient to go to approval center to approve this bill
    </div>

    <script type="text/javascript">
        function Close_Notification() {
            $("#Inpatient_Notification").dialog("close");
        }
    </script>
    <script type="text/javascript">
        function Send_To_Cashier2() {
            approved_Message2();
        }
    </script>
    <script type="text/javascript">
        function Send_To_Cashier() {
            validate_issues();

            var sms = confirm("Are you sure you want to send to cashier?");
            if (sms == true) {
                approved_Message();

                document.location = "Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Check_In_Type=<?= $Check_In_Type ?>";
            }
        }
    </script>
        <div id="dossage_feedback_message">

        </div>
    <script type="text/javascript">

        function close_dialog_drug_duration_alert(){
            $("#dossage_feedback_message").dialog("close");
        }
        function varidate_dosage_duration(after_bill){
            var uri="varidate_dosage_duration.php";
             var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
             var Transaction_Type = '<?php echo $Transaction_Type; ?>';
             var Registration_ID='<?php echo $Registration_ID; ?>';
             var check="false";
             $.ajax({
                type: 'GET',
                url: uri,
                data: {Payment_Cache_ID : Payment_Cache_ID,Transaction_Type:Transaction_Type,Registration_ID:Registration_ID,after_bill:after_bill},
                success: function(data){
                    //alert("moja")

                   if(data==1){
                    // alert(after_bill)
                       if(after_bill=="after_bill"){
                          // alert('after na bill')
                          console.log(after_bill);
                           Dispense_Medication()
                       }else if(after_bill=='before_bill'){
                           console.log(after_bill)
                           Dispense_Credit_Medication()
                       }else{
                          // alert('credit')
                          //do nothing
                       }

                   }else{
                      $("#dossage_feedback_message").dialog({autoOpen: false, width: '75%', height: 450, title: 'PATIENT MEDICINE DOSSAGE DURATION ALERT', modal: true});
                      $("#dossage_feedback_message").dialog("open").html(data);

             }
                },
                error: function(){

                }
            });
        }

        function dispence_and_bill(){
            validate_issues();
            var sms = confirm("Are you sure you want to bill & dispense selected medication");
                        if (sms == true) {
                            document.location = "Dispense_Credits_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>&approve_yes=yes&inpatient_cash_post=yes&Check_In_Type=<?= $Check_In_Type ?>";
                        }
        }
        function Dispense_Credit_Medication() {
          // var duration_txt= $("#duration_txt").val()
          // if(parseInt(duration_txt)<=0){
          //      alert("you must specify dosage duration for each medicine");
           //     exit;
          // }
         // alert(varidate_dosage_duration())

            var bill_type = document.getElementById('Billing_Type').value;
            var passedTime = '<?php echo $numberDays; ?>';
            var Admission_Status = '<?php echo $Admission_Status; ?>';

            if ((bill_type == 'Outpatient Credit') && passedTime > 24) {

                alert('Credit medication cannot be dispensed after 24 hrs');

            } else if (bill_type == 'Inpatient Credit' && Admission_Status == 'Discharged') {

                alert('Credit medication cannot be dispensed after the patient is discharged');
            } else {

                validate_issues();

                var Allow_Cashier_To_Approve_Pharmaceutical = '<?php echo strtolower($Allow_Cashier_To_Approve_Pharmaceutical); ?>';
                var Require_Document_To_Sign_At_receiption = '<?php echo strtolower($Require_Document_To_Sign_At_receiption); ?>';
                var Transaction_Status_Title = '<?php echo strtolower($Transaction_Status_Title); ?>';
                var Billing_Type = '<?php echo strtolower($Billing_Type); ?>';
                var Discharge_Clearance_Status = '<?php echo $Discharge_Clearance_Status; ?>';

                if (Allow_Cashier_To_Approve_Pharmaceutical == 'yes' && Require_Document_To_Sign_At_receiption == 'mandatory' && Transaction_Status_Title == 'not yet approved') {
                    $("#Approval_Required").dialog("open");
                } else {
                    if (Discharge_Clearance_Status == 'cleared') {
                        $("#Inpatient_Notification").dialog("open");
                    } else {
                        var sms = confirm("Are you sure you want to dispense selected medication");
                        if (sms == true) {
                            document.location = "Dispense_Credits_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>&approve_yes=yes&Check_In_Type=<?= $Check_In_Type ?>";
                        }
                    }
                }
            }

     }
    </script>

    <script type="text/javascript">
        function Dispense_Medication() {
            validate_issues();
            var Allow_Cashier_To_Approve_Pharmaceutical = '<?php echo strtolower($Allow_Cashier_To_Approve_Pharmaceutical); ?>';
            var Require_Document_To_Sign_At_receiption = '<?php echo strtolower($Require_Document_To_Sign_At_receiption); ?>';
            var Transaction_Status_Title = '<?php echo strtolower($Transaction_Status_Title); ?>';
            var Billing_Type = '<?php echo strtolower($Billing_Type); ?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Discharge_Clearance_Status = '<?php echo $Discharge_Clearance_Status; ?>';


            if (Allow_Cashier_To_Approve_Pharmaceutical == 'yes' && Require_Document_To_Sign_At_receiption == 'mandatory' && Transaction_Status_Title == 'not yet approved') {
                $("#Approval_Required").dialog("open");
            } else {
                if (Discharge_Clearance_Status == 'cleared') {
                    $("#Inpatient_Notification").dialog("open");
                } else {
                    var sms = confirm("Are you sure you want to dispense selected medication?");
                    if (sms == true) {
                        document.location = "Dispense_Medication.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Check_In_Type=Pharmacy";
                    }
                }
            }
         }

    </script>
<div id="myDiaglog" style="display:none;">


</div>
<script type="text/javascript">


        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }

    }
    function get_terminals(trans_type){


         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
                document.location = "Departmental_Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>"+'&manual_offline=manual&approve_yes=yes&Check_In_Type=<?= $Check_In_Type ?>';
           }
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }


      function offline_transaction(amount_required,reg_id){

        var uri = '../epay/pharmacyworkspageOfflinePayment.php';


        Payment_Cache_ID='<?php echo $Payment_Cache_ID; ?>'
        Transaction_Type='<?php echo $Transaction_Type; ?>'
        Sub_Department_ID='<?php echo $Sub_Department_ID; ?>'
        Registration_ID='<?php echo $Registration_ID; ?>'
        Billing_Type='<?php echo $Billing_Type; ?>'

        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){

            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Payment_Cache_ID:Payment_Cache_ID,Transaction_Type:Transaction_Type,Sub_Department_ID:Sub_Department_ID,Registration_ID:Registration_ID,Billing_Type:Billing_Type},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        }
    }
</script>
    <script type="text/javascript">
        function Make_Payment() {
            validate_issues();
            var reg_id='<?php echo $Registration_ID; ?>';
            var amount_required=document.getElementById("total_txt").value;
            offline_transaction(amount_required,reg_id);
            //var sms = confirm("Are you sure you want to make payment?");
            //if (sms == true) {
          //}
        }
    </script>
    <script type="text/javascript">

        function openItemDialog() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID='<?=  $Sponsor_ID ?>';
            if (window.XMLHttpRequest) {
                myObjectAddItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddItem.overrideMimeType('text/xml');
            }
///Check_In_Typ
            myObjectAddItem.onreadystatechange = function () {
                data_Add_New = myObjectAddItem.responseText;
                if (myObjectAddItem.readyState == 4) {
                    document.getElementById("Add_New_Items_Area").innerHTML = data_Add_New;
                    $("#Add_New_Item").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectAddItem.open('GET', 'Patient_Billing_Pharmacy_Add_New_Item.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID, true);
            myObjectAddItem.send();
        }

        // readd removed items
        function openRemovedItemDialog() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID='<?=  $Sponsor_ID ?>';
            if (window.XMLHttpRequest) {
                myObjectAddItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddItem.overrideMimeType('text/xml');
            }
///Check_In_Typ
            myObjectAddItem.onreadystatechange = function () {
                data_Add_New = myObjectAddItem.responseText;
                if (myObjectAddItem.readyState == 4) {
                    document.getElementById("Add_New_Items_Area").innerHTML = data_Add_New;
                    $("#Add_New_Item").dialog("open");


                    // test the function here
$("#readd").click(function(e){
    e.preventDefault()
    var paymentId = $("#paymentId").val();
    var paymentListId = $("#paymentListId").val();
    var Registration_ID = <?php echo $Registration_ID; ?>;
    var Item_ID = $(".itemId").val();
    console.log(Registration_ID + " " + Item_ID )

    $.ajax({
                type: "GET",
                url: "AddPharmacyItem.php",
                data: {Item_ID:Item_ID, Registration_ID:Registration_ID,Payment_Cache_ID:paymentId,Payment_Item_Cache_List_ID:paymentListId},
                success: function (result) {
                    if (result === "endelea") {
                        check_for_duplicate();
                    } else {
                        if (confirm(result)) {
                            check_for_duplicate();
                        }
                    }
                }});

})

// function to check for dublicate while activating


function check_for_duplicate() {
            // var Item_ID = document.getElementById("Item_ID").value;
            var Item_ID = $(".itemId").val();
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';

            $.ajax({
                type: 'GET',
                url: 'checkforduplicatepharmacy.php',
                data: 'Item_ID=' + Item_ID + '&Payment_Cache_ID=' + Payment_Cache_ID,
                cache: false,
                success: function (html) {
                    // if (html == '1') {
                    //     alertMsgSimple("Process the current item or remove and re-add it again.", "Duplicate Items", "error", 400, false, 'Ok');
                    //     exit;
                    // } else {
                        // Get_Selected_Item();
                    // }
                }
            });
        }

                    // end test the function here
                }
            }; //specify name of function that will handle server response........

            myObjectAddItem.open('GET', 'Pharmacy_Works_Page_Iframe2.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID, true);
            myObjectAddItem.send();
        }



    </script>
    <script>
        function Get_Details(Item_Name, Item_ID) {
            document.getElementById('Comment').value = '';

            if (window.XMLHttpRequest) {
                myObjectGetItemName = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItemName.overrideMimeType('text/xml');
            }

            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
            document.getElementById("Quantity").value = 1;
        }
    </script>

    <script type="text/javascript">
        function Get_Item_Name(Item_Name, Item_ID) {
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
            if (Billing_Type == 'Outpatient Credit') {
                if (window.XMLHttpRequest) {
                    My_Object_Verify_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    My_Object_Verify_Item.overrideMimeType('text/xml');
                }

                My_Object_Verify_Item.onreadystatechange = function () {
                    dataVerify = My_Object_Verify_Item.responseText;
                    if (My_Object_Verify_Item.readyState == 4) {
                        var feedback = dataVerify;
                        if (feedback == 'yes') {
                            Get_Details(Item_Name, Item_ID);
                        } else {
                            document.getElementById("Price").value = 0;
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Item_Name").value = '';
                            $("#Non_Supported_Item").dialog("open");
                        }
                    }
                }; //specify name of function that will handle server response........
                My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID, true);
                My_Object_Verify_Item.send();
            } else {
                Get_Details(Item_Name, Item_ID);
            }
        }
    </script>
    <script>
        function Get_Item_Price(Item_ID, Sponsor_ID) {
            var Billing_Type = document.getElementById("Billing_Type").value;
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
            myObject.onreadystatechange = function () {
                dataPrice = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Price').value = dataPrice;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Patient_Billing_Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
            myObject.send();
        }
    </script>

    <script>
        function Check_Dosage_Time() {
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Item_ID = document.getElementById("Item_ID").value;
            $.ajax({
                type: "GET",
                url: "Check_Dosage_Time.php",
                data: {Item_ID: Item_ID, Registration_ID: Registration_ID},
                success: function (result) {
                    if (result === "endelea") {
                        check_for_duplicate();
                    } else {
                        if (confirm(result)) {
                            check_for_duplicate();
                        }
                    }
                }});
        }

        function activateItem(Payment_Item_Cache_List_ID,Payment_Cache_ID){

            var Payment_Item_Cache_List_ID=Payment_Item_Cache_List_ID;
            var Payment_Cache_ID= Payment_Cache_ID;

            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Item_ID = document.getElementById("Item_ID").value;
            $.ajax({
                type: "GET",
                url: "AddPharmacyItem.php",
                data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                Item_ID: Item_ID, Registration_ID: Registration_ID,Payment_Cache_ID:Payment_Cache_ID},
                success: function (result) {
                    if (result === "endelea") {
                        check_for_duplicate();
                    } else {
                        if (confirm(result)) {
                            check_for_duplicate();
                        }
                    }
                }});
        }

        function check_for_duplicate() {
            var Item_ID = document.getElementById("Item_ID").value;
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';

            $.ajax({
                type: 'GET',
                url: 'checkforduplicatepharmacy.php',
                data: 'Item_ID=' + Item_ID + '&Payment_Cache_ID=' + Payment_Cache_ID,
                cache: false,
                success: function (html) {
                    if (html == '1') {
                        alertMsgSimple("Process the current item or remove and re-add it again.", "Duplicate Items", "error", 400, false, 'Ok');
                        exit;
                    } else {
                        Get_Selected_Item();
                    }
                }
            });
        }
    </script>
    <script>
        function Get_Selected_Item() {
            var dosade_duration = document.getElementById("dosade_duration").value;
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Item_ID = document.getElementById("Item_ID").value;
            var Quantity = document.getElementById("Quantity").value;
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Comment = document.getElementById("Comment").value;
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Price = document.getElementById("Price").value;
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
            var Check_In_Type = '<?php echo $Check_In_Type; ?>';

            if (Registration_ID != '' && Registration_ID != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null) {
                if (parseInt(Price) == 0) {
                    alert('Process fail!. Item missing price.');
                    return false;
                }

                if (window.XMLHttpRequest) {
                    myObject2 = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject2.overrideMimeType('text/xml');
                }
                myObject2.onreadystatechange = function () {
                    dataadd = myObject2.responseText;

                    if (myObject2.readyState == 4) {
                        document.getElementById('patientItemsList').innerHTML = dataadd;
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Price").value = '';
                        document.getElementById("Comment").value = '';
                        document.getElementById("Search_Value").focus();
                            //alert(Guarantor_Name)
                        alert("Item Added Successfully");

                        Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
                        $("#Add_New_Item").dialog("close");
                    }
                }; //specify name of function that will handle server response........

                myObject2.open('GET', 'Patient_Billing_Pharmacy_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Claim_Form_Number=' + Claim_Form_Number + '&Comment=' + Comment + '&Sub_Department_ID=' + Sub_Department_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Transaction_Type=' + Transaction_Type+'&dosade_duration='+dosade_duration+'&Sponsor_ID='+Sponsor_ID+"&Check_In_Type="+Check_In_Type, true);
                myObject2.send();

            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
                alertMessage();
            } else {
                if (Quantity == '' || Quantity == null) {
                    document.getElementById("Quantity").focus();
                    document.getElementById("Quantity").style = 'border: 3px solid red';
                }
            }
        }
    </script>

    <script type="text/javascript">
        function Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type) {
            if (window.XMLHttpRequest) {
                myObjectRefresh = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRefresh.overrideMimeType('text/xml');
            }

            myObjectRefresh.onreadystatechange = function () {
                dataRefresh = myObjectRefresh.responseText;
                if (myObjectRefresh.readyState == 4) {
                    document.getElementById("totalAmounts").innerHTML = dataRefresh;
                    //Refresh_Removed_Button(Payment_Cache_ID,Sub_Department_ID,Transaction_Type);
                }
            }; //specify name of function that will handle server response........

            myObjectRefresh.open('GET', 'Patient_Billing_Pharmacy_Refresh_Total.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
            myObjectRefresh.send();
        }
    </script>

    <script type="text/javascript">
        function removeitem(item) {
            // alert(item);
            var check = confirm("Are you sure you want to remove selected item");
            if (check) {
                $.ajax({
                    type: 'POST',
                    url: "change_items_info_pharmacy.php",
                    data: "Payment_Item_Cache_List_ID=" + item,
                    dataType: "json",
                    success: function (data) {
                        // alert(data['data']);
                        $('#patientItemsList').html(data['data']);
                        $('#totalAmount').html(data['total_amount']);
                        //alert(data['data']);

                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
        }

        function vieweRemovedItem() {
            // alert(item);

            $.ajax({
                type: 'POST',
                url: "change_items_info_pharmacy.php",
                data: "viewRemovedItem=true",
                dataType: "json",
                success: function (data) {
                    $('#patientItemsList').html(data['data']);
                    $('#totalAmount').html(data['total_amount']);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        function addItem(item) {
            $.ajax({
                type: 'POST',
                url: "change_items_info_pharmacy.php",
                data: "readdItem=" + item,
                dataType: "json",
                success: function (data) {
                    $('#patientItemsList').html(data['data']);
                    $('#totalAmount').html(data['total_amount']);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        function showItem() {
            $.ajax({
                type: 'POST',
                url: "change_items_info_pharmacy.php",
                data: "show_all_items=true",
                dataType: "json",
                success: function (data) {
                    $('#patientItemsList').html(data['data']);
                    $('#totalAmount').html(data['total_amount']);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        function submitAddedItems() {

            var datastring = $("form#addedItemForm").serialize();

            if (datastring !== '') {
                $.ajax({
                    type: 'POST',
                    url: "search_item_for_test.php",
                    data: "addMoreItems=true&" + datastring + '&section=<?php echo $_GET['section'] ?>',
                    success: function (data) {
                        // alert(data);
                        if (data == 'saved') {
                            window.location = window.location.href;
                            $("#addTests").dialog("close");
                        } else {
                            alert(data);
                        }
                        //              $('#patientItemsList').html(data);
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });


            } else {
                alert("No data set");
            }
            $("#loader").hide();
        }

        function removeitemphar(item) {
            //  alert(item);
            var check = confirm("Are you sure you want to remove selected item?");
            if (check) {
                $.ajax({
                    type: 'POST',
                    url: "change_items_pharmacy_list.php",
                    data: "Payment_Item_Cache_List_ID=" + item,
                    success: function (data) {
                        console.log(data);
                        window.location = window.location.href;

                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
        }

        function vieweRemovedItem() {
            //alert("item");

            $.ajax({
                type: 'POST',
                url: "change_items_pharmacy_list.php",
                data: "viewRemovedItem=true",
                dataType: "json",
                success: function (data) {
                    //alert(data);
                    $('#patientItemsList').html(data['data']);
                    $('#totalAmounts').html(data['total_amount']);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        function addItemPhar(item) {
            $.ajax({
                type: 'POST',
                url: "change_items_pharmacy_list.php",
                data: "readdItem=" + item,
                success: function (data) {
                    console.log("Test if page is refreshing")
                    if (parseInt(data) == 1) {
                        location.reload();
                    } else {
                        alert("An error ha occured! Please try again later.");
                    }

                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        function reloadPage() {
            window.location = window.location.href;
        }

        function searchMedicene(search) {
            if (search !== '') {
                $.ajax({
                    type: 'GET',
                    url: "search_item_for_test.php",
                    data: "section=<?php echo $_GET['section'] ?>&search_word=" + search + '&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>',
                    success: function (data) {
                        if (data !== '') {
                            $('#items_to_choose').html(data);
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
        }

    </script>

    <script type="text/javascript">
        function Preview_Medication_History(Payment_Cache_ID) {
            if (window.XMLHttpRequest) {
                myObjectPreview = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPreview.overrideMimeType('text/xml');
            }
            myObjectPreview.onreadystatechange = function () {
                data = myObjectPreview.responseText;
                if (myObjectPreview.readyState == 4) {
                    document.getElementById('Patient_Details_Area').innerHTML = data;
                    $("#Previous_History").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectPreview.open('GET', 'Preview_Medication_History.php?Payment_Cache_ID=' + Payment_Cache_ID, true);
            myObjectPreview.send();
        }
    </script>

    <script>
        function Print_Receipt_Payment() {
            // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
            var data = "<?php echo $Patient_Payment_ID; ?>"
    if(checkForMaximmumReceiptrinting(data) === 'true'){

            var winClose = popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);
            //winClose.close();
            //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');



             $.ajax({
                    type:"POST",
                    url:"update_receipt_count.php",
                    async:false,
                    data:{payment_id:data},
                    success:function(result){
                        console.log(result)
                    }
                })

}else{
        alert("You have exeded maximum print count")
        return false;
    }
 }


 function checkForMaximmumReceiptrinting(theId){

    var theCount = '';
    $.ajax({
                    type:"POST",
                    url:"compare_receipt_count.php",
                    async:false,
                    data:{payment_id:theId},
                    success:function(result){
                        // alert(result)
                        theCount = result;
                        console.log(theCount)

                    }
                })

return theCount;
}



        function popupwindow(url, title, w, h) {
            var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
            var wTop = window.screenTop ? window.screenTop : window.screenY;

            var left = wLeft + (window.innerWidth / 2) - (w / 2);
            var top = wTop + (window.innerHeight / 2) - (h / 2);
            var mypopupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            return mypopupWindow;
        }


    </script>
    <script>
        function Show_Patient_File() {
            // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
            var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $_GET['Registration_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
            //winClose.close();
            //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

        }

        // function popupwindow(url, title, w, h) {
        //     var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        //     var wTop = window.screenTop ? window.screenTop : window.screenY;

        //     var left = wLeft + (window.innerWidth / 2) - (w / 2);
        //     var top = wTop + (window.innerHeight / 2) - (h / 2);
        //     var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        //    return mypopupWindow;
        // }

    </script>
    <link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
    <script src="js/zebra_dialog.js"></script>
    <script src="js/ehms_zebra_dialog.js"></script>

    <script>
        $(document).ready(function () {
            $("#Previous_History").dialog({autoOpen: false, width: '80%', height: 550, title: 'PATIENT MEDICATION HISTORY', modal: true});
            $("#addTests").dialog({autoOpen: false, width: 900, height: 560, title: 'Choose an Item', modal: true});
//       $(".ui-widget-header").css("background-color","blue");

            $(".chosenTests").live("click", function () {
                var id = $(this).attr("id");
                if ($(this).is(':checked')) {


                    $.ajax({
                        type: 'GET',
                        url: "search_item_for_test.php",
                        data: "section=<?php echo $_GET['section'] ?>&adthisItem=" + id,
                        success: function (data) {
                            if (data !== '') {
                                $('#getSelectedTests').append(data);
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });

                } else {
                    $("#itm_id_" + id).remove();
                }
            });

            $(".ui-icon-closethick").click(function () {
//         $(this).hide();
                $("#loader").hide();
            });
        });
    </script>
    <script>
        function getItemsListFiltered(Item_Name) {
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            document.getElementById("Quantity").value = '';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

            var Item_Category_ID = document.getElementById("Item_Category_ID").value;
            if (Item_Category_ID == '' || Item_Category_ID == null) {
                Item_Category_ID = 'All';
            }

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data135 = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('New_Items_Fieldset').innerHTML = data135;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject.send();
        }
    </script>



    <script>
        function getItemsList(Item_Category_ID) {
            document.getElementById("Search_Value").value = '';
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject.onreadystatechange = function () {
                data265 = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('New_Items_Fieldset').innerHTML = data265;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID, true);
            myObject.send();
        }
    </script>
    <script type="text/javascript">
        function Change_Pharmacy() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            if (window.XMLHttpRequest) {
                myObjectChange = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectChange = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectChange.overrideMimeType('text/xml');
            }

            myObjectChange.onreadystatechange = function () {
                dataChange = myObjectChange.responseText;
                if (myObjectChange.readyState == 4) {
                    document.getElementById('Change_Pharmacy_Area').innerHTML = dataChange;
                    $("#Change_Medication_Location").dialog("open");
                }
            }; //specify name of function that will handle server response........
            myObjectChange.open('GET', 'Change_Medication_Location.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Transaction_Type=' + Transaction_Type, true);
            myObjectChange.send();
        }
    </script>

    <script type="text/javascript">
        function Change_Medication_Get_Values(Payment_Item_Cache_List_ID, temp) {
            var Sub_Dep_ID = document.getElementById("Dep_" + temp).value;
            if (window.XMLHttpRequest) {
                myObjectChangeMed = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectChangeMed = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectChangeMed.overrideMimeType('text/xml');
            }

            myObjectChangeMed.onreadystatechange = function () {
                dataChangeMed = myObjectChangeMed.responseText;
                if (myObjectChangeMed.readyState == 4) {

                }
            }; //specify name of function that will handle server response........
            myObjectChangeMed.open('GET', 'Change_Medication_Get_Values.php?Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID + '&Sub_Dep_ID=' + Sub_Dep_ID, true);
            myObjectChangeMed.send();
        }
    </script>

    <script type="text/javascript">
        function Change_Pharmaceutical_Location() {
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            if (window.XMLHttpRequest) {
                myObjectChangePharmaceut = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectChangePharmaceut = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectChangePharmaceut.overrideMimeType('text/xml');
            }

            myObjectChangePharmaceut.onreadystatechange = function () {
                dataChangePharm = myObjectChangePharmaceut.responseText;
                if (myObjectChangePharmaceut.readyState == 4) {
                    document.location = "pharmacyworkspage.php?section=Pharmacy&Registration_ID=" + Registration_ID + "&Transaction_Type=" + Transaction_Type + "&Payment_Cache_ID=" + Payment_Cache_ID + "&NR=True&PharmacyWorks=PharmacyWorksThisPage";
                }
            }; //specify name of function that will handle server response........
            myObjectChangePharmaceut.open('GET', 'Change_Pharmaceutical_Location.php', true);
            myObjectChangePharmaceut.send();
        }
    </script>

    <script type="text/javascript">
        function Cancel_Change_Medication() {
            $("#Change_Medication_Location").dialog("close");
        }
    </script>

    <script type="text/javascript">
        function Change_Medication_Location_Confirm() {
            $("#Change_Medication_Location_Confirm").dialog("open");
        }
    </script>

    <script type="text/javascript">
        function Change_Medication_Location_Confirm_Close() {
            $("#Change_Medication_Location_Confirm").dialog("close");
        }
    </script>
    <script>
        function number_format(number, decimals, dec_point, thousands_sep) {
            //  discuss at: http://phpjs.org/functions/number_format/
            // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
            // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // improved by: davook
            // improved by: Brett Zamir (http://brett-zamir.me)
            // improved by: Brett Zamir (http://brett-zamir.me)
            // improved by: Theriault
            // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // bugfixed by: Michael White (http://getsprink.com)
            // bugfixed by: Benjamin Lupton
            // bugfixed by: Allan Jensen (http://www.winternet.no)
            // bugfixed by: Howard Yeend
            // bugfixed by: Diogo Resende
            // bugfixed by: Rival
            // bugfixed by: Brett Zamir (http://brett-zamir.me)
            //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
            //  revised by: Luke Smith (http://lucassmith.name)
            //    input by: Kheang Hok Chin (http://www.distantia.ca/)
            //    input by: Jay Klehr
            //    input by: Amir Habibi (http://www.residence-mixte.com/)
            //    input by: Amirouche
            //   example 1: number_format(1234.56);
            //   returns 1: '1,235'
            //   example 2: number_format(1234.56, 2, ',', ' ');
            //   returns 2: '1 234,56'
            //   example 3: number_format(1234.5678, 2, '.', '');
            //   returns 3: '1234.57'
            //   example 4: number_format(67, 2, ',', '.');
            //   returns 4: '67,00'
            //   example 5: number_format(1000);
            //   returns 5: '1,000'
            //   example 6: number_format(67.311, 2);
            //   returns 6: '67.31'
            //   example 7: number_format(1000.55, 1);
            //   returns 7: '1,000.6'
            //   example 8: number_format(67000, 5, ',', '.');
            //   returns 8: '67.000,00000'
            //   example 9: number_format(0.9, 0);
            //   returns 9: '1'
            //  example 10: number_format('1.20', 2);
            //  returns 10: '1.20'
            //  example 11: number_format('1.20', 4);
            //  returns 11: '1.2000'
            //  example 12: number_format('1.2000', 3);
            //  returns 12: '1.200'
            //  example 13: number_format('1 000,50', 2, '.', ' ');
            //  returns 13: '100 050.00'
            //  example 14: number_format(1e-8, 8, '.', '');
            //  returns 14: '0.00000001'

            number = (number + '')
                    .replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + (Math.round(n * k) / k)
                                .toFixed(prec);
                    };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                    .split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '')
                    .length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1)
                        .join('0');
            }
            return s.join(dec);
        }

    </script>

    <script>
        $(document).ready(function () {
            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
            $("#Add_New_Item").dialog({autoOpen: false, width: '80%', height: 450, title: 'ADD NEW ITEMS', modal: true});
            $("#Add_New_Item").dialog({autoOpen: false, width: '80%', height: 450, title: 'ADD NEW ITEMS', modal: true});
            $("#Non_Supported_Item").dialog({autoOpen: false, width: '40%', height: 150, title: 'NON SUPPORTED ITEM', modal: true});
            $("#Change_Medication_Location").dialog({autoOpen: false, width: '75%', height: 300, title: 'CHANGE MEDICATION LOCATION', modal: true});
            $("#Change_Medication_Location_Confirm").dialog({autoOpen: false, width: '40%', height: 150, title: 'CONFIRM', modal: true});
            $("#Approval_Required").dialog({autoOpen: false, width: '50%', height: 150, title: 'eHMS 2.0', modal: true});
            $("#Inpatient_Notification").dialog({autoOpen: false, width: '50%', height: 150, title: 'eHMS 2.0 NOTIFICATION', modal: true});
        });
    </script>
    <script>
        function validate_issues() {
            var qty_has_error = false;
            var baln_has_error = false;
            var dsg_has_error=false;
            var can_dispense = "<?= $_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'] ?>";
            var can_varidate_dosage_duration='<?= $allow_dispense_control ?>';
            $('.validatesubmit').each(function () {
                var trg = $(this).attr('trg');
                var qty = parseInt($('.gty' + trg).val());
                var balance = parseInt($('.baln' + trg).val());
                var dsgd=parseInt($('.dsgd' + trg).val());
                // alert(trg+" "+qty+" "+balance);exit;

                if (isNaN(qty) || qty < 1) {
                    $('.gty' + trg).css('border', '2px solid red');
                    qty_has_error = true;
                } else if (qty > balance) {
                    $('.baln' + trg).css('border', '2px solid red');
                    baln_has_error = true;
                } else if((isNaN(dsgd) || dsgd < 1)&&can_varidate_dosage_duration=='yes'){
                    $('.dsgd' + trg).css('border', '2px solid red');
                    dsg_has_error=true;
                }else{
                    $('.baln' + trg).css('border', '1px solid #ccc');
                    $('.gty' + trg).css('border', '1px solid #ccc');
                }

            });

            if (qty_has_error) {
                alertMsgSimple("You cannot dispense zero item. Please correct the reded field", "Need correction", "error", 400, false, 'Ok');
                exit;
            } else if (baln_has_error) {
                if (can_dispense == 'no') {
                    alertMsgSimple("It seems you don't have enough balance in your store. Please adjust your stock before continuing", "Need correction", "error", 400, false, 'Ok');
                    exit;
                }
            }else if(dsg_has_error&&can_varidate_dosage_duration=='yes'){

                alertMsgSimple("you have to specify dosage duration", "Need correction", "error", 400, false, 'Ok');
                    exit;
            }

        }
    </script>
    <script>
        function Pay_Via_Mobile_Function(Payment_Cache_ID) {
            $("#testwindow").dialog("open");
            validate_issues();
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Employee_ID = '<?php echo $_SESSION['userinfo']['Employee_ID']; ?>';
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            if (window.XMLHttpRequest) {
                myObjectGetDetails = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
            }

            myObjectGetDetails.onreadystatechange = function () {
                data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
                    document.getElementById('ePayment_Area').innerHTML = data29;
                    $("#ePayment_Window").dialog("open");

                }
            }; //specify name of function that will handle server response........

            myObjectGetDetails.open('GET', 'ePayment_Patient_Details_Departmental.php?Section=Pharmacy&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
            myObjectGetDetails.send();
        }
    </script>
    <script type="text/javascript">
        function Confirm_Create_ePayment_Bill() {

            Create_ePayment_Bill();

//            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
//            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
//
//            if (window.XMLHttpRequest) {
//                myObjectConfirm = new XMLHttpRequest();
//            } else if (window.ActiveXObject) {
//                myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
//                myObjectConfirm.overrideMimeType('text/xml');
//            }
//
//            myObjectConfirm.onreadystatechange = function () {
//                data2933 = myObjectConfirm.responseText;
//                if (myObjectConfirm.readyState == 4) {
//                    var feedback = data2933;
//                    if (feedback == 'yes') {
//                        Create_ePayment_Bill();
//                    } else if (feedback == 'not') {
//                        alert("No Item Found!");
//                    } else {
//                        alert("You are not allowed to create transaction whith zero price or zero quantity. Please remove those items to proceed");
//                    }
//                }
//            }; //specify name of function that will handle server response........
//            myObjectConfirm.open('GET', 'Confirm_ePayment_Patient_Details_Departmental.php?Section=MainPharmacy&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
//            myObjectConfirm.send();
        }
    </script>


    <script type="text/javascript">
        function Create_ePayment_Bill() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Payment_Mode = document.getElementById("Payment_Mode").value;
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Amount = document.getElementById("Amount_Required").value;
            var Billing_Type = document.getElementById("Billing_Type").value;
            if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0') {
                alert("Process Fail! You can not prepare a bill with zero amount");
            } else {
                if (Payment_Mode != null && Payment_Mode != '') {
                    if (Payment_Mode == 'Bank_Payment') {
                        var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                        if (Confirm_Message == true) {
                            document.location = 'Departmental_Bank_Payment_Transaction.php?Section=MainPharmacy&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type+"&kutokaphamacy=yes";
                        }
                    } else if (Payment_Mode == 'Mobile_Payemnt') {
                        var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                        if (Confirm_Message == true) {
                            document.location = "#";
                        }
                    }
                } else {
                    document.getElementById("Payment_Mode").focus();
                    document.getElementById("Payment_Mode").style = 'border: 3px solid red';
                }
            }
        }
    </script>


    <script type="text/javascript">
        function Print_Payment_Code(Payment_Code) {

            var winClose = popupwindow('paymentcodepreview.php?Payment_Code=' + Payment_Code + '&PaymentCodePreview=PaymentCodePreviewThisPage', 'PAYMENT CODE', 530, 400);
        }

        // function popupwindow(url, title, w, h) {
        //     var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        //     var wTop = window.screenTop ? window.screenTop : window.screenY;

        //     var left = wLeft + (window.innerWidth / 2) - (w / 2);
        //     var top = wTop + (window.innerHeight / 2) - (h / 2);
        //     var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        //     return mypopupWindow;
        // }

        function Change_To_Brand(e,Payment_Item_Cache_List_ID){
          var Sub_Department_ID = '<?=$_SESSION['Pharmacy_ID'];?>';
          var Employee_ID = '<?=$_SESSION['userinfo']['Employee_ID'];?>';
          var brand_name = $(e).closest('tr').find('td:eq(2) option:selected').text();
          var Item_ID = e.value;
          var Sponsor_ID = "<?=$Sponsor_ID;?>";

          //alert("Item: "+Item_ID+" payment: "+Payment_Item_Cache_List_ID);
          $.ajax({
              type: "POST",
              url: "pharmacybrandUpdate.php",
              data:{Sponsor_ID:Sponsor_ID,Item_ID:Item_ID,Sub_Department_ID:Sub_Department_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Employee_ID:Employee_ID},
              'async':false,
              dataType:"json",
              success: function (result) {
                //alert(result.status);
                if (result.status == 'success') {
                  $(e).closest('tr').find('td:eq(1) input').val(brand_name);
                  $(e).closest('tr').find('td:eq(4) input').val(result.price);
                  $(e).closest('tr').find('td:eq(8) input').val(result.balance);
                }else if(result.status == 'no_price'){
                  alert('NO PRICE SET FOR THIS BRAND !!');
                }else if(result.status == 'fail'){
                  alert('PROCESS FAILS !!');
                }else if(result.status == 'invalid'){
                  alert('PROCESS FAILS, SOMETHING WRONG !!');
                }
              }});
        }
    </script>

    <?php
    include("./includes/footer.php");
    ?>
