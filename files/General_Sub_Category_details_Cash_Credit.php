<center>
<table width="100%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>ITEM NAME</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF PATIENTS</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF SERVICES</b></td>
		<td style="text-align: right;" width="12%"><b>CASH</b></td>
		 
		<td style="text-align: right;" width="12%"><b>CREDIT</b></td>
        <td style="text-align: right;" width="12%"><b>MSAMAHA</b></td>
		<!--<td style="text-align: right;" width="12%"><b>TOTAL</b></td>-->
	</tr>
	<tr><td colspan="9"><hr></td></tr>
<?php
	$Grand_Quantity = 0;
	$Grand_Total_Cash = 0;
	 
	$Grand_Total_Credit = 0;
    	$Grand_Total_Msamaha=0;
	$get_items = mysqli_query($conn,"select i.Item_ID, i.Product_Name,ts.Exemption from
                                tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl,tbl_sponsor as ts,tbl_patient_payments pp where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                ic.Item_Category_ID = isu.Item_Category_ID and
                                isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                 ts.Sponsor_ID=pp.Sponsor_ID and
                                $filter
                                i.Item_ID = ppl.Item_ID group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));

	$num_items = mysqli_num_rows($get_items);
	if($num_items > 0){
		$coun = 0;
		while($data = mysqli_fetch_array($get_items)){
			$Item_ID = $data['Item_ID'];
			$Quantity = 0;
			$Total_Cash = 0;
			 
			$Total_Credit = 0;
                        $Total_Msamaha=0;
			echo '<tr><td>'.++$coun.'</td>';
?>
					<td style="color: #0079AE;"><b><label onclick="Display_Items_Details('<?php echo $Section; ?>',<?php echo $data['Item_ID']; ?>)"><?php echo $data['Product_Name']; ?></label></b></td>
<?php
			//get quantity, cash & credit transactions
			$get_Quantity = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount,pp.Billing_Type,ts.Exemption,pp.payment_type,pp.Fast_Track, pp.Pre_Paid from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl,tbl_sponsor as ts,tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        i.Item_ID = ppl.Item_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and
	                                        $filter
	                                        ppl.Item_ID = '$Item_ID' AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
                   
			$num_quantity = mysqli_num_rows($get_Quantity);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantity)){
                                    
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
				        	 
				        }else{
				            $Total_Cash += $Total;
				            $Grand_Total_Cash += $Total;
				        }
			        }elseif((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '1') or (strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'post')){
			            $Total_Credit += $Total;
			            $Grand_Total_Credit += $Total;
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
	                                        i.Item_ID = ppl.Item_ID and
	                                        $filter
	                                        ppl.Item_ID = '$Item_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			echo "<td style='text-align: center;'>".$Patient_No."</td>";
			echo '<td style="text-align: center;">'.$Quantity.'</td><td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cash, 2); }else{ echo number_format($Total_Cash); }
			echo '</td>';

			 
                        
			echo '<td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Credit, 2); }else{ echo number_format($Total_Credit); }
			echo '</td>';
                        
                        echo '<td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Msamaha, 2); }else{ echo number_format($Total_Msamaha); }
			echo '</td>';
                        
                        
//			echo '<td style="text-align: right;">';
//				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Credit + $Total_Cash+$Total_Msamaha, 2); }else{ echo number_format($Total_Credit + $Total_Cash+$Total_Msamaha); }
//			echo '</td>
			echo '</tr>';
		}
	}
?>
	<tr><td colspan="9"><hr></td></tr>
	<tr>
		<td colspan="3"><b>GRAND TOTAL</b></td>
		<td style="text-align: center;"><b><?php echo $Grand_Quantity; ?></b></td>
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cash, 2); } else{ echo number_format($Grand_Total_Cash); } ?></b>
		</td>
		 
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Credit, 2); } else{ echo number_format($Grand_Total_Credit); } ?></b>
		</td>
                
        <td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Msamaha, 2); } else{ echo number_format($Grand_Total_Msamaha); } ?></b>
		</td>
                
<!--		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cash + $Grand_Total_Credit + $Grand_Total_Msamaha, 2); } else{ echo number_format($Grand_Total_Cash + $Grand_Total_Credit + $Grand_Total_Msamaha); } ?></b>
		</td>-->
	</tr>
</table>