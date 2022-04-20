<?php
    @session_start();
    include("./includes/connection.php"); 
   
   // echo  $_SESSION['Payment_Cache_ID'];
    //exit;
//    if(isset($_SESSION['Payment_Cache_ID'])){
//        $Payment_Cache_ID = $_SESSION['Payment_Cache_ID'];
//    }else{
//	$Payment_Cache_ID = '';
//    }
//    if(isset( $_SESSION['Transaction_Type'])){
//        $Transaction_Type =  $_SESSION['Transaction_Type'];
//    }else{
//	$Transaction_Type = '';
//    }
//    
//    if(isset( $_SESSION['Sub_Department_ID'])){
//	$Sub_Department_ID =  $_SESSION['Sub_Department_ID'];
//    }else{
//	$Sub_Department_ID = 0;
//    }
    $total = 0;
    $temp = 1;
    $data='';
    $dataAmount='';
    $zero_price_items_count=0;
    $zero_price_items='';
    
    //generate sql
    
    
   
    
   // echo $Transaction_Status_Title;
    //If paid
    if(isset($Transaction_Status_Title) && !empty($Transaction_Status_Title) && $Transaction_Status_Title=="PAID"){
         echo '<center><table width =100% border=0>';
         echo '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                     <td style="text-align:right;" width=8%><b>Discount</b></td>
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                    </tr>';
        $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'paid' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Pharmacy' AND Patient_Payment_ID='$Patient_Payment_ID'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly' size='100%'></td>";
		echo "<td style='text-align:right;'><input  type='text' readonly='readonly' value='".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price']))."' style='text-align:right;'></td>";
		echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		?>
	    <td style='text-align:right;'>
		<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		    <input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;'>
		<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		    <input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;'>
		<?php } ?>
	    </td>
	    <?php
		echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity))."' style='text-align:right;'></td>";	
		
	        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	    }   echo "</tr>";
	    
	  
		 $dataAmount= "<td colspan=7 style='text-align: right;'><b> TOTAL : ".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code'].'&nbsp;&nbsp;'."</b></td>";
	    
	   
	} 
        echo '</table></center>';
    }else{
      echo '<center><table width =100% border=0>';
      echo '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                      <td style="text-align:right;" width=8%><b>Discount</b></td>
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                <td style="text-align: left  ;" width=8%><b>&nbsp;</b></td>
                                    </tr>';
    $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'approved' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Pharmacy'"); 
    
  

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
//      echo $Payment_Cache_ID.' '.$Sub_Department_ID.' '.$Transaction_Type;
//    exit;
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                if($row['Price']==0){
                    $zero_price_items_count +=1;
                    if(empty( $zero_price_items)){
                         $zero_price_items=$row["Payment_Item_Cache_List_ID"].'achanisha';
                    }else{
                         $zero_price_items .=$row["Payment_Item_Cache_List_ID"].'achanisha';
                    }
                }
                
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price']))."' style='text-align:right;'></td>";
		echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		
                echo "<td style='text-align:right;'>";
                      if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; 
                         echo "<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";
                      }else{ $Quantity = $row['Edited_Quantity'];
                      echo "<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";}

     //		        
                 echo'</td>';
	    
		echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity))."' style='text-align:right;'></td>";	
		//echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	       if($no==1){
                   echo '<td>&nbsp;</td>';
               }else{
                    echo '<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button></td>';
               }
               
                
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	        echo "</tr>";
            }
	    //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
//	    $Check_Items = "select status from tbl_item_list_cache where payment_Cache_ID = '$Payment_Cache_ID' and status = 'removed'";
//	    $Check_Items_Results = mysqli_query($conn,$Check_Items);
            
            $select_Transaction_Items_Remove = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Pharmacy'"); 
            
	    $No_Of_Items = mysqli_num_rows($select_Transaction_Items_Remove);
	    if($No_Of_Items > 0){
		 $dataAmount= "<td style='text-align: right;'><b> TOTAL : ".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code'].'&nbsp;&nbsp;'."</b>
			<button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                       </td>";
	    }else{
		 $dataAmount= "<td style='text-align: right;'><b> TOTAL : ".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code'].'&nbsp;&nbsp;'."</b></td>";
	    }
         }
         
         if($zero_price_items_count >0){
             echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value="'.$zero_price_items.'"  />';
         }else{
             echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value=""  />';
         }   
    ?></table></center>
<?php

//  $select_Transaction_Items_Active_remove = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache ilc, tbl_Items its
//		    where ilc.item_id = its.item_id and
//                    ilc.Status = 'remove' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Pharmacy'"); 
//  
//  $select_Transaction_Items_Active_if_paid = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache ilc, tbl_Items its
//		    where ilc.item_id = its.item_id and
//                    ilc.Status = 'paid' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Pharmacy'"); 
//  
// $no_check_if = mysqli_num_rows($select_Transaction_Items_Active_if_paid);
//
//    $no2 = mysqli_num_rows($select_Transaction_Items_Active_remove);
//    
//    $row=mysqli_fetch_assoc($select_Transaction_Items_Active_remove);
//    if($row['Amount_Removed']!=0 && $no_check_if==0){
//        echo '<table align="center" width="100%">
//                  <tr>
//                    <td style="float:right;padding-right:10px;"><button type="button" class="removeItemFromCache art-button" onclick="vieweRemovedItem()">View Removed Items</button></td><td style="float:right;margin-top:0.28%;padding:7px;">&nbsp;</td>
//                  </tr>  
//                </table>';
//    }
    }
?>
 <input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>