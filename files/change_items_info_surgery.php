<?php
 @session_start();
    include("./includes/connection.php"); 
    $data='';
    
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
    
    if(isset( $_SESSION['Sub_Department_ID'])){
	$Sub_Department_ID =  $_SESSION['Sub_Department_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
	
	$dataToEncode=array();
	
	
    
  if(isset($_POST['Payment_Item_Cache_List_ID'])){
        $id=$_POST['Payment_Item_Cache_List_ID'];
      
      $rs= mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
//        echo mysqli_num_rows($rs);
//        exit;
        if(mysqli_num_rows($rs)>1){
        $sql="UPDATE tbl_item_list_cache SET status='removed' WHERE Payment_Item_Cache_List_ID='".$id."'";
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
       } 
    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    $data.= '<center><table width =100% border=0>';
    $data.= '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                       
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                <td style="text-align: left  ;" width=8%><b>&nbsp;</b></td>
                                    </tr>';
    
     
    $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                
		$data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		$data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		$data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		//echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		
                $data.= "<td style='text-align:right;'>";
                      if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; 
                         $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";
                      }else{ $Quantity = $row['Edited_Quantity'];
                      $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";}

     //		        
                 $data.='</td>';
	    
		$data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		//echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	       if($no==1){
                   $data.='<td>&nbsp;</td>';
               }else{
                    $data.='<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button></td>';
               }
               
                
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	        $data.= "</tr>";
            }
	    //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	   $select_Transaction_Items_Remove = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
            
	    $No_Of_Items = mysqli_num_rows($select_Transaction_Items_Remove);
	    if($No_Of_Items > 0){
		 $dataAmountInfo= "<td  style='text-align: right;'><b> TOTAL : ".number_format($total)."</b><style='text-align: right;'>
			<button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                       </td>";
		 $dataToEncode['total_amount']=$dataAmountInfo;
	    }else{
		 $dataAmountInfo= "<td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
		 
		 $dataToEncode['total_amount']=$dataAmountInfo;
	    }
         }
            
        $data.= '</table></center>';
		
		$dataToEncode['data']=$data;
//        $select_Transaction_Items_Active_remove = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache ilc, tbl_Items its
//		    where ilc.item_id = its.item_id and
//                    ilc.status = 'removed' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Surgery'"); 
//
//    $no2 = mysqli_num_rows($select_Transaction_Items_Active_remove);
//    $row=mysqli_fetch_assoc($select_Transaction_Items_Active_remove);
//     if($row['Amount_Removed']!=0){
//        $data.= '<table align="center" width="100%">
//                  <tr>
//                    <td style="float:right"><button type="button" class="removeItemFromCache art-button" onclick="vieweRemovedItem()">View Removed Items</button></td><td style="float:right">&nbsp;</td>
//                  </tr>  
//                </table>';
//    }
        echo json_encode($dataToEncode);
  }

  if(isset($_POST['show_all_items'])){
     
    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    $data.= '<center><table width =100% border=0>';
    $data.= '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                       
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                <td style="text-align: left  ;" width=8%><b>&nbsp;</b></td>
                                    </tr>';
    
     
    $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                
		$data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		$data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		$data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		//echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		
                $data.= "<td style='text-align:right;'>";
                      if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; 
                         $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";
                      }else{ $Quantity = $row['Edited_Quantity'];
                      $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";}

     //		        
                 $data.='</td>';
	    
		$data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		//echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	       if($no==1){
                   $data.='<td>&nbsp;</td>';
               }else{
                    $data.='<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button></td>';
               }
               
                
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	        $data.= "</tr>";
            }
	    //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	    $select_Transaction_Items_Remove = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
            
	    $No_Of_Items = mysqli_num_rows($select_Transaction_Items_Remove);
	    if($No_Of_Items > 0){
		 $dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
                                    <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>
                                   </td>                    
                                  ";
                 $dataToEncode['total_amount']=$dataAmountInfo;
	    }else{
		 $dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
	    
                 $dataToEncode['total_amount']=$dataAmountInfo;
            }
         }
            
        $data.= '</table></center>';
        $dataToEncode['data']=$data;
//        $select_Transaction_Items_Active_remove = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache ilc, tbl_Items its
//		    where ilc.item_id = its.item_id and
//                    ilc.status = 'removed' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Surgery'"); 
//
//    $no2 = mysqli_num_rows($select_Transaction_Items_Active_remove);
//    $row=mysqli_fetch_assoc($select_Transaction_Items_Active_remove);
//     if($row['Amount_Removed']!=0){
//        $data.= '<table align="center" width="100%">
//                  <tr>
//                    <td style="float:right"><button type="button" class="removeItemFromCache art-button" onclick="vieweRemovedItem()">View Removed Items</button></td><td style="float:right">&nbsp;</td>
//                  </tr>  
//                </table>';
//    }
        echo json_encode($dataToEncode);
  }
  
 if(isset($_POST['viewRemovedItem'])){
    
     $rs= mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
////        echo mysqli_num_rows($rs);
////        exit;
//        if(mysqli_num_rows($rs)>1){
//        $sql="UPDATE tbl_item_list_cache SET status='remove' WHERE Payment_Item_Cache_List_ID='".$id."'";
//        mysqli_query($conn,$sql) or die(mysqli_error($conn));
//       } 
    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    $data.= '<center><table width =100% border=0>';
    $data.= '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                       
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                <td style="text-align: left  ;" width=8%><b>&nbsp;</b></td>
                                    </tr>';
    
     
    $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                
		$data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		$data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		$data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		//echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		
                $data.= "<td style='text-align:right;'>";
                      if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; 
                         $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";
                      }else{ $Quantity = $row['Edited_Quantity'];
                      $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";}

     //		        
                 $data.='</td>';
	    
		$data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		//echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	       
                    $data.='<td><button type="button" class="readItemTOCache art-button" onclick="addItem('.$row["Payment_Item_Cache_List_ID"].')">Re add</button></td>';
              
                    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	        $data.= "</tr>";
            }
	    //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
//	    $Check_Items = "select status from tbl_item_list_cache where payment_Cache_ID = '$Payment_Cache_ID' and status = 'removed'";
//	    $Check_Items_Results = mysqli_query($conn,$Check_Items);
//	    $No_Of_Items = mysqli_num_rows($Check_Items_Results);
            $select_Transaction_Items_Remove = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
            
	    $No_Of_Items = mysqli_num_rows($select_Transaction_Items_Remove);
	    if($No_Of_Items > 0){
//		 $dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
//			 <button type='button' class='removeItemFromCache art-button' onclick='showItem()'>Back</button>   
//			      </td>";
                 $dataAmountInfo= "<td  style='text-align: right;'><b> TOTAL : ".number_format($total)."</b><style='text-align: right;'>
			<button type='button' class='removeItemFromCache art-button' onclick='showItem()'>Back</button>   
                       </td>";
                 $dataToEncode['total_amount']=$dataAmountInfo;
	    }else{
		 $dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
                 $dataToEncode['total_amount']=$dataAmountInfo;
	    }
         }
            
        $data.= '</table></center>';
        $dataToEncode['data']=$data;
//        $select_Transaction_Items_Active_remove = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache ilc, tbl_Items its
//		    where ilc.item_id = its.item_id and
//                    ilc.status = 'removed' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Surgery'"); 
//
//    $no2 = mysqli_num_rows($select_Transaction_Items_Active_remove);
//    $row=mysqli_fetch_assoc($select_Transaction_Items_Active_remove);
//     if($row['Amount_Removed']!=0){
//        $data.= '<table align="center" width="100%">
//                  <tr>
//                    <td style="float:right"><button type="button" class="removeItemFromCache art-button" onclick="vieweRemovedItem()">View Removed Items</button></td><td style="float:right">&nbsp;</td>
//                  </tr>  
//                </table>';
//    }
       echo json_encode($dataToEncode);
     }
     
     if(isset($_POST['readdItem'])){
         $id=$_POST['readdItem'];
      
      $rs= mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
//
        $sql="UPDATE tbl_item_list_cache SET status='active' WHERE Payment_Item_Cache_List_ID='".$id."'";
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
      
    $total = 0;
    $temp = 1;
    
    //generate sql
    
    
    $data.= '<center><table width =100% border=0>';
    $data.= '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                       
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                <td style="text-align: left  ;" width=8%><b>&nbsp;</b></td>
                                    </tr>';
    
     
    $select_Transaction_Items_Active = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'active' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
                
		$data.= "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		$data.= "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		$data.= "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		//echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		
                $data.= "<td style='text-align:right;'>";
                      if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; 
                         $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";
                      }else{ $Quantity = $row['Edited_Quantity'];
                      $data.="<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";}

     //		        
                 $data.='</td>';
	    
		$data.= "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		//echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	       if($no==1){
                   $data.='<td>&nbsp;</td>';
               }else{
                    $data.='<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button></td>';
               }
               
                
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	        $data.= "</tr>";
            }
	    //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	   $select_Transaction_Items_Remove = mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Surgery'"); 
            
	    $No_Of_Items = mysqli_num_rows($select_Transaction_Items_Remove);
	    if($No_Of_Items > 0){
		$dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
			  <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>
                        </td>";
                 $dataToEncode['total_amount']=$dataAmountInfo;
	    }else{
		$dataAmountInfo= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
	    
                $dataToEncode['total_amount']=$dataAmountInfo;
            }
         }
            
        $data.= '</table></center>';
         $dataToEncode['data']=$data;
//        $select_Transaction_Items_Active_remove = mysqli_query($conn,"
//		select ilc.status, ilc.Price,SUM(ilc.Price) as Amount_Removed, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
//		from tbl_item_list_cache AS ilc LEFT JOIN tbl_Items  AS its ON ilc.item_id = its.item_id
//		    where 
//                    ilc.status = 'removed' and
//			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
//			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
//                                ilc.Transaction_Type = '$Transaction_Type' and
//                                        ilc.Check_In_Type = 'Surgery'"); 
//
//    $no3 = mysqli_num_rows($select_Transaction_Items_Active_remove);
//    $row=mysqli_fetch_assoc($select_Transaction_Items_Active_remove);
//    
//     if($row['Amount_Removed']!=0){
//        $data.= '<table align="center" width="100%">
//                  <tr>
//                    <td style="float:right"><button type="button" class="removeItemFromCache art-button" onclick="vieweRemovedItem()">View Removed Items</button></td><td style="float:right">&nbsp;</td>
//                  </tr>  
//                </table>';
//    }
        echo json_encode($dataToEncode);
     }
     
     
    ?>
  

