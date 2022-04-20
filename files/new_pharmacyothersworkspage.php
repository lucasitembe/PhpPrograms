<script src='js/functions.js'></script>
<!--<script src="jquery.js"></script>-->
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

$configResult = mysqli_query($conn, "SELECT * FROM tbl_config") or die(mysqli_error($conn));

while ($data = mysqli_fetch_assoc($configResult)) {
    $configname = $data['configname'];
    $configvalue = $data['configvalue'];
    $_SESSION['configData'][$configname] = strtolower($configvalue);
}


#************************************************************************************#
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
if (
    isset($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) &&
    strtolower($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) == 'yes' &&
    $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != null &&
    $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != ''
) {
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

$select = mysqli_query($conn, "SELECT Sub_Department_Name,Sub_Department_ID from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
        $Sub_Department_id = $data['Sub_Department_ID'];
    }
} else {
    $Sub_Department_Name = '';
}
?>

<!-- link menu -->
<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'OUTPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'removed list') {
            document.location = "pharmacylistremoved.php?PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'OUTPATIENT CREDIT') {
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

<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: 1px solid #ccc !important;
    }
</style>

    <!-- commented -->

    <!-- <label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' class='btn-default'> -->
    <!-- <select id='patientlist' name='patientlist' onchange='gotolink()'>
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

    </select> -->
    <!-- <input type='button' value='VIEW' onclick='gotolink()'> -->

    <!-- commented -->

</label>
<?php if (getButtonStatus("inpatient_list") == "visible") { ?>
    <a href='pharmacyinpatientlist.php?PharmacyInpatientList=PharmacyInpatientListThisPage' style="font-family:arial;" class='art-button-green'>INPATIENTS LIST</a>
<?php } ?>
<?php if (getButtonStatus("medication_history") == "visible") { ?>
    <input type="button" value="MEDICATION HISTORY" style="font-family:arial;" class='art-button-green' onclick="Preview_Medication_History(<?php echo $Payment_Cache_ID; ?>)">
<?php } ?>
<?php if (getButtonStatus("pharmacy_works_pharmacy") == "visible") { ?>
    <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' style="font-family:arial;" class='art-button-green'>PHARMACY WORKS</a>
<?php }
if ($from_billing_work == "from_billing_work") { ?>
    <a href='previewpatientbilldetails.php?Check_In_ID=<?= $Check_In_ID ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage' class='art-button-green'>BACK</a>
<?php
} else { ?>
    <a href='pharmacyworkspage_new.php' style="font-family:arial;" class='art-button-green'>BACK</a>
    <!-- new date function (Contain years, Months and days)-->
<?php
}
$Today_Date = mysqli_query($conn, "select now() as today");
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
    function approved_Message() {}

    function approved_Message2() {
        alert('Payment Information is already sent to CASHIER!');
        return false;
    }

    function Payment_approved_Message() {
        alert('Patient\'s medication is not yet paid. Please advice PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');
    }
</script>

<!-- end of approved message-->
<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: none !important;

    }

    tr:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }

    button {
        height: 27px !important;
        color: #FFFFFF !important;
    }

    label {
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
function dateDiff($d1, $d2)
{
    // Return the number of days between the two dates:
    return round(abs(strtotime($d1) - strtotime($d2)) / (60 * 60));
}

// end function dateDiff

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $select_Patient = mysqli_query($conn, "SELECT * from tbl_payment_cache pc,tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
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
        }

        $Today = $original_Date;

        if (isset($_SESSION['systeminfo']['Dispense_Credit_Patients_after_24_hrs']) && strtolower($_SESSION['systeminfo']['Dispense_Credit_Patients_after_24_hrs']) == 'yes') {
            $numberDays = dateDiff($Transaction_Date_And_Time, $Today);
        } else {

            $numberDays = 0;
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
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
if (isset($_GET['Check_In_Type'])) {
    $Check_In_Type = $_GET['Check_In_Type'];
} else {
    $Check_In_Type = "";
}
if (strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'inpatient cash') {
    //check if inpatient then make sure admitted
    $get_status = mysqli_query($conn, "select Discharge_Clearance_Status,Admission_Status,Clearance_Date_Time from
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
    $slcts = mysqli_query($conn, "select Payment_Date_And_Time from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
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
        $Get_Receipt = mysqli_query($conn, "
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
            $Get_Receipt = mysqli_query($conn, "
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
    $slct = mysqli_query($conn, "select Folio_Number from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
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
        .nshwbdr table {
            border-collapse: collapse !important;
            border: none !important;
        }

        .nshwbdr tr {
            border-collapse: collapse !important;
            border: none !important;
        }

        .nshwbdr td {
            border-collapse: collapse !important;
            border: none !important;
        }
    </style>

    <fieldset>
        <legend style='background-color:#006400;color:white;padding:8px;' align=right>
            <b>
                <?php
                if (isset($_SESSION['Pharmacy_ID'])) {
                    echo $Sub_Department_Name;
                }
                ?>
            </b>
        </legend>
        <center>
            <table width="100%">
                <tr>
                    <td width='10%' style='text-align: right;'>Customer Name</td>
                    <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td width='12%' style='text-align: right;'>Card Expiry Date</td>
                    <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                    <td width='11%' style='text-align: right;'>Gender</td>
                    <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                    <td style='text-align: right;'>Receipt Number</td>
                    <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Billing Type</td>
                    <td>
                        <select name='Billing_Type' id='Billing_Type' style='width:100%'>
                            <option selected='selected'><?php echo $Billing_Type; ?></option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Claim Form Number</td>

                    <!-- Select the last claim form number-->
                    <?php
                    //select the last claim form number
                    $select_claim_form = mysqli_query($conn, "SELECT Claim_Form_Number from tbl_patient_payments where
                                                            Folio_number = '$Folio_Number' AND Registration_ID = '$Registration_ID' and
                                                            Sponsor_ID = '$Sponsor_ID' ORDER BY patient_payment_id DESC limit 1");
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
                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()' style='width:100%'>
                            <option selected='selected'>Pharmacy</option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age' disabled='disabled' value='<?php echo $age; ?>'></td>
                    <td style='text-align: right;'>Registered Date</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                    <td style='text-align: right;'>Folio Number</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Customer Direction</td>
                    <td>
                        <select id='direction' name='direction' required='required' style='width:100%'>
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
                        <select name='Consultant' id='Consultant' style='width:100%'>
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
    <fieldset>
        <style>
            .blue{
                background-color:rgb(0, 0, 255);
                height: 8px;
                width: 5.7%;
            }
        </style>
        <?php
        $select_payment_methode = mysqli_query($conn, "SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        while ($row_methode = mysqli_fetch_assoc($select_payment_methode)) {
            $payment_type = $row_methode['payment_method'];
        }

        $select_status = mysqli_query($conn, "SELECT Status FROM tbl_item_list_cache  as ilc, tbl_payment_cache as ptc where ptc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ptc.Registration_ID = '$Registration_ID' AND ilc.Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
        while ($status_row = mysqli_fetch_assoc($select_status)) {
            $status_to_check = $status_row['Status'];
        }

        if ($payment_type == 'credit') {
            echo '
                <input onclick="make_bill_and_dispense()" class="art-button-green pull-right" style="font-family:arial;font-weight:bold" type="button" value="DISPENSE & BILL ITEMS">
                <input class="art-button-green pull-right" style="font-family:arial;" type="button" onclick="openItemDialog()" value="ADD MORE ITEM"> 
            ';
        } else if ($payment_type == "cash") {
            if($Billing_Type == "Inpatient Cash"){
                echo'
                    <input onclick="cash_inpatient_bill()" class="art-button-green pull-right" style="font-family:arial;" type="button" value="DISPENSE AND  BILL MEDICATION">
                    <input onclick="send_patient_to_cashier()" style="font-family:arial;" class="art-button-green pull-right" type="button" value="SEND TO CASHIER">
                    <input class="art-button-green pull-right" style="font-family:arial;" type="button" onclick="openItemDialog()" value="ADD MORE ITEM">
                ';
            }else{
                echo '
                    <!--<input class="art-button-green pull-right" type="button" value="Make Payment">-->
                    <input onclick="send_patient_to_cashier()" style="font-family:arial;" class="art-button-green pull-right" type="button" value="SEND TO CASHIER">
                    <input onclick="dispense_item()" style="font-family:arial;" class="art-button-green pull-right" type="button" value="DISPENSE ITEMS">
                    <input class="art-button-green pull-right" style="font-family:arial;" type="button" onclick="openItemDialog()" value="ADD MORE ITEM">
                ';
            }
        }
        ?>


    </fieldset>

    <fieldset>
        <table class="table table-bordered" style="background-color: white;">
            <thead style="background-color: #ddd;font-size:13px">
                <tr>
                    <th width='2%'>SN</th>
                    <th width='14%'>Item name</th>
                    <th width='14%'>Dose</th>
                    <th width='6%'>Diseases Code</th>
                    <th width='6%'>Doctor Quantity</th>
                    <th width='6%'>Dose Quantity</th>
                    <th width='6%'>Dispensed Quantity</th>
                    <th width='6%'>Duration</th>
                    <th width='6%'>Previous Dispensed</th>
                    <th width='6%'>Remaining Quantity</th>
                    <th width='10%'>Balance</th>
                    <th width='5%'>Price</th>
                    <th width='6%'>Discount</th>
                    <th width='6%'>Subtotal</th>
                    <th width='6%'>Select</th>
                    <th width='2%'>Action</th>
                </tr>
            </thead>
            <tbody id="partial_dispensed" style="height: 50px;">
                <?php
                    $start_date = $_GET['Start_Date'];
                    $drquantity = 0;


                    $select_item = mysqli_query($conn, "SELECT ilc.Status,dispensed_quantity,dose,Payment_Item_Cache_List_ID,
                                                    Edited_Quantity,Quantity,Product_Name,Price,Discount,Doctor_Comment,
                                                    tim.Item_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache AS ilc,
                                                    tbl_payment_cache AS tpc, tbl_items AS tim WHERE ilc.Item_ID = tim.Item_ID
                                                    AND ilc.Payment_Cache_ID = tpc.Payment_Cache_ID AND tpc.Registration_ID = '$Registration_ID' 
                                                    AND ilc.Check_In_Type = 'Pharmacy' AND (ilc.Status = 'active' OR ilc.Status = 'paid' 
                                                    OR ilc.Status = 'partial dispensed') AND Transaction_Date_And_Time BETWEEN '$start_date' AND NOW()")
                                                    or die(mysqli_error($conn));
                
                
                if(mysqli_num_rows($select_item) > 0){

                $count = 1;
                while ($item_row = mysqli_fetch_assoc($select_item)) {
                    $Item_ID = $item_row['Item_ID'];
                    $item_name = $item_row['Product_Name'];
                    $Price = $item_row['Price'];
                    $Discount = $item_row['Discount'];
                    $Doctor_Comment = $item_row['Doctor_Comment'];
                    $Quantity = $item_row['Quantity'];
                    $Sub_Department_ID = $item_row['Sub_Department_ID'];
                    $Edited_Quantity = $item_row['Edited_Quantity'];
                    $Payment_Item_Cache_List_ID = $item_row['Payment_Item_Cache_List_ID'];

                    $dispensed_quantity = $item_row['dispensed_quantity'];
                    $dose = $item_row['dose'];
                    $Status = $item_row['Status'];
                    $remain = $dose - $dispensed_quantity;

                    if ($Quantity >= 1) {
                        $drquantity = $Quantity;
                    }
                    if ($Edited_Quantity != 0) {
                        $Quantity =  $Edited_Quantity;
                    }
                    $sub_total = $Quantity * $Price;

                    $balance_new = $_SESSION['Pharmacy_ID'];

                    $get_balance_now = '';
                    $get_balance = mysqli_query($conn, "SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = $balance_new");
                    while ($balance_row = mysqli_fetch_assoc($get_balance)) {
                        $get_balance_now = $balance_row['Item_Balance'];
                    }


                    # get diseases code
                    if($query = mysqli_query($conn,"SELECT consultation_id FROM tbl_payment_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID'")):
                        while($get_consultation = mysqli_fetch_assoc($query)):
                            $consultation_id = $get_consultation['consultation_id'];
                            $get_disease_id = mysqli_query($conn,"SELECT d.disease_code FROM tbl_disease_consultation dc, tbl_disease d  WHERE dc.consultation_ID = '$consultation_id' AND  d.Disease_ID = dc.Disease_ID
                            AND dc.diagnosis_type = 'diagnosis'")or die(mysqli_error($conn));
                                while($get_diseases = mysqli_fetch_assoc($get_disease_id)):                                   
                                    $code .= $get_diseases['disease_code'].', ';                                        
                                endwhile;
                        endwhile;
                    endif;
                    # get diseases code

                    echo '
                       <input id="check_status' . $Payment_Item_Cache_List_ID . '" type="text" value="' . $Status . '" style="display:none">
                        <tr>
                            <th>' . $count . '</th>
                            <th id="name' . $Payment_Item_Cache_List_ID . '" onhover="check_payment()">' . $item_name . '</th>
                            <th>' . $Doctor_Comment . '</th>
                            <th>'.$code.'</th>
                            <th><input type="text" readonly="readonly" value="' . $drquantity . '" style="text-align:center"></th>
                            <th><input type="text" class="dose" id="dosenqtyid' . $Payment_Item_Cache_List_ID . '" value="' . $dose . '" style="text-align:center"></th>
                            <th><input type="text" class="dispensed ' . $Payment_Item_Cache_List_ID . '" id="dispenseqtyid' . $Payment_Item_Cache_List_ID . '" onkeyup="dispensed_qty_function(' . $Payment_Item_Cache_List_ID . ')"  value="' . $Edited_Quantity . '" style="text-align:center"></th>
                            <th><input type="text" value="0" id="dose_duration'.$Payment_Item_Cache_List_ID.'" style="text-align:center"></th>
                            <th><input type="text" id="previuse' . $Payment_Item_Cache_List_ID . '" readonly="readonly" value="' . $dispensed_quantity . '" style="text-align:center"></th>
                            <th><input type="text" id="remainqtyid' . $Payment_Item_Cache_List_ID . '" readonly="readonly" value="' . $remain . '" style="text-align:center"></th>
                            <th><input type="text" id="balanceqtyid' . $Payment_Item_Cache_List_ID . '" readonly="readonly" value="' . $get_balance_now . '" style="text-align:center"></th>
                            <th><input type="text" id="item_price' . $Payment_Item_Cache_List_ID . '" readonly="readonly" value="' . $Price . '" style="text-align:center"></th>
                            <th><input type="text" value="' . $Discount . '" style="text-align:center"></th>
                            <th><input type="text" class="total" id="subtotal' . $Payment_Item_Cache_List_ID . '" readonly="readonly" value="0" style="text-align:center"></th>
                            <th><input onchange="dispensed_qty_function(' . $Payment_Item_Cache_List_ID . ')" onclick="checkDose('.$Payment_Item_Cache_List_ID.','.$Item_ID.','.$Registration_ID.','.$Payment_Cache_ID.')" type="checkbox" id="' . $Payment_Item_Cache_List_ID . '" class="check_item" value="' . $Payment_Item_Cache_List_ID . '"></th>
                            <th>
                                <span style="font-size:18px;color:red;padding:20px" onclick="remove_item(' . $Payment_Cache_ID . ','.$Item_ID.')">&#128465;</span>
                            </th>
                        </tr>
                    ';
                    $code = "";
                    $count++;


                ?>
                    <script>
                        $(document).ready(function() {
                            var item_id = '<?php echo $Payment_Item_Cache_List_ID; ?>';

                            var name_id = "name" + item_id;
                            var id_for_dispense = "dispenseqtyid" + item_id;
                            var id_for_dose_qty = "dosenqtyid" + item_id;
                            var check_status = "check_status" + item_id;
                            var doseqt = "dosenqtyid" + item_id;

                            var status = $('#' + check_status).val();
                            if (status == "active") {
                                $('#' + name_id).css({
                                    "color": "red",
                                    "font-weight": "bolder"
                                });
                            } else if (status == "partial dispensed") {
                                $('#' + name_id).css({
                                    "color": "blue",
                                    "font-weight": "bolder"
                                });
                                $('#' + doseqt).attr("disabled", "disabled");
                            } else {
                                $('#' + name_id).css({
                                    "color": "green",
                                    "font-weight": "bolder"
                                });
                                $('#' + id_for_dispense).attr("disabled", "disabled");
                                $('#' + id_for_dose_qty).attr("disabled", "disabled");
                                $('#' + doseqt).attr("disabled", "disabled");
                            }
                        });
                    </script>
                <?php
                }}else{
                    echo '
                        <tr>
                            <td colspan="16" style="color:red;padding:2em;font-size:16px;font-weight:bold;text-align:center">No Data Found</td>
                        </tr>
                    ';
                }
                echo "
                        <tr style='background-color: #ddd;'>
                            <td colspan='12' style='text-align:end;font-wight:bolder;font-size:16px;font-wight:bolder;font-family:arial;padding-top:15px'>Total : </td>
                            <td ><input style='text-align:center;font-wight:bolder;font-family:arial;font-size:16px;border:none;background-color:inherit;padding-top:8px' disabled='disabled' id='total_amount' value='Tshs : 0 /='/></td>
                            <td colspan='4'>
                                <input class='art-button-green' onclick='view_removed_items(".$_GET['Payment_Cache_ID'].",".$_GET['Registration_ID'].")' type='button' style='float:right;font-family:arial;' value='REMOVED ITEMS'>
                            </td>
                        </tr>
                    ";
                ?>
            </tbody>
        </table>
    </fieldset>

    <div id="Add_New_Item" style='background-color:#eee'>
        <span id='Add_New_Items_Area'></span>
    </div>

    <!-- section space -->
    <div id="Previous_History"></div>
    <div id="removed_item_section"></div>
    <!-- section space -->

    <script>
        $(document).ready(function() {
            console.clear();
            $("#ePayment_Window").dialog({
                autoOpen: false,
                width: '55%',
                height: 250,
                title: 'Create ePayment Bill',
                modal: true
            });

            $("#Add_New_Item").dialog({
                autoOpen: false,
                width: '80%',
                height: 450,
                title: 'ADD NEW ITEMS',
                modal: true
            });

            $("#Add_New_Item").dialog({
                autoOpen: false,
                width: '80%',
                height: 450,
                title: 'ADD NEW ITEMS',
                modal: true
            });

            $("#Non_Supported_Item").dialog({
                autoOpen: false,
                width: '40%',
                height: 150,
                title: 'NON SUPPORTED ITEM',
                modal: true
            });

            $("#Change_Medication_Location").dialog({
                autoOpen: false,
                width: '75%',
                height: 300,
                title: 'CHANGE MEDICATION LOCATION',
                modal: true
            });

            $("#Change_Medication_Location_Confirm").dialog({
                autoOpen: false,
                width: '40%',
                height: 150,
                title: 'CONFIRM',
                modal: true
            });

            $("#Approval_Required").dialog({
                autoOpen: false,
                width: '50%',
                height: 150,
                title: 'eHMS 2.0',
                modal: true
            });

            $("#Inpatient_Notification").dialog({
                autoOpen: false,
                width: '50%',
                height: 150,
                title: 'eHMS 2.0 NOTIFICATION',
                modal: true
            });
        });
    </script>

    <script type="text/javascript">
        function Preview_Medication_History(cache_id) {
            var Payment_Cache_ID = cache_id;
            $.get(
                'Preview_Medication_History.php', {
                    Payment_Cache_ID: Payment_Cache_ID
                }, (data) => {
                    $("#Previous_History").dialog({
                        autoOpen: false,
                        width: '80%',
                        title: 'eHMS 2.0 NOTIFICATION',
                        modal: true
                    }); 
                    $("#Previous_History").html(data);
                    $("#Previous_History").dialog("open");
                }
            );
        }

        function checkDose(Payment_Item_Cache_List_ID,Item_Id,Registration_Id,Payment_Cache_Id){
            var get_checkbox_id = Payment_Item_Cache_List_ID; 
            var response = '';
            $.post(
                'validate_dosage.php',{
                    Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
                    Item_Id: Item_Id,
                    Registration_Id: Registration_Id,
                    Payment_Cache_Id: Payment_Cache_Id
                },(response) =>{
                    var msg = response;
                    if(msg == 'Yes'){
                        console.log('Good to go');
                    }else{
                        if(confirm(response)){
                            console.log('Checkbox checked');
                            $('#'+get_checkbox_id).prop('checked',true);
                        }else{
                            console.log('checkbox unchecked');
                            $('#'+get_checkbox_id).prop('checked',false);
                        }
                    }
                }
            );
        }

        // view and returned removed items pharmacy
        function view_removed_items(Payment_Cache_ID,Reg_No){
            $.post(
                'view_removed_pharmacy_items.php',{
                    Payment_Cache_ID: Payment_Cache_ID,
                    Reg_No: Reg_No
                },(response) => {
                    $("#removed_item_section").dialog({
                        autoOpen: false,
                        width: '80%',
                        height: 800,
                        title: 'eHMS 4.0 : Items Removed',
                        modal: true
                    });
                    $("#removed_item_section").html(response);
                    $("#removed_item_section").dialog("open");
                }
            );
        }

    </script>

    <script>
        function get_partial_dispensed_items(Registration_ID) {
            $.ajax({
                url: 'get_partial_dispensed_items.php',
                type: 'GET',
                data: {
                    Registration_ID: Registration_ID,
                },
                success: function(data) {
                    $('#partial_dispensed').empty();
                    $('#partial_dispensed').html(data);
                }
            });
        }
    </script>

    <script>
        // remove item from active list to removed status
        function remove_item(id_param,Item_ID) {
            if (confirm('Are you want to remove the item ?')) {
                $.post(
                    'remove_item_pharmacry.php', {
                        id_param: id_param,
                        Item_ID: Item_ID
                    }, (response) => {
                        location.reload(true);
                    }
                );
            }
        }


        //funtion to bill and dispense for credit patients
        function make_bill_and_dispense() { 
            var checked_item = $(".check_item").is(':checked');

            if (checked_item) {
                var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
                var Transaction_Type = '<?php echo $Transaction_Type; ?>';
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>'
                var Check_In_Type = "Pharmacy";

                var selectedItem = [];

                $(".check_item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dosenqtyid" + Idd;
                    var id_for_dispense = "dispenseqtyid" + Idd;
                    var id_for_dose_duration = "dose_duration" + Idd;

                    var dose = $('#'+ id_for_dose_duration).val();

                    if(dose == 0){
                        $('#'+ id_for_dose_duration).css('background-color','red');
                    }else{
                        selectedItem.push({
                            id: Idd,
                            doseqty: $('#' + id_for_dose_qty).val(),
                            dispensedqty: $('#' + id_for_dispense).val(),
                            dose_duration: $('#'+ id_for_dose_duration).val()
                        });

                        $.ajax({
                            url: 'new_dispense_credit.php',
                            type: 'GET',
                            data: {
                                Payment_Cache_ID: Payment_Cache_ID,
                                Transaction_Type: Transaction_Type,
                                Registration_ID: Registration_ID,
                                Check_In_Type: Check_In_Type,
                                Sub_Department_ID: Sub_Department_ID,
                                selectedItem: selectedItem
                            },
                            success: function(data) {
                                alert(data);
                                location.reload(true);
                            }
                        });
                    }
                });

            } else {
                alert("Choose Item to Dispense");
            }
        }
    </script>

    </script>
    <script>
        function dispensed_qty_function(Id) {
            var id_for_dose_qty = "dosenqtyid" + Id;
            var id_for_dispense = "dispenseqtyid" + Id;
            var id_for_remained = "remainqtyid" + Id;
            var id_for_balance = "balanceqtyid" + Id;
            var id_for_previus = "previuse" + Id;
            var item_sub_total = "subtotal" + Id;
            var item_price = "item_price" + Id;

            var balance_qty = $('#' + id_for_balance).val();
            var dosen_qty = $('#' + id_for_dose_qty).val();
            var dispensed_qty = $('#' + id_for_dispense).val();
            var previus = $('#' + id_for_previus).val();
            var idforremained = $('#' + id_for_remained).val();
            var get_item_price = $('#' + item_price).val();

            if (parseInt(dispensed_qty) > parseInt(dosen_qty)) {
                alert("Dispensed quantity should not be greater than dose quantity");
                $('#' + id_for_dispense).css("background-color", 'purple');
                document.getElementById(Id).disabled = true;
                exit;
            }


            var remain_to_complete = dosen_qty - dispensed_qty - previus;

            $('#' + id_for_remained).val(remain_to_complete);
            var difference = balance_qty - dispensed_qty;

            if (parseInt(remain_to_complete) < 0) {
                alert('Remaining quantity is less than 0');
                $('#' + id_for_dispense).css("background-color", 'green');
                document.getElementById(Id).disabled = true;
                exit;
            }

            if (parseInt(balance_qty) <= 0) {
                $('#' + id_for_dispense).css("background-color", "red");
                alert("balance it is not enough");
            } else {
                $('#' + id_for_dispense).css("background-color", "white");
                $('#' + item_sub_total).val(get_item_price * dispensed_qty);

                var date_values = $("input[class='total']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var sum = 0;
                for (var el in date_values) {
                    if (date_values.hasOwnProperty(el)) {
                        sum += parseFloat(date_values[el]);
                    }
                }

                $('#total_amount').val('Tshs : ' + sum.toLocaleString('en') + ' /=');
                document.getElementById(Id).disabled = false;
            }
        }
    </script>
    <script>
        function send_patient_to_cashier() {
            var checked_item = $(".check_item").is(':checked');
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var Check_In_Type = "Pharmacy";

            console.log(Payment_Cache_ID + "=>" + Transaction_Type + "= >" + Sub_Department_Name);

            if (checked_item) {
                var selectedItem = [];
                $(".check_item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dosenqtyid" + Idd;
                    var id_for_dispense = "dispenseqtyid" + Idd;
                    selectedItem.push({
                        id: Idd,
                        doseqty: $('#' + id_for_dose_qty).val(),
                        dispensedqty: $('#' + id_for_dispense).val()
                    });
                });

                console.log(selectedItem);

                if (confirm("Send This patient to Cashier")) {
                    $.ajax({
                        url: 'Send_To_Cashier.php',
                        type: 'GET',
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            Transaction_Type: Transaction_Type,
                            Sub_Department_Name: Sub_Department_Name,
                            Registration_ID: Registration_ID,
                            Check_In_Type: Check_In_Type,
                            selectedItem: selectedItem
                        },
                        success: function(data) {
                            alert(data);
                            location.reload();
                        }
                    });
                }
            } else {
                alert("Choose Item to Send to Cashier");
            }
        }
    </script>

    <script>
        function dispense_item() {
            var checked_item = $(".check_item").is(':checked');
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var Check_In_Type = "Pharmacy";

            if (checked_item) {
                var selectedItem = [];
                var dose_durations = [];

                $(".check_item:checked").each(function() {
                    var Idd = $(this).val();
                    check_if_paid(Idd);
                    var id_for_dose_qty = "dosenqtyid" + Idd;
                    var id_for_dispense = "dispenseqtyid" + Idd;
                    var id_for_dose_duration = "dose_duration" + Idd;
                    selectedItem.push({
                        id: Idd,
                        doseqty: $('#' + id_for_dose_qty).val(),
                        dispensedqty: $('#' + id_for_dispense).val(),
                        dose_duration: $('#'+ id_for_dose_duration).val()
                    });
                });


                if (confirm("Dispense This items")) {
                    $.ajax({
                        url: 'new_medication_pharmacy.php',
                        type: 'GET',
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            Transaction_Type: Transaction_Type,
                            Sub_Department_Name: Sub_Department_Name,
                            Registration_ID: Registration_ID,
                            Check_In_Type: Check_In_Type,
                            selectedItem: selectedItem,
                        },
                        success: function(data) {
                            alert(data);
                            location.reload();
                        }
                    });
                }
            } else {
                alert("Choose Item to Dispense");
            }
        }
    </script>

    <script>
        function check_if_paid(idd) {
            var checked_item = $(".check_item").is(':checked');
            if (checked_item) {
                $(".check_item:checked").each(function() {
                    var check_status = "check_status" + idd;
                    var status = $('#' + check_status).val();
                    if (status == "active" || status == "partial dispensed") {
                        alert("you can't dispense  unpaid item");
                        exit();
                    }
                });
            }

        }

        function openItemDialog() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Sub_Department_ID = '<?php echo $_SESSION['Pharmacy_ID'] ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID = '<?= $Sponsor_ID ?>';
            if (window.XMLHttpRequest) {
                myObjectAddItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddItem.overrideMimeType('text/xml');
            }
            ///Check_In_Typ
            myObjectAddItem.onreadystatechange = function() {
                data_Add_New = myObjectAddItem.responseText;
                if (myObjectAddItem.readyState == 4) {
                    document.getElementById("Add_New_Items_Area").innerHTML = data_Add_New;
                    $("#Add_New_Item").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectAddItem.open('GET', 'Patient_Billing_Pharmacy_Add_New_Item.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObjectAddItem.send();
        }

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

                My_Object_Verify_Item.onreadystatechange = function() {
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

        function Get_Item_Price(Item_ID, Sponsor_ID) {
            var Billing_Type = document.getElementById("Billing_Type").value;
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
            myObject.onreadystatechange = function() {
                dataPrice = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Price').value = dataPrice;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Patient_Billing_Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
            myObject.send();
        }

        function Check_Dosage_Time() {
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Item_ID = document.getElementById("Item_ID").value;
            $.ajax({
                type: "GET",
                url: "Check_Dosage_Time.php",
                data: {
                    Item_ID: Item_ID,
                    Registration_ID: Registration_ID
                },
                success: function(result) {
                    if (result === "endelea") {
                        check_for_duplicate();
                    } else {
                        if (confirm(result)) {
                            check_for_duplicate();
                        }
                    }
                }
            });
        }

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

            myObject.onreadystatechange = function() {
                data135 = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('New_Items_Fieldset').innerHTML = data135;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject.send();
        }

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

            myObject.onreadystatechange = function() {
                data265 = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('New_Items_Fieldset').innerHTML = data265;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject.send();
        }

        function check_for_duplicate() {
            var Item_ID = $(".itemId").val();
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';

            $.ajax({
                type: 'GET',
                url: 'checkforduplicatepharmacy.php',
                data: 'Item_ID=' + Item_ID + '&Payment_Cache_ID=' + Payment_Cache_ID,
                cache: false,
                success: function(html) {
                    // if (html == '1') {
                    //     alertMsgSimple("Process the current item or remove and re-add it again.", "Duplicate Items", "error", 400, false, 'Ok');
                    //     exit;
                    // } else {
                    // Get_Selected_Item();
                    // }
                }
            });
        }

        function check_for_duplicate() {
            var Item_ID = document.getElementById("Item_ID").value;
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';

            $.ajax({
                type: 'GET',
                url: 'checkforduplicatepharmacy.php',
                data: 'Item_ID=' + Item_ID + '&Payment_Cache_ID=' + Payment_Cache_ID,
                cache: false,
                success: function(html) {
                    // alert('Item Added');
                    // location.reload();
                    if (html == '1') {
                        alertMsgSimple("Process the current item or remove and re-add it again.", "Duplicate Items", "error", 400, false, 'Ok');
                        exit;
                    } else {
                        Get_Selected_Item();
                    }
                }
            });
        }

        function Get_Selected_Item() {
            var dosade_duration = document.getElementById("dosade_duration").value;
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Item_ID = document.getElementById("Item_ID").value;
            var Quantity = document.getElementById("Quantity").value;
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Comment = document.getElementById("Comment").value;
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Price = document.getElementById("Price").value;
            var Sub_Department_ID = '<?php echo $_SESSION['Pharmacy_ID'] ?>';
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
                myObject2.onreadystatechange = function() {
                    dataadd = myObject2.responseText;
                    console.log(Sub_Department_ID);

                    if (myObject2.readyState == 4) {
                        // document.getElementById('patientItemsList').innerHTML = dataadd;
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Price").value = '';
                        document.getElementById("Comment").value = '';
                        // document.getElementById("Search_Value").focus();
                        // alert('Item Added');
                        // location.reload();
                        alert("Item Added Successfully");
                        location.reload();
                        Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
                    }
                }; //specify name of function that will handle server response........

                myObject2.open('GET', 'Patient_Billing_Pharmacy_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Claim_Form_Number=' + Claim_Form_Number + '&Comment=' + Comment + '&Sub_Department_ID=' + Sub_Department_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Transaction_Type=' + Transaction_Type + '&dosade_duration=' + dosade_duration + '&Sponsor_ID=' + Sponsor_ID + "&Check_In_Type=" + Check_In_Type, true);
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

        function varidate_dosage_duration(after_bill) {
            var uri = "varidate_dosage_duration.php";
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var check = "false";
            $.ajax({
                type: 'GET',
                url: uri,
                data: {
                    Payment_Cache_ID: Payment_Cache_ID,
                    Transaction_Type: Transaction_Type,
                    Registration_ID: Registration_ID,
                    after_bill: after_bill
                },
                success: function(data) {
                    //alert("moja")

                    if (data == 1) {
                        // alert(after_bill)
                        if (after_bill == "after_bill") {
                            // alert('after na bill')
                            console.log(after_bill);
                            Dispense_Medication()
                        } else if (after_bill == 'before_bill') {
                            console.log(after_bill)
                            Dispense_Credit_Medication()
                        } else {
                            // alert('credit')
                            //do nothing
                        }

                    } else {
                        $("#dossage_feedback_message").dialog({
                            autoOpen: false,
                            width: '75%',
                            height: 450,
                            title: 'PATIENT MEDICINE DOSSAGE DURATION ALERT',
                            modal: true
                        });
                        $("#dossage_feedback_message").dialog("open").html(data);

                    }
                },
                error: function() {

                }
            });
        }

        function validate_issues() {
            var qty_has_error = false;
            var baln_has_error = false;
            var dsg_has_error = false;
            var can_dispense = "<?= $_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'] ?>";
            var can_varidate_dosage_duration = '<?= $allow_dispense_control ?>';
            $('.validatesubmit').each(function() {
                var trg = $(this).attr('trg');
                var qty = parseInt($('.gty' + trg).val());
                var balance = parseInt($('.baln' + trg).val());
                var dsgd = parseInt($('.dsgd' + trg).val());
                // alert(trg+" "+qty+" "+balance);exit;

                if (isNaN(qty) || qty < 1) {
                    $('.gty' + trg).css('border', '2px solid red');
                    qty_has_error = true;
                } else if (qty > balance) {
                    $('.baln' + trg).css('border', '2px solid red');
                    baln_has_error = true;
                } else if ((isNaN(dsgd) || dsgd < 1) && can_varidate_dosage_duration == 'yes') {
                    $('.dsgd' + trg).css('border', '2px solid red');
                    dsg_has_error = true;
                } else {
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
            } else if (dsg_has_error && can_varidate_dosage_duration == 'yes') {

                alertMsgSimple("you have to specify dosage duration", "Need correction", "error", 400, false, 'Ok');
                exit;
            }

        }
    </script>

<script>
    // inpatient bill for cash 
    function cash_inpatient_bill(){
        var selected_item = []; 
        var Registration_ID = '<?php echo $_GET['Registration_ID'] ?>';
        var Sponsor_ID = '<?=$Sponsor_ID?>';
        var Guarantor_Name = '<?=$Guarantor_Name?>';
        var check_in_type = 'Pharmacy';
        var Transaction_Type = '<?=$Transaction_Type?>';
        var Payment_Cache_ID = '<?=$Payment_Cache_ID?>';
        var Payment_Item_Cache_List_ID = '<?=$Payment_Item_Cache_List_ID?>';
        var Employee_Name = '<?=$Employee_Name?>';

        $(".check_item:checked").each(function() {
            var Idd = $(this).val();
            var id_for_dose_qty = "dosenqtyid" + Idd;
            var id_for_dispense = "dispenseqtyid" + Idd;
            var id_for_dose_duration = "dose_duration" + Idd;
            var Payment_Item_Cache_List_ID = Idd;
            selected_item.push({
                id: Idd,
                doseqty: $('#' + id_for_dose_qty).val(),
                dispensedqty: $('#' + id_for_dispense).val(),
                dose_duration: $('#'+ id_for_dose_duration).val(),
                Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
            });
        });

        $.get(
            'test_credit_biling.php',{
                Registration_ID: Registration_ID,
                Sponsor_ID: Sponsor_ID,
                Guarantor_Name: Guarantor_Name,
                check_in_type: check_in_type,
                Transaction_Type: Transaction_Type,
                Payment_Cache_ID:Payment_Cache_ID,
                selected_item: selected_item,
                Employee_Name: Employee_Name
            },(response) => {
                alert(response);
                location.reload(true);
            }
        );
    }
</script>

<?php include("./includes/footer.php"); ?>