<?php
include("./includes/connection.php");
include("./includes/header.php");
// echo "<script>if (window.location.href.indexOf('reload')==-1) {
//     window.location.replace(window.location.href+'?reload');
//}</script>";
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 0;
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = '';
}

if (isset($_GET['consultation_ID'])) {
    $given_consultation_ID = $_GET['consultation_ID'];
} else {
    $given_consultation_ID = 0;
}

$Pre_Operative_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_pre_operative_checklist  WHERE Registration_ID = '$Registration_ID' ORDER BY Pre_Operative_ID DESC LIMIT 1"))['Pre_Operative_ID'];


    if($Pre_Operative_ID > 0){
        echo "<a href='Checklist_Form.php?Registration_ID=".$Registration_ID."&Pre_Operative_ID=".$Pre_Operative_ID."&RevisitedPatient=RevisitedPatientThisPage' style='background: #990000;' class='art-button-green' target='_blank'>RECENT PRE-OPERATIVE CHECKLIST</a>";
    }

    if (strtolower($Section) == 'inpatient') {
        echo "<a href='doctorspageinpatientwork.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $given_consultation_ID . "&Admision_ID=".$Admision_ID."&DoctorsPageInpatientWork=DoctorsPageInpatientWorkThisPage' class='art-button-green'>BACK</a>";
    } else if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_Item_List_ID']) && isset($_GET['Patient_Payment_ID'])) {
        echo "<a href='clinicalnotes.php?Registration_ID=" . $Registration_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&consultation_ID=" . $consultation_ID . "&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage' class='art-button-green'>BACK</a>";
    }

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//get patient details
$select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, pr.Occupation, pr.Phone_Number, Member_Number, Registration_Date_And_Time, Require_Document_To_Sign_At_receiption 
                        from tbl_patient_registration pr, tbl_sponsor sp where 
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Occupation = $data['Occupation'];
        $Phone_Number = $data['Phone_Number'];
        $Member_Number = $data['Member_Number'];
        $Registration_Date_And_Time = $data['Registration_Date_And_Time'];
        $Require_Document_To_Sign_At_receiption = $data['Require_Document_To_Sign_At_receiption'];
    }
} else {
    $Patient_Name = '';
    $Gender = '';
    $Date_Of_Birth = '';
    $Guarantor_Name = '';
    $Occupation = '';
    $Phone_Number = '';
    $Member_Number = '';
    $Registration_Date_And_Time = '';
    $Require_Document_To_Sign_At_receiption = '';
}

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$Age = $diff->y . " Years, ";
$Age .= $diff->m . " Months, ";
$Age .= $diff->d . " Days";
//    echo $Billing_Type;
//    exit();
?>

<style>
    table,tr,td{
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <legend align="right">RERFORM SURGERY</legend>
    <center>
        <table width="100%">
            <tr>
                <td width="12%" style="text-align: right;">Patient Name</td>
                <td width="15%"><input type="text" name="Patient_Name" id="Patient_Name" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" ></td>
                <td width="12%" style="text-align: right;">Patient Number</td>
                <td><input type="text" name="Registration_ID" id="Registration_ID" readonly="readonly" value="<?php echo $Registration_ID; ?>" ></td>
                <td width="8%" style="text-align: right;">Age</td>
                <td><input type="text" name="Age" id="Age" readonly="readonly" value="<?php echo $Age; ?>" ></td>
            </tr>
            <tr>
                <td width="12%" style="text-align: right;">Sponsor Name</td>
                <td><input type="text" name="Guarantor_Name" id="Guarantor_Name" readonly="readonly" value="<?php echo $Guarantor_Name; ?>" ></td>
                <td width="12%" style="text-align: right;">Gender</td>
                <td><input type="text" name="Gender" id="Gender" readonly="readonly" value="<?php echo $Gender; ?>" ></td>
                <td width="12%" style="text-align: right;">Visit Date</td>
                <td><input type="text" name="Visit_Date" id="Visit_Date" readonly="readonly" value="<?php echo $Today; ?>" ></td>
            </tr>
            <tr>
                <td width="8%" style="text-align: right;">Phone Number</td>
                <td><input type="text" name="Phone_Number" id="Phone_Number" readonly="readonly" value="<?php echo $Phone_Number; ?>" ></td>
                <td width="12%" style="text-align: right;">Registered Date</td>
                <td><input type="text" name="Registration_Date_And_Time" id="Registration_Date_And_Time" readonly="readonly" value="<?php echo $Registration_Date_And_Time; ?>"></td>
                <td width="12%" style="text-align: right;">Doctor Name</td>
                <td><input type="text" name="Doctor_Name" id="Doctor_Name" value="<?php echo $Employee_Name; ?>" readonly="readonly"></td>
            </tr>
        </table>
    </center>
</fieldset>

<?php


$Title = '
            <tr><td colspan="9"><hr></td></tr>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>TEST NAME</b></td>
                <td width="10%"><b>STATUS</b></td>
                <td width="10%"><b>PAYMENT STATUS</b></td>
                <td width="15%"><b>REMARK</b></td>
                <td width="28%" colspan="2" style="text-align: center;"><b>ACTIONS & REPORTS</b></td>
                <td width="8%"><b>COMMENT</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>';
?>

<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Items_Fieldset'>
    <table width="100%">
        <?php
        $temp = 0;
        $select_surgery = mysqli_query($conn,"SELECT  i.Product_Name, pc.consultation_ID, ilc.Priority, ilc.Status, ilc.Transaction_Type, ilc.Patient_Payment_ID, pc.Billing_Type, ilc.Doctor_Comment,
                                        Payment_Item_Cache_List_ID, ilc.payment_type
                                        from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items i where
                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                        i.Item_ID = ilc.Item_ID and
                                        ilc.Check_In_Type = 'Surgery' and
                                        pc.Registration_ID = '$Registration_ID' and
                                        ilc.Status <> 'removed'") or die(mysqli_error($conn));
        $num_surgery = mysqli_num_rows($select_surgery);
        echo $Title;
        if ($num_surgery > 0) {
            if($Priority == 'Urgent'){
                while ($data = mysqli_fetch_array($select_surgery)) {
                    $Transaction_Type = $data['Transaction_Type'];
                    $ilcPatient_Payment_ID= $data['Patient_Payment_ID'];
                    $Billing_Type = $data['Billing_Type'];
                    $Status = $data['Status'];
                    $consultation_ID = $data['consultation_ID'];
                    $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                    $Priority = $data['Priority'];
                    if ($Status == 'notsaved') {
                        mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'active' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                        echo "<script>if (window.location.href.indexOf('reload')==-1) {
                        window.location.replace(window.location.href+'?reload');
                      }</script>";
                    }
    // die("SELECT Billing_Type, payment_type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$ilcPatient_Payment_ID'");
                    $Select_status_receipt = mysqli_query($conn, "SELECT Billing_Type, payment_type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$ilcPatient_Payment_ID'");
                        while($dta = mysqli_fetch_assoc($Select_status_receipt)){
                            $ppBilling_Type = $dta['Billing_Type'];
                            $pppayment_type = $dta['payment_type'];
                        }
    
                    ?>
                    <tr>
                        <td><?php echo ++$temp; ?><b>.</b></td>
                        <td><?php echo $data['Product_Name']; ?></td>
                        <td>
                            <select id="Surgery_Status" id="Surgery_Status">
                                <?php if (strtolower($Status) != 'served') { ?>
                                    <option selected="selected">Pending</option>
                                <?php } else { ?>
                                    <option>Done</option>
                                <?php } ?>
                            </select>
                        </td>
                        <?php
                        //check payment status
                        if (strtolower($Billing_Type) == 'inpatient cash'  && strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Paid
                            <?php 
    //                        if($Billing_Type=='inpatient cash'){
                                // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                                //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bill</button>";
                                // }else{
                                    echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';
    
                                // }
    //                        }
                            ?>
                            </td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES"  onclick="Payment_Warning(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
    
                            <?php
                        } else if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Paid
                            <?php 
    //                        if($Billing_Type=='inpatient cash'){
                                // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                                //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bill</button>";
                                // }else{
                                    echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';
    
                                // }
    //                        }
                            ?>
                            </td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                            </td>
    
                            <?php
                        } else if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'credit' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Billed</td>
                            <td>
                                <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <?php if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory') { ?>
                                <td>
                                    <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                                </td>
                            <?php } else { ?>
                                <td>
                                    <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                                </td>
                            <?php } ?>
                            <?php
                        } else if (strtolower($Status) == 'removed') {
                            ?>
                            <td>Removed</td>
                            <td>
                                <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="alert('Test Removed')">
                            </td>
                            <?php
                        } else if ((strtolower($Status) == 'paid' || strtolower($Status) == 'served') && strtolower($Transaction_Type) == 'credit') {
                            ?>
                            <td>Billed</td>
                            <td>
                                <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                            <?php
                        } else if ((strtolower($Status) == 'paid' || strtolower($Status) == 'served') && strtolower($Transaction_Type) == 'cash') {
                            if($pppayment_type == 'post' && $ppBilling_Type == 'Inpatient Cash'){
    
                                echo "<td>Billed</td>";
                            }else{
                            ?>
                            <td>Paid</td>
                            <?php
                            }
                            ?>
                            <td>
                                <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                            <?php
                            
                        } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active' and strtolower($payment_type) == 'pre') {
                            ?>
                            <td>Not Paid
                            <?php 
    //                        if($Billing_Type=='inpatient cash'){
                                // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                                //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bil</button>";
                                // }else{
                                    echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';
    
                                // }
    //                        }
                            ?>
                            </td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                            </td>
                            <?php
                        } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Billed</td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                            <?php
                        } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'credit' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Billed</td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                            <?php
                        } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                            ?>
                            <td>Not Billed</td>
                            <td>
                                <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                            </td>
                            <?php
                        } else {
                            ?>
                            <td>Not Billed
                            <button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(<?= $Payment_Item_Cache_List_ID ?>)">Send To Bill</button>
                            </td>
                            <td>
                                <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                            </td>
                            <?php if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory') { ?>
                                <td>
                                    <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                                </td>
                            <?php } else { ?>
                                <td>
                                    <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                                </td>
                            <?php } ?>
                            <?php
                        } 
                        
                        echo "<td>";
                        $who_Pre_Operative_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist  WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Level_Status = 3"))['Pre_Operative_ID'];
    
                        if($who_Pre_Operative_ID > 0){
                            echo "<a href='who_Checklist_Form.php?Registration_ID=".$Registration_ID."&Pre_Operative_ID=".$who_Pre_Operative_ID."&consultation_ID='.$consultation_ID.'&RevisitedPatient=RevisitedPatientThisPage' style='background: #990000;' class='art-button-green' target='_blank'>WHO CHECKLIST DONE</a>";
                        }else{
                            echo "<a href='who_checklistform.php?consultation_ID=".$consultation_ID."&Registration_ID=".$Registration_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&RevisitedPatient=RevisitedPatientThisPage' class='art-button-green' target='_blank'>WHO CHECKLIST FORM</a>";
                        }
                        if (strtolower($Status) == 'served') {
                            ?>
                        <input type="button" value="REPORT" class="art-button-green" onclick="Preview_Operative_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        <?php
                    } else {
                        ?>
    
                        <input type="button" value="REPORT" class="art-button-green" onclick="Served_Warning()">
                    <?php } 
                                        $Surgery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];
                                        if($Surgery_Status == 'Death'){
                                            $style = " style='background: #bd0d0d; !important; padding: 5px;'";
                                        }elseif($Surgery_Status == 'removed'){
                                            $style = " style='background: yellow; !important; padding: 5px;'";
                                        }elseif($Surgery_Status == 'Active'){
                                            $style = " style='background: green; !important; padding: 5px;'";
                                        }else{
                                            $style = " style='padding: 5px;'";
                                        }
                                        // echo "<td>".$Surgery_Status."</td>";
                                            echo "<td $style>
                                            <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                                <option "; if($Surgery_Status == '' || $Surgery_Status == NULL) { echo "selected='selected'"; } echo"></option>
                                                <option "; if($Surgery_Status == 'Active') { echo "selected='selected'"; } echo">Active</option>
                                                <option "; if($Surgery_Status == 'removed') { echo "selected='selected'"; } echo" value='Reject'>Postpone</option>
                                                <option "; if($Surgery_Status == 'Death') { echo "selected='selected'"; } echo">Death</option>
                                            </select>
                                        </td>";
                    ?>
                    </td>
                    </tr>
                    <?php
                }
            }else{
            while ($data = mysqli_fetch_array($select_surgery)) {
                $Transaction_Type = $data['Transaction_Type'];
                $ilcPatient_Payment_ID= $data['Patient_Payment_ID'];
                $Billing_Type = $data['Billing_Type'];
                $Status = $data['Status'];
                $consultation_ID = $data['consultation_ID'];
                $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                $Priority = $data['Priority'];

                $Select_appointment = mysqli_query($conn, "SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Final_Decision = 'Accepted'") or die(mysqli_error($conn));
                if(mysqli_num_rows($Select_appointment)>0){
                if ($Status == 'notsaved') {
                    mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'active' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                    echo "<script>if (window.location.href.indexOf('reload')==-1) {
                    window.location.replace(window.location.href+'?reload');
                  }</script>";
                }
// die("SELECT Billing_Type, payment_type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$ilcPatient_Payment_ID'");
                $Select_status_receipt = mysqli_query($conn, "SELECT Billing_Type, payment_type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$ilcPatient_Payment_ID'");
                    while($dta = mysqli_fetch_assoc($Select_status_receipt)){
                        $ppBilling_Type = $dta['Billing_Type'];
                        $pppayment_type = $dta['payment_type'];
                    }

                ?>
                <tr>
                    <td><?php echo ++$temp; ?><b>.</b></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td>
                        <select id="Surgery_Status" id="Surgery_Status">
                            <?php if (strtolower($Status) != 'served') { ?>
                                <option selected="selected">Pending</option>
                            <?php } else { ?>
                                <option>Done</option>
                            <?php } ?>
                        </select>
                    </td>
                    <?php
                    //check payment status
                    if (strtolower($Billing_Type) == 'inpatient cash'  && strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Paid
                        <?php 
//                        if($Billing_Type=='inpatient cash'){
                            // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                            //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bill</button>";
                            // }else{
                                echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';

                            // }
//                        }
                        ?>
                        </td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES"  onclick="Payment_Warning(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        </td>

                        <?php
                    } else if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Paid
                        <?php 
//                        if($Billing_Type=='inpatient cash'){
                            // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                            //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bill</button>";
                            // }else{
                                echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';

                            // }
//                        }
                        ?>
                        </td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                        </td>

                        <?php
                    } else if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'credit' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Billed</td>
                        <td>
                            <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <?php if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory') { ?>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                            </td>
                        <?php } else { ?>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                        <?php } ?>
                        <?php
                    } else if (strtolower($Status) == 'removed') {
                        ?>
                        <td>Removed</td>
                        <td>
                            <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="alert('Test Removed')">
                        </td>
                        <?php
                    } else if ((strtolower($Status) == 'paid' || strtolower($Status) == 'served') && strtolower($Transaction_Type) == 'credit') {
                        ?>
                        <td>Billed</td>
                        <td>
                            <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        </td>
                        <?php
                    } else if ((strtolower($Status) == 'paid' || strtolower($Status) == 'served') && strtolower($Transaction_Type) == 'cash') {
                        if($pppayment_type == 'post' && $ppBilling_Type == 'Inpatient Cash'){

                            echo "<td>Billed</td>";
                        }else{
                        ?>
                        <td>Paid</td>
                        <?php
                        }
                        ?>
                        <td>
                            <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        </td>
                        <?php
                        
                    } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active' and strtolower($payment_type) == 'pre') {
                        ?>
                        <td>Not Paid
                        <?php 
//                        if($Billing_Type=='inpatient cash'){
                            // if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                            //     echo  "<button type='button'style='height:27px!important;color:#FFFFFF!important' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bil</button>";
                            // }else{
                                echo '<button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(' . $Payment_Item_Cache_List_ID . ')">Send To Bill</button>';

                            // }
//                        }
                        ?>
                        </td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                        </td>
                        <?php
                    } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Billed</td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        </td>
                        <?php
                    } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'no' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'credit' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Billed</td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                        </td>
                        <?php
                    } else if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes' && (strtolower($Billing_Type) != 'inpatient cash' || (strtolower($Billing_Type) != 'inpatient credit' )) and strtolower($Transaction_Type) == 'cash' and strtolower($Status) == 'active') {
                        ?>
                        <td>Not Billed</td>
                        <td>
                            <textarea id='comments' name='comments' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <td>
                            <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                        </td>
                        <?php
                    } else {
                        ?>
                        <td>Not Billed
                        <button type="button"style="height:27px!important;color:#FFFFFF!important" class="art-button-green" onclick="sendToBill(<?= $Payment_Item_Cache_List_ID ?>)">Send To Bill</button>
                        </td>
                        <td>
                            <textarea id='comments' name='comments' required='required' rows='0' cols='0' style="width: 100%; height: 30px;" readonly="readonly"><?php echo $data['Doctor_Comment']; ?></textarea>
                        </td>
                        <?php if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory') { ?>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Payment_Warning()">
                            </td>
                        <?php } else { ?>
                            <td>
                                <input type="button" name="Post_Operative_Button" id="Post_Operative_Button" class="art-button-green" value="POST OPERATIVE NOTES" onclick="Post_Operative_Process(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                            </td>
                        <?php } ?>
                        <?php
                    } 
                    
                    echo "<td>";
                    $who_Pre_Operative_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist  WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Level_Status = 3"))['Pre_Operative_ID'];

                    if($who_Pre_Operative_ID > 0){
                        echo "<a href='who_Checklist_Form.php?Registration_ID=".$Registration_ID."&Pre_Operative_ID=".$who_Pre_Operative_ID."&consultation_ID='.$consultation_ID.'&RevisitedPatient=RevisitedPatientThisPage' style='background: #990000;' class='art-button-green' target='_blank'>WHO CHECKLIST DONE</a>";
                    }else{
                        echo "<a href='who_checklistform.php?consultation_ID=".$consultation_ID."&Registration_ID=".$Registration_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&RevisitedPatient=RevisitedPatientThisPage' class='art-button-green' target='_blank'>WHO CHECKLIST FORM</a>";
                    }
                    if (strtolower($Status) == 'served') {
                        ?>
                    <input type="button" value="REPORT" class="art-button-green" onclick="Preview_Operative_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)">
                    <?php
                } else {
                    ?>

                    <input type="button" value="REPORT" class="art-button-green" onclick="Served_Warning()">
                <?php } 
                                    $Surgery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];
                                    if($Surgery_Status == 'Death'){
                                        $style = " style='background: #bd0d0d; !important; padding: 5px;'";
                                    }elseif($Surgery_Status == 'removed'){
                                        $style = " style='background: yellow; !important; padding: 5px;'";
                                    }elseif($Surgery_Status == 'Active'){
                                        $style = " style='background: green; !important; padding: 5px;'";
                                    }else{
                                        $style = " style='padding: 5px;'";
                                    }
                                    // echo "<td>".$Surgery_Status."</td>";
                                        echo "<td $style>
                                        <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                                            <option "; if($Surgery_Status == '' || $Surgery_Status == NULL) { echo "selected='selected'"; } echo"></option>
                                            <option "; if($Surgery_Status == 'Active') { echo "selected='selected'"; } echo">Active</option>
                                            <option "; if($Surgery_Status == 'removed') { echo "selected='selected'"; } echo" value='Reject'>Postpone</option>
                                            <option "; if($Surgery_Status == 'Death') { echo "selected='selected'"; } echo">Death</option>
                                        </select>
                                    </td>";
                ?>
                </td>
                </tr>
                <?php
            }else{
            echo "<tr><td colspan='7' style='text-align: center;'><h3>NO APPROVED SURGERY AVAILABLE</h3></td></tr>";
            }
        }
    }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'><h3>NO APPROVED SURGERY AVAILABLE</h3></td></tr>";
        }

        $consultation_ID_current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND Patient_Payment_Item_List_ID IS NOT NULL AND Process_Status = 'served' ORDER BY consultation_ID DESC LIMIT 1"))['consultation_ID'];
        ?>
    </table>
</fieldset>
<div id="Payment_Warning" style="width:25%;" style="display: none">
    <center>
        <?php if ((strtolower($Billing_Type) != 'outpatient cash' || strtolower($Billing_Type) != 'outpatient credit') && strtolower($Transaction_Type) == 'credit' and strtolower($Status) == 'active') { ?>
            <b>Can not Process This Procedure! This Procedure/Surgery must be Approved before performing it!</b><br/><br/>
        <?php } else { ?>
            <b>Not Paid or Billed! Patient must pay first or Send to Bill!</b><br/><br/>
        <?php } ?>
    </center>
    <table width="100%">
        <tr>
            <td style="text-align: right;"><input type="button" class="art-button-green" value="CLOSE" onclick="Payment_Warning_Close()"></td>
        </tr>
    </table>
</div>

<div id="Preview_Report">
    <center id="Report_Area">

    </center>    
</div>

<div id="Served_Warning" style="display:none">
    <center>No report generated. Selected test is not served</center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Served_Warning_Generated()">
            </td>
        </tr>
    </table>
</div>
<div id="rejection_reason_panel" style="display: none;">
    <input type='text' required='required' id="rejection_reason" placeholder="Reason For Rejection">
<input type='hidden' name='Date_From' id='Payment_Item_Cache_List_ID' value="">
<input type='hidden' name='Date_From' id='reason_holder' value="">
                    <br>
                    <br>
    <center> <input type="button" value="Save Changes" onclick='save_reason()' class="art-button-green"></center>
</div>
<?php
$sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
$Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];
    //select sonsor name
    $selectInfo = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $Guarantor_Name=mysqli_fetch_assoc($selectInfo)['Guarantor_Name'];
?>
<script type="text/javascript">
        function sendToBill(Payment_Item_Cache_List_ID){
        var selected_item = []; 
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID ?>';
        var Guarantor_Name = '<?php echo $Guarantor_Name ?>';
        
        var validate=0;
        selected_item.push(Payment_Item_Cache_List_ID);
        validate++;

    // console.log(selected_item);

        if(validate<=0){
            alert("Please Select Test You Want To Sent To Bill First");
        }else{
            if(confirm("Are you sure you want to bill the selected items?")){
            $.ajax({
                type:'POST',
                url:'ajax_creating_inpatient_bill.php',
                data:{selected_item:selected_item,Registration_ID:Registration_ID,Sponsor_ID:Sponsor_ID,Guarantor_Name:Guarantor_Name,Check_In_Type:"Surgery"},
                success:function(data){
                    console.log(selected_item);
                    console.log(data);
                        alert("Bill Created Successfully");
                        location.reload();
                }
            });
        }
        }
    }
    
    
    
    function Served_Warning() {
        $("#Served_Warning").dialog("open");
    }
</script>
<script type="text/javascript">
    function Served_Warning_Generated() {
        $("#Served_Warning").dialog("close");
    }
</script>
<script type="text/javascript">
    function Preview_Reports() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            dataPreview = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById("Report_Area").innerHTML = dataPreview;
                $("#Preview_Report").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreview.open('GET', 'Procedure_Preview_Report.php?Registration_ID=' + Registration_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Preview_Operative_Report(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        window.open("previewpostoperativereport.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + "&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage", "_blank");
    }
</script>


<script type="text/javascript">
    function Preview_Colonoscopy_Report(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        window.open("previewcolonoscopyreport.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + "&PreviewColonoscopyReport=PreviewColonoscopyReportThisPage", "_blank");
    }
</script>


<script type="text/javascript">
    function Preview_Upper_Git_Scope_Report(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        window.open("previewuppergitscopereport.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + "&PreviewUpperGitScopeReport=PreviewUpperGitScopeReportThisPage", "_blank");
    }
</script>
<script type="text/javascript">
    function Preview_Operative_All_Report(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        window.open("previewoperativeallreport.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + "&PreviewOperativeAllReport=PreviewOperativeAllReportThisPage", "_blank");
    }
</script>

<script type="text/javascript">
    function Move_On(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        var consultation_ID = '<?php echo $consultation_ID_current; ?>';
        var Section = '<?php echo $Section; ?>';
        window.open("postoperativenotes_theater.php?Registration_ID=" + Registration_ID + "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + "&consultation_ID=" + consultation_ID + "&Section=" + Section + "&PostOperativeNotes=PostOperativeNotesThisPage", "_parent");
    }
    function Post_Operative_Process(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID) {
        $.ajax({
            type: "GET",
            url: "ajax_set_Surgery_appointment.php",
            data: {
                Registration_ID:Registration_ID,
                Patient_Payment_ID:Patient_Payment_ID,
                Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                assign: 'check Status'
            },
            cache: false,
            success: function (response) {
                if(response == 200){
                    Move_On(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID)
                }else if(response == 201){
                    alert("This Surgery Can not be Performed for Some reasons! \n Please Contact Person Incharge for further Clarification about this Patient");
                    alert("This Surgery Did not pass the right chain!");
                    exit();
                    // Move_On(Registration_ID, Patient_Payment_ID, Patient_Payment_Item_List_ID, Payment_Item_Cache_List_ID);
                }
            }
        });
    }
</script>

<script type="text/javascript">
    function Payment_Warning() {
        $("#Payment_Warning").dialog("open");
    }
</script>
<script type="text/javascript">
    function Payment_Warning_Close() {
        $("#Payment_Warning").dialog("close");
    }
</script>
<script src="js/select2.min.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function () {
        $("#Payment_Warning").dialog({autoOpen: false, width: '35%', height: 160, title: 'eHMS 2.0 ~ Information!', modal: true});
        $("#Preview_Report").dialog({autoOpen: false, width: '75%', height: 280, title: '<?php echo strtoupper($Patient_Name=htmlspecialchars($Patient_Name,ENT_QUOTES)); ?> Surgery Reports', modal: true});
        $("#Served_Warning").dialog({autoOpen: false, width: '35%', height: 150, title: 'eHMS 2.0 ~ Information!', modal: true});
    });

    function add_reason(Payment_Item_Cache_List_ID) {
        assign = $("#assign"+Payment_Item_Cache_List_ID).val();
        reason = $("#reason").val();
        // var Payment_Item_Cache_List_ID = document.getElementById("assign" + Payment_Item_Cache_List_ID).value;
        var Employee_ID = '<?= $Current_Employee_ID ?>';
        if((assign == 'Reject' || assign == 'Death') && reason == undefined){
            document.getElementById('Payment_Item_Cache_List_ID').value = Payment_Item_Cache_List_ID;
            document.getElementById('reason_holder').value = assign;
            $('#rejection_reason_panel').dialog({
                modal: true,
                width: '30%',
                resizable: true,
                draggable: true,
                title: 'ADD REJECTION REASON',
               close: function (event, ui) {
               }
            });
        }else{
            $.ajax({
                type: "GET",
                url: "ajax_set_Surgery_appointment.php",
                data: {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,assign:assign,Employee_ID:Employee_ID},
                cache: false,
                success: function (response) {
                    
                }
            });
        }

    }
    function save_reason(){
        rejection_reason = $("#rejection_reason").val();
        Payment_Item_Cache_List_ID = $("#Payment_Item_Cache_List_ID").val();
        Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID'] ?>';
        reason_holder = $("#reason_holder").val();

        if(Payment_Item_Cache_List_ID != "" && rejection_reason != undefined){
            if(confirm("Are sure you want to Change Status For This Surgery?")){
                $.ajax({
                    type: "GET",
                    url: "ajax_set_Surgery_appointment.php",
                    data: {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,assign:reason_holder,Employee_ID:Employee_ID,rejection_reason:rejection_reason},
                    cache: false,
                    success: function (response) {
                        if(response == 200){
                            alert("The Surgery was Successfully postponned");
                            location.reload();
                        }else if(response == 201) {
                            alert("The Patient is DEAD, You can not change any details for at the moment");
                            location.reload();
                        }else{
                            alert(response);
                            location.reload();
                        }
                    $("#rejection_reason_panel").dialog("close");
                    // filterPatient();
                    }
                });
            }
        }
    }
    $(document).ready(function (e){
        $(".Mark_This_Item").select2();
    });
</script>
<?php
include("./includes/footer.php");
?>