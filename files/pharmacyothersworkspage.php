<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
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

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
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


//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
?>
<style>
    button{
        color:#FFFFFF!important;
        height:27px!important;
    }
</style>
<!-- link menu -->
<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'OUTPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'OUTPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'COSTUMER LIST') {
            //document.location = "pharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
            document.location = "pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm";
        } else if (patientlist == 'DISPENSED LIST') {
            document.location = "dispensedlist.php?Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm";
        } else {
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
    <select id='patientlist' name='patientlist'> 
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
        <option>
            COSTUMER LIST
        </option>
   <?php } ?>   
    <?php if (getButtonStatus("dispensed_lists_op") == "visible") { ?>       
        <option>
            DISPENSED LIST
        </option>
    <?php } ?>       
    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label> 


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        ?>
        <!-- <a href='#' class='art-button-green'>
            VIEW - EDIT
        </a> -->
    <?php
    }
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        ?>
        <!-- <a href='#' class='art-button-green'>
            VIEW MY DATA
        </a> -->
    <?php
    }
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        ?>
        <a href='pharmacyworks.php?PharmacyWorkPage=PharmacyWorkPageThisPage' class='art-button-green'>
            BACK
        </a>
    <?php
    }
}
?>
<!-- old date function -->
<?php
/* $Today_Date = mysqli_query($conn,"select now() as today");
  while($row = mysqli_fetch_array($Today_Date)){
  $original_Date = $row['today'];
  $new_Date = date("Y-m-d", strtotime($original_Date));
  $Today = $new_Date;

  $age = $Today - $original_Date;
  } */
?>
<!-- end of old date function -->


<!-- new date function (Contain years, Months and days)--> 
<?php
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
        alert('    Successfully Approved! Please notify PATIENT to go to CASHIER for payment and then return to PHARMACY to pick up their medication   ');
    }

    function approved_Message2() {
        alert('    The Bill is already APPROVED! if not yet, please notify PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');
    }

    function Payment_approved_Message() {
        alert('    Patient\'s medication is not yet paid. Please advice PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');
    }


</script>
<!-- end of approved message-->




<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp where
				       sp.Sponsor_ID = pr.Sponsor_ID and
					  pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
            $auto_item_update_api = $row['auto_item_update_api'];
            $Payment_Method = $row['payment_method'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Require_Document_To_Sign_At_receiption = $row['Require_Document_To_Sign_At_receiption'];
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    } else {
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
        $Payment_Method = "";
        $Guarantor_Name = '';
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
        $Require_Document_To_Sign_At_receiption = '';
    }
} else {
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
    $Payment_Method = "";
    $Guarantor_Name = '';
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
    $Require_Document_To_Sign_At_receiption = '';
}
?>

<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<?php
if (strtolower($Payment_Method) != 'cash') {
    //get the last folio number if available
    $get_folio = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' and
                                    Receipt_Date = '$Today' and
                                    Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($get_folio);
    if ($numrow > 0) {
        while ($data = mysqli_fetch_array($get_folio)) {
            $Folio_Number = $data['Folio_Number'];
            $Claim_Form_Number = $data['Claim_Form_Number'];
        }
    } else {
//       include("./includes/Folio_Number_Generator_Emergency.php");
        $Folio_Number = "";
        $Claim_Form_Number = '';
    }
} else {
    $Folio_Number = '';
    $Claim_Form_Number = '';
}
?>


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>

<!--get sub department name-->
<?php
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}
?>


<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }

</style>
<br/>

<fieldset>  
    <legend style='background-color:#006400;color:white;padding:5px;' align=right><b><?php
            if (isset($_SESSION['Pharmacy_ID'])) {
                echo $Sub_Department_Name;
            }
            ?></b></legend>
    <center>
        <table width=100%> 
            <tr>
                <td width='10%' style='text-align: right;'>Costumer Name</td>
                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                <td style='text-align: right;' width="12%">Claim Form Number</td>
                <td width="15%">
                    <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' value="1<?php echo $Claim_Form_Number; ?>">
                </td>
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
            </tr> 
            <tr>
                <td style='text-align: right;'><b style="color:green"><span style="color:red">*</span>Billing Type</b></td> 
                <td>
                    <select name='Billing_Type' id='Billing_Type' style="width:100%" onchange="Sponsor_Warning()">
                        <?php
                        $Billing_Type_filter="";
                        $select_bill_type = mysqli_query($conn,
                                "select Billing_Type
								  from tbl_pharmacy_items_list_cache alc
								  where alc.Employee_ID = '$Employee_ID' and
								  Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

                        $no_of_items = mysqli_num_rows($select_bill_type);
                        if ($no_of_items > 0) {
                            while ($data = mysqli_fetch_array($select_bill_type)) {
                                $Billing_Type = $data['Billing_Type'];
                                $B_Type = $data['Billing_Type'];
                                $Billing_Type_filter=$B_Type;
                            }
                            echo "<option selected='selected'>" . $Billing_Type . "</option>";
                        } else {
                            $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                        if (strtolower($Payment_Method) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
                                echo "<option selected='selected'>Outpatient Cash</option>";
                                $B_Type = 'Outpatient Cash';
                                $Billing_Type_filter=$B_Type;
                            } else {
                                echo "<option selected='selected'>Outpatient Credit</option> 
						      <option>Outpatient Cash</option>";
                                $B_Type = 'Outpatient Credit';
                                $Billing_Type_filter=$B_Type;
                            }
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Costumer Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                <td style='text-align: right;'>Folio Number</td>
                <td>
                    <input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" autocomplete='off' placeholder='Folio Number' value="0<?php echo $Folio_Number; ?>">
                </td>

            </tr>
            <tr>
                <td style='text-align: right;'>Type Of Check In</td>
                <td>  
                    <select name='Type_Of_Check_In' style="width:100%"  id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
                        <option selected='selected'>Pharmacy</option> 
                    </select>
                </td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
            </tr>
            <tr> 
                <td style='text-align: right;'>Costumer Direction</td>
                <td>
                    <select id='direction' name='direction' required='required' style="width:100%"> 
                        <option selected='selected'>Others</option>
                    </select>
                </td>
                <td style='text-align: right;'>Registration Number</td>
                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                <td style='text-align: right;'>Phone Number</td>
                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Consultant</td>
                <td>
                    <select name='Consultant_ID' id='Consultant_ID'  style="width:100%">
                        <option selected='selected'></option>
                        <?php
                        $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                        $Select_Doctors = "select Employee_ID, Employee_Name from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'";
                        $result = mysqli_query($conn,$Select_Doctors);
                        $count_Consultant=1;
                        while ($row = mysqli_fetch_array($result)) {
                            if($can_login_to_high_privileges_department=="yes"&&$count_Consultant==1)$selected="selected='selected'"; else $selected="";

                            ?>
                            <option value='<?php echo $row['Employee_ID']; ?>' <?= $selected ?>><?php echo $row['Employee_Name']; ?></option>
                        <?php
                        $count_Consultant++;
                    }
                    ?>
                    </select>
                </td>
                <td style='text-align: right;'>Transaction Mode</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td id="Transaction_Area">
                                <select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                                    <?php
                                    $select_Transaction_type = mysqli_query($conn,"select Fast_Track from tbl_pharmacy_items_list_cache alc
                                                                            where alc.Employee_ID = '$Employee_ID' and
                                                                            Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
                                    $no_of_items = mysqli_num_rows($select_Transaction_type);
                                    if ($no_of_items > 0) {
                                        while ($data = mysqli_fetch_array($select_Transaction_type)) {
                                            $Fast_Track = $data['Fast_Track'];
                                        }
                                        if ($Fast_Track == '1') {
                                            echo "<option selected='selected'>Fast Track Transaction</option>";
                                        } else {
                                            echo "<option selected='selected'>Normal Transaction</option>";
                                        }
                                    } else {
                                        ?>
                                        <option selected="selected">Normal Transaction</option>
                                        <option <?php if (isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction' && $B_Type == 'Outpatient Cash') {
                                        echo "selected='selected'";
                                    } ?>>Fast Track Transaction</option>
    <?php
}
?>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" id="Remember_Mode" name="Remember_Mode" onclick="Remember_Mode_Function()" <?php if (isset($_SESSION['Transaction_Mode']) && $B_Type == 'Outpatient Cash') {
    echo "checked='checked'";
} ?>>
                                <label for="Remember_Mode">Remember</label>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style='text-align: right;'>Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
            </tr>
             <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Clinic
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            $count_clinic=1;
                            while ($row = mysqli_fetch_array($result)) {
                                 if($can_login_to_high_privileges_department=="yes"&&$count_clinic==1)$selected="selected='selected'"; else $selected="";
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>" <?= $selected ?>><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                                $count_clinic++;
                            } 
                            ?>
                        </select>
                    </td>
                    <td style="text-align:right;color:green" id="sponsor_for_this_trans">
                        <b><span style="color:red">*</span>Select Temporary Sponsor for this transaction</b>
                    </td>
                    <td>
                        <select id="new_sponsor_to_bill">
                            <?php 
                               $filter_sponsor="WHERE";
                               $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                            if($can_login_to_high_privileges_department=="yes"){
                                $filter_sponsor.=" auto_item_update_api<>'1' AND ";
                                ?>
                            <option value=""></option>
                            <?php 
                            }else{
                             ?>
                            <option value="<?= $Sponsor_ID ?>"><?= $Guarantor_Name ?></option>
                            <?php 
                            }
                                if($Billing_Type_filter=="Outpatient Credit"){
                                    $filter_sponsor.=" payment_method='credit'";
                                }else{
                                    $filter_sponsor.=" payment_method='cash'";
                                }
                                $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor $filter_sponsor") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                   while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                      $Sponsor_ID_ch=$sponsor_rows['Sponsor_ID'];
                                      $Guarantor_Name_ch=$sponsor_rows['Guarantor_Name'];
                                      //this hardcoded for temporary due to time limit... next version should create setup to control this
                                      if($Guarantor_Name_ch=="Pharmacy Project Cash"||($Guarantor_Name_ch=="Pharmacy Project Credit"&&strtolower($Guarantor_Name)=="nhif")){
                                          ///$selected="selected='selected'";
                                      }else{
                                          if($Billing_Type_filter=="Outpatient Credit"&&strtolower($Guarantor_Name_ch)==strtolower($Guarantor_Name)){
                                             $selected="selected='selected'"; 
                                          }else{
                                            $selected="";   
                                          } 
                                      }
                                      echo "<option value='$Sponsor_ID_ch' $selected>$Guarantor_Name_ch</option>";
                                   } 
                                }
                                ?>
                        </select>
                    </td>
                </tr>
                                <tr>
                  <td style="text-align:right">
                        Select Clinic Location
                    </td>
                    <td>
                             <select  style='width: 100%;height:30%'  name='clinic_location_id' id='clinic_location_id' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic_location WHERE enabled_disabled='enabled'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['clinic_location_id']; ?>"><?php echo $row['clinic_location_name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select> 
                    </td>
                    <td style="text-align:right">
                        Select Department
                    </td>
                    <td>
                     <select id='working_department' name='working_department'  style="width:100%">
                            <option value=""></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
        </table>
    </center>
</fieldset>

<fieldset>
    <table width=100% id="Process_Buttons_Area">
        <?php
        $select_Transaction_Items = mysqli_query($conn,
                "select Billing_Type
				    from tbl_pharmacy_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

        $no_of_items = mysqli_num_rows($select_Transaction_Items);
        ?>
        <td style='text-align: right;'>
            <?php
            if ($no_of_items > 0) {
                while ($data = mysqli_fetch_array($select_Transaction_Items)) {
                    $Billing_Type = $data['Billing_Type'];
                }

                if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                    if (isset($_SESSION['systeminfo']['Departmental_Collection']) && strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes') {
                        if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { //this setting allows system to display both (send to cashier) and (make payment) buttons
                            echo '<button class="art-button-green" type="button" onclick="Send_To_Cashier(); clearContent();">SEND TO CASHIER</button>';
                        }

                        if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                            ?>
                            <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                            <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                            <?php
                        } }
                        if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                        echo '<button class="art-button-green" type="button" onclick="Make_Payment(); clearContent();">MAKE PAYMENT</button>';
                        }
                    } else {
                        echo '<button class="art-button-green" type="button" onclick="Send_To_Cashier(); clearContent();">SEND TO CASHIER</button>';
                    }
                } else {
                    if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory' && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes') {
                        ?>
                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="SEND TO APPROVAL" onclick="Send_To_Approval()">
                                <?php
                            } else {
                                ?>
                        <button class="art-button-green" type="button" onclick="clearContent();Bill_Patient()
                                            ">BILL PATIENT</button>
                                <?php
                            }
                        }
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) && strtolower($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) == 'no') {
                        //check if patient exists as inpatient
                        $check_patient = mysqli_query($conn,"select Admision_ID from tbl_admission where Registration_ID = '$Registration_ID' and Admission_Status <> 'Discharged'") or die(mysqli_error($conn));
                        $num_check = mysqli_num_rows($check_patient);
                         $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                        if ($num_check > 0&&$can_login_to_high_privileges_department!="yes") {
                            echo '<button class="art-button-green" type="button" onclick="Inpatient_Warning();">ADD MEDICATION</button>';
                        } else {
                            echo '<button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD ITEMS</button>';
                        }
                    } else {
                        echo '<button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD ITEMS</button>';
                    }
                    ?>

        </td>
    </table>
    <img id="loader" style="float:left;display:none" src="images/22.gif"/>
</fieldset>
<fieldset>   
    <center>
        <table width=100%>
            <tr>
                <td>
                    <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll; height: 200px;'>
                        <center>
                            <table width =100% border=0>
                                <tr><td colspan=8><hr></td></tr>
                                <tr id="thead">
                                    <td style="text-align: left;" width=5%><b>Sn</b></td>
                                    <td><b>Medication Name</b></td>
                                    <td style="text-align: left;" width=25%><b>Dosage</b></td>
                                    <td style="text-align: right;" width=8%><b>Price</b></td>
                                    <td style="text-align: right;" width=8%><b>Discount</b></td>
                                    <td style="text-align: right;" width=8%><b>Quantity</b></td>
                                    <td style="text-align: right;" width=8%><b>Sub Total</b></td>
                                    <td style="text-align: center;" width=6%><b>Action</b></td></tr>
                                <tr><td colspan=8><hr></td></tr>

                                <?php
                                $temp = 0;
                                $total = 0;
                                $select_Transaction_Items = mysqli_query($conn,
                                        "select Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Dosage,Discount
				     from tbl_items t, tbl_pharmacy_items_list_cache alc
					 where alc.Item_ID = t.Item_ID and
					     alc.Employee_ID = '$Employee_ID' and
						     Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

                                $no_of_items = mysqli_num_rows($select_Transaction_Items);
                                while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                    echo "<tr><td>" . ++$temp . "</td>";
                                    echo "<td>" . $row['Product_Name'] . "</td>";
                                    echo "<td>" . $row['Dosage'] . "</td>";
                                    echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "</td>";
                                    echo "<td style='text-align:right;'>" . $row['Discount'] . "</td>";
                                    echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                    echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity'])) . "</td>";
                                    ?>
                                    <td style="text-align: center;"> 
                                        <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                    </td>
    <?php
    $total = $total + (($row['Price'] - $row['Discount'] ) * $row['Quantity']);
}echo "</tr></table>";
?>
                                </fieldset>
                                </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right; width: 70%;' id='Total_Area'>
                                        <h4>Total : <?php
                                            echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;';
                                            ;
?></h4> <input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>

                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                    <div id="Inpatient_Warning">
                        <center>
                            <b>'<?php echo $Patient_Name; ?>'&nbsp; currently is inpatient</b>
                        </center><br/>
                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: right;">
                                    <input type="button" onclick="Go_To_Inpatient_Page()" class="art-button-green" value="PROCESS AS INPATIENT">
                                    <input type="button" onclick="Go_To_Inpatient_Page_Cancel()" class="art-button-green" value="CANCEL">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="Non_Supported_Item">
                        <center>
                            Selected Item is not supported by <?php echo $Guarantor_Name; ?><br/>
                            Please change bill type.
                        </center>
                    </div>

                    <div id="Sponsor_Warning">
                        <center>The Bill type selected, patient will pay cash. <br/>Are you sure?</center><br/>
                        <table width="100%">
                            <tr>
                                <td style="text-align: right;">
                                    <input type="button" value="YES" onclick="Response('yes')" class="art-button-green">
                                    <input type="button" value="NO" onclick="Response('no')" class="art-button-green">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="Item_Already_Added">
                        <center>Duplicate Item! Dawa hii ulishaichagua</center><br/>
                    </div>

                    <div id="Change_Billing_Type_Alert">
                        You are about to create fast track transaction. Billing type will change to <b>Outpatient Cash</b><br/><br/>
                        <table width="100%">
                            <tr>
                                <td style="text-align: right;">
                                    <input type="button" value="CONTINUE" onclick="Change_Billing_Type()" class="art-button-green">
                                    <input type="button" value="DISCARD" onclick="Close_Change_Billing_Type_Alert()" class="art-button-green">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <script type="text/javascript">
                        function Refresh_Transaction_Mode() {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            if (Billing_Type == 'Outpatient Cash') {
                                if (window.XMLHttpRequest) {
                                    myObjectRefreshMode = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectRefreshMode = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectRefreshMode.overrideMimeType('text/xml');
                                }
                                myObjectRefreshMode.onreadystatechange = function () {
                                    dataRefresh = myObjectRefreshMode.responseText;
                                    if (myObjectRefreshMode.readyState == 4) {
                                        document.getElementById("Transaction_Area").innerHTML = dataRefresh;
                                    }
                                }; //specify name of function that will handle server response........

                                myObjectRefreshMode.open('GET', 'Refresh_Transaction_Mode.php', true);
                                myObjectRefreshMode.send();
                            }
                        }
                    </script>

                    <script type="text/javascript">
                        function Refresh_Remember_Mode() {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            if (Billing_Type == 'Outpatient Credit') {
                                document.getElementById("Remember_Mode").checked = false;
                            } else {
                                if (window.XMLHttpRequest) {
                                    myObjectRem = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectRem.overrideMimeType('text/xml');
                                }
                                myObjectRem.onreadystatechange = function () {
                                    dataRem = myObjectRem.responseText;
                                    if (myObjectRem.readyState == 4) {
                                        var feedback = dataRem;
                                        if (feedback == 'yes') {
                                            document.getElementById("Remember_Mode").checked = true;
                                        } else {
                                            document.getElementById("Remember_Mode").checked = false;
                                        }
                                    }
                                }; //specify name of function that will handle server response........
                                myObjectRem.open('GET', 'Refresh_Remember_Mode.php', true);
                                myObjectRem.send();
                            }
                        }
                    </script>
                    <script type="text/javascript">
                        function Remember_Mode_Function() {
                            var Controler = 'not checked';
                            if (document.getElementById("Remember_Mode").checked) {
                                Controler = "checked";
                            } else {
                                Controler = "not checked";
                            }
                            var Transaction_Mode = document.getElementById("Transaction_Mode").value;

                            if (window.XMLHttpRequest) {
                                myObjectRemember = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectRemember = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectRemember.overrideMimeType('text/xml');
                            }
                            myObjectRemember.onreadystatechange = function () {
                                dataRemember = myObjectRemember.responseText;
                                if (myObjectRemember.readyState == 4) {
                                    //Continue.................................
                                }
                            }; //specify name of function that will handle server response........

                            myObjectRemember.open('GET', 'Remember_Transaction_Mode.php?&Transaction_Mode=' + Transaction_Mode + '&Controler=' + Controler, true);
                            myObjectRemember.send();
                        }
                    </script>

                    <script type="text/javascript">
                        function Validate_Transaction_Mode() {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                            if (Transaction_Mode == 'Fast Track Transaction' && Billing_Type == 'Outpatient Credit') {
                                document.getElementById("Transaction_Mode").value = 'Normal Transaction';
                                $("#Change_Billing_Type_Alert").dialog("open");
                            }
                            Remember_Mode_Function();
                        }
                    </script>

                    <script type="text/javascript">
                        function Close_Change_Billing_Type_Alert() {
                            $("#Change_Billing_Type_Alert").dialog("close");
                        }
                    </script>

                    <script type="text/javascript">
                        function Change_Billing_Type() {
                            document.getElementById("Billing_Type").value = 'Outpatient Cash';
                            document.getElementById("Transaction_Mode").value = 'Fast Track Transaction';
                            $("#Change_Billing_Type_Alert").dialog("close");
                        }
                    </script>

                    <script type="text/javascript">
                        function Send_To_Approval() {
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Folio_Number = document.getElementById("Folio_Number").value;
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Registration_ID = '<?php echo $Registration_ID; ?>';

                            if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Folio_Number == '' || Folio_Number == null || Consultant_ID == '' || Consultant_ID == null)) {

                                if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                    document.getElementById("Claim_Form_Number").focus();
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                }

                                if (Folio_Number == '' || Folio_Number == null) {
                                    document.getElementById("Folio_Number").focus();
                                    document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Folio_Number").style = 'border: 3px';
                                }

                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else if (Guarantor_Name != 'NHIF' && (Consultant_ID == '' || Consultant_ID == null)) {
                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else {
                                var r = confirm("Are you sure you want to send this bill to approval center?");
                                if (r == true) {
                                    document.location = 'Pharmacy_Bill_Patient_Send_To_Approval.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID;
                                }
                            }
                        }
                    </script>

                    <script type="text/javascript">
                        function Sponsor_Warning() {
                            var Guarantor_Name = '<?php echo strtolower($Guarantor_Name); ?>';
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            update_new_transaction_sponsor_list(Billing_Type);
                            if (Billing_Type == 'Outpatient Cash' && Guarantor_Name != 'cash') {
                                $("#Sponsor_Warning").dialog("open");
                            } else {
                                document.getElementById("Transaction_Mode").value = 'Normal Transaction';
                            }
                        }
                        function update_new_transaction_sponsor_list(Billing_Type){
                            var Sponsor_ID ='<?= $Sponsor_ID ?>';
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            $.ajax({
                               type:'GET',
                               url:'update_new_transaction_sponsor_list.php',
                               data:{Billing_Type:Billing_Type,Sponsor_ID:Sponsor_ID,Guarantor_Name:Guarantor_Name},
                               success:function(data){
                                   //console.log("Sponsor_ID=>"+Sponsor_ID+"\n");
                                   //console.log("success"+data)
                                   $("#new_sponsor_to_bill").prop("class","");
                                   $('#new_sponsor_to_bill').html(data);
                                   $("#new_sponsor_to_bill").select2();
                               },
                               complete: function (data) {
                                    console.log("completed"+data)
                                }, error: function (jqXHR, textStatus, errorThrown) {
                                    alert(errorThrown);
                                    console.log("Error")
                                }
                            });
                        }
                    </script>

                    <script type="text/javascript">
                        function Response(feedback) {
                            if (feedback == 'no') {
                                
                                
                               // document.getElementById("Billing_Type").value = 'Outpatient Credit';
                               var bill_contect= "<option selected='selected'>\n\
                                       Outpatient Credit\n\
                                    </option>\n\
                                    <option>\n\
                                           Outpatient Cash\n\
                                     </option>";
                                     $("#Billing_Type").prop("class","");
                                     $("#Billing_Type").html(bill_contect);
                                      $("#Billing_Type").select2();
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            update_new_transaction_sponsor_list(Billing_Type);
                                document.getElementById("Remember_Mode").checked = false;
                            } else {
                                Refresh_Transaction_Mode();
                                Refresh_Remember_Mode();
                            }
                            $("#Sponsor_Warning").dialog("close");
                        }
                    </script>
                    <div id="Add_Pharmacy_Items" style="width:50%;" >
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td width=40%>
                                    <table width=100% style='border-style: none;'>
                                        <tr>
                                            <td>
                                                <b>Category : </b>
                                                <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                                    <option selected='selected'></option>
                                                    <?php
                                                    $data = mysqli_query($conn,"
				     select Item_Category_Name, Item_Category_ID
				     from tbl_item_category WHERE Category_Type = 'Pharmacy'
				     ") or die(mysqli_error($conn));
                                                    while ($row = mysqli_fetch_array($data)) {
                                                        echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                                    }
                                                    ?>   
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                                                    <table width=100%>
                                                               <?php
                                                               $result = mysqli_query($conn,"SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID where ip.Sponsor_ID='$Sponsor_ID' AND Item_Type = 'Pharmacy' and Status = 'Available' order by Product_Name limit 150");
                                                               while ($row = mysqli_fetch_array($result)) {
                                                                   echo "<tr>
				   <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                                                   ?>

                                                            <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="varidate_dosage_duration('<?php echo $row['Item_ID']; ?>');Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                                                        Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Sponsor_ID; ?>');">

    <?php
    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
}
?>
                                                    </table>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width=100% border=0>
                                        <tr>
                                            <td colspan="2" id="Transaction_Mode_Area" style="text-align: center;">

                                            </td>
                                        </tr>
                                        <tr><td colspan="2">&nbsp;</td></tr>
                                        <tr>
                                            <td style='text-align: right;' width=30%>Item Name</td>
                                            <td>
                                                <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
                                                <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Item Price</td>
                                            <td>
                                                <input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Price Discount</td>
                                            <td>
                                                <input type='text' name='Discount' id='Discount' placeholder='Discount' value="0" onkeypress="numberOnly(this);
                                                        Calculate_Total();" oninput="numberOnly(this); Calculate_Total();" onkeyup="numberOnly(this);
                                                                Calculate_Total();">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Item Balance</td>
                                            <td>
                                                <input type='text' name='Item_Balance' id='Item_Balance' readonly='readonly' placeholder='Item Balance'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Quantity</td>
                                            <td>
                                                <input type='text' name='Quantity' id='Quantity' autocomplete='off' placeholder='Quantity' onkeypress="numberOnly(this);
                                                        Calculate_Total();" oninput="numberOnly(this); Calculate_Total();" onkeyup="numberOnly(this);
                                                                Calculate_Total();">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Total</td>
                                            <td>
                                                <input type='text' name='Total' id='Total' readonly='readonly' placeholder='Total' value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan=2>
                                                <textarea name='Dosage' id='Dosage' placeholder='Dosage'></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan=2 style='text-align: center;'>
                                                <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEMS' onclick='Verify_Balance()'>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
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



    <script>
        $(document).ready(function () {
            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
        });
    </script>

                    <div id="Balance_Error">
                        Umebakiwa na idadi ndogo kutoa kiwango ulichokiandika
                    </div>
                    <script type="text/javascript">
                        function Calculate_Total() {
                            var Price = document.getElementById("Price").value;
                            var Discount = document.getElementById("Discount").value;
                            var Quantity = document.getElementById("Quantity").value;

                            if (Price != null && Price != '' && Quantity != null && Quantity != '') {
                                if (window.XMLHttpRequest) {
                                    My_Object_Calc = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    My_Object_Calc = new ActiveXObject('Micrsoft.XMLHTTP');
                                    My_Object_Calc.overrideMimeType('text/xml');
                                }
                                My_Object_Calc.onreadystatechange = function () {
                                    dataCalc = My_Object_Calc.responseText;
                                    if (My_Object_Calc.readyState == 4) {
                                        document.getElementById("Total").value = dataCalc;
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Calc.open('GET', 'Pharmacy_Calculate_Total.php?&Price=' + Price + '&Discount=' + Discount + '&Quantity=' + Quantity, true);
                                My_Object_Calc.send();
                            }
                        }
                    </script>

                    <script type='text/javascript'>
                        function Send_To_Cashier() {
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Folio_Number = document.getElementById("Folio_Number").value;
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            var Consultant_ID = document.getElementById("Consultant_ID").value;

                            if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Folio_Number == '' || Folio_Number == null)) {

                                if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                    document.getElementById("Claim_Form_Number").focus();
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                }

                                if (Folio_Number == '' || Folio_Number == null) {
                                    document.getElementById("Folio_Number").focus();
                                    document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Folio_Number").style = 'border: 3px';
                                }

                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else {
                                Verify_Price_And_Quantity();
                                /*var r = confirm("Are you sure you want to send this bill to cashier?");
                                 if(r == true){
                                 document.location = 'Send_To_Cashier_Pharmacy.php?Registration_ID='+Registration_ID+'&Folio_Number='+Folio_Number+'&Claim_Form_Number='+Claim_Form_Number+'&Consultant_ID='+Consultant_ID;
                                 }*/
                            }
                        }
                    </script>

                    <script type="text/javascript">
                        function Verify_Price_And_Quantity() {
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Folio_Number = document.getElementById("Folio_Number").value;
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            var Consultant_ID = document.getElementById("Consultant_ID").value;

                            if (window.XMLHttpRequest) {
                                My_Object_Verify = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                My_Object_Verify = new ActiveXObject('Micrsoft.XMLHTTP');
                                My_Object_Verify.overrideMimeType('text/xml');
                            }
                            My_Object_Verify.onreadystatechange = function () {
                                data690 = My_Object_Verify.responseText;
                                if (My_Object_Verify.readyState == 4) {
                                    var feedback = data690;
                                    if (feedback == 'yes') {
                                        var r = confirm("Are you sure you want to send this bill to cashier?");
                                        if (r == true) {
                                            document.location = 'Send_To_Cashier_Pharmacy.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&Check_In_Type=Pharmacy';
                                        }
                                    } else {
                                        alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                                    }
                                }
                            }; //specify name of function that will handle server response........

                            My_Object_Verify.open('GET', 'Pharmacy_Verify_Price_Quantity.php?&Registration_ID=' + Registration_ID, true);
                            My_Object_Verify.send();
                        }
                    </script>

                    <script>
                        function Confirm_Remove_Item(Item_Name, Item_Cache_ID, Registration_ID) {
                            var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            if (Confirm_Message == true) {
                                if (window.XMLHttpRequest) {
                                    My_Object_Remove_Item = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                                    My_Object_Remove_Item.overrideMimeType('text/xml');
                                }
                                My_Object_Remove_Item.onreadystatechange = function () {
                                    data6 = My_Object_Remove_Item.responseText;
                                    if (My_Object_Remove_Item.readyState == 4) {
                                        document.getElementById('Picked_Items_Fieldset').innerHTML = data6;
                                        update_total(Registration_ID);
                                        //update_Billing_Type();
                                        //Update_Claim_Form_Number();
                                        update_total(Registration_ID);
                                        update_process_buttons(Registration_ID);
                                        update_billing_type(Registration_ID);
                                        update_transaction_mode(Registration_ID);
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Remove_Item.open('GET', 'Pharmacy_Remove_Item_From_List.php?Item_Cache_ID=' + Item_Cache_ID + '&Registration_ID=' + Registration_ID, true);
                                My_Object_Remove_Item.send();
                            }
                        }
                    </script>

                    <script>
                        function update_total() {
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            if (window.XMLHttpRequest) {
                                My_Object_Update_Total = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                My_Object_Update_Total = new ActiveXObject('Micrsoft.XMLHTTP');
                                My_Object_Update_Total.overrideMimeType('text/xml');
                            }

                            My_Object_Update_Total.onreadystatechange = function () {
                                dataT600 = My_Object_Update_Total.responseText;
                                if (My_Object_Update_Total.readyState == 4) {
                                    document.getElementById('Total_Area').innerHTML = dataT600;
                                }
                            }; //specify name of function that will handle server response........

                            My_Object_Update_Total.open('GET', 'Pharmacy_Update_Total.php?Registration_ID=' + Registration_ID, true);
                            My_Object_Update_Total.send();
                        }
                    </script>

                    <script>
                        function update_Billing_Type() {
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                            if (window.XMLHttpRequest) {
                                My_Object_Update_Total = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                My_Object_Update_Total = new ActiveXObject('Micrsoft.XMLHTTP');
                                My_Object_Update_Total.overrideMimeType('text/xml');
                            }

                            My_Object_Update_Total.onreadystatechange = function () {
                                data600 = My_Object_Update_Total.responseText;
                                if (My_Object_Update_Total.readyState == 4) {
                                    document.getElementById('Total_Area').innerHTML = data600;
                                    //update_total(Registration_ID);
                                    //update_Billing_Type(Registration_ID);
                                    //Update_Claim_Form_Number();
                                }
                            }; //specify name of function that will handle server response........

                            My_Object_Update_Total.open('GET', 'pharmacy_update_Billing_Type.php?Registration_ID=' + Registration_ID + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID, true);
                            My_Object_Update_Total.send();
                        }
                    </script>

                    <script type="text/javascript">
                        function Get_Details_Verify(Item_Name, Item_ID) {
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            if (window.XMLHttpRequest) {
                                myObjectVerify = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectVerify.overrideMimeType('text/xml');
                            }
                            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
                            myObjectVerify.onreadystatechange = function () {
                                dataVer = myObjectVerify.responseText;
                                if (myObjectVerify.readyState == 4) {
                                    var feedbak = dataVer;
                                    if (feedbak == 'yes') {
                                        Get_Details(Item_Name, Item_ID);
                                    } else {
                                        document.getElementById('Quantity').value = '';
                                        document.getElementById("Price").value = '';
                                        document.getElementById('Dosage').value = '';
                                        document.getElementById('Discount').value = '';
                                        document.getElementById('Total').value = '';
                                        document.getElementById('Item_Balance').value = '';
                                        document.getElementById("Item_ID").value = '';
                                        document.getElementById("Item_Name").value = '';
                                        $("#Item_Already_Added").dialog("open");
                                    }
                                }
                            }; //specify name of function that will handle server response........

                            myObjectVerify.open('GET', 'Pharmacy_Get_Details_Verify.php?Item_ID=' + Item_ID + '&Registration_ID=' + Registration_ID, true);
                            myObjectVerify.send();
                        }
                    </script>
                    <script>
                        function Get_Details(Item_Name, Item_ID) {
                            document.getElementById('Quantity').value = '';
                            document.getElementById('Dosage').value = '';
                            document.getElementById('Item_Balance').value = '';
                            document.getElementById('Discount').value = '';
                            document.getElementById('Total').value = '';
                            var Temp = '';
                            if (window.XMLHttpRequest) {
                                myObjectGetItemName = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectGetItemName.overrideMimeType('text/xml');
                            }

                            document.getElementById("Item_Name").value = Item_Name;
                            document.getElementById("Item_ID").value = Item_ID;
                            Get_Item_Balance(Item_ID);
                        }
                    </script>

                    <script type="text/javascript">
                        function Get_Item_Balance(Item_ID) {
                            if (window.XMLHttpRequest) {
                                myObjectGetBalance = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectGetBalance.overrideMimeType('text/xml');
                            }
                            myObjectGetBalance.onreadystatechange = function () {
                                Data_Balance = myObjectGetBalance.responseText;
                                if (myObjectGetBalance.readyState == 4) {
                                    document.getElementById('Item_Balance').value = Data_Balance;
                                }
                            }; //specify name of function that will handle server response........
                            myObjectGetBalance.open('GET', 'Pharmacy_Get_Item_Balance.php?Item_ID=' + Item_ID, true);
                            myObjectGetBalance.send();
                        }
                    </script>

                    <script type="text/javascript">
                        function Get_Item_Name(Item_Name, Item_ID) {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                            Sponsor_ID=$("#new_sponsor_to_bill").val();
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
                                            Get_Details_Verify(Item_Name, Item_ID);
                                        } else {
                                            document.getElementById("Price").value = 0;
                                            document.getElementById("Quantity").value = '';
                                            document.getElementById("Item_Balance").value = '';
                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Total").value = '';
                                            document.getElementById("Discount").value = '';
                                            document.getElementById("Item_Balance").value = '';
                                            $("#Non_Supported_Item").dialog("open");
                                        }
                                    }
                                }; //specify name of function that will handle server response........
                                My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID, true);
                                My_Object_Verify_Item.send();
                            } else {
                                Get_Details_Verify(Item_Name, Item_ID);
                            }
                        }
                    </script>

                    <script>
                        function vieweRemovedItem() {
                            //alert("item");

                            $.ajax({
                                type: 'POST',
                                url: "change_items_pharmacy_list.php",
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
                                url: "change_items_pharmacy_list.php",
                                data: "readdItem=" + item,
                                success: function (data) {
                                    showItem();
                                }, error: function (jqXHR, textStatus, errorThrown) {
                                    alert(errorThrown);
                                }
                            });
                        }
                    </script>


                    <script>
                        function openItemDialog() {
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            if (Consultant_ID == '') {
                                alert("Please select consultant to continue");
                                document.getElementById("Consultant_ID").style = 'border-color:red';
                                exit;
                            }
                            var new_sponsor_to_bill = document.getElementById("new_sponsor_to_bill").value;
                            if (new_sponsor_to_bill == '') {
                                alert("Please select temporary sponsor for this transaction");
                                document.getElementById("sponsor_for_this_trans").style = 'color:red;font-weight:bold;text-align:right';
                                exit;
                            }
                            var Clinic_ID = document.getElementById("Clinic_ID").value;
                                      if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Select clinic to continue")
                                        exit;
                                    }
                            var clinic_location_id = document.getElementById("clinic_location_id").value;
                                      if(clinic_location_id==''|| clinic_location_id==null){
                                        alert("Select clinic location to continue")
                                        exit;
                                    }
                            var working_department = document.getElementById("working_department").value;
                                      if(working_department==''|| working_department==null){
                                        alert("Select Department to continue")
                                        exit;
                                    }
                            var Transaction_Title = document.getElementById("Transaction_Mode").value
                            document.getElementById("Transaction_Mode_Area").innerHTML = '<span style="color: #037CB0;"><h4>TRANSACTION MODE : <b>' + Transaction_Title + '</b></h4></span>';
                            $("#Add_Pharmacy_Items").dialog("open");
                            getItemsListFiltered("");
                        }
                    </script>

                    <script type="text/javascript">
                        function Inpatient_Warning() {
                            $("#Inpatient_Warning").dialog("open");
                        }
                    </script>
                    <script type="text/javascript">
                        function Go_To_Inpatient_Page() {
                            var Registration_ID = '<?php echo $Registration_ID; ?>';
                            document.location = 'pharmacyinpatientpage.php?Registration_ID=' + Registration_ID + '&PharmacyInpatientPage=PharmacyInpatientPageThisForm';
                        }
                    </script>
                    <script type="text/javascript">
                        function Go_To_Inpatient_Page_Cancel() {
                            $("#Inpatient_Warning").dialog("close");
                            document.location = 'pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm';
                        }
                    </script>
                    <script>
                        function clearContent() {
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Item_Name").value = '';
                            document.getElementById("Item_ID").value = '';
                            document.getElementById("Price").value = '';
                            document.getElementById("Dosage").value = '';
                            document.getElementById("Discount").value = '';
                            document.getElementById("Total").value = '';
                            document.getElementById("Item_Balance").value = '';
                            document.getElementById("Search_Value").value = '';
                        }
                    </script>


                    <script>
                        function Get_Item_Price(Item_ID, Sponsor_ID) {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                            //alert(Item_ID);
                            Sponsor_ID=$("#new_sponsor_to_bill").val();
                            if (window.XMLHttpRequest) {
                                myObject = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObject.overrideMimeType('text/xml');
                            }
                            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
                            myObject.onreadystatechange = function () {
                                data = myObject.responseText;

                                if (myObject.readyState == 4) {
                                    document.getElementById('Price').value = data;
                                    //alert(data);
                                }
                            }; //specify name of function that will handle server response........

                            myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Transaction_Mode=' + Transaction_Mode, true);
                            myObject.send();
                        }
                    </script>

                    <script>
                        function update_process_buttons(Registration_ID) {
                            var Billing_Type=$("#Billing_Type").val();
                            if (window.XMLHttpRequest) {
                                my_Object_Update_Process = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                my_Object_Update_Process = new ActiveXObject('Micrsoft.XMLHTTP');
                                my_Object_Update_Process.overrideMimeType('text/xml');
                            }

                            my_Object_Update_Process.onreadystatechange = function () {
                                data = my_Object_Update_Process.responseText;

                                if (my_Object_Update_Process.readyState == 4) {
                                    document.getElementById('Process_Buttons_Area').innerHTML = data;
                                }
                            }; //specify name of function that will handle server response........

                            my_Object_Update_Process.open('GET', 'Pharmacy_Update_Process_Button.php?Registration_ID=' + Registration_ID+"&Billing_Type="+Billing_Type, true);
                            my_Object_Update_Process.send();
                        }
                    </script>

                    <script type="text/javascript">
                        function update_transaction_mode(Registration_ID) {
                            if (window.XMLHttpRequest) {
                                myObjectUpd = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectUpd = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectUpd.overrideMimeType('text/xml');
                            }

                            myObjectUpd.onreadystatechange = function () {
                                dataUpd = myObjectUpd.responseText;
                                if (myObjectUpd.readyState == 4) {
                                    document.getElementById('Transaction_Area').innerHTML = dataUpd;
                                }
                            }; //specify name of function that will handle server response........

                            myObjectUpd.open('GET', 'Update_Transaction_Mode.php?Registration_ID=' + Registration_ID, true);
                            myObjectUpd.send();
                        }
                    </script>

                    <script src="js/jquery-1.8.0.min.js"></script>
                    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
                    <script src="script.js"></script>
                    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
                    <script src="script.responsive.js"></script>

                    <script>
                        $(document).ready(function () {
                            $("#Add_Pharmacy_Items").dialog({autoOpen: false, width: 950, height: 450, title: 'ADD PHARMACY ITEMS', modal: true});
                            $("#Inpatient_Warning").dialog({autoOpen: false, width: '40%', height: 150, title: 'INPATIENT WARNING', modal: true});
                            $("#Non_Supported_Item").dialog({autoOpen: false, width: '40%', height: 150, title: 'NON SUPPORTED ITEM', modal: true});
                            $("#Sponsor_Warning").dialog({autoOpen: false, width: '40%', height: 180, title: 'BILLING TYPE WARNING!', modal: true});
                            $("#Item_Already_Added").dialog({autoOpen: false, width: '40%', height: 110, title: 'ITEM WARNING!', modal: true});
                            $("#Balance_Error").dialog({autoOpen: false, width: '40%', height: 110, title: 'BALANCE WARNING!', modal: true});
                            $("#Change_Billing_Type_Alert").dialog({autoOpen: false, width: '60%', height: 150, title: 'TRANSACTION WARNING!', modal: true});
                        });
                    </script>
                    <script type="text/javascript">
                        function Verify_Balance() {
                            var Item_ID = document.getElementById("Item_ID").value;
                            var Quantity = document.getElementById("Quantity").value;

                            if (window.XMLHttpRequest) {
                                myObjectVerifyBalance = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectVerifyBalance = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectVerifyBalance.overrideMimeType('text/xml');
                            }

                            myObjectVerifyBalance.onreadystatechange = function () {
                                dataVerBalance = myObjectVerifyBalance.responseText;
                                if (myObjectVerifyBalance.readyState == 4) {
                                    var feedback = dataVerBalance;
                                    if (feedback == 'yes') {
                                        Get_Selected_Item();
                                    } else {
                                        if (Quantity != null && Quantity != '') {
                                            $("#Balance_Error").dialog("open");
                                        }
                                    }
                                }
                            }; //specify name of function that will handle server response........
                            myObjectVerifyBalance.open('GET', 'Pharmacy_Verify_Balance.php?Quantity=' + Quantity + '&Item_ID=' + Item_ID, true);
                            myObjectVerifyBalance.send();
                        }
                    </script>
                    <script>
                        function Get_Selected_Item() {
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            var Item_ID = document.getElementById("Item_ID").value;
                            var Item_Name = document.getElementById("Item_Name").value;
                            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                            Sponsor_ID=$("#new_sponsor_to_bill").val();
                            var Quantity = document.getElementById("Quantity").value;
                            var Discount = document.getElementById("Discount").value;
                            var Registration_ID = <?php echo $Registration_ID; ?>;
                            var Dosage = document.getElementById("Dosage").value;
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            Guarantor_Name=$("#new_sponsor_to_bill").text();
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                            var Clinic_ID = document.getElementById("Clinic_ID").value;
                            var clinic_location_id = document.getElementById("clinic_location_id").value;
                            var working_department = document.getElementById("working_department").value;
                            //alert(Sponsor_ID);exit;
                             //   alert(Clinic_ID)
                            var Price = document.getElementById("Price").value;
                            //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 
                            if (parseFloat(Price) > 0) {

                            } else {
                                alert('Selected Item missing price.');
                                exit;
                            }
                            //alert('Add_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount);
                            if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null && Dosage != null && Dosage != '') {

                                if (window.XMLHttpRequest) {
                                    myObject2 = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject2.overrideMimeType('text/xml');
                                }
                                myObject2.onreadystatechange = function () {
                                    data = myObject2.responseText;

                                    if (myObject2.readyState == 4) {
                                        //alert("One Item Added");
                                        document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                                        //update_Billing_Type(Registration_ID);
                                        //Update_Claim_Form_Number();
                                        document.getElementById("Item_Name").value = '';
                                        document.getElementById("Item_ID").value = '';
                                        document.getElementById("Quantity").value = '';
                                        document.getElementById("Price").value = '';
                                        document.getElementById("Dosage").value = '';
                                        document.getElementById("Discount").value = '';
                                        document.getElementById("Total").value = '';
                                        document.getElementById("Item_Balance").value = '';
                                        document.getElementById("Search_Value").focus();
                                        alert("Item Added Successfully");
                                        update_billing_type(Registration_ID);
                                        update_total(Registration_ID);
                                        update_process_buttons(Registration_ID);
                                        update_transaction_mode(Registration_ID);
                                    }
                                }; //specify name of function that will handle server response........

                                //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                myObject2.open('GET', 'Pharmacy_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Consultant_ID=' + Consultant_ID + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number + '&Billing_Type=' + Billing_Type + '&Dosage=' + Dosage + '&Discount=' + Discount + '&Transaction_Mode=' + Transaction_Mode+"&Clinic_ID="+Clinic_ID+'&working_department='+working_department+'&clinic_location_id='+clinic_location_id, true);
                                myObject2.send();

                            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
                                alertMessage();
                            } else {
                                if (Quantity == '' || Quantity == null) {
                                    document.getElementById("Quantity").focus();
                                    document.getElementById("Quantity").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Quantity").style = 'border: 3px';
                                }

                                if (Dosage == '' || Dosage == null) {
                                    document.getElementById("Dosage").focus();
                                    document.getElementById("Dosage").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Dosage").style = 'border: 3px';
                                }
                            }
                        }
                    </script>


                    <script>
                        function getItemsList(Item_Category_ID) {
                            document.getElementById("Search_Value").value = '';
                            document.getElementById("Item_Name").value = '';
                            document.getElementById("Item_ID").value = '';
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';

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
                                    //document.getElementById('Approval').readonly = 'readonly';
                                    document.getElementById('Items_Fieldset').innerHTML = data265;
                                }
                            }; //specify name of function that will handle server response........
                            myObject.open('GET', 'Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
                            myObject.send();
                        }
                    </script>

                    <script>
                        function update_billing_type(Registration_ID) {
                            if (window.XMLHttpRequest) {
                                myObjectUpdateBilling = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectUpdateBilling = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectUpdateBilling.overrideMimeType('text/xml');
                            }

                            myObjectUpdateBilling.onreadystatechange = function () {
                                data2605 = myObjectUpdateBilling.responseText;
                                if (myObjectUpdateBilling.readyState == 4) {
                                    document.getElementById('Billing_Type').innerHTML = data2605;
                                }
                            }; //specify name of function that will handle server response........
                            myObjectUpdateBilling.open('GET', 'Pharmacy_Update_Billing_Type.php?Registration_ID=' + Registration_ID, true);
                            myObjectUpdateBilling.send();
                        }
                    </script>

                    <script>
                        function getItemsListFiltered(Item_Name) {
                            document.getElementById("Item_Name").value = '';
                            document.getElementById("Item_ID").value = '';
                            document.getElementById("Dosage").value = '';
                            document.getElementById("Quantity").value = '';
                            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                            Sponsor_ID=$("#new_sponsor_to_bill").val();
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
                                    document.getElementById('Items_Fieldset').innerHTML = data135;
                                }
                            }; //specify name of function that will handle server response........
                            myObject.open('GET', 'Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
                            myObject.send();
                        }
                    </script>

        <div id="dossage_feedback_message">

        </div>
    <script type="text/javascript">
        function close_dialog_drug_duration_alert(){
            $("#dossage_feedback_message").dialog("close");
        }
        function varidate_dosage_duration(drug_item_id){
            var uri="varidate_dosage_duration.php";
             
             var Registration_ID='<?php echo $Registration_ID; ?>';
             //alert(Registration_ID)
             var check="false";
             after_bill="after_bill";
             $.ajax({
                type: 'GET',
                url: uri,
                data: {drug_item_id : drug_item_id,Registration_ID:Registration_ID},
                success: function(data){
                    //alert("moja")
                   
                   if(data==1){
                    //   alert(data)
                     // clearContent();Bill_Patient()
                      
                   }else{
                      $("#dossage_feedback_message").dialog({autoOpen: false, width: '75%', height: 450, title: 'PATIENT MEDICINE DOSSAGE DURATION ALERT', modal: true});
                      $("#dossage_feedback_message").dialog("open").html(data);
                 
             }
                },
                error: function(){

                }
            });
        }
        </script>
                    <script type='text/javascript'>
                        function Bill_Patient() {
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Folio_Number = document.getElementById("Folio_Number").value;
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Registration_ID = '<?php echo $Registration_ID; ?>';


                            if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Consultant_ID == '' || Consultant_ID == null)) {

                                if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                    document.getElementById("Claim_Form_Number").focus();
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                }

                                if (Folio_Number == '' || Folio_Number == null) {
                                    document.getElementById("Folio_Number").focus();
                                    document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Folio_Number").style = 'border: 3px';
                                }

                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else if (Guarantor_Name != 'NHIF' && (Consultant_ID == '' || Consultant_ID == null)) {
                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else {
                                var r = confirm("Are you sure you want to BILL this PATIENT?");
                                if (r == true) {
                                    document.location = 'Pharmacy_Bill_Patient.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID;
                                }
                            }
                        }
                    </script>
<!-------------------------------------------------------------------------------------------------------> 
<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
         $(document).ready(function(){
             $('select').select2();
         });  
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var registration_id = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        Sponsor_ID=$("#new_sponsor_to_bill").val();
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){      
                 var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                 var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                 var Folio_Number = document.getElementById("Folio_Number").value;
                 var Consultant_ID = document.getElementById("Consultant_ID").value;
                 var Registration_ID = '<?php echo $Registration_ID; ?>';


                document.location = 'Pharmacy_Make_Payment.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&manual_offline=manual';   
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
               
        var uri = '../epay/pharmacyothersworkspageOfflinePayment.php';
        
               
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
            var Folio_Number = document.getElementById("Folio_Number").value;
            var Consultant_ID = document.getElementById("Consultant_ID").value;
            var Registration_ID = '<?php echo $Registration_ID; ?>';

          
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Guarantor_Name:Guarantor_Name,Claim_Form_Number:Claim_Form_Number,Consultant_ID:Consultant_ID,Folio_Number:Folio_Number},
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
                            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                            var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                            var Folio_Number = document.getElementById("Folio_Number").value;
                            var Consultant_ID = document.getElementById("Consultant_ID").value;
                            var Registration_ID = '<?php echo $Registration_ID; ?>';


                            if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Folio_Number == '' || Folio_Number == null || Consultant_ID == '' || Consultant_ID == null)) {

                                if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                    document.getElementById("Claim_Form_Number").focus();
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                }

                                if (Folio_Number == '' || Folio_Number == null) {
                                    document.getElementById("Folio_Number").focus();
                                    document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Folio_Number").style = 'border: 3px';
                                }

                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else if (Guarantor_Name != 'NHIF' && (Consultant_ID == '' || Consultant_ID == null)) {
                                if (Consultant_ID == '' || Consultant_ID == null) {
                                    document.getElementById("Consultant_ID").focus();
                                    document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                } else {
                                    document.getElementById("Consultant_ID").style = 'border: 3px';
                                }
                            } else {
                               var amount_required= document.getElementById("total_txt").value;
                               offline_transaction(amount_required,Registration_ID) 
                            }
                        }
                    </script>

                    <script type="text/javascript">
                        function alertMessage() {
                            alert("No item selected. Please select item first");
                            document.getElementById("Dosage").value = '';
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Quantity").style = 'border: 3px';
                            document.getElementById("Dosage").style = 'border: 3px';
                        }
                    </script>
                    <script>
                        function Pay_Via_Mobile_Function(Payment_Cache_ID) {
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

                            myObjectGetDetails.open('GET', 'ePayment_Patient_Details_Departmental.php?src=patlist&Section=Pharmacy&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
                            myObjectGetDetails.send();
                        }
                    </script>
                    <script type="text/javascript">
                        function Confirm_Create_ePayment_Bill() {
                            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
                            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
                             var Registration_ID = '<?php echo $Registration_ID; ?>';

                            if (window.XMLHttpRequest) {
                                myObjectConfirm = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectConfirm.overrideMimeType('text/xml');
                            }

                            myObjectConfirm.onreadystatechange = function () {
                                data2933 = myObjectConfirm.responseText;
                                if (myObjectConfirm.readyState == 4) {
                                    var feedback = data2933;
                                    if (feedback == 'yes') {
                                        Create_ePayment_Bill();
                                    } else if (feedback == 'not') {
                                        alert("No Item Found!");
                                    } else {
                                        alert("You are not allowed to create transaction whith zero price or zero quantity. Please remove those items to proceed");
                                    }
                                }
                            }; //specify name of function that will handle server response........
                            myObjectConfirm.open('GET', 'Confirm_ePayment_Patient_Details_Departmental.php?src=patlist&Section=MainPharmacy&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID+ '&Registration_ID=' + Registration_ID , true);
                            myObjectConfirm.send();
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
                                            document.location = 'Departmental_Bank_Payment_Transaction.php?src=patlist&Section=MainPharmacy&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type+'&from_phamacy_cutomer_payment&Check_In_Type=Pharmacy';
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

                        function popupwindow(url, title, w, h) {
                            var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                            var wTop = window.screenTop ? window.screenTop : window.screenY;enter

                            var left = wLeft + (window.innerWidth / 2) - (w / 2);
                            var top = wTop + (window.innerHeight / 2) - (h / 2);
                            var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
                            return mypopupWindow;
                        }
                        
                        
                        
                            function create_epayment_mobile_card_bill(Payment_Cache_ID){
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Folio_Number = document.getElementById("Folio_Number").value;
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                var new_sponsor_to_bill = document.getElementById("new_sponsor_to_bill").value;
                                if (new_sponsor_to_bill == '') {
                                    alert("Please select temporary sponsor for this transaction");
                                    document.getElementById("sponsor_for_this_trans").style = 'color:red;font-weight:bold;text-align:right';
                                    exit;
                                }
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                if (Consultant_ID == '') {
                                    alert("Please select consultant to continue");
                                    document.getElementById("Consultant_ID").style = 'border-color:red';
                                    exit;
                                }
                                 var clinic_location_id = document.getElementById("clinic_location_id").value;
                                   if (clinic_location_id == '') {
                                    alert("Please select clinic location to continue");
                                    document.getElementById("clinic_location_id").style = 'border-color:red';
                                    exit;
                                }
                                var working_department = document.getElementById("working_department").value;
                                   if (working_department == '') {
                                    alert("Please select working_department to continue");
                                    document.getElementById("working_department").style = 'border-color:red';
                                    exit;
                                }
                                var Clinic_ID = document.getElementById("Clinic_ID").value;
                                      if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Select clinic to continue")
                                         document.getElementById("Clinic_ID").style = 'border-color:red';
                                        exit;
                                    }
                                if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Folio_Number == '' || Folio_Number == null)) {

                                    if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                        document.getElementById("Claim_Form_Number").focus();
                                        document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                    }

                                    if (Folio_Number == '' || Folio_Number == null) {
                                        document.getElementById("Folio_Number").focus();
                                        document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Folio_Number").style = 'border: 3px';
                                    }

                                    if (Consultant_ID == '' || Consultant_ID == null) {
                                        document.getElementById("Consultant_ID").focus();
                                        document.getElementById("Consultant_ID").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Consultant_ID").style = 'border: 3px';
                                    }
                                } else {
                                    var Sub_Department_Name='<?php echo $Sub_Department_Name; ?>';
                                    var Registration_ID='<?php echo $Registration_ID; ?>';
                                    var Check_In_Type='Pharmacy';
                                    if(confirm("Are You sure you want to go to Mobile/Card Payment")){
                                        $.ajax({
                                            type:'GET',
                                            url:'ajax_create_epayment_mobile_card_bill_from_item_added_pharmacy_out.php',
                                            data:{Sub_Department_Name:Sub_Department_Name,Registration_ID:Registration_ID,Check_In_Type:Check_In_Type,Payment_Cache_ID:Payment_Cache_ID,Section:'Outpatient',Folio_Number:Folio_Number,Claim_Form_Number:Claim_Form_Number,Consultant_ID:Consultant_ID},
                                            success:function(data){
                                                if(data=="success"){
                                                    document.location = "./patient_sent_to_cashier_payment.php?itemfrom=Pharmacy&Registration_ID=<?php echo $Registration_ID ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID ?>&Check_In_Type=Pharmacy&Sub_Department_Name=<?= $Sub_Department_Name ?>";
                                                
                                                }else{
                                                    alert(data+"Process Fail...Please Try Again");
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                    </script>

<!--                    <script>

                        $(document).ready(function () {
                            $('select').select2();
                        })
                    </script>
                    <link rel="stylesheet" href="css/select2.min.css" media="screen">
                    <script src="js/select2.min.js"></script>-->
<?php
include("./includes/footer.php");
?>