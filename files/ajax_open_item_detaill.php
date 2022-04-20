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
if(isset($_POST['Item_ID'])){
   $Item_ID=$_POST['Item_ID']; 
}else{
   $Item_ID=""; 
}
?>
<div class="row">
    <div class="col-md-12">
        <div id="selected_item_progess_div"></div>
        <div class="box box-primary">
            <table class="table">
                <tr>
                    <td width='50px'>S/NO.</td>
                    <th width="30%">PATIENT NAME</th>
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
                        $sql_select_all_dispensing_unit_result=mysqli_query($conn,"SELECT Patient_Name,pr.Registration_ID FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID AND ppl.Item_ID='$Item_ID' AND pr.Registration_ID=pp.Registration_ID AND ppl.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND ppl.Sub_Department_ID='$Sub_Department_ID' GROUP BY pp.Registration_ID") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_all_dispensing_unit_result)>0){
                            while($unit_rows=mysqli_fetch_assoc($sql_select_all_dispensing_unit_result)){
                               $Patient_Name=$unit_rows['Patient_Name'];
                               $Registration_ID=$unit_rows['Registration_ID'];
                               
                                $ubtotal_cash=0;
                                $ubtotal_credit=0;
                                $subtotal_msamaha=0;
                            ////calculate amount from each unit
                            //select all items
                                   $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND pp.Registration_ID='$Registration_ID' AND ppl.Item_ID='$Item_ID' AND ppl.Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
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
                               echo "<tr>
                                        <td>$count_sn.</td>
                                        <td><b>".strtoupper($Patient_Name)."</b></td>
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
                    <td colspan="2"><b>GRAND TOTAL</b></td>
                    <td><b><?= number_format($grand_total_cash) ?></b></td>
                    <td><b><?= number_format($grand_total_credit) ?></b></td>
                    <td><b><?= number_format($grand_total_msamaha) ?></b></td>
                    <td><b><?= number_format($grand_total_cash+$grand_total_credit) ?></b></td>
                </tr>
            </table>
        </div>
    </div>
</div>
