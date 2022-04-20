<?php
    include("./includes/connection.php");
    
    $Registration_ID = '';
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['folio'])){
        $folio = $_GET['folio'];
    }
    
    echo '<center><table width ="100%" border="0">';
    echo '<tr>
              <td width="5%"><b>SN</b></td>
              <td width="18%"><b>PARTICULAR</b></td>
	      <td width="15%" style="text-align:right"><b>PRICE</b></td>
	      <td width="17%" style="text-align:right"><b>QUANTITY</b></td>
              <td width="19%" style="text-align:right"><b>BALANCE DUE</b></td>
	 </tr>
         ';
    echo "<tr><td colspan='5'><hr/></td></tr>";
   
    $bl=  mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = '$folio' ORDER BY Patient_Payment_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    $Billing_Type=  mysqli_fetch_assoc($bl)['Billing_Type'];
    
    if($Billing_Type == 'Outpatient Cash'){
        $Billing_Type='Inpatient Cash';
    }
    
    $Select_payments = "SELECT SUM((price-Discount)*Quantity) FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID = receipt_number) AS Amount
                        FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = $folio
			AND (Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Inpatient Cash')";
    
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
       
       $result_quantity=mysqli_query($conn,"SELECT SUM(Quantity) AS quantity FROM `tbl_patient_payment_item_list` ppil LEFT JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID JOIN tbl_items i ON i.Item_ID=ppil.Item_ID WHERE pp.Billing_Type = '$Billing_Type' AND pp.Registration_ID=$Registration_ID AND Folio_Number = '$folio'  AND ppil.Status !='Removed'   AND ppil.Item_ID='$Item_ID' ") or die(mysqli_error($conn));
        
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
       
        echo '<tr>
              <td width="5%">'.$sn++.'</td>
              <td width="48%">'.$paticular.'</td>
              <td width="18%" style="text-align:right">'.$price_disp.'</td>
	      <td width="15%" style="text-align:right">'.$quantity_disp.'</td>
	      <td width="19%" style="text-align:right">'.$to_pay_disp.'</td>
              
	 </tr>';
	      
    }
   
    echo '</table></center>';
    
//    echo "<tr><td colspan='5'<hr></td></tr>";
//    echo "<tr><td colspan=3 style='text-align: right;'><b> TOTAL :   </b></td>
//    <td style='text-align: right;'><b>".number_format($inactive_sum)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_billed)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_paid)."</b></td>
//    <td style='text-align: right;'><b>".number_format($ballance)."</b></td></tr>";
//    echo "<tr><td colspan=7><hr></td></tr></table></center>";
?>