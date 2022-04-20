<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
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

            $bill_Clinic_ID= $_SESSION['bill_Clinic_ID'];
            $bill_clinic_location_id=$_SESSION['bill_clinic_location_id'];
            $bill_working_department=$_SESSION['bill_working_department'];

//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//  $Sub_Department_ID = $_SESSION['Sub_Department_ID'];
 
//  echo $Sub_Department_ID;

 ///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

?>
<style>
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
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
        $Payment_Method="";
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
?>
<!-- link menu -->





        <a href='motuary_admitted_list.php?MotuaryAdmittedList=MotuaryAdmittedListThisPage' class='art-button-green'>
            BACK
        </a>


<!-- new date function (Contain years, Months and days)--> 

<!-- end of the function -->




<!--Approved message-->
<script type='text/javascript'>
    function approved_Message() {
        alert('    Successfully Approved! Please notify PATIENT to go to CASHIER for payment and then return to PHARMACY to pick up their service   ');
    }

    function approved_Message2() {
        alert('    The Bill is already APPROVED! if not yet, please notify PATIENT to go to CASHIER for payment then return to PHARMACY to pick up service   ');
    }

    function Payment_approved_Message() {
        alert('    Patient\'s service is not yet paid. Please advice PATIENT to go to CASHIER for payment then return to PHARMACY to pick up service   ');
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






<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<?php
if (strtolower($Payment_Method) != 'cash') {
    //get the last folio number if available
    $get_folio = mysqli_query($conn,"SELECT Folio_Number, Claim_Form_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($get_folio);
    if ($numrow > 0) {
        while ($data = mysqli_fetch_array($get_folio)) {
            $Folio_Number = $data['Folio_Number'];
            $Claim_Form_Number = $data['Claim_Form_Number'];
        }
    } else {
        $Folio_Number = 0;
        $Claim_Form_Number = '';
    }
} else {
    $Folio_Number = '';
    $Claim_Form_Number = '';
}
?>






<!--get sub department name-->
<?php

// $select = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
// $num = mysqli_num_rows($select);
// if ($num > 0) {
//     while ($data = mysqli_fetch_array($select)) {
//         $Sub_Department_Name = $data['Sub_Department_Name'];
//     }
// } else {
//     $Sub_Department_Name = '';
// }
?>


<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }	
</style>
<br/>

<fieldset>  
    <legend style='background-color:#006400;color:white;padding:5px;' align=right><b>MORTUARY ADDITIONAL SERVICES</b></legend>
    <center>
        <table width=100%>
            <tr>
                <td width='10%' style='text-align: right;'>Patient Name</td>
                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                <td style='text-align: right;' width="12%">Claim Form Number</td>
                <td width="15%">
                    <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' value="<?php echo $Claim_Form_Number; ?>" readonly="readonly">
                </td> 
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
            </tr> 
            <tr>
                <td style='text-align: right;'><b style="color:green"><span style="color:red">*</span>Billing Type</b></td> 
                <td>
                    <select name='Billing_Type' id='Billing_Type' onchange="Sponsor_Warning()" style="width:100%">
                        <?php
                        $select_bill_type = mysqli_query($conn,
                                "SELECT Billing_Type
								  from tbl_pharmacy_inpatient_items_list_cache alc
								  where alc.Employee_ID = '$Employee_ID' and
								  Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
                        $select_admision_ID = mysqli_query($conn, "SELECT Admision_ID from tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status IN('Admitted','pending') AND (Credit_Bill_Status = 'pending' or Cash_Bill_Status='pending') ORDER BY Admision_ID DESC LIMIT 1");
                        $no_of_items = mysqli_num_rows($select_bill_type);
                        $admission_num = mysqli_num_rows($select_admision_ID);
                        if ($no_of_items > 0) {
                            while ($data = mysqli_fetch_array($select_bill_type)) {
                                $Billing_Type = $data['Billing_Type'];
                                $B_Type = $data['Billing_Type'];
                            }
                            echo "<option selected='selected'>" . $Billing_Type . "</option>";
                        } elseif($admission_num > 0) {
                            if (strtolower($Payment_Method) == 'cash') {
                                echo "<option selected='selected'>Inpatient Cash</option>";
                                $B_Type = 'Inpatient Cash';
                            } else {
                                echo "<option selected='selected'>Inpatient Credit</option> 
						      <option>Inpatient Cash</option>";
                                $B_Type = 'Inpatient Credit';
                            }
                        }else {
                            if (strtolower($Payment_Method) == 'cash') {
                                echo "<option selected='selected'>Outpatient Cash</option>";
                                $B_Type = 'Outpatient Cash';
                            } else {
                                echo "<option selected='selected'>Outpatient Credit</option> 
						      <option>Outpatient Cash</option>";
                                $B_Type = 'Outpatient Credit';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                <td style='text-align: right;'>Folio Number</td>
                <td>
                    <input type='text' name='Folio_Number' id='Folio_Number' autocomplete='off' placeholder='Folio Number' value="<?php echo $Folio_Number; ?>" readonly="readonly">
                </td>

            </tr>
            <tr>
                <td style='text-align: right;'>Type Of Check In</td>
                <td>  
                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()' style="width:100%"> 
                        <option selected='selected'>Mortuary</option> 
                    </select>
                </td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Registration_Date_And_Time' id='Registration_Date_And_Time' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
            </tr>
            <tr> 
                <td style='text-align: right;'>Patient Direction</td>
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
                <td style='text-align: right;'>Transaction Mode</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td id="Transaction_Area">
                                <select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                                    <?php
                                    $select_Transaction_type = mysqli_query($conn,"select Fast_Track from tbl_pharmacy_inpatient_items_list_cache alc
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
                                        <option <?php
                                        if (isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction' && $B_Type == 'Inpatient Cash') {
                                            echo "selected='selected'";
                                        }
                                        ?>>Fast Track Transaction</option>
                                            <?php
                                        }
                                        ?>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" id="Remember_Mode" name="Remember_Mode" onclick="Remember_Mode_Function()" <?php
                                if (isset($_SESSION['Transaction_Mode']) && $B_Type == 'Inpatient Cash' && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                                    echo "checked='checked'";
                                }
                                ?>>
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
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>" <?php if($bill_Clinic_ID==$row['Clinic_ID']){ echo "selected='selected'"; } ?>><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                  
                    <td style="text-align:right;color:green" id="sponsor_for_this_trans">
                        <b><span style="color:red">*</span>Select Temporary Sponsor for this transaction</b>
                    </td>
                    <td>
                        <select id="new_sponsor_to_bill">
                            <option value="<?= $Sponsor_ID ?>"><?= $Guarantor_Name ?></option>
                            <?php 
                                if($Billing_Type_filter=="Inpatient Credit"){
                                    $filter_sponsor="credit";
                                }else{
                                    $filter_sponsor="WHERE payment_method='cash'";
                                }
                                $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor $filter_sponsor") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                   while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                      $Sponsor_ID_ch=$sponsor_rows['Sponsor_ID'];
                                      $Guarantor_Name_ch=$sponsor_rows['Guarantor_Name'];
                                      echo "<option value='$Sponsor_ID_ch'>$Guarantor_Name_ch</option>";
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
                            <option></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic_location WHERE enabled_disabled='enabled'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['clinic_location_id']; ?>" <?php if($bill_clinic_location_id==$row['clinic_location_id']){ echo "selected='selected'"; } ?>><?php echo $row['clinic_location_name']; ?></option>
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
                            <option value=""><?= $bill_working_department ?></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       $selected="";
                                       if($bill_working_department==$finance_dep_rows['finance_department_id']){ 
                                           $selected="selected='selected'";
                                       } 
                                       echo "<option value='$finance_department_id' $selected>$finance_department_name-->$finance_department_code</option>";
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
				    from tbl_pharmacy_inpatient_items_list_cache alc
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
                if (strtolower($Billing_Type) == 'inpatient cash') {
                    if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                        if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                            ?>
                            <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                            <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('')">&nbsp;&nbsp;
                            <?php
                        }
                        }
                        if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { //this setting allows system to display both (send to cashier) and (make payment) buttons
                           if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                            echo '<button class="art-button-green" type="button" onclick="Make_Payment(); clearContent();">ADD THE SERVICES TO THE BILL</button>';
                           }
                        }
                        ?>
                        <button class="art-button-green" type="button" onclick="Check_Patients_Bill('true');
                                            clearContent();">ADD TO THE PATIENT BILL</button>

                        <button class="art-button-green" type="button" onclick="Send_To_Cashier();clearContent();">SEND TO CASHIER</button>
                                <?php
                            } else {
                                ?>
                        <button class="art-button-green" type="button" onclick="Check_Patients_Bill();
                                            clearContent();">ADD TO THE PATIENT BILL</button>
                                <?php
                            }
                        } else {
                            if ($Exemption == 'yes') {
                                ?>
                        <input type="button" name="Send_To_Approval" id="Send_To_Approval" class="art-button-green" value="SEND TO APPROVAL" onclick="Approval_Medication()">
                        <?php
                    } else {
                        ?>
                        <button class="art-button-green" type="button" onclick="Check_Patients_Bill(); clearContent();">ADD TO THE PATIENT BILL</button>
                        <?php
                    }
                }
                
            }
            ?>
            <button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD MORTUARY SERVICES</button>
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
                        <?php
//                        if (isset($_GET['Sub_Department_ID'])) {
//                            $Sub_Department_ID = $_GET['Sub_Department_ID'];
//                        } else {
//                            $Sub_Department_ID = 0;
//                        }
                        ?>
                        <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll; height: 150px;'>
                            <center>
                                <table width =100% border=0>
                                    <tr><td colspan=8><hr></td></tr>
                                    <tr id="thead">
                                        <td style="text-align: left;" width=5%><b>Sn</b></td>
                                        <td><b>Service Name</b></td>
                                        <td style="text-align: left;" width=25%><b>Remarks</b></td>
                                        <td style="text-align: right;" width=8%><b>Price</b></td>
                                        <td style="text-align: right;" width=8%><b>Discount</b></td>
                                        <td style="text-align: right;" width=8%><b>Quantity</b></td>
                                        <td style="text-align: right;" width=8%><b>Sub Total</b></td>
                                        <td style="text-align: center;" width=6%><b>Action</b></td></tr>
                                    <tr><td colspan=8><hr></td></tr>
                                    <?php
                                    $temp = 0;
                                    $total = 0;
                                    $payment_before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Receipt_Date = CURDATE()"))['Payment_Cache_ID'];

                                    if($payment_before > 0){
                                        $select_Transaction_Items = mysqli_query($conn,
                                        "SELECT ilc.Payment_Item_Cache_List_ID, t.Product_Name, ilc.Price, ilc.Quantity, ilc.Doctor_Comment, ilc.Discount
                                            from tbl_items t, tbl_item_list_cache ilc 
                                                where ilc.Item_ID = t.Item_ID and
                                                    ilc.Employee_Created = '$Employee_ID' and ilc.Payment_Cache_ID = '$Payment_Cache_ID' AND ilc.status = 'active'") or die(mysqli_error($conn));

                                            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                                            while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                                echo "<tr><td>" . ++$temp . "</td>";
                                                echo "<td>" . $row['Product_Name'] . "</td>";
                                                echo "<td>" . $row['Dosage'] . "</td>";
                                                echo "<td style='text-align:right;'>" . number_format($row['Price']) . "</td>";
                                                echo "<td style='text-align:right;'>" . $row['Discount'] . "</td>";
                                                echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                                echo "<td style='text-align:right;'>" . number_format(($row['Price'] - $row['Discount']) * $row['Quantity']) . "</td>";
                                                ?>
                                                <td style="text-align: center;"> 
                                                    <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Payment_Item_Cache_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                                </td>
                                                <?php
                                                $total = $total + ($row['Price'] * $row['Quantity']);
                                            }
                                    
                                        }echo "</tr></table>";
                                    ?>
                                    </fieldset>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td style='text-align: right; width: 70%;' id='Total_Area'>
                                            <h4>Total : <?php echo number_format($total); ?></h4>
                                        </td>
                                    <input type="text"hidden="hidden" value="<?= $total ?>" id="total_txt"/>
                                    </tr>
                                </table>
                            </center>
                        </fieldset>

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
                                                    <fieldset style='overflow-y: scroll; height: 200px;' id='Items_Fieldset'>
                                                        <table width=100%>
                                                            <?php
                                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID where Item_Type = 'Pharmacy' AND ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' order by Product_Name limit 150");
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                echo "<tr>
				   <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                                                ?>

                                                                <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                                                            Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Guarantor_Name; ?>');">

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
                                                    <input type='text' name='Item_Name' id='Item_Name' readyonly='readyonly' placeholder='Item Name'>
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
                                            <tr class='hide'>
                                                <td style='text-align: right;'>Item Balance</td>
                                                <td>
                                                    <input type='text' name='Item_Balance' id='Item_Balance' readonly='readonly' placeholder='Item Balance'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: right;'>Quantity</td>
                                                <td>
                                                    <input type='text' name='Quantity' id='Quantity' autocomplete="off" placeholder='Quantity' onkeypress="numberOnly(this); Calculate_Total();" oninput="numberOnly(this); Calculate_Total();" onkeyup="numberOnly(this);
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
                                                    <textarea name='Dosage' id='Dosage' placeholder='Remarks' autocomplete="off"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan=2 style='text-align: center;'>
                                                    <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD SERVICE' onclick='Verify_Balance()'>
                                                </td>
                                            </tr>
                                        </table>
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

                        <div id="Balance_Error">
                            Umebakiwa na idadi ndogo kutoa kiwango ulichokiandika
                        </div>

                        <div id="Item_Already_Added">
                            <center>Duplicate Item! Huduma hii ulishaichagua</center><br/>
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

                        <div id="No_Item_Selected">
                            No item selected!! Please select item
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
<div id="myDiaglog" style="display:none;">
    
    
</div>

                    <script type="text/javascript">
                        function Sponsor_Warning() {
                            var Guarantor_Name = '<?php echo strtolower($Guarantor_Name); ?>';
                            var Billing_Type = document.getElementById("Billing_Type").value;
                            update_new_transaction_sponsor_list(Billing_Type);
                            console.log(Billing_Type);
                            if (Billing_Type == 'Inpatient Cash') {
                                console.log("ndani")
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
                                       Inpatient Credit\n\
                                    </option>\n\
                                    <option>\n\
                                           Inpatient Cash\n\
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
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Folio_Number = document.getElementById("Folio_Number").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){            
               // document.location = "Departmental_Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>"+'&manual_offline=manual&approve_yes=yes';
                document.location = 'Inpatient_Pharmacy_Bill_Patient.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '"&post_payment=false"+"&manual_offline=manual";
                                   
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
               
        var uri = '../epay/pharmacyworkspageOfflinePaymentinpatient.php';
        
               
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Folio_Number = document.getElementById("Folio_Number").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
                            
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
                
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Folio_Number:Folio_Number,Claim_Form_Number:Claim_Form_Number},
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
            var reg_id='<?php echo $Registration_ID; ?>';
            var amount_required=document.getElementById("total_txt").value;
            offline_transaction(amount_required,reg_id);
            //var sms = confirm("Are you sure you want to make payment?");
            //if (sms == true) {
          //}
        }
    </script>
                        <script>
                            $(document).ready(function () {
                                $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
                            });
                        </script>

                        <script type="text/javascript">
                            function Refresh_Remember_Mode() {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                if (Billing_Type == 'Inpatient Credit') {
                                    document.getElementById("Remember_Mode").checked = false;
                                    document.getElementById("Transaction_Mode").value = 'Normal Transaction';
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
                                                Refresh_Transaction_Mode();
                                            } else {
                                                document.getElementById("Remember_Mode").checked = false;
                                            }
                                        }
                                    }; //specify name of function that will handle server response........
                                    myObjectRem.open('GET', 'Refresh_Remember_Mode.php?Session=Inpatient', true);
                                    myObjectRem.send();
                                }
                            }
                        </script>

                        <script type="text/javascript">
                            function Refresh_Transaction_Mode() {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                if (Billing_Type == 'Inpatient Cash') {
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
                                var Inpatient_Prepaid = "<?php echo strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']); ?>";
                                if (Transaction_Mode == 'Fast Track Transaction' && Billing_Type == 'Inpatient Credit' && Inpatient_Prepaid == 'yes') {
                                    document.getElementById("Transaction_Mode").value = 'Normal Transaction';
                                    $("#Change_Billing_Type_Alert").dialog("open");
                                }
                                Remember_Mode_Function();
                            }
                        </script>

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
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

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

                                } else {
                                    var r = confirm("Are you sure you want to bill this Service(s)?");
                                    if (r == true) {
                                        document.location = 'send_to_cashier_mortuary.php?Registration_ID=' + Registration_ID +'&Sub_Department_ID=' +Sub_Department_ID;
                                    }
                                }
                            }
                        </script>

                        <script>
                            function Confirm_Remove_Item(Item_Name, Payment_Item_Cache_List_ID, Registration_ID) {
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
                                            update_billing_type(Registration_ID);
                                            update_process_buttons(Registration_ID);
                                            // update_transaction_mode(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    My_Object_Remove_Item.open('GET', 'Mortuary_Service_Remove_Item_From_List.php?Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID + '&Registration_ID=' + Registration_ID, true);
                                    My_Object_Remove_Item.send();
                                }
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

                                myObjectUpd.open('GET', 'Update_Transaction_Mode_Inpatient.php?Registration_ID=' + Registration_ID, true);
                                myObjectUpd.send();
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
                                        //alert(data600)
                                        document.getElementById("total_txt").value=data600;
                                        document.getElementById('Total_Area').innerHTML = "<h4>Total : "+data600+"</h4>";
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Update_Total.open('GET', 'update_mortuary_total_services.php?Registration_ID=' + Registration_ID, true);
                                My_Object_Update_Total.send();
                            }
                        </script>

                        <script>
                            function update_Billing_Type() {
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                if (window.XMLHttpRequest) {
                                    My_Object_Update_Total = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    My_Object_Update_Total = new ActiveXObject('Micrsoft.XMLHTTP');
                                    My_Object_Update_Total.overrideMimeType('text/xml');
                                }

                                My_Object_Update_Total.onreadystatechange = function () {
                                    data_Audate = My_Object_Update_Total.responseText;
                                    if (My_Object_Update_Total.readyState == 4) {
                                        document.getElementById('Total_Area').innerHTML = data_Audate;
                                        //update_total(Registration_ID);
                                        //update_Billing_Type(Registration_ID);
                                        //Update_Claim_Form_Number();
                                    }
                                }; //specify name of function that will handle server response........

                                My_Object_Update_Total.open('GET', 'mortuary_update_billing_type.php?Registration_ID=' + Registration_ID + '&Guarantor_Name=' + Guarantor_Name, true);
                                My_Object_Update_Total.send();
                            }
                        </script>
                        <script>
                            function Get_Details(Item_Name, Item_ID) {
                                document.getElementById('Quantity').value = '';
                                document.getElementById('Dosage').value = '';
                                document.getElementById('Total').value = '';
                                document.getElementById('Discount').value = '';
                                document.getElementById('Item_Balance').value = '';
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
                                if (Billing_Type == 'Inpatient Credit') {
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
                                                //Get_Details(Item_Name,Item_ID);
                                                Get_Details_Verify(Item_Name, Item_ID);
                                            } else {
                                                document.getElementById("Price").value = 0;
                                                document.getElementById("Quantity").value = '';
                                                document.getElementById("Item_Name").value = '';
                                                document.getElementById('Discount').value = '';
                                                document.getElementById('Item_Balance').value = '';
                                                document.getElementById("Total").value = '';
                                                $("#Non_Supported_Item").dialog("open");
                                            }
                                        }
                                    }; //specify name of function that will handle server response........
                                    My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID, true);
                                    My_Object_Verify_Item.send();
                                } else {
                                    //Get_Details(Item_Name,Item_ID);
                                    Get_Details_Verify(Item_Name, Item_ID);
                                }
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

                                myObjectVerify.open('GET', 'mortuary_item_verify.php?Item_ID=' + Item_ID + '&Registration_ID=' + Registration_ID, true);
                                myObjectVerify.send();
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
                                var new_sponsor_to_bill = document.getElementById("new_sponsor_to_bill").value;
                                if (new_sponsor_to_bill == '') {
                                    alert("Please select temporary sponsor for this transaction");
                                    document.getElementById("sponsor_for_this_trans").style = 'color:red;font-weight:bold;text-align:right';
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
                                var Transaction_Title = document.getElementById("Transaction_Mode").value
                                document.getElementById("Transaction_Mode_Area").innerHTML = '<span style="color: #037CB0;"><h4>TRANSACTION MODE : <b>' + Transaction_Title + '</b></h4></span>';
                                $("#Add_Pharmacy_Items").dialog("open");
                                getItemsListFiltered("");
                            }
                        </script>

                        <script>
                            function clearContent() {
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Item_ID").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Dosage").value = '';
                                document.getElementById("Total").value = '';
                                document.getElementById("Discount").value = '';
                                document.getElementById('Item_Balance').value = '';
                                document.getElementById("Search_Value").value = '';
                            }
                        </script>


                        <script>
                            function Get_Item_Price(Item_ID, Guarantor_Name) {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                                var Sponsor_ID='<?php echo $Sponsor_ID; ?>';
                                //alert(Item_ID);
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

                                myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type + '&Transaction_Mode=' + Transaction_Mode+"&Sponsor_ID="+Sponsor_ID, true);
                                myObject.send();
                            }
                        </script>

                        <script>
                            function update_process_buttons(Registration_ID) {

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

                                my_Object_Update_Process.open('GET', 'mortuary_additional_update_button.php?Registration_ID=' + Registration_ID, true);
                                my_Object_Update_Process.send();
                            }
                        </script>

                        <script src="js/jquery-1.8.0.min.js"></script>
                        <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
                        <script src="script.js"></script>
                        <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
                        <script src="script.responsive.js"></script>

                        <script>
                            $(document).ready(function () {
                                $("#Sponsor_Warning").dialog({autoOpen: false, width: '40%', height: 180, title: 'BILLING TYPE WARNING!', modal: true});
                                $("#Add_Pharmacy_Items").dialog({autoOpen: false, width: 950, height: 320, title: 'eHMS 4.0 :: ADD MORTUARY SERVICES', modal: true});
                                $("#Non_Supported_Item").dialog({autoOpen: false, width: '40%', height: 150, title: 'NON SUPPORTED ITEM', modal: true});
                                $("#Balance_Error").dialog({autoOpen: false, width: '40%', height: 110, title: 'BALANCE WARNING!', modal: true});
                                $("#No_Item_Selected").dialog({autoOpen: false, width: '40%', height: 110, title: 'ITEM MISSING!', modal: true});
                                $("#Change_Billing_Type_Alert").dialog({autoOpen: false, width: '60%', height: 150, title: 'TRANSACTION WARNING!', modal: true});
                                $("#Item_Already_Added").dialog({autoOpen: false, width: '40%', height: 110, title: 'ITEM WARNING!', modal: true});
                                $("select").select2();
                                });
                        </script>

                        <script type="text/javascript">
                            function Close_Change_Billing_Type_Alert() {
                                $("#Change_Billing_Type_Alert").dialog("close");
                            }
                        </script>

                        <script type="text/javascript">
                            function Change_Billing_Type() {
                                document.getElementById("Billing_Type").value = 'Inpatient Cash';
                                document.getElementById("Transaction_Mode").value = 'Fast Track Transaction';
                                $("#Change_Billing_Type_Alert").dialog("close");
                            }
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
                                        var feedback = 'no';
                                        if (feedback == 'no') {
                                            Get_Selected_Item();
                                        } else {
                                            if (Item_ID != null && Item_ID != '') {
                                                $("#Balance_Error").dialog("open");
                                            } else {
                                                $("#No_Item_Selected").dialog("open");
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
                                var Discount = document.getElementById("Discount").value;
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                Sponsor_ID=$("#new_sponsor_to_bill").val();
                                var Quantity = document.getElementById("Quantity").value;
                                var Registration_ID = <?php echo $Registration_ID; ?>;
                                var Dosage = document.getElementById("Dosage").value;
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                Guarantor_Name=$("#new_sponsor_to_bill").text();
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                                var Clinic_ID = document.getElementById("Clinic_ID").value;
                                var clinic_location_id = document.getElementById("clinic_location_id").value;
                                var working_department = document.getElementById("working_department").value;

                                var Price = document.getElementById("Price").value;
                                //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 
                                if (parseInt(Price) == 0) {
                                    alert('Selected item missing price!!. Please specify item price to continue');
                                    exit;
                                }

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
                                            document.getElementById('Item_Balance').value = '';
                                            document.getElementById("Total").value = '';
                                            document.getElementById("Search_Value").focus();
                                            alert("Item Added Successfully");
                                            update_total(Registration_ID);
                                            update_process_buttons(Registration_ID);
                                            // update_transaction_mode(Registration_ID);
                                        }
                                    }; 
                                    myObject2.open('GET', 'add_mortuary_item_ajax.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number + '&Billing_Type=' + Billing_Type + '&Dosage=' + Dosage + '&Discount=' + Discount + '&Transaction_Mode=' + Transaction_Mode+"&Clinic_ID="+Clinic_ID+'&clinic_location_id='+clinic_location_id+"&working_department="+working_department, true);
                                    myObject2.send();

                                } else if (Registration_ID != '' && Registration_ID != null &&  Item_ID != '' && Item_ID != null != '' && Quantity != '' && Quantity != null) {
                                    alertMessage();
                                } else {
                                    if (Quantity == '' || Quantity == null) {
                                        document.getElementById("Quantity").focus();
                                        document.getElementById("Quantity").style = 'border: 3px solid red';
                                    }
                                    if (Dosage == '' || Dosage == null) {
                                        document.getElementById("Dosage").focus();
                                        document.getElementById("Dosage").style = 'border: 3px solid red';
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
                                myObjectUpdateBilling.open('GET', 'mortuary_update_billing_type.php?Registration_ID=' + Registration_ID, true);
                                myObjectUpdateBilling.send();
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
                        <script>
                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Item_ID").value = '';
                                document.getElementById("Dosage").value = '';
                                document.getElementById("Quantity").value = '';
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
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
                                myObject.open('GET', 'Get_List_Of_mortuary_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name+"&Sponsor_ID="+Sponsor_ID, true);
                                myObject.send();
                            }
                        </script>

                        <script>
                            function Check_Patients_Bill(post_payment='') {

                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                if (window.XMLHttpRequest) {
                                    myObjectConfirm = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectConfirm.overrideMimeType('text/xml');
                                }

                                myObjectConfirm.onreadystatechange = function () {
                                    data2000 = myObjectConfirm.responseText;
                                    if (myObjectConfirm.readyState == 4) {
                                        var feedback = data2000;
                                        if (feedback == 'yes') {
                                            Bill_Patient(post_payment);
                                        } else {
                                            alert("Sorry. Process Fail! Some Services detected with zore price.\nYou are not allowed to process Services with zero Price");
                                        }
                                    }
                                }; //specify name of function that will handle server response........
                                myObjectConfirm.open('GET', 'Inpatient_Pharmacy_Check_Price.php?Registration_ID=' + Registration_ID, true);
                                myObjectConfirm.send();
                            }
                        </script>

                        <script type='text/javascript'>
                            function Bill_Patient(post_payment='') {
                                var from_billing_work='<?= $from_billing_work ?>';
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                Guarantor_Name=$("#new_sponsor_to_bill").text();
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Folio_Number = document.getElementById("Folio_Number").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Patient_Name = '<?php echo $Patient_Name; ?>';

                              if (Guarantor_Name != 'NHIF') {
                                    if (Registration_ID == '' || Registration_ID == null) {
                                        document.getElementById("Registration_ID").focus();
                                        document.getElementById("Registration_ID").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Registration_ID").style = 'border: 3px';
                                    }
                                } else {
                                    var r = confirm("Are you sure you want to add these services to " + Patient_Name + "'s Bill?");
                                    if (r == true) {
                                        document.location = 'Inpatient_Pharmacy_Bill_Patient.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '"&post_payment="+post_payment+"&Check_In_Type=Pharmacy&from_billing_work="+from_billing_work;
                                    }
                                }
                            }
                        </script>

                        <script type="text/javascript">
                            function Approval_Medication() {
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Folio_Number = document.getElementById("Folio_Number").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var Patient_Name = '<?php echo $Patient_Name; ?>';

                            if (Guarantor_Name != 'NHIF' && (Registration_ID == '' || Registration_ID == null)) {
                                    if (Registration_ID == '' || Registration_ID == null) {
                                        document.getElementById("Registration_ID").focus();
                                        document.getElementById("Registration_ID").style = 'border: 3px solid red';
                                    } else {
                                        document.getElementById("Registration_ID").style = 'border: 3px';
                                    }
                                } else {
                                    var r = confirm("Are you sure you want to send this bill to approval?");
                                    if (r == true) {
                                        document.location = 'Inpatient_Pharmacy_Send_To_Approval.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number;
                                    }
                                }
                            }
                        </script>
                        <script>
                            function alertMessage() {
                                alert("Please select service first!");
                                document.getElementById("Quantity").value = '';
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

                                myObjectGetDetails.open('GET', 'ePayment_Patient_Details_Departmental.php?src=inpatlist&Section=Pharmacy&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
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
                                myObjectConfirm.open('GET', 'Confirm_ePayment_Patient_Details_Departmental.php?src=inpatlist&Section=MainPharmacy&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Registration_ID=' + Registration_ID, true);
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
                                                document.location = 'Departmental_Bank_Payment_Transaction.php?src=inpatlist&Section=MainPharmacy&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type;
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
                                var wTop = window.screenTop ? window.screenTop : window.screenY;

                                var left = wLeft + (window.innerWidth / 2) - (w / 2);
                                var top = wTop + (window.innerHeight / 2) - (h / 2);
                                var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
                                return mypopupWindow;
                            }
                            function create_epayment_mobile_card_bill(Payment_Cache_ID){
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Folio_Number = document.getElementById("Folio_Number").value;
                                var Registration_ID = '<?php echo $Registration_ID; ?>';
                                var new_sponsor_to_bill = document.getElementById("new_sponsor_to_bill").value;
                                if (new_sponsor_to_bill == '') {
                                    alert("Please select temporary sponsor for this transaction");
                                    document.getElementById("sponsor_for_this_trans").style = 'color:red;font-weight:bold;text-align:right';
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

                                } else {
                                    var Sub_Department_Name='<?php echo $Sub_Department_Name; ?>';
                                    var Registration_ID='<?php echo $Registration_ID; ?>';
                                    var Check_In_Type='Pharmacy';
                                    if(confirm("Are You sure you want to go to Mobile/Card Payment")){
                                        $.ajax({
                                            type:'GET',
                                            url:'ajax_create_epayment_mobile_card_bill_from_item_added_pharmacy.php',
                                            data:{Sub_Department_Name:Sub_Department_Name,Registration_ID:Registration_ID,Check_In_Type:Check_In_Type,Payment_Cache_ID:Payment_Cache_ID,Section:'Inpatient',Folio_Number:Folio_Number,Claim_Form_Number:Claim_Form_Number},
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

                        <?php
                        include("./includes/footer.php");
                        ?>