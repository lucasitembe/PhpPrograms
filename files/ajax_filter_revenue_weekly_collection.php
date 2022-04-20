<?php
include_once("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])){
   $start_date=$_POST['start_date'];
   $end_date=$_POST['end_date'];
   $report_type=$_POST['report_type'];
   if($report_type=="daily_report"){
       $time_frame=0;
       $time_interval=86400;
       $day_week="Day";
   }else{
      $time_frame=518400;
      $time_interval=604800;
       $day_week="Week";
   }
   
   $date_from = strtotime($start_date); // Convert date to a UNIX timestamp  
   $date_to = strtotime($end_date); // Convert date to a UNIX timestamp
        $count_seven_days=0;
        $serial_n_count=1;
        $loop_count=1;
        
                $pharmacy_cash_grand=0;
                $pharmacy_credit_grand=0;
                
                $laboratory_cash_grand=0;
                $laboratory_credit_grand=0;
                
                $radiology_cash_grand=0;
                $radiology_credit_grand=0;
                
                $consultation_credit_grand=0;
                $consultation_cash_grand=0;
                
                $procedure_cash_grand=0;
                $procedure_credit_grand=0;
                
                $surgery_cash_grand=0;
                $surgery_credit_grand=0;
        
   	for ($i=$date_from; $i<=$date_to; $i+=$time_interval) {
		$Current_Date = date("Y-m-d", $i);
		$Next_Date = date("Y-m-d", ($i+$time_frame));
		
                if($report_type=="daily_report"){
                   $filter="AND DATE(pp.Payment_Date_And_Time) ='$Current_Date'"; 
                   $transaction_time="$Current_Date ($day_week $serial_n_count)";
                }else{
                     $filter="AND pp.Payment_Date_And_Time BETWEEN '$Current_Date' AND '$Next_Date'";
                     $transaction_time="$Current_Date - $Next_Date ($day_week $serial_n_count)";
                }
                
                
                
                $pharmacy_cash_subtotal=0;
                $pharmacy_credit_subtotal=0;
                
                $laboratory_cash_subtotal=0;
                $laboratory_credit_subtotal=0;
                
                $radiology_cash_subtotal=0;
                $radiology_credit_subtotal=0;
                
                $consultation_credit_subtotal=0;
                $consultation_cash_subtotal=0;
                
                $procedure_cash_subtotal=0;
                $procedure_credit_subtotal=0;
                
                $surgery_cash_subtotal=0;
                $surgery_credit_subtotal=0;
               
                 //select amount
       $sql_select_amount_result=mysqli_query($conn,"SELECT Price,Check_In_Type,Quantity,Billing_Type FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID $filter AND pp.Sponsor_ID IN (SELECT Sponsor_ID FROM tbl_sponsor WHERE Exemption='no')") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_amount_result)>0){
            while($amount_rows=mysqli_fetch_assoc($sql_select_amount_result)){
                $Price=$amount_rows['Price'];
                $Check_In_Type=$amount_rows['Check_In_Type'];
                $Quantity=$amount_rows['Quantity'];
                $Billing_Type=$amount_rows['Billing_Type'];
                
                if($Check_In_Type=='Pharmacy'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $pharmacy_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $pharmacy_credit_subtotal+=($Price*$Quantity);
                   }
                }
                if($Check_In_Type=='Laboratory'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $laboratory_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $laboratory_credit_subtotal+=($Price*$Quantity);
                   }
                }
                if($Check_In_Type=='Radiology'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $radiology_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $radiology_credit_subtotal+=($Price*$Quantity);
                   }
                }
                if($Check_In_Type=='Doctor Room'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $consultation_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $consultation_credit_subtotal+=($Price*$Quantity);
                   }
                }
                if($Check_In_Type=='Procedure'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $procedure_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $procedure_credit_subtotal+=($Price*$Quantity);
                   }
                }
                if($Check_In_Type=='Surgery'){
                   if($Billing_Type=="Inpatient Cash"||$Billing_Type=="Outpatient Cash"||$Billing_Type=="Patient From Outside"){
                       $surgery_cash_subtotal+=($Price*$Quantity);
                   }else{
                       $surgery_credit_subtotal+=($Price*$Quantity);
                   }
                }
                
            }
       }
                $pharmacy_cash_grand+=$pharmacy_cash_subtotal;
                $pharmacy_credit_grand+=$pharmacy_credit_subtotal;
                
                $laboratory_cash_grand+=$laboratory_cash_subtotal;
                $laboratory_credit_grand+=$laboratory_credit_subtotal;
                
                $radiology_cash_grand+=$radiology_cash_subtotal;
                $radiology_credit_grand+=$radiology_credit_subtotal;
                
                $consultation_credit_grand+=$consultation_credit_subtotal;
                $consultation_cash_grand+=$consultation_cash_subtotal;
                
                $procedure_cash_grand+=$procedure_cash_subtotal;
                $procedure_credit_grand+=$procedure_credit_subtotal;
                
                $surgery_cash_grand+=$surgery_cash_subtotal;
                $surgery_credit_grand+=$surgery_credit_subtotal;               

                ?>
                        <tr>
                            <td><?= $serial_n_count ?></td>
                            <td><?= $transaction_time ?></td>
                            <td><?= number_format($pharmacy_cash_subtotal) ?></td>
                            <td><?= number_format($pharmacy_credit_subtotal) ?></td>
                            <td><?= number_format($pharmacy_cash_subtotal+$pharmacy_credit_subtotal) ?></td>
                            <td><?= number_format($laboratory_cash_subtotal) ?></td>
                            <td><?= number_format($laboratory_credit_subtotal) ?></td>
                            <td><?= number_format($laboratory_credit_subtotal+$laboratory_cash_subtotal) ?></td>
                            <td><?= number_format($radiology_cash_subtotal) ?></td>
                            <td><?= number_format($radiology_credit_subtotal) ?></td>
                            <td><?= number_format($radiology_cash_subtotal+$radiology_credit_subtotal) ?></td>
                            <td><?= number_format($surgery_cash_subtotal) ?></td>
                            <td><?= number_format($surgery_credit_subtotal) ?></td>
                            <td><?= number_format($surgery_cash_subtotal+$surgery_credit_subtotal) ?></td>
                            <td><?= number_format($procedure_cash_subtotal) ?></td>
                            <td><?= number_format($procedure_credit_subtotal) ?></td>
                            <td><?= number_format($procedure_cash_subtotal+$procedure_credit_subtotal) ?></td>
                            <td><?= number_format($consultation_cash_subtotal) ?></td>
                            <td><?= number_format($consultation_credit_subtotal) ?></td>
                            <td><?= number_format($consultation_cash_subtotal+$consultation_credit_subtotal) ?></td>
                        </tr>
                    <?php
                    $serial_n_count++;
	}
    ?>
                        <tr><td colspan="2"><b>GRAND TOTAL</b></td>
                        <td><?= number_format($pharmacy_cash_grand) ?></td>
                            <td><?= number_format($pharmacy_credit_grand) ?></td>
                            <td><?= number_format($pharmacy_cash_grand+$pharmacy_credit_grand) ?></td>
                            <td><?= number_format($laboratory_cash_grand) ?></td>
                            <td><?= number_format($laboratory_credit_grand) ?></td>
                            <td><?= number_format($laboratory_credit_grand+$laboratory_cash_grand) ?></td>
                            <td><?= number_format($radiology_cash_grand) ?></td>
                            <td><?= number_format($radiology_credit_grand) ?></td>
                            <td><?= number_format($radiology_cash_grand+$radiology_credit_grand) ?></td>
                            <td><?= number_format($surgery_cash_grand) ?></td>
                            <td><?= number_format($surgery_credit_grand) ?></td>
                            <td><?= number_format($surgery_cash_grand+$surgery_credit_grand) ?></td>
                            <td><?= number_format($procedure_cash_grand) ?></td>
                            <td><?= number_format($procedure_credit_grand) ?></td>
                            <td><?= number_format($procedure_cash_grand+$procedure_credit_grand) ?></td>
                            <td><?= number_format($consultation_cash_grand) ?></td>
                            <td><?= number_format($consultation_credit_grand) ?></td>
                            <td><?= number_format($consultation_cash_grand+$consultation_credit_grand) ?></td>
                        </tr>                    
    <?php
}

