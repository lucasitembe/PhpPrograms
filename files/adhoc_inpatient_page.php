<?php
$location = '';

if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    include("./includes/header_general.php");
    $location = 'location=otherdepartment&';
} else {
    include("./includes/header.php");
}
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
    } else {
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
                die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
            } else {
                header("Location: ../index.php?InvalidPrivilege=yes");
            }
        }
    } else {
        if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
            die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
        } else {
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
    } else {
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if(isset($_GET['Admision_ID'])){
    $Admision_ID = $_GET['Admision_ID'];
}else{
    $Admision_ID = 0;
}

if(isset($_GET['Check_In_ID'])){
    $Check_In_ID = $_GET['Check_In_ID'];
}else{
    $Check_In_ID = 0;
}

//echo "<pre>";
//print_r($_SESSION['systeminfo']);
?>

<?php
//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        <a href='Direct_departmental_payment_inpatient.php?<?php echo $location ?>AdhocInpatientList=AdhocInpatientListThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>



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
    $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp, tbl_admission ad where
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        ad.Registration_ID = pr.Registration_ID and
                                        ad.Admission_Status = 'Admitted' and
                                        Discharge_Clearance_Status = 'not cleared' and
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
            $Exemption = $row['Exemption'];



            // echo $Ward."  ".$District."  ".$Ward; exit;
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
        $Exemption = 'no';
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
    $Exemption = 'no';
}
//$show_make_payment="";
//$sql_select_make_payment_configuration="SELECT 	configname FROM tbl_config WHERE configvalue='show' AND configname='showMakePaymentButton'";
//$sql_select_make_payment_configuration_result=mysqli_query($conn,$sql_select_make_payment_configuration) or die(mysqli_error($conn));
//if(mysqli_num_rows($sql_select_make_payment_configuration_result)>0){
//    //show button
//}else{
// $show_make_payment="style='display:none'";   
//}
//get sponsor details
$select_dets = mysqli_query($conn,"select Claim_Number_Status, Folio_Number_Status from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select_dets);
if ($nm > 0) {
    while ($data = mysqli_fetch_array($select_dets)) {
        $Claim_Number_Status = strtolower($data['Claim_Number_Status']);
        $Folio_Number_Status = strtolower($data['Folio_Number_Status']);
    }
} else {
    $Claim_Number_Status = '';
    $Folio_Number_Status = '';
}

//get folio number & claim form number
$select = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
$nums = mysqli_num_rows($select);
if ($nums > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Folio_Number = $row['Folio_Number'];
        $Claim_Form_Number = '';
    }
} else {
    $Folio_Number = 0;
    $Claim_Form_Number = '';
}

if(empty($Claim_Form_Number)){
      $clm= mysqli_query($conn,"SELECT Claim_Form_Number FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' order by Check_In_Details_ID desc limit 1") or die(mysqli_error($conn));
      $row2 = mysqli_fetch_array($clm);
      $Claim_Form_Number = $row2['Claim_Form_Number'];
}
?>



<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
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
    button{
        color:#FFFFFF!important;
        height: 27px!important;
    }
</style>

<!--get sub department name-->
<br/><br/>
<fieldset>  
    <legend align="right"><b>Direct Departmental Payments ~ INPATIENTS LIST: REVENUE CENTER</b></legend>
    <center>
        <table width=100%>
            <tr>
                <td width='10%' style='text-align: right;'>Patient Name</td>
                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                <td style='text-align: right;'>Registration No</td>
                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                <td style='text-align: right;' width="10%">Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='9%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
            </tr> 
            <tr>
                <td style='text-align: right;'>Billing Type</td> 
                <td id="Billing_Type_Area">
                    <select name='Billing_Type'  style='width: 100%;' id='Billing_Type' onchange="Sponsor_Warning(); Refresh_Transaction_Mode(); Refresh_Remember_Mode()">
                        <?php
                        $select_bill_type = mysqli_query($conn,
                                "select Billing_Type
                                from tbl_inpatient_items_list_cache alc
                                where alc.Employee_ID = '$Employee_ID' and
                                Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

                        $no_of_items = mysqli_num_rows($select_bill_type);
                        if ($no_of_items > 0) {
                            while ($data = mysqli_fetch_array($select_bill_type)) {
                                $Billing_Type = $data['Billing_Type'];
                                $B_Type = $data['Billing_Type'];
                            }
                            echo "<option selected='selected'>" . $Billing_Type . "</option>";
                        } else {
                            if (strtolower($Guarantor_Name) == 'cash'   || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
                                echo "<option selected='selected'>Inpatient Cash</option>";
                                $B_Type = 'Inpatient Cash';
                            } else {
                                echo "<option selected='selected'>Inpatient Credit</option> 
                                    <option>Inpatient Cash</option>";
                                    $B_Type = 'Inpatient Credit';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td width='12%' style='text-align: right;'>Card Expire Date</td>
                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                <td style='text-align: right;'>Phone Number</td>
                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                <td style='text-align: right;'>Folio Number</td>
                <td>
                    <input type='text' name='Folio_Number' id='Folio_Number' autocomplete='off' placeholder='Folio Number' value="<?php echo $Folio_Number; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style='text-align: right;'>Consultation Type</td>
                <td>  
                    <select  style='width: 100%;' name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'> 
                        <option selected='selected'></option> 
                        <option>Laboratory</option> 
                        <option>Procedure</option>
                        <option>Radiology</option> 
                        <option>Surgery</option>
                        <option>Admission</option>
                        <option>Optical</option>
                        <option>Others</option>
                        <!-- <option>Dental</option> -->
                        <!-- <option>Dialysis</option> -->
                        <!-- <option>Dressing</option> -->
                        <!-- <option>Ear</option> -->
                        <!-- <option>Matenity</option> -->
                        <!-- <option>Physiotherapy</option> -->
                        <!-- <option>Theater</option> -->
                    </select>
                </td>
                <td style='text-align: right;'>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                <td style='text-align: right;'>Claim Form Number</td>
                <td>
                    <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' value="<?php echo $Claim_Form_Number; ?>" >
                </td>
            </tr>
            <tr> 
                <td style='text-align: right;'>Consultant</td>
                <td>
<!--                    <select name='Consultant_ID' id='Consultant_ID'>
                        <option selected='selected' value="<?php //echo $Employee_ID ?>"><?php //echo $Employee_Name; ?></option>
                    </select>-->
                     <select  style='width: 100%;' name='Consultant_ID' id='Consultant_ID'>
                        <option selected="selected"></option>
                        <?php
                        //get doctors
                        $slct_doc = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active'") or die(mysqli_error($conn));
                        $num_doc = mysqli_num_rows($slct_doc);
                        if ($num_doc > 0) {
                            while ($dat = mysqli_fetch_array($slct_doc)) {
                                ?>
                                <option value="<?php echo $dat['Employee_ID']; ?>"><?php echo strtoupper($dat['Employee_Name']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                <td style='text-align: right;'>Transaction Mode</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td id="Transaction_Area">
                                <select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                            <?php
                                $select_Transaction_type = mysqli_query($conn,"select Fast_Track from tbl_inpatient_items_list_cache alc
                                                                        where alc.Employee_ID = '$Employee_ID' and
                                                                        Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
                                $no_of_items = mysqli_num_rows($select_Transaction_type);
                                if($no_of_items > 0){
                                    while($data = mysqli_fetch_array($select_Transaction_type)){
                                        $Fast_Track = $data['Fast_Track'];
                                    }
                                    if($Fast_Track == '1'){
                                     echo "<option selected='selected'>Fast Track Transaction</option>";
                                    }else{
                                     echo "<option selected='selected'>Normal Transaction</option>";                            
                                    }
                                }else{
                            ?>
                                    <option selected="selected">Normal Transaction</option>
                                    <option <?php if(isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction' && $B_Type == 'Inpatient Cash'){ echo "selected='selected'"; } ?>>Fast Track Transaction</option>
                            <?php
                                }
                            ?>
                            </select>
                            </td>
                            <td>
                                <input type="checkbox" id="Remember_Mode" name="Remember_Mode" onclick="Remember_Mode_Function()" <?php if(isset($_SESSION['Transaction_Mode']) && $B_Type == 'Inpatient Cash' && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){ echo "checked='checked'"; } ?>>
                                <label for="Remember_Mode">Remember</label>
                            </td>
                        </tr>
                    </table>
                </td>
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
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td style="text-align:right">
                        <b style="color:red">Select Department</b>
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='finance_department_id' id='finance_department_id'>
                            <option selected='selected'></option>
                            <?php
                             $select_department = "select * from tbl_finance_department where enabled_disabled = 'enabled'";
                            $result = mysqli_query($conn,$select_department);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['finance_department_id']; ?>"><?php echo $row['finance_department_name']."--".$row['finance_department_code']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
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
                </tr>
        </table>
    </center>
</fieldset>

<fieldset>
    <table width=100% id="Process_Buttons_Area">
        <?php
           $select_Transaction_Items = mysqli_query($conn,
				 "select Billing_Type
				    from tbl_inpatient_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
			     
        $no_of_items = mysqli_num_rows($select_Transaction_Items);
?>
    <td style='text-align: right;'>
<?php
	    if($no_of_items > 0){
            while($data = mysqli_fetch_array($select_Transaction_Items)){
                $Billing_Type = $data['Billing_Type'];
            }
            if(strtolower($Billing_Type) == 'inpatient cash'){
                if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
                    if(isset($_SESSION['systeminfo']['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments']) && strtolower($_SESSION['systeminfo']['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments']) == 'yes'){
            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                 
                        ?>
                        <button class="art-button-green" type="button" onclick="Create_Patient_Bill_Verify();">CREATE PATIENT BILL</button>
            <?php
            }   }
            ?>
            <?php
                 if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                     ?>
                         <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                         <?php
//                     echo '<button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>';
                 }

             ?>    
                <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button>        
                <?php 
                 if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                 
                ?>        
                    <button class="art-button-green"  type="button" onclick="Make_Payment();">MAKE PAYMENT</button>
<?php
                 } }else{
?>                  <?php
                 if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                     
//                     echo '<button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>';
                 }
 if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                
                  ?>  
                    <button class="art-button-green" type="button" onclick="Make_Payment();">MAKE PAYMENT</button>
 <?php } ?>
                     <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button> 
<?php
                }
            }else{
                if($Exemption == 'yes' && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes'){
?>
                    <button class="art-button-green" type="button" onclick="Send_To_Approval_Confirm();">SEND TO APPROVAL</button>
<?php
                }else{
?>
                    <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATIONss</button>
<?php
                }
            }
	    }
?>
<button class="art-button-green" type="button" onclick="Validate_Type_Of_Check_In();">SEND TO CASHIER</button>
	<button class="art-button-green" type="button" onclick="Validate_Type_Of_Check_In();">ADD ITEM</button>
           
        </td>
    </table>
    <img id="loader" style="float:left;display:none" src="images/22.gif"/>
</fieldset>

<fieldset>   
    <center>
        <table width=100%>
            <tr>
                <td>
                    <form id="saveDiscount">
                        <!-- get Sub_Department_ID from the URL -->
<?php if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
} ?>
                        <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll; height: 190px;'>
                            <center>
                                <table width =100% border=0>
                                    <tr id="thead">
                                        <td style="text-align: left;" width=5%><b>Sn</b></td>
                                        <td><b>Item Name</b></td>
                                        <td width="12%"><b>Location</b></td>
                                        <td style="text-align: left;" width=17%><b>Comment</b></td>
                                        <td style="text-align: right;" width=8%><b>Price</b></td>
                                        <td style="text-align: right;" width=8%><b>Discount</b></td>
                                        <td style="text-align: right;" width=8%><b>Quantity</b></td>
                                        <td style="text-align: right;" width=8%><b>Sub Total</b></td>
                                        <td style="text-align: center;" width=6%><b>Action</b></td></tr>
                                    <?php
                                    $temp = 0;
                                    $total = 0;
                                    $select_Transaction_Items = mysqli_query($conn,
                                            "select Item_Cache_ID, Product_Name, Price, Quantity, Discount, Registration_ID,Comment,Sub_Department_ID
				     from tbl_items t, tbl_inpatient_items_list_cache alc
					 where alc.Item_ID = t.Item_ID and
					     alc.Employee_ID = '$Employee_ID' and
						     Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

                                    $no_of_items = mysqli_num_rows($select_Transaction_Items);
                                    while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                        $Temp_Sub_Department_ID = $row['Sub_Department_ID'];
                                        //get sub department name
                                        $select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
                                        $my_num = mysqli_num_rows($select_sub_department);
                                        if ($my_num > 0) {
                                            while ($rw = mysqli_fetch_array($select_sub_department)) {
                                                $Sub_Department_Name = $rw['Sub_Department_Name'];
                                            }
                                        } else {
                                            $Sub_Department_Name = '';
                                        }
                                        echo "<tr><td>" . ++$temp . "</td>";
                                        echo "<td>" . $row['Product_Name'] . "</td>";
                                        echo "<td>" . $Sub_Department_Name . "</td>";
                                        echo "<td>" . $row['Comment'] . "</td>";
                                        echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "</td>";
                                        echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Discount'], 2) : number_format($row['Discount'])) . "</td>";
                                        echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                        echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity'])) . "</td>";
                                        ?>
                                        <td style="text-align: center;"> 
                                            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                        </td>
    <?php
    $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
}echo "</tr></table>";
?>
                                    </fieldset>
                                    </td>
                                    </tr>
                                    <tr>
                                         <input type="text" hidden="hidden" id="grand_total_txt" value="<?php echo $total; ?>">
                                        <td style='text-align: right; width: 70%;' id='Total_Area'>
                                            <h4>Total : <?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code']; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </fieldset>

                        <div id="Items_Div_Area" style="width:50%;" >

                        </div>


<!-- ePayment pop up windows -->
<div id="ePayment_Window" style="width:50%;" >
    <span id='ePayment_Area'>
        
    </span>
</div>


<div id="Non_Supported_Item">
    <center>
        Selected Item is not supported by <?php echo $Guarantor_Name; ?><br/>
        Please change bill type.
    </center>
</div>

<div id="Item_Existing_Error">
    Duplicate!! Selected Item already added.
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

<div id="Missing_Items">
    No Items found!!
</div>

<div id="Change_Billing_Type_Alert">
    You are about to create fast track transaction. Billing type will change to <b>Inpatient Cash</b><br/><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" value="CONTINUE" onclick="Change_Billing_Type()" class="art-button-green">
                <input type="button" value="DISCARD" onclick="Close_Change_Billing_Type_Alert()" class="art-button-green">
            </td>
        </tr>
    </table>
</div>
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
        var registration_id = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        
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
                var Consultant_ID = document.getElementById("Consultant_ID").value; 
                  document.location = 'Inpatient_Departmental_Make_Payment.php?Registration_ID=' + registration_id + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&manual_offline=manual';
           
                //document.location = 'Inpatient_Departmental_Make_Payment.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&manual_offline=manual';
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
               
        var uri = '../epay/directdepartmentalpaymentinpatientsOffline.php';
        
                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                var Folio_Number = document.getElementById("Folio_Number").value;
                var Consultant_ID = document.getElementById("Consultant_ID").value;
                var Consultant_ID = document.getElementById("Consultant_ID").value; 
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
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
    function Remember_Mode_Function(){
        var Controler = 'not checked';
        if(document.getElementById("Remember_Mode").checked){
            Controler = "checked";
        }else{
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

        myObjectRemember.open('GET', 'Remember_Transaction_Mode.php?&Transaction_Mode='+Transaction_Mode+'&Controler='+Controler, true);
        myObjectRemember.send();
    }
</script>

<script type="text/javascript">
    function Close_Change_Billing_Type_Alert(){
        $("#Change_Billing_Type_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Change_Billing_Type(){
        document.getElementById("Billing_Type").value = 'Inpatient Cash';
        document.getElementById("Transaction_Mode").value = 'Fast Track Transaction';
        $("#Change_Billing_Type_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Refresh_Remember_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Billing_Type == 'Inpatient Credit'){
            document.getElementById("Remember_Mode").checked = false;
            document.getElementById("Transaction_Mode").value = 'Normal Transaction';
        }else{
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
                    if(feedback == 'yes'){
                        document.getElementById("Remember_Mode").checked = true;
                        Refresh_Transaction_Mode();
                    }else{
                        document.getElementById("Remember_Mode").checked = false;
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectRem.open('GET', 'Direct_Departmental_Refresh_Remember_Mode.php?Session=Inpatient', true);
            myObjectRem.send();
        }
    }
</script>

<script type="text/javascript">
    function Refresh_Transaction_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Billing_Type == 'Inpatient Cash'){
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

            myObjectRefreshMode.open('GET', 'Direct_Departmental_Refresh_Transaction_Mode.php', true);
            myObjectRefreshMode.send();
        }
    }
</script>

<script type="text/javascript">
    function Validate_Transaction_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Transaction_Mode = document.getElementById("Transaction_Mode").value;
        var Inpatient_Prepaid = "<?php echo strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']); ?>";
        if(Transaction_Mode == 'Fast Track Transaction' && Billing_Type == 'Inpatient Credit' && Inpatient_Prepaid == 'yes'){
            document.getElementById("Transaction_Mode").value = 'Normal Transaction';
            $("#Change_Billing_Type_Alert").dialog("open");
        }
        Remember_Mode_Function();
    }
</script>

<script type="text/javascript">
    function Create_Patient_Bill_Verify(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            MyObjectBill = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            MyObjectBill = new ActiveXObject('Micrsoft.XMLHTTP');
            MyObjectBill.overrideMimeType('text/xml');
        }
        MyObjectBill.onreadystatechange = function () {
            dataBill = MyObjectBill.responseText;
            if (MyObjectBill.readyState == 4) {
                var feedback = dataBill;
                if(feedback == 'yes'){
                    Create_Patient_Bill(Registration_ID);
                }else if(feedback == 'no'){
                    $("#Missing_Items").dialog("open");
                }else{
                    alert("Process fail! Please try again");
                }
            }
        }; //specify name of function that will handle server response........

        MyObjectBill.open('GET', 'Create_Patient_Bill_Verify.php?Registration_ID='+Registration_ID, true);
        MyObjectBill.send();
    }
</script>

<script type="text/javascript">
    function Create_Patient_Bill(Registration_ID){
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var sms = confirm("Are you sure you want to create patient bill?");
        if(sms == true){
            document.location = 'Create_Patient_Bill.php?Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID;
        }
    }
</script>

<script type="text/javascript">
    function Send_To_Approval_Confirm(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            MyObjectAppr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            MyObjectAppr = new ActiveXObject('Micrsoft.XMLHTTP');
            MyObjectAppr.overrideMimeType('text/xml');
        }
        MyObjectAppr.onreadystatechange = function () {
            dataAppr = MyObjectAppr.responseText;
            if (MyObjectAppr.readyState == 4) {
                var feedback = dataAppr;
                if(feedback == 'yes'){
                    Send_To_Approval(Registration_ID);
                }else if(feedback == 'no'){
                    $("#Missing_Items").dialog("open");
                }else{
                    alert("Process fail! Please try again");
                }
            }
        }; //specify name of function that will handle server response........

        MyObjectAppr.open('GET', 'Send_To_Approval_Confirm.php?Registration_ID='+Registration_ID, true);
        MyObjectAppr.send();
    }
</script>

<script type="text/javascript">
    function Send_To_Approval(Registration_ID){
        var sms = confirm("Are you sure you want to send this bill to approval?")
        if(sms == true){
            if (window.XMLHttpRequest) {
                MyObjectSend = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                MyObjectSend = new ActiveXObject('Micrsoft.XMLHTTP');
                MyObjectSend.overrideMimeType('text/xml');
            }
            MyObjectSend.onreadystatechange = function () {
                dataSent = MyObjectSend.responseText;
                if (MyObjectSend.readyState == 4) {
                    var feedback = dataSent;
                    if(feedback == 'yes'){
                        alert("Bill sent Successfully");
                        document.location = 'departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage';
                    }else{
                        alert("Process fail!!! Please try again");
                    }
                }
            }; //specify name of function that will handle server response........

            MyObjectSend.open('GET', 'Inpatient_Send_To_Approval.php?Registration_ID='+Registration_ID, true);
            MyObjectSend.send();
        }
    }
</script>

                        <script type='text/javascript'>
                            function Make_Payment() {
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
                                   // var r = confirm("Are you sure you want to make payment?\nClick OK to proceed or Cancel to stop process");
                                   // if (r == true) {
                                      //  document.location = 'Inpatient_Departmental_Make_Payment.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID;
                                           var  amount_required = document.getElementById('grand_total_txt').value;
                                      //alert(amount_required)
                                      offline_transaction(amount_required,Registration_ID);
                                 //}
                                }
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
                                            update_total();
                                            update_Billing_Type();
                                            update_process_buttons(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    My_Object_Remove_Item.open('GET', 'Inpatient_Remove_Item_From_List.php?Item_Cache_ID=' + Item_Cache_ID + '&Registration_ID=' + Registration_ID, true);
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
                                    data600 = My_Object_Update_Total.responseText;
                                    if (My_Object_Update_Total.readyState == 4) {
                                        document.getElementById('Total_Area').innerHTML = data600;
                                        //update_total();
                                        //update_Billing_Type(Registration_ID);
                                        //Update_Claim_Form_Number();
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Update_Total.open('GET', 'Inpatient_Update_Total.php?Registration_ID=' + Registration_ID, true);
                                My_Object_Update_Total.send();
                            }
                        </script>

                        <script>
                            function update_Billing_Type() {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                if (window.XMLHttpRequest) {
                                    My_Object_Update_Billing_Type = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    My_Object_Update_Billing_Type = new ActiveXObject('Micrsoft.XMLHTTP');
                                    My_Object_Update_Billing_Type.overrideMimeType('text/xml');
                                }

                                My_Object_Update_Billing_Type.onreadystatechange = function () {
                                    data6001 = My_Object_Update_Billing_Type.responseText;
                                    if (My_Object_Update_Billing_Type.readyState == 4) {
                                        document.getElementById('Billing_Type_Area').innerHTML = data6001;
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Update_Billing_Type.open('GET', 'Inpatient_update_Billing_Type.php?Registration_ID=' + Registration_ID, true);
                                My_Object_Update_Billing_Type.send();
                            }
                        </script>

                        <script>
                            function Get_Item_Name(Item_Name, Item_ID) {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                document.getElementById('Quantity').value = '';
                                document.getElementById('Comment').value = '';

                                var Temp = '';
                                if (window.XMLHttpRequest) {
                                    myObjectGetItemName = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectGetItemName.overrideMimeType('text/xml');
                                }

                                myObjectGetItemName.onreadystatechange = function () {
                                    dataGet = myObjectGetItemName.responseText;
                                    if (myObjectGetItemName.readyState == 4) {
                                        var feedback_value = dataGet;
                                        if(feedback_value == 'yes'){
                                            document.getElementById("Item_Name").value = Item_Name;
                                            document.getElementById("Item_ID").value = Item_ID;
                                            document.getElementById("Quantity").value = 1;
                                        }else{
                                            document.getElementById("Price").value = '0';
                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Item_ID").value = '';
                                            document.getElementById("Quantity").value = '';
                                            $("#Item_Existing_Error").dialog("open");
                                        }
                                    }
                                }; //specify name of function that will handle server response........

                                myObjectGetItemName.open('GET', 'Inpatient_Get_Item_Name_Verify.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID, true);
                                myObjectGetItemName.send();
                            }
                        </script>



                        <script>
                            function openItemDialog() {
                                $("#Items_Div_Area").dialog("open");
                            }
                        </script>

<script type="text/javascript">
    function Sponsor_Warning(){
        var Guarantor_Name = '<?php echo strtolower($Guarantor_Name); ?>';
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Billing_Type == 'Inpatient Cash' && Guarantor_Name != 'cash'){
            $("#Sponsor_Warning").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Response(feedback){
        if(feedback == 'no'){
            document.getElementById("Billing_Type").value = 'Inpatient Credit';
            document.getElementById("Transaction_Mode").value = 'Normal Transaction';
            document.getElementById("Remember_Mode").checked = false;
        }
        $("#Sponsor_Warning").dialog("close");
    }
</script>
                        <script>
                            function Validate_Type_Of_Check_In() {
                                var finance_department_id = document.getElementById("finance_department_id").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                if (Type_Of_Check_In == '' || Type_Of_Check_In == null) {
                                    document.getElementById("Type_Of_Check_In").style = 'border: 3px solid red';
                                    document.getElementById("Type_Of_Check_In").focus();
                                } else {
                                    document.getElementById("Type_Of_Check_In").style = 'border: 3px white';
                                     var Clinic_ID = document.getElementById("Clinic_ID").value;
                                      if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Select clinic first")
                                        exit;
                                    }
                                     var clinic_location_id = document.getElementById("clinic_location_id").value;
                                       if(clinic_location_id==''|| clinic_location_id==null){
                                        alert("Select clinic location first")
                                        exit;
                                    }
                                    if(finance_department_id==''|| finance_department_id==null){
                                        alert("Select Department first")
                                        exit;
                                    }
                                    Refresh_Items_Div();
                                }
                            }
                        </script>

                        <script>
                            function Refresh_Items_Div() {
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                var Transaction_Mode = document.getElementById("Transaction_Mode").value;

                                if (window.XMLHttpRequest) {
                                    myObjectRefreshDiv = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectRefreshDiv = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectRefreshDiv.overrideMimeType('text/xml');
                                }
                                //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
                                myObjectRefreshDiv.onreadystatechange = function () {
                                    data999 = myObjectRefreshDiv.responseText;
                                    if (myObjectRefreshDiv.readyState == 4) {
                                        document.getElementById('Items_Div_Area').innerHTML = data999;
                                        clearContent();
                                        openItemDialog();
                                    }
                                }; //specify name of function that will handle server response........

                                myObjectRefreshDiv.open('GET', 'Refresh_Inpatient_Payments_Div.php?Type_Of_Check_In='+Type_Of_Check_In+'&Sponsor_ID='+Sponsor_ID+'&Transaction_Mode='+Transaction_Mode, true);
                                myObjectRefreshDiv.send();
                            }
                        </script>

                        <script>
                            function clearContent() {
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Item_ID").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Comment").value = '';
                                document.getElementById("Search_Value").value = '';
                            }
                        </script>


                        <script>
                            function Get_Item_Price(Item_ID, Sponsor_ID) {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Transaction_Mode = document.getElementById("Transaction_Mode").value;
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

                                myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type+'&Transaction_Mode='+Transaction_Mode, true);
                                myObject.send();
                            }
                        </script>




                        <script>
                            function update_process_buttons(Registration_ID) {
                                var Exemption = '<?php echo $Exemption; ?>';
                                
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

                                my_Object_Update_Process.open('GET', 'Inpatient_Update_Process_Button.php?Registration_ID='+Registration_ID+'&Exemption='+Exemption, true);
                                my_Object_Update_Process.send();
                            }
                        </script>

                         <link rel="stylesheet" href="css/select2.min.css" media="screen">

                        <script src="js/jquery-1.8.0.min.js"></script>
                        <script src="js/select2.min.js"></script>
                        <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
                        <script src="script.js"></script>
                        <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
                        <script src="script.responsive.js"></script>

                        <script>
                            $(document).ready(function () {
                                $("#Items_Div_Area").dialog({autoOpen: false, width: 950, height: 450, title: 'ADD NEW ITEM', modal: true});
                                $("#Non_Supported_Item").dialog({autoOpen: false, width: '40%', height: 150, title: 'NON SUPPORTED ITEM', modal: true});
                                $("#Sponsor_Warning").dialog({autoOpen: false, width: '40%', height: 180, title: 'BILLING TYPE WARNING!', modal: true});
                                $("#Item_Existing_Error").dialog({autoOpen: false, width: '40%', height: 120, title: 'DUPLICATE SELECTION!', modal: true});
                                $("#Missing_Items").dialog({autoOpen: false, width: '40%', height: 120, title: 'eHMS 2.0', modal: true});
                                $("#Change_Billing_Type_Alert").dialog({autoOpen: false, width: '60%', height: 150, title: 'TRANSACTION WARNING!', modal: true});
                                $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
                            });
                        </script>





                        <script>
                            
                            function Get_Selected_Item() {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                var Quantity = document.getElementById("Quantity").value;
                                var Discount = document.getElementById("Discount").value;
                                var Registration_ID = <?php echo $Registration_ID; ?>;
                                var Comment = document.getElementById("Comment").value;
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var Price =document.getElementById("Price").value ;
                                var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                                var Clinic_ID = document.getElementById("Clinic_ID").value;
                                 var clinic_location_id = document.getElementById("clinic_location_id").value;
                                var finance_department_id = document.getElementById("finance_department_id").value;
                                      
                                if(Discount == null || Discount == ''){
                                    Discount = 0;
                                }

                                if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null && Type_Of_Check_In != null && Type_Of_Check_In != '') {
                                    
                                    if (Sub_Department_ID == '') {
                                        alert("Select location");
                                        exit;
                                    }

                                    if (parseFloat(Price) > 0) {
                                       
                                    }else{
                                         alert('This Item has not been set its price.Please tell the person incharge to set its price before continuing.');
                                        exit;
                                    }
                                    
                                    if (window.XMLHttpRequest) {
                                        myObject2 = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObject2.overrideMimeType('text/xml');
                                    }
                                    myObject2.onreadystatechange = function () {
                                        data = myObject2.responseText;

                                        if (myObject2.readyState == 4) {
                                            document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Item_ID").value = '';
                                            document.getElementById("Quantity").value = '';
                                            document.getElementById("Price").value = '';
                                            document.getElementById("Comment").value = '';
                                            document.getElementById("Search_Value").focus();
                                            alert("Item Added Successfully");
                                            update_billing_type(Registration_ID);
                                            update_total();
                                            update_process_buttons(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Inpatient_Add_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Quantity='+Quantity+'&Consultant_ID='+Consultant_ID+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name+'&Sponsor_ID='+Sponsor_ID+'&Claim_Form_Number='+Claim_Form_Number+'&Billing_Type='+Billing_Type+'&Comment='+Comment+'&Sub_Department_ID='+Sub_Department_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Discount='+Discount+'&Transaction_Mode='+Transaction_Mode+"&Clinic_ID="+Clinic_ID+"&finance_department_id="+finance_department_id+"&clinic_location_id="+clinic_location_id, true);
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
                            function alertMessage() {
                                alert("Select Item First");
                                document.getElementById("Quantity").value = '';
                            }
                        </script>

                        <script>
                            function getItemsList(Item_Category_ID) {
                                document.getElementById("Search_Value").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Item_ID").value = '';
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;

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
                                myObject.open('GET', 'Get_List_Of_Inpatient_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Sponsor_ID='+Sponsor_ID, true);
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
                                myObjectUpdateBilling.open('GET', 'Inpatient_Update_Billing_Type.php?Registration_ID=' + Registration_ID, true);
                                myObjectUpdateBilling.send();
                            }
                        </script>




                        <script>
                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Item_ID").value = '';
                                document.getElementById("Comment").value = '';
                                document.getElementById("Quantity").value = '';
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
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
                                myObject.open('GET', 'Get_List_Of_Inpatient_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID + '&Type_Of_Check_In=' + Type_Of_Check_In, true);
                                myObject.send();
                            }
                        </script>











                        <!-- nitaanzia hapaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->



                        <script type="text/javascript">
                            function Save_Information_Confirm() {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                if (window.XMLHttpRequest) {
                                    myObjectConfirmSave = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectConfirmSave = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectConfirmSave.overrideMimeType('text/xml');
                                }

                                myObjectConfirmSave.onreadystatechange = function () {
                                    data2 = myObjectConfirmSave.responseText;
                                    if (myObjectConfirmSave.readyState == 4) {
                                        var feedback = data2;
                                        if (feedback == 'yes') {
                                            Save_Information();
                                        } else {
                                            alert("Sorry. Process Fail! Some items detected with zore price.\nYou are not allowed to process items with zero Price");
                                        }
                                    }
                                }; //specify name of function that will handle server response........
                                myObjectConfirmSave.open('GET', 'Inpatient_Confirm_Items_Price.php?Registration_ID=' + Registration_ID, true);
                                myObjectConfirmSave.send();
                            }
                        </script>


                        <!-- hapaaaaaaaaaaaaaaaaaa   -->













                        <script type='text/javascript'>
                            function Save_Information() {
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Folio_Number = document.getElementById("Folio_Number").value;
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';

                                var Claim_Number_Status = '<?php echo $Claim_Number_Status; ?>';
                                var Folio_Number_Status = '<?php echo $Folio_Number_Status; ?>';

                                if ((Folio_Number_Status == 'mandatory' && (Folio_Number == '' || Folio_Number == null)) || (Claim_Number_Status == 'mandatory' && (Claim_Form_Number == '' || Claim_Form_Number == null))) {
                                    if (Claim_Number_Status == 'mandatory' && (Claim_Form_Number == '' || Claim_Form_Number == null)) {
                                        document.getElementById("Claim_Form_Number").focus();
                                        document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Claim_Form_Number").style = 'border: 3px';
                                    }

                                    if (Folio_Number_Status == 'mandatory' && (Folio_Number == '' || Folio_Number == null)) {
                                        document.getElementById("Folio_Number").focus();
                                        document.getElementById("Folio_Number").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Folio_Number").style = 'border: 3px';
                                    }
                                } else {
                                    var r = confirm("Are you sure you want to save information?\nClick OK to proceed or Cancel to stop process");
                                    if (r == true) {
                                        document.location = 'Inpatient_Save_Information.php?<?php echo $location ?>Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID;
                                    }
                                }
                            }   
                        </script> 
                        
                         <script>
                            function Pay_Via_Mobile_Function() {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Employee_ID = '<?php echo $Employee_ID; ?>';

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

                                myObjectGetDetails.open('GET', 'Departmental_ePayment_Patient_Details.php?src=drinp&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID, true);
                                myObjectGetDetails.send();
                            }
                        </script>

                        <script type="text/javascript">
                            function Verify_ePayment_Bill() {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Employee_ID = '<?php echo $Employee_ID; ?>';

                                if (window.XMLHttpRequest) {
                                    myObjectVerify = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectVerify.overrideMimeType('text/xml');
                                }
                                myObjectVerify.onreadystatechange = function () {
                                    data2912 = myObjectVerify.responseText;
                                    if (myObjectVerify.readyState == 4) {
                                        var feedback = data2912;
                                        if (feedback == 'yes') {
                                            Create_ePayment_Bill();
                                        } else {
                                            alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                                        }
                                    }
                                }; //specify name of function that will handle server response........

                                myObjectVerify.open('GET', 'Departmental_Verify_ePayment_Bill.php?src=drinp&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
                                myObjectVerify.send();
                            }
                        </script>


                        <script type="text/javascript">
                            function Create_ePayment_Bill() {
                                var Payment_Mode = document.getElementById("Payment_Mode").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Amount = document.getElementById("Amount_Required").value;
                                if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0') {
                                    alert("Process Fail! You can not prepare a bill with zero amount");
                                } else {
                                    if (Payment_Mode != null && Payment_Mode != '') {
                                        if (Payment_Mode == 'Bank_Payment') {
                                            var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                                            if (Confirm_Message == true) {
                                                document.location = 'Departmental_Prepare_Bank_Payment_Transaction.php?src=drinp&Section=departmental&Registration_ID=' + Registration_ID;
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
                         <script>
                            $(document).ready(function () {
                                $("select").select2();
                            });
                            
                            function create_epayment_mobile_card_bill(){
                                
                                
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                             
                                if (Consultant_ID == '') {
                                    alert("Please select consultant to continue");
                                    
                                }
                                 var clinic_location_id = document.getElementById("clinic_location_id").value;
                                   if (clinic_location_id == '') {
                                    alert("Please select clinic location to continue");
                                    
                                }
                                var finance_department_id = document.getElementById("finance_department_id").value;
                                if (finance_department_id == '') {
                                    alert("Please select working_department to continue");
                                    
                                }
                                var Clinic_ID = document.getElementById("Clinic_ID").value;
                                      if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Please Select clinic to continue")
                                         
                                    }
                                
                                    var Registration_ID='<?php echo $Registration_ID; ?>';
                                    var Sponsor_ID='<?php echo $Sponsor_ID; ?>';
                                    Check_In_ID = '<?= $Check_In_ID ?>';
                                   
                                    if(confirm("Are You sure you want to go to Mobile/Card Payment")){
                                        $.ajax({
                                            type:'GET',
                                            url:'ajax_create_epayment_mobile_card_bill_departmental_payment_inpatient.php',
                                            data:{Registration_ID:Registration_ID,Sponsor_ID:Sponsor_ID,finance_department_id:finance_department_id,Check_In_ID:Check_In_ID},
                                            success:function(data){
                                                if(data=="fail"){
                                                    alert(data+"Process Fail...Please Try Again");
                                                }else{
                                                   var Payment_Cache_ID=data;
                                                    document.location = "./patient_sent_to_cashier_payment.php?Registration_ID=<?php echo $Registration_ID ?>&Payment_Cache_ID="+Payment_Cache_ID;
                                                }
                                            }
                                        });
                                    }
                            }
                        </script>

                        <?php
                        if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
                            //include("./includes/footer.php");
                        } else {
                            include("./includes/footer.php");
                        }
                        ?>