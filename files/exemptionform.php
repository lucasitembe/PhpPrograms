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
// if (isset($_SESSION['userinfo'])) {
//     if (isset($_SESSION['userinfo']['Reception_Works'])) {
//         if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
//             header("Location: ./index.php?InvalidPrivilege=yes");
//         }
//     } else {
//         header("Location: ./index.php?InvalidPrivilege=yes");
//     }
// } else {
//     @session_destroy();
// }

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
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
                                Date_Of_Birth,Patient_Picture,
                                    Gender,Religion_Name,Denomination_Name,
					pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                                        Member_Number,Member_Card_Expire_Date,payment_method,
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
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Exemption = strtolower($row['Exemption']); //
            $Diseased = strtolower($row['Diseased']);
            $national_id = $row['national_id'];
            $Patient_Picture = $row['Patient_Picture'];
            $Religion_Name = $row['Religion_Name'];
            $Denomination_Name = $row['Denomination_Name'];
            $payment_method = $row['payment_method'];
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
    $verify = mysqli_query($conn,"SELECT msamaha_ID from tbl_msamaha where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($verify);
    if ($nm < 1) {
        $Exemption_Details = 'not available';
    }
}
//SELECT LAST VISIT DATE

$lastvisitdate = mysqli_query($conn,"SELECT Check_In_Date from tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
$lastvisit = mysqli_fetch_assoc($lastvisitdate);
$Last_Check_In_Date = $lastvisit['Check_In_Date'];
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

</style>
<?php 
    $Patient_Bill_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Bill_ID FROM  tbl_nurse_exemption_form nef WHERE nef.Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' "))['Patient_Bill_ID'];

    if($Patient_Bill_ID ==0){
        $select_check_in = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID'   ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_check_in)>0){
            while($ckeck_rw = mysqli_fetch_assoc($select_check_in)){
                $last_check_in = $ckeck_rw['Check_In_ID'];
            }
        }
        //get last Patient_Bill_ID
        $select = mysqli_query($conn,"SELECT Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where  Registration_ID = '$Registration_ID' and  Check_In_ID = '$last_check_in' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
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
$Hospital_Ward_Name="";
$select_hospital_ward = mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward hw, tbl_admission a WHERE a.Hospital_Ward_ID=hw.Hospital_Ward_ID AND a.Registration_ID='$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
if((mysqli_num_rows($select_hospital_ward))>0){
    while($ward_rw = mysqli_fetch_assoc($select_hospital_ward)){
        $Hospital_Ward_Name = $ward_rw['Hospital_Ward_Name'];
    }
}
 
   //$admin_date = '';
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
   //get last Patient_Bill_ID
   
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
            $items = mysqli_query($conn,"SELECT Check_In_Type,ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from   tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and     isc.Item_Subcategory_ID = i.Item_Subcategory_ID and    i.Item_ID = ppl.Item_ID and    pp.Transaction_type = 'indirect cash' and    pp.Billing_Type = 'Inpatient Cash' and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type ='post' AND  pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and    pp.Patient_Bill_ID = '$Patient_Bill_ID'  and   pp.Registration_ID = '$Registration_ID' GROUP BY Check_In_Type") or die(mysqli_error($conn));
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
    $items = mysqli_query($conn,"SELECT   SUM((ppl.Price-ppl.Discount) *ppl.Quantity) AS TOTALAMOUNT from tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where ic.Item_Category_ID = isc.Item_Category_ID and isc.Item_Subcategory_ID = i.Item_Subcategory_ID and i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Billing_Type IN ('Inpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type = 'post' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and   pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
     if (mysqli_num_rows($items) > 0) {
        while ($dt = mysqli_fetch_array($items)) {
             $jumla_yagharama_za_hospitali = $dt['TOTALAMOUNT'];
        }     
    }
    //======Jumla ya gharama za hospitali==========//
 
  $kiasi_anachostahili = $jumla_yagharama_za_hospitali-$Grand_Total;
    
?>
<style>
#th{
    background-color: #6cd2ff;
    font-size: 15px;

    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
<br><br>
<fieldset>
    <legend align=center >TEMPORARY EXEMPTION FORM</legend> 
    <hr>
    <table width=90% >
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
    <?php 

    //Engeneer MESHACK 
    $select_Exemption_ID = mysqli_query($conn, "SELECT Exemption_ID FROM tbl_temporary_exemption_form WHERE Registration_ID = '$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID'") or die(mysqli_error($conn));	    
        if((mysqli_num_rows($select_Exemption_ID))>0){
            while($exp_row = mysqli_fetch_assoc($select_Exemption_ID)){
                $Exemption_ID = $exp_row['Exemption_ID'];
                ?>
        <input type="text" style="display: none;" id="exemptionID" value="<?php echo  $Exemption_ID;?>">
    <script>
        $(document).ready(function(){
            var exemptionID = $("#exemptionID").val();
            Exemptiondate_OpenDialog();
        });
    </script>        
        <?php
            }}else{  

        ?>      <table  width="90%" style="border-top: 1px solid black; border-bottom:1px solid black; border-left:1px solid black;">
                    <caption  align="center" id="th"><h4 align="center" > FORM YA MAOIMBI YA DHAMANA/ MSAMAHA WA MALIPO YA MATIBABU </h4></caption>
                    <tr>
                        <?php 
                            $select_form_ya_maombi = mysqli_query($conn, "SELECT * FROM tbl_nurse_exemption_form WHERE Registration_ID='$Registration_ID'   AND Nurse_Exemption_ID='$Nurse_Exemption_ID'") or die(mysqli_error($conn));

                            if((mysqli_num_rows($select_form_ya_maombi))>0){
                                while($fomu = mysqli_fetch_assoc($select_form_ya_maombi)){
                                    $Nurse_Exemption_ID = $fomu['Nurse_Exemption_ID'];
                                    $Jina_la_balozi = $fomu['Jina_la_balozi'];
                                    $simu_ya_balozi = $fomu['simu_ya_balozi'];
                                    $maelezo_ya_nurse_mratibu = $fomu['maelezo_ya_nurse_mratibu'];
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
                        <td style='text-align:right;'><b>Cheo</b>&nbsp;&nbsp;|&nbsp;&nbsp;<span><?php  echo $Employee_Title; ?></span></td>       
                        <td style='text-align:right;'> <b>Sahii</b> &nbsp;&nbsp;|&nbsp;&nbsp;<span><?php echo $signature; ?></span></td>
                        
                    </tr>
                
                </table>
                <hr>
                <table width="90%"> 
                    <caption  align="center" id="th"><h4 align="center" style=" padding-bottom:5px;"> SOCIAL WELFARE UNIT </h4></caption>
                        <tr>
                            <form action="" method="POST" id="exmpform">
                            <td style='text-align:right;'><b>Amepewa ushauri kuhusu bima ya afya</b></td>
                            <td ><b><center>
                                <span>Ndio<input type="radio" name="ushauriwabima" id="ushaurindio" value="NDIO"></span>
                                <span>Hapana<input type="radio" name="ushauriwabima" id="ushaurihapana" value="HAPANA"></span></center>
                                </b>
                            </td>
                            <td  style='text-align:right;'>
                                <b>Tathimini/Usaili(Iwapo mgonjwa anastahili msaada)</b>
                            </td>
                            <td>
                                <textarea name="tathiminiyamsaada" id="tathimini"  rows="1" class="form-control"></textarea>
                            </td>
                        </tr>
                    </table>
                    <table width="90%">
                        <th colspan="3" id="th">Taarifa za mgonjwa </th>
                        <tr>
                            <td colspan="3" style='text-align:center;'>
                                Mtu mwenye ulemavu <input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mlemavu'>
                                Mjane / Mgane<input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mjane_mgane'>
                                Mwenye Urahibu <input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mwenye_urahibu'>
                                Mfungwa <input type="radio"  name="ajira" style="display: inline;" id='Mfungwa'>
                                Mahabusu <input type="radio"  name="ajira" style="display: inline;" id='Mahabusu'>
                                Mwajiriwa <input type="radio"  name="ajira" style="display: inline;" id='Mwajiriwa'>
                                Kajiajiri <input type="radio"  name="ajira" style="display: inline;" id='Kajiajiri'><br><br>
                                Mzee <input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mzee'>
                                Mstaafu <input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mstaafu'> 
                                Mjamzito <input type="checkbox"  name="taarifa_za_mgonjwa" style="display: inline;" id='Mjamzito'>                              
                                Kuachika(Divorced) <input type="radio"  name="ndoa" style="display: inline;" id='Divorced'>                                
                                Mwanafunzi <input type="radio"  name="ndoa" style="display: inline;" id='Mwanafunzi'>
                                Ameolewa/Kuoa <input type="radio"  name="ndoa" style="display: inline;" id='Ameolewa_kuoa'>
                                Mtoto chini ya miaka 5 <input type="radio"  name="ndoa" style="display: inline;" id='Mtoto_chini_5'>
                                Hajaolewa / Kuoa <input type="radio"  name="ndoa" style="display: inline;" id='HanaNdoa'>
                                <br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Jina la mtu aliyemleta mgonjwa Hospitali</label>
                                <input type="text" style="display: inline; width:40%;" name="patient_next_kin" id="patient_next_kin" value="">                   
                            </td>
                            <td>
                                <label for="">No. ya simu</label>
                                <input type="text" style="display: inline; width:40%;" name="next_kin_phoneNo" id="next_kin_phoneNo" value="">                   
                            </td>
                            <td>
                                <label for="">Uhusiano wa Mgonjwa na aliyemleta/ alieyemtembelea hospitali</label>
                                <input type="text" style="display: inline; width:35%;" name="Next_kin_relationship" id="Next_kin_relationship" value="">                   
                            </td>
                        </tr><br>
                        
                        <tr>
                            <td>
                                <label for="">Mgonjwa amekuwa akitibiwa wapi</label>
                                <input type="text" style="display: inline; width:40%;" name="previous_hospital_treatment" value="" id="previous_hospital_treatment">                   
                            </td>
                            <td>
                                <label for="">Kwa muda gani</label>
                                <input type="text" style="display: inline; width:40%;" name="time_of_treatment"  value="" id="time_of_treatment">                   
                            </td>
                            <td>
                                <span>Amekuwa akichangia matibabu kwa mfumo upi
                                    <label for="">Taslimu</label>
                                    <input type="checkbox" name="mode_of_treatment" id="Sponsor_cash" value="Taslimu"  >
                                </span>                      
                            <span>
                                    <label for="">Bima</label>
                                    <input type="checkbox" name="mode_of_treatment" id="Sponsor_credit"  value="Bima" >
                            </span>                         
                            </td>
                        </tr>
                        <tr>
                            
                            <td colspan="2" style='text-align:center;'><b>Alishawahi Kupewa 
                                <span>Msamaha(Exemption)<input type="checkbox" name="alishawahikupewa" id="msamaha" value="Msamaha"></span>
                                <span>Dhamana<input type="checkbox" name="alishawahikupewa" id="Dhamanaye" value="Dhamana"></span>
                                <span>/Aliwahi kutoroka<input type="checkbox" name="alishawahikupewa" id="Kutoroka" value="Kutoroka"></span><b>
                            </td>
                            <td ><span ><b>Kiwango</b><input type="text" style="display: inline; width:60%;" id="kiwango_cha_msamaha" name="kiwango_cha_msamaha"></span></td>
                        </tr>
                    </table>

                    <table width="90%" >
                        <tr >
                            <td  style='text-align:right;'>
                                <b>Mapendekezo Dhamana/ msamaha</b>
                            </td>
                            <td colspan="5">
                                <textarea name="mapendekezomsamaha" id="mapendekezo"  rows="1" class="form-control"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Anaombewa</b></td>
                            <td colspan="5">
                                <b> 
                                    <span>Dhamana 
                                        <input type="radio" class="" style="display: inline;" name="Anaombewa"  id="anaombewa_Dhamana" value="Dhamana">
                                    </span> 
                                    <span>Msamaha 
                                        <input type="radio" class="" style="display: inline;" name="Anaombewa" id="anaombewa_Msamaha" value="Msamaha">
                                    </span>
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Ghamara za siku alizolazwa</b> </td>
                            <td><input  class="form-control" name="" id="" disabled value="<?php echo number_format($gharamaZaKulala); ?>"></td>
                            <td style='text-align:right;'><b> Gharama za dawa</b></td>
                            <td colspan=""><input class="form-control" name="" id="" disabled value="<?php echo number_format($total_dawa); ?>" ></td>
                            <td style='text-align:right;'><b> Mengineyo</b></td>
                            <td><input  class="form-control" name="" disabled value="<?php echo number_format($total_others); ?>"></td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Ghamara za vipimo</b> </td>
                            <td><input  class="form-control" name="" id=""disabled value="<?php echo number_format($total_vipimo); ?>"></td>
                            <td style='text-align:right;'><b> Gharama za upasuaji</b></td>
                            <td><input class="form-control" name="" id="" disabled value="<?php echo number_format($total_upasuaji); ?>"></td>
                            <td style='text-align:right;'><b> Kiasi Anachodaiwa</b></td>
                            <td><input  class="form-control" name="" disabled value="<?php echo $kiasi_anachostahili; ?>" id="kiasi_anachostahili"></td>
                        </tr>
                        <tr>
                            <td style='text-align:right;'><b>Jumla ya gharama za Hospitali</b></td>
                            <td><input class="form-control" name="" id="" disabled='disabled'value="<?php echo number_format($jumla_yagharama_za_hospitali);?>"></td>
                            <td style='text-align:right;'><b>Gharama iliyolipwa</b></td>
                            <td ><input class="form-control" name="" id=""disabled='disabled' value="<?php echo number_format($Grand_Total); ?>" ></td>
                            <td style='text-align:right;'><b>kiasi kinachoombewa</b>
                                
                            </td>
                            <td> <span></span><span><input class="form-control" style="display: inline; width:100%;" name="kiasikinachoombewamshamaha" id="kiasikinachoombewamshamaha" type="number"></span></td>
                        </tr>
                    
                    </table>
                
                    <button id="exemptionsaves" type="button" name="exemptionsaves" class="art-button-green pull-right" onclick="ExemptionSave('<?php echo $Registration_ID;?>')">SAVE</button>
        <?php
            }
        ?>
       
</form>
<div id="Exemptiondate">
<input type="text" hidden id="Nurse_Exemption_ID" value="<?php echo $Nurse_Exemption_ID; ?>">
</div>
</fieldset>
 
<script>
    function Exemptiondate_OpenDialog(){ 
        var Registration_ID = "<?php echo $Registration_ID; ?>"; 
        var exemptionID = $("#exemptionID").val();
       //alert(Registration_ID +exemptionID);       
        $.ajax({
            type:'POST',
            url:'exemptiondate.php',
            data:{Registration_ID:Registration_ID, exemptionID:exemptionID},
            success:function(responce){
                $("#Exemptiondate").dialog({
                    title: 'SELECT EXEMPTION DATE',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#Exemptiondate").html(responce)
            }
        });
    }
    function ExemptionSave(Registration_ID){
       var ushauriwabima = "";
       if($("#ushaurindio ").is(":checked")){
           ushauriwabima = "NDIO";
       }
       if($("#ushaurihapana").is(":checked")){
           ushauriwabima = "HAPANA";
       }
       var Anaombewa = "";
       if($("#anaombewa_Dhamana ").is(":checked")){
           Anaombewa = "Dhamana";
       }
       if($("#anaombewa_Msamaha").is(":checked")){
           Anaombewa = "Msamaha";
       }
       var alishawahikupewa = "";
       if($("#msamaha").is(":checked")){
           alishawahikupewa +="Msamaha"+','         
       }
       if($("#Dhamanaye").is(":checked")){
           alishawahikupewa +='Dhamana'+','
           }
       if($("#Kutoroka").is(":checked")){
           alishawahikupewa +="Kutoroka"+','
           }

       var    mode_of_treatment  =""; 
       if($("#Sponsor_cash ").is(":checked")){
           mode_of_treatment += "Taslimu"+',';
       }
       if($("#Sponsor_credit").is(":checked")){
           mode_of_treatment += "Bima"+',';
       }

       var taarifa_za_mgonjwa= "";
       if($("#HanaNdoa").is(":checked")){
           taarifa_za_mgonjwa +="HanaNdoa"+','         
       }
       if($("#Mtoto_chini_5").is(":checked")){
           taarifa_za_mgonjwa +='Mtoto_chini_5'+','
           }
       if($("#Ameolewa_kuoa").is(":checked")){
           taarifa_za_mgonjwa +="Ameolewa_kuoa"+','
           }
       if($("#Mjamzito").is(":checked")){
           taarifa_za_mgonjwa +="Mjamzito"+','         
       }
       if($("#Mzee").is(":checked")){
           taarifa_za_mgonjwa +='Mzee'+','
           }
       if($("#Mstaafu").is(":checked")){
           taarifa_za_mgonjwa +="Mstaafu"+','
           }
        if($("#Mjane_mgane").is(":checked")){
           taarifa_za_mgonjwa +="Mjane_mgane"+','         
       }
       if($("#Mwenye_urahibu").is(":checked")){
           taarifa_za_mgonjwa +='Mwenye_urahibu'+','
           }
       if($("#Mwanafunzi").is(":checked")){
           taarifa_za_mgonjwa +="Mwanafunzi"+','
           }

        if($("#Mahabusu").is(":checked")){
           taarifa_za_mgonjwa +="Mahabusu"+','
           }
        if($("#Mfungwa").is(":checked")){
           taarifa_za_mgonjwa +="Mfungwa"+','         
       }
       if($("#Divorced").is(":checked")){
           taarifa_za_mgonjwa +='Divorced'+','
           }
       if($("#Kajiajiri").is(":checked")){
           taarifa_za_mgonjwa +="Kajiajiri"+','
           }
       if($("#Mwajiriwa").is(":checked")){
           taarifa_za_mgonjwa +="Mwajiriwa"+','
           }
        if($("#Mlemavu").is(":checked")){
           taarifa_za_mgonjwa +="Mlemavu"+','
           }
           
           //alert(taarifa_za_mgonjwa);
       var patient_next_kin =$("#patient_next_kin").val();  
       var next_kin_phoneNo = $("#next_kin_phoneNo").val();  
       var Next_kin_relationship = $("#Next_kin_relationship").val();
       var previous_hospital_treatment = $("#previous_hospital_treatment").val();
       var time_of_treatment= $("#time_of_treatment").val();
       var tathiminiyamsaada = $('#tathimini').val();
       var kiasikinachoombewamshamaha = $('#kiasikinachoombewamshamaha').val();
       var kiwango_cha_msamaha = $('#kiwango_cha_msamaha').val(); 
       var mapendekezomsamaha = $("#mapendekezo").val();
       var Nurse_Exemption_ID = $("#Nurse_Exemption_ID").val();
       var kiasi_anachostahili = $("#kiasi_anachostahili").val();

    //    alert(Anaombewa+kiasikinachoombewamshamaha);
    //    exit();

       if(Anaombewa ==''){
          alert("Tafadhali Jaza Mgonjwa Anaombewa Dhamana au Msamaha");exit() 
       }else if(kiasikinachoombewamshamaha==''){
        alert("Tafadhali Jaza Kiasi kinachoombewa Anaombewa " +Anaombewa);//exit()
        $('#kiasikinachoombewamshamaha').css("border", "1px solid red");
       }else if(kiasikinachoombewamshamaha > kiasi_anachostahili){
        alert("Kiasi kinachoombewa Anaombewa " +Anaombewa+ "Hakiwezikuwa kuwa zaidi ya kiasi anachodaiwa mgonjwa"); 
        $('#kiasi_anachostahili').css("border", "1px solid red");
        exit()
       }
       
       $.ajax({
           type:'POST',
           url:'ajax_exemptionform.php',
           data:{ushauriwabima:ushauriwabima,mode_of_treatment:mode_of_treatment,taarifa_za_mgonjwa:taarifa_za_mgonjwa, Anaombewa:Anaombewa,patient_next_kin:patient_next_kin,next_kin_phoneNo:next_kin_phoneNo,previous_hospital_treatment:previous_hospital_treatment,time_of_treatment:time_of_treatment, tathiminiyamsaada:tathiminiyamsaada,alishawahikupewa:alishawahikupewa,kiasikinachoombewamshamaha:kiasikinachoombewamshamaha,kiwango_cha_msamaha:kiwango_cha_msamaha,mapendekezomsamaha:mapendekezomsamaha,Next_kin_relationship:Next_kin_relationship, Registration_ID:Registration_ID,Nurse_Exemption_ID:Nurse_Exemption_ID, exemptionsaves:""},
           success:function(responce){
            alert(responce);
            document.location.reload();
           }
       });

    }

</script>