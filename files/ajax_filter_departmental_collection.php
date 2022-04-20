<?php
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

$count=1;
$consultation_grand_total=0;
$pharmacy_grand_total=0;
$radiology_grand_total=0;
$laboratory_grand_total=0;
$procedure_grand_total=0;
$surgery_grand_total=0;
$deposit_grand_total=0;
$nuclear_grand_total=0;

$verticatl_grand_total=0;

$sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_working_department_result)>0){
    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
       $finance_department_id=$finance_dep_rows['finance_department_id'];
       $finance_department_name=$finance_dep_rows['finance_department_name'];
       $finance_department_code=$finance_dep_rows['finance_department_code'];
       
       
       $Consultation_subtotal=0;
       $pharmacy_subtotal=0;
       $laboratory_subtotal=0;
       $radiology_subtotal=0;
       $procedure_subtotal=0;
       $surgery_subtotal=0;
       $deposit_subtotal=0;
       $Nuclearmedicine_subtotal=0;
       //select amount

      //  die("SELECT Price, Check_In_Type, Quantity, Discount FROM tbl_patient_payment_item_list WHERE finance_department_id='$finance_department_id' AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date')");
       $sql_select_amount_result=mysqli_query($conn,"SELECT Price, Check_In_Type, Quantity, Discount FROM tbl_patient_payment_item_list WHERE finance_department_id='$finance_department_id' AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Pre_Paid = '0' AND Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Transaction_status <> 'cancelled')") or die(mysqli_error($conn));
         if(mysqli_num_rows($sql_select_amount_result)>0){
            while($amount_rows=mysqli_fetch_assoc($sql_select_amount_result)){
                $Price=$amount_rows['Price'];
                $Check_In_Type=$amount_rows['Check_In_Type'];
                $Quantity=$amount_rows['Quantity'];
                $Discount=$amount_rows['Discount'];

                  if($Check_In_Type=='Doctor Room'){
                     $Consultation_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Pharmacy'){
                     $pharmacy_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Laboratory'){
                     $laboratory_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Radiology'){
                     $radiology_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Procedure'){
                     $procedure_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Surgery'){
                     $surgery_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Direct Cash'){
                     $deposit_subtotal+=(($Price-$Discount)*$Quantity); 
                  }elseif($Check_In_Type=='Nuclearmedicine'){
                     $Nuclearmedicine_subtotal+=(($Price-$Discount)*$Quantity); 
                  }   
            }
         }
       $consultation_grand_total+=$Consultation_subtotal;
       $pharmacy_grand_total+=$pharmacy_subtotal;
       $radiology_grand_total+=$radiology_subtotal;
       $laboratory_grand_total+=$laboratory_subtotal;
       $surgery_grand_total+=$surgery_subtotal;
       $procedure_grand_total+=$procedure_subtotal;
       $nuclear_grand_total+=$Nuclearmedicine_subtotal;
       $deposit_grand_total+=$deposit_subtotal;
       echo "<tr>
                <td>$count.</td>
                <td>$finance_department_name</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Doctor Room\",$finance_department_id)'>".number_format($Consultation_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Pharmacy\",$finance_department_id)'>".number_format($pharmacy_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Laboratory\",$finance_department_id)'>".number_format($laboratory_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Radiology\",$finance_department_id)'>".number_format($radiology_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Procedure\",$finance_department_id)'>".number_format($procedure_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Surgery\",$finance_department_id)'>".number_format($surgery_subtotal)."</td>
                <td class='rows_list' style='text-align: right;' onclick='get_selected_amount_details(\"Direct cash\",$finance_department_id)'>".number_format($deposit_subtotal)."</td>
                <td style='text-align: right;'><b>".number_format($pharmacy_subtotal+$laboratory_subtotal+$radiology_subtotal+$Consultation_subtotal+$procedure_subtotal+$surgery_subtotal+$deposit_subtotal)."</b></td>
             </tr>";
       $count++;
       $verticatl_grand_total+=($pharmacy_subtotal+$laboratory_subtotal+$radiology_subtotal+$Consultation_subtotal+$procedure_subtotal+$surgery_subtotal+$deposit_subtotal);
    }
    ?>
   <tr>
    <td colspan="2"><b>GRAND TOTAL</b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($consultation_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($pharmacy_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($laboratory_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($radiology_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($procedure_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($surgery_grand_total) ?></b></td>
    <td class='rows_list' style='text-align: right;'><b><?= number_format($deposit_grand_total) ?></b></td>
    <td style='text-align: right;'><b><?= number_format($verticatl_grand_total) ?></b></td>
</tr>

<?php

}else{
   echo "<tr class='rows_list' style='text-align: right;'>
   <td colspan='11' style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;'>NO TRANSACTION DONE IN A GIVEN PERIOD</td>
</tr>";
}
            ?>
