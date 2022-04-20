<?php
	session_start();
	include("./includes/connection.php");
	include("allFunctions.php");
	$Grand_Total = 0;

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}
	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}
	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//get Admision_ID
	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = 0;
	}

	//get Cash_Bill_Status & Credit_Bill_Status
	$select = mysqli_query($conn,"select Cash_Bill_Status, Credit_Bill_Status from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($dt = mysqli_fetch_array($select)) {
			$Cash_Bill_Status = $dt['Cash_Bill_Status'];
			$Credit_Bill_Status = $dt['Credit_Bill_Status'];
		}
	}else{
		$Cash_Bill_Status = '';
		$Credit_Bill_Status = '';
	}

	//get guarantor name
	$get_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($get_sponsor);
	if($nm > 0){
		while ($dt = mysqli_fetch_array($get_sponsor)) {
			$Guarantor_Name = $dt['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
    	$payments_filter = "pp.payment_type = 'post' and ";
    }else{
    	$payments_filter = '';
    }
    if($Transaction_Type=="Cash_Bill_Details"){
        $Payment_Method= 'cash';
    }else{
         $Payment_Method= 'credit';
    }
?>

<?php
	//calculate grand total
		$Grand_Total=0;
        // AND pp.Check_In_ID='$Check_In_ID'
		if($Transaction_Type == 'Cash_Bill_Details'){
			$Billing_Type="  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";   
			$TotalBill = json_decode(PatientTotalBills($Registration_ID,$Patient_Bill_ID, $Billing_Type), true);
		}else{
			$Billing_Type="  AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";   
			$TotalBill = json_decode(PatientTotalBills($Registration_ID,$Patient_Bill_ID, $Billing_Type), true);
		}
		$Grand_Total = $TotalBill[0]['TotalAmountRequired'];
	
?>
		
                <fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
                    <legend>
                        <?php
                        if ($Transaction_Type == 'Cash_Bill_Details') {
                            echo "CASH BILL SUMMARY ";
                        } else {
                            echo "CREDIT BILL SUMMARY";
                        }
                        ?>
                    </legend>

                    <table width="100%">
                       
                        <tr><td><b>CATEGORY</b></td><td style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td></tr>
                        <?php
                        if ($Transaction_Type == 'Cash_Bill_Details') {
                            $Billing_Type="  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";                            
                            $get_cate = json_decode(getBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type), true);

                        } else {
                            $Billing_Type="  AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";
                            $get_cate = json_decode(getBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type), true);
                        
                        }
                        $temp_cat = 0;
                        $Grand_Totals=0;
                    if (sizeof($get_cate) > 0) {                        
                        foreach($get_cate as $dts) {
                                $Item_Category_Name = $dts['Item_Category_Name'];
                                $Item_Category_ID = $dts['Item_Category_ID'];
                                $Category_Grand_Total = 0;

                                //calculate total       
                                if (strtolower($Payment_Method) == 'cash') {
                                    $Billing_Type="  AND pp.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash') ";                            
                                    $items_i =json_decode(getBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $dts['Item_category_ID']), true);

                                } else {
                                    $Billing_Type="  AND pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') ";                            
                                    $items_i =json_decode(getBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $dts['Item_category_ID']), true);

                                }

                                if (sizeof($items_i) > 0) {
                                    $temp = 0;
                                    $Sub_Total = 0;
                                    foreach($items_i as $t_item) {
                                        $Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
                                    }
                                }
                                
                                echo "<tr><td>" . ++$temp_cat . '<b>. </b>' . ucwords(strtolower($Item_Category_Name)) . "</td><td style='text-align: right;'>" . number_format($Category_Grand_Total) . "&nbsp;&nbsp;&nbsp;</td></tr>";
                                $Grand_Totals +=$Category_Grand_Total;
                                }
                            }

                        ?>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td><b>Bill Status</b></td>
                            <?php
                            if ($Transaction_Type == 'Cash_Bill_Details') {
                                echo "<td style='text-align: right;'>" . ucwords(strtolower($Cash_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                            } else {
                                echo "<td style='text-align: right;'>" . ucwords(strtolower($Credit_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <td width="65%"><b>Total Amount Required </b></td><td style="text-align: right;"><?php echo number_format($Grand_Totals); ?>&nbsp;&nbsp;&nbsp; <input type="text" id="Grand_Total" value="<?= $amount_required_total ?>" hidden="hidden"></td>
                            <!--<td width="65%"><b>Total Amount Required </b></td><td style="text-align: right;"><?php // echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp; <input type="text" id="Grand_Total" value="<?= $Grand_Total ?>" hidden="hidden"></td>-->
                        </tr>
                        <tr>
                            <td width="65%"><b>Cash Deposit</b></td>
                            <td style="text-align: right;">
                                <?php
                                $Grand_Total_Direct_Cash = 0;
                                if ($Transaction_Type == 'Cash_Bill_Details') {
                                    //calculate cash payments
									$selectcash=   json_decode(getPatientDirectCash($Registration_ID,$Patient_Bill_ID), true);                                    
                                    $Grand_Total_Direct_Cash =$selectcash[0]['TotalAmount'];
                                    echo number_format($Grand_Total_Direct_Cash);
                                } else {
                                    echo "<i>(not applicable)</i>";
                                }
//                                $Temp_Balance = ($Grand_Total - $Grand_Total_Direct_Cash);
                                $Temp_Balance = ($Grand_Totals - $Grand_Total_Direct_Cash);
                                ?>&nbsp;&nbsp;&nbsp;
								<input type="text" id="Grand_Total_Direct_Cash" value="<?= $Grand_Total_Direct_Cash ?>" hidden="hidden">
                            </td>
                        </tr>
						<tr>
							<?php 
								if ($Transaction_Type == 'Cash_Bill_Details') {
									if(sizeof($selectexmption)>0){
										foreach($selectexmption as $rw){
											$Anaombewa =$rw['Anaombewa'];
											$kiasikinachoombewamshamaha = $rw['kiasikinachoombewamshamaha'];
											$amountsuggested = $rw['amountsuggested'];
											$maoniDHS = $rw['maoniDHS'];
											
										}                                         
									}else{
										$amountsuggested = 0;
										$kiasikinachoombewamshamaha=0;
									}
									$Temp_Balance = ($Grand_Totals - $Grand_Total_Direct_Cash -$amountsuggested);
								}
							?>
							<input type="text" id="Grand_Total" value="<?php echo $Grand_Totals; ?>"  readonly hidden  />
                            <input type="text" id="Anaombewa" value="<?php echo $Anaombewa; ?>"  readonly hidden />
                            <input type="text" id="maoniDHS" value="<?php echo $maoniDHS; ?>"  readonly hidden />
						</tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td><b>Balance </b></td>
                            <td style="text-align: right;">
                                <?php
                                if ($Temp_Balance > 0) {
                                    echo number_format($Temp_Balance);
                                } else {
                                    echo 0;
                                }
                                ?>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
						
                        <?php
                        if ($Temp_Balance < 0) {
                            ?>
                            <tr>
                                <td><b>Refund Amount</b></td><td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr><td colspan="2"><hr></td></tr>
                    </table>
                </fieldset>
<br/>

                <table width="100%">
                    <tr>
                        <td>
                            <input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                        </td>
                        <td>
                            
                            <?php
                            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                            $sql_check_approve_bill_privilege_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_privileges WHERE Employee_ID='$Employee_ID' AND can_have_access_to_approve_bill_btn='yes'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_check_approve_bill_privilege_result)>0){  
                            	if ($Transaction_Type == 'Cash_Bill_Details') {
                                    if ($Cash_Bill_Status == 'pending') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                        <?php
                                    } else if ($Cash_Bill_Status == 'approved') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning()">
                                        <?php
                                    }
                                } else {
                                    if ($Credit_Bill_Status == 'pending') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                        <?php
                                    } else if ($Credit_Bill_Status == 'approved') {
                                        ?>
                                        <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="CLEAR BILL " onclick="Approve_Patient_Bill_Warning()">
                                        <?php
                                    }
                                }
                            }else{
                                ?>
                                         <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL " onclick="alert('Access Denied!you dont have privilege to approve bill')">
                                       
                                    <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <?php 
                        $sql_select_patient_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_patient_sponsor_result)>0){
                            $sponsor_rows=mysqli_fetch_assoc($sql_select_patient_sponsor_result);
                            $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                        }
                    ?>
                    <tr>
                        <td>
                            <?php if ($Transaction_Type == 'Cash_Bill_Details') { ?>
                                <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                            <?php } else { ?>
                                <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments_Warning();">
							<?php } ?>
                        </td>
                       
                    </tr>
                    <tr>
                         <?php 
                        	$sql_check_if_bill_creared_result=mysqli_query($conn,"SELECT Admision_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID' AND Cash_Bill_Status='cleared'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_check_if_bill_creared_result)<=0){
                        ?>
                        <td>
                            <a href="#" onclick="verify_if_this_user_login_to_dispencing()" id="Add_Consumable_Phamacetical" class="art-button-green">  ADD CONSUMABLE AND PHAMACETICAL</a>
                        </td>
                        <td>
                             <input type="button" name="Add_Item" id="Add_Item" value="ADD SERVICES" class="art-button-green" onclick="Add_More_Items(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                        </td>
                        <?php 
                            }
                        ?>
                    </tr>
                </table>
			<?php
        		//Check if patient has cash bill
        		if($Transaction_Type == 'Credit_Bill_Details' &&  strtolower($Cash_Bill_Status) == 'pending'){
        			$slct = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments pp where
        									Registration_ID = '$Registration_ID' and
        									Folio_Number = '$Folio_Number' and
        									Check_In_ID = '$Check_In_ID' and
        									pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' )  and
        									Patient_Bill_ID = '$Patient_Bill_ID'   limit 1") or die(mysqli_error($conn));
        			$n_slct = mysqli_num_rows($slct);
        			if($n_slct > 0 && strtolower($Cash_Bill_Status) == 'pending'){
        				$Cont_Val = strtolower($Cash_Bill_Status);
        				echo "<span id=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='color: red;'>ALERT!!.. Selected patient has pending cash bill</b></span>";
        			}
        		}
        	?>