<?php
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
include_once("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
}else{
    $start_date=""; 
}
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date']; 
}else{
   $end_date=""; 
}

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
//select amount from each category
            $number_of_service=0;
            
            $total_pharmacy_cash=0;
            $total_pharmacy_credit=0;
            $total_pharmacy_services_cash=0;
            $total_pharmacy_services_credit=0;
            $total_pharmacy_services_in_credit=0;
            
            $total_laboratory_cash=0;
            $total_laboratory_credit=0;
            $total_laboratory_services_cash=0;
            $total_laboratory_services_credit=0;
            $total_laboratory_services_in_credit=0;
            
            $total_radiology_cash=0;
            $total_radiology_credit=0;
            $total_radiology_services_cash=0;
            $total_radiology_services_credit=0;
            $total_radiology_services_in_credit=0;
            
            $total_consumable_services_cash=0;
            $total_consumable_services_credit=0;
            $total_consumable_services_in_credit=0;
            $total_consumable_msamaha=0;

           $total_accomodation_services_cash=0;
           $total_accomodation_services_credit=0;
           $total_accomodation_services_in_credit=0;
           
           $total_accomodation_msamaha=0;
           
           
           
           $total_wardround_services_cash=0;
           $total_wardround_services_credit=0;
           $total_wardround_services_in_credit=0;
           
           
           $total_surgeries_main_theater_services_cash=0;
           $total_surgeries_main_theater_services_credit=0;
           $total_surgeries_main_theater_services_in_credit=0;
           
           
           
           $total_surgical_minor_procedure_services_cash=0;
           $total_surgical_minor_procedure_services_credit=0;
           $total_surgical_minor_procedure_services_in_credit=0;
           
           
           $total_diagnostic_procedure_and_curative_procedure_services_cash=0;
           $total_diagnostic_procedure_and_curative_procedure_services_credit=0;
           $total_diagnostic_procedure_and_curative_procedure_services_in_credit=0;
           
           
           $total_curative_procedure_msamaha=0;
           $total_consultation_services_cash=0;
           $total_consultation_services_credit=0;
           $total_consultation_services_in_credit=0;
           
           
           $total_Dialysis_services_cash=0;
           $total_Dialysis_services_credit=0;
           $total_Dialysis_services_in_credit=0;
           
           
           
           
           $total_others_msamaha=0;
           $cash_deposit_total=0;
            //select all items
//           
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
            $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Item_ID,ppl.Check_In_Type,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID $filter AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){
                
               while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                  $payment_type=$items_rows['payment_type'];
                  $Billing_Type=$items_rows['Billing_Type'];
                  $Pre_Paid=$items_rows['Pre_Paid'];
                  $Sponsor_ID=$items_rows['Sponsor_ID'];
                  $Discount=$items_rows['Discount'];
                  $Price=$items_rows['Price'];
                  $Quantity=$items_rows['Quantity'];
                  $Check_In_Type=$items_rows['Check_In_Type'];
                  $Item_ID=$items_rows['Item_ID'];
                  
                   $number_of_service++;
                   $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                   $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
///////////////////////////////////////////////////////////////
               	$sql_select_revenue_report_category_result=mysqli_query($conn,"SELECT revenue_report_category FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                $revenue_report_category=mysqli_fetch_assoc($sql_select_revenue_report_category_result)['revenue_report_category'];
                
                
                    if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
	                if($revenue_report_category=="Pharmacy"){   
                        $total_pharmacy_msamaha += ($Quantity*$Price);
                        $total_pharmacy_services_credit++;
                        }
                         if($revenue_report_category=="Laboratory"){
                            $revenue_report_category+=($Quantity*$Price);
                            $total_laboratory_services_credit++;
                         }
                         if($revenue_report_category=="Radiology"){
                            $total_radiology_msamaha+=($Quantity*$Price);
                            $total_radiology_services_credit++;
                         }
                             if($revenue_report_category=="Consumable"){
                               $total_consumable_msamaha+=($Quantity*$Price);
                               $total_consumable_services_credit++;
                            }
                            if($revenue_report_category=="Accommodation"){
                               $total_accomodation_msamaha+=($Quantity*$Price);
                               $total_accomodation_services_credit++;
                            }
                            if($revenue_report_category=="Ward Round"){
                               $total_wardround_msamaha+=($Quantity*$Price);
                                $total_wardround_services_credit++;
                            }
                            if($revenue_report_category=="Surgeries-main theater"){
                               $total_surgeries_main_theater_msamaha+=($Quantity*$Price);
                               $total_surgeries_main_theater_services_credit++;
                            }
                            if($revenue_report_category=="Surgical minor procedure"){
                               $total_surgical_minor_procedure_msamaha+=($Quantity*$Price);
                               $total_surgical_minor_procedure_services_credit++;
                            }
                            if($revenue_report_category=="Diagnostic procedure and curative procedure"){
                               $total_diagnostic_procedure_and_curative_procedure_msamaha+=($Quantity*$Price);
                            }
                            if($revenue_report_category=="Curative Procedure"){
                               $total_curative_procedure_msamaha+=($Quantity*$Price);
                            }
                            if($revenue_report_category=="Consultation"){
                               $total_consultation_msamaha+=($Quantity*$Price);
                               $total_consultation_services_credit++;
                               
                            }
                            if($revenue_report_category=="Dialysis"){
                               $total_Dialysis_msamaha+=($Quantity*$Price);
                               $total_Dialysis_services_credit++;
                               
                            }
                            if($Check_In_Type=="Direct cash"){
                               $total_others_msamaha+=($Quantity*$Price);
                            }
                    }  else {
                      	
			if((strtolower($Billing_Type) == 'outpatient cash' && $Pre_Paid == '0') or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                            if($revenue_report_category=="Pharmacy"){
                               $total_pharmacy_cash+=($Quantity*$Price);
                               $total_pharmacy_services_cash++;
                            }
                            if($revenue_report_category=="Laboratory"){
                               $total_laboratory_cash+=($Quantity*$Price);
                                $total_laboratory_services_cash++;
                            }
                            if($revenue_report_category=="Radiology"){
                               $total_radiology_cash+=($Quantity*$Price);
                               $total_radiology_services_cash++;
                               
                            }
                            if($revenue_report_category=="Consumable"){
                               $total_consumable_cash+=($Quantity*$Price);
                               $total_consumable_services_cash++;
                            }
                            if($revenue_report_category=="Accommodation"){
                               $total_accomodation_cash+=($Quantity*$Price);
                               $total_accomodation_services_cash++;
                            }
                            if($revenue_report_category=="Ward Round"){
                               $total_ward_round_cash+=($Quantity*$Price);
                               $total_wardround_services_cash++;
                            }
                            if($revenue_report_category=="Surgeries-main theater"){
                               $total_surgeries_main_theater_cash+=($Quantity*$Price);
                               $total_surgeries_main_theater_services_cash++;
                            }
                            if($revenue_report_category=="Surgical minor procedure"){
                               $total_surgical_minor_cash+=($Quantity*$Price);
                               $total_surgical_minor_procedure_services_cash++;
                            }
                            if($revenue_report_category=="Diagnostic procedure and curative procedure"){
                               $total_diagnostic_procedure_and_curative_procedure_cash+=($Quantity*$Price);
                               $total_diagnostic_procedure_and_curative_procedure_services_cash++;
                            }
                            if($revenue_report_category=="Curative Procedure"){
                               $total_curative_procedure_cash+=($Quantity*$Price);
                            }
                            if($revenue_report_category=="Consultation"){
                               $total_consultation_cash+=($Quantity*$Price);
                                $total_consultation_services_cash++;
                            }
                            if($revenue_report_category=="Others"){
                               $total_others_cash+=($Quantity*$Price);
                            }
                            if($revenue_report_category=="Dialysis"){
                               $total_Dialysis_cash+=($Quantity*$Price);
                               $total_Dialysis_services_cash++;
                            }
                            if($Check_In_Type=="Direct cash"){
                                $total_others_cash+=($Quantity*$Price);
                            }
                        }elseif((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit')){
                            if($revenue_report_category=="Pharmacy"){
                               $total_pharmacy_credit+=($Quantity*$Price);
                               $total_pharmacy_services_in_credit++;
                            }
                            if($revenue_report_category=="Laboratory"){
                               $total_laboratory_credit+=($Quantity*$Price);
                               $total_laboratory_services_in_credit++;
                            }
                            if($revenue_report_category=="Radiology"){
                               $total_radiology_credit+=($Quantity*$Price);
                               $total_radiology_services_in_credit++;
                            }
                            if($revenue_report_category=="Consumable"){
                               $total_consumable_credit+=($Quantity*$Price);
                               $total_consumable_services_in_credit++;
                            }
                            if($revenue_report_category=="Accommodation"){
                               $total_accomodation_credit+=($Quantity*$Price);
                               $total_accomodation_services_in_credit++;
                            }
                            if($revenue_report_category=="Ward Round"){
                               $total_ward_round_credit+=($Quantity*$Price);
                               $total_wardround_services_in_credit++;
                            }
                            if($revenue_report_category=="Surgeries-main theater"){
                               $total_surgeries_main_theater_credit+=($Quantity*$Price);
                               $total_surgeries_main_theater_services_in_credit++;
                            }
                            if($revenue_report_category=="Surgical minor procedure"){
                               $total_surgical_minor_procedure_credit+=($Quantity*$Price);
                               $total_surgical_minor_procedure_services_in_credit++;
                            }
                            if($revenue_report_category=="Diagnostic procedure and curative procedure"){
                               $total_diagnostic_procedure_and_curative_procedure_credit+=($Quantity*$Price);
                               $total_diagnostic_procedure_and_curative_procedure_services_in_credit++;
                            }
//                            if($revenue_report_category=="Curative Procedure"){
//                               $total_curative_procedure_credit+=($Quantity*$Price);
//                            }
                            if($revenue_report_category=="Consultation"){
                               $total_consultation_credit+=($Quantity*$Price);
                               $total_consultation_services_in_credit++;
                            }
                            if($revenue_report_category=="Dialysis"){
                               $total_Dialysis_credit+=($Quantity*$Price);
                               $total_Dialysis_services_in_credit++;
                            }
                            if($Check_In_Type=="Direct cash"){
                               $total_others_credit+=($Quantity*$Price);
                            }
                           
                        }


                                    }
               ///////////////////////////////////////////////////////////////
               }
            }
                }
?>        
<div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <div class="box box-primary">
    <table class="table">
        <tr>
            <td style="width:30%">
               CATEGORY 
            </td>
            <td>
               NO OF SERVICES
            </td>
            <td>
               CASH 
            </td>
            <td>
               CREDIT 
            </td>
            
            <td>
               TOTAL 
            </td>
           
            
        </tr>
        <tr><td class='department_row'><b>PHARMACY</b></td><td><?= number_format( $total_pharmacy_services_cash + $total_pharmacy_services_in_credit) ?></td><td><?= number_format($total_pharmacy_cash) ?></td><td><?= number_format($total_pharmacy_credit)?></td><td><?= number_format($total_pharmacy_credit+$total_pharmacy_cash)?></td></tr>
        <tr><td class='department_row'><b>LABORATORY</b></td><td><?= number_format( $total_laboratory_services_cash + $total_laboratory_services_in_credit) ?></td><td><?= number_format($total_laboratory_cash) ?></td><td><?= number_format($total_laboratory_credit) ?></td><td><?= number_format($total_laboratory_credit+$total_laboratory_cash) ?></td></tr>
        <tr><td class='department_row'><b>RADIOLOGY</b></td><td><?= number_format(  $total_radiology_services_cash + $total_radiology_services_in_credit) ?></td><td><?= number_format($total_radiology_cash) ?></td><td><?= number_format($total_radiology_credit) ?></td><td><?= number_format($total_radiology_cash+$total_radiology_credit) ?></td></tr>
        <tr><td class='department_row'><b>CONSUMABLE</b></td> <td> <?= number_format(  $total_consumable_services_cash + $total_consumable_services_in_credit) ?></td><td><?= number_format($total_consumable_cash) ?></td><td><?= number_format($total_consumable_credit) ?></td><td><?= number_format($total_consumable_cash+$total_consumable_credit) ?></td></tr>
        <tr><td class='department_row'><b>ACCOMMODATION</b></td> <td> <?= number_format($total_accomodation_services_cash + $total_accomodation_services_in_credit) ?></td><td><?= number_format($total_accomodation_cash) ?></td><td><?= number_format($total_accomodation_credit) ?></td><td><?= number_format($total_accomodation_credit+$total_accomodation_cash) ?></td></tr>
        <tr><td class='department_row'><b>WARD ROUNDS</b></td> <td>  <?= number_format( $total_wardround_services_cash + $total_wardround_services_in_credit) ?></td><td><?= number_format($total_ward_round_cash) ?></td><td><?= number_format($total_ward_round_credit) ?></td><td><?= number_format($total_ward_round_credit+$total_ward_round_cash) ?></td></tr>
        <tr><td class='department_row'><b>SURGERIES(Main Theatre)</b></td> <td> <?= number_format( $total_surgeries_main_theater_services_cash + $total_surgeries_main_theater_services_in_credit) ?></td><td><?= number_format($total_surgeries_main_theater_cash) ?></td><td><?= number_format($total_surgeries_main_theater_credit) ?></td><td><?= number_format($total_surgeries_main_theater_credit+$total_surgeries_main_theater_cash) ?></td></tr>
        <tr><td class='department_row'><b>SURGICAL MINOR PROCEDURE(side rooms)</b></td> <td> <?= number_format(  $total_surgical_minor_procedure_services_cash + $total_surgical_minor_procedure_services_in_credit) ?></td><td><?= number_format($total_surgical_minor_cash) ?></td><td><?= number_format($total_surgical_minor_procedure_credit) ?></td><td><?= number_format($total_surgical_minor_cash+$total_surgical_minor_procedure_credit) ?></td></tr>
        <tr><td class='department_row'><b>DIAGNOSTIC PROCEDURES & CURATIVE PROCEDURES</b></td> <td>  <?= number_format( $total_diagnostic_procedure_and_curative_procedure_services_cash +  $total_diagnostic_procedure_and_curative_procedure_services_in_credit) ?></td><td><?= number_format($total_diagnostic_procedure_and_curative_procedure_cash) ?></td><td><?= number_format($total_diagnostic_procedure_and_curative_procedure_credit) ?></td><td><?= number_format($total_diagnostic_procedure_and_curative_procedure_cash+$total_diagnostic_procedure_and_curative_procedure_credit) ?></td></tr>
        <!--<tr><td class='department_row' onclick="open_all_other_category_detail(<?= $finance_department_id ?>,'<?= $finance_department_name ?>','Curative Procedure')"><b>CURATIVE PROCEDURES</b></td><td><?= number_format($total_curative_procedure_cash) ?></td><td><?= number_format($total_curative_procedure_credit) ?></td><td><?= number_format($total_curative_procedure_msamaha) ?></td><td><?= number_format($total_curative_procedure_cash+$total_curative_procedure_credit) ?></td></tr>-->
        <tr><td class='department_row'><b>CONSULTATIONS</b></td> <td>  <?= number_format(  $total_consultation_services_cash + $total_consultation_services_in_credit) ?></td><td><?= number_format($total_consultation_cash) ?></td><td><?= number_format($total_consultation_credit) ?></td><td><?= number_format($total_consultation_credit+$total_consultation_cash) ?></td></tr>
        <tr><td class='department_row'><b>DIALYSIS</b></td> <td><?= number_format( $total_Dialysis_services_cash + $total_Dialysis_services_in_credit) ?></td><td><?= number_format($total_Dialysis_cash) ?></td><td><?= number_format( $total_Dialysis_credit) ?></td><td><?= number_format($total_Dialysis_cash+$total_Dialysis_credit) ?></td></tr>
        <!--<tr><td class='department_row'><b>OTHERS</b></td> <td></td><td><?= number_format($total_others_cash) ?></td><td><?= number_format($total_others_credit) ?></td><td><?= number_format($total_others_cash+$total_others_credit) ?></td></tr>-->
        <tr><td><b>GRAND TOTAL</b></td> <td></td><td><b><?= number_format($total_pharmacy_cash+$total_laboratory_cash+$total_radiology_cash+$total_consumable_cash+$total_accomodation_cash+$total_ward_round_cash+$total_surgeries_main_theater_cash+$total_surgical_minor_cash+$total_diagnostic_procedure_and_curative_procedure_cash+$total_curative_procedure_cash+$total_consultation_cash+ $total_Dialysis_services_cash) ?></b></td><td><b><?= number_format($total_Dialysis_services_credit+$total_consultation_credit+$total_curative_procedure_credit+$total_diagnostic_procedure_and_curative_procedure_credit+$total_surgical_minor_procedure_credit+$total_surgeries_main_theater_credit+$total_ward_round_credit+$total_accomodation_credit+$total_consumable_credit+$total_radiology_credit+$total_laboratory_credit+$total_pharmacy_credit) ?></b></td><td><b><?= number_format($total_pharmacy_cash+$total_laboratory_cash+$total_radiology_cash+$total_consumable_cash+$total_accomodation_cash+$total_ward_round_cash+$total_surgeries_main_theater_cash+$total_surgical_minor_cash+$total_diagnostic_procedure_and_curative_procedure_cash+$total_curative_procedure_cash+$total_consultation_cash+$total_Dialysis_cash+$total_Dialysis_services_credit+$total_consultation_credit+$total_curative_procedure_credit+$total_diagnostic_procedure_and_curative_procedure_credit+$total_surgical_minor_procedure_credit+$total_surgeries_main_theater_credit+$total_ward_round_credit+$total_accomodation_credit+$total_consumable_credit+$total_radiology_credit+$total_laboratory_credit+$total_pharmacy_credit) ?></b></td></tr>
    </table>
</div>
    </div>