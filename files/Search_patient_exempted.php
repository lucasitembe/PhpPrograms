<?php 

include("./includes/connection.php");
session_start();
if(isset($_POST['search_value'])){
    $search_value = mysqli_real_escape_string($conn, $_POST['search_value']);
   $select_exempted_patient = mysqli_query($conn, "SELECT tef.Nurse_Exemption_ID,Patient_Name,pr.Gender,pr.Phone_Number, tef.Registration_ID,kiasikinachoombewamshamaha,Patient_Bill_ID,exemptionstatus, Anaombewa,  Employee_Name,Employee_Title, tef.Exemption_ID, tef.Employee_ID,  tef.created_at FROM tbl_employee e, tbl_temporary_exemption_form tef, tbl_patient_registration pr, tbl_nurse_exemption_form nef WHERE nef.Nurse_Exemption_ID=tef.Nurse_Exemption_ID AND (tef.Registration_ID like '%$search_value%' or Patient_Name like '%$search_value%' ) AND tef.Employee_ID=e.Employee_ID AND tef.Registration_ID=pr.Registration_ID ORDER BY Exemption_ID DESC LIMIT 20 ") or die(mysqli_error($conn));

   $num= 0;
            while($row = mysqli_fetch_assoc($select_exempted_patient)){
                $Exemption_ID =$row['Exemption_ID'];
                $Patient_Name = $row['Patient_Name'];
                $Nurse_Exemption_ID = $row['Nurse_Exemption_ID'];
                $Registration_ID = $row['Registration_ID'];
                $Employee_Name = $row['Employee_Name'];
                $Employee_Title = $row['Employee_Title'];
                $created_at = $row['created_at'];
                $kiasikinachoombewamshamaha =$row['kiasikinachoombewamshamaha'];
                $Phone_Number = $row['Phone_Number'];
                $Gender = $row['Gender'];
                $payment_method = $row['payment_method'];
                $Patient_Bill_ID = $row['Patient_Bill_ID'];
                $Anaombewa =$row['Anaombewa'];
                $exemptionstatus =$row['exemptionstatus'];
     
                $count_rw++;

                $Grand_Total = 0; 

                $Grand_Total = 0;
               
                $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from 
                tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where
                pp.Transaction_type = 'direct cash' and
                pp.Transaction_status <> 'cancelled' and
                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                ppl.Item_ID=i.Item_ID and
                i.Visible_Status='Others' and 
                pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

                    
                $nms = mysqli_num_rows($cal);
                if ($nms > 0) {
                    while ($cls = mysqli_fetch_array($cal)) {
                        $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                    }
                }
                //========== Mwisho wa kuangalia=======//

                //total hospital bill

            //jumla ya gharama za hospital
            $total_hospital_bill=0;
          //  if (strtolower($payment_method) == 'cash') {
                
                $items = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from     tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where    ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and   i.Item_ID = ppl.Item_ID and    pp.Transaction_type = 'indirect cash' and (pp.Billing_Type = 'Inpatient Cash' OR pp.Pre_Paid='1') and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            // }
            //  else {
            //     $items = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from   tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where    ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and   i.Item_ID = ppl.Item_ID and   pp.Transaction_type = 'indirect cash' and   pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and   (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and     pp.Patient_Bill_ID = '$Patient_Bill_ID'  and  pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            // }
            $nm = mysqli_num_rows($items);
            if ($nm > 0) {
                $temp = 0;
                $Sub_Total = 0;        
                while ($dt = mysqli_fetch_array($items)) {          
                    $total_hospital_bill += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                }     
            }

            $select_this_exempition = mysqli_query($conn, "SELECT Debt_status FROM tbl_patient_debt WHERE Patient_Bill_ID ='$Patient_Bill_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

            $billStatus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Status FROM tbl_prepaid_details WHERE Patient_Bill_ID ='$Patient_Bill_ID' AND Registration_ID='$Registration_ID'"))['Status'];
            if(mysqli_num_rows($select_this_exempition)>0){
                while($rowdt = mysqli_fetch_assoc($select_this_exempition)){
                    $Debt_status = $rowdt['Debt_status'];
                }
            }else{
                if($Anaombewa =='Dhamana'){
                    $Debt_status ="Pending";
                }else{
                    $Debt_status = "Exemption";
                }
                
            }
            $jumla_yagharama_za_hospitali= $total_hospital_bill;
            //mwisho ho ya gharama za hospital 
                    $deficity =$jumla_yagharama_za_hospitali -$Grand_Total ;
                ?>
            <tr> 
                <td><?php echo $count_rw; ?></td>
                <td><?php echo strtoupper($Patient_Name);?></td>
                <td><?php echo $Gender; ?></td>
                <td><?php echo $Registration_ID;?></td>
                <td><?php echo $Phone_Number; ?></td>                    
                <td><?php echo number_format($jumla_yagharama_za_hospitali, 2);?></td>
                <td><?php echo number_format($Grand_Total,2);?></td>
                <td><?php echo number_format($deficity,2);?></td>
                <td><?php echo number_format($kiasikinachoombewamshamaha, 2); ?></td>
                <td><?php echo $Anaombewa; ?></td>
                <td><?php echo $Debt_status; ?></td>
                <td><?php echo $created_at;?></td>                 
                <td>
                    <div class="btn-group hidden-sm"> 
                        <span ><a href="preview_exemptionform.php?Registration_ID=<?php echo $Registration_ID;?>&Exemption_ID=<?php echo $Exemption_ID;?>&created_at=<?php echo $created_at;?>&Nurse_Exemption_ID=<?php echo $Nurse_Exemption_ID;?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> PREVIEW </a> &nbsp;&nbsp; 
                        <?php $select_form = mysqli_query($conn, "SELECT Exemption_ID FROM tbl_exemption_maoni_dhs WHERE Exemption_ID='$Exemption_ID'") or die(mysqli_error($conn));
                        if((mysqli_num_rows($select_form))>0){
                            $select_dhs_maon = mysqli_query($conn, "SELECT MaoniDHS_ID, Exemption_ID FROM tbl_exemption_maoni_dhs WHERE Exemption_ID='$Exemption_ID' " ) or mysqli_error($conn);
                            while($maoni = mysqli_fetch_assoc($select_dhs_maon)){
                                $MaoniDHS_ID = $maoni['MaoniDHS_ID'];
                                $Exemption_id = $maoni['Exemption_ID'];
                                if (isset($_SESSION['userinfo'])) {
                                    if ($_SESSION['userinfo']['Management_Works'] == 'yes') {
                                        if($billStatus != "cleared"){
                                           echo "<a href='dhs_edit_exemptionform.php?Registration_ID=$Registration_ID&Exemption_ID=$Exemption_id&created_at=$created_at&Nurse_Exemption_ID=$Nurse_Exemption_ID&MaoniDHS_ID=$MaoniDHS_ID' class='btn btn-primary btn-xs'><i class='fa fa-pancel'></i>DHS EDIT</a>";
                                     
                                        }
                                        if($Debt_status == "Pending"){
                                            echo "<input type='button' class='btn btn-danger btn-xs' style='width:75px;' value='CANCEL' onclick='cancel_excemption($Exemption_ID)'>";
                                        }
                                        
                                    }
                                }
                             
                            }
                            
                        }else{
                            echo "<a href='newexemptionform.php?Registration_ID=$Registration_ID&Exemption_ID=$Exemption_ID&created_at=$created_at&Nurse_Exemption_ID=$Nurse_Exemption_ID' class='btn btn-primary btn-xs'><i class='fa fa-pancel'></i>EDIT</a>";
                        }
                        $totol_bill +=$jumla_yagharama_za_hospitali;
                        $total_Amount_paid += $Grand_Total;
                        $Total_deficity += $deficity;
                        $Totalkiasikilichoombewa +=$kiasikinachoombewamshamaha;
                        ?>
                        </span>
                    <div>
                </td>
            </tr>
            <?php } 
            
            
                echo '<tr>
                    <th colspan="5">TOTAL AMOUNT</th>
                    <th> '.number_format($totol_bill, 2).'</th>
                    <th>'.number_format($total_Amount_paid, 2) .'</th>
                    <th>'.number_format($Total_deficity, 2) .'</th>
                    <th>'.number_format($Totalkiasikilichoombewa, 2) .'</th>
                </tr>';
        }
            
    if(isset($_POST['cancelexemption'])){
        $Exemption_ID = $_POST['Exemption_ID'];
        
        $updatestatus = mysqli_query($conn, "UPDATE tbl_temporary_exemption_form SET exemptionstatus = 'Cancelled' WHERE Exemption_ID='$Exemption_ID'") or die(mysqli_error($conn));
        if($updatestatus){
            echo "Exemption cancelled";
        }else{
            echo "Failed to cancel";
        }
    }