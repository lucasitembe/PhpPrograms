<?php

session_start();
include("./includes/connection.php");
include("allFunctions.php");
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Emp_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Emp_Name = '';
}

if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = '';
}


if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = '';
}


if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}


if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}


if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
    $payments_filter = "pp.payment_type = 'post' and ";
} else {
    $payments_filter = '';
}


$patientStatus =$_GET['Status'];
$adDetail =json_decode(getAdmisionDetails($Check_In_ID, $patientStatus), true);
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}
$PtDetails = json_decode(getPatientDetails($Registration_ID), true); 
$selectexmption =json_decode(getRowExemption($Registration_ID,$Patient_Bill_ID), true);   

$patient_date_of_birth= $PtDetails[0]['Date_Of_Birth'];

$age =getCurrentPatientAge($patient_date_of_birth);

//get last Patient_Bill_ID
$select = mysqli_query($conn,"select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where 
							Registration_ID = '$Registration_ID' and
							Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
         //$Patient_Bill_ID = $data['Patient_Bill_ID'];
        $Folio_Number = $data['Folio_Number'];
    }
} else {
    //$Patient_Bill_ID = 0;
    $Folio_Number = 0;
}

$morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
        $num=mysqli_num_rows($morgueDetails);
        if ($num > 0) {
            $Patient_Status="mortuary";
            $filter_p_status="Patient_Status='mortuary'";
        }else{
            $Patient_Status="Inpatient";
            $filter_p_status="(Patient_Status='Inpatient' OR Patient_Status='Outpatient')";
        }
        $credit_bill_trans="";
        $cash_bill_trans="";
        
         $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' AND $filter_p_status  order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
         $nums = mysqli_num_rows($select);
         if($nums > 0){
         while ($row = mysqli_fetch_array($select)) {
           // $Patient_Bill_ID = $row['Patient_Bill_ID'];
                  }
              }
                
        //test if pateint have credit and cash transaction
        $sql_check_credit_bill_result=mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_check_credit_bill_result)>0){
            $credit_bill_trans="ipo";
        }	
        $sql_check_cash_bill_result=mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Inpatient Cash') AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_check_cash_bill_result)>0){
            $cash_bill_trans="ipo";
        }	

        if($cash_bill_trans=="ipo"&&$credit_bill_trans=="ipo"){
            
            $check_if_all_bill_creared_result=mysqli_query($conn,"SELECT Cash_Bill_Status,Credit_Bill_Status FROM tbl_admission WHERE Cash_Bill_Status='cleared' && Credit_Bill_Status='cleared' AND Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
        
            if(mysqli_num_rows($check_if_all_bill_creared_result)>0){
                //do nothing the bill is cleared
                
            }else{
                mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='active' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));				
            }
        }

	//get the last bill id
	$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' AND Status='active' AND $filter_p_status order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
	$nums = mysqli_num_rows($select);
	if($nums > 0){
		while ($row = mysqli_fetch_array($select)) {
		    // $Patient_Bill_ID = $row['Patient_Bill_ID'];
		}
	}else{
            //insert data to tbl_patient_bill
		$insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time,Patient_Status) VALUES ('$Registration_ID',(select now()),'$Patient_Status')") or die(mysqli_error($conn));
		if($insert){
			$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' AND Status='active' AND $filter_p_status order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
			$nums = mysqli_num_rows($select);
			while ($row = mysqli_fetch_array($select)) {
				//$Patient_Bill_ID = $row['Patient_Bill_ID'];
			}
		}
	}


// $htm .= $Patient_Bill_ID;
// exit;
//select diagnosis details outpatient
$diagnosis = "";
$Consultant_Name = "";
$Consultation_ID = '';
$NoOfHour="";
$Hour="";

$morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
                   $num=mysqli_num_rows($morgueDetails);
if ($num > 0) {

            $select_hour="SELECT TIMESTAMPDIFF(HOUR,Admission_Date_Time,NOW()) AS NoOfHour FROM tbl_admission WHERE Admision_ID='$Admision_ID' ";
      $result = mysqli_query($conn,$select_hour) or die(mysqli_error($conn)); 
      if(mysqli_num_rows($result)>0){
         $NoOfHour=mysqli_fetch_assoc($result)['NoOfHour']; 
      }
     $NoOfHour;
//     $htm .= " $NoOfHour "; $htm .= 'Hrs';

//==================CHECKING FROM MORGUE, DONE BY FULL STACK DEVELOPERS===================

$item_name="";
     $price=0;
     $ageFrom="";
     $ageTO="";
     $count=1;
     $count2=1;
     $charges_duration=1;
     
 $user_sponsor_id=$Sponsor_ID;
//==================CHECKING FROM MORGUE, DONE BY FULL STACK DEVELOPERS===================

$morgueDetails=mysqli_query($conn,"SELECT ma.inalala_bilakulala,ma.admitted_from,ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
    $morge_details_row=mysqli_fetch_assoc($morgueDetails);
     $admitted_from=$morge_details_row['admitted_from'];
     $inalala_bilakulala=$morge_details_row['inalala_bilakulala'];
}
 //============OVERIDDING SPONSOR TO CASH=====================
 //copppiyy finalcode
 $morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death,ma.Date_In, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
 $Payment_Method="cash";
 //============OVERIDDING SPONSOR TO CASH=====================
 $Sponsor_ID=mysqli_fetch_array(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE payment_method='$Payment_Method' ORDER BY Sponsor_ID ASC LIMIT 1"))['Sponsor_ID'];
 // $htm .= $Sponsor_ID;
 // exit;
	 $Folio_Number =0;
while($data =  mysqli_fetch_array($morgueDetails)){
	$case_type=$data['case_type'];
        $date_death=$data['Date_Of_Death'];
	$Admission_Date_Time=$data['Admission_Date_Time'];
        $$Date_In=$data['Date_In'];
	$date_death= strtotime($date_death);
	$date_death = date("Y-m-d",$date_death);
	
	$date1 = new DateTime($date_death);
	$date2 = new DateTime($Date_Of_Birth);
	 $diff = $date1->diff($date2);
	$year = $diff->y;
	$month= $diff->m;
	$days= $diff->d ;
	//$htm .= $month ." ".$days;
	$admission_date= strtotime($Admission_Date_Time);
	$Admission_Date_Time = date("Y-m-d",$admission_date );
	$admission_date1 = new DateTime($Admission_Date_Time);
	$leo = new DateTime($Today);
	$diff2 = $leo->diff($admission_date1)->format("%a");
}
//    
   }
   $date_status="";
 $year=(int)$year;
 $month=(int)$month;
 $days=(int)$days;
 $grand_total_mortuary_prc=0;
 $NoOfHour=(int)$NoOfHour;
 //$Hour=(int)$Hour;
 $charges_duration=(int)$charges_duration;
//=======================FOR HOSPITAL CASE==============================
    if($year!=0){
     
     $date_status="years";
    $sql_mortuary_item=mysqli_query($conn,"SELECT mp.item_id,mp.ageFrom,mp.ageTO,it.Product_Name,mp.price,mp.date_status,mp.charges_duration,mp.admitted_from,mp.inalala_bilakulala,it.Item_ID,mp.Sponsor_ID FROM tbl_morgue_prices mp,tbl_items it WHERE mp.item_id=it.Item_ID AND ageFrom<='$year' AND ageTO>='$year' AND date_status='$date_status' AND admitted_from='$admitted_from'  AND inalala_bilakulala='$inalala_bilakulala'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_mortuary_item)>0){
    while($mochwari_data=mysqli_fetch_assoc($sql_mortuary_item))
    {     //$htm .= $NoOfHour.' = '.$charges_duration.'<br>';
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
      
        $grand_total_mortuary_prc=$grand_total_mortuary_prc+$subtotal_price_detail;
         $count++;
    
        
    }}}
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

    $htm .= "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td>&nbsp;</td></tr></table>";
    $htm .= "<table width ='100%' height = '30px'>
		<tr><td> </td></tr>
        <tr><td>&nbsp;</td></tr></table>";

    $htm .= '<table width="100%">
    <tr>
        <td width="33%"><span style="font-size: x-small;"><b>Full Name : &nbsp;&nbsp;&nbsp;</b>' . ucwords(strtolower($PtDetails[0]['Patient_Name'])) . '</span></td>
        <td width="33%"><span style="font-size: x-small;"><b>Registration Number : &nbsp;&nbsp;&nbsp;</b>' . $Registration_ID . '</span></td>
        </tr>
        <tr>
        <td><span style="font-size: x-small;"><b>Gender : &nbsp;&nbsp;&nbsp;</b>' . $PtDetails[0]['Gender'] . '</span></td>
        <td><span style="font-size: x-small;"><b>Sponsor Name : &nbsp;&nbsp;&nbsp;</b>' . strtoupper($PtDetails[0]['Guarantor_Name']) . '</span></td>
        </tr>
        <tr>
        <td><span style="font-size: x-small;"><b>Admission Date : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['Admission_Date_Time'] . '</span></td>
        <td><span style="font-size: x-small;"><b>Admitted By : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['Employee_Name'] . '</span></td>
        </tr>
            <tr>
        <td><span style="font-size: x-small;"><b>Number of Hour passed : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['NoOfDay'] . '</span></td>
        <td><span style="font-size: x-small;"><b>Age : &nbsp;&nbsp;&nbsp;</b>' . $age . '</span></td>
        </tr>

		</table><br/>';

                                  ////////////////////////////////////////////////////////////////////////////////////////////
    $htm .="<table width='100%' border='1' style='border-collapse: collapse;'>
                <caption style='text-align:left'><b>BILL SUMMARY</b></caption>
                <tr>
                    <td width='4%'>SN</td>
                    <td>ITEM NAME</td>
                    <td style='text-align: right;'>AGE FROM</td>
                    <td style='text-align: right;'>AGE TO</td>
                    <td style='text-align: right;'>AGE STATUS</td>
                    <td width='10%' style='text-align: center;'>ADMITTED FROM</td>
                    <td width='10%' style='text-align: center;'>INALALA/BILAKULALA</td>
                    <td width='10%' style='text-align: center;'>CHARGED HOURS</td>
                    <td width='10%' style='text-align: center;'>PRICE</td>
                    <td width='10%' style='text-align: center;'>KEEPING <br> DAYS</td>
                    <td width='10%' style='text-align: right;'>SUB TOTAL</td>
                </tr>   ";
                $Payment_Method="cash";
 //============OVERIDDING SPONSOR TO CASH=====================
 //copppiyy finalcode
 $morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death,ma.Date_In, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID AND ma.Admision_ID='$Admision_ID' ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
 $Payment_Method="cash";
 //============OVERIDDING SPONSOR TO CASH=====================
 $Sponsor_ID=mysqli_fetch_array(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE payment_method='$Payment_Method' ORDER BY Sponsor_ID ASC LIMIT 1"))['Sponsor_ID'];

	 $Folio_Number =0;
while($data =  mysqli_fetch_array($morgueDetails)){
	$case_type=$data['case_type'];
        $date_death=$data['Date_Of_Death'];
	$Admission_Date_Time=$data['Admission_Date_Time'];
        $$Date_In=$data['Date_In'];
	$date_death= strtotime($date_death);
	$date_death = date("Y-m-d",$date_death);
	
	$date1 = new DateTime($date_death);
	$date2 = new DateTime($Date_Of_Birth);
	 $diff = $date1->diff($date2);
	$year = $diff->y;
	$month= $diff->m;
	$days= $diff->d ;
	//$htm .= $month ." ".$days;
	$admission_date= strtotime($Admission_Date_Time);
	$Admission_Date_Time = date("Y-m-d",$admission_date );
	$admission_date1 = new DateTime($Admission_Date_Time);
	$leo = new DateTime($Today);
	$diff2 = $leo->diff($admission_date1)->format("%a");
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
    {     //$htm .= $NoOfHour.' = '.$charges_duration.'<br>';
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

             $htm .="<tr colspan='7'>
                                    <td width='4%'>$count</td>
                                    <td>strtoupper($item_name)</td>
                                    <td width='10%' style='text-align: right;'>$ageFrom</td>
                                    <td width='10%' style='text-align: right;'>$ageTO</td>
                                    <td width='10%' style='text-align: right;'>$date_status</td>
                                      <td width='10%' style='text-align: right;'>$admitted_from</td>
                                      <td width='10%' style='text-align: right;'>$inalala_bilakulala</td>
                                     <td width='10%' style='text-align: right;'>$charges_duration</td>
                                    <td width='10%' style='text-align: right;'>".number_format($price,2)."</td>
                                     <td width='10%' style='text-align: right;'>";
                                      
                                     if(($charges_duration)==0){
                                        $hr=1;
                                        $subtotal_price_detail=($hr)*$price;
                                        $htm.= $hr; 
                                    }else{
                                        $htm.= $diff2;
                                        $subtotal_price_detail=($diff2)*$price;
                                    }
                                    $htm .=" </td>
                                    <td width='10%' style='text-align: right;'>".number_format($subtotal_price_detail,2)."</td>
                                   
                                </tr>";

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
      
            $htm .=" <tr colspan='7'>
                                    <td width='4%'>$count</td>
                                    <td>strtoupper($item_name)</td>
                                    <td width='10%' style='text-align: right;'>$ageFrom</td>
                                    <td width='10%' style='text-align: right;'>$ageTO</td>
                                    <td width='10%' style='text-align: right;'>$date_status</td>
                                   <td width='10%' style='text-align: right;'>$admitted_from</td>
                                    <td width='10%' style='text-align: right;'>".number_format($price,2)."</td>
                                     <td width='10%' style='text-align: right;'>$charges_duration</td>
                                     <td width='10%' style='text-align: right;'>";
                                     if(($charges_duration)==0){
                                        $hr=1;
                                        $subtotal_price_detail=($hr)*$price;
                                        $htm.= $hr; 
                                    }else{
                                        $htm.= $diff2;
                                        $subtotal_price_detail=($diff2)*$price;
                                    }
                                     $htm .="</td>
                                    <td width='10%' style='text-align: right;'>".number_format($subtotal_price_detail,2)."</td>
                                   
                                </tr>";

        
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
       
           $htm .="  <tr colspan='7'>
                                    <td width='4%'>".$count."</td>
                                    <td>".strtoupper($item_name)."</td>
                                    <td width='10%' style='text-align: right;'>".$ageFrom."</td>
                                    <td width='10%' style='text-align: right;'>".$ageTO."</td>
                                    <td width='10%' style='text-align: right;'>".$date_status."</td>
                                    <td width='10%' style='text-align: right;'>".$admitted_from."</td>
                                    <td width='10%' style='text-align: right;'>".$inalala_bilakulala."</td>
                                    <td width='10%' style='text-align: right;'>".number_format($price,2)."</td>
                                     <td width='10%' style='text-align: right;'>".$charges_duration."</td>
                                      <td width='10%' style='text-align: right;'>";
                         

                                         if(($charges_duration)==0){
                                            $hr=1;
                                            $subtotal_price_detail=($hr)*$price;
                                            $htm.= $hr; 
                                        }else{
                                            $htm.= $diff2;
                                            $subtotal_price_detail=($diff2)*$price;
                                        }
                                         $htm .="</td>
                                    <td width='10%' style='text-align: right;'>".number_format($subtotal_price_detail,2)."</td>
                                   
                                </tr>";

        
        $grand_total_mortuary_prc=$grand_total_mortuary_prc+$subtotal_price_detail;
         $count++;
    }}}
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
}}
?>

	                   <?php $totalmorgueprice=$subtotal_price_detail+$subtotal_price_detail;
                               
					 								
                             $htm .="   <tr style='background:#DEDEDE'>
                                <td colspan='10'><b>TOTAL</b></td>
                                <td style='text-align:right'><b>".number_format($grand_total_mortuary_prc,2)."</b>								 
                                </td>

                                </tr>	
 									
                             </table>";

                             $Select_receipt = mysqli_query($conn, "SELECT pp.Patient_Payment_ID, hw.Hospital_Ward_Name, em.Employee_Name FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee em, tbl_hospital_ward hw WHERE pp.Registration_ID='$Registration_ID' AND ppl.Check_In_Type = 'Mortuary' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Pre_Paid = '1' AND hw.Hospital_Ward_ID = pp.Hospital_Ward_ID AND em.Employee_ID = pp.Employee_ID AND pp.Patient_Bill_ID = '$Patient_Bill_ID' GROUP BY pp.Patient_Payment_ID ORDER BY pp.Patient_Payment_ID DESC");
                             if($Select_receipt > 0){

                                 $htm.= '<table width="100%" border="1" style="border-collapse: collapse;">                                     
                                         <tr style="background: #dedede;">                                         
                                             <th colspan="8" style="text-align: left;">OTHER MORTUARY SERVICES</th>                                     
                                         </tr>
                                         <tr style="font-weight: bold; border: 1px solid #dedede !important;">
                                             <td width="4%">SN</td>
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

                                     $Select_Mortuary = mysqli_query($conn, "SELECT i.Product_Name, ppl.Transaction_Date_And_Time, ppl.Quantity, ppl.Price,  ppl.Transaction_Date_And_Time FROM tbl_patient_payment_item_list ppl, tbl_items i WHERE ppl.Patient_Payment_ID = '$Patient_Payment_ID' AND  i.Item_ID = ppl.Item_ID GROUP BY  ppl.Patient_Payment_Item_List_ID");

                                             while($morgue = mysqli_fetch_assoc($Select_Mortuary)){
                                                 $Product_Name = $morgue['Product_Name'];
                                                 $Quantity = $morgue['Quantity'];
                                                 $Price = $morgue['Price'];
                                                 $Transaction_Date_And_Time = $morgue['Transaction_Date_And_Time'];
                                                 $subtotal = $Price * $Quantity;
                                             
                                                     $htm.= '<tr>
                                                             <td width="4%">'.$count.'</td>
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
                                 $htm.= "<tr style='background: #dedede;'>
                                             <th colspan='7' style='text-align: left;'>GRAND TOTAL</th>
                                             <th style='text-align: right;'>".number_format($other_total,2)."</th>
                                     </tr>";
                                 $htm.= "</table>";
                             }
//========================= END OF OTHER CHARGERS MORTUARY =============================
                             
    $htm .= '<span style="font-size: x-small;"><b> ADVANCE PAYMENTS </b></span>
			<table width=100% border=1 style="border-collapse: collapse;">
				<tr>
					<td width="5%"><span style="font-size: x-small;">SN</span></td>
					<td><span style="font-size: x-small;">ITEM NAME</span></td>
					<td width="12%"><span style="font-size: x-small;">RECEIPT#</span></td>
					<td width="20%"><span style="font-size: x-small;">RECEIPT DATE</span></td>
					<td width="12%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
				</tr>';

    $Direct_Cash_Grand_Total = 0;
    $sn = 0;
    $select = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, i.Product_Name, ppl.Item_Name from 
							tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where pp.Transaction_type = 'direct cash' and
							ppl.Item_ID = i.Item_ID and 
							pp.Transaction_status <> 'cancelled' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							 ppl.Item_ID=i.Item_ID and i.Visible_Status='Others' and
							pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        $temp = 0;
        $jibu='';
        while ($data = mysqli_fetch_array($select)) {
            $jibu=$data['Price']  * $data['Quantity'];
            $htm .= '<tr>
						<td><span style="font-size: x-small;">' . ++$sn . '<b>.</b></span></td>
						<td><span style="font-size: x-small;">' . ucwords(strtolower($data['Product_Name'] . ' ~ ' . $data['Item_Name'])) . '</span></td>
						<td><span style="font-size: x-small;">' . $data['Patient_Payment_ID'] . '</span></td>
						<td><span style="font-size: x-small;">' . $data['Payment_Date_And_Time'] . '</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">' .  number_format($jibu,2) . '</span></td>
					</tr>';

            $Direct_Cash_Grand_Total = (($data['Price'] - $data['Discount']) * $data['Quantity']);
            
            $jumla_kuu +=$jibu;
        }
    }             $dataprice=$data['Price'];
                  $dataQuantity=$data['Quantity'];
    
       $Direct_Cash_Grand_Total=0;
    if($Direct_Cash_Grand_Total>=0){
     $Direct_Cash_Grand_Total= $Direct_Cash_Grand_Total +$dataprice+$dataQuantity;
     $count++;
    }
    else{
//   $grand_total_mortuary_prc='';  
    }
    $htm .= '<tr><td colspan="4" style="text-align: left;"><span style="font-size: x-small;"><b>TOTAL AMOUNT PAID</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($jumla_kuu,2) . '</b></span></td></tr>
				</table><br/>';


    $htm .= '
				<span style="font-size: x-small;"><b>OVERALL SUMMARY</b></span>
					<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="65%"><span style="font-size: x-small;">Total Amount Required</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($grand_total_mortuary_prc,2) . '</span></td>
						</tr>
						<tr>
							<td width="65%"><span style="font-size: x-small;">Total Amount Paid</span></td>
							<td style="text-align: right;">';

                    if ($Transaction_Type == 'Cash_Bill_Details') {
                        $htm .= '<span style="font-size: x-small;">' . number_format($jibu) . '</span>';
                    } else {
                        $htm .= "<span style='font-size: x-small;'>(<i>Not applicable</i>)</span>";
                    }

                   
    $htm .= '</td>
					</tr>';
                   
                   $hmt.= '
					<tr>
						<td><span style="font-size: x-small;">Balance</span></td>';

    if ($Transaction_Type == 'Cash_Bill_Details') {
        if ($grand_total_mortuary_prc > $jibu) {
            $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($grand_total_mortuary_prc - $jibu) . '</span></td>';
        } else {
            $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">0</span></td>';
        }
    } else {
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($grand_total_mortuary_prc) . '</span></td>';
    }
    $htm .= '</tr>';

    if (($Transaction_Type == 'Cash_Bill_Details') && ($grand_total_mortuary_prc < $jibu)) {
        $htm .= "<tr>
							<td><span style='font-size: x-small;'>Refund Amount</span></td>
							<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($Direct_Cash_Grand_Total - $Cat_Grand_Total) . "</span></td>
						</tr>";
    }

    $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Bill Status</span></td>';
    if ($Transaction_Type == 'Cash_Bill_Details') {
        //get employee clearded the bill(Cash)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Cash_Bill_Status . '</span></td>';
    } else {
        //get employee clearded the bill(Credit)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Credit_Bill_Status . '</span></td>';
    }
    $htm .= '</tr>';
    if ($Transaction_Type == 'Cash_Bill_Details' && strtolower($Cash_Bill_Status) == 'cleared') {
        //get employee clearded the bill(Cash)
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Cleared By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Cash_Clearer)) . '</span></td>';
    } else if ($Transaction_Type == 'Credit_Bill_Details' && strtolower($Credit_Bill_Status) == 'cleared') {
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Approved By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Credit_Clearer)) . '</span></td>';
    }
    $htm .= '</table></td></tr></table>';

    //====================== END OF MORTUARY ====================

}

else {

    $htm .= "<table width ='100%' height = '30px'>
    <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
    <tr><td>&nbsp;</td></tr></table>";
$htm .= "<table width ='100%' height = '30px'>
    <tr><td> </td></tr>
    <tr><td>&nbsp;</td></tr></table>";

$htm .= '<table width="100%">
<tr>
    <td width="33%"><span style="font-size: x-small;"><b>Full Name : &nbsp;&nbsp;&nbsp;</b>' . ucwords(strtolower($PtDetails[0]['Patient_Name'])) . '</span></td>
    <td width="33%"><span style="font-size: x-small;"><b>Registration Number : &nbsp;&nbsp;&nbsp;</b>' . $Registration_ID . '</span></td>
</tr>
<tr>
    <td><span style="font-size: x-small;"><b>Gender : &nbsp;&nbsp;&nbsp;</b>' . $PtDetails[0]['Gender'] . '</span></td>
    <td><span style="font-size: x-small;"><b>Sponsor Name : &nbsp;&nbsp;&nbsp;</b>' . strtoupper($PtDetails[0]['Guarantor_Name']) . '</span></td>
</tr>
<tr>
    <td><span style="font-size: x-small;"><b>Admission Date : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['Admission_Date_Time'] . '</span></td>
    <td><span style="font-size: x-small;"><b>Admitted By : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['Employee_Name'] . '</span></td>
</tr>
<tr>
    <td><span style="font-size: x-small;"><b>Number of Hour passed : &nbsp;&nbsp;&nbsp;</b>' . $adDetail[0]['NoOfDay'] . '</span></td>
    <td><span style="font-size: x-small;"><b>Age : &nbsp;&nbsp;&nbsp;</b>' . $age . '</span></td>
</tr>
    </table><br/>';
    if ($Transaction_Type == 'Cash_Bill_Details') {
        $htm .= "<span style='font-size: x-small;'><b>INPATIENT INVOICE</b></span><br/>";
        $htm .= "<span style='font-size: x-small;'><b>BILL TYPE ~ CASH BILL</b></span><br/><br/>";
        $Billing_Type ="  AND pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash')";
    } else if ($Transaction_Type == 'Credit_Bill_Details') {
        $htm .= "<span style='font-size: x-small;'><b>INPATIENT INVOICE</b></span><br/>";
        $htm .= "<span style='font-size: x-small;'><b>BILL TYPE ~ CREDIT BILL</b></span><br/><br/>";
        $Billing_Type="  AND pp.Billing_Type IN ('Outpatient Credit' , 'Inpatient Credit')";
    }
    // AND pp.Check_In_ID='$Check_In_ID'
    $get_cat = json_decode(getBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type), true);
    
    if (sizeof($get_cat)>0) {
        $temp_cat = 0;
        $htm .= "<span style='font-size: x-small;'><b>BILL DETAILS</b></span>";
        foreach ($get_cat as $row){
            // $Item_category_ID = $row['Item_category_ID'];
            $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
            $htm .= "<thead><tr><td colspan='7'><span style='font-size: x-small;'>" . ++$temp_cat . '. ' . strtoupper($row['Item_Category_Name']) . "</span></td></tr>";  

            $htm .= '<tr>
                    <td width="4%"><span style="font-size: x-small;">SN</span></td>
                    <td><span style="font-size: x-small;">ITEM NAME</span></td>
                    <td width="10%" style="text-align: center;"><span style="font-size: x-small;">RECEIPT#</span></td>
                    <td width="10%" style="text-align: right;"><span style="font-size: x-small;">PRICE</span></td>
                    <td width="10%" style="text-align: right;"><span style="font-size: x-small;">DISCOUNT</span></td>
                    <td width="10%" style="text-align: right;"><span style="font-size: x-small;">QUANTITY</span></td>
                    <td width="10%" style="text-align: right;"><span style="font-size: x-small;">SUB TOTAL</span></td>
                </tr></thead>';   
        $items =json_decode(getBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $row['Item_category_ID']), true);
        if (sizeof($items) > 0) {
            $temp = 0;
            $Sub_Total = 0;
            foreach($items as $dt) {
                $htm .= '<tr>
                        <td width="4%"><span style="font-size: x-small;">' . ++$temp . '<b>.</b></span></td>
                        <td><span style="font-size: x-small;">' . ucwords(strtolower($dt['Product_Name'])) . '</span></td>
                        <td width="10%" style="text-align: center"><span style="font-size: x-small;">' . $dt['Patient_Payment_ID'] . '</span></td>
                        <td width="10%" style="text-align: right"><span style="font-size: x-small;">' . number_format($dt['Price']) . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format($dt['Discount']) . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . $dt['Quantity'] . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</span></td>
                    </tr>';
                $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
            }
            //$htm .= "<tr><td colspan='7'><hr></td></tr>";
            $htm .= "<tr>
                        <td colspan='6' style='text-align: right;'>
                            <span style='font-size: x-small;'><b>SUB TOTAL</b></span></td><td style='text-align: right;'>
                            <span style='font-size: x-small;'><b>" . number_format($Sub_Total) . "</b></span>
                        </td>
                    </tr>";
        }
        $htm .= "</table><br/>";
        }
        $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
        $htm .= '<tr>
                    <td width="90%" style="text-align: right;">
                        <span style="font-size: x-small;"><b>GRAND TOTAL</b></span>
                    </td>
                    <td style="text-align: right;">
                        <span style="font-size: x-small;"><b>' . number_format($Grand_Total) . '</b></span></td></tr>';
        $htm .= '</table>';

        $htm .= '<br/><span style="font-size: x-small;"><b>BILL SUMMARY</b></span>
        <table width=100% border=1 style="border-collapse: collapse;">
            <thead><tr>
                <td width="5%"><span style="font-size: x-small;">SN</span></td>
                <td><span style="font-size: x-small;">CATEGORY NAME</span></td>
                <td width="20%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
            </tr></thead>';
            // $Item_category_ID=0;
            $selectcategory = json_decode(getBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type), true);
        
            $tmp = 0;
            $cont = 0;$Cat_Grand_Total = 0;
            if (sizeof($selectcategory)> 0) {
                foreach($selectcategory as $mes) {
                    $Item_Category_Name = $mes['Item_Category_Name'];                       
                    $itemscat =json_decode(getBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $mes['Item_category_ID']), true);
                    $Category_Grand_Total = 0;
                    if (sizeof($itemscat) > 0) {
                        foreach ($itemscat as $t_item_c){
                            $Category_Grand_Total += (($t_item_c['Price'] - $t_item_c['Discount']) * $t_item_c['Quantity']);                                
                            $Cat_Grand_Total += (($t_item_c['Price'] - $t_item_c['Discount']) * $t_item_c['Quantity']);                               
                        }
                    }
                   
                    $htm .= "<tr><td><span style='font-size: x-small;'>" . ++$cont . '<b>.</b></span></td>
                                <td><span style="font-size: x-small;">' . ucwords(strtolower($Item_Category_Name)) . "</span></td>
                                <td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($Category_Grand_Total) . "</span></td></tr>";
                               
                }
            }
            $htm .= '<tr><td colspan="2" style="text-align: right;"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
            <td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($Cat_Grand_Total) . '</b></span></td></tr>
            </table><br/><br/>';
    }


    $htm .= '<tr><td colspan="2" style="text-align: right;"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
				<td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($Cat_Grand_Total) . '</b></span></td></tr>
				</table><br/><br/>';
                if ($Transaction_Type == 'Cash_Bill_Details') {

                    $htm .= '<span style="font-size: x-small;"><b>ADVANCE PAYMENTS</b></span>
                        <table width=100% border=1 style="border-collapse: collapse;">
                            <tr>
                                <td width="5%"><span style="font-size: x-small;">SN</span></td>
                                <td><span style="font-size: x-small;">ITEM NAME</span></td>
                                <td width="12%"><span style="font-size: x-small;">RECEIPT#</span></td>
                                <td width="20%"><span style="font-size: x-small;">RECEIPT DATE</span></td>
                                <td width="12%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
                            </tr>';
            
                    $Direct_Cash_Grand_Total = 0;
                    $sn = 0;
                    $select=   json_decode(getPatientDirectCash($Registration_ID,$Patient_Bill_ID), true); 
                           
                    if (sizeof($select) > 0) {
                        $temp = 0;
                        foreach($select as $data) {
            
                            $htm .= '<tr>
                                    <td><span style="font-size: x-small;">' . ++$sn . '<b>.</b></span></td>
                                    <td><span style="font-size: x-small;">' . ucwords(strtolower($data['Product_Name'] . ' ~ ' . $data['Item_Name'])) . '</span></td>
                                    <td><span style="font-size: x-small;">' . $data['Patient_Payment_ID'] . '</span></td>
                                    <td><span style="font-size: x-small;">' . $data['Payment_Date_And_Time'] . '</span></td>
                                    <td style="text-align: right;"><span style="font-size: x-small;">' . number_format(($data['Price'] - $data['Discount']) * $data['Quantity']) . '</span></td>
                                </tr>';
            
                            $Direct_Cash_Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        }
                    }
            
                    $htm .= '<tr><td colspan="4" style="text-align: left;"><span style="font-size: x-small;"><b>TOTAL AMOUNT PAID</b></span></td>
                                <td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($Direct_Cash_Grand_Total) . '</b></span></td></tr>
                            </table><br/>';
                }
                $htm .= '
                <span style="font-size: x-small;"><b>OVERALL SUMMARY</b></span>
                    <table width="55%" border=1 style="border-collapse: collapse;">
                        <tr>
                            <td width="65%"><span style="font-size: x-small;">Total Amount Required</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Cat_Grand_Total) . '</span></td>
                        </tr>
                        <tr>
                            <td width="65%"><span style="font-size: x-small;">Total Amount Paid</span></td>
                            <td style="text-align: right;">';
        
                            
        
                if ($Transaction_Type == 'Cash_Bill_Details') {
                $htm .= '<span style="font-size: x-small;">' . number_format($Direct_Cash_Grand_Total) . '</span>';
                } else {
                $htm .= "<span style='font-size: x-small;'>(<i>Not applicable</i>)</span>";
                }
        
                $htm .= '</td>
                            </tr>';//</table>
                           
                            if(sizeof($selectexmption)>0){
                                foreach($selectexmption as $rw){
                                    $Anaombewa =$rw['Anaombewa'];
                                    $kiasikinachoombewamshamaha = $rw['kiasikinachoombewamshamaha'];
                                    $amountsuggested = $rw['amountsuggested'];
                                    $maoniDHS = $rw['maoniDHS'];
                                    $htm.= "<tr><td style='font-size: x-small;'>Kiwango cha <b>".$Anaombewa."</b></td>
                                    <td style='text-align:right;'>".number_format($kiasikinachoombewamshamaha)."</td></tr>";
                                }                                         
                            }else{
                                $amountsuggested = 0;
                                $kiasikinachoombewamshamaha=0;
                            }
                            $Temp_Balance = ($Grand_Total - ($Grand_Total_Direct_Cash + $kiasikinachoombewamshamaha));
                            $htm.='
                            <tr>
                                <td><span style="font-size: x-small;">Balance</span></td>';
        
            if ($Transaction_Type == 'Cash_Bill_Details') {
                if ($Cat_Grand_Total > $Direct_Cash_Grand_Total) {
                    $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Temp_Balance) . '</span></td>';
                } else {
                    $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">0</span></td>';
                }
            } else {
                $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Cat_Grand_Total) . '</span></td>';
            }
            $htm .= '</tr>';
        
            if (($Transaction_Type == 'Cash_Bill_Details') && ($Cat_Grand_Total < $Direct_Cash_Grand_Total)) {
                $htm .= "<tr>
                        <td><span style='font-size: x-small;'>Refund Amount</span></td>
                        <td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($Direct_Cash_Grand_Total - $Cat_Grand_Total) . "</span></td>
                    </tr>";
            }
    $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Bill Status</span></td>';
    if ($Transaction_Type == 'Cash_Bill_Details') {
        //get employee clearded the bill(Cash)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Cash_Bill_Status . '</span></td>';
    } else {
        //get employee clearded the bill(Credit)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Credit_Bill_Status . '</span></td>';
    }
    $htm .= '</tr>';
    if ($Transaction_Type == 'Cash_Bill_Details' && strtolower($Cash_Bill_Status) == 'cleared') {
        //get employee clearded the bill(Cash)
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Cleared By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Cash_Clearer)) . '</span></td>';
    } else if ($Transaction_Type == 'Credit_Bill_Details' && strtolower($Credit_Bill_Status) == 'cleared') {
        //get employee clearded the bill(Credit)
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Approved By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Credit_Clearer)) . '</span></td>';
    }
    $htm .= '</table></td></tr></table>';
}

//PDF GENERATOR=============================================================


include("./MPDF/mpdf.php");
$mpdf = new mPDF('', '', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('Printed By ' . strtoupper($Emp_Name) . '|{PAGENO}/{nb}|{DATE d-m-Y}' .'             Powered by GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();






