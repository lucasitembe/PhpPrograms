<?php
 include("./includes/connection.php");
if(isset($_POST['selected_Payment_Item_Cache_List_ID'])){
    $selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
   $selected_item=join("','",$selected_Payment_Item_Cache_List_ID);
    
    ?>
<div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
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
                $sql_select_patient_items_result=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID,Product_Name,Price,Quantity,Edited_Quantity,Discount FROM tbl_item_list_cache ilc,tbl_items i WHERE ilc.Item_ID=i.Item_ID AND Payment_Item_Cache_List_ID IN ('$selected_item') AND (ilc.Check_In_Type='Pharmacy' AND ilc.Status='approved' OR (ilc.Check_In_Type<>'Pharmacy' AND (ilc.Status IN('active','approved'))))") or die(mysqli_error($conn));
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
                                
                                <td>$count_sn.<input type='text' value='$Payment_Item_Cache_List_ID' class='Payment_Item_Cache_List_ID_selected hide' /></td>
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
    </div>
<div class="row">
    
    <div class="col-md-2" id="progress_dialog" style="display:none">
        <img src="images/ajax-loader_1.gif" width="" style="border-color:white ">
    </div>
    <div class="col-md-2">
        <select class="pull-right" style="width: 100%">
            <option>Select Payment Mode</option>
            <option>Mobile Payment</option>
            <option>Card Payment</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="number" placeholder="Enter Phone Number" id="patient_phone_number"/>
    </div>
    <div class="col-md-3">
        <input type="button" class="art-button-green pull-right" onclick="request_control_number('<?= $grand_total ?>')" value="Request Control Number" />
    </div>
    <div class="col-md-2">
        <input type="button" class="art-button-green pull-right" onclick="make_epayment('<?= $grand_total ?>')" value="Make ePayment" />
    </div>
</div>
<?php }

