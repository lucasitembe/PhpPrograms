<?php
    @session_start();
    include("./includes/connection.php"); 
   
   
    $total = 0;
    $temp = 1;
    $data='';
    $sqlq='';
    $totalItem='';
    $totalDone='';
    $dataAmount='';
    
    
    
    //generate sql
    
        // $Transaction_Status_Title="PATIENT PROCESSED"; 
        // $Registration_ID='255';
        // $billing_type="Outpatient Credit";
        // $Transaction_Type='Credit';
        // $Payment_Cache_ID='38';
        // $Sub_Department_ID='48';
        // $typeconsultant="DOCTOR_CONSULT";
    
   // echo $Transaction_Status_Title;
    //If paid
    if(isset($Transaction_Status_Title) && !empty($Transaction_Status_Title) && $Transaction_Status_Title=="Saved"){
        echo '<center><table width =100% border=0>';
      echo '<tr id="thead"><td style="text-align: center;"><b>SN</b></td>
                <td><b>ITEM NAME</b></td>
                    <td style="text-align:left;" width=8%><b>PROGRESS</b></td>
                      
                                <td style="text-align: left  ;" ><b>REMARKS</b></td>
                                <td style="text-align: left  ;" width=8%><b>TEMPLATE</b></td>   
                                 </tr>';
      
       if($typeconsultant=='OTHERS_CONSULT'){
           $sqlq="
		select ilc.status, its.Product_Name,ilc.Patient_Payment_Item_List_ID, ilc.Patient_Payment_ID,ilc.remarks
		from tbl_patient_payment_item_list ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                   ilc.status IN ('served','Pending','Not Applicable') and
			ilc.Patient_Payment_ID = '$Payment_Cache_ID' $doctorproce and
                                     ilc.Check_In_Type = 'Procedure'";
       }else{
           $sqlq="
		select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                   ilc.status IN ('served','Pending','Not Applicable')  and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'";
       }
       
      $totalItem=  mysqli_num_rows(mysqli_query($conn,"select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                         ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'")); 
      
      $totalDone=  mysqli_num_rows(mysqli_query($conn,"select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                     ilc.status IN ('served')  and
                         ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'"));
      //echo $totalItem.' = '.$totalDone;exit;
      
    $select_Transaction_Items_Active = mysqli_query($conn,$sqlq) or die(mysqli_error($conn)); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status=$row['status'];
                $payment_status='';
				//die($status);
			    if($status=='served'){
				  $status='Done';
				}
                
                if($typeconsultant=="OTHERS_CONSULT"){
                   $payment_status='Paid'; 
                }else{
                if($billing_type=='Outpatient Cash' && $status=="active"){
                    $payment_status="Not Paid";
                }elseif($billing_type=='Outpatient Cash' && $status=="paid"){
                    $payment_status="Paid";
                }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="paid"){
                    $payment_status="Paid";
                }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="active"){
                    $payment_status="Bill";
                }elseif($billing_type=='Inpatient Cash' || $billing_type=='Inpatient Credit'){
                    $payment_status="Bill";
                }
                }
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";

                //datainfo
                   if($typeconsultant=="OTHERS_CONSULT"){
                     echo "<td><input type='hidden' value='".$row['Patient_Payment_Item_List_ID']."' name='paymentItermCache[]'><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
                     echo '<td style="text-align:right;">
					        <select name="status_'.$row["Patient_Payment_Item_List_ID"].'">
					         <option>Select progress</option>
							 <option>Done</option>
							 <option>Pending</option>
							 <option>Not Applicable</option>
						   </select>
						   </td>
					       ';        
					
                     echo "<td width=''><textarea type='text' name='remarks_".$row["Patient_Payment_Item_List_ID"]."' id='".$row["Patient_Payment_Item_List_ID"]." style='' cols='8' rows='1'>".($row['remarks'])."</textarea></td>";	
                     echo '<td>&nbsp;</td>';
                     echo '<td><button type="button" class="removeItemFromCache art-button" onclick="template('.$row["Patient_Payment_Item_List_ID"].')">Template</button></td>';
              
                }else{
                     echo "<td><input type='hidden' value='".$row['Payment_Item_Cache_List_ID']."' name='paymentItermCache[]'><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
                     
                     if($status=='Done'){
                     
                        echo '<td><select name="status_'.$row["Payment_Item_Cache_List_ID"].'" readonly="readonly">
			              <option selected="selected">Done</option>
				  </select>
					       ';        
					 echo    '</td>';
                                         
                        echo "<td width='30%'><textarea type='text' readonly='readonly' name='remarks_".$row["Payment_Item_Cache_List_ID"]."' id='".$row["Payment_Item_Cache_List_ID"]." style='' cols='8' rows='1'>".($row['remarks'])."</textarea></td>";	
                        echo '<td><button type="button" class="removeItemFromCache art-button" onclick="templete('.$row["Payment_Item_Cache_List_ID"].')">Template</button></td>';
            
                     }else{
                          echo '<td><select name="status_'.$row["Payment_Item_Cache_List_ID"].'" >';
                                            if($status=='Done'){
                                              echo '<option selected="selected">Done</option>
                                                  <option>Pending</option>
                                                  <option>Not Applicable</option>';
                                            }elseif($status=='Pending'){
                                                echo '<option >Done</option>
                                                  <option selected="selected">Pending</option>
                                                  <option>Not Applicable</option>'; 
                                            }elseif($status=='Not Applicable'){
                                                echo '<option >Done</option>
                                                  <option>Pending</option>
                                                  <option selected="selected">Not Applicable</option>'; 
                                            }
					       
					 echo    '</td>';
                                         
                            echo "<td width='30%'><textarea type='text' name='remarks_".$row["Payment_Item_Cache_List_ID"]."' id='".$row["Payment_Item_Cache_List_ID"]." style='' cols='8' rows='1'>".($row['remarks'])."</textarea></td>";	
                            echo '<td><button type="button" class="removeItemFromCache art-button" onclick="templete('.$row["Payment_Item_Cache_List_ID"].')">Template</button></td>';
            
                     }                     
                    }
                //enddataInfo
               
                
              $temp++;
	        echo "</tr>";
            }

         } else {
           echo "<td colspan='5' style='text-align:center;font-size:18px;color:red;padding:5px;'>No Surgery Availlable</td>";	
         }
            
    ?></table></center>
<?php

    
    }else{
        
        // echo  '$sqlq';exit;
      echo '<center><table width =100% border=0>';
      echo '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Procedure Name</b></td>
                    <td style="text-align:left;" ><b>Progress</b></td>
                      
                                <td style="text-align: left  ;" ><b>Remarks</b></td>
                                
                                 <td style="text-align: left  ;"><b>Template</b></td>   
                                 </tr>';
      
       if($typeconsultant=='OTHERS_CONSULT'){
           $sqlq="
		select ilc.status, its.Product_Name,ilc.Patient_Payment_Item_List_ID, ilc.Patient_Payment_ID,ilc.remarks
		from tbl_patient_payment_item_list ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                   ilc.status IN ('active','paid') and
			ilc.Patient_Payment_ID = '$Payment_Cache_ID' $doctorproce and
                                     ilc.Check_In_Type = 'Procedure'";
       }else{
           $sqlq="
		select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                   ilc.status IN ('Pending') and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'";
       }
	   
      $totalItem=  mysqli_num_rows(mysqli_query($conn,"select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                         ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'")); 
      
      $totalDone=  mysqli_num_rows(mysqli_query($conn,"select ilc.status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks
		from tbl_item_list_cache ilc, tbl_Items its 
		    where ilc.item_id = its.item_id and
                     ilc.status IN ('served')  and
                         ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure'"));
      
    $select_Transaction_Items_Active = mysqli_query($conn,$sqlq) or die(mysqli_error($conn)); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status=strtolower($row['status']);
                $payment_status='';
                
                if($typeconsultant=="OTHERS_CONSULT"){
                   $payment_status='Paid'; 
                }else{
                if($billing_type=='Outpatient Cash' && $status=="active"){
                    $payment_status="Not Paid";
                }elseif($billing_type=='Outpatient Cash' && $status=="paid"){
                    $payment_status="Paid";
                }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="paid"){
                    $payment_status="Paid";
                }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="active"){
                    $payment_status="Bill";
                }elseif($billing_type=='Inpatient Cash' || $billing_type=='Inpatient Credit'){
                    $payment_status="Bill";
                }
                }
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		
                 if($typeconsultant=="OTHERS_CONSULT"){
                     echo "<td><input type='hidden' value='".$row['Patient_Payment_Item_List_ID']."' name='paymentItermCache[]'><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
                     echo '<td style="text-align:right;">
					        <select name="status_'.$row["Patient_Payment_Item_List_ID"].'">
					         <option>Select progress</option>
							 <option>Done</option>
							 <option>Pending</option>
							 <option>Not Applicable</option>
						   </select>
						   </td>
					       ';        
					
                     echo "<td width=''><textarea type='text' name='remarks_".$row["Patient_Payment_Item_List_ID"]."' id='".$row["Patient_Payment_Item_List_ID"]." style='' cols='8' rows='1'>".($row['remarks'])."</textarea></td>";	
                     echo '<td>&nbsp;</td>';
                     echo '<td><button type="button" class="removeItemFromCache art-button" onclick="template('.$row["Patient_Payment_Item_List_ID"].')">Template</button></td>';
              
                }else{
                     echo "<td><input type='hidden' value='".$row['Payment_Item_Cache_List_ID']."' name='paymentItermCache[]'><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
                      echo '<td><select name="status_'.$row["Payment_Item_Cache_List_ID"].'">
					         <option>Select progress</option>
							 <option>Done</option>
							 <option>Pending</option>
							 <option>Not Applicable</option>
						   </select>
					       ';        
					 echo    '</td>';
                     echo "<td width='30%'><textarea type='text' name='remarks_".$row["Payment_Item_Cache_List_ID"]."' id='".$row["Payment_Item_Cache_List_ID"]." style='' cols='8' rows='1'>".($row['remarks'])."</textarea></td>";	
                    
                     echo '<td><button type="button" class="removeItemFromCache art-button" onclick="templete('.$row["Payment_Item_Cache_List_ID"].')">Template</button></td>';
                }
               
                
              $temp++;
	        echo "</tr>";
            }

         }else {
           echo "<td colspan='5' style='text-align:center;font-size:18px;color:red;padding:5px;'>No Surgery Availlable</td>";	
         }
            
    ?></table></center>
<?php

    }
?>