<?php
    @session_start();
    include("./includes/connection.php"); 
    $total = 0;
    $temp = 1;
    $data='';
    $dataAmount='';
    $zero_price_items_count=0;
    $zero_price_items='';

     
        $select_Transaction_Items_Active = mysqli_query($conn,"
                                        select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                        from tbl_item_list_cache ilc, tbl_Items its
                                        where ilc.item_id = its.item_id and
                                        (ilc.status = 'approved' or ilc.status = 'active') and
                                        ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                        ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Pharmacy'"); 
    
  

    $no = mysqli_num_rows($select_Transaction_Items_Active);    
	//display all medications
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
		
            if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity']; 
            }else{ 
                $Quantity = $row['Edited_Quantity'];
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
        }            
        $dataAmount= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>"; 
    }
         
         if($zero_price_items_count >0){
             echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value="'.$zero_price_items.'"  />';
         }else{
             echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value=""  />';
         }   
    ?>