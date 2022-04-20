<?php
    include("./includes/connection.php");
     if(isset($_POST['Payment_Cache_ID'])&&isset($_POST['Check_In_Type'])){
         $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
         $Check_In_Type=$_POST['Check_In_Type'];
         $filter_department="";
         if($Check_In_Type!="All"){
             $filter_department="AND Check_In_Type='$Check_In_Type'";
         }
              //select all patient items sent to cashier
                $grand_total=0;
                $grand_total_discount=0;
                $grand_total_price=0;
                $grand_total_quantity=0;
                $sql_select_patient_items_result=mysqli_query($conn, "SELECT card_and_mobile_payment_transaction_id,card_and_mobile_payment_status,Payment_Item_Cache_List_ID,Product_Name,Price,Quantity,Edited_Quantity,Discount FROM tbl_item_list_cache ilc,tbl_items i WHERE ilc.Item_ID=i.Item_ID AND ilc.Payment_Cache_ID='$Payment_Cache_ID' AND (ilc.Check_In_Type='Pharmacy' AND ilc.Status='approved' OR (ilc.Check_In_Type<>'Pharmacy' AND (ilc.Status IN('active','approved')))) AND (card_and_mobile_payment_status='unprocessed' OR card_and_mobile_payment_status='pending') $filter_department") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_patient_items_result)>0){
                   $count_sn=1;
                   while($item_list_rows=mysqli_fetch_assoc($sql_select_patient_items_result)){
                      $Product_Name=$item_list_rows['Product_Name'];
                      $Price=$item_list_rows['Price'];
                      $Quantity=$item_list_rows['Quantity'];
                      $Edited_Quantity=$item_list_rows['Edited_Quantity'];
                      $Discount=$item_list_rows['Discount'];
                      $Payment_Item_Cache_List_ID=$item_list_rows['Payment_Item_Cache_List_ID'];
                      $card_and_mobile_payment_status=$item_list_rows['card_and_mobile_payment_status'];
                      $card_and_mobile_payment_transaction_id=$item_list_rows['card_and_mobile_payment_transaction_id'];
                      $change_color_style="";
                      if($card_and_mobile_payment_status=="pending"){
                          $change_color_style="style='color:#FFFFFF;background:green;font-weight:bold'";
                      }
                      if($Edited_Quantity>0){
                          $item_quantity=$Edited_Quantity;
                      }else{
                          $item_quantity=$Quantity;
                      }
                      $grand_total_discount+=$Discount;
                      $grand_total_price+=$Price;
                      $grand_total_quantity+=$item_quantity;
                      $total_price=$item_quantity*($Price-$Discount);
                      echo "<tr>
                                
                                <td>$count_sn.</td>
                                <td $change_color_style>$Product_Name</td>
                                <td style='text-align:right'>".number_format($Price)."</td>
                                <td style='text-align:right'>$Discount</td>
                                <td style='text-align:right'>$item_quantity</td>
                                <td style='text-align:right'>".number_format($item_quantity*($Price-$Discount))."<input type='text' value='$total_price' id='total_price$Payment_Item_Cache_List_ID' class='hide'/></td>
                                <td class='select_item_column'>&nbsp;&nbsp;<input type='checkbox' value='$Payment_Item_Cache_List_ID' class='Payment_Item_Cache_List_ID' onclick='calculate_bill_amount()'/></td>
                                <td>$card_and_mobile_payment_transaction_id</td>";
                            if(!empty($card_and_mobile_payment_transaction_id)){
                               $New_amount= number_format($item_quantity*($Price-$Discount));
                                 echo   "<td><input type='button' value='Print Code' class='art-button-green' onclick='print_sangira_code(\"$card_and_mobile_payment_transaction_id\",\"$New_amount\")'></td>";
								 
                            }
                          echo "</tr>";
                      $count_sn++;
                    $grand_total+=($item_quantity*($Price-$Discount));
					

                   }
				   
               } 
            ?>
            <tr>
                <td colspan="2"><b>TOTAL</b></td><td style='text-align:right'><b><?= number_format($grand_total_price) ?></b></td><td style='text-align:right'><b><?= number_format($grand_total_discount) ?></b></td><td style='text-align:right'><b><?= $grand_total_quantity ?></b></td><td style='text-align:right'><b><?= number_format($grand_total) ?></b>
                
                </td>
               <td></td>
               <td colspan="2" style='text-align:right'>
			   
               <?php 
			   
			   $total = number_format($grand_total);
                 ?>
               </td>
            </tr>
    <?php }
?>
