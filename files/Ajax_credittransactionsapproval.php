<?php
	session_start();
	include("./includes/connection.php");
    $can_change_transaction_type = $_SESSION['userinfo']['change_bill_type_transaction_type_for_excempted'];

	if(isset($_POST['Sponsor_ID'])){
		$Sponsor_ID = $_POST['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_POST['Date_From'])){
		$Date_From = $_POST['Date_From'];
	}else{
		$Date_From = '';
	}

    if(isset($_POST['Date_To'])){
        $Date_To = $_POST['Date_To'];
    }else{
        $Date_To = '';
    }
    if(isset($_POST['Sponsor_ID'])){
        $Sponsor_ID = $_POST['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }
    if(isset($_POST['Section'])){
        $Section_Link = "Section=".$_POST['Section']."&";
        $Section = $_POST['Section'];
    }else{
        $Section_Link = "";
        $Section = '';
    }
    if(isset($_POST['Patient_Number'])){
        $Patient_Number = $_POST['Patient_Number'];
    }else{
        $Patient_Number = 0;
    }
    $approval_type = $_POST['approval_type'];
    //create filter based on dates selected
    if($Date_From != null && $Date_From != '' && $Date_To != '' && $Date_To != ''){
        $Date_Filter = " AND ilc.Transaction_Date_And_Time between '$Date_From' and '$Date_To'";
    }else{
        $Date_Filter = '';
    }
    $patient_name_n_number_filter="";
    $Patient_Name = str_replace(" ", "%", $_POST['Patient_Name']);
    if($Patient_Name != ''){        
        $patient_name_n_number_filter.=" AND pr.Patient_Name like '%$Patient_Name%'";
    }else{
        $Patient_Name = '';
    }
    if($Patient_Number !=''){        
        $patient_name_n_number_filter.=" AND pr.Registration_ID = '$Patient_Number%'";
    }else{
        $Patient_Number = 0;
    }
    if($Sponsor_ID != 0){
        $patient_name_n_number_filter .=" AND pc.Sponsor_ID='$Sponsor_ID'";
    }
    
    if($approval_type =='approve_credit_transaction'){
        $temp = 0;
        $get_details = mysqli_query($conn,"SELECT ilc.Transaction_Date_And_Time,pr.Registration_ID,pr.Sponsor_ID,pc.Payment_Cache_ID,pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth FROM tbl_payment_cache pc,tbl_item_list_cache ilc, tbl_patient_registration pr , tbl_sponsor sp WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID and   pc.Registration_ID = pr.Registration_ID and  sp.Sponsor_ID = pr.Sponsor_ID and (Edited_Quantity<>'0' OR Quantity<>'0') and       ilc.ePayment_Status = 'pending' and   ilc.Status IN ('active', 'approved') and  ilc.Transaction_Type = 'Credit'  $Date_Filter $patient_name_n_number_filter group by pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID  DESC LIMIT 20") or die(mysqli_error($conn));
        
        $nm = mysqli_num_rows($get_details);
        
        if($nm > 0){
            while ($row = mysqli_fetch_array($get_details)) {
                $Sponsor_ID = $row['Sponsor_ID'];
                $Payment_Cache_ID = $row['Payment_Cache_ID'];
                
                $sql_select_sponsor_name_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID' AND Require_Document_To_Sign_At_receiption = 'Mandatory'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_sponsor_name_result)>0){
                    $Guarantor_Name=mysqli_fetch_assoc($sql_select_sponsor_name_result)['Guarantor_Name'];
                }else{
                    continue;
                }
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
    ?>
        <tr>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo $Guarantor_Name; ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
            <td><a href="approvecredittransaction.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Approvecredit=Approvecredittransaction" style="text-decoration: none;"><?php echo $row['Transaction_Date_And_Time']; ?></a></td>
        </tr>
    <?php
            } 
        }else{
            echo "<tr>
            <td colspan='8' style='color:red;'>No data Found</td>
        </tr>";
        }
    }else if($approval_type =='approve_outpatient_bill_transaction'){
        $get_details = mysqli_query($conn,"SELECT Transaction_Date_And_Time,Guarantor_Name, pr.Registration_ID,pr.Sponsor_ID,pc.Payment_Cache_ID,pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth FROM tbl_payment_cache pc,tbl_item_list_cache ilc, tbl_patient_registration pr , tbl_sponsor sp WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID and   pc.Registration_ID = pr.Registration_ID and  sp.Sponsor_ID = pr.Sponsor_ID and (Edited_Quantity<>'0' OR Quantity<>'0') and       ilc.ePayment_Status = 'pending' and  ilc.Status IN ('active', 'approved') and  ilc.Transaction_Type = 'Cash'  $Date_Filter $patient_name_n_number_filter group by  pc.Payment_Cache_ID order by ilc.Payment_Cache_ID  DESC LIMIT 20") or die(mysqli_error($conn));

        $nm = mysqli_num_rows($get_details);
        $temp = 0;
        if($nm > 0){
            while ($row = mysqli_fetch_array($get_details)) {
            	$Date_Of_Birth = $row['Date_Of_Birth'];
				$date1 = new DateTime($Today);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$age = $diff->y." Years, ";
				$age .= $diff->m." Months, ";
				$age .= $diff->d." Days";
                $Transaction_Date_And_Time=$row['Transaction_Date_And_Time'];

            ?>
                <tr>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                    <td><a href="approvecashtransactionbybill.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&Approvecashtransaction=Approvecashtransaction&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Transaction_Date_And_Time']; ?></a></td>
                </tr>
                
            <?php
            	if($temp%21 == 0){
            		echo $Title;
            	}
            }
        }else{
            echo "<tr>
            <td colspan='8' style='color:red;'>No data Found</td>
        </tr>";
        }
    }

    if(isset($_POST['item_to_approve'])){
        if(isset($_POST['Check_In_Type'])){
            $Check_In_Type = $_POST['Check_In_Type'];
        }else{
            $Check_In_Type ='All';
        }
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $Registration_ID = $_POST['Registration_ID'];

        if($Check_In_Type !='All'){
            $filter =" AND Check_In_Type='$Check_In_Type'";
            $Check_In_Typep= " For ".$Check_In_Type;
        }else{
            $filter =" ";
            
        }
           
            
        $select_items = mysqli_query($conn,"SELECT Billing_Type,Treatment_Authorizer,Treatment_Authorization_No, Transaction_Date_And_Time,ilc.Item_ID,pc.Sponsor_ID, itm.Product_Name,ilc.Transaction_type, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status, Treatment_Authorization_No  from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and  ilc.Item_ID = itm.Item_ID and   ilc.Status IN ('active', 'approved')  and (Edited_Quantity<>'0' OR Quantity<>'0') AND   pc.Payment_Cache_ID = '$Payment_Cache_ID' and  pc.Registration_ID = '$Registration_ID' and    ilc.ePayment_Status = 'pending' $filter order by ilc.Payment_Cache_ID DESC LIMIT 20 ") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_items);
        // and  ilc.Transaction_Type = 'Credit' 
        if ($no > 0) {
            $count_rows=0;
            while ($data = mysqli_fetch_array($select_items)) {

                //====== generate Quantity ======
                if ($data['Edited_Quantity'] != 0) {
                    $Qty = $data['Edited_Quantity'];
                } else {
                    $Qty = $data['Quantity'];
                }
                $Sponsor_ID = $data['Sponsor_ID'];
                $The_Item_ID = $data['Item_ID'];
                $Billing_Type = $data['Billing_Type'];
                $Total = (($data['Price'] - $data['Discount']) * $Qty);
                $Grand_Total += $Total;
                $Payment_Item_Cache_List_ID=$data['Payment_Item_Cache_List_ID']; 
                $Transaction_type=$data['Transaction_type']; 
                $Treatment_Authorization_No = $data['Treatment_Authorization_No'];
                $Treatment_Authorizer = $data['Treatment_Authorizer'];
                $count_rows++;

                 /*==== check if the nhif item is allowed to a patient ====*/
               $Sponsor_needs_approval = mysqli_fetch_assoc(mysqli_query($conn,"SELECT auto_item_update_api FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'"))['auto_item_update_api'];

               $authorization_query = mysqli_query($conn,"SELECT iut.IsRestricted FROM tbl_items i, tbl_item_update_tem iut WHERE i.Item_ID = '$The_Item_ID' AND iut.ItemCode = i.Product_Code  AND iut.sponsor_id='$Sponsor_ID'");
               $Require_Authorization = mysqli_fetch_assoc($authorization_query)['IsRestricted'];
               
            //    $Require_Authorization=1;
            //    $Sponsor_needs_approval=1;

               $treatment_authorization = "";
                
               if($Require_Authorization == 1 && $Sponsor_needs_approval == 1){
                
                 $treatment_authorization = "
                    <input type='text' name='treatmentauthorizationno' class='treatmentauthorizationno' id='treatmentauthorizationno$Payment_Item_Cache_List_ID' placeholder='Verify Authorization Number'  style='text-align:center;width:30%;display:inline;'> 
                    <input type='button' name='authorize_treatment' value='NHIF-VERIFY NO.' onclick='verify_service(\"$The_Item_ID\", \"$Registration_ID\",\"$Payment_Item_Cache_List_ID\")' id='authorize_$Payment_Item_Cache_List_ID' class='btn btn-danger btn-xs' style=' width:30%;display:inline;'>
                    <div align='center' style='display:none' id='verifyprogress_".$Payment_Item_Cache_List_ID."'><img src='images/ajax-loader_1.gif' width='' style='border-color:white '></div>";
                    if($Treatment_Authorization_No=='' || $Treatment_Authorization_No ==NULL){
                       
                    }else{
                       
                    }
               }else{
                $treatment_authorization='';
                $Sponsor_needs_approval='';
               }
               //=======    END OF CKECK    ============

                ?>
                <tr>
                    <td><?php echo ++$temp; ?></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td><?php echo $data['Check_In_Type']; ?></td>
                    <td><?php echo $data['Transaction_Date_And_Time']; ?></td>
                    <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price']) : number_format($data['Price'])); ?></td>
                   <td style="text-align: right;" id="td1<?= $Payment_Item_Cache_List_ID ?>"><?php
                        if($Transaction_type=="Cash"){
                            ?>
                        <input type="text"style="width:50%" <?= $discount ?>onkeyup="update_discount_price('<?php echo $Payment_Item_Cache_List_ID ?>')" value="<?php echo $data['Discount']; ?>" id="<?= $Payment_Item_Cache_List_ID  ?>"/>
                                <?php
                        }else{
                            echo number_format($data['Discount']);
                        }
                    ?></td>
                   <td style="text-align: right;display: none" id="td2<?= $Payment_Item_Cache_List_ID ?>"><?php
                        
                         echo number_format($data['Discount']);
                        
                    ?></td>
                   <td style="text-align: right;"><?php
                        if ($data['Edited_Quantity'] != 0) {
                            echo $data['Edited_Quantity'];
                        } else {
                            echo $data['Quantity'];
                        }
                        ?></td>
                    <td style="text-align: right;" id="sub_total<?= $Payment_Item_Cache_List_ID ?>"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total) : number_format($Total)) ?></td>
                    <input type="text"class="sub_t_txt"hidden="hidden" value="<?= $Total ?>" id="sub_total_txt<?= $Payment_Item_Cache_List_ID ?>">
                    
                    <td width='20%' style="text-align: center;">
                        <select onchange="change_bill_type('<?= $Payment_Item_Cache_List_ID ?>')" id="billtype<?= $Payment_Item_Cache_List_ID ?>" class="form-control" style="width:30%;display:inline;">
                           <?php if($Billing_Type=="Inpatient Credit"|| $Billing_Type=="Outpatient Credit"){
                                ?>
                                <option selected="selected">Credit</option>
                                <?php
                            }else if($Billing_Type=="Inpatient Cash"|| $Billing_Type=="Outpatient Cash"){ ?>
                            <option <?php if($Transaction_type=="Cash"){ echo 'selected="selected"'; } ?>>Cash </option>
                            <?php if($can_change_transaction_type=='yes'){ ?>
                            <option <?php if($Transaction_type=="Credit"){ echo 'selected="selected"'; } ?>>Credit</option>
                            <?php } } ?>
                        </select>
                        <?php 
                           
                           if($Require_Authorization == 1 && $Sponsor_needs_approval == 1 && ($Treatment_Authorization_No=='' || $Treatment_Authorization_No ==NULL)){
                                echo  $treatment_authorization;   
                           }else{ 
                               ?>
                               
                          <?php }
                        ?>
                    </td>
                    <td style="text-align: center;">
                    <input type="checkbox" name="checkitem[]" class="mark" id='<?= $data['Payment_Item_Cache_List_ID']; ?>' value='<?php echo $data['Payment_Item_Cache_List_ID']; ?>'  >
                    </td>
                    <?php
                }
            }else{
                echo "<tr><td colspan='10' style='color:red; text-align:center;'>No Any Credit Transaction  pending  $Check_In_Typep</td></tr>";
            }
            ?>
        </tr>            

        <tr><td colspan="10"><hr></td></tr><input type="text"hidden="hidden" id="grand_total_txt" value="<?= $Grand_Total?>"/>
        <tr><td colspan="7" style="text-align: right;"><b>TOTAL</b></td><td style="text-align: right;" id="grand_total_td"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total) : number_format($Grand_Total)); ?></td></tr>
        <?php
        
    }

    if(isset($_POST['item_to_approve_cash'])){
        if(isset($_POST['Approval_Status'])){
            $Approval_Status = $_POST['Approval_Status'];
        }else{
            $Approval_Status ='';
        }
        if(isset($_POST['Check_In_Type'])){
            $Check_In_Type = $_POST['Check_In_Type'];
        }else{
            $Check_In_Type ='All';
        }
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $Registration_ID = $_POST['Registration_ID'];
       
        if($Check_In_Type !='All'){
            $filter =" AND Check_In_Type='$Check_In_Type'";
            $Check_In_Typep=$Check_In_Type;
        }else{
            $filter =" ";
            
        } 
        if($Approval_Status !=''){
            $filter .=" AND ilc.Approval_Status='$Approval_Status'";
        }

           
            
        $select_items = mysqli_query($conn,"SELECT Transaction_Date_And_Time, itm.Product_Name,ilc.Transaction_type, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status  from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and  ilc.Item_ID = itm.Item_ID and   ilc.Status IN ('active', 'approved')  and (Edited_Quantity<>'0' OR Quantity<>'0') AND   pc.Payment_Cache_ID = '$Payment_Cache_ID' and  pc.Registration_ID = '$Registration_ID' and    ilc.ePayment_Status = 'pending' $filter order by ilc.Payment_Item_Cache_List_ID DESC") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_items);
        if ($no > 0) {
            $count_rows=0;
            while ($data = mysqli_fetch_array($select_items)) {
                //generate Quantity
                if ($data['Edited_Quantity'] != 0) {
                    $Qty = $data['Edited_Quantity'];
                } else {
                    $Qty = $data['Quantity'];
                }
                $Total = (($data['Price'] - $data['Discount']) * $Qty);
                $Grand_Total += $Total;
                $Payment_Item_Cache_List_ID=$data['Payment_Item_Cache_List_ID']; 
                $Transaction_type=$data['Transaction_type']; 
                $count_rows++;
                ?>
                <tr>
                    <td><?php echo ++$temp; ?></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td><?php echo $data['Check_In_Type']; ?></td>
                    <td><?php echo $data['Transaction_Date_And_Time']; ?></td>
                    <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price']) : number_format($data['Price'])); ?></td>
                   <td style="text-align: right;" id="td1<?= $Payment_Item_Cache_List_ID ?>"><?php
                        if($Transaction_type=="Cash"){
                            ?>
                        <input type="text"style="width:50%" <?= $discount ?>onkeyup="update_discount_price('<?php echo $Payment_Item_Cache_List_ID ?>')" value="<?php echo $data['Discount']; ?>" id="<?= $Payment_Item_Cache_List_ID  ?>"/>
                                <?php
                        }else{
                         echo number_format($data['Discount']);
                        }
                    ?></td>
                   <td style="text-align: right;display: none" id="td2<?= $Payment_Item_Cache_List_ID ?>"><?php
                        
                         echo number_format($data['Discount']);
                        
                    ?></td>
                   <td style="text-align: right;"><?php
                        if ($data['Edited_Quantity'] != 0) {
                            echo $data['Edited_Quantity'];
                        } else {
                            echo $data['Quantity'];
                        }
                        ?></td>
                    <td style="text-align: right;" id="sub_total<?= $Payment_Item_Cache_List_ID ?>"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total) : number_format($Total)) ?></td>
                     <input type="text"class="sub_t_txt"hidden="hidden" value="<?= $Total ?>" id="sub_total_txt<?= $Payment_Item_Cache_List_ID ?>">
                    
                    <?php
                    if ($no == 1) {
                       // echo '<td>&nbsp;</td>';
                    } else {
                       // echo '<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem(' . $data["Payment_Item_Cache_List_ID"] . ')">Remove</button></td>';
                    }
                     ?>
                  
                    <td style="text-align: center;">
                        <!-- <input type="text" name="checkitem[]" style="display: nlone;" class="mark" value='<?php echo $data['Item_ID']; ?>'> -->
                        <input type="checkbox" name="checkitem[]" class="mark" id='<?= $data['Payment_Item_Cache_List_ID']; ?>' value='<?php echo $data['Payment_Item_Cache_List_ID']; ?>' >
                    </td>
                    <?php
                }
            }else{
                echo "<tr><td colspan='10' style='color:red; text-align:center;'>No Any Transaction pending for $Check_In_Typep</td></tr>";
            }
            ?>
        </tr>            

        <tr><td colspan="10"><hr></td></tr><input type="text"hidden="hidden" id="grand_total_txt" value="<?= $Grand_Total?>"/>
        <tr><td colspan="7" style="text-align: right;"><b>TOTAL</b></td><td style="text-align: right;" id="grand_total_td"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total) : number_format($Grand_Total)); ?></td></tr>
        <?php
        
    
}
    if(isset($_POST['socialComments'])){
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $items = $_POST['items'];
        $Social_Walfare_Comments = mysqli_real_escape_string($conn, $_POST['Social_Walfare_Comments']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $selectExst = mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_social_bill_creation WHERE Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND Social_Comments='$Social_Walfare_Comments'") or die(mysqli_error($conn));
        if(mysqli_num_rows($selectExst)>0){
            echo "done";
        }else{
            $commectSql = mysqli_query($conn, "INSERT INTO tbl_social_bill_creation (Registration_ID, Payment_Cache_ID,Social_Comments, Employee_ID )VALUES('$Registration_ID', '$Payment_Cache_ID', '$Social_Walfare_Comments', '$Employee_ID')") or die(mysqli_error($conn));
            foreach($items as $item){
                $item;
                $Update = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Approval_Status='active' WHERE Payment_Item_Cache_List_ID='$item' AND Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
            }
            if($commectSql){
                echo "ok";
            }else{
                echo "failed";
            }
        }
    }

    if(isset($_POST['HODsocialComments'])){
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Social_Bill_ID = $_POST['Social_Bill_ID'];
        $type_of_approval = mysqli_real_escape_string($conn, $_POST['type_of_approval']);
        $items = $_POST['items'];
        $document_approver = mysqli_real_escape_string($conn, $_POST['document_approver']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $commectSql = mysqli_query($conn, "UPDATE tbl_social_bill_creation SET approvalStatus='Approve', HOD_ID='$Employee_ID', HOD_date=NOW(),type_of_approval='$type_of_approval', document_approver='$document_approver'  WHERE Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND  Social_Bill_ID='$Social_Bill_ID'") or die(mysqli_error($conn));
        foreach($items as $item){
            $item;
            $Update = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Approval_Status='aprove' WHERE Payment_Item_Cache_List_ID='$item' AND Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
        }
        if($commectSql){
            echo "ok";
        }else{
            echo "failed";
        }
    }


    if(isset($_POST['DGsocialComments'])){
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Social_Bill_ID = $_POST['Social_Bill_ID'];
        $type_of_approval = mysqli_real_escape_string($conn, $_POST['type_of_approval']);
        $items = $_POST['items'];
        $document_approver = mysqli_real_escape_string($conn, $_POST['document_approver']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $commectSql = mysqli_query($conn, "UPDATE tbl_social_bill_creation SET approvalStatus='Approved', Managment_Approver_ID='$Employee_ID', Managment_Approval_date=NOW() WHERE Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND  Social_Bill_ID='$Social_Bill_ID'") or die(mysqli_error($conn));
        foreach($items as $item){
            $item;
            $Update = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Approval_Status='aproved' WHERE Payment_Item_Cache_List_ID='$item' AND Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
        }
        if($commectSql){
            echo "ok";
        }else{
            echo "failed";
        }
    }

    if(isset($_POST['msamahasearch'])){
        $Patient_Number=$_POST['Patient_Number'];
        $Patient_Name=$_POST['Patient_Name'];
        $fitlter='';
        if($Patient_Name != ''){
            $fitlter .=" AND pr.Patient_Name LIKE  '$Patient_Name'";
        }
    
        if($Patient_Number != ''){
            $fitlter .=" AND sbc.Registration_ID =  '$Patient_Number'";
        }
        $temp = 0;
       
        $selectExst = mysqli_query($conn, "SELECT created_at,Social_Bill_ID,Guarantor_Name,Payment_Cache_ID,Patient_Name,Gender, sbc.Registration_ID FROM tbl_sponsor sp, tbl_social_bill_creation sbc, tbl_patient_registration pr WHERE sbc.Registration_ID=pr.Registration_ID AND approvalStatus='Approve' AND pr.Sponsor_ID=sp.Sponsor_ID $filter ") or die(mysqli_error($conn));
        if(mysqli_num_rows($selectExst)>0){
           
                while($row2=mysqli_fetch_assoc($selectExst)){
                    
                $Date_Of_Birth = $row2['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
    ?>
        <tr>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Patient_Name']); ?></a></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Registration_ID']; ?></a></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Guarantor_Name']; ?></a></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Gender']); ?></a></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="approvecashtransactionbybill.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row2['Payment_Cache_ID']; ?>" style="text-decoration: none;"><?php echo $row2['created_at']; ?></a></td>
           
        </tr>
    <?php
            }
        }else{
            echo "<tr><td colspan='5'  style='color:red;'>No Result found for $Patient_Number $Patient_Name </td></tr>";
        }
       
    }
    

    //ALTER TABLE `tbl_social_bill_creation` ADD `HOD_ID` INT NULL AFTER `created_at`, ADD `HOD_date` DATETIME NOT NULL AFTER `HOD_ID`, ADD `Managment_Approver_ID` INT NULL AFTER `HOD_date`, ADD `Managment_Approval_date` DATETIME NOT NULL AFTER `Managment_Approver_ID`; 
mysqli_close($conn);
