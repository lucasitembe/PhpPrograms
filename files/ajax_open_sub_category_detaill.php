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
if(isset($_POST['finance_department_id'])){
   $finance_department_id=$_POST['finance_department_id']; 
}else{
   $finance_department_id=""; 
}
if(isset($_POST['finance_department_name'])){
   $finance_department_name=$_POST['finance_department_name']; 
}else{
   $finance_department_name=""; 
}
if(isset($_POST['Item_Subcategory_ID'])){
   $Item_Subcategory_ID=$_POST['Item_Subcategory_ID']; 
}else{
   $Item_Subcategory_ID=""; 
}
if(isset($_POST['Item_Subcategory_Name'])){
   $Item_Subcategory_Name=$_POST['Item_Subcategory_Name']; 
}else{
   $Item_Subcategory_Name=""; 
}
if(isset($_POST['Department_Name'])){
   $Department_Name=$_POST['Department_Name']; 
}else{
   $Department_Name=""; 
}
if(isset($_POST['Sub_Department_Name'])){
   $Sub_Department_Name=$_POST['Sub_Department_Name']; 
}else{
   $Sub_Department_Name=""; 
}
if(isset($_POST['Item_Category_Name'])){
   $Item_Category_Name=$_POST['Item_Category_Name']; 
}else{
   $Item_Category_Name=""; 
}
if(isset($_POST['Department_ID'])){
   $Department_ID=$_POST['Department_ID']; 
}else{
   $Department_ID=""; 
}
if(isset($_POST['Sub_Department_ID'])){
   $Sub_Department_ID=$_POST['Sub_Department_ID']; 
}else{
   $Sub_Department_ID=""; 
}
?>
<div class="row">
    <div class="col-md-12">
        <div id="selected_item_progess_div"></div>
        <div class="box box-primary">
            <table class="table">
                <tr>
                    <th width='50px'>S/NO.</th>
                    <th width="30%">ITEM NAME</th>
                    <td style="width:15%">
                        NO OF SERVICE 
                     </td>
                     <td style="width:15%">
                        NO OF PATIENT 
                     </td>
                    <th>CASH</th>
                    <th>CREDIT</th>
                    <th>MSAMAHA</th>
                    <th>TOTAL</th>
                </tr>
                <tbody>
                    <?php 
                        $grand_total_cash=0;
                        $grand_total_credit=0;
                        $grand_total_msamaha=0;
                        $count_sn=1;
                        $sql_select_all_dispensing_unit_result=mysqli_query($conn,"SELECT Product_Name,ppl.Item_ID FROM tbl_items it,tbl_patient_payment_item_list ppl WHERE it.Item_ID=ppl.Item_ID AND it.Item_Subcategory_ID='$Item_Subcategory_ID' AND ppl.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND ppl.Sub_Department_ID='$Sub_Department_ID'GROUP BY ppl.Item_ID") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_all_dispensing_unit_result)>0){
                            while($unit_rows=mysqli_fetch_assoc($sql_select_all_dispensing_unit_result)){
                               $Product_Name=$unit_rows['Product_Name'];
                               $Item_ID=$unit_rows['Item_ID'];
                               
                                $ubtotal_cash=0;
                                $ubtotal_credit=0;
                                $subtotal_msamaha=0;
                                $number_of_service=0;
                            ////calculate amount from each unit
                            //select all items
                                   $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND ppl.Item_ID='$Item_ID' AND ppl.Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){

                                       while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                                          $payment_type=$items_rows['payment_type'];
                                          $Billing_Type=$items_rows['Billing_Type'];
                                          $Pre_Paid=$items_rows['Pre_Paid'];
                                          $Sponsor_ID=$items_rows['Sponsor_ID'];
                                          $Discount=$items_rows['Discount'];
                                          $Price=$items_rows['Price'];
                                          $Quantity=$items_rows['Quantity'];

                                           
                                           $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                                           $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
                        ///////////////////////////////////////////////////////////////


                                            if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
                                                    $subtotal_msamaha += ($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                            }  else {

                                                if(((strtolower($Billing_Type) == 'outpatient cash'|| strtolower($Billing_Type)=="patient from outside") && $Pre_Paid == '0') or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                    $ubtotal_cash+=($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                                }elseif((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit')){
                                                    $ubtotal_credit += ($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                                }
                                            }
                                       ///////////////////////////////////////////////////////////////
                                       }
                                    }
                                    $grand_total_cash+=$ubtotal_cash;
                                    $grand_total_credit+=$ubtotal_credit;
                                    $grand_total_msamaha+=$subtotal_msamaha;
                                    
                                    $number_of_patient=0;
                                    $sql_select_number_of_patient_result=mysqli_query($conn,"SELECT Registration_ID AS number_of_patient FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND Sub_Department_ID='$Sub_Department_ID' AND ppl.Item_ID='$Item_ID' GROUP BY Registration_ID") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_number_of_patient_result)>0){
                                        $number_of_patient=mysqli_num_rows($sql_select_number_of_patient_result);
                                    }
                               echo "<tr>
                                        <td>$count_sn.</td>
                                        <td class='department_row' onclick='open_item_detail($Item_ID,$Item_Subcategory_ID,$Department_ID,$finance_department_id,$Sub_Department_ID,\"$finance_department_name\",\"$Department_Name\",\"$Sub_Department_Name\",\"$Item_Subcategory_Name\",\"$Product_Name\",\"$Item_Category_Name\")' ><b>".strtoupper($Product_Name)."</b></td>
                                        <td>".number_format($number_of_service)."</td>
                                        <td>".number_format($number_of_patient)."</td>
                                        <td>".number_format($ubtotal_cash)."</td>
                                        <td>".number_format($ubtotal_credit)."</td>
                                        <td>".number_format($subtotal_msamaha)."</td>
                                        <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                                    </tr>";
                               $count_sn++;
                            }
                        }
                     ?>
                </tbody>
                <tr>
                    <td colspan="4"><b>GRAND TOTAL</b></td>
                    <td><b><?= number_format($grand_total_cash) ?></b></td>
                    <td><b><?= number_format($grand_total_credit) ?></b></td>
                    <td><b><?= number_format($grand_total_msamaha) ?></b></td>
                    <td><b><?= number_format($grand_total_cash+$grand_total_credit) ?></b></td>
                </tr>
            </table>
        </div>
    </div>
</div>
