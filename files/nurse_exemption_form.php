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

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    $consultation_ID = 0;
}


if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = 0;
}
if(isset($_GET['Check_In_ID'])){
    $Check_In_ID = $_GET['Check_In_ID'];
}else{
    $Check_In_ID = 0;
}

if(isset($_GET['Prepaid_ID'])){
    $Prepaid_ID = $_GET['Prepaid_ID'];
}else{
    $Prepaid_ID = 0;
}
?>

<a href="#" onclick="goBack()"class="art-button-green">BACK</a>
<script>
function goBack() {
    window.history.back();
}
</script>

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
} else {
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
    $Exemption = 'no';
    $Diseased = '';
    $service_no = "0";
}
if ($service_no == "") {
    $service_no = "-";
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
 $Hospital_Ward_Name="";
 $select_hospital_ward = mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward hw, tbl_admission a WHERE a.Hospital_Ward_ID=hw.Hospital_Ward_ID AND a.Registration_ID='$Registration_ID' AND Admision_ID='$Admision_ID' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
 if((mysqli_num_rows($select_hospital_ward))>0){
     while($ward_rw = mysqli_fetch_assoc($select_hospital_ward)){
         $Hospital_Ward_Name = $ward_rw['Hospital_Ward_Name'];
     }
 }
  
    //$admin_date = '';
  
    $select_admission = mysqli_query($conn, "SELECT Admision_ID, Admission_Date_Time,Discharge_Date_Time, Kin_Name, Registration_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_admission))>0){
        while($row_date =mysqli_fetch_assoc($select_admission)){
          
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
   
    $Grand_Total = 0;
   
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

    
    $Grand_Total = 0;
   $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from 
   tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where
    pp.payment_type='pre' AND 
   pp.Transaction_status <> 'cancelled' and
   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
   pp.Patient_Bill_ID = '$Patient_Bill_ID' and
   ppl.Item_ID=i.Item_ID   AND
   pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                //pp.Transaction_type = 'Direct cash' and and  i.Visible_Status='Others'                  
   if (mysqli_num_rows($cal) > 0) {
       while ($cls = mysqli_fetch_array($cal)){
           $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
       }
   }
   
                            
    //========== Mwisho wa kuangalia=======//
    //========== No. of days ==============//
    $gharamaZaKulala =0;
  
        $days = mysqli_query($conn, "SELECT Check_In_Type,Discount, ad.Admision_ID, ad.Admission_Date_Time, TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_admission ad, tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID  AND pp.Registration_ID='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Others' AND ((i.Product_Name LIKE '%Kulala%') OR (i.Product_Name LIKE '%Accomodation%'))  AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($days)>0){
        while($days_row = mysqli_fetch_assoc($days)){
            $Price = $days_row['Price'];
            $NoOfDay = $days_row['NoOfDay'];

            $gharamaZaKulala = $Price * $NoOfDay;
        }
    }else{
        $gharamaZaKulala = "OPD";
    }
    //========== END OF QUERY =============//
 
   $total_vipimo = "0";
    $vipimo =mysqli_query($conn, "SELECT Check_In_Type,Discount, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND ppl.Item_ID = i.Item_ID AND (Check_In_Type='Laboratory' OR Check_In_Type='Radiology') AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'");

    while($vp = mysqli_fetch_assoc($vipimo)){
        $Product_Name =$vp['Product_Name'];
        $Price = $vp['Price'];
        $Quantity = $vp['Quantity'];
        $Discount_vp = $vp['Discount'];

        $amount = (($Price-$Discount_vp) * $Quantity);

        $total_vipimo += $amount;
    }


    //grarama za madawa yaliyotumika
    $total_dawa = "0";
    $madawa =mysqli_query($conn, "SELECT Check_In_Type,Discount, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Pharmacy'  AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'");

    while($dawa = mysqli_fetch_assoc($madawa)){
        $Product_Name =$dawa['Product_Name'];
        $Price = $dawa['Price'];
        $Quantity = $dawa['Quantity'];
        $Discount = $dawa['Discount'];

        $jumla_kuu = (($Price-$Discount) * $Quantity);

        $total_dawa += $jumla_kuu;
    }

    //gharama za upasuaji
    $total_upasuaji = 0;
    $sql_upasuaji =mysqli_query($conn, "SELECT Check_In_Type,Discount, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type='Surgery'  AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'");

    while($upasuaji = mysqli_fetch_assoc($sql_upasuaji)){
        $Product_Name =$upasuaji['Product_Name'];
        $Price = $upasuaji['Price'];
        $Quantity = $upasuaji['Quantity'];
        $Discount = $upasuaji['Discount'];

        $jumla_kuu_ya_upasuaji = (($Price  -$Discount)* $Quantity);

        $total_upasuaji += $jumla_kuu_ya_upasuaji;
    }

    //=======Query to find other charges=======================//
    
    $total_others = 0;
    $others =mysqli_query($conn, "SELECT Check_In_Type,Discount, ppl.Item_ID,i.Product_Name,pp.payment_type,pp.Billing_Type, Price,Quantity,ppl.Patient_Payment_ID, ppl.Hospital_Ward_ID, pp.Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp, tbl_items i WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND ppl.Item_ID = i.Item_ID AND Check_In_Type In ('Others','Procedure') AND ((i.Product_Name NOT LIKE '%Kulala%') AND (i.Product_Name NOT LIKE '%Accomodation%')) AND ((Pre_Paid = '1' AND pp.Billing_Type='Outpatient Cash') OR (pp.payment_type='post' AND pp.Billing_Type='Inpatient Cash')) AND pp.Patient_Bill_ID = '$Patient_Bill_ID'");

    while($other = mysqli_fetch_assoc($others)){
        $Product_Name =$other['Product_Name'];
        $Price = $other['Price'];
        $Quantity = $other['Quantity'];
        $Discount_o = $other['Discount'];

        $jumla_kuu_ya_mengineyo = (($Price -$Discount_o)* $Quantity);

        $total_others += $jumla_kuu_ya_mengineyo;
    }

    //=======End of other charges query========================//
     //======query to check direct cash============//
   

    $Total_cash_paid = 0;
   
    //jumla ya gharama za hospital
    if(mysqli_num_rows($days)>0){
        
    //jumla ya gharama za hospital
        if (strtolower($payment_method) == 'cash') {
            $tohospitalcost = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from   tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and     i.Item_ID = ppl.Item_ID and   pp.Transaction_type = 'indirect cash' and pp.Billing_Type IN ( 'Inpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type  = 'post'   and  pp.Transaction_status <> 'cancelled' and  pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
              
        } else {
            
            $tohospitalcost = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from    tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and   i.Item_ID = ppl.Item_ID and   pp.Transaction_type = 'indirect cash' and      pp.Transaction_status <> 'cancelled' and    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and   pp.Billing_Type IN ('Outpatient Credit' ,'Inpatient Credit', 'Inpatient Cash', 'Outpatient Cash')  and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type  ='post' AND   pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        }
        // $nm = mysqli_num_rows($tohospitalcost);
        if (mysqli_num_rows($tohospitalcost) > 0) {
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
    }else{
        //Total hospital bills for out patient
        
        $items = mysqli_query($conn, "SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where ic.Item_Category_ID = isc.Item_Category_ID and isc.Item_Subcategory_ID = i.Item_Subcategory_ID and i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Billing_Type IN ('Outpatient Credit' ,'Inpatient Credit', 'Inpatient Cash', 'Outpatient Cash') and pp.Pre_Paid IN ('1' , '0') AND pp.payment_type ='post' AND pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

        $nm = mysqli_num_rows($items);
        if($nm > 0){
            $temp = 0;
            $Sub_Total = 0;
            while ($dt = mysqli_fetch_array($items)) {
                $jumla_yagharama_za_hospitali += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
            }
        }
    }
    //mwisho wa gharama za hospitali  


    //======Jumla ya gharama za hospitali==========//
 

   $kiasi_anachostahili = $jumla_yagharama_za_hospitali-$Grand_Total;
 

  echo '<input type="text" hidden value="'.$Patient_Bill_ID.'" id="Patient_Bill_IDsss" >';
//   echo $last_check_in;
?>

<br><br>
<fieldset>
    <legend align=center>FOMU YA MAOMBI YA DHAMANA / MSAMAHA WA MALIPO YA MATIBABU</legend> 
    <hr>
    <table width=90% >
        <tr>
            <td style='text-align:right;'   width="15%"><b>Namba Hospitali</b></td>
            <td style='text-align:right;'  width="25%"><b><input type="text" name="" id="" disabled value="<?php  echo $Registration_ID;?>">  </b></td>
            <td style='text-align:right;'   width="15%"><b>Idara </b></td>
            <td style='text-align:right;'   width="15%"><b><input type="text" name="" disabled='disabled' id="" value="<?php echo $Hospital_Ward_Name; ?>"> </b></td>
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
            <td style='text-align:right;'  width="15%"><b>Tarehe ya Kurudi Kliniki</b></td>
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

                <table  width="90%" >                        
                        <tr>
                            <td style='text-align:right;'><b>Kiasi alichotakiwa kulipa</b></td>
                            <td><input class="form-control" name="" id="" disabled='disabled'value="<?php echo number_format($jumla_yagharama_za_hospitali);?>"></td>
                            <td style='text-align:right;'><b>Kiasi alicholipa</b></td>
                            <td ><input class="form-control" name="" id=""disabled='disabled' value="<?php echo number_format($Grand_Total); ?>" ></td>
                            <td style='text-align:right;'><b>kiasi Anachodaiwa</b></td>
                            <td><input class="form-control" name="" disabled value="<?php echo number_format($kiasi_anachostahili);?>" ></td>
                        </tr>                    
                </table>
                <table   width="90%">
                    <form action="" method="POST">
                       
                        <tr>
                        <?php 
                            $select_form_ya_maombi = mysqli_query($conn, "SELECT * FROM tbl_nurse_exemption_form WHERE Registration_ID='$Registration_ID' AND Patient_Bill_ID='$Patient_Bill_ID' AND Employee_ID='$Employee_ID' ORDER BY Nurse_Exemption_ID DESC  LIMIT 1") or die(mysqli_error($conn));

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
                            <tr>
                            <td>
                                <a href="#" onclick="Exemption_EditDialog('<?php echo $Registration_ID;?>', '<?php echo $Nurse_Exemption_ID; ?>')" class="art-button-green" name='btn_edit_form'>EDIT FORM</a>
                            </td>
                            <td colspan="3"></td>
                                </tr>
                            <?php
                                }
                            }else{
                                ?>
                            <tr>
                                <td width="25%" style='text-align:right;'><b>Jina la balozi</b> </td>
                                <td width="25%"><input  class="form-control" name="" id="Jina_la_balozi"></td>
                                <td width="25%" style='text-align:right;'><b> Namba ya simu ya balozi</b></td>
                                <td width="25%"><input class="form-control" name="" id="simu_ya_balozi"  value=""></td>
                            </tr>
                            <tr>
                                <td colspan="" style='text-align:right;' width="30%"><b>Maelezo mafupi kutoka kwa Mratibu wa idara husika</b></td>
                                <td colspan="3" width="70%"> <textarea name="maelezo_ya_nurse_mratibu" id="maelezo_ya_nurse_mratibu" cols="30" rows="4" class="form-control"></textarea> </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td > <button class="art-button-green" type="button" name="nurse_save_btn" onclick="nurse_save_maelezo(<?php echo $Registration_ID;?>)">SAVE</button> </td>
                            </tr>
                        <?php
                            }
                        ?>
                        </tr>
                    </form>
                </table>
             <div class="col-md-6" id="saveresponce"></div>  
</fieldset>
<div id="ExemptionEdit"></div>
<script>
    function Exemption_EditDialog(Registration_ID, Nurse_Exemption_ID){
       
        $.ajax({
            type:'POST',
            url:'ajax_exemptionform.php',
            data:{Registration_ID:Registration_ID, Nurse_Exemption_ID:Nurse_Exemption_ID,btn_edit_form:''},
            success:function(responce){
                $("#ExemptionEdit").dialog({
                    title: 'FORM YA MAOMBI YA DHAMANA/ MSAMAHA WA MALIPO YA MATIBABU (EDIT FORM)',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#ExemptionEdit").html(responce)
            }
        });
    }
    function nurse_save_maelezo(Registration_ID){
        var Jina_la_balozi = $("#Jina_la_balozi").val();
        var simu_ya_balozi =$("#simu_ya_balozi").val();
        var  Patient_Bill_ID =$("#Patient_Bill_IDsss").val();
        var maelezo_ya_nurse_mratibu = $("#maelezo_ya_nurse_mratibu").val();
        if(maelezo_ya_nurse_mratibu==""){
            $("#maelezo_ya_nurse_mratibu").css("border", "1px solid red");
        }else{
            $("#maelezo_ya_nurse_mratibu").css("border", "");
            if(confirm("Are you sure you want to send this request")){
                $.ajax({
                    type:'POST',
                    url:'ajax_exemptionform.php',
                    data:{Registration_ID:Registration_ID,Jina_la_balozi:Jina_la_balozi,simu_ya_balozi:simu_ya_balozi,maelezo_ya_nurse_mratibu:maelezo_ya_nurse_mratibu,Patient_Bill_ID:Patient_Bill_ID, nurse_save_btn:''},
                    success:function(responce){
                        //document.location.reload();
                    $("#saveresponce").html(responce);
                    } 
                });
            }
        }
    }

    function Exemption_update_form(Registration_ID, Nurse_Exemption_ID){
        var Jina_la_balozi = $("#Jina_la_balozi").val();
        var simu_ya_balozi =$("#simu_ya_balozi").val();
        var maelezo_ya_nurse_mratibu = $("#maelezo_ya_nurse_mratibu").val();
        if(maelezo_ya_nurse_mratibu==""){
            $("#maelezo_ya_nurse_mratibu").css("border", "1px solid red");
        }else{
            $("#maelezo_ya_nurse_mratibu").css("border", "");
            $.ajax({
                type:'POST',
                url:'ajax_exemptionform.php',
                data:{Registration_ID:Registration_ID,Nurse_Exemption_ID:Nurse_Exemption_ID, Jina_la_balozi:Jina_la_balozi,simu_ya_balozi:simu_ya_balozi,maelezo_ya_nurse_mratibu:maelezo_ya_nurse_mratibu,btn_update_form:''},
                success:function(responce){
                   
                    $("#saveresponce").html(responce);
                    // alert(responce) 
                    document.location.reload();
                }
            });
        }
    }
</script>