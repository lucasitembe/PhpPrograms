<?php
include("./includes/connection.php");
include("./includes/header.php");
$Payment_Cache_ID=0;
$Registration_ID=0;
if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
?>
<a href="patient_sent_to_cashier_payment.php?Payment_Cache_ID=<?= $Payment_Cache_ID ?>&Registration_ID=<?= $Registration_ID ?>" class="art-button-green">BACK</a>
<div id="patient_bill_dialog"></div>
<fieldset style="height:100px;overflow-y: hidden;overflow-x: hidden">
    <legend align='center'><b>PATIENT PAYMENT</b></legend>
    <?php 
 if(isset($_GET['Payment_Cache_ID'])&&isset($_GET['Registration_ID'])){
  $Registration_ID=$_GET['Registration_ID'];
  $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
  $filter="AND pr.Registration_ID='$Registration_ID'";
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT pr.Registration_ID,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID,pc.Payment_Date_And_Time,pc.Payment_Cache_ID FROM tbl_patient_registration pr,tbl_payment_cache pc WHERE pr.Registration_ID=pc.Registration_ID AND Billing_Type IN('Inpatient Cash','Outpatient Cash') $filter ORDER BY Payment_Date_And_Time ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
         $Gender=$patient_list_rows['Gender'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $Payment_Date_And_Time=$patient_list_rows['Payment_Date_And_Time'];
         $Payment_Cache_ID=$patient_list_rows['Payment_Cache_ID'];
        
                
         //filter only patient with active or approved item
         $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Status IN('active','approved')") or die(mysqli_error($conn));
         if(mysqli_num_rows($sql_select_active_or_approved_item_result)<=0){
             
         } 
                $date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
        //sql select payment sponsor
        $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
      }
  }
}

    
    ?>
    <table class="table" style="background: #FFFFFF">
        <tr>
            <td width="8%">
                <b>Patient Name</b>
            </td>
            <td>
                <?= $Patient_Name ?>
            </td>
            <td width="8%"> <b>Patient Reg#</b></td>
            <td>
                <?= $Registration_ID ?>
            </td>
            <td width="5%"> <b>Age</b></td>
            <td><?= $age ?></td>
            <td width="5%"> <b>Gender</b></td>
            <td>
                <?= $Gender ?>
            </td>
            <td width="7%"> <b>Sponsor</b></td>
            <td>
                <?= $Guarantor_Name ?>
            </td>
            <td width="7%"> <b>Sent Date</b></td>
            <td>
                <?= $Payment_Date_And_Time ?>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style="height:400px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF">
    <?php 
        $sql_select_payment_transaction_result=mysqli_query($conn,"SELECT card_and_mobile_payment_transaction_id FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND card_and_mobile_payment_status='pending' GROUP BY card_and_mobile_payment_transaction_id") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_payment_transaction_result)>0){
            while($transaction_rows=mysqli_fetch_assoc($sql_select_payment_transaction_result)){
                $card_and_mobile_payment_transaction_id=$transaction_rows['card_and_mobile_payment_transaction_id'];
        
    ?>
    <div class="box box-primary">
    <table class="table table-bordered table-hover">
        <tr>
            <th width='50px'>S/No</th>
            <th>Item Name</th>
            <th style='text-align:right'>Item Price</th>
            <th style='text-align:right'>Item Discount</th>
            <th style='text-align:right'>Quantity</th>
            <th style='text-align:right'>Subtotal</th>
        </tr>
        <tbody id='patient_sent_to_cashier_item_tbl'>
            <?php 
                //select all patient items sent to cashier
                $grand_total=0;
                $grand_total_discount=0;
                $grand_total_price=0;
                $grand_total_quantity=0;
                $sql_select_patient_items_result=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID,Product_Name,Price,Quantity,Edited_Quantity,Discount FROM tbl_item_list_cache ilc,tbl_items i WHERE ilc.Item_ID=i.Item_ID AND ilc.Payment_Cache_ID='$Payment_Cache_ID' AND (ilc.Check_In_Type='Pharmacy' AND ilc.Status='approved' OR (ilc.Check_In_Type<>'Pharmacy' AND (ilc.Status IN('active','approved')))) AND card_and_mobile_payment_status='pending' AND card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_patient_items_result)>0){
                   $count_sn=1;
                   while($item_list_rows=mysqli_fetch_assoc($sql_select_patient_items_result)){
                      $Product_Name=$item_list_rows['Product_Name'];
                      $Price=$item_list_rows['Price'];
                      $Quantity=$item_list_rows['Quantity'];
                      $Edited_Quantity=$item_list_rows['Edited_Quantity'];
                      $Discount=$item_list_rows['Discount'];
                      $Payment_Item_Cache_List_ID=$item_list_rows['Payment_Item_Cache_List_ID'];
                      if($Edited_Quantity>0){
                          $item_quantity=$Edited_Quantity;
                      }else{
                          $item_quantity=$Quantity;
                      }
                      $grand_total_discount+=$Discount;
                      $grand_total_price+=$Price;
                      $grand_total_quantity+=$item_quantity;
                      echo "<tr>
                                
                                <td>$count_sn.</td>
                                <td>$Product_Name</td>
                                <td style='text-align:right'>".number_format($Price)."</td>
                                <td style='text-align:right'>$Discount</td>
                                <td style='text-align:right'>$item_quantity</td>
                                <td style='text-align:right'>".number_format($item_quantity*($Price-$Discount))."</td>
                           </tr>";
                      $count_sn++;
                    $grand_total+=($item_quantity*($Price-$Discount));
                   }
               } 
            ?>
            <tr>
                <td colspan="2"><b>GRAND TOTAL</b></td><td style='text-align:right'><b><?= number_format($grand_total_price) ?></b></td><td style='text-align:right'><b><?= number_format($grand_total_discount) ?></b></td><td style='text-align:right'><b><?= $grand_total_quantity ?></b></td><td style='text-align:right'><b><?= number_format($grand_total) ?></b></td>
            </tr>
        </tbody>
    </table>
        <input type="button" value="Make ePayment" class="art-button-green pull-right"/><br/>
    </div>
    <?php 
            }
        }
    ?>
</fieldset>
<div id="card_mobile_payment_feedback" class="hide"></div>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
    include("./includes/footer.php");
?>
