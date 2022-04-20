<table width="100%">
	<tr><td colspan="9"><hr></td></tr>
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>MAIN DEPARTMENT NAME</b></td>
		
		<td style="text-align: center" width="10%"><b>NO OF SERVICES</b></td>
		<td style="text-align: right" width="10%"><b>TOTAL CANCELLED</b></td>
	</tr>
	<tr><td colspan="9"><hr></td></tr>
        <?php 
            $temper=0;
            $sql_select_main_department_result=mysqli_query($conn,"SELECT *FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_main_department_result)>0){
                $all_Grand_Total_Cancelled = 0;
		
		$all_Grand_Quantity = 0;
                 
                $all_Patient_No=0;
                $grand_grand_total_all=0;
                while($idara_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                    $finance_department_id=$idara_rows['finance_department_id'];
                    $finance_department_name=$idara_rows['finance_department_name'];
                    ?>
         
                        <tr><td><b><?php echo ++$temper; ?></b></td>
                            <td><a href='<?= "generalgeneralledgerfilteredreport.php?Section=$Section&Patient_Type=$Patient_Type&Start_Date=$Start_Date&End_Date=$End_Date&Sponsor_ID=$Sponsor_ID&Employee_ID=$Employee_ID&Hospital_Ward_ID=$Hospital_Ward_ID&clinic=$Clinic_ID&GeneralLedgerCashAndCreditReport=GeneralLedgerCashAndCreditReportThisPage&finance_department_id=$finance_department_id" ?>' style="text-decoration:none"><b><label style='color: #0079AE;'><?php echo strtoupper($finance_department_name);?></label></b></a></td>
                        <?php 
                        ///////////////////////////////////////////////////////////////////////////////////
	$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                    tbl_item_category ic order by ic.Item_Category_Name") or die(mysqli_error($conn));
	$num_cat = mysqli_num_rows($get_categories);
	if($num_cat > 0){
		
		
		$Grand_Total_cancelled = 0;
		
		$Grand_Quantity = 0;
                
                $Patient_No=0;
		while ($cat = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $cat['Item_Category_ID'];
			$Quantity = 0;
			$Total_Cancelled = 0;
			
			//get quantity, cash & credit transactions 
            $get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type,ts.Exemption, pp.payment_type, pp.Fast_Track, pp.Pre_Paid  from tbl_patient_payment_item_list ppl,
                                    tbl_item_category ic, tbl_item_subcategory isu, tbl_items i,tbl_patient_payments pp,tbl_sponsor as ts  where
                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                    ic.Item_Category_ID = isu.Item_Category_ID and
                                    isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                    $filter
                                    i.Item_ID = ppl.Item_ID and
                                    ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
            
			$num_quantity = mysqli_num_rows($get_Quantiry);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantiry)){
                                    $Quantity = $Quantity + $Det['Quantity'];
                                    $Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
                                    $Total_Cancelled = $Total_Cancelled + (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                                    $Grand_Total_Cancelled += (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
				}
			}
			//get total patients
			$get_patients = mysqli_query($conn,"select pp.Registration_ID from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID and
	                                        ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
		 $Patient_No += mysqli_num_rows($get_patients);
			
		}
                $all_Grand_Total_Cancelled += $Total_Cancelled;
		 
		$all_Grand_Quantity += $Grand_Quantity;
                
                $all_Patient_No +=$Patient_No;
                
?>
                
		<td style="text-align: center;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Grand_Quantity; }else{ echo $Grand_Quantity; } ?></b></td>
		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cancelled, 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cancelled); } ?></b></td>	
<?php
	}

                        ///////////////////////////////////////////////////////////////////////////////////
                }
                $grand_grand_total_all =$all_Grand_Total_Cancelled;
                ?>
               </tr>
               <tr><td colspan="9"><hr></td></tr>
	<tr>
		<td colspan="2"><b>GRAND TOTAL</b></td>
                 
		<td style="text-align: center;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $all_Grand_Quantity; }else{ echo $all_Grand_Quantity; } ?></b></td>
		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format($grand_grand_total_all, 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($grand_grand_total_all); } ?></b>&nbsp;&nbsp;&nbsp;</td>
	</tr>
             <?php
            }
            ?>
    </table>


