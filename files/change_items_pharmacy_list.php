<?php
 @session_start();
    include("./includes/connection.php"); 
    $data='';
    $dataAmount='';
        $Payment_Cache_ID = '';
        $Registration_ID = '';
        $Transaction_Type = ''; 
    if(isset($_SESSION['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_SESSION['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }
    if(isset( $_SESSION['Transaction_Type'])){
        $Transaction_Type =  $_SESSION['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    
    if(isset( $_SESSION['Pharmacy_ID'])){
	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
	
	$dataToEncode=array();
	
	// echo '<pre>';
	// echo print_r($_SESSION);exit;
        
        
 $data.= '<center><table width =100% border=0>';
 $data.= '<tr id="thead"><td style="text-align: center;" width=5%><b>Sn</b></td>
                <td><b>Medication Name </b></td>
                    <td style="text-align: left;" width=15%><b>Dosage</b></td>
			<td style="text-align: right;" width=8%><b>Price</b></td>
			    <td style="text-align: right;" width=8%><b>Discount</b></td>
				<td style="text-align: center;" width=8%><b>Quantity</b></td>
				    <td style="text-align: center;" width=8%><b>Balance</b></td>
					<td style="text-align: center;" width=8%><b>Sub Total</b></td>
					    <td style="text-align: center;" width=6%><b>Action</b></td></tr>';	
    
  if(isset($_POST['Payment_Item_Cache_List_ID'])){
       
        $id=$_POST['Payment_Item_Cache_List_ID'];
     
	 $select_Transaction_Items_Active = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			
				ilc.status = 'active'"); 

        if(mysqli_num_rows($select_Transaction_Items_Active)>1){
         $Remove_Item = "UPDATE tbl_item_list_cache set status = 'removed' where Payment_Item_Cache_List_ID = '$id'";
       
        mysqli_query($conn,$Remove_Item) or die(mysqli_error($conn));
        
       }
 
    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    $select_Transaction_Items_Active2 = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_Items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			   
				ilc.status = 'active'"); 
    
    $no = mysqli_num_rows($select_Transaction_Items_Active2);
    
    //display all medications that not approved
    if($no > 0){
	while($row = mysqli_fetch_array($select_Transaction_Items_Active2)){
	    $data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    $data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    $data.= "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	   
	$data.="<td style='text-align:right;'>";
	   if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity']; 
			$data.="<input type='text' value=' $Quantity' onkeyup='pharmacyQuantityUpdate(". $row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	    }else{ $Quantity = $row['Edited_Quantity'];
			$data.="<input type='text' value='$Quantity' onkeyup='pharmacyQuantityUpdate(".$row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	     } 
	$data.= "</td>";
	//calculate balance-->
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"SELECT Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	
	 $data.="<td style='text-align: right;' id='Balance' name='Balance'>
	         <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='". $Item_Balance."'>
	        </td>";
	
	    $data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    if($no==1){
               $data.='<td>&nbsp;</td>'; 
            }else{
            
            $data.= '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
                    </td>';
            }
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
             $data.= "</tr>";
	}  
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	
	$Check_Items = "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type'  and
				ilc.status = 'removed'";
	$Check_Items_Results = mysqli_query($conn,$Check_Items);
	$No_Of_Items = mysqli_num_rows($Check_Items_Results);
	if($No_Of_Items > 0){
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                      
                         </td>";
						  $dataToEncode['total_amount']=$dataAmount;
	}else{
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
	}
			
	
        $data.= '</table></center>';
        
        $dataToEncode['data']=$data;

        echo json_encode($dataToEncode);
  }
  
}

  if(isset($_POST['show_all_items'])){
     
    $total = 0;
    $temp = 1;
    
     $select_Transaction_Items_Active = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_Items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
				ilc.Status = 'active'"); 

    
    
    
    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    $data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    $data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    $data.= "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	   
	$data.="<td style='text-align:right;'>";
	    if($row['Edited_Quantity'] == 0){  
            $Quantity = $row['Quantity']; 
			$data.="<input type='text' value=' $Quantity' onkeyup='pharmacyQuantityUpdate(".$row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	    }else{ $Quantity = $row['Edited_Quantity'];
			$data.="<input type='text' value='$Quantity' onkeyup='pharmacyQuantityUpdate(".$row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	    } 
		 
	$data.= "</td>";
	//calculate balance-->
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	
	 $data.="<td style='text-align: right;' id='Balance' name='Balance'>
	         <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='". $Item_Balance."'>
	        </td>";
	
	    $data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    if($no==1){
               $data.='<td>&nbsp;</td>'; 
            }else{
            
            $data.= '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
                    </td>';
            }
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
             $data.= "</tr>";
	}  
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	$Check_Items = "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type'  and
				ilc.status = 'removed'";
	$Check_Items_Results = mysqli_query($conn,$Check_Items);
	$No_Of_Items = mysqli_num_rows($Check_Items_Results);
	if($No_Of_Items > 0){
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                      
                         </td>";
						  $dataToEncode['total_amount']=$dataAmount;
	}else{
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
		$dataToEncode['total_amount']=$dataAmount;
	}
        $data.= '</table></center>';
        
        $dataToEncode['data']=$data;

        echo json_encode($dataToEncode);
  }
  }
  
 if(isset($_POST['viewRemovedItem'])){
  
     $select_Transaction_Items_Active = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
				ilc.status = 'removed'"); 

    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    
    
    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    $data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    $data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    $data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    $data.= "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	   
	$data.="<td style='text-align:right;'>";
	   if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity']; 
		$data.="<input type='text' value=' $Quantity' onkeyup='pharmacyQuantityUpdate(". $row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	    }else{ $Quantity = $row['Edited_Quantity'];
		$data.="<input type='text' value='$Quantity' onkeyup='pharmacyQuantityUpdate(".$row["Payment_Item_Cache_List_ID"].",this.value)' name = 'Quantity' id='Quantity' style='text-align: center;' size=13>";
	     } 
	$data.= "</td>";
	//calculate balance-->
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	
	     $data.="<td style='text-align: right;' id='Balance' name='Balance'>
	         <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='". $Item_Balance."'>
	        </td>";
	
	    $data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    $data.='<td><button type="button" class="readItemTOCache art-button" onclick="addItemPhar('.$row["Payment_Item_Cache_List_ID"].')">Re add items</button></td>';
              
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            
	    $temp++;
             $data.= "</tr>";
	}  
	
	
            $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='reloadPage()'>Back</button> 
                          </td>";
            
            $dataToEncode['total_amount']=$dataAmount;
	
        $data.= '</table></center>';
        
        $dataToEncode['data']=$data;

        echo json_encode($dataToEncode);
  }
}
     
     if(isset($_POST['readdItem'])){
        $id=$_POST['readdItem'];
      
        $sql="UPDATE tbl_item_list_cache SET Status='active' WHERE Payment_Item_Cache_List_ID='".$id."' ";
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
      
      echo 1;
     }
     
     
    ?>
  

