<table width="100%">
	<tr><td colspan="9"><hr></td></tr>
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>MAIN DEPARTMENT NAME</b></td>

		<td style="text-align: center" width="10%"><b>NO OF SERVICES</b></td>
		<td style="text-align: right" width="10%"><b>CASH</b></td>

		<td style="text-align: right" width="10%"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr><td colspan="9"><hr></td></tr>
        <?php
            $temper=0;
            $Currency_Code='';
            $sql_select_main_department_result=mysqli_query($conn,"SELECT *FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_main_department_result)>0){
                $all_Grand_Total_Cash = 0;

		$all_Grand_Total_Credit = 0;
		$all_Grand_Quantity = 0;
                $all_Grand_Total_Msamaha=0;
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

		$Grand_Total_Cash = 0;
//		$Grand_Total_Fast_Track = 0;
		$Grand_Total_Credit = 0;
		$Grand_Quantity = 0;
                $Grand_Total_Msamaha=0;
                $Patient_No=0;
		while ($cat = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $cat['Item_Category_ID'];
			$Quantity = 0;
			$Total_Cash = 0;
			$Total_Fast_Track = 0;
			$Total_Credit = 0;
            $Total_Msamaha=0;
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
                    $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                    if(($Det['Exemption']=='yes') && ((strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'post'))){
	                    $Total_Msamaha += $Total;
			            $Grand_Total_Msamaha += $Total;
                    }  else {
                      	$Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
					if((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '0') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'pre')){
			            if($Det['Fast_Track'] == '1'){
			            	$Total_Fast_Track += $Total;
//				            $Grand_Total_Fast_Track += $Total;
			            }else{
				            $Total_Cash += $Total;
				            $Grand_Total_Cash += $Total;
				        }
			        }

                                    }

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
                $all_Grand_Total_Cash += $Grand_Total_Cash;

		$all_Grand_Quantity += $Grand_Quantity;
                $all_Patient_No +=$Patient_No;

?>

		<td style="text-align: center;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Grand_Quantity; }else{ echo $Grand_Quantity; } ?></b></td>
		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash, 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash); } ?></b></td>

		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash, 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash); } ?></b>&nbsp;&nbsp;&nbsp;</td>

<?php
	}

                        ///////////////////////////////////////////////////////////////////////////////////
                }
                $grand_grand_total_all =$all_Grand_Total_Cash;
                ?>
               </tr>
							 <?php
							 	$select_rev_from_other_sources=mysqli_query($conn,"SELECT Price FROM tbl_other_sources_payment_item_list pil, tbl_other_sources_payments osp WHERE pil.Payment_ID=osp.Payment_ID AND osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date'");
								$service_number=mysqli_num_rows($select_rev_from_other_sources);
								$total_rev_amount=0;
								while ($row=mysqli_fetch_assoc($select_rev_from_other_sources)) {
									$total_rev_amount+=$row['Price'];
								}
							 ?>
							 <tr>
							 	<td><b><?=(++$temper);?></b></td> <td><a href='<?= "generalgeneralledgerfilteredreport.php?Section=other_sources&Start_Date=$Start_Date&End_Date=$End_Date&Employee_ID=$Employee_ID&GeneralLedgerCashAndCreditReport=GeneralLedgerCashAndCreditReportThisPage" ?>'  style='text-decoration:none'><b><label style='color: #0079AE;'>REVENUE FROM OTHER SOURCES</label></b></a></td><td style='text-align:center;'><b><?=$service_number;?></b></td><td style='text-align:right;'><b><?=number_format($total_rev_amount);?></b></td><td style='text-align:right;'><b><?=number_format($total_rev_amount);?></b>&nbsp;&nbsp;&nbsp;</td>
							 </tr>
               <tr><td colspan="9"><hr></td></tr>
	<tr>
		<td colspan="2"><b>GRAND TOTAL</b></td>

		<td style="text-align: center;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo ($all_Grand_Quantity+$service_number); }else{ echo ($all_Grand_Quantity+$service_number); } ?></b></td>
		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format(($all_Grand_Total_Cash+$total_rev_amount), 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Cash+$total_rev_amount); } ?></b></td>

		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format(($grand_grand_total_all+$total_rev_amount), 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($grand_grand_total_all+$total_rev_amount); } ?></b>&nbsp;&nbsp;&nbsp;</td>
	</tr>
             <?php
            }
            ?>
    </table>
