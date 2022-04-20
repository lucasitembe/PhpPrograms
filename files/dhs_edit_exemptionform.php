<?php
include("./includes/header.php");
include("./includes/connection.php");
$Patient_Picture = "";
$national_id = "";
$Religion_Name = "";
$village = "";
$Denomination_Name = "";
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
}

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if(isset($_GET['Exemption_ID'])){
    $exemptionID = $_GET['Exemption_ID'];
}else{
    $exemptionID ='';
}

if(isset($_GET['created_at'])){
    $created_at = $_GET['created_at'];
}else{
    $created_at ='';
}
if(isset($_GET['MaoniDHS_ID'])){
    $MaoniDHS_ID = $_GET['MaoniDHS_ID'];
}else{
    $MaoniDHS_ID ='';
}
if(isset($_GET['Nurse_Exemption_ID'])){
    $Nurse_Exemption_ID = $_GET['Nurse_Exemption_ID'];
}else{
    $Nurse_Exemption_ID=0;
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='temporaryExemptionList.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php

    }
}
?>


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

<?php
//    select patient information to perform check in process
        if (isset($_GET['Registration_ID'])) {
            $Registration_ID = $_GET['Registration_ID'];
            $select_Patient = mysqli_query($conn,"SELECT
            patient_type,Status,service_no,dependancy_id,dependecny_service_no,military_unit,
            Old_Registration_Number,Title,Patient_Name,
                Date_Of_Birth,Patient_Picture,sp.payment_method,
                    Gender,Religion_Name,Denomination_Name,
        pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                        Member_Number,Member_Card_Expire_Date,
                            pr.Phone_Number,Email_Address,Occupation,
                                Employee_Vote_Number,Emergence_Contact_Name,
                                    Emergence_Contact_Number,Company,Registration_ID,
                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                        Registration_ID,sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id,
                                        village
                    FROM tbl_patient_registration pr LEFT JOIN tbl_denominations de ON de.Denomination_ID=pr.Denomination_ID LEFT JOIN tbl_religions re  ON re.Religion_ID=de.Religion_ID,
                    tbl_sponsor sp
                    WHERE pr.Sponsor_ID = sp.Sponsor_ID AND
                    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);

        if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) { //
        $patient_type = $row['patient_type'];
        $rank = $row['rank'];
        $Status = $row['Status'];
        $service_no = $row['service_no'];
        $dependecny_service_no = $row['dependecny_service_no'];
        $military_unit = $row['military_unit'];
        $dependancy_id = $row['dependancy_id'];
        $payment_method = $row['payment_method'];



            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $village = mysqli_real_escape_string($conn,$row['village']);
            $Country = $row['Country'];
            $Region = $row['Region'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Tribe = $row['Tribe'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Exemption = strtolower($row['Exemption']); //
            $Diseased = strtolower($row['Diseased']);
            $national_id = $row['national_id'];
            $Patient_Picture = $row['Patient_Picture'];
            $Religion_Name = $row['Religion_Name'];
            $Denomination_Name = $row['Denomination_Name'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

       
        $select_ward = mysqli_query($conn,"SELECT w.Ward_ID, w.Ward_Name FROM tbl_ward w, tbl_patient_registration pr WHERE  w.Ward_ID = pr.Ward_ID  AND pr.Registration_ID = $Registration_ID") or die(mysqli_error($conn));
        $ward = mysqli_fetch_assoc($select_ward)['Ward_Name'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

    } else {
        $Religion_Name = "";
        $Denomination_Name = "";
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Tribe = '';
        $Sponsor_ID = '';
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
        $Exemption = 'yes';
        $Diseased = '';
        $patient_type = "";
        $rank = "";
        $Status = "";
        $service_no = "0";
        $dependecny_service_no = "";
        $military_unit = "";
        $dependancy_id = "";
    }
} 
//Check if Exemption details available
$Exemption_Details = 'available';
if ($Exemption == 'yes') {
    $verify = mysqli_query($conn,"select msamaha_ID from tbl_msamaha where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($verify);
    if ($nm < 1) {
        $Exemption_Details = 'not available';
    }
}
//SELECT LAST VISIT DATE
$lastvisitdate = mysqli_query($conn,"select Check_In_Date from tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
$lastvisit = mysqli_fetch_assoc($lastvisitdate);
$Last_Check_In_Date = $lastvisit['Check_In_Date'];
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<?php
//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

$get_reception_setting = mysqli_query($conn,"select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($get_reception_setting);
if ($no > 0) {
    while ($data = mysqli_fetch_array($get_reception_setting)) {
        $Reception_Picking_Items = $data['Reception_Picking_Items'];
    }
} else {
    $Reception_Picking_Items = 'no';
}

?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

</style>
<?php 
                $msamahaa="";
                $dhamanaa="";
                $aliwahikutoroka="";
    
    $exemptiondata  = mysqli_query($conn, "SELECT ushauriwabima, Nurse_Exemption_ID, time_of_treatment,mode_of_treatment,taarifa_za_mgonjwa,patient_next_kin,next_kin_phoneNo,Next_kin_relationship,Anaombewa,previous_hospital_treatment ,Exemption_ID, tathiminiyamsaada,alishawahikupewa, kiasikinachoombewamshamaha, kiwango_cha_msamaha, mapendekezomsamaha FROM tbl_temporary_exemption_form WHERE Registration_ID ='$Registration_ID' AND Exemption_ID='$exemptionID' ");

        while($ex_data = mysqli_fetch_assoc($exemptiondata)){
            $ushauriwabima  = $ex_data['ushauriwabima'];
            $tathiminiyamsaada = $ex_data['tathiminiyamsaada'];
            $alishawahikupewa = explode(',', $ex_data['alishawahikupewa']);
            $kiasikinachoombewamshamaha = $ex_data['kiasikinachoombewamshamaha'];
            $kiwango_cha_msamaha = $ex_data['kiwango_cha_msamaha'];
            $mapendekezomsamaha = $ex_data['mapendekezomsamaha'];
            $Anaombewa = $ex_data['Anaombewa'];
            $Nurse_Exemption_ID= $ex_data['Nurse_Exemption_ID'];

            $mode_of_treatment =explode(',', $ex_data['mode_of_treatment']);
            $taarifa_za_mgonjwa = explode(',',$ex_data['taarifa_za_mgonjwa']);
            $patient_next_kin = $ex_data['patient_next_kin'];
            $next_kin_phoneNo = $ex_data['next_kin_phoneNo'];
            $Next_kin_relationship = $ex_data['Next_kin_relationship'];
            $previous_hospital_treatment = $ex_data['previous_hospital_treatment'];
            $time_of_treatment = $ex_data['time_of_treatment'];
            foreach($alishawahikupewa as $ushauri){
                if($ushauri =="Msamaha"){
                    $msamahaa ="checked='checked'";
                }
                if($ushauri =="Dhamana"){
                    $dhamanaa = "checked='checked'";
                }
                if($ushauri == "Kutoroka"){
                    $aliwahikutoroka ="checked='checked'";
                }
            }

             
        foreach($mode_of_treatment as $treatment){
            if($treatment =="Taslimu"){
                $Taslimu ="checked='checked'";
            }
            if($treatment =="Bima"){
                $Bima ="checked='checked'";
            }
        }
        foreach($taarifa_za_mgonjwa as $taarifa){
            if($taarifa =="Mwanafunzi"){
                $Mwanafunzi = "checked='checked'";
            }
            if($taarifa =="Mlemavu"){
                $Mlemavu = "checked='checked'";
            }
            if($taarifa =="Mwajiriwa"){
                $Mwajiriwa = "checked='checked'";
            }
            if($taarifa =="Kajiajiri"){
                $Kajiajiri = "checked='checked'";
            }
            if($taarifa =="Divorced"){
                $Divorced = "checked='checked'";
            }
            if($taarifa =="Mfungwa"){
                $Mfungwa = "checked='checked'";
            }
            if($taarifa =="Mahabusu"){
                $Mahabusu = "checked='checked'";
            }
            if($taarifa =="Mwenye_urahibu"){
                $Mwenye_urahibu = "checked='checked'";
            }
            if($taarifa =="Mjane_mgane"){
                $Mjane_mgane = "checked='checked'";
            }
            if($taarifa =="Mstaafu"){
                $Mstaafu = "checked='checked'";
            }
            if($taarifa =="Mzee"){
                $Mzee = "checked='checked'";
            }
            if($taarifa =="Mjamzito"){
                $Mjamzito = "checked='checked'";
            }
            if($taarifa =="Ameolewa_kuoa"){
                $Ameolewa_kuoa = "checked='checked'";
            }
            if($taarifa =="Mtoto_chini_5"){
                $Mtoto_chini_5 = "checked='checked'";
            }
            if($taarifa =="HanaNdoa"){
                $HanaNdoa = "checked='checked'";
            }
        }
                
            
        }
       
    //$admin_date = '';
   $Admision_ID="0";
    $select_admission = mysqli_query($conn, "SELECT Admision_ID, Admission_Date_Time,Discharge_Date_Time, Kin_Name, Registration_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_admission))>0){
        while($row_date =mysqli_fetch_assoc($select_admission)){
            $Admision_ID = $row_date['Admision_ID'];
            $admin_date = $row_date['Admission_Date_Time'];
            $Kin_Name = $row_date['Kin_Name'];
            $Discharge_Date_Time = $row_date['Discharge_Date_Time'];
        }
    }else{
        $admin_date = "Nothing to show";

    }//echo $Admision_ID;
    $magonjwa = "";
    $select_diagnosis = mysqli_query($conn, "SELECT wrd.Round_ID, wr.Registration_ID, wrd.disease_ID,  disease_name, disease_code  FROM tbl_disease d, tbl_ward_round_disease wrd, tbl_ward_round wr WHERE wrd.Round_ID = wr.Round_ID AND wr.Registration_ID ='$Registration_ID' AND wrd.disease_ID=d.disease_ID GROUP BY wrd.disease_ID") or die(mysqli_error($conn));

    if((mysqli_num_rows($select_diagnosis))>0){
        while($diagnosis_row = mysqli_fetch_assoc($select_diagnosis)){
            $disease_name = $diagnosis_row['disease_name'];
            $disease_code = $diagnosis_row['disease_code'];

            $magonjwa .= "$disease_name($disease_code) ; ";
        }
    }else{

    }
    $select_appointment = mysqli_query($conn, "SELECT patient_No, date_time FROM tbl_appointment WHERE patient_No='$Registration_ID'") or die(mysqli_error($conn));

    if((mysqli_num_rows($select_appointment))>0){
        while($appoint_row= mysqli_fetch_assoc($select_appointment)){
            $date_time = $appoint_row['date_time'];
        }
    }else{
        $date_time = "No result";
    }
    $Grand_Total = 0;
 


    $Patient_Bill_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Bill_ID FROM  tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' "))['Patient_Bill_ID'];
    if($Patient_Bill_ID ==0){
        $select_check_in = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID'   ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_check_in)>0){
            while($ckeck_rw = mysqli_fetch_assoc($select_check_in)){
                $last_check_in = $ckeck_rw['Check_In_ID'];
            }
        }
        //get last Patient_Bill_ID
            $select = mysqli_query($conn,"select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where 
            Registration_ID = '$Registration_ID' and
            Check_In_ID = '$last_check_in' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
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
           
    }
 
    $Grand_Total = 0;
    $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from 
    tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where
    pp.Transaction_type = 'direct cash' and
    pp.Transaction_status <> 'cancelled' and
    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
    pp.Patient_Bill_ID = '$Patient_Bill_ID' and
    ppl.Item_ID=i.Item_ID  and 
    pp.Registration_ID = '$Registration_ID' AND Visible_Status='Others'") or die(mysqli_error($conn));
    
    //and i.Visible_Status='Others'
        
    $nms = mysqli_num_rows($cal);
                                        // echo $Patient_Bill_ID." ".$Folio_Number;
                                        // exit;
    if ($nms > 0) {
        while ($cls = mysqli_fetch_array($cal)) {
            $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
        }
    }
    //========== Mwisho wa kuangalia=======//
    //========== No. of days ==============//
    $gharamaZaKulala =0;
 
    $select_kama_amelazwa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admission_Status FROM tbl_admission WHERE Registration_ID='$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1"))['Admission_Status'];
    if($select_kama_amelazwa=='Admitted'){ 
      
  $days = mysqli_query($conn, "SELECT Check_In_Type,Discount, ad.Admision_ID, ad.Admission_Date_Time, TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_admission ad, tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID  AND pp.Registration_ID='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Others' AND ((i.Product_Name LIKE '%Kulala%') OR (i.Product_Name LIKE '%Accomodation%'))  AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'") or die(mysqli_error($conn));

       while($days_row = mysqli_fetch_assoc($days)){
           $Price = $days_row['Price'];
           $NoOfDay = $days_row['NoOfDay'];

           $gharamaZaKulala = $Price * $NoOfDay;
       }
    }else{
       
        $days = mysqli_query($conn, "SELECT Check_In_Type,Discount, ad.Admision_ID, ad.Admission_Date_Time, TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_Time) AS NoOfDay, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_admission ad, tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID  AND pp.Registration_ID='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Others' AND ((i.Product_Name LIKE '%Kulala%') OR (i.Product_Name LIKE '%Accomodation%'))  AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'") or die(mysqli_error($conn));

       while($days_row = mysqli_fetch_assoc($days)){
           $Price = $days_row['Price'];
           $NoOfDay = $days_row['NoOfDay'];

           $gharamaZaKulala = $Price * $NoOfDay;
       }
    }
   //========== END OF QUERY =============//
  
   $total_vipimo = 0; 
   $total_dawa = 0;  
   $total_upasuaji = 0;  
   $total_others = 0;
   
///check malipo mengine 
                    if (strtolower($payment_method) == 'cash') {
                        $items = mysqli_query($conn,"SELECT Check_In_Type,ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from   tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and     isc.Item_Subcategory_ID = i.Item_Subcategory_ID and    i.Item_ID = ppl.Item_ID and    pp.Transaction_type = 'indirect cash' and    pp.Billing_Type = 'Inpatient Cash' and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type ='post' AND  pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and    pp.Patient_Bill_ID = '$Patient_Bill_ID'  and   pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    } else {
                        $items = mysqli_query($conn,"SELECT Check_In_Type,ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from      tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and  i.Item_ID = ppl.Item_ID and   pp.Transaction_type = 'indirect cash' and  pp.Transaction_status <> 'cancelled' and  pp.Billing_Type = 'Inpatient Cash' and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type='post'  AND   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and     (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and     pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    }
                    $nm = mysqli_num_rows($items);
                    if ($nm > 0) {                      
                        while ($dt = mysqli_fetch_array($items)){
                           $Check_In_Type=  $dt['Check_In_Type'];
                            if(( $Check_In_Type=="Others") || ($Check_In_Type =="Procedure") && ($Check_In_Type !="Kulala") && ($Check_In_Type !="Accomodation") ){
                                $total_others += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                            }else if($Check_In_Type=="Surgery"){
                                $total_upasuaji += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                            }else if($Check_In_Type=="Pharmacy"){
                                $total_dawa += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                            }else if(( $Check_In_Type=="Laboratory") || ($Check_In_Type =="Radiology") ){
                                $total_vipimo += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                            }
                            
                        }
                    
                    }

//mwish wa kucheck


 
    //jumla ya gharama za hospital    
    $items = mysqli_query($conn,"SELECT   SUM((ppl.Price-ppl.Discount) *ppl.Quantity) AS TOTALAMOUNT from tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where ic.Item_Category_ID = isc.Item_Category_ID and isc.Item_Subcategory_ID = i.Item_Subcategory_ID and i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Billing_Type IN ('Inpatient Cash', 'Outpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type = 'post' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and   pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
     if (mysqli_num_rows($items) > 0) {
        while ($dt = mysqli_fetch_array($items)) {
             $jumla_yagharama_za_hospitali = $dt['TOTALAMOUNT'];
        }     
    }
    //======Jumla ya gharama za hospitali==========//
 ?>
<br><br>
<style>
#th{
    background-color: #6cd2ff;
    font-size: 15px;

    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
<fieldset >
    <legend align=center>TEMPORARY EXEMPTION FORM</legend> 
    <hr>
    <table width='90%' >
        <tr>
            <td style='text-align:right;'   width="15%"><b>Namba Hospitali</b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" id="" disabled value="<?php  echo $Registration_ID;?>">  </b></td>
            <td style='text-align:right;'   width="15%"><b>Idara </b></td>
            <td style='text-align:right;'   width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php ?>"> </b></td>
            <td style='text-align:right;'   width="15%"><b>Dhehebu </b></td>
            <td style='text-align:right;'   width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Denomination_Name; ?>"> </b></td>
        </tr>
        <tr>
            <td style='text-align:right;'  width="15%"><b>Jina la Mgonjwa </b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Patient_Name;?>">  </b></td>
            <td style='text-align:right;'  width="15%"><b>Jinsia </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Gender;?>"> </b></td>
            <td style='text-align:right;'  width="15%"><b>Umri </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $age;?>"> </b></td>
        </tr>
        <tr>
            <td style='text-align:right;'  width="15%"><b>Kazi </b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Occupation;?>">  </b></td>
            <td style='text-align:right;'  width="15%"><b>Hali ya Ndoa </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php ?>"> </b></td>
            <td style='text-align:right;'  width="15%"><b>Anuani </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php ?>"> </b></td>
        </tr>
        <tr>
            <td style='text-align:right;'  width="15%"><b>Mtaa/Kitongoji </b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $District;?>">  </b></td>
            <td style='text-align:right;'  width="15%"><b>Mwenyekiti/Balozi </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php ?>"> </b></td>
            <td style='text-align:right;'  width="15%"><b>Jiran wa Karibu </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo  $Kin_Name; ?>"> </b></td>
        </tr>
        <tr>
            <td style='text-align:right;'  width="15%"><b>Tarehe ya Kulazwa </b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $admin_date; ?>">  </b></td>
            <td style='text-align:right;'  width="15%"><b>Tarehe ya kuruhusiwa </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Discharge_Date_Time; ?>"> </b></td>
            <td style='text-align:right;'  width="15%"><b>Tarehe ya Kurudi Kliniki Date </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $date_time; ?>"> </b></td>
        </tr>
        <tr>
            <td style='text-align:right;'  width="15%"><b>Ugonjwa </b></td>
            <td style='text-align:right;'  width="55%" colspan="3"> <?php echo $magonjwa; ?></td>
            <td style='text-align:right;'  width="15%"><b>Simu namba </b></td>
            <td style='text-align:right;'  width="15%"><b><input type="text" name="" disabled='disabled'  id="" value="<?php echo $Phone_Number;?>"> </b></td>
            
        </tr>
    </table>
    <hr>
    <table  width="90%" style="border-top: 1px solid black; border-bottom:1px solid black; border-left:1px solid black;">
                    <caption  id="th"><h4 align="center" style=" padding-bottom:5px;"> FORM YA MAOIMBI YA DHAMANA/ MSAMAHA WA MALIPO YA MATIBABU </h4></caption>
                    <tr>
                        <?php 
                            $select_form_ya_maombi = mysqli_query($conn, "SELECT Jina_la_balozi,simu_ya_balozi, maelezo_ya_nurse_mratibu,nef.Registration_ID, Employee_Name,Employee_Title,Idara, employee_signature,  nef.Employee_ID, nef.created_at FROM tbl_employee e, tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' AND nef.Employee_ID=e.Employee_ID ") or die(mysqli_error($conn));

                            if((mysqli_num_rows($select_form_ya_maombi))>0){
                                while($fomu = mysqli_fetch_assoc($select_form_ya_maombi)){
                                    $Nurse_Exemption_ID = $fomu['Nurse_Exemption_ID'];
                                    $Jina_la_balozi = $fomu['Jina_la_balozi'];
                                    $simu_ya_balozi = $fomu['simu_ya_balozi'];
                                    $maelezo_ya_nurse_mratibu = $fomu['maelezo_ya_nurse_mratibu'];
                                    $idara=$fomu['Idara'];
                            ?>
                            <tr>
                            <td width="25%" style='text-align:right;'><b>Jina la balozi</b> </td>
                            <td width="25%"><input  class="form-control" name="" disabled id="Jina_la_balozi" value="<?php echo $Jina_la_balozi; ?>"></td>
                            <td width="25%" style='text-align:right;'><b> Namba ya simu ya balozi</b></td>
                            <td width="25%"><input class="form-control" disabled name="" id="simu_ya_balozi"  value="<?php echo $simu_ya_balozi; ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="" style='text-align:right;' width="30%"><b>Maelezo mafupi kutoka kwa Mratibu wa idara husika</b></td>
                            <td colspan="3" width="70%"> <textarea disabled name="maelezo_ya_nurse_mratibu" id="" cols="30" rows="4" class="form-control"><?php echo $maelezo_ya_nurse_mratibu; ?></textarea> </td>
                        </tr>
                            
                    <?php
                    $select_patient = mysqli_query($conn, "SELECT  nef.Registration_ID, Employee_Name,Employee_Title, employee_signature, Nurse_Exemption_ID, nef.Employee_ID, nef.created_at FROM tbl_employee e, tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' AND nef.Employee_ID=e.Employee_ID ") or die(mysqli_error($conn));
            
                    while($row = mysqli_fetch_assoc($select_patient)){
                        $Nurse_Exemption_ID =$row['Nurse_Exemption_ID'];            
                        $Registration_ID = $row['Registration_ID'];
                        $Name = $row['Employee_Name'];
                        $Employee_Title = $row['Employee_Title'];
                        $created_at = $row['created_at'];
                        $employee_signature = $row['employee_signature'];
                               if($employee_signature==""||$employee_signature==null){
                                $signature="_______________________";
                            }else{
                                $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                            }
                    }
                }
            }
                    ?>
                    <tr>
                        <td style='text-align:right;'><b> Jina na la Mratibu</b>&nbsp;&nbsp;|&nbsp;&nbsp;<span><?php  echo $Name; ?></span></td>                       
                        <td style='text-align:right;'><b>Idara</b>&nbsp;&nbsp;|&nbsp;&nbsp;<span><?php  echo  $idara; ?></span></td>       
                        <td style='text-align:right;'> <b>Sahii</b> &nbsp;&nbsp;|&nbsp;&nbsp;<span><?php echo $signature; ?></span></td>
                        
                    </tr>
                
                </table>
                <hr>

    <table width="90%">
    <caption  id="th"><h4 align="center" style=" padding-bottom:5px;"> SOCIAL WELFARE UNIT </h4></caption>
        <?php 
            $checked_ndio="";
            $checked_hapana="";
            if($ushauriwabima=="NDIO"){
                $checked_ndio="checked='checked'";
            }else{
                $checked_hapana="checked='checked'";
            }
        ?>
        
        <tr>
        <form action="" method="POST" id="exmpform">
            <td style='text-align:right;'><b>Amepewa ushauri kuhusu bima ya afya</b></td>
            <td ><b><center>
                <span>Ndio<input type="radio" name="ushauriwabima" disabled <?php echo $checked_ndio; ?> id="ushaurindio" value="<?php echo $ushauriwabima; ?>"></span>
                <span>Hapana<input type="radio" name="ushauriwabima" disabled <?php  echo $checked_hapana; ?> id="ushaurihapana" value="<?php echo $ushauriwabima; ?>"></span></center>
                </b>
            </td>
            <td width="40%" style='text-align:right;'>
                <b>Tathimini/Usaili(Iwapo mgonjwa anastahili msaada)</b>
            </td>
            <td>
                <textarea name="tathiminiyamsaada" id=""disabled='disabled'  rows="1" class="form-control"><?php echo $tathiminiyamsaada; ?></textarea>
            </td>
        </tr>
    </table>
    <table width="90%">
        <th colspan="3" id="th">Taarifa za mgonjwa </th>
        <tr>
            <td colspan="3" style='text-align:center;'>
                Mtu mwenye ulemavu <input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mlemavu; ?> style="display: inline;" id='Mlemavu'>
                Mjane / Mgane<input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mjane_mgane; ?> style="display: inline;" id='Mjane_mgane'>
                Mwenye Urahibu <input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mwenye_urahibu; ?> style="display: inline;" id='Mwenye_urahibu'>
                Mfungwa <input type="radio"  name="ajira" <?php echo $Mfungwa; ?> style="display: inline;" id='Mfungwa'>
                Mahabusu <input type="radio"  name="ajira" <?php echo $Mahabusu; ?> style="display: inline;" id='Mahabusu'>
                Mwajiriwa <input type="radio"  name="ajira" <?php echo $Mwajiriwa; ?> style="display: inline;" id='Mwajiriwa'>
                Kajiajiri <input type="radio"  name="ajira" <?php echo $Kajiajiri; ?> style="display: inline;" id='Kajiajiri'><br><br>
                Mzee <input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mzee; ?> style="display: inline;" id='Mzee'>
                Mstaafu <input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mstaafu; ?> style="display: inline;" id='Mstaafu'> 
                Mjamzito <input type="checkbox"  name="taarifa_za_mgonjwa" <?php echo $Mjamzito; ?> style="display: inline;" id='Mjamzito'>                              
                Kuachika(Divorced) <input type="radio"  name="ndoa" <?php echo $Divorced; ?> style="display: inline;" id='Divorced'>                                
                Mwanafunzi <input type="radio"  name="ndoa" <?php echo $Mwanafunzi; ?> style="display: inline;" id='Mwanafunzi'>
                Ameolewa/Kuoa <input type="radio"  name="ndoa" <?php echo $Ameolewa_kuoa; ?> style="display: inline;" id='Ameolewa_kuoa'>
                Mtoto chini ya miaka 5 <input type="radio"  name="ndoa" style="display: inline;" id='Mtoto_chini_5' <?php echo $Mtoto_chini_5; ?>>
                Hajaolewa / Kuoa <input type="radio"  name="ndoa" style="display: inline;" id='HanaNdoa' <?php echo $HanaNdoa; ?>>
                <br><br>
            </td>
        </tr>
        <tr>
            <td style='text-align:right;'>
                <label for="">Jina la mtu aliyemleta mgonjwa Hospitali</label>
                <input type="text" style="display: inline; width:40%;" name="patient_next_kin" id="patient_next_kin" value="<?php echo $patient_next_kin; ?>">                   
            </td>
            <td style='text-align:right;'>
                <label for="">No. ya simu</label>
                <input type="text" style="display: inline; width:40%;" name="next_kin_phoneNo" id="next_kin_phoneNo" value="<?php echo $next_kin_phoneNo; ?>">                   
            </td>
            <td style='text-align:right;'>
                <label for="">Uhusiano wa Mgonjwa na aliyemleta/ alieyemtembelea hospitali</label>
                <input type="text" style="display: inline; width:40%;" name="Next_kin_relationship" id="Next_kin_relationship" value="<?php echo $Next_kin_relationship; ?>">                   
            </td>
        </tr><br>
        
        <tr>
            <td style='text-align:right;'>
                <label for="">Mgonjwa amekuwa akitibiwa wapi</label>
                <input type="text"readonly style="display: inline; width:40%;" name="previous_hospital_treatment" value="<?php echo $previous_hospital_treatment; ?>" id="previous_hospital_treatment">                   
            </td>
            <td style='text-align:right;'>
                <label for="">Kwa muda gani</label>
                <input readonly type="text" style="display: inline; width:40%;" name="time_of_treatment"  value="<?php echo $time_of_treatment; ?>" id="time_of_treatment">                   
            </td>
            <td style='text-align:right;'>
                <span>Amekuwa akichangia matibabu kwa mfumo upi
                    <label for="">Taslimu</label>
                    <input type="checkbox" name="mode_of_treatment" id="Sponsor_cash" <?php echo $Taslimu; ?>disabled >
                </span>                      
            <span>
                    <label for="">Bima</label>
                    <input type="checkbox" name="mode_of_treatment" id="Sponsor_credit"  <?php echo $Bima; ?> disabled>
            </span>                         
            </td>
        </tr>
        <tr>
            <td style='text-align:right;'>
                <b>Alishawahi Kupewa </b>
            </td>
            <td style='text-align:right;'><b>
                <span>Msamaha(Exemption)<input type="checkbox" name="alishawahikupewa" <?php echo $msamahaa; ?> value="<?php echo $ushauri1; ?>"></span>
                <span>Dhamana<input type="checkbox" name="alishawahikupewa" id="" <?php echo $dhamanaa; ?> value="<?php echo $ushauri2; ?>"></span>
                <span>/Aliwahi kutoroka<input type="checkbox"  name="alishawahikupewa" id=""<?php echo $aliwahikutoroka; ?> value="<?php echo $ushauri3; ?>"></span><b>
            </td>
            <td ><span ><b>Kiwango</b><input type="text" style="display: inline; width:60%;" name="kiwango_cha_msamaha" disabled='disabled' value="<?php echo $kiwango_cha_msamaha; ?>"></span></td>
        </tr>
    </table>
    <table width="90%" >
        <tr >
            <td  style='text-align:right;'>
                <b>Mapendekezo Dhamana/ msamaha</b>
            </td>
            <td colspan="5">
                <textarea name="mapendekezomsamaha" id="" disabled rows="1" class="form-control"><?php echo $mapendekezomsamaha; ?></textarea>
            </td>
        </tr>
        <tr>
                            <td style='text-align:right;'><b>Ghamara za siku alizolazwa</b> </td>
                            <td><input  class="form-control" name="" id="" disabled value="<?php echo number_format($gharamaZaKulala); ?>"></td>
                            <td style='text-align:right;'><b> Gharama za dawa</b></td>
                            <td colspan="3"><input class="form-control" name="" id="" disabled value="<?php echo number_format($total_dawa); ?>" ></td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Ghamara za vipimo</b> </td>
                            <td><input  class="form-control" name="" id=""disabled value="<?php echo number_format($total_vipimo); ?>"></td>
                            <td style='text-align:right;'><b> Gharama za upasuaji</b></td>
                            <td><input class="form-control" name="" id="" disabled value="<?php echo number_format($total_upasuaji); ?>"></td>
                            <td style='text-align:right;'><b> Mengineyo</b></td>
                            <td><input  class="form-control" name="" disabled value="<?php echo number_format($total_others); ?>"></td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Jumla ya gharama za Hospitali</b></td>
                            <td><input class="form-control" name="" id="" disabled='disabled'value="<?php echo number_format($jumla_yagharama_za_hospitali);?>"></td>
                            <td style='text-align:right;'><b>Gharama iliyolipwa</b></td>
                            <td ><input class="form-control" name="" id=""disabled='disabled' value="<?php echo number_format($Grand_Total); ?>" ></td>
                            <?php 
                                $checked_Dhamana="";
                                $checked_Msamaha="";
                                if($Anaombewa=="Dhamana"){
                                    $checked_Dhamana="checked='checked'";
                                }else{
                                    $checked_Msamaha="checked='checked'";
                                }
                            ?>
                            <td style='text-align:right;'>
                                <b> 
                                    <span>Dhamana 
                                        <input type="radio" class="" name="Anaombewa" disabled id="anaombewa_dhamana" <?php echo $checked_Dhamana;?>>
                                    </span> 
                                    <span>Msamaha 
                                        <input type="radio" class="" name="Anaombewa" disabled id="Anaombewa_msamaha" <?php echo $checked_Msamaha;?>>
                                    </span>
                                </b>
                            </td>
                            <td> <span><b>Kiasi kinachoombewa</b></span><span><input class="form-control"disabled name="kiasikinachoombewamshamaha" id="" value="<?php echo number_format($kiasikinachoombewamshamaha);?>" ></span></td>
                            
                        </tr>
       
    </table>
    <?php 
        $select_patient = mysqli_query($conn, "SELECT  tef.Registration_ID, Employee_Name,Employee_Title, employee_signature, Exemption_ID, tef.Employee_ID, tef.created_at FROM tbl_employee e, tbl_temporary_exemption_form tef WHERE tef.Registration_ID='$Registration_ID' AND Exemption_ID='$exemptionID' AND tef.Employee_ID=e.Employee_ID ") or die(mysqli_error($conn));

        while($row = mysqli_fetch_assoc($select_patient)){
            $Exemption_ID =$row['Exemption_ID'];            
            $Registration_ID = $row['Registration_ID'];
            $Name = $row['Employee_Name'];
            $Employee_Title = $row['Employee_Title'];
            $created_at = $row['created_at'];
            $employee_signature = $row['employee_signature'];
                   if($employee_signature==""||$employee_signature==null){
                    $signature="_______________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }
        }
    ?>
    <table width="90%" >
            <thead><h4><b>Mgonjwa amesailiwa na</b></h4></thead>
            <tbody>
                <tr>
                    <td style='text-align:right;'><b> Jina Kamili</b></td>
                    <td><input type="text" disabled='disabled' value=" <?php  echo $Name; ?>"></td>
                    <td style='text-align:right;'><b>Cheo</b></td>
                    <td><input type="text" disabled='disabled' value="<?php  echo $Employee_Title; ?>"></td>
                </tr>
                <tr>
                    <td style='text-align:right;'> <b>Sahii</b> </td>
                    <td><?php echo $signature; ?></td>
                    <td style='text-align:right;'><b>Tarehe</b></td>
                    <td><input type="text" disabled='disabled' value="<?php echo $created_at;?>"></td>
                </tr>                
            </tbody>
    </table><hr>
    <table width="90%">
    <caption  align="center" id="th"><h4 align="center" style=" padding-bottom:5px;"> TATHIMINI YA PHRO </h4></caption>

        <tbody>
        <?php
            $select_tathimini_ya_PHRO = mysqli_query($conn, "SELECT tathiminiyaphro from tbl_expemption_phro where Exemption_ID='$Exemption_ID'" ) or die(mysqli_error($conn));
            if((mysqli_num_rows($select_tathimini_ya_PHRO))>0){
                while($row = mysqli_fetch_assoc($select_tathimini_ya_PHRO)){
                    $tathiminiyaphro = $row['tathiminiyaphro'];
                    
                    echo "<tr><td>$tathiminiyaphro</td></tr>";
                }
            }else{
        ?>
        <tr>
            <td style='text-align:right;'><b>TATHIMINI YA PHRO</b></td>
            <input type="text" id="Exemption_ID" style="display: none;" value="<?php echo $Exemption_ID;?>">
            <td ><textarea name="tathiminiphro" class="form-control" id="tathimin" disabled  rows="2"></textarea></td>
            <td><button type="button" id="phrobtn" class="art-button-green pull-right" onclick="tathimini_PHRO(<?php echo $Exemption_ID;?>,<?php echo $Registration_ID;?>)">JAZA</button></td>
        </tr>
            <?php } ?>
        </tbody>
    </table>
    
</fieldset>
<fieldset>
    <legend align="center">MAONI YA UTAWALA</legend>
    <table width="90%">
        <tr><h4>Maoni /idhinisho la DHS</h4></tr>
        <tbody><input type="text" id="Exemption_ID" style="display: none;" value="<?php echo $Exemption_ID;?>">
            <?php
                $select_maoni_ya_DHS = mysqli_query($conn, "SELECT maoniDHS, sababudhs,MaoniDHS_ID, emd.Employee_ID, Employee_Name, emd.created_at, employee_signature from tbl_exemption_maoni_dhs emd, tbl_employee e where Exemption_ID='$Exemption_ID' AND MaoniDHS_ID='$MaoniDHS_ID' AND emd.Employee_ID =e.Employee_ID" ) or die(mysqli_error($conn));

                if((mysqli_num_rows($select_maoni_ya_DHS))>0){
                    while($row = mysqli_fetch_assoc($select_maoni_ya_DHS)){
                        $MaoniDHS_ID = $row['MaoniDHS_ID'];
                        $maoniDHS = $row['maoniDHS'];
                        $sababudhs = $row['sababudhs'];
                        $Employee_Name = $row['Employee_Name'];            
                        $created_at = $row['created_at'];
                        $employee_signature = $row['employee_signature'];
                            if($employee_signature==""||$employee_signature==null){
                                $signature="_______________________";
                            }else{
                                $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                            }
                    }
                }
                ?>
                <tr>
                <td width="35%">
                    <?php 
                        $checkedndio="";
                        $checkedhapana="";
                        if($maoniDHS=="NDIO"){
                            $checkedndio="checked='checked'";
                        }else{
                            $checkedhapana="checked='checked'";
                        }
                    ?>
                    <span><b>Ndio</b><input type="radio" name="maoniDHS" <?php echo $checkedndio; ?> id="dhsndio" value="<?php echo $maoniDHS; ?>"></span>
                    <span><b>Hapana</b>  <input type="radio" name="maoniDHS" <?php echo $checkedhapana; ?> id="dhshapana" value="<?php echo $maoniDHS; ?>"></span>                   
                </td>                
                <td style='text-align:left; width:5%;'>
                   <b>Sababu </b> 
                </td>
                <td colspan="3" >
                   <textarea style="width: 100%; height:100px; overflow-y:scroll" class='form-control' disabled><?php echo $sababudhs; ?></textarea>
                </td>
                <td><button type="button" class="art-button-green pull-right" onclick="update_maoni_ya_DHS(<?php echo $Exemption_ID;?>,<?php echo $Registration_ID;?>,<?php echo $MaoniDHS_ID;?>)">EDIT </button></td>
                
            </tr>
                   
        </tbody>
    </table>
</fieldset>
<div id="tathiminidialog">

</div>
<div id="maoniDHS">
</div>
<script>
    function Edit_tathimini_PHRO(Exemption_ID, Registration_ID,PHRO_ID){
        
        $.ajax({
            type:'POST',
            url:'exemption_tathimini_PHRO.php',
            data:{Exemption_ID:Exemption_ID,Registration_ID:Registration_ID,PHRO_ID:PHRO_ID, edit_phro:''},
            success:function (responce){
                $("#tathiminidialog").dialog({
                    title: 'TATHIMINI  YA MSAMAHA',
                    width: '70%',
                    height: 350,
                    modal: true,
                });
                $("#tathiminidialog").html(responce)
            }
        });

    }
    function update_tathimini(PHRO_ID){
        var tathimini = $("#tathimini").val();
        alert(PHRO_ID)
        $.ajax({
            type:'POST',
            url:'ajax_exemptionform.php',
            data:{PHRO_ID:PHRO_ID, tathiminiyaphro:tathimini,update_tathiminPHRO:""},
            success:function(responce){
                alert("Date saved successful");                              
                $("#tathiminidialog").dialog("close")
              
            }
        });
    }

    // $(document).ready(function(){
    //     showtashimin();
    // });
    // function showtashimin(){
    //     var Exemption_ID = $("#Exemption_ID").val();        
    //     $.ajax({
    //         type:'POST',
    //         url:'ajax_exemptionform.php',
    //         data:{Exemption_ID:Exemption_ID},
    //         success:function(responce){
    //             $("#tathimin").html(responce);
    //             //$("#phrobtn").hide();                
    //         }
    //     });
    // }

    function update_maoni_ya_DHS(Exemption_ID, Registration_ID,MaoniDHS_ID){
        
        $.ajax({
            type:'POST',
            url:'exemption_maoni_ya_DHS.php',
            data:{Exemption_ID:Exemption_ID,Registration_ID:Registration_ID,MaoniDHS_ID:MaoniDHS_ID},
            success:function (responce){
                $("#maoniDHS").dialog({
                    title: 'IDHINISHO  LA MSAMAHA',
                    width: '70%',
                    height: 350,
                    modal: true,
                });
                $("#maoniDHS").html(responce)
            }
        });

    }
    function updatemaoni(MaoniDHS_ID){
        var maoniDHS = "";
        var sababudhs= $("#sababuDHS").val();
        if($("#dhsndio ").is(":checked")){
            maoniDHS="NDIO";
           
        }          
        if($("#dhshapana ").is(":checked")){
            maoniDHS="HAPANA";
            
        }      
        if(sababudhs==""){
            $("#sababuDHS").css("border", "2px solid red");
        }
        
        $.ajax({
            type:'POST',
            url:'ajax_exemptionform.php',
            data:{updatemaoniyadhs:"",maoniDHS:maoniDHS,sababudhs:sababudhs,MaoniDHS_ID:MaoniDHS_ID },
            success:function(){
                alert("Saved successful");
                $("#maoniDHS").dialog("close");
                document.location.reload();
            }
        });
    }
    

    function showIdhinisholaDHS(){
        var Exemption_ID = $("#Exemption_ID").val();
        $.ajax({
            type:'POST',
            url:'ajax_exemptionform.php',
            data:{Exemption_ID:Exemption_ID, DHS:""},
            success:function(responce){
                $("#tathimin").html(responce);                
            }
        });
    }

    function checkphrovalue(){
        var phrovalue = $("#tathimin").val();       
        if(phrovalue==""){
            alert("Jaza tathimini ya PHRO Tafadhali!!!!");
            $("#tathimin").css("border", "2px solid red");
        }
    }
</script>