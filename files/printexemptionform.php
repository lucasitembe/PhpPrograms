<?php
session_start();
include("./includes/connection.php");

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];



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

// if(isset($_GET['Nurse_Exemption_ID'])){
//     $Nurse_Exemption_ID = $_GET['Nurse_Exemption_ID'];
// }else{
//     $Nurse_Exemption_ID=0;
// }


//<!-- new date function (Contain years, Months and days)-->

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

//<!-- end of the function -->


//    select patient information to perform check in process =
$department = "___________";
$Occupation ="___________";
$Tribe ="___________";
$Mwenyekiti= "__________";
$marital_status ="__________";
$address = "_________";
$Phone_Number = "_________";
$Religion_Name="________";

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT
                            patient_type,Status,service_no,dependancy_id,dependecny_service_no,military_unit,
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,Patient_Picture,
                                    Gender,Religion_Name,Denomination_Name,payment_method,
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
            $payment_method = $row['payment_method'];
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

<?php


    $get_reception_setting = mysqli_query($conn,"select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_reception_setting);
    if ($no > 0) {
        while ($data = mysqli_fetch_array($get_reception_setting)) {
            $Reception_Picking_Items = $data['Reception_Picking_Items'];
        }
    } else {
        $Reception_Picking_Items = 'no';
    }
    
    $exemptiondata  = mysqli_query($conn, "SELECT ushauriwabima,mode_of_treatment,taarifa_za_mgonjwa,patient_next_kin,next_kin_phoneNo,Next_kin_relationship,Nurse_Exemption_ID, Anaombewa, Exemption_ID, tathiminiyamsaada,alishawahikupewa, kiasikinachoombewamshamaha, kiwango_cha_msamaha,previous_hospital_treatment, time_of_treatment,mapendekezomsamaha FROM tbl_temporary_exemption_form tef WHERE Registration_ID ='$Registration_ID' AND Exemption_ID='$exemptionID' ");

        while($ex_data = mysqli_fetch_assoc($exemptiondata)){
            $ushauriwabima  = $ex_data['ushauriwabima'];
            $tathiminiyamsaada = $ex_data['tathiminiyamsaada'];
            $alishawahikupewa = explode(',', $ex_data['alishawahikupewa']);
            $kiasikinachoombewamshamaha = $ex_data['kiasikinachoombewamshamaha'];
            $kiwango_cha_msamaha = $ex_data['kiwango_cha_msamaha'];
            $mapendekezomsamaha = $ex_data['mapendekezomsamaha'];
            $Nurse_Exemption_ID = $ex_data['Nurse_Exemption_ID'];
            $ushauri1 = $alishawahikupewa[0];
            $ushauri2 = $alishawahikupewa[1]; 
            $ushauri3 = $alishawahikupewa[2];
            $Anaombewa = $ex_data['Anaombewa'];

            $mode_of_treatment = $ex_data['mode_of_treatment'];
            
            $taarifa_za_mgonjwa = explode(',',$ex_data['taarifa_za_mgonjwa']);
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
                    $HanaNdoa = 'checked="checked"';
                }
            }
            $patient_next_kin = $ex_data['patient_next_kin'];
            $next_kin_phoneNo = $ex_data['next_kin_phoneNo'];
            $Next_kin_relationship = $ex_data['Next_kin_relationship'];
            $previous_hospital_treatment = $ex_data['previous_hospital_treatment'];
            $time_of_treatment = $ex_data['time_of_treatment'];
        }
    //$admin_date = '';
  

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
            if($Folio_Number==""){
            $Folio_Number=0;
            }
    }
    $Hospital_Ward_Name="";
    $select_hospital_ward = mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward hw, tbl_admission a WHERE a.Hospital_Ward_ID=hw.Hospital_Ward_ID AND a.Registration_ID='$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_hospital_ward))>0){
        while($ward_rw = mysqli_fetch_assoc($select_hospital_ward)){
            $Hospital_Ward_Name = $ward_rw['Hospital_Ward_Name'];
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
    
       } 
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
      
       $last_check_in=0;
       $select_check_in = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID'   ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
       if(mysqli_num_rows($select_check_in)>0){
           while($ckeck_rw = mysqli_fetch_assoc($select_check_in)){
               $last_check_in = $ckeck_rw['Check_In_ID'];
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

            
        $nms = mysqli_num_rows($cal);
                                            
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
    
$days = mysqli_query($conn, "SELECT Check_In_Type,Discount, ad.Admision_ID, ad.Admission_Date_Time, TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_admission ad, tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID  AND pp.Registration_ID='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Others' AND ((i.Product_Name LIKE '%Kulala%') OR (i.Product_Name LIKE '%Accomodation%'))  AND Pre_Paid IN ( '1', '0') AND pp.Billing_Type='Inpatient Cash' AND pp.payment_type='post' AND pp.Patient_Bill_ID = '$Patient_Bill_ID'") or die(mysqli_error($conn));

    while($days_row = mysqli_fetch_assoc($days)){
        $Price = $days_row['Price'];
        $NoOfDay = $days_row['NoOfDay'];

        $gharamaZaKulala = $Price * $NoOfDay;
    }
}else{
   $days = mysqli_query($conn, "SELECT Check_In_Type,Discount, ad.Admision_ID, ad.Admission_Date_Time, TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_Time) AS NoOfDay, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_admission ad, tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID  AND pp.Registration_ID='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Others' AND ((i.Product_Name LIKE '%Kulala%') OR (i.Product_Name LIKE '%Accomodation%'))  AND Pre_Paid IN ( '1', '0') AND pp.Billing_Type='Inpatient Cash' AND pp.payment_type='post' AND pp.Patient_Bill_ID = '$Patient_Bill_ID'") or die(mysqli_error($conn));

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
                   $items = mysqli_query($conn,"SELECT Check_In_Type,ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from   tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and     isc.Item_Subcategory_ID = i.Item_Subcategory_ID and    i.Item_ID = ppl.Item_ID and    pp.Transaction_type = 'indirect cash' and    pp.Billing_Type in ('Inpatient Cash', 'Outpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type ='post' AND  pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and    pp.Patient_Bill_ID = '$Patient_Bill_ID'  and   pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

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
if (strtolower($payment_method) == 'cash') {
   $tohospitalcost = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
               tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
               ic.Item_Category_ID = isc.Item_Category_ID and
               isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
               i.Item_ID = ppl.Item_ID and
               pp.Transaction_type = 'indirect cash' and pp.Billing_Type IN ( 'Inpatient Cash', 'Outpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type  = 'post'   and  pp.Transaction_status <> 'cancelled' and
               pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
               pp.Patient_Bill_ID = '$Patient_Bill_ID'  and
                
               pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
              
} else {
   $tohospitalcost = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
               tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
               ic.Item_Category_ID = isc.Item_Category_ID and
               isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
               i.Item_ID = ppl.Item_ID and
               pp.Transaction_type = 'indirect cash' and
               pp.Transaction_status <> 'cancelled' and
               pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
               (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit')  and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type  IN ('post') AND
               pp.Patient_Bill_ID = '$Patient_Bill_ID'  and
                
               pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
}
    $nm = mysqli_num_rows($tohospitalcost);
    if ($nm > 0) {
        $temp = 0;
        $Sub_Total = 0;
        while ($dt = mysqli_fetch_array($tohospitalcost)) {
            $Hospital_Ward_ID=$dt['Hospital_Ward_ID'];
            $sql_select_ward_name_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_ward_name_result)>0){
                $ward_name_row=mysqli_fetch_assoc($sql_select_ward_name_result);
                $Hospital_Ward_Name=$ward_name_row['Hospital_Ward_Name'];
            }else{
                $Hospital_Ward_Name="";
            }
            $Sponsor_ID = $dt['Sponsor_ID'];
            $Billing_Type_sts = $dt['Billing_Type'];
            $sql_select_sponsor_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_sponsor_result)>0){
                $sponsor_name_row=mysqli_fetch_assoc($sql_select_sponsor_result);
               $spnsor_name=$sponsor_name_row['Guarantor_Name'];
            }else{
                $spnsor_name="";
            }
           
            $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
            $jumla_yagharama_za_hospitali += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
        }
     
    }
    //======Jumla ya gharama za hospitali==========//
 
  $kiasi_anachostahili = $jumla_yagharama_za_hospitali-$Grand_Total;
        
        
     
$htm .="

<fieldset>
   
    <table width='100%' >
    <tr><td style='text-align:center' colspan='6'><img src='./branchBanner/branchBanner.png'></td></tr>
        
        <tr>
            <td><b>FORM NO: $exemptionID </b></td><td style='text-align:center' colspan='5'><u><h4>SOCIAL WALFARE UNIT <br>TEMPORARY EXEMPTION FORM </h4></u><br><br></td>
        </tr>
        <tr>
            <td>Namba Hospitali</td>
            <td width='20%'><b>" .$Registration_ID ." </b></td>
            <td>Idara </td>
            <td><b> $Hospital_Ward_Name</b></td>
            <td>Dhehebu </td>
            <td><b> ". $Denomination_Name ."</b></td>
        </tr>
        <tr>
            <td>Jina la Mgonjwa </td>
            <td><b> $Patient_Name  </b></td>
            <td>Jinsia </td>
            <td><b> $Gender </b></td>
            <td>Umri </td>
            <td><b> $age </b></td>
        </tr>
        <tr>
            <td>Kazi </td>
            <td><b> $Occupation  </b></td>
            <td>Hali ya Ndoa </td>
            <td><b>$marital_status </b></td>
            <td>Anuani </td>
            <td><b>$address</b></td>
        </tr>
        <tr>
            <td>Mtaa/Kitongoji</td>
            <td><b> $District  </b></td>
            <td>Mwenyekiti/Balozi </td>
            <td><b>$Mwenyekiti</b></td>
            <td><b>Jiran wa Karibu </b></td>
            <td> $Kin_Name </td>
        </tr>
        <tr>
            <td>Tarehe ya Kulazwa </td>
            <td><b> $admin_date  </b></td>
            <td>Tarehe ya kuruhusiwa </td>
            <td><b> $Discharge_Date_Time </b></td>
            <td>Tarehe ya Kurudi Kliniki  </td>
            <td><b> $date_time </b></td>
        </tr>
        <tr>
            <td>Ugonjwa </td>
            <td colspan='3'>  $magonjwa</td>
            <td>Namba ya Simu </td>
            <td><b> $Phone_Number </b></td>            
        </tr>
    </table>
    <hr>
    <table  width='100%' style='border: 1px solid black;'>
                    <caption  align='center' style='background-color: #cccccc; height:40px;'><h5 align='center' style='padding-bottom:5px;'> FORM YA MAOIMBI YA DHAMANA/ MSAMAHA WA MALIPO YA MATIBABU </h5></caption>
                    ";
                        
                    $select_form_ya_maombi = mysqli_query($conn, "SELECT Jina_la_balozi,simu_ya_balozi, maelezo_ya_nurse_mratibu,nef.Registration_ID, Employee_Name,Employee_Title,Idara, employee_signature,  nef.Employee_ID, nef.created_at FROM tbl_employee e, tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' AND nef.Employee_ID=e.Employee_ID ") or die(mysqli_error($conn));

                    if((mysqli_num_rows($select_form_ya_maombi))>0){
                        while($fomu = mysqli_fetch_assoc($select_form_ya_maombi)){
                            $Nurse_Exemption_ID = $fomu['Nurse_Exemption_ID'];
                            $Jina_la_balozi = $fomu['Jina_la_balozi'];
                            $simu_ya_balozi = $fomu['simu_ya_balozi'];
                            $maelezo_ya_nurse_mratibu = $fomu['maelezo_ya_nurse_mratibu'];
                            $idara=$fomu['Idara'];
                           
            $htm .="    <tr>
                            <td width='25%' style='text-align:right;'>Jina la balozi</td>
                            <td width='25%'><b> $Jina_la_balozi</b></td>
                            <td width='25%' style='text-align:right;'>Namba ya simu ya balozi</td>
                            <td width='25%'><b> $simu_ya_balozi</b></td>
                        </tr>
                        <tr>
                            <td  style='text-align:right;' width='30%'>Maelezo mafupi kutoka kwa Mratibu wa idara husika</td>
                            <td colspan='3' width='70%'> <b> $maelezo_ya_nurse_mratibu </b></td>
                        </tr>";
                            
                    
                    $select_nurse = mysqli_query($conn, "SELECT  nef.Registration_ID, Employee_Name,Employee_Title, employee_signature, Nurse_Exemption_ID, nef.Employee_ID, nef.created_at FROM tbl_employee e, tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' AND nef.Employee_ID=e.Employee_ID ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($select_nurse))>0){
                    while($row = mysqli_fetch_assoc($select_nurse)){
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

            $htm .=" <tr>
                        <td style='text-align:right;' width='50%' colspan='2'> Jina na la Mratibu &nbsp;&nbsp;|&nbsp;&nbsp;<span><b>$Name </b></span></td>                       
                        <td style='text-align:right;' width='30%'>Idara &nbsp;&nbsp;|&nbsp;&nbsp;<span><b>$idara </b></span></td>       
                        <td style='text-align:right;' width='20%'> Sahii  &nbsp;&nbsp;|&nbsp;&nbsp;<span><b>$signature </b></span></td>
                        
                    </tr>";
                    }
                }else{
                    $htm .="No result found";
                }

                }
            }
                    
            
                
    $htm .="</table>
        <caption  align='center' style='background-color: #cccccc; height:40px;'><h5 align='center' style='padding-bottom:5px;'> USTAWI WA JAMII </h5></caption>";

    $htm ."<table width='100%' >
    
    ";

        
            
       
    $htm .="<tr>        
            <td >Amepewa ushauri kuhusu bima ya afya</td>
            <td >
                <span> <b>$ushauriwabima</b></span>                              
            </td>            
        </tr>
        <tr>
            <td colspan='2'>
                Tathimini/Usaili(Iwapo mgonjwa anastahili msaada)
            </td>
            
        </tr>
        <tr>
            <td colspan='2'>
                <b> $tathiminiyamsaada</b>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <h4 style='text-align:center;'><b>Taarifa za mgonjwa</b></h4>
    
    <table width='100%'>
        <tr>
            <td>Mjane / Mgane</td>
            <td><input type='checkbox' $Mjane_mgane></td>
            <td>Mtu mwenye Ulemavu</td>
            <td><input type='checkbox' $Mlemavu ></td>
        </tr>
        <tr>
            <td>Mfungwa</td>
            <td><input type='checkbox' $Mfungwa></td>
            <td>Mahabusu</td>
            <td><input type='checkbox' $Mahabusu></td>
        </tr>
        <tr>
            <td>Mwenye Urahibu</td>
            <td><input type='checkbox' $Mwenye_urahibu></td>
            <td>Mzee</td>
            <td><input type='checkbox' $Mzee  ></td>
        </tr>
       
        <tr>
            <td>Kajiajiri</td>
            <td><input type='checkbox' $Kajiajiri></td>
            <td>Mwanafunzi</td>
            <td><input type='checkbox' $Mwanafunzi ></td>
        </tr>
        <tr>
            <td>Mstaafu</td>
            <td><input type='checkbox' $Mstaafu ></td>
            <td>Mjamzito</td>
            <td><input type='checkbox' $Mjamzito></td>
        </tr>
        <tr>
            <td>Kuachika(Divorced)</td>
            <td><input type='checkbox'  $Divorced></td>
            <td>Ameolewa/Kuoa</td>
            <td><input type='checkbox' $Ameolewa_kuoa ></td>
        </tr>
        <tr>
            <td>Mtoto chini ya miaka 5</td>
            <td><input type='checkbox' $Mtoto_chini_5 ></td>
            <td>Hajaoa/Hajaolewa</td>
            <td><input type='checkbox' $HanaNdoa ></td>
        </tr>
        <tr>
            
            <td>Mwajiriwa</td>
            <td><input type='checkbox' $Mwajiriwa></td>
        </tr>
    </table>
    <h5 style='text-align:center;'>MAELEZO YA ZIADA</h5>
    <table width='100%'>
        <tr>
            <td>Jina la mtu aliyemleta mgonjwa Hospitalini</td>
            <td><b> $patient_next_kin </b></td>
            <td>No ya simu</td>
            <td><b>$next_kin_phoneNo</b></td>
        </tr>
        <tr>
            <td>Uhusiano wa Ngonjwa na aliyemleta/ aliemtembelea hospitali</td>
            <td><b>$Next_kin_relationship</b></td>
            <td>Mgonjwa amekuwa akitibiwa wapi</td>
            <td><b>$previous_hospital_treatment</b></td>
        </tr>
        <tr>
            <td>Kwa muda gani</td>
            <td><b>$time_of_treatment</b></td>
            <td>Amekuwa akichangia matibabu kwa mfumo upi</td>
            <td><b>$mode_of_treatment</b></td>
        </tr>
        
    </table>
    <table width='100%'>
        <tr>
            <td>
                Alishawahi Kupewa 
            </td>";
            $msamahaa="";
                $dhamanaa="";
                $aliwahikutoroka="";
                if($ushauri1 =="Msamaha"){
                    $msamahaa ="checked='checked'";
                }
                if($ushauri2 =="Dhamana"){
                    $dhamanaa = "checked='checked'";
                }
                if($ushauri3 == "Kutoroka"){
                    $aliwahikutoroka ="checked='checked'";
                }
           $htm.=" <td><b>
                <span><u>$ushauri1</u></span>
                <span><u>$ushauri2</u></span> 
                <span><u>$ushauri3 </u></span><b>
            </td>
            <td ><span >Kiwango</td><td><b> $kiwango_cha_msamaha</b></span></td>
        </tr>
    </table>
    <table width='100%' >
        <tr >
            <td  >
                Mapendekezo Dhamana/ msamaha
            </td>
            <td colspan='6'>
                <b> $mapendekezomsamaha <br/></b>
            </td>
        </tr>
        <tr><td colspan='6'></td></tr>
        <tr>
            <td width='45%' colspan='2'>Ghamara za siku alizolazwa </td>
            <td width='5%'><b> ".number_format($gharamaZaKulala)."</b></td>
            <td width='45%' colspan='2'> Gharama za dawa</td>
            <td width='5%'> <b>".number_format($total_dawa)."</b></td>
        </tr>
        <tr>
            <td width='25%' >Ghamara za vipimo </td>
            <td width='5%' ><b> ".number_format($total_vipimo)."</b></td>
            <td width='25%' > Gharama za upasuaji</td>
            <td width='5%' ><b> ".number_format($total_upasuaji)."</b></td>
            <td width='25%' >Mengineyo</td>
            <td width='5%' > <b> ".number_format($total_others)."</b></td>
        </tr>
        <tr>
            <td width='25%' >Jumla ya gharama za Hospitali</td>
            <td width='5%'><b> ".number_format($jumla_yagharama_za_hospitali)."</b></td>
            <td width='25%' >Gharama iliyolipwa</td>
            <td width='5%' ><b>".number_format( $Grand_Total)."</b></td>
            <td width='15%' ><span>Anaombewa<b>&nbsp;&nbsp;$Anaombewa</b></span> </td>
            <td width='15%'><span>Kiasi <b> ".number_format($kiasikinachoombewamshamaha)."</b></span></td>
        </tr>
       
    </table>";
    
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
    $htm .="
            <table width='100%' >
                    <thead>
                        <tr><td align='center' colspan='6'><h5><b>Mgonjwa amesailiwa na</b></h5></td></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td >Jina Kamili</td>
                            <td><b>  $Name</b></td>
                            <td >Cheo</td>
                            <td> <b>$Employee_Title</b></td>
                        </tr>
                        <tr>
                            <td > Sahii </td>
                            <td><b> $signature</b></td>
                            <td >Tarehe</td>
                            <td><b> $created_at</b></td>
                        </tr>                
                    </tbody>
            </table>";
            // $select_tathimini_ya_PHRO = mysqli_query($conn, "SELECT tathiminiyaphro from tbl_expemption_phro where Exemption_ID='$Exemption_ID'" ) or die(mysqli_error($conn));

            // while($row = mysqli_fetch_assoc($select_tathimini_ya_PHRO)){
            //     $tathiminiyaphro = $row['tathiminiyaphro'];
                
            // }
            // $htm .="<hr>
            //         <table width='100%'>
            //         <caption  align='center' style='background-color: #cccccc; height:40px;'><h5 align='center' style='padding-bottom:5px;'> TATHIMINI YA PHRO </h5></caption>
            //         <tbody>
            //         <tr>
                                              
            //             <td colspan='6' width='100%'><b>$tathiminiyaphro</b></td>
            //         </tr>
            //         </tbody>
            //     </table>
            //     ";
            $select_maoni_ya_DHS = mysqli_query($conn, "SELECT maoniDHS, sababudhs, emd.Employee_ID, Employee_Name, emd.created_at, employee_signature from tbl_exemption_maoni_dhs emd, tbl_employee e where Exemption_ID='$Exemption_ID' AND emd.Employee_ID =e.Employee_ID" ) or die(mysqli_error($conn));

            if((mysqli_num_rows($select_maoni_ya_DHS))>0){
                while($row = mysqli_fetch_assoc($select_maoni_ya_DHS)){
                    $maoniDHS = $row['maoniDHS'];
                    $sababudhs = $row['sababudhs'];
                    $Employee_Name = $row['Employee_Name'];            
                    $created_at = $row['created_at'];
                    $employee_signature = $row['employee_signature'];
                        if($employee_signature==""||$employee_signature==null){
                            $signature="______________________";
                        }else{
                            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                        }
                }
            }
                $checkedndio="";
                $checkedhapana="";
                if($maoniDHS=="NDIO"){
                    $checkedndio="checked='checked'";
                }else{
                    $checkedhapana="checked='checked'";
                }
        $htm .="<hr><table width='100%'> 
        <caption  align='center' style='background-color: #cccccc; height:20px;'><h5 align='center' style='padding-bottom:5px;'>  MAONI YA UTAWALA  </h5></caption>
                    <tbody>
                       
                        <tr>
                            
                            <td style='text-align:right;' colspan='2' width='100%'>Maoni /idhinisho la Muhasibu &nbsp;&nbsp;|&nbsp;&nbsp;<span><b>$maoniDHS</b></span></td> 
                        </tr>
                        <tr>
                            <td width='5%'>
                                Sababu  
                            </td>
                            <td colspan='4' width='95%'>
                                 <b>$sababudhs</b>
                            </td>
                        </tr><br>
                        <tr>
                            <td>Jina la aliyeidhinisha</td>
                            <td><b>$Employee_Name </b></td>
                            <td style='text-align:right'>Sahii</td>
                            <td><b> $signature</b></td>
                            <td>Tarehe</td>
                            <td style='text-align:left'><b>$created_at</b></td>
                        </tr>
                    </tbody>
                </table>";

        $htm = mb_convert_encoding($htm, 'UTF-8', 'UTF-8');
        include("./MPDF/mpdf.php");
        $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
        $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} | Powered By GPITG LTD');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
            ?>
