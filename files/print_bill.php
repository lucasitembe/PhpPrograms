<?php
    include("./includes/connection.php");
    
    $Registration_ID = '';$data='';
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['folio'])){
        $folio = $_GET['folio'];
    }
     $bl=  mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = '$folio' ORDER BY Patient_Payment_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    $Billing_Type=  mysqli_fetch_assoc($bl)['Billing_Type'];
    
    if($Billing_Type == 'Outpatient Cash'){
        $Billing_Type='Inpatient Cash';
    }elseif ($Billing_Type == 'Outpatient Credit') {
         $Billing_Type='Inpatient Credit';
    }
    
    $select_Patients_details = mysqli_query($conn,
            "select * from tbl_patient_registration pr ,tbl_sponsor s, tbl_admission ad,tbl_employee e,tbl_hospital_ward hp,tbl_beds bd where
		pr.registration_id = ad.registration_id and Admission_Status = 'pending' and
		s.Sponsor_ID = pr.Sponsor_ID and e.Employee_ID= ad.Admission_Employee_ID
                and hp.Hospital_Ward_ID= ad.Hospital_Ward_ID and bd.Bed_ID= ad.Bed_ID
		AND pr.Registration_ID='$Registration_ID'
		") or die(mysqli_error($conn));
  //$rs=  mysqli_query($conn,$qr) or die(mysqli_error($conn));
   $patient=  mysqli_fetch_array($select_Patients_details);
   $data .= "<center><table width ='100%' height = '30px'>
		    <tr><td colspan='4' style='text-align: center;'>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>PATIENT BILLING REPORT</b></span></td></tr>
                     </table></center>
		    ";
    
    $data .= '<br/><center>
          <table border="0" width="100%">
            <tr>
                <td style="text-align:ridght"><b>Patient Name</b></td><td>'.$patient['Patient_Name'].'</td><td style="text-align:rigdht" ><b>Sponsor</b></td><td colspan="">'.$patient['Guarantor_Name'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Admitted By</b></td><td>'.$patient['Employee_Name'].'</td><td style="text-align:ridght"><b>Admission Date</b></td><td>'.$patient['Admission_Date_Time'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Ward</b></td><td>'.$patient['Hospital_Ward_Name'].'</td><td style="text-align:rigdht"><b>Bed #</b></td><td colspan="">'.$patient['Bed_Name'].'</td>
               </tr>
               <tr>
                 <td style="text-align:ridght" ><b>Follio #</b></td><td style="text-align:left" colspan="">'.$folio.'</td><td style="text-align:rigdht"><b>Billing Type</b></td><td>'.$Billing_Type.'</td> 
               </tr>
          </table></center>
         <br/>
        ';
    
    $data .= '<center><table width ="100%" border="0">';
    $data .= '<tr>
              <td width="5%"><b>SN</b></td>
              <td width="18%"><b>PARTICULAR</b></td>
	      <td width="15%"><b>PRICE</b></td>
	      <td width="17%"><b>QUANTITY</b></td>
              <td width="19%"><b>BALANCE DUE</b></td>
	 </tr>
         ';
    $data .= "<tr><td colspan='5'><hr/></td></tr>";
       
//    $Select_payments = "SELECT SUM((price-Discount)*Quantity) FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID = receipt_number) AS Amount
//                        FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = $folio
//			AND (Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Inpatient Cash')";
    
    $select_direct_cash="SELECT SUM((price-Discount)*Quantity) AS Amount_In_Hand FROM `tbl_patient_payment_item_list` ppil LEFT JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID WHERE Registration_ID=$Registration_ID AND Folio_Number = '$folio' AND ppil.Check_In_Type='Direct Cash'";
    $results_direct_cash = mysqli_query($conn,$select_direct_cash) or die(mysqli_error($conn));
    
    $Amount_In_Hand = mysqli_fetch_assoc($results_direct_cash)['Amount_In_Hand'];
    
    //echo $Amount_In_Hand.'<br/>';
    
    $toal_balance_Due=0;
    $total_qty=0;
    
    $selec_items_in_recept="
               SELECT distinct(ppil.`Item_ID`),Product_Name,price FROM `tbl_patient_payment_item_list` ppil LEFT JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID JOIN tbl_items i ON i.Item_ID=ppil.Item_ID WHERE pp.Billing_Type = '$Billing_Type' AND pp.Registration_ID=$Registration_ID AND Folio_Number = '$folio' AND ppil.Status !='Removed'  AND ppil.Check_In_Type !='Direct Cash' group by ppil.Patient_Payment_ID ,ppil.Item_ID
            ";
    $results_total_item = mysqli_query($conn,$selec_items_in_recept) or die(mysqli_error($conn));
    $sn=1;
    //Foreach Iterm
    while ($row = mysqli_fetch_array($results_total_item)) {
       //get price
       // echo $row['Item_ID'].'  '.$row['Product_Name'].'  '.$row['price'].'<br/>';
       $Item_ID=$row['Item_ID'];
       $Product_Name=$row['Product_Name'];
       $Price=$row['price'];
       
       //Retrieve quantity total;
       
       $result_quantity=mysqli_query($conn,"SELECT SUM(Quantity) AS quantity FROM `tbl_patient_payment_item_list` ppil LEFT JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID JOIN tbl_items i ON i.Item_ID=ppil.Item_ID WHERE pp.Billing_Type = '$Billing_Type' AND pp.Registration_ID=$Registration_ID AND Folio_Number = '$folio' AND ppil.Status !='Removed'   AND ppil.Item_ID='$Item_ID'") or die(mysqli_error($conn));
        
       $qtydeni=  mysqli_fetch_array($result_quantity);
       
       $quantity=$qtydeni['quantity'];
       //$quantity=$qtydeni['quantity'];
       $to_pay=$quantity*$Price;
       
       //echo $Product_Name.'  '.$Price.'  '.$quantity.'  '.$deni.'<br/>';
       
       $paticular='<span class="individualBill" Onclick="individualPaymentDetais(\''.$Item_ID.'\',\''.$folio.'\',\''.$Billing_Type.'\',\''.$Registration_ID.'\')">'.$Product_Name.'</span>';
       $price_disp='<span class="individualBill" Onclick="individualPaymentDetais(\''.$Item_ID.'\',\''.$folio.'\',\''.$Billing_Type.'\',\''.$Registration_ID.'\')">'.number_format($Price).'</span>';
       $to_pay_disp='<span class="individualBill" Onclick="individualPaymentDetais(\''.$Item_ID.'\',\''.$folio.'\',\''.$Billing_Type.'\',\''.$Registration_ID.'\')">'.number_format($to_pay).'</span>';
       $quantity_disp='<span class="individualBill" Onclick="individualPaymentDetais(\''.$Item_ID.'\',\''.$folio.'\',\''.$Billing_Type.'\',\''.$Registration_ID.'\')">'.number_format($quantity).'</span>';
       
       $total_qty+=$quantity;
       $toal_balance_Due+=$to_pay;
       
        $data .= '<tr>
              <td width="5%">'.$sn++.'</td>
              <td width="48%">'.$paticular.'</td>
              <td width="18%">'.$price_disp.'</td>
	      <td width="15%">'.$quantity_disp.'</td>
	      <td width="19%">'.$to_pay_disp.'</td>
              
	 </tr>';
	      
    }
   
    if($Billing_Type=='Inpatient Cash'){
    $data .= ''
    . ''
            . '<tr><td colspan="5"><hr/></td></tr>
                 <tr style="font-weight: bold">
                    <td style="text-align: right" colspan="4">Total Balance Due</td><td style="text-align: right;padding-right: 30px">'.number_format($toal_balance_Due).'</td>
                </tr>
                <tr style="font-weight: bold">
                    <td style="text-align: right" colspan="4">Amount Paid</td><td style="text-align: right;padding-right: 30px;">'.number_format($Amount_In_Hand).'</td>
                </tr>
                <tr style="font-weight: bold">';
         $pay='';$col='';
           if(($Amount_In_Hand-$toal_balance_Due)>0){
               $pay='Over Paid Amount';
               $col='color:blue';
           }else{
                $pay= 'Remaining Balance';
                 $col='color:red';
           }
           
            
          $data .= '           <td style="text-align: right" colspan="4">'.$pay.'</td><td style="text-align: right;padding-right: 30px;'.$col.'">'.number_format($Amount_In_Hand-$toal_balance_Due).'</td>
                </tr>
                <tr><td colspan="5"></td></tr>
                <tr>';
    }else{
        $data .=' <tr><td colspan="5"><hr/></td></tr>
                 <tr style="font-weight: bold">
                    <td style="text-align: right" colspan="4"><span style="font-size:17px">Total Balance Due</span></td><td style="text-align: right;padding-right: 30px"><b style="font-size:17px;">'.number_format($toal_balance_Due).'</b></td>
                </tr>';
    }
    if(($Amount_In_Hand-$toal_balance_Due) < 0 and $Billing_Type=='Inpatient Cash'){
        $data .=' <tr>
                    <td style="text-align: center;color:red;font-size:20px" colspan="5"><br/><br/><b>PATIENT HAS NOT PAID YET</b></td>
                </tr>';
    }else{
             $data .=        '<tr><td style="text-align: left" colspan="5"><b>Patient signature</b> ................................</td>
                </tr>
                 <tr>
                    <td style="text-align: left" colspan="5"><b>Staff signature</b> ..................................</td>
                </tr>
                <tr>
                    <td style="text-align: left" colspan="5"><b>Release Date:</b> '.  date('Y-m-d').'</td>
                </tr>';
    }                        
            $data .= '</table></center>';
    
   // echo $data;
    
     //echo $htm;
    $htm .= "</table>";
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($data);
    $mpdf->Output();
    exit;
    
//    echo "<tr><td colspan='5'<hr></td></tr>";
//    echo "<tr><td colspan=3 style='text-align: right;'><b> TOTAL :   </b></td>
//    <td style='text-align: right;'><b>".number_format($inactive_sum)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_billed)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_paid)."</b></td>
//    <td style='text-align: right;'><b>".number_format($ballance)."</b></td></tr>";
//    echo "<tr><td colspan=7><hr></td></tr></table></center>";
?>