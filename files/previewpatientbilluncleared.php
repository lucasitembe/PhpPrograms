<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$employeeId = $_SESSION['userinfo']['Employee_ID'];


if (isset($_GET['submit'])) {
    session_start();
    $_SESSION['bill_working_department'] = $_GET['bill_working_department'];
}

if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}

$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
    //$payments_filter = "pp.payment_type = 'post' and ";
} else {
    //$payments_filter = '';
}

$canZero = false;
if (strtolower($_SESSION['systeminfo']['enable_zeroing_price']) == 'yes') {
    $canZero = true;
}


?>


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

    label {
        font-weight: normal;
    }

    .cash_credit_bill_tbl,
    .cash_credit_bill_tbl tr,
    .cash_credit_bill_tbl td {
        border: 1px solid #CECECE !important;
    }

    button {
        height: 27px !important;
        color: #FFFFFF !important;
    }
</style>
<?php
if (isset($_GET['Status']) && $_GET['Status'] == 'cld') {
    $select = mysqli_query($conn, "SELECT pr.Patient_Name,Discharge_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number,Admission_Status,ward_type, sp.Guarantor_Name,sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_Time) AS NoOfDay,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp where
							cd.Admission_ID = ad.Admision_ID and

							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							(ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
} else {
    $select = mysqli_query($conn, "SELECT pr.Patient_Name,Discharge_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth,Admission_Status, pr.Member_Number,ward_type, sp.Guarantor_Name, sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,	emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID 	from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp where 	cd.Admission_ID = ad.Admision_ID and ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and 	pr.Registration_ID = ad.Registration_ID and	pr.Sponsor_ID = sp.Sponsor_ID and 	emp.Employee_ID= ad.Admission_Employee_ID and 	(ad.Admission_Status = 'Discharged' or ad.Admission_Status = 'Pending' or ad.Admission_Status = 'Admitted') and 	cd.Check_In_ID = '$Check_In_ID'	") or die(mysqli_error($conn));

   
}
$num = mysqli_num_rows($select);

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Registration_ID = $data['Registration_ID'];
        $Discharge_Date_Time = $data['Discharge_Date_Time'];
        $Admission_Status = $data['Admission_Status'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Member_Number = $data['Member_Number'];
        $Payment_Method = $data['payment_method'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Employee_Name = $data['Employee_Name'];
        $Admission_Date_Time = $data['Admission_Date_Time'];
        $Bed_Name = $data['Bed_Name'];
        $Hospital_Ward_Name = $data['Hospital_Ward_Name'];
        $Sponsor_ID = $data['Sponsor_ID'];
        $Cash_Bill_Status = $data['Cash_Bill_Status'];
        $Credit_Bill_Status = $data['Credit_Bill_Status'];
        $Admision_ID = $data['Admision_ID'];
        $ward_type=$data['ward_type'];
        $NoOfDay = $data['NoOfDay'];
        $lapsed_time_from_admision_date = $NoOfDay;
        $sikuzakulala = $data['NoOfDay'];
        //calculate age
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        if($Admission_Status =='Discharged'){
            $dischargedate = new DateTime($Discharge_Date_Time);
            $admisiondate = new DateTime($Admission_Date_Time);
            $No_Of_Day = $dischargedate->diff($admisiondate);
            $NoOfDay=$No_Of_Day->d;
            $lapsed_time_from_admision_date = $NoOfDay;
        }else{
            $date1 = new DateTime($Today);
            $admisiondate = new DateTime($Admission_Date_Time);
            $No_Of_Day = $date1->diff($admisiondate);
            $NoOfDay=$No_Of_Day->d;
            $lapsed_time_from_admision_date = $NoOfDay;
        }
        

    }
} else {
    $Patient_Name = '';
    $Registration_ID = '';
    $Guarantor_Name = '';
    $Gender = '';
    $Date_Of_Birth = '';
    $Member_Number = '';
    $Payment_Method = '';
    $Employee_Name = '';
    $Admission_Date_Time = '';
    $Bed_Name = '';
    $Hospital_Ward_Name = '';
    $Sponsor_ID = '';
    $Cash_Bill_Status = '';
    $Credit_Bill_Status = '';
    $Admision_ID = '';
    $NoOfDay = "";
}



// if (isset($_SESSION['userinfo'])) {
// if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {

echo    "<a target='_blank' href='inpatientbillingdirectcash.php?Registration_ID=" . $Registration_ID . "&NR=true&CP=True&PatientBilling=PatientBillingThisForm' class='art-button-green'>
            PAY BILL
        </a>";
// }
// }
//if (isset($_SESSION['userinfo']['Admission_Works'])) {
//    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
?>
<span id="discharge_btn">
    <input type="button" class="art-button-green" onclick="discharge_this_patient(<?= $Registration_ID ?>,<?= $Admision_ID ?>)" value="FINAL DISCHARGE PATIENT PROCESS" />
</span>
<span id="print_discharge_summary_btn" style="display:none">
    <?php
    $Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'"))['consultation_ID'];
    ?>
    <a class="art-button-green" href="print_patient_discharge_summery.php?consultation_ID=<?= $Consultation_ID ?>&Registration_ID=<?= $Registration_ID ?>&Admision_ID=<?= $Admision_ID ?>" target="_blank">PRINT PATIENT DISCHARGE SUMMARY</a>
</span>
<?php
 // echo "<a href='searchlistofoutpatientadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>DISCHARGE PATIENT</a>";
 //echo "<a href='searchlistofmortuaryadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>DISCHARGE MORTUARY</a>";
//    }
//}


echo '<input type="button" value="[BACK]" onclick="history.go(-1)" class="art-button-green">';
echo "<br/><br/>";
//UPDATE UNCLEARED BILL AND SELECT THE LAST BILL
$select_existing_bill = mysqli_query($conn, "SELECT Patient_Bill_ID from tbl_patient_bill where
Registration_ID = '$Registration_ID'  and Status='active'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_existing_bill) > 1){
    $select_existing_bill2 = mysqli_query($conn, "SELECT Patient_Bill_ID from tbl_patient_bill where
    Registration_ID = '$Registration_ID'  and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
    $Patient_Bill_ID_max=mysqli_fetch_assoc($select_existing_bill2)['Patient_Bill_ID'];
    while($row1=mysqli_fetch_array($select_existing_bill)){
        $Patient_Bill_ID_min=$row1['Patient_Bill_ID'];
        $updated_bill_ID=mysqli_query($conn,"UPDATE tbl_patient_payments set Patient_Bill_ID='$Patient_Bill_ID_max' where Patient_Bill_ID='$Patient_Bill_ID_min' and Patient_Bill_ID <> '$Patient_Bill_ID_max'");
        if($updated_bill_ID){
            $updated_bill_ID=mysqli_query($conn,"UPDATE tbl_patient_bill set Status='cleared' where Patient_Bill_ID='$Patient_Bill_ID_min'  and Patient_Bill_ID <> '$Patient_Bill_ID_max'");
            // echo "1";
        }
    }
    $update_admission = mysqli_query($conn, "UPDATE tbl_admission set Patient_Bill_ID='$Patient_Bill_ID_max' where Admision_ID='$Admision_ID' ") or die(mysqli_error($conn));
    if($update_admission){
        // echo "2";
    }
    
}
//get last Patient_Bill_ID
$select = mysqli_query($conn, "SELECT Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' and 	Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Bill_ID = $data['Patient_Bill_ID'];
        $Folio_Number = $data['Folio_Number'];
    }
} else {
    $Patient_Bill_ID = 0;
    $Folio_Number = 0;
}
if ($Folio_Number == "") {
    $Folio_Number = 0;
}

//check if patient has been admited to mortuary
$morgueDetails = mysqli_query($conn, "SELECT ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num = mysqli_num_rows($morgueDetails);
if ($num > 0) {
    $Patient_Status = "mortuary";
    $filter_p_status = "Patient_Status='mortuary'";
} else {
    $Patient_Status = "Inpatient";
    $filter_p_status = "(Patient_Status='Inpatient' OR Patient_Status='Outpatient')";
}
$credit_bill_trans = "";
$cash_bill_trans = "";
//test if pateint have credit and cash transaction
$sql_check_credit_bill_result = mysqli_query($conn, "SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_credit_bill_result) > 0) {
    $credit_bill_trans = "ipo";
}
$sql_check_cash_bill_result = mysqli_query($conn, "SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Inpatient Cash') AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_cash_bill_result) > 0) {
    $cash_bill_trans = "ipo";
}

if ($cash_bill_trans == "ipo" && $credit_bill_trans == "ipo") {

    $check_if_all_bill_creared_result = mysqli_query($conn, "SELECT Cash_Bill_Status,Credit_Bill_Status FROM tbl_admission WHERE Cash_Bill_Status='cleared' && Credit_Bill_Status='cleared' AND Admision_ID='$Admision_ID'") or die(mysqli_error($conn));

    if (mysqli_num_rows($check_if_all_bill_creared_result) > 0) {
        //do nothing the bill is cleared

    } else {
        //make the bill id active
        //get the last bill id

        mysqli_query($conn, "UPDATE tbl_patient_bill SET Status='active' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
    }
}


//check if admission has bill id
$sql_get_bill_id_from_admission_sql_result = mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID' AND Patient_Bill_ID<>'0'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_get_bill_id_from_admission_sql_result) > 0) {
    $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_from_admission_sql_result)[''];
} else {
    $filter_active_or_cleared = "";
    if (isset($_GET['Status']) && $_GET['Status'] == "cld") {
        $filter_active_or_cleared = "AND Status='cleared'";
    } else {
        $filter_active_or_cleared = "AND Status='active'";
    }
    //get the last bill id
    $select = mysqli_query($conn, "select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' $filter_active_or_cleared order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
    $nums = mysqli_num_rows($select);
    if ($nums > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Patient_Bill_ID = $row['Patient_Bill_ID'];
        }
    } else {
        //insert data to tbl_patient_bill
        $insert = mysqli_query($conn, "INSERT INTO tbl_patient_bill(Registration_ID,Date_Time,Patient_Status) VALUES ('$Registration_ID',(select now()),'$Patient_Status')") or die(mysqli_error($conn));
        if ($insert) {
            $Patient_Bill_ID = mysqli_insert_id($conn);
        }
    }
    mysqli_query($conn, "UPDATE tbl_admission SET Patient_Bill_ID='$Patient_Bill_ID' WHERE Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
}

$sql_get_bill_id_from_admission_sql_result = mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID' AND Patient_Bill_ID<>'0'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_get_bill_id_from_admission_sql_result) > 0) {
    $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_from_admission_sql_result)['Patient_Bill_ID'];
}
//fix missing billing items
//
if (isset($_GET['Status']) && $_GET['Status'] == "cld") {
} else {
    mysqli_query($conn, "UPDATE tbl_patient_payments SET Patient_Bill_ID='$Patient_Bill_ID' WHERE Registration_ID='$Registration_ID' AND Payment_Date_And_Time>='$Admission_Date_Time'") or die(mysqli_error($conn));
}
// echo $Patient_Bill_ID;
// exit;
//select diagnosis details outpatient
$diagnosis = "";
$Consultant_Name = "";
$Consultation_ID = '';
$NoOfHour = "";
$Hour = "";

$select_con = mysqli_query($conn, "SELECT c.Consultation_ID,d.disease_code, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
    	FROM tbl_consultation c,tbl_disease_consultation dc, tbl_disease d
    	WHERE c.Consultation_ID=dc.Consultation_ID AND d.Disease_ID = dc.Disease_ID
    	AND dc.diagnosis_type = 'diagnosis'
    	AND c.Patient_Payment_Item_List_ID IN (
    	    SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
    		ppl.Patient_Payment_ID = pp.Patient_Payment_ID and

    		pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn));
$no_of_rows = mysqli_num_rows($select_con);
if ($no_of_rows > 0) {
    while ($diagnosis_row = mysqli_fetch_array($select_con)) {
        $diagnosis .= $diagnosis_row['disease_code'] . "; ";
        $Consultant_Name = $diagnosis_row['Consultant_Name'] . "; ";
    }
}

$Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'"))['consultation_ID'];
$select_con1 = mysqli_query($conn, "SELECT d.disease_code, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = w.Employee_ID) as Consultant_Name
    	FROM tbl_ward_round w,tbl_ward_round_disease dw, tbl_disease d
    	WHERE w.Round_ID=dw.Round_ID AND d.Disease_ID = dw.Disease_ID
    	AND dw.diagnosis_type = 'diagnosis'
        AND w.Process_Status = 'served'
    	AND w.Consultation_ID ='$Consultation_ID' ") or die(mysqli_error($conn));

$no_of_rows1 = mysqli_num_rows($select_con1);
if ($no_of_rows1 > 0) {
    while ($diagnosis_row1 = mysqli_fetch_array($select_con1)) {
        $diagnosis .= $diagnosis_row1['disease_code'] . "; ";
        $Consultant_Name .= $diagnosis_row1['Consultant_Name'] . "; ";
    }
}
?>

<?php // if ($Patient_Status=="mortuary") {
?><fieldset>
    <table width="100%">
        <tr>
            <td width="25%"><b>Patient Name &nbsp;&nbsp;&nbsp;</b><?php echo ucwords(strtolower($Patient_Name)); ?></td>
            <td width="25%"><b>Patient Number &nbsp;&nbsp;&nbsp;</b><?php echo $Registration_ID; ?></td>
            <td width="20%"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b><?php echo strtoupper($Guarantor_Name); ?></td>
            <td width="15%"><b>Member Number &nbsp;&nbsp;&nbsp;</b><?php echo $Member_Number; ?></td>
            <td width="15%"><b>&nbsp;&nbsp;&nbsp;Folio Number &nbsp;&nbsp;&nbsp;</b><?php echo $Folio_Number; ?></td>
        </tr>
        <tr>
            <td><b>Gender &nbsp;&nbsp;&nbsp;</b><?php echo $Gender; ?></td>
            <td><b>Admitted By &nbsp;&nbsp;&nbsp;</b><?php echo $Employee_Name; ?></td>
            <td><b>Age : &nbsp;&nbsp;&nbsp;</b><?php echo $age; ?></td>
            <td><b>Admission Date &nbsp;&nbsp;&nbsp;</b><?php echo $Admission_Date_Time; ?></td>
            <td colspan="2"><b>Ward & Room Number&nbsp;&nbsp;&nbsp;</b><?php echo $Hospital_Ward_Name . ' ~ ' . $Bed_Name; ?></td>
        </tr>
        <tr>
            <td><b>Disease code &nbsp;&nbsp;&nbsp;</b><?php echo $diagnosis; ?></td>

            <td><b>Consultant &nbsp;&nbsp;&nbsp;</b><?php echo $Consultant_Name; ?></td>
            <td><b>Admission Id</b>&nbsp;&nbsp;&nbsp;<?= $Admision_ID ?></td>
            <td><b>NO. DAYS</b>&nbsp;&nbsp;&nbsp;<?= $sikuzakulala  ?></td>

            <?php 
                if($Admission_Status =='Discharged'){
                    echo "<td>$Discharge_Date_Time</td>";
                }
            ?>
            <!--<b>MAITI INAYOLALA AU ISIYOLALA</b>&nbsp;&nbsp;&nbsp; YONGO CODING FROM GPITG-->
            <?php $morgueDetails = mysqli_query($conn, "SELECT inalala_bilakulala, ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
            $num = mysqli_num_rows($morgueDetails);
            if ($num > 0) { ?>
                <td><b>NO.HOURS IN MORTUARY:</b>&nbsp;&nbsp;&nbsp;<b><?PHP

                                                                        $select_hour = "SELECT TIMESTAMPDIFF(HOUR,Admission_Date_Time,NOW()) AS NoOfHour FROM tbl_admission WHERE Admision_ID='$Admision_ID' ";
                                                                        $result = mysqli_query($conn, $select_hour) or die(mysqli_error($conn));
                                                                        if (mysqli_num_rows($result) > 0) {
                                                                            $NoOfHour = mysqli_fetch_assoc($result)['NoOfHour'];
                                                                        }
                                                                        $NoOfHour;
                                                                        echo " $NoOfHour ";
                                                                        echo 'Hrs';


                                                                        http://localhost/ehmskcmc/files/Sort_Mode_Summary_Display.php?Patient_Bill_ID=39118&Folio_Number=0&Sponsor_ID=29793&Check_In_ID=34726&Receipt_Mode=Group_By_Receipt&Transaction_Type=Cash_Bill_Details&Registration_ID=16556&Admision_ID=188
                                                                        ?></b></td>
        </tr><?php  }
            if ($Patient_Status == "mortuary") {

                while ($data =  mysqli_fetch_array($morgueDetails)) {
                    $inalala_bilakulala = $data['inalala_bilakulala'];
                }
                ?>
        <!--<font color="red">This is some text!</font>-->
        <td width='13%' style='text-align:right;'>
            <font color="red">CHANGE STATUS&nbsp;:&nbsp;MWILI UNALALA AU HAULALI? : </font>
        </td>
        <td width='16%'>
            <select name='inalala_bilakulala' id='inalala_id' style='width:100%;padding: 5px' onchange="update_morgue_condition();">
                <option value="inalala" <?php if ($inalala_bilakulala == 'inalala') {
                                            echo 'selected';
                                        } ?>>Maiti inayolala</option>
                <option value="bilakulala" <?php if ($inalala_bilakulala == 'bilakulala') {
                                                echo 'selected';
                                            } ?>>Maiti inayochukuliwa bila kulala</option>
            </select>
        </td>

        </td>
    <?php } ?>
    </table>
</fieldset>


<fieldset>


    <?php
    //kuntapest juu ya copy one hence copy5
    $item_name = "";
    $price = 0;
    $ageFrom = "";
    $ageTO = "";
    $count = 1;
    $count2 = 1;
    $charges_duration = 1;

    $user_sponsor_id = $Sponsor_ID;
    #==================CHECKING FROM MORGUE, DONE BY FULL STACK DEVELOPERS===================

    $morgueDetails=mysqli_query($conn,"SELECT ma.inalala_bilakulala,ma.admitted_from,ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
    $num=mysqli_num_rows($morgueDetails);
    if ($num > 0) {
        $morge_details_row=mysqli_fetch_assoc($morgueDetails);
         $admitted_from=$morge_details_row['admitted_from'];
         $inalala_bilakulala=$morge_details_row['inalala_bilakulala'];
        ?>
        <!--copy4kunta-->
    <table width="100%">
            <tr>
                <td width="70%">
                    <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
                        <legend>
                        CASH BILL DETAILS
                        </legend>
                        <table width='100%' class="cash_credit_bill_tbl">
                          
                                   <tr>
                                        <td width="4%">SN</td>
                                        <td>ITEM NAME</td>
                                        <td style="text-align: right;">AGE FROM</td>
                                        <td style="text-align: right;">AGE TO</td>
                                        <td style="text-align: right;">AGE STATUS</td>
                                        <td width="10%" style="text-align: center;">ADMITTED FROM</td>
                                        <td width="10%" style="text-align: center;">Inalala_Bilakulala</td>
                                        <td width="10%" style="text-align: center;">CHARGED HOURS</td>
                                        <td width="10%" style="text-align: center;">PRICE</td>
                                        <td width="10%" style="text-align: center;">KEEPING <br> DAYS</td>
                                        <td width="10%" style="text-align: right;">SUB TOTAL</td>
                                    </tr>    
        <?php
     $Payment_Method="cash";
     //============OVERIDDING SPONSOR TO CASH=====================
     //copppiyy finalcode
     $morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death,ma.Date_In, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
    $num=mysqli_num_rows($morgueDetails);
    if ($num > 0) {
     $Payment_Method="cash";
     //============OVERIDDING SPONSOR TO CASH=====================
     $Sponsor_ID=mysqli_fetch_array(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE payment_method='$Payment_Method' ORDER BY Sponsor_ID ASC LIMIT 1"))['Sponsor_ID'];
     // echo $Sponsor_ID;
     // exit;
         $Folio_Number =0;
    //	 include("./includes/Get_Patient_Transaction_Number.php");	
        
    
    while($data =  mysqli_fetch_array($morgueDetails)){
        $case_type=$data['case_type'];
            $date_death=$data['Date_Of_Death'];
        $Admission_Date_Time=$data['Admission_Date_Time'];
            $$Date_In=$data['Date_In'];
    //        echo "<br/>";
    //	echo $Date_Of_Birth;
    //	 $Date_Of_Birth = strtotime($Date_Of_Birth);
        $date_death= strtotime($date_death);
        $date_death = date("Y-m-d",$date_death);
        
        $date1 = new DateTime($date_death);
        $date2 = new DateTime($Date_Of_Birth);
         $diff = $date1->diff($date2);
        $year = $diff->y;
        $month= $diff->m;
        $days= $diff->d ;
        //echo $month ." ".$days;
        $admission_date= strtotime($Admission_Date_Time);
        $Admission_Date_Time = date("Y-m-d",$admission_date );
        $admission_date1 = new DateTime($Admission_Date_Time);
        $leo = new DateTime($Today);
        $diff2 = $leo->diff($admission_date1)->format("%a");
    
        
    }
    }       
    $date_status="";
     $year=(int)$year;
     $month=(int)$month;
     $days=(int)$days;
     $grand_total_mortuary_prc=0;
     $NoOfHour=(int)$NoOfHour;
     //$Hour=(int)$Hour;
     $charges_duration=(int)$charges_duration;
     
     
    if($year!=0){
         
         $date_status="years";
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //     AND inalala_bilakulala='$inalala_bilakulala'MICHAEL YONGO CODING FROM GPITG-
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,mp.charges_duration,mp.admitted_from,mp.inalala_bilakulala,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$year' AND ageTO>='$year' AND date_status='$date_status' AND admitted_from='$admitted_from'  AND inalala_bilakulala='$inalala_bilakulala' AND enabled_disabled='enabled'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {     //echo $NoOfHour.' = '.$charges_duration.'<br>';
            $charges_duration=$mochwari_data['charges_duration'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
            if($NoOfHour>=$charges_duration ){
             $date_status=$mochwari_data['date_status'];
             $item_name=$mochwari_data['Product_Name'];
             $price=$mochwari_data['price'];
             $ageFrom=$mochwari_data['ageFrom'];
             $ageTO=$mochwari_data['ageTO'];
             $charges_duration=$mochwari_data['charges_duration'];
             $admitted_from=$mochwari_data['admitted_from'];
             $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
    
         
           ?>
                 <tr colspan="7">
                    <td width="4%"><?=$count?></td>
                    <td><?=strtoupper($item_name)?></td>
                    <td width="10%" style="text-align: right;"><?=$ageFrom?></td>
                    <td width="10%" style="text-align: right;"><?=$ageTO?></td>
                    <td width="10%" style="text-align: right;"><?=$date_status?></td>
                    <td width="10%" style="text-align: right;"><?=$admitted_from?></td>
                    <td width="10%" style="text-align: right;"><?=$inalala_bilakulala?></td>
                    <td width="10%" style="text-align: right;"><?=$charges_duration?></td>
                    <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                    <td width="10%" style="text-align: right;"><?php if(($charges_duration)==0){$hr=1;
                        $subtotal_price_detail=($hr)*$price;
                        echo $hr; } else{echo $diff2;
                        $subtotal_price_detail=($diff2)*$price;
                        }?> </td>
                    <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                    
                </tr>
    
            <?php
            $grand_total_mortuary_prc=$grand_total_mortuary_prc+$subtotal_price_detail;
             $count++;
        
            
        }}}
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else if($year==0&&$month!=0){
        $date_status="months";
        ///////////////////////////////////////////////////////////////////////////MICHAEL YONGO CODING FROM GPITG-//////////////////////////////////
       
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID,charges_duration,admitted_from,inalala_bilakulala FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$month' AND ageTO>='$month' AND date_status='$date_status'AND admitted_from='$admitted_from' AND inalala_bilakulala='$inalala_bilakulala' AND enabled_disabled='enabled'");
         mysqli_num_rows($sql_mortuary_item);
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
                    $charges_duration=$mochwari_data['charges_duration'];
            if($NoOfHour>=$charges_duration ){
         //$item_id=$mochwari_data['item_id'];
            $date_status=$mochwari_data['date_status'];
            $item_name=$mochwari_data['Product_Name'];
            $price=$mochwari_data['price'];
            $ageFrom=$mochwari_data['ageFrom'];
            $ageTO=$mochwari_data['ageTO'];
            $charges_duration=$mochwari_data['charges_duration'];
            $admitted_from=$mochwari_data['admitted_from'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
           //$subtotal_price_detail=($diff2)+1*$price;
           ?>
                 <tr colspan="7">
                    <td width="4%"><?=$count?></td>
                    <td><?=strtoupper($item_name)?></td>
                    <td width="10%" style="text-align: right;"><?=$ageFrom?></td>
                    <td width="10%" style="text-align: right;"><?=$ageTO?></td>
                    <td width="10%" style="text-align: right;"><?=$date_status?></td>
                    <td width="10%" style="text-align: right;"><?=$admitted_from?></td>
                    <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                        <td width="10%" style="text-align: right;"><?=$charges_duration?></td>
                        <td width="10%" style="text-align: right;"><?php if(($diff2)==0){$hr=1;
                        $subtotal_price_detail=(($diff2)+$hr)*$price;//
                        echo $diff2+$hr; } else{ $subtotal_price_detail=($diff2)*$price;
                            echo $diff2; }?> </td>
                    <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                    
                </tr>
    
            <?php
            $grand_total_mortuary_prc=$grand_total_mortuary_prc+$subtotal_price_detail;
             $count++;
        }}}
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else if($year==0&&$month==0){
         $date_status="days";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID FROM,mp.charges_duration tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$days' AND ageTO>='$days' AND date_status='$date_status AND admitted_from='$admitted_from' AND enabled_disabled='enabled'");
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
                    $charges_duration=$mochwari_data['charges_duration'];
            if($NoOfHour>=$charges_duration ){
         //$item_id=$mochwari_data['item_id'];
            $date_status=$mochwari_data['date_status'];
            $item_name=$mochwari_data['Product_Name'];
            $price=$mochwari_data['price'];
            $ageFrom=$mochwari_data['ageFrom'];
            $ageTO=$mochwari_data['ageTO'];
            $charges_duration=$mochwari_data['charges_duration'];
            $admitted_from=$mochwari_data['admitted_from'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
          // $subtotal_price_detail=($diff2)*$price;
           ?>
                 <tr colspan="7">
                                        <td width="4%"><?=$count?></td>
                                        <td><?=strtoupper($item_name)?></td>
                                        <td width="10%" style="text-align: right;"><?=$ageFrom?></td>
                                        <td width="10%" style="text-align: right;"><?=$ageTO?></td>
                                        <td width="10%" style="text-align: right;"><?=$date_status?></td>
                                        <td width="10%" style="text-align: right;"><?=$admitted_from?></td>
                                        <td width="10%" style="text-align: right;"><?=$inalala_bilakulala?></td>
                                        <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                                         <td width="10%" style="text-align: right;"><?=$charges_duration?></td>
                                          <td width="10%" style="text-align: right;"><?php if(($charges_duration)==0){$hr=1;
                                         $subtotal_price_detail=($hr)*$price;
                                         echo $hr; } else{echo $diff2;
                                         $subtotal_price_detail=($diff2)*$price;
                                         }?> </td>
                                        <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                                       
                                    </tr>
    
            <?php
            $grand_total_mortuary_prc=$grand_total_mortuary_prc+$subtotal_price_detail;
             $count++;
        }}}
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    ?>
    
                           <?php $totalmorgueprice=$subtotal_price_detail+$subtotal_price_detail;?>
                                   
                                                         
                                    <tr style="background:#DEDEDE">
                                            <td colspan="10"><b>TOTAL</b></td>
                                     
                                                                    
                                                                    <!--KUNTA CHANGES FOR  NEW JUMLA YA PRICE-->
                                                                   <?php $totalmorgueprice=array($subtotal_price_detail,$subtotal_price_detail);
                     
                                                                    ?>
                                                                    <td style="text-align:right"><b><?php echo number_format($grand_total_mortuary_prc,2)?></b>
                                     <input id="Grand_Total" type="hidden" value="<?=$grand_total_mortuary_prc?>" />
                                    </td>
    
                                    </tr>	
                                         
                                 </table>
                                <?php
                        
                                $Select_receipt = mysqli_query($conn, "SELECT pp.Patient_Payment_ID, hw.Hospital_Ward_Name, em.Employee_Name FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee em, tbl_hospital_ward hw WHERE pp.Registration_ID='$Registration_ID' AND ppl.Check_In_Type = 'Mortuary' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Pre_Paid = '1' AND hw.Hospital_Ward_ID = pp.Hospital_Ward_ID AND em.Employee_ID = pp.Employee_ID AND pp.Patient_Bill_ID = '$Patient_Bill_ID' GROUP BY pp.Patient_Payment_ID ORDER BY pp.Patient_Payment_ID DESC");
                                        if($Select_receipt > 0){
    
                                            echo '<table width="100%" style="border: 1px solid #dedede !important;">                                     
                                                    <tr style="background: #dedede;">                                         
                                                        <th colspan="9" style="text-align: left;">OTHER MORTUARY SERVICES</th>                                     
                                                    </tr>
                                                    <tr style="font-weight: bold; border: 1px solid #dedede !important;">
                                                        <td width="4%">SN</td>
                                                        <td></td>
                                                        <td width="20%" style="text-align: center;">ITEM NAME</td>
                                                        <td  width="20%" style="text-align: left;">ADDED BY</td>
                                                        <td  width="20%" style="text-align: center;">ADDED DATE</td>
                                                        <td style="text-align: left;">LOCATION</td>
                                                        <td width="10%" style="text-align: right;">PRICE</td>
                                                        <td width="10%" style="text-align: center;">QUANITY</td>
                                                        <td width="10%" style="text-align: right;">SUB TOTAL</td>
                                                    </tr>';
                                            while($details = mysqli_fetch_assoc($Select_receipt)){
                                                $Patient_Payment_ID = $details['Patient_Payment_ID'];
                                                $Hospital_Ward_Name = $details['Hospital_Ward_Name'];
                                                $Added_by = $details['Employee_Name'];
    
                                                $Select_Mortuary = mysqli_query($conn, "SELECT Patient_Payment_Item_List_ID, i.Product_Name, ppl.Transaction_Date_And_Time, ppl.Quantity, ppl.Price,  ppl.Transaction_Date_And_Time FROM tbl_patient_payment_item_list ppl, tbl_items i WHERE ppl.Patient_Payment_ID = '$Patient_Payment_ID' AND  i.Item_ID = ppl.Item_ID GROUP BY  ppl.Patient_Payment_Item_List_ID");
    
                                                        while($morgue = mysqli_fetch_assoc($Select_Mortuary)){
                                                            $Product_Name = $morgue['Product_Name'];
                                                            $Quantity = $morgue['Quantity'];
                                                            $Price = $morgue['Price'];
                                                            $Transaction_Date_And_Time = $morgue['Transaction_Date_And_Time'];
                                                            $subtotal = $Price * $Quantity;
                                                        
                                                                echo '<tr>
                                                                        <td width="4%">'.$count.'</td>
                                                                        <td><input type="checkbox" class="zero_items" id="' . $morgue['Patient_Payment_Item_List_ID'] . '" ' . $chk . '/></td>
                                                                        <td width="20%">'.strtoupper($Product_Name).'</td>
                                                                        <td  width="20%" style="text-align: left;">'.$Added_by.'</td>
                                                                        <td  width="20%" style="text-align: center;">'.$Transaction_Date_And_Time.'</td>
                                                                        <td style="text-align: left;">'.$Hospital_Ward_Name.'</td>
                                                                        <td width="10%" style="text-align: right;">'.number_format($Price,2).'</td>
                                                                        <td width="10%" style="text-align: center;">'.$Quantity.'</td>
                                                                        <td width="10%" style="text-align: right;">'.number_format($subtotal,2).'</td>
                                                                </tr>';
                                                                $count++;
    
                                                                $other_total += $subtotal;
                                                        }
    
                                                }
                                                $grand_total_mortuary_prc3 = $grand_total_mortuary_prc + $other_total;
                                            echo "<tr style='background: #dedede;'>
                                                        <th colspan='8' style='text-align: left;'>GRAND TOTAL</th>
                                                        <th style='text-align: right;'>".number_format($grand_total_mortuary_prc3,2)."</th>
                                                </tr>";
                                            echo "</table>";
                                        }

                        //================== END OF OTHER CHARGERS MORTUARY ============
                                            ?>
                    </fieldset>
                </td>
                <td id='Transaction_Summary_Area'>
                    <fieldset style='overflow-y: scroll; height: 340px; background-color: white;'>
                        <legend> 
                        BILL SUMMARY
                        </legend>
                        
    
                       <table width="100%">
                                             <tr><td colspan='7'></td></tr>
                                    <tr>
                                        
                                        <td width="4%">SN</td>
                                        <td width="40%">ITEM NAME</td>
                                         <td width="40%" style="text-align: center;">PRICE</td>
                                        <td width="40%" style="text-align: left;">DAYS</td>
                                        <td width="40%" style="text-align: right;">SUB TOTAL</td>
                                    </tr>
                                 <?php  //=======================FOR HOSPITAL CASE==============================
    //if($case_type=="hospital"){
    //=======================UNDER ONE MONTH====================================?>
                                    
                                    
                                    <!--sSTART GKC CHIEF CODE HERE-->
                                  
                                        <?PHP
                                        $grand_total_mortuary_prc_summary=0;
                                        if($year!=0){
                                            
         $date_status="years";
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //     AND inalala_bilakulala='$inalala_bilakulala'MICHAEL YONGO CODING FROM GPITG-
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,mp.charges_duration,mp.admitted_from,mp.inalala_bilakulala,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$year' AND ageTO>='$year' AND date_status='$date_status' AND admitted_from='$admitted_from'  AND inalala_bilakulala='$inalala_bilakulala' AND enabled_disabled='enabled'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {     //echo $NoOfHour.' = '.$charges_duration.'<br>';
            $charges_duration=$mochwari_data['charges_duration'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
            if($NoOfHour>=$charges_duration ){
                //if($NoOfHour>24 && $inalala_bilakulala='inalala'){
                    
         //$item_id=$mochwari_data['item_id'];
             $date_status=$mochwari_data['date_status'];
             $item_name=$mochwari_data['Product_Name'];
             $price=$mochwari_data['price'];
             $ageFrom=$mochwari_data['ageFrom'];
             $ageTO=$mochwari_data['ageTO'];
             $charges_duration=$mochwari_data['charges_duration'];
             $admitted_from=$mochwari_data['admitted_from'];
             $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
             
          // $subtotal_price_detail=(($diff2)+1)*$price;
    //        }
     
            
            //else if ()
          //echo $date_status;
        //kunta katisha yongomsater code // echo $charges_duration;
          //echo $$admitted_from;
       
           ?>
                 <tr colspan="7">      
                                        <td width="4%"><?=$count2?></td>
                                        <td><?=strtoupper($item_name)?></td>
               
                                        <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                                             <td width="10%" style="text-align: right;">
                                        <?php 
                                        
                                        if(($charges_duration)==0){
                                            $hr=1;
                                            $subtotal_price_detail=($hr)*$price;
                                            echo $hr; 
                                        }else{
                                            echo $diff2;
                                            $subtotal_price_detail=($diff2)*$price;
                                        }

                                         ?> 
                                         </td>
                                        <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                                       
                                    </tr>
    
         <?php
         $grand_total_mortuary_prc_summary=$grand_total_mortuary_prc_summary+$subtotal_price_detail;
             $count2++;
        }}}
       
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    
    else if($year==0&&$month!=0){
       $date_status="months";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
       
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID,charges_duration,admitted_from,inalala_bilakulala FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$month' AND ageTO>='$month' AND date_status='$date_status'AND admitted_from='$admitted_from' AND inalala_bilakulala='$inalala_bilakulala' AND enabled_disabled='enabled'");
         mysqli_num_rows($sql_mortuary_item);
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
                    $charges_duration=$mochwari_data['charges_duration'];
            if($NoOfHour>=$charges_duration ){
         //$item_id=$mochwari_data['item_id'];
            $date_status=$mochwari_data['date_status'];
            $item_name=$mochwari_data['Product_Name'];
            $price=$mochwari_data['price'];
            $ageFrom=$mochwari_data['ageFrom'];
            $ageTO=$mochwari_data['ageTO'];
            $charges_duration=$mochwari_data['charges_duration'];
            $admitted_from=$mochwari_data['admitted_from'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
           //$subtotal_price_detail=($diff2)+1*$price;
           ?>
                 <tr colspan="7">
                                       <td width="4%"><?=$count2?></td>
                                        <td><?=strtoupper($item_name)?></td>
                                    
                                        <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                                          <td width="10%" style="text-align: right;"><?php if(($diff2)==0){$hr=1;
                                         $subtotal_price_detail=(($diff2)+$hr)*$price;//
                                         echo $diff2+$hr; } else{ $subtotal_price_detail=($diff2)*$price;
                                             echo $diff2; }?> </td>
                                        <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                                       
                                    </tr>
    
            <?php
           echo $grand_total_mortuary_prc_summary=$grand_total_mortuary_prc_summary+$subtotal_price_detail;
             $count2++;
        }}}
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else if($year==0&&$month==0){
        $date_status="days";
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID FROM,mp.charges_duration tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$days' AND ageTO>='$days' AND date_status='$date_status AND admitted_from='$admitted_from' AND enabled_disabled='enabled'");
        if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
                    $charges_duration=$mochwari_data['charges_duration'];
            if($NoOfHour>=$charges_duration ){
         //$item_id=$mochwari_data['item_id'];
            $date_status=$mochwari_data['date_status'];
            $item_name=$mochwari_data['Product_Name'];
            $price=$mochwari_data['price'];
            $ageFrom=$mochwari_data['ageFrom'];
            $ageTO=$mochwari_data['ageTO'];
            $charges_duration=$mochwari_data['charges_duration'];
            $admitted_from=$mochwari_data['admitted_from'];
            $inalala_bilakulala=$mochwari_data['inalala_bilakulala'];
          // $subtotal_price_detail=($diff2)*$price;
           ?>
                 <tr colspan="7">
                                       <td width="4%"><?=$count2?></td>
                                        <td><?=strtoupper($item_name)?></td>
                                       
                                        <td width="10%" style="text-align: right;"><?=number_format($price,2)?></td>
                                            <td width=sql_mortuary_item"10%" style="text-align: right;"><?php if(($diff2)==0){$hr=1;
                                         $subtotal_price_detail=(($diff2)+$hr)*$price;//
                                         echo $diff2+$hr; } else{ $subtotal_price_detail=($diff2)*$price;
                                             echo $diff2; }?> </td>
                                        <td width="10%" style="text-align: right;"><?=number_format($subtotal_price_detail,2)?></td>
                                       
                                    </tr>
    
            <?php
            $grand_total_mortuary_prc_summary=$grand_total_mortuary_prc_summary+$subtotal_price_detail;
             $count2++;
        }}}
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
                                         }
    ?>
    
                
                                 <!--kuntacopy geoge code -->
                                     <?php
    
    if($year<1 && (($month == 1 && $days == 0) || $month < 1)){
    
        $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND $days >=ageFrom AND $days<=ageTO AND date_status='days'");
      if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
         //$item_id=$mochwari_data['item_id'];
             $date_status=$mochwari_data['date_status'];
        $item_name=$mochwari_data['Product_Name']."<br>";
        $price=$mochwari_data['price']."<br>";
        $ageFrom=$mochwari_data['ageFrom']."<br>";
        $ageTO=$mochwari_data['ageTO']."<br>";
      }}
      else{
          //echo "hamna data";
          $item_name=$mochwari_data[' '];
         $price=$mochwari_data[' '];
         $ageFrom=$mochwari_data[' '];
         $ageTO=$mochwari_data[' '];
          
      }
      
    // echo $year. " ".$month." ".$days;
    }
    else if(($year < 5  && $year > 0) || ($year == 0 && $month >= 1 && $days > 0)){
    //===========================UNDER 5 YEARS, OVER 1 MONTH==================================
    $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND $month>=ageFrom AND $month<=ageTO AND date_status='months'");
      if(mysqli_num_rows($sql_mortuary_item)>0){
        while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
         //$item_id=$mochwari_data['item_id'];
         $item_name=$mochwari_data['Product_Name'];
         $price=$mochwari_data['price'];
         $ageFrom=$mochwari_data['ageFrom'];
         $ageTO=$mochwari_data['ageTO'];
      }}
      else{
         // echo"hamna data2";  
         $item_name=$mochwari_data[' '];
         $price=$mochwari_data[' '];
         $ageFrom=$mochwari_data[' '];
         $ageTO=$mochwari_data[' '];
    // echo $year. " ".$month." ".$days;	
    }
    
      }else if($year>=5){
    //=========================OVER 5 YEARS=========================================
        // echo $year. " ".$month." ".$days;
    $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND $year>=ageFrom AND $year<=ageTO AND date_status='years'");
      if(mysqli_num_rows($sql_mortuary_item)>0){
        
          $grand_total_mortuary_prc_summary_1=0;
          while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
        {
         //$item_id=$mochwari_data['item_id'];
            $date_status=$mochwari_data['date_status'];
            $item_name=$mochwari_data['Product_Name']."<br>";
            $price=$mochwari_data['price']."<br>";
            $ageFrom=$mochwari_data['ageFrom']."<br>";
            $ageTO=$mochwari_data['ageTO']."<br>";
            
            //if(($diff2)!=0){$hr=1;
                
           // $subtotal_price_detail=(($diff2)+($hr))*$price;
           //;
            //}
            //else{
                
             $subtotal_price_detail=($diff2)*$price;
             
            //}
    /*new formula kunta*/
             
            ?>
                                     
                <!--MICHAEL YONGO CODING FROM GPITG- nimeondoa display ya bill summary ya zamani -->
                
    <?php
    $grand_total_mortuary_prc_summary_1=$grand_total_mortuary_prc_summary_1+$subtotal_price_detail;
     $count2++;
      }
      
      $morguePrices=$subtotal_price_detail + $subtotal_price_detail;
      $diff2=($diff2==0?"Within 24 hrs":$diff2=$diff2);
        }
      else{
           echo"hakuna data3";
          $item_name=$mochwari_data[' '];
         $price=$mochwari_data[' '];
         $ageFrom=$mochwari_data[' '];
         $ageTO=$mochwari_data[' '];
    // echo $year. " ".$month." ".$days;			
    }	
    } else{
    //===============================OUTSIDE CASE===================================================
        echo ("not working hiphop hailipi?");
    }
    
    
    ?>
        
    
                           <?php
                           if($other_total > 0){
                                echo "<tr>
                                <td>".$count2."</td>
                                <td colspan='3'>OTHER MORTUARY COST</td>
                                <td style='text-align: right;'>".number_format($other_total,2)."</td></tr>";
                           }
    
                           
                           $totalmorgueprice=$subtotal_price_detail+$subtotal_price_detail;
                           
                           $grand_total_mortuary_prc_summary = $grand_total_mortuary_prc_summary + $other_total; ?>
    
    
                        <tr><td colspan="5"><hr></td></tr>								
                                    <tr>
                                    <td><b>TOTAL</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                       
                                    <td style="text-align:right"><b><?=number_format($grand_total_mortuary_prc_summary,2)?></b>
                                     <input id="Grand_Total" type="hidden" value="<?=$grand_total_mortuary_prc_summary?>" />
                                    </td>
    <?php //echo $grand_total_mortuary_prc_summary."hapa"?>
                                    </tr>	
     <tr><td colspan="5"><hr></td></tr>
                             <tr>
                                <td colspan="4" width="65%"><b>Cash Deposit</b></td>
                                <td style="text-align: right;">
                                    <?php
                                    $Grand_Total_Direct_Cash = 0;
                                    if (strtolower($Payment_Method) == 'cash') {
                                        //calculate cash payments
                                        $cal = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount from         tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i, tbl_item_subcategory isc, tbl_item_category ic where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID AND pp.Transaction_type = 'Direct cash' and      pp.Transaction_status <> 'cancelled' and
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Patient_Bill_ID = '$Patient_Bill_ID' and         ppl.Item_ID=i.Item_ID and  i.Visible_Status='Others' and  pp.Registration_ID = '$Registration_ID' AND Item_Category_Name='Mortuary'") or die(mysqli_error($conn));
                                        $nms = mysqli_num_rows($cal);
                                        // echo $Patient_Bill_ID." ".$Folio_Number;
                                        // exit;
                                        if ($nms > 0) {
                                            while ($cls = mysqli_fetch_array($cal)) {
                                                $Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                                            }
                                        }
                                        echo number_format($Grand_Total_Direct_Cash,2);
                                    } else {
                                        echo "<i>(not applicable)</i>";
                                    }
                                    $Temp_Balance = ($grand_total_mortuary_prc_summary - $Grand_Total_Direct_Cash);
                                    ?>&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <tr><td colspan="5"><hr></td></tr>
                            <tr>
                                <td colspan="4"><b>Balance </b></td>
                                <td style="text-align: right;">
                                    <?php
                                    if ($Temp_Balance > 0) {
                                        echo number_format($Temp_Balance,2);
                                    } else {
                                        echo 0;
                                    }
                                    ?>&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <?php
                            if ($Temp_Balance < 0) {
                                ?>
                                <tr>
                                    <td colspan="4"><b>Refund Amount</b></td><td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr><td colspan="5"><hr></td></tr>
                        </table>
                    </fieldset><br/>
                    <table width="100%">
                        <tr>
                            <td><input id="Transaction_Type" type="hidden" value="Cash_Bill_Details" />
                                <input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                            </td>
                            <td>
                                <?php
                                    if ($Cash_Bill_Status == 'pending') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                        <?php
                                    } else if ($Cash_Bill_Status == 'approved') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL" onclick="Approve_Patient_Bill_Warning()">
                                        <?php
                                    }
                                
                                ?>
                            </td>
                        </tr>
    <tr><td id="Details_Area"></td></tr>
                    </table>
                </td>
            </tr>
    
        </table>
        <?php if ($canZero) { ?>
                <input type='button' onclick="confirmZering()" class="art-button-green" value="Zero Item(s) Prices" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i style="font-size:15px; color:red">~~ Use checkboxes to set the item price to zero ~~</i>
                <!-- <span style="color: #0079AE;"><b><i>* * * CLICK RECEIPT NUMBER FOR CANCELLING OR EDITING OPTION * * *</i></b></span> -->
            <?php } ?>
    </fieldset>

    
    <!--//kuntacodefinalcodingcopy-->    
<?php } else {
?>
    <!-- =============================END OF MORGUE SECTION= BY GEORGE CHIFIE ===========NEW VERSION BY MICHAEL YONGO CODING FROM GPITG-================================= -->
    <?php
        //////////////////////****************************************check for automatic adding of items************************************
        //select all item with auto add status
        //get branch id
        if (isset($_SESSION['userinfo']['Branch_ID'])) {
            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        } else {
            $Branch_ID = 0;
        }
        if (strtolower($Payment_Method) == 'cash') {
            $Billing_Type = "Inpatient Cash";
        } else {
            $Billing_Type = "Inpatient Credit";
        }
        //============ADD Accomdation Charges  =============
        // $sql_select_all_auto_add_item_result = mysqli_query($conn, "SELECT it.Item_ID,ip.Items_Price FROM tbl_items it,tbl_item_price ip WHERE it.Item_ID=ip.Item_ID AND daily_add_automaticaly_in_inpatient_bill='yes' AND ip.Sponsor_ID='$Sponsor_ID' AND Items_Price<>'0'") or die(mysqli_error($conn));
        // if (mysqli_num_rows($sql_select_all_auto_add_item_result) > 0) {
        //     $sql_select_ward_id_result = mysqli_query($conn, "SELECT Hospital_Ward_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
        //     if (mysqli_num_rows($sql_select_ward_id_result) > 0) {
        //         $ward_id_row = mysqli_fetch_assoc($sql_select_ward_id_result);
        //         $Hospital_Ward_ID = $ward_id_row['Hospital_Ward_ID'];
        //     }
        //     while ($item_rows = mysqli_fetch_assoc($sql_select_all_auto_add_item_result)) {
        //         $Item_ID = $item_rows['Item_ID'];
        //         $Items_Price = $item_rows['Items_Price'];

                //check if the item has charged today
                // $sql_check_if_auto_item_already_charged_result = mysqli_query($conn, "SELECT Item_ID,Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Item_ID='$Item_ID' AND DATE(Transaction_Date_And_Time)=CURDATE() AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time)=CURDATE())") or die(mysqli_error($conn));
                
                // $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                // $sql_check_if_auto_item_already_charged_result = mysqli_query($conn, "SELECT Item_ID,Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Item_ID='$Item_ID'  AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND Patient_Bill_ID='$Patient_Bill_ID')") or die(mysqli_error($conn));
                // if (mysqli_num_rows($sql_check_if_auto_item_already_charged_result) <= 0) {
                //     //save auto add item for current date
                //     $sql_save_auto_add_item_for_current_date_result = mysqli_query($conn, "INSERT INTO tbl_patient_payments(Registration_ID,Payment_Date_And_Time,Check_In_ID,Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,payment_type,branch_id,Patient_Bill_ID,Folio_Number, Supervisor_ID, Employee_ID) VALUES('$Registration_ID',NOW(),'$Check_In_ID','$Sponsor_ID','$Sponsor_Name','$Billing_Type',CURDATE(),'post','$Branch_ID','$Patient_Bill_ID','$Folio_Number', '$Employee_ID', '$Employee_ID')") or die(mysqli_error($conn));
                //     $Patient_Payment_ID_1 = mysqli_insert_id($conn);
                //     $Sub_Department_ID = $_SESSION['Admission_Sub_Department_ID'];
                //     if (isset($_SESSION['Admission_Sub_Department_ID'])) {
                //         $bill_working_department = $_SESSION['bill_working_department'];
                //         $sql_save_item_result = mysqli_query($conn, "INSERT into tbl_patient_payment_item_list(Check_In_Type, Item_ID, Price,    Quantity, Patient_Direction,  Patient_Payment_ID,Transaction_Date_And_Time,Hospital_Ward_ID,Sub_Department_ID,finance_department_id)  values ('Others','$Item_ID','$Items_Price',   '$sikuzakulala','others',     '$Patient_Payment_ID_1', (select now()),'$Hospital_Ward_ID','$Sub_Department_ID','$bill_working_department')") or die(mysqli_error($conn));
                //     }
                // } else {
                //     $Patient_Payment_ID = mysqli_fetch_assoc($sql_check_if_auto_item_already_charged_result)['Patient_Payment_ID'];
                //     // for st Joseph mosh accomodation should add automatic 
                //     mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Quantity='$sikuzakulala' WHERE Item_ID='$Item_ID' AND Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
                // }

        //     }
        // }

        //============END OF ADD Accomdation Charges  =============


        //============REMOVE OPD CONSULTATION CHERGE FOR NHIF eCLIM ==============
        if($Guarantor_Name =='NHIF'){
            $seleectOPDconsultation = mysqli_query($conn, "SELECT pp.Patient_Payment_ID  FROM tbl_patient_payment_item_list ppil, tbl_patient_payments pp WHERE Check_in_Type='Doctor Room' AND pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.Patient_Payment_ID = ppil.Patient_Payment_ID AND	pp.Transaction_status <> 'cancelled' AND pp.payment_type = 'post' and 	pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn)); 
            if(mysqli_num_rows($seleectOPDconsultation)>0){
                $Patient_Payment_ID = mysqli_fetch_assoc($seleectOPDconsultation)['Patient_Payment_ID'];
                $updateSql = mysqli_query($conn, "UPDATE tbl_patient_payments SET Transaction_status = 'cancelled'  WHERE Patient_Payment_ID = '$Patient_Payment_ID' ") or die(mysqli_error($conn));
            }
        }
        //============END OF REMOVE OPD CONSULTATION CHERGE FOR NHIF eCLIM =======

    ?>
    <fieldset>
        <table width="100%">
            <tr>
                <td width="70%">
                    <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
                        <legend>
                            <?php
                            if (strtolower($Payment_Method) == 'cash') {
                                echo "CASH BILL DETAILS($Patient_Bill_ID)";
                            } else {
                                echo "CREDIT BILL DETAILS($Patient_Bill_ID)";
                            }
                            ?>
                        </legend>
                        <?php
                        $Grand_Total = 0;
                        if (isset($_SESSION['Sort_Mode']) && $_SESSION['Sort_Mode'] == 'Group_By_Category') {

                            //get categories
                            if (strtolower($Payment_Method) == 'cash') {

                                $get_cat = mysqli_query($conn, "SELECT ic.Item_category_ID, ic.Item_Category_Name from 	tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID and Check_In_Type <> 'Mortuary' and 	i.Item_ID = ppl.Item_ID and 	pp.Transaction_type = 'indirect cash' and 	pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) and 	$payments_filter 	pp.Patient_Bill_ID = '$Patient_Bill_ID' and 	pp.Transaction_status <> 'cancelled'  and 	pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                            } else {
                                $get_cat = mysqli_query($conn, "select ic.Item_category_ID, ic.Item_Category_Name from 	tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID and Check_In_Type <> 'Mortuary' and 	i.Item_ID = ppl.Item_ID and 	pp.Transaction_type = 'indirect cash' and 	pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and	pp.Patient_Bill_ID = '$Patient_Bill_ID' and 	pp.Transaction_status <> 'cancelled' and pp.payment_type = 'post' AND 	pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                            }
                            $num = mysqli_num_rows($get_cat);
                            if ($num > 0) {
                                $temp_cat = 0;
                                while ($row = mysqli_fetch_array($get_cat)) {
                                    $Item_category_ID = $row['Item_category_ID'];
                                    echo "<table width='100%' class='cash_credit_bill_tbl'>";
                                    echo "<tr><td colspan='13'><b>" . ++$temp_cat . '. ' . strtoupper($row['Item_Category_Name']) . "</b></td></tr>";
                        ?>
            <tr>
                <td width="4%">SN</td>
                <?php echo ($canZero) ? '<td width="4%">&nbsp;</td>' : '' ?>
                <td>ITEM NAME</td>
                <td width="10%" style="text-align: center;">RECEIPT#</td>
                <td width="10%" style="text-align: right;">PRICE</td>
                <td width="10%" style="text-align: right;">DISCOUNT</td>
                <td width="10%" style="text-align: left;">ADDED DATE</td>
                <td width="10%" style="text-align: left;">ADDED BY</td>
                <td width="10%" style="text-align: left;">WARD</td>
                <td width="10%" style="text-align: left;">STATUS</td>
                <td width="10%" style="text-align: left;">SPONSOR</td>
                <td width="10%" style="text-align: right;">QUANTITY</td>
                <td width="10%" style="text-align: right;">SUB TOTAL</td>
            </tr>
            <!-- <tr><td colspan='7'><hr></td></tr> -->
            <?php
                                    if (strtolower($Payment_Method) == 'cash') {
                                        $items = mysqli_query($conn, "select ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and Check_In_Type <> 'Mortuary' and
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) and
												$payments_filter
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID  AND 
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_category_ID = '$Item_category_ID' and

												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                    } else {
                                        $items = mysqli_query($conn, "select ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and Check_In_Type <> 'Mortuary' and 
												pp.Transaction_type = 'indirect cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.payment_type = 'post'AND 
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_category_ID = '$Item_category_ID' and

												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                    }

                                    $nm = mysqli_num_rows($items);
                                    if ($nm > 0) {
                                        $temp = 0;
                                        $Sub_Total = 0;
                                        while ($dt = mysqli_fetch_array($items)) {
                                            $Hospital_Ward_ID = $dt['Hospital_Ward_ID'];
                                            $sql_select_ward_name_result = mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'") or die(mysqli_error($conn));
                                            if (mysqli_num_rows($sql_select_ward_name_result) > 0) {
                                                $ward_name_row = mysqli_fetch_assoc($sql_select_ward_name_result);
                                                $Hospital_Ward_Name = $ward_name_row['Hospital_Ward_Name'];
                                            } else {
                                                $Hospital_Ward_Name = "";
                                            }
                                            $Sponsor_ID = $dt['Sponsor_ID'];
                                            $Billing_Type_sts = $dt['Billing_Type'];
                                            $sql_select_sponsor_result = mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                                            if (mysqli_num_rows($sql_select_sponsor_result) > 0) {
                                                $sponsor_name_row = mysqli_fetch_assoc($sql_select_sponsor_result);
                                                $spnsor_name = $sponsor_name_row['Guarantor_Name'];
                                            } else {
                                                $spnsor_name = "";
                                            }

                                            echo '<tr>
											<td width="4%">' . ++$temp . '<b>.</b></td>';
                                            $chk = '';
                                            if ($dt['Price'] == 0) {
                                                $chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
                                            }
                                            echo ($canZero) ? '<td width="4%"><input type="checkbox" class="zero_items" id="' . $dt['Patient_Payment_Item_List_ID'] . '" ' . $chk . '/></td>' : '';
                                            echo '<td><label for="' . $dt['Patient_Payment_Item_List_ID'] . '" style="display:block">' . ucwords(strtolower($dt['Product_Name'])) . '</label></td>
											<td width="10%" style="text-align: center"><label style="color: #0079AE;" onclick="View_Details(' . $dt['Patient_Payment_ID'] . ',' . $dt['Patient_Payment_Item_List_ID'] . ');"><b>' . $dt['Patient_Payment_ID'] . '</b></label></td>
										    <td width="10%" style="text-align: right">' . number_format($dt['Price']) . '</td>
											<td width="10%" style="text-align: right;">' . number_format($dt['Discount']) . '</td>
                                            <td>' . $dt['Transaction_Date_And_Time'] . '</td>
                                            <td>' . $dt['Consultant'] . '</td>
                                            <td>' . $Hospital_Ward_Name . '</td>
                                            <td>' . $Billing_Type_sts . '</td>
                                            <td>' . $spnsor_name . '</td>
											<td width="10%" style="text-align: right;">' . $dt['Quantity'] . '</td>
											<td width="10%" style="text-align: right;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</td>
										</tr>';
                                            $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                            $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                        }
                                        // echo "<tr><td colspan='12'><hr></td></tr>";
                                        echo "<tr style='background:#DEDEDE'><td colspan='12' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>" . number_format($Sub_Total) . "</b></td></tr>";
                                    }
                                    echo "</table>";
                                }
                            }
                            /////////////////////////////////////////////////////////

                        } else {
                            if (strtolower($Payment_Method) == 'cash') {
                                $get_details = mysqli_query($conn, "select pp.Patient_Bill_ID, pp.Sponsor_ID,pp.Billing_Type, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and 
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) and
												$payments_filter
												pp.Patient_Bill_ID = '$Patient_Bill_ID'
												  order by pp.Patient_Payment_ID DESC") or die(mysqli_error($conn));
                            } else {
                                $get_details = mysqli_query($conn, "select pp.Patient_Bill_ID, pp.Sponsor_ID,pp.Billing_Type, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Transaction_type = 'indirect cash' and 
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.payment_type = 'post'
												  order by pp.Patient_Payment_ID DESC") or die(mysqli_error($conn));
                            }
                            $num = mysqli_num_rows($get_details);
                            if ($num > 0) {
                                $temp_rec = 0;
                                while ($row = mysqli_fetch_array($get_details)) {
                                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                    $Billing_Type_sts = $row['Billing_Type'];
                                    $Sponsor_ID = $row['Sponsor_ID'];
                                    $sql_select_sponsor_result = mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                                    if (mysqli_num_rows($sql_select_sponsor_result) > 0) {
                                        $sponsor_name_row = mysqli_fetch_assoc($sql_select_sponsor_result);
                                        $spnsor_name = $sponsor_name_row['Guarantor_Name'];
                                    } else {
                                        $spnsor_name = "";
                                    }
                                    echo "<table width='100%' class='cash_credit_bill_tbl'>";
                                    echo "<tr><td colspan='13'><b>" . ++$temp_rec . '. Receipt Number ~ <i><label style="color: #0079AE;" onclick="View_Details(' . $row['Patient_Payment_ID'] . ',0);">' . $row['Patient_Payment_ID'] . '</label></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt Date ~ ' . $row['Payment_Date_And_Time'] . "</b></td></tr>";
            ?>
            <tr>
                <td width="4%">SN</td>
                <?php echo ($canZero) ? '<td width="4%">&nbsp;</td>' : '' ?>
                <td>ITEM NAME</td>
                <td width="10%" style="text-align: right;">PRICE</td>
                <td width="10%" style="text-align: right;">DISCOUNT</td>
                <td width="10%" style="text-align: left;">ADDED DATE</td>
                <td width="10%" style="text-align: left;">ADDED BY</td>
                <td width="10%" style="text-align: left;">WARD</td>
                <td width="10%" style="text-align: left;">STATUS</td>
                <td width="10%" style="text-align: left;">SPONSOR</td>
                <td width="10%" style="text-align: right;">QUANTITY</td>
                <td width="10%" style="text-align: right;">SUB TOTAL</td>
            </tr>
            <!--                                <tr><td colspan='12'><hr></td></tr>-->
<?php
                                    $items = mysqli_query($conn, "select ppl.Hospital_Ward_ID,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time, ppl.Price, ppl.Quantity, ppl.Discount,ppl.Patient_Payment_Item_List_ID FROM
														tbl_items i, tbl_patient_payment_item_list ppl where
														i.Item_ID = ppl.Item_ID and Check_In_Type <> 'Mortuary' and
														ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                                    $nm = mysqli_num_rows($items);
                                    if ($nm > 0) {
                                        $temp = 0;
                                        $Sub_Total = 0;
                                        while ($dt = mysqli_fetch_array($items)) {
                                            $Hospital_Ward_ID = $dt['Hospital_Ward_ID'];
                                            $sql_select_ward_name_result = mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'") or die(mysqli_error($conn));
                                            if (mysqli_num_rows($sql_select_ward_name_result) > 0) {
                                                $ward_name_row = mysqli_fetch_assoc($sql_select_ward_name_result);
                                                $Hospital_Ward_Name = $ward_name_row['Hospital_Ward_Name'];
                                            } else {
                                                $Hospital_Ward_Name = "";
                                            }
                                            echo '<tr>
											<td width="4%">' . ++$temp . '<b>.</b></td>';
                                            $chk = '';
                                            if ($dt['Price'] == 0) {
                                                $chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
                                            }
                                            echo ($canZero) ? '<td width="4%"><input type="checkbox" class="zero_items" id="' . $dt['Patient_Payment_Item_List_ID'] . '" ' . $chk . '/></td>' : '';
                                            echo '<td><label for="' . $dt['Patient_Payment_Item_List_ID'] . '" style="display:block">' . ucwords(strtolower($dt['Product_Name'])) . '</label></td>

                                                                                        <td width="10%" style="text-align: right">' . number_format($dt['Price']) . '</td>
											<td width="10%" style="text-align: right;">' . number_format($dt['Discount']) . '</td>
                                                                                        <td>' . $dt['Transaction_Date_And_Time'] . '</td>
                                                                                        <td>' . $dt['Consultant'] . '</td>
                                                                                        <td>' . $Hospital_Ward_Name . '</td>
                                                                                        <td>' . $Billing_Type_sts . '</td>
                                                                                        <td>' . $spnsor_name . '</td>
											<td width="10%" style="text-align: right;">' . $dt['Quantity'] . '</td>
											<td width="10%" style="text-align: right;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</td>
										</tr>';
                                            $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                            $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                        }
                                        //echo "<tr><td colspan='12'><hr></td></tr>";
                                        echo "<tr style='background:#DEDEDE'><td colspan='11' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>" . number_format($Sub_Total) . "</b></td></tr>";
                                    }
                                    echo "</table>";
                                }
                            }
                        }
?>
    </fieldset>
    </td>
    <td id='Transaction_Summary_Area'>
        <fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
            <legend>
                <?php
                if (strtolower($Payment_Method) == 'cash') {
                    echo "CASH BILL SUMMARY ";
                } else {
                    echo "CREDIT BILL SUMMARY";
                }
                ?>
            </legend>

            <table width="100%">
                <!-- <tr>
                                    <td width="65%"><b>Bill Type </b></td><td><?php
                                                                                if (strtolower($Payment_Method) == 'cash') {
                                                                                    echo "CASH";
                                                                                } else {
                                                                                    echo "CREDIT";
                                                                                }
                                                                                ?></td>
                            </tr> -->
                <tr>
                    <td><b>CATEGORY</b></td>
                    <td style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <?php
                if (strtolower($Payment_Method) == 'cash') {
                    $get_cate = mysqli_query($conn, "SELECT ic.Item_Category_ID, ic.Item_Category_Name from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and pp.payment_type = 'post' and
												i.Item_ID = ppl.Item_ID  and 
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and Check_In_Type <> 'Mortuary' AND
												pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) and
												$payments_filter
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and

												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                } else {
                    $get_cate = mysqli_query($conn, "SELECT ic.Item_Category_ID, ic.Item_Category_Name from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and

												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                }
                // AND Check_In_Type<>'mortuary'
                $nms_slct = mysqli_num_rows($get_cate);
                $tmp = 0;
                if ($nms_slct > 0) {
                    $cont = 0;
                    while ($dts = mysqli_fetch_array($get_cate)) {
                        $Item_Category_Name = $dts['Item_Category_Name'];
                        $Item_Category_ID = $dts['Item_Category_ID'];
                        $Category_Grand_Total = 0;

                        //calculate total
                        if (strtolower($Payment_Method) == 'cash') {
                           
                            $items = mysqli_query($conn, "SELECT ppl.Price, ppl.Quantity, ppl.Discount from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) and 
												$payments_filter
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_Category_ID = '$Item_Category_ID' and Check_In_Type <> 'Mortuary' and

												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                        } else {
                            $items = mysqli_query($conn, "SELECT ppl.Price, ppl.Quantity, ppl.Discount from
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_Category_ID = '$Item_Category_ID' and

												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                        }
                        $nums = mysqli_num_rows($items);
                        if ($nums > 0) {
                            while ($t_item = mysqli_fetch_array($items)) {
                                $Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
                            }
                        }
                        echo "<tr><td>" . ++$cont . '<b>. </b>' . ucwords(strtolower($Item_Category_Name)) . "</td><td style='text-align: right;'>" . number_format($Category_Grand_Total) . "&nbsp;&nbsp;&nbsp;</td></tr>";
                    }
                }
                ?>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td><b>Bill Status</b></td>
                    <?php
                    if (strtolower($Payment_Method) == 'cash') {
                        echo "<td style='text-align: right;'>" . ucwords(strtolower($Cash_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                    } else {
                        echo "<td style='text-align: right;'>" . ucwords(strtolower($Credit_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <td width="65%"><b>Total Amount Required </b></td>
                    <td style="text-align: right;"><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp; <input type="text" id="Grand_Total" value="<?= $Grand_Total ?>" hidden="hidden"></td>
                </tr>
                <tr>
                    <td width="65%"><b>Cash Deposit</b></td>
                    <td style="text-align: right;">
                        <?php
                        $Grand_Total_Direct_Cash = 0;
                        if (strtolower($Payment_Method) == 'cash') {
                            //calculate cash payments
                            $cal = mysqli_query($conn, "SELECT ppl.Price, ppl.Quantity, ppl.Discount from         tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i, tbl_item_subcategory isc, tbl_item_category ic where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID AND pp.Transaction_type = 'Direct cash' and      pp.Transaction_status <> 'cancelled' and
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Patient_Bill_ID = '$Patient_Bill_ID' and         ppl.Item_ID=i.Item_ID and  i.Visible_Status='Others' and  pp.Registration_ID = '$Registration_ID' AND Item_Category_Name <> 'Mortuary'") or die(mysqli_error($conn));
                            $nms = mysqli_num_rows($cal);
                            if ($nms > 0) {
                                while ($cls = mysqli_fetch_array($cal)) {
                                    $Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                                }
                            }
                            echo number_format($Grand_Total_Direct_Cash);
                        } else {
                            echo "<i>(not applicable)</i>";
                        }
                        $Temp_Balance = ($Grand_Total - $Grand_Total_Direct_Cash);
                        ?>&nbsp;&nbsp;&nbsp;
                        <input type="text" id="Grand_Total_Direct_Cash" value="<?= $Grand_Total_Direct_Cash ?>" hidden="hidden">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td><b>Balance </b></td>
                    <td style="text-align: right;">
                        <?php
                        if ($Temp_Balance > 0) {
                            echo number_format($Temp_Balance);
                        } else {
                            echo 0;
                        }
                        ?>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <?php
                if ($Temp_Balance < 0) {
                ?>
                    <tr>
                        <td><b>Refund Amount</b></td>
                        <td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
            </table>
        </fieldset><br />
        <table width="100%">
            <tr>
                <td>
                    <input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                </td>
                <td>
                    <?php
                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    $sql_check_approve_bill_privilege_result = mysqli_query($conn, "SELECT Employee_ID FROM tbl_privileges WHERE Employee_ID='$Employee_ID' AND can_have_access_to_approve_bill_btn='yes'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_check_approve_bill_privilege_result) > 0) {
                        if (strtolower($Payment_Method) == 'cash') {
                            if ($Cash_Bill_Status == 'pending') {
                    ?>
                                <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                            <?php
                            } else if ($Cash_Bill_Status == 'approved') {
                            ?>
                                <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill_Warning()">
                            <?php
                            }
                        } else {
                            if ($Credit_Bill_Status == 'pending') {
                            ?>
                                <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                            <?php
                            } else if ($Credit_Bill_Status == 'approved') {
                            ?>
                                <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill_Warning()">
                        <?php
                            }
                        }
                    } else {
                        ?>
                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL 5" onclick="alert('Access Denied!you dont have privilege to approve bill')">

                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <?php
            $sql_select_patient_sponsor_result = mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_patient_sponsor_result) > 0) {
                $sponsor_rows = mysqli_fetch_assoc($sql_select_patient_sponsor_result);
                $Sponsor_ID = $sponsor_rows['Sponsor_ID'];
            }
            ?>
            <tr>
                <td>
                    <?php if (strtolower($Payment_Method) == 'cash') { ?>
                        <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                    <?php } else { ?>
                        <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments_Warning();">

                    <?php } ?>
                </td>

            </tr>
            <tr>


                <td>
                    <a href="#" onclick="verify_if_this_user_login_to_dispencing()" id="Add_Consumable_Phamacetical" class="art-button-green"> ADD CONSUMABLE AND PHAMACETICAL</a>
                </td>
                <td>
                    <input type="button" name="Add_Item" id="Add_Item" value="ADD SERVICES" class="art-button-green" onclick="Add_More_Items(<?php echo $Patient_Bill_ID; ?>,<?php echo 1; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                </td>
            </tr>

            </tr>
        </table>
        <div id="select_clinic" style="display:none;">
            <style type="text/css">
                #spu_lgn_tbl {
                    width: 100%;
                    border: none !important;
                }

                #spu_lgn_tbl tr,
                #spu_lgn_tbl tr td {
                    border: none !important;
                    padding: 15px;
                    font-size: 14PX;
                }
            </style>
            <form action="previewpatientbilldetails.php" method="GET">
                <table id="spu_lgn_tbl" style="width:100%">
                    <!--                <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Your working Clinic
                    </td>
                    <td style="width:60%">-->
                    <input type="text" name="Check_In_ID" class="hide" value="<?php echo $Check_In_ID  ?>">
                    <!--
                    <select  style='width: 100%;height:30%'  name='bill_Clinic_ID' id='bill_Clinic_ID' value='<?php // previewpatientbilldetails.phpecho $Guarantor_Name;
                                                                                                                ?>'  required='required'>
                            <option selected='selected'></option>
                            <?php
                            //                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            //                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            //                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <option value="<?php // echo $row['Clinic_ID'];
                                                ?>"><?php // echo $row['Clinic_Name'];
                                                                                    ?></option>
                                <?php
                                //                            }
                                ?>
                        </select>
                    </td>
                </tr>-->
                    <!--                <tr>
                    <td style="text-align:right">
                        Select Clinic Location
                    </td>
                    <td style="width:60%">
                        <select  style='width: 100%;height:30%'  name='bill_clinic_location_id' id='bill_clinic_location_id' required='required'>
                            <option selected='selected'></option>
                            <?php
                            //                             $Select_Consultant = "select * from tbl_clinic_location WHERE enabled_disabled='enabled'";
                            //                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            //                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <option value="<?php // echo $row['clinic_location_id'];
                                                ?>"><?php // echo $row['clinic_location_name'];
                                                                                                ?></option>
                                <?php
                                //                            }
                                ?>
                        </select>
                    </td>
                </tr>-->
                    <tr>
                        <td style="text-align:right">
                            Select Your working Department
                        </td>
                        <td style="width:60%">
                            <select id="bill_working_department" name="bill_working_department" style="width:100%">
                                <option value=""></option>
                                <?php
                                $sql_select_working_department_result = mysqli_query($conn, "SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if (mysqli_num_rows($sql_select_working_department_result) > 0) {
                                    while ($finance_dep_rows = mysqli_fetch_assoc($sql_select_working_department_result)) {
                                        $finance_department_id = $finance_dep_rows['finance_department_id'];
                                        $finance_department_name = $finance_dep_rows['finance_department_name'];
                                        $finance_department_code = $finance_dep_rows['finance_department_code'];
                                        echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <td colspan="2" align="right">
                        <input type="submit" name="submit" onclick="return post_clinic_department()" class="art-button-green" value="Open" />
                    </td>
                    </tr>
                </table>
            </form>
            <?php
            //Check if patient has cash bill
            if (strtolower($Payment_Method) != 'cash' && strtolower($Cash_Bill_Status) == 'pending') {
                $slct = mysqli_query($conn, "select Patient_Payment_ID from tbl_patient_payments pp where
        									Registration_ID = '$Registration_ID' and

        									Check_In_ID = '$Check_In_ID' and
        									Billing_Type = 'Inpatient Cash' and
        									pp.payment_type = 'post' and
        									Patient_Bill_ID = '$Patient_Bill_ID' limit 1") or die(mysqli_error($conn));
                $n_slct = mysqli_num_rows($slct);
                if ($n_slct > 0) {
                    $Cont_Val = strtolower($Cash_Bill_Status);
                    echo "<span id=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='color: red;'>ALERT!!.. Selected patient has pending cash bill</b></span>";
                }
            }
            ?>
    </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if (strtolower($Payment_Method) == 'cash') { ?>
                <select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                    <option selected="selected" value="Cash_Bill_Details">Cash Bill Details</option>
                    <option value="Credit_Bill_Details">Credit Bill Details</option>
                </select>
            <?php } else { ?>
                <select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                    <option selected="selected" value="Credit_Bill_Details">Credit Bill Details</option>
                    <option value="Cash_Bill_Details">Cash Bill Details</option>
                </select>
            <?php } ?>
            <select name="Receipt_Mode" id="Receipt_Mode" onchange="Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                <option selected="selected" value="Group_By_Receipt">Group by Receipt</option>
                <option value="Group_By_Category" <?php
                                                    if (isset($_SESSION['Sort_Mode']) && strtolower($_SESSION['Sort_Mode']) == 'group_by_category') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Group by Category</option>
            </select>
            <?php if ($canZero) { ?>
                <input type='button' onclick="confirmZering()" class="art-button-green" value="Zero Item(s) Prices" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i style="font-size:15px; color:red">~~ Use checkboxes to set the item price to zero ~~</i>
                <!-- <span style="color: #0079AE;"><b><i>* * * CLICK RECEIPT NUMBER FOR CANCELLING OR EDITING OPTION * * *</i></b></span> -->
            <?php } ?>

        </td>
    </tr>
    </table>
    </fieldset>
<?php } ?>
<div id="Get_Patient_Details" style="width:50%;">

</div>

<div id="Add_Items">
    <center id="Details_Area">

    </center>
</div>

<div id="Preview_Transaction_Details" style="width:50%;">

</div>
<div id="MessageAlert">
    Advance Payments Not Applicable For Credit Bills
</div>

<div id="Preview_Advance">

</div>

<div id="Approval_Warning_Message">

</div>

<div id="Not_Ready_To_Bill">
    System inaonyesha mgonjwa bado anaendelea na matibabu. Si mda sahihi wa kumtoa
    <center><b>Ili kufunga bill yake inabidi nurse amtoe wodin</b></center>
    <div class="row hide">
        <div class="col-md-12" style="text-align:center">
            <label>
                Discharge Patient Anyway
            </label>
        </div>
        <div class="col-md-3">
            <label>Discharge Reason</label>
        </div>
        <div class="col-md-4">
            <select class="form-control" id="Discharge_Reason">
                <option value=""></option>
                <?php
                $select_discharge_reason = "SELECT * FROM tbl_discharge_reason";
                $reslt = mysqli_query($conn, $select_discharge_reason);
                while ($output = mysqli_fetch_assoc($reslt)) {
                    echo '<option value="' . $output["Discharge_Reason_ID"] . '">' . $output["Discharge_Reason"] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-5">
            <button class="btn btn-primary pull-right" onclick="force_discharge()" style="color:#FFFFFF!important;">Nurse Discharge Patient initial process</button>
        </div>
    </div>
</div>
<div id="login_to_phamacy_from_billing">
    <table width='100%' class="table">
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

            <tr>
                <td width=30% style="text-align:right;">Supervisor Username</td>
                <td width=70%>
                    <input type='text' name='Supervisor_Username' required='required' autocomplete='off' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Supervisor Password</td>
                <td width=70%>
                    <input type='password' name='Supervisor_Password' required='required' size=70 autocomplete='off' id='Supervisor_Password' placeholder='Supervisor Password'>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Sub Department</td>
                <td>
                    <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                    <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                        <option selected='selected'></option>
                        <?php
                        if (isset($_SESSION['userinfo']['Employee_ID'])) {
                            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                        }
                        $select_sub_departments = mysqli_query($conn, "select Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = 'Pharmacy' and
                                                                                            sdep.Sub_Department_Status = 'active'
                                                                                        ");
                        while ($row = mysqli_fetch_array($select_sub_departments)) {
                            echo "<option>" . $row['Sub_Department_Name'] . "</option>";
                        }

                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan=2 style='text-align: center;'>
                    <input type='button' name='submit' id='submit' value='<?php echo 'ALLOW ' . strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' onclick="login_to_phamacy()" class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'>
                    <?php if (isset($_SESSION['Pharmacy_Supervisor'])) { ?>
                        <a href='./pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>CANCEL PROCESS</a>
                    <?php } else { ?>
                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                    <?php } ?>
                    <input type='hidden' name='submittedSupervisorInformationForm' value='true' />
                </td>
            </tr>
        </form>
    </table>
</div>


<div id="mortuary_service_section"></div>


<?php
if (!isset($_SESSION['Pharmacy_Supervisor'])) {
    $not_login_to_phamacy = "yes";
} else {
    $not_login_to_phamacy = "no";
}
?>
<script>
    function login_to_phamacy() {
        var Supervisor_Password = $("#Supervisor_Password").val();
        var Supervisor_Username = $("#Supervisor_Username").val();
        var Sub_Department_Name = $("#Sub_Department_Name").val();


        var userText = Supervisor_Password.replace(/^\s+/, '').replace(/\s+$/, '');
        var userText2 = Supervisor_Username.replace(/^\s+/, '').replace(/\s+$/, '');
        var userText3 = Sub_Department_Name.replace(/^\s+/, '').replace(/\s+$/, '');
        if (userText == "") {
            $("#Supervisor_Password").css("border", "2px solid red");
            $("#Supervisor_Password").focus();
            exit;
        } else {
            $("#Supervisor_Password").css("border", "");
        }
        if (userText2 == "") {
            $("#Supervisor_Username").css("border", "2px solid red");
            $("#Supervisor_Username").focus();
            exit;
        } else {
            $("#Supervisor_Username").css("border", "");
        }
        if (userText3 == "") {
            $("#Sub_Department_Name").css("border", "2px solid red");
            $("#Sub_Department_Name").focus();
            exit;
        } else {
            $("#Sub_Department_Name").css("border", "");
        }

        $.ajax({
            type: 'POST',
            url: 'login_to_pharmacy_from_billing.php',
            data: {
                Supervisor_Password: Supervisor_Password,
                Supervisor_Username: Supervisor_Username,
                Sub_Department_Name: Sub_Department_Name
            },
            success: function(data) {
                console.log("===>" + data);
                if (data == "success") {
                    document.location = "automatic_login_to_dispencing_from_bill.php?Registration_ID=<?= $Registration_ID ?>&Check_In_ID=<?= $Check_In_ID ?>";
                } else if (data == "invalid_information") {
                    alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                } else {
                    alert('FOR SUCCESSFULL AUTHENTICATION PLEASE PROVIDE ALL REQUIRED INFORMATION');
                }
            }
        });
    }

    function verify_if_this_user_login_to_dispencing() {
        var not_login_to_phamacy = '<?= $not_login_to_phamacy ?>';
        if (not_login_to_phamacy == "yes") {
            $("#login_to_phamacy_from_billing").dialog("open");
            console.log(not_login_to_phamacy);
        } else {
            document.location = "automatic_login_to_dispencing_from_bill.php?Registration_ID=<?= $Registration_ID ?>&Check_In_ID=<?= $Check_In_ID ?>";
        }
    }

    function force_discharge() {
        var admission_ID = '<?= $Admision_ID ?>';
        var Discharge_Reason = $("#Discharge_Reason").val();
        if (Discharge_Reason == "") {
            $("#Discharge_Reason").css("border", "2px solid red");
            alert("Select Discharge Reason");
            exit;
        } else {
            $("#Discharge_Reason").css("border", "");
        }
        if (confirm("Are you sure you want to dischage this patient.The patient will not be visible to the doctor. Continue?")) {
            $.ajax({
                type: 'GET',
                url: 'doctor_discharge_release_force.php',
                data: {
                    admission_ID: admission_ID,
                    Discharge_Reason: Discharge_Reason
                },
                success: function(data) {
                    if (data == '1') {
                        alert("Processed successifully.Patient is in discharge state now!");
                        $("#Not_Ready_To_Bill").dialog("close")
                    } else {
                        alert('An error has occured try again or contact system administrator');
                    }
                }
            });
        }
    }

    function post_clinic_department() {
        var Clinic_ID = $("#bill_Clinic_ID").val();
        var working_department = $("#bill_working_department").val();
        var clinic_location_id = $("#bill_clinic_location_id").val();
        //       if(Clinic_ID==''||Clinic_ID==null){
        //          alert("select clinic first")
        //          return false;
        //       }
        //       if(clinic_location_id==''||clinic_location_id==null){
        //          alert("select clinic location")
        //          return false;
        //       }
        //       if(working_department==''||working_department==null){
        //          alert("select your working department first")
        //          return false;
        //       }

        return true;
    }

    function select_clinic_dialog() {
        $("#select_clinic").dialog({
            title: 'SELECT CLINIC',
            width: '50%',
            height: 300,
            modal: true,
            position: 'center',
        });
    }
</script>
<div id="Body_Not_Ready_To_Bill">
    Mwili bado haujaruhusiwa kutoka!!
</div>
<div id="change_sponsor_first">
    Change first Patient Sponsor to Cash Grade III!!
</div>

<div id="Patient_Already_Cleared">
    Selected Bill already cleared.
</div>

<div id="Zero_Price_Alert">
    <center>Process fail!. Selected item missing price</center>
</div>

<div id="No_Items_Found">
    <center>Process fail!. No items found</center>
</div>

<div id="Zero_Price_Or_Quantity_Alert">
    <center>Process fail!. Some items missing Price or Quantity.</center>
</div>

<div id="Something_Wrong">
    <center>Process fail!. Please try again</center>
</div>

<div id="Unsuccessful_Dialogy">
    <center>Process Fail! Please try again</center>
</div>
<div id="Successful_Dialogy">
    <center>Selected items added successfully</center>
</div>
<div id="Verify_Remove_Item" style="width:25%;">
    <span id="Remove_Selected_Area">

    </span>
</div>

<div id="Editing_Transaction" style="width:25%;">
    <span id="Edit_Area">

    </span>
</div>


<div id="List_OF_Doctors">
    <center>
        <table width="100%">
            <tr>
                <td>
                    <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
                        <table width="100%">
                            <?php
                            $counter = 0;
                            $get_doctors = mysqli_query($conn, "select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                            $doctors_num = mysqli_num_rows($get_doctors);
                            if ($doctors_num > 0) {
                                while ($data = mysqli_fetch_array($get_doctors)) {
                            ?>
                                    <tr>
                                        <td style='text-align: right;'>
                                            <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
                                        </td>
                                        <td>
                                            <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </center>
</div>
<div id="Change_Item" style="width:50%;">
    <center id='Edit_Items_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="No_Enough_Payments">
    <form action="upload_bill_excemption_attachment.php" method="POST" enctype="multipart/form-data" id="approve_bill_form">
        <center>
            Malipo zaidi yanahitajika
        </center>
        <table class="table table-bordered">
            <tr>
                <th colspan="3">CLEAR BILL ANYWAY</th>
            </tr>            
            <tr>
                <td>
                    <textarea placeholder="Enter Approve Reason" id="imbalance_approval_reason" class="form-control"></textarea>
				</td>
				<?php 
                    
					$selectexmption = mysqli_query($conn, "SELECT tef.Nurse_Exemption_ID, tef.created_at, kiasikinachoombewamshamaha,Anaombewa,maoniDHS, tef.Exemption_ID FROM tbl_temporary_exemption_form tef, tbl_nurse_exemption_form nef, tbl_exemption_maoni_dhs emd  WHERE nef.Nurse_Exemption_ID=tef.Nurse_Exemption_ID AND tef.Registration_ID='$Registration_ID' AND Patient_Bill_ID='$Patient_Bill_ID' AND tef.Exemption_ID=emd.Exemption_ID") or die(mysqli_error($conn));
					if(mysqli_num_rows($selectexmption)>0){
						while($rowex = mysqli_fetch_assoc($selectexmption)){
							$Exemption_ID = $rowex['Exemption_ID'];
							$kiasikinachoombewamshamaha = $rowex['kiasikinachoombewamshamaha'];
							$Anaombewa = $rowex['Anaombewa'];
							$maoniDHS = $rowex['maoniDHS'];
							$created_at = $rowex['created_at'];
						}
					}
				
				?>
                <td id="approve_bill_status_out">
                    <select id="approve_bill_status" style="width:100%" onchange="check_if_bill_excemption_status()">
                        <!-- <option value="">SELECT STATUS</option> -->
						<!-- <option value="discount">DISCOUNT</option> -->
						<?php 
						if($Anaombewa =='Dhamana'){
                        
							echo "<option value='dept'>DEBIT (Atadaiwa kirudi kwenye matibabu)</option>";
						}else if($Anaombewa =='Msamaha'){
							echo "<option value='bill_excemption'>BILL EXCEMPTION (Hatodaiwa pesa hii)</option>";
						}else{
                            echo "<option value=''>Advise a patient to pay or Consult Social warfare Unit</option>";
                        }
					?>
                    </select>
                    <span style="color:#FFFFFF">
                        <b>please select status</b>
                    </span>
                </td>
            <td>
                    <input type="button" class="art-button-green pull-right" onclick="force_bill_approval(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?= $Admision_ID ?>,<?= $Registration_ID ?>)" value="APPROVE BILL">
                </td>
            </tr>
            <tr style="display:none" id="bill_excemption_details">
                <td>
				
				Exemption No.
					<input type="text" id="excemption_number" value="<?php echo $Exemption_ID; ?>" placeholder="Enter Excemption Number" readonly style="width:40%;" />
					<input type="text" id="kiasikinachoombewamshamaha" value="<?php echo $kiasikinachoombewamshamaha; ?>" placeholder="Enter Excemption Number" readonly hidden />
					<input type="text" id="Anaombewa" value="<?php echo $Anaombewa; ?>" placeholder="Enter Excemption Number" readonly hidden />
					<input type="text" id="maoniDHS" value="<?php echo $maoniDHS; ?>" placeholder="Enter Excemption Number" readonly hidden />
                </td>
                <td style="text-align:right">
                     Excemption 
                </td>
                <td>
					<!-- <input type="file" id="excemtion_attachment" /> -->
					<a href="preview_exemptionform.php?Registration_ID=<?= $Registration_ID ?>&Exemption_ID=<?= $Exemption_ID ?>&created_at=<?=$created_at?>&Nurse_Exemption_ID=<?= $Nurse_Exemption_ID ?>" class="art-button-green" target="blank">Preview Exemption Form</a>
                </td>
            </tr>
            <tr>
                <td id="uploading_msg" colspan="3"></td>
            </tr>
        </table>
    </form>
</div>
<script>
    function check_if_bill_excemption_status() {
        var approve_bill_status = $("#approve_bill_status").val();
        if (approve_bill_status == "bill_excemption") {
            $("#bill_excemption_details").show();
        } else {
            $("#bill_excemption_details").hide();
        }
    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script>
    $(document).ready(function() {
        $("#Get_Patient_Details").dialog({
            autoOpen: false,
            width: "90%",
            height: 630,
            title: 'INPATIENT BILLING DETAILS',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#approve_bill_status').select2();
        $('#bill_clinic_location_id').select2();
        $('#bill_working_department').select2();
        $('#bill_Clinic_ID').select2();
        $("#Preview_Transaction_Details").dialog({
            autoOpen: false,
            width: "80%",
            height: 500,
            title: 'TRANSACTION DETAILS',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#MessageAlert").dialog({
            autoOpen: false,
            width: 600,
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Preview_Advance").dialog({
            autoOpen: false,
            width: "70%",
            height: 450,
            title: 'ADVANCE PAYMENTS',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Approval_Warning_Message").dialog({
            autoOpen: false,
            width: "70%",
            height: 450,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Not_Ready_To_Bill").dialog({
            autoOpen: false,
            width: "50%",
            height: 100,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#login_to_phamacy_from_billing").dialog({
            autoOpen: false,
            width: "60%",
            height: 240,
            title: 'eHMS 2.0 ~ Login To Dispecing Work',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Body_Not_Ready_To_Bill").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#change_sponsor_first").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Zero_Price_Alert").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#No_Items_Found").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Something_Wrong").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Zero_Price_Or_Quantity_Alert").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Patient_Already_Cleared").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Refund_Required").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#No_Enough_Payments").dialog({
            autoOpen: false,
            width: "60%",
            height: 260,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Error_During_Process").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Credit_Bill").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Add_Items").dialog({
            autoOpen: false,
            width: "90%",
            height: 500,
            title: 'ADD MORE ITEMS',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Successful_Dialogy").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Unsuccessful_Dialogy").dialog({
            autoOpen: false,
            width: "40%",
            height: 110,
            title: 'eHMS 2.0 ~ Alert Message',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Verify_Remove_Item").dialog({
            autoOpen: false,
            width: '40%',
            height: 220,
            title: 'REMOVE ITEM',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Editing_Transaction").dialog({
            autoOpen: false,
            width: '80%',
            height: 200,
            title: 'EDIT ITEM',
            modal: true
        });
    });
</script>


<script>
    $(document).ready(function() {
        $("#List_OF_Doctors").dialog({
            autoOpen: false,
            width: '30%',
            height: 350,
            title: 'DOCTORS LIST',
            modal: true
        });
    });
</script>


<script>
    $(document).ready(function() {
        $("#Change_Item").dialog({
            autoOpen: false,
            width: '30%',
            height: 500,
            title: 'CHANGE ITEM',
            modal: true
        });
    });
</script>



<script type="text/javascript">
    function discharge_this_patient(Registration_ID, Admision_ID) {
        var Patient_Bill_ID = '<?= $Patient_Bill_ID ?>';
        //        alert(Patient_Bill_ID)
        if (confirm("Are you sure you want to discharge this patient?")) {
            $.ajax({
                type: 'GET',
                url: 'discharge_patient.php',
                data: {
                    Registration_ID: Registration_ID,
                    Admision_ID: Admision_ID,
                    Patient_Bill_ID: Patient_Bill_ID
                },
                success: function(data) {
                    alert(data)
                    if (data == "pending") {
                        alert("Approve the patient Bill first");
                    } else if (data == "Discharged") {
                        alert("Discharged Successfully");
                        $("#print_discharge_summary_btn").show();
                        $("#discharge_btn").hide();
                    } else if (data == "mortuary") {

                        alert("Final Discharge done Mortuary");

                    } else {
                        alert("Fail to discharge try again");
                    }
                    // alert(data)
                }
            });
        }
    }

    function Get_Item_Name(Item_Name, Item_ID) {
        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").value = '';
        document.getElementById("Quantity").focus();
        select_check_in_type(Item_ID);
    }

    function select_check_in_type(Item_ID) {
        $.ajax({
            type: 'GET',
            url: 'select_item_consultation_type.php',
            data: {
                Item_ID: Item_ID
            },
            success: function(data) {
                $("#Check_In_Type").html(data);
            }
        });
    }
</script>

<script type="text/javascript">
    function Save_Information_Verify() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function() {
            dataVerify = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = dataVerify;
                if (feedback == 'yes') {
                    Save_Information(Registration_ID);
                } else if (feedback == 'not') {
                    $("#Zero_Price_Or_Quantity_Alert").dialog("open");
                } else if (feedback == 'no') {
                    $("#No_Items_Found").dialog("open");
                } else {
                    $("#Something_Wrong").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Inpatient_Verify_Information.php?Registration_ID=' + Registration_ID, true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Save_Information(Registration_ID) {
        var Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Folio_Number = '<?php echo $Folio_Number; ?>';
        var Check_In_ID = '<?php echo $Check_In_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var sms = confirm("Are you sure you want to add selected items?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectSave = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSave = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSave.overrideMimeType('text/xml');
            }

            myObjectSave.onreadystatechange = function() {
                dataSave = myObjectSave.responseText;
                if (myObjectSave.readyState == 4) {
                    var feedbacks = dataSave;
                    if (feedbacks == 'yes') {
                        $("#Add_Items").dialog("close");
                        Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID)
                        $("#Successful_Dialogy").dialog("open");
                    } else {
                        $("#Unsuccessful_Dialogy").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........

            myObjectSave.open('GET', 'Save_Information_Inpatient.php?Registration_ID=' + Registration_ID + '&Transaction_Type=' + Transaction_Type + '&Check_In_ID=' + Check_In_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Bill_ID=' + Patient_Bill_ID, true);
            myObjectSave.send();
        }
    }
</script>

<script type="text/javascript">
    function Calculate_Grand_Total() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectGrand = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGrand = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGrand.overrideMimeType('text/xml');
        }

        myObjectGrand.onreadystatechange = function() {
            dataGrandTotal = myObjectGrand.responseText;
            if (myObjectGrand.readyState == 4) {
                document.getElementById('Grand_Total_Area').innerHTML = dataGrandTotal;
            }
        }; //specify name of function that will handle server response........

        myObjectGrand.open('GET', 'Inpatient_Calculate_Grand_Total.php?Registration_ID=' + Registration_ID, true);
        myObjectGrand.send();
    }
</script>
<script type="text/javascript">
    function Add_Selected_Item() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Discount = document.getElementById("Discount").value;
        var Check_In_Type = document.getElementById("Check_In_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Price = document.getElementById("Price").value;
        //        var Clinic_ID=document.getElementById("Clinic_ID").value;
        //        var clinic_location_id=document.getElementById("clinic_location_id").value;
        var working_department = document.getElementById("working_department").value;
        //        if(Clinic_ID==""){
        //           alert("Select Clinic");
        //           $("#Clinic_ID").css("border","3px solid red");
        //           exit;
        //        }
        //        if(clinic_location_id=="") {
        //             alert("Select Clinic Location");
        //           $("#clinic_location_id").css("border","3px solid red");
        //           exit;
        //        }
        if (working_department == "") {
            alert("Select working_department");
            $("#working_department").css("border", "3px solid red");
            exit;
        }
        if (Price != 0 && Item_ID != null && Item_ID != '' && Check_In_Type != null && Check_In_Type != '' && Registration_ID != '' && Registration_ID != null && Quantity != null && Quantity != '' && Quantity != 0) {
            if (window.XMLHttpRequest) {
                myObjectAddSelectedItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddSelectedItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddSelectedItem.overrideMimeType('text/xml');
            }

            myObjectAddSelectedItem.onreadystatechange = function() {
                data921 = myObjectAddSelectedItem.responseText;
                if (myObjectAddSelectedItem.readyState == 4) {
                    document.getElementById("Cached_Items").innerHTML = data921
                    Calculate_Grand_Total();
                    document.getElementById("Price").value = '';
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Discount").value = '';
                    document.getElementById("Quantity").value = '';
                }
            }; //specify name of function that will handle server response........

            myObjectAddSelectedItem.open('GET', 'Inpatient_Add_More_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Discount=' + Discount + '&Check_In_Type=' + Check_In_Type + '&Transaction_Type=' + Transaction_Type + '&Sponsor_ID=' + Sponsor_ID + "&Admision_ID=" + Admision_ID + "&working_department" + working_department, true);
            myObjectAddSelectedItem.send();
        } else {
            if ((Price == null || Price == '' || Price == 0) && Item_ID != null && Item_ID != '') {
                $("#Zero_Price_Alert").dialog("open");
                return false
            }
            if (Item_ID == null || Item_ID == '') {
                document.getElementById("Item_Name").style = 'border: 3px solid red';
            } else {
                document.getElementById("Item_Name").style = 'border: 3px solid white';
            }

            if (Check_In_Type == null || Check_In_Type == '') {
                document.getElementById("Check_In_Type").style = 'border: 3px solid red';
            } else {
                document.getElementById("Check_In_Type").style = 'border: 3px solid white';
            }

            if (Quantity == null || Quantity == '' || Quantity == 0) {
                document.getElementById("Quantity").style = 'border: 3px solid red';
            } else {
                document.getElementById("Quantity").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Remove_Item(Item_Cache_ID, Product_Name) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var sms = confirm("Are you sure you want to remove " + Product_Name + "?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function() {
                dataRemove = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Cached_Items').innerHTML = dataRemove;
                    Calculate_Grand_Total();
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Inpatient_Remove_Selected_Item.php?Item_Cache_ID=' + Item_Cache_ID + '&Registration_ID=' + Registration_ID, true);
            myObjectRemove.send();
        }
    }
</script>
<script type="text/javascript">
    function Get_Item_Price(Item_ID, Guarantor_Name, Sponsor_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        //  alert(Sponsor_ID)
        if (window.XMLHttpRequest) {
            myObjectPrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPrice.overrideMimeType('text/xml');
        }
        // alert(Item_ID+"::"+Guarantor_Name+"::"+Sponsor_ID);
        myObjectPrice.onreadystatechange = function() {
            data = myObjectPrice.responseText;

            if (myObjectPrice.readyState == 4) {
                document.getElementById('Price').value = data;
                document.getElementById("Quantity").value = 1;
            }
        }; //specify name of function that will handle server response........

        myObjectPrice.open('GET', 'Get_Items_Price_Inpatient.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Transaction_Type=' + Transaction_Type + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectPrice.send();
    }
</script>
<script type="text/javascript">
    function Preview_Patient_Details(Check_In_ID) {
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function() {
            data2000 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById("Get_Patient_Details").innerHTML = data2000;
                $("#Get_Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectPreview.open('GET', 'Preview_Patient_Bill_Details.php?Check_In_ID=' + Check_In_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function View_Details(Patient_Payment_ID, Patient_Payment_Item_List_ID) {
        if (window.XMLHttpRequest) {
            myObjectViewDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectViewDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectViewDetails.overrideMimeType('text/xml');
        }

        myObjectViewDetails.onreadystatechange = function() {
            data = myObjectViewDetails.responseText;
            if (myObjectViewDetails.readyState == 4) {
                document.getElementById("Preview_Transaction_Details").innerHTML = data;
                $("#Preview_Transaction_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectViewDetails.open('GET', 'Preview_Transaction_Details.php?Patient_Payment_ID=' + Patient_Payment_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectViewDetails.send();
    }
</script>
<script>
    function Patient_List_Search() {
        var Patient_Name = document.getElementById("Search_Patient").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Patient_Number").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function() {
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchPatient.open('GET', 'Revenue_Center_Pharmacy_List_Iframe.php?Patient_Name=' + Patient_Name + '&date_From=' + date_From + '&date_To=' + date_To + '&Billing_Type=' + Billing_Type, true);
        myObjectSearchPatient.send();
    }
</script>

<script>
    function Patient_List_Search2() {
        var Patient_Number = document.getElementById("Patient_Number").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Search_Patient").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function() {
            data218 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data218;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchPatient.open('GET', 'Revenue_Center_Pharmacy_List_Iframe.php?Patient_Number=' + Patient_Number + '&date_From=' + date_From + '&date_To=' + date_To + '&Billing_Type=' + Billing_Type, true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if (window.XMLHttpRequest) {
            myObjectMode = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMode = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMode.overrideMimeType('text/xml');
        }
        myObjectMode.onreadystatechange = function() {
            data288 = myObjectMode.responseText;
            if (myObjectMode.readyState == 4) {
                document.getElementById('Transaction_Items_Details').innerHTML = data288;
                Summary_Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectMode.open('GET', 'Sort_Mode_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectMode.send();
    }
</script>



<script type="text/javascript">
    function Summary_Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectSummary = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSummary = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSummary.overrideMimeType('text/xml');
        }
        myObjectSummary.onreadystatechange = function() {
            data9999 = myObjectSummary.responseText;
            if (myObjectSummary.readyState == 4) {
                document.getElementById('Transaction_Summary_Area').innerHTML = data9999;
            }
        }; //specify name of function that will handle server response........

        myObjectSummary.open('GET', 'Sort_Mode_Summary_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID, true);
        myObjectSummary.send();
    }
</script>


<script type="text/javascript">
    function Preview_Advance_Payments(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPreviewAdvance = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreviewAdvance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreviewAdvance.overrideMimeType('text/xml');
        }
        myObjectPreviewAdvance.onreadystatechange = function() {
            data987 = myObjectPreviewAdvance.responseText;
            if (myObjectPreviewAdvance.readyState == 4) {
                document.getElementById("Preview_Advance").innerHTML = data987;
                $("#Preview_Advance").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreviewAdvance.open('GET', 'Preview_Advance_Payments.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectPreviewAdvance.send();
    }
</script>

<script type="text/javascript">
    function Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        //alert(Transaction_Type);
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectDisplay = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function() {
            data99929 = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Transaction_Summary_Area').innerHTML = data99929;
            }
        }; //specify name of function that will handle server response........

        myObjectDisplay.open('GET', 'Sort_Mode_Summary_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID, true);
        myObjectDisplay.send();
    }
</script>

<script type="text/javascript">
    function Add_More_Items(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        // alert(Sponsor_ID);
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        if (window.XMLHttpRequest) {
            myObjectDisplay = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function() {
            mydata = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = mydata;
                $("#Add_Items").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectDisplay.open('GET', 'Patient_Billing_Add_More_Items.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectDisplay.send();
    }
</script>
<?php
$STATUS_ = "";
if (isset($_GET['Status'])) {
    $STATUS_ = $_GET['Status'];
}
?>
<script type="text/javascript">
    function Preview_Patient_Bill(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Status = '<?= $STATUS_ ?>';
        window.open('previewpatientbill.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Registration_ID=' + Registration_ID + '&Transaction_Type=' + Transaction_Type + '&Status=' + Status, '_blank');
    }
</script>

<script type="text/javascript">
    function uploading_attachment(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Admision_ID) {
        var imbalance_approval_reason = $("#imbalance_approval_reason").val();
        var approve_bill_status = $("#approve_bill_status").val();
        var excemption_number = $("#excemption_number").val();
        //var excemtion_attachment=$("#excemtion_attachment").val();
        var property = document.getElementById('excemtion_attachment').files[0];


        //        var form_data = new FormData();
        //        form_data.append("file",property);
        var form_data = new FormData();
        var files = $('#excemtion_attachment')[0].files[0];
        form_data.append('file', files);
        console.log(files);
        $.ajax({
            url: 'upload_bill_excemption_attachment.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#uploading_msg').html('Uploading......');
            },
            success: function(data) {
                console.log("==>" + data);
                //$('#msg').html(data);

                if (data != "uploading_fail") {
                    /*
                                     $.ajax({
                                  type:'GET',
                                  url:'force_imbalance_bill_approval.php',
                                  data:{Admision_ID:Admision_ID,imbalance_approval_reason:imbalance_approval_reason,approve_bill_status:approve_bill_status,excemption_number:excemption_number,excemtion_attachment:data},
                                  success:function(data){

                                      if(data=="success"){
                                          alert("Approved Successfully")
                                          $("#No_Enough_Payments").dialog("close")

                                      }else{
                                          alert("Fail to Approve...try again")
                                      }
                                      Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                                  }
                              }); */
                } else {
                    alert("Fail to upload the attachment...please try again");
                }
            }
        });


    }

    function force_bill_approval(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Admision_ID, Registration_ID) {
        var maoniDHS = $("#maoniDHS").val();
		var kiasikinachoombewamshamaha = $("#kiasikinachoombewamshamaha").val()
		var Anaombewa = $("#Anaombewa").val();
        var imbalance_approval_reason = $("#imbalance_approval_reason").val();
        var approve_bill_status = $("#approve_bill_status").val();
        var excemption_number = $("#excemption_number").val();
        //var excemtion_attachment = $("#excemtion_attachment").val();
        var userText = imbalance_approval_reason.replace(/^\s+/, '').replace(/\s+$/, '');
        var userText2 = approve_bill_status.replace(/^\s+/, '').replace(/\s+$/, '');
        var userText3 = excemption_number.replace(/^\s+/, '').replace(/\s+$/, '');
      //  var userText4 = excemtion_attachment.replace(/^\s+/, '').replace(/\s+$/, '');
        var Grand_Total = $("#Grand_Total").val();
        var Grand_Total_Direct_Cash = $("#Grand_Total_Direct_Cash").val();
        if (userText == "") {
            $("#imbalance_approval_reason").css("border", "2px solid red");
            $("#imbalance_approval_reason").focus();
            exit;
        } else {
            $("#imbalance_approval_reason").css("border", "");
        }
        if (userText2 == "") {
            $("#approve_bill_status_out").css("background", "red");
            $("#approve_bill_status").focus();
            exit;
        } else {
            $("#approve_bill_status_out").css("background", "");
        }
        if (userText3 == "" && approve_bill_status == "bill_excemption") {
            $("#excemption_number").css("border", "2px solid red");
            $("#excemption_number").focus();
            exit;
        } else {
            $("#excemption_number").css("border", "");
        }
       
        var Kiasikinachotakiwa = Grand_Total - Grand_Total_Direct_Cash;
        var tobepaid =Kiasikinachotakiwa - kiasikinachoombewamshamaha ;
		if(kiasikinachoombewamshamaha < Kiasikinachotakiwa ){
			alert("Advise patient to make more payment of "+tobepaid + "/= In order to clear bill");
			exit();
		}else if(maoniDHS != "NDIO"){
			alert("Administration view on this bill is "+ maoniDHS +" Advise a patient to make payments");
			exit();
		}
        if (approve_bill_status == "bill_excemptions") {  ///changed og == bill_excemption
            uploading_attachment(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Admision_ID)
            $.ajax({
                type: 'GET',
                url: 'force_imbalance_bill_approval.php',
                data: {
                    Admision_ID: Admision_ID,
                    Patient_Bill_ID: Patient_Bill_ID,
                    Registration_ID: Registration_ID,
                    Grand_Total: Grand_Total,
                    Grand_Total_Direct_Cash: Grand_Total_Direct_Cash,
                    imbalance_approval_reason: imbalance_approval_reason,
                    approve_bill_status: approve_bill_status,
                    excemption_number: excemption_number
                    
                },
                success: function(data) {

                    if (data == "success") {
                        alert("Approved Successfully")
                        $("#No_Enough_Payments").dialog("close")

                    } else {
                        alert(data)
                    }
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                }
            });
        } else {
            $.ajax({
                type: 'GET',
                url: 'force_imbalance_bill_approval.php',
                data: {
                    Admision_ID: Admision_ID,
                    Patient_Bill_ID: Patient_Bill_ID,
                    Registration_ID: Registration_ID,
                    Grand_Total: Grand_Total,
                    Grand_Total_Direct_Cash: Grand_Total_Direct_Cash,
                    imbalance_approval_reason: imbalance_approval_reason,
                    approve_bill_status: approve_bill_status,
                    excemption_number: excemption_number
                    
                },
                success: function(data) {

                    if (data == "success") {
                        alert("Approved Successfully")
                        $("#No_Enough_Payments").dialog("close")

                    } else {
                        alert(data)
                    }
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                }
            });
        }
    }

    function Approve_Patient_Bill(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {

        // alert("test")

        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Confirm_Message = confirm("Are you sure you want to approve selected bill?");


        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectVerifyItems = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectVerifyItems = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectVerifyItems.overrideMimeType('text/xml');
            }
            myObjectVerifyItems.onreadystatechange = function() {
                data01 = myObjectVerifyItems.responseText;

                if (myObjectVerifyItems.readyState == 4) {

                    var feedback = data01;


                    if (feedback == 'yes') {
                        Approve_Bill_Process(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
                    } else if (feedback == 'not') {
                        $("#Not_Ready_To_Bill").dialog("open"); //not ready to bill
                    } else if (feedback == 'true') {
                        $("#Patient_Already_Cleared").dialog("open");
                    } else if (feedback == 'mortuary_not') {
                        $("#Body_Not_Ready_To_Bill").dialog("open");
                        //alert("Mwili bado haujaruhusiwa kutoka!!");
                    } else if (feedback == 'change') {
                        $("#change_sponsor_first").dialog("open");
                    } else {
                        $("#Approval_Warning_Message").dialog("open"); //something happened
                    }
                }

            }; //specify name of function that will handle server response........
            myObjectVerifyItems.open('GET', 'Approve_Patient_Bill_Verify.php?Transaction_Type=' + Transaction_Type + '&Admision_ID=' + Admision_ID, true);
            myObjectVerifyItems.send();
        }
    }
</script>

<script type="text/javascript">
    function Approve_Bill_Process(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var ward_type = '<?php echo $ward_type; ?>';
        var Grand_Total = $("#Grand_Total").val();
        //        alert(Grand_Total);
        //        exit;
        if (window.XMLHttpRequest) {
            myObjectApprove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectApprove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectApprove.overrideMimeType('text/xml');
        }
        myObjectApprove.onreadystatechange = function() {
            data99991 = myObjectApprove.responseText;
            if (myObjectApprove.readyState == 4) {
                var feedback = data99991;
                // alert(feedback)
                if (feedback == 100) { //refund required
                    alert("Bill Cleared Successfully");
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Add_Item").hide();
                    $("#Refund_Required").dialog("open");
                } else if (feedback == 200) { //no enough paymments to clear bill
                    // alert("yes here")
                    $("#No_Enough_Payments").dialog("open");
                } else if (feedback == 300) { //error occur during the process
                    $("$Error_During_Process").dialog("open");
                } else if (feedback == 400) { //credit bill ~ no complexity
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Credit_Bill").dialog("open");
                    $("#Add_Item").hide();
                    $("#Refund_Required").dialog("open");
                } else {
                    //alert("empty query");		// alert("Bill Cleared Successfully");
                } 
            }
        }; //specify name of function that will handle server response........

        myObjectApprove.open('GET', 'Approve_Patient_Bill.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID + '&Grand_Total=' + Grand_Total+'&ward_type='+ward_type, true);
        myObjectApprove.send();
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID, Sponsor_ID) {
        document.getElementById("Search_Product").value = '';
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //alert(data);

        myObject.onreadystatechange = function() {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(Item_Name, Sponsor_ID) {
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
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
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function Remove_Transaction(Patient_Payment_ID, Patient_Payment_Item_List_ID, Item_Name) {
        if (window.XMLHttpRequest) {
            myObjectContent = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectContent = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectContent.overrideMimeType('text/xml');
        }

        myObjectContent.onreadystatechange = function() {
            data9834 = myObjectContent.responseText;
            if (myObjectContent.readyState == 4) {
                document.getElementById("Remove_Selected_Area").innerHTML = data9834;
                $("#Verify_Remove_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectContent.open('GET', 'Remove_Selected_Item_Contents.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Item_Name=' + Item_Name, true);
        myObjectContent.send();
    }
</script>

<script type="text/javascript">
    function Edit_Transaction(Patient_Payment_ID, Patient_Payment_Item_List_ID, Item_Name) {
        if (window.XMLHttpRequest) {
            myObjectRemoveItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveItem.overrideMimeType('text/xml');
        }

        myObjectRemoveItem.onreadystatechange = function() {
            dataEdit = myObjectRemoveItem.responseText;
            if (myObjectRemoveItem.readyState == 4) {
                document.getElementById("Edit_Area").innerHTML = dataEdit;
                $("#Editing_Transaction").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveItem.open('GET', 'Patient_Billing_Edit_Transaction.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Patient_Payment_ID=' + Patient_Payment_ID, true);
        myObjectRemoveItem.send();
    }
</script>

<script type="text/javascript">
    function Remove_Selected_Item(Patient_Payment_ID, Patient_Payment_Item_List_ID) {
        var Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
        var Folio_Number = '<?php echo $Folio_Number; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Check_In_ID = '<?php echo $Check_In_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectRemoveItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveItem.overrideMimeType('text/xml');
        }

        myObjectRemoveItem.onreadystatechange = function() {
            data = myObjectRemoveItem.responseText;
            if (myObjectRemoveItem.readyState == 4) {
                $("#Verify_Remove_Item").dialog("close");
                Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID)
                View_Details(Patient_Payment_ID, 0);
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveItem.open('GET', 'Patient_Billing_Remove_Selected_Item.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectRemoveItem.send();
    }
</script>


<script type="text/javascript">
    function getDoctor() {
     
        var Patient_Direction = document.getElementById('Patient_Direction').value;
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        if (window.XMLHttpRequest) {
            myObjectFilter = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }

        myObjectFilter.onreadystatechange = function() {
            dataFilter = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById("Consultant_Area").innerHTML = dataFilter;
            }
        }; //specify name of function that will handle server response........

        if (Patient_Direction == 'Direct To Doctor Via Nurse Station' || Patient_Direction == 'Direct To Doctor') {
            document.getElementById('Doctors_List').style.visibility = "";
            myObjectFilter.open('GET', 'Patient_Billing_Select_Patient_Direction.php?Type_Of_Check_In=' + Type_Of_Check_In + '&Patient_Direction=doctor', true);
        } else {
            document.getElementById('Doctors_List').style.visibility = "hidden";
            myObjectFilter.open('GET', 'Patient_Billing_Select_Patient_Direction.php?Patient_Direction=clinic', true);
        }
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Edit_Process() {
        $("#Editing_Transaction").dialog("close");
    }
</script>


<script type="text/javascript">
    function Get_Doctor() {
        var Direction = document.getElementById("Patient_Direction").value;
        if (Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')) {
            $("#List_OF_Doctors").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Get_Selected_Doctor(Doctror_Name) {
        document.getElementById("Consultant").value = Doctror_Name;
        // document.getElementById("Doc_Name").value = '';
        // Search_Doctors();
        $("#List_OF_Doctors").dialog("close");
    }
</script>


<script type="text/javascript">
    function Search_Doctors() {
        var Doctror_Name = document.getElementById("Doc_Name").value;
        if (window.XMLHttpRequest) {
            myObject_Search_Doctor = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Doctor.overrideMimeType('text/xml');
        }

        myObject_Search_Doctor.onreadystatechange = function() {
            data = myObject_Search_Doctor.responseText;
            if (myObject_Search_Doctor.readyState == 4) {
                document.getElementById('Doctors_Area').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Doctor.open('GET', 'Search_Doctors.php?Doctror_Name=' + Doctror_Name, true);
        myObject_Search_Doctor.send();
    }
</script>
<script type="text/javascript">
    function Close_Dialog() {
        $("#Verify_Remove_Item").dialog("close");
    }
</script>

<script type="text/javascript">
    function Preview_Advance_Payments_Warning() {
        $("#MessageAlert").dialog("open");
    }
</script>

<script type="text/javascript">
    function Open_Item_Dialogy(Sponsor_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObjectChangeItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectChangeItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectChangeItem.overrideMimeType('text/xml');
        }

        myObjectChangeItem.onreadystatechange = function() {
            data2922 = myObjectChangeItem.responseText;
            if (myObjectChangeItem.readyState == 4) {
                document.getElementById('Edit_Items_Area').innerHTML = data2922;
                $("#Change_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectChangeItem.open('GET', 'Patient_Billing_Change_Item.php?Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
        myObjectChangeItem.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Item(Item_Name, Item_ID, Sponsor_ID) {
        document.getElementById("Pro_Name").value = Item_Name;
        document.getElementById("Pro_ID").value = Item_ID;
        var Billing_Type = document.getElementById("Billing_Type").value;
        Get_Price(Item_ID, Sponsor_ID);
    }
</script>
<script type="text/javascript">
    function update_morgue_condition() {
        var inalala_bilakulala = $("#inalala_id").val();
        var Patient_id = '<?php echo $Registration_ID; ?>';

        $.ajax({
            type: 'GET',
            url: 'ajax_update_morgue_condition.php',
            data: {
                inalala_bilakulala: inalala_bilakulala,
                patient_id: Patient_id
            },
            success: function(data) {
                location.reload();
            }
        });
    }
</script>

<script type="text/javascript">
    function Get_Price(Item_ID, Sponsor_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObjectEditedPrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectEditedPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectEditedPrice.overrideMimeType('text/xml');
        }
        myObjectEditedPrice.onreadystatechange = function() {
            data5050 = myObjectEditedPrice.responseText;
            if (myObjectEditedPrice.readyState == 4) {
                document.getElementById('Edited_Price').value = data5050;
                $("#Change_Item").dialog("close");
            }
        }; //specify name of function that will handle server response........

        myObjectEditedPrice.open('GET', 'Patient_Billing_Get_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
        myObjectEditedPrice.send();
    }
</script>


<script type="text/javascript">
    function Get_Items_List_Filtered() {
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Item_Name = document.getElementById("Search_Product_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObjectSearchEdit = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchEdit = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchEdit.overrideMimeType('text/xml');
        }

        myObjectSearchEdit.onreadystatechange = function() {
            data97 = myObjectSearchEdit.responseText;
            if (myObjectSearchEdit.readyState == 4) {
                document.getElementById('Items_Area').innerHTML = data97;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchEdit.open('GET', 'Get_Items_List_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectSearchEdit.send();
    }
</script>

<script type="text/javascript">
    function Get_Items_List(Item_Category_ID) {
        document.getElementById("Search_Product_Name").value = '';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectSearchEdit2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchEdit2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchEdit2.overrideMimeType('text/xml');
        }

        myObjectSearchEdit2.onreadystatechange = function() {
            data456 = myObjectSearchEdit2.responseText;
            if (myObjectSearchEdit2.readyState == 4) {
                document.getElementById('Items_Area').innerHTML = data456;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchEdit2.open('GET', 'Get_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectSearchEdit2.send();
    }
</script>
<script>
    function confirmZering() {
        var count = 0;
        var i = 1;
        $(".zero_items").each(function() {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }

        var msg = '';

        if (count == 1) {
            msg = 'the selected item';
        } else {
            msg = count + ' selected items';
        }

        confirmAction('<h3 style="text-align:center;font-weight:100">Are you sure you want to zero price for ' + msg + '?</h3>', 'Please make sure you know what you are doing', 'confirmation', 450, false, false, 'Yes', "No", .5, zeroItems);
    }
</script>
<script>
    function zeroItems() {
        var dataInfo = '';
        var count = 0;
        var i = 1;
        $(".zero_items").each(function() {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }
        if(confirm("This action is recorded. Are you sure you want to zero this service?? ")){
            $.ajax({
                type: 'POST',
                url: 'zero_item_price.php',
                data: 'action=zeroprice&dataInfos=' + dataInfo,
                beforeSend: function(xhr) {
                    $("#progressStatus").show();
                },
                success: function(html) {
                    if (html == '1') {
                        document.location.reload();
                    } else {
                        alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                    }
                },
                complete: function() {
                    $("#progressStatus").hide();
                },
                error: function(html, jjj) {
                    alert(html);
                }
            });
        }

    }
</script>
<script>
    function addPrice(ppil, element) {
        var status = $(element).is(':checked');
        console.log("Hii"+ ppil);
        if(confirm("Are you sure you want to unzero this service??")){
            if (!status) {
                $.ajax({
                    type: 'POST',
                    url: 'zero_item_price.php',
                    data: 'action=unzeroprice&ppil=' + ppil,
                    beforeSend: function(xhr) {
                        $("#progressStatus").show();
                    },
                    success: function(html) {
                        if (html == '1') {
                            document.location.reload();
                        } else {
                        alertMsg(html, "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                        }
                    },
                    complete: function() {
                        $("#progressStatus").hide();
                    },
                    error: function(html, jjj) {
                        alert(html);
                    }
                });
            }
        }
    }
</script>

<?php
include("./includes/footer.php");
?>
