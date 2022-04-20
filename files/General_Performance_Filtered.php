<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type_Value = $_GET['Billing_Type'];
    }else{
        $Billing_Type_Value = '';
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }

    if(isset($_SESSION['Location'])){
        $Location = $_SESSION['Location'];
    }else{
        $Location = '';
    }
?>
<?php if($Location != '' && $Location != null){ ?>
<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>General Performance ~ <?php if(isset($_SESSION['Location'])){ echo ucwords(strtolower($_SESSION['Location'])); } ?> </b></legend>
<center>
    <table width=100% border=1>
        <?php
            if(isset($_SESSION['Pharmacy'])){
                $Sub_Department_Name = $_SESSION['Pharmacy'];
                $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
                while($row = mysqli_fetch_array($select_sub_department)){
                    $Sub_Department_ID = $row['Sub_Department_ID'];
                }
            }else{
                $Sub_Department_ID = '';
            }
        ?>
        <?php
            $temp = 1;
            $total_cash = 0;
            $total_credit = 0;
            $total_cancelled = 0;
            
            echo "<tr id='thead'>
			    <td width=5%><b>SN</b></td>
			    <td><b>EMPLOYEE NAME</b></td>
			    <td width='12%'style='text-align: right;'><b>CASH</b></td>
			    <td width='12%'style='text-align: right;'><b>CREDIT</b></td>
			    <td width='12%'style='text-align: right;'><b>CANCELED</b></td>
			    <td width='12%'style='text-align: right;'><b>TOTAL COLLECTED</b></td>
			</tr>";
	    echo '<tr><td colspan="6"><hr></td></tr>';
            



    //get employee details (Cashiers)
    if($Sponsor_ID == 0){
        if($Employee_ID == 0){    
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){                    
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }else{
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            ppl.Status <> 'Removed' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            ppl.Status <> 'Removed' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }
    }else{
        if($Employee_ID == 0){    
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));

            }else if($Billing_Type_Value == 'Outpatient'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            i.Item_ID = ppl.Item_ID and
                                            ppl.Status <> 'Removed' and
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }else{
            if($Billing_Type_Value == 'All'){
                            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                pp.Employee_ID = emp.Employee_ID and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                ppl.Status <> 'Removed' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                pp.Sponsor_ID = '$Sponsor_ID' and
                                emp.Employee_ID = '$Employee_ID' and
                                i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));

            }else if($Billing_Type_Value == 'Outpatient'){
                            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                pp.Employee_ID = emp.Employee_ID and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                ppl.Status <> 'Removed' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                pp.Sponsor_ID = '$Sponsor_ID' and
                                emp.Employee_ID = '$Employee_ID' and
                                i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                                tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
                                pp.Employee_ID = emp.Employee_ID and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                ppl.Status <> 'Removed' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                pp.Sponsor_ID = '$Sponsor_ID' and
                                emp.Employee_ID = '$Employee_ID' and
                                i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }

    }        
            $num = mysqli_num_rows($select_details);
            
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Employee_ID = $row['Employee_ID']; //cashier id
                    $Employee_Name = ucwords(strtolower($row['Employee_Name'])); //cashier name
                    
                    //filter all transactions based on selected cashier
                    
                    if($Sponsor_ID == 0){
                        if($Billing_Type_Value == 'All'){
                            $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                            $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }
                    }else{
                        if($Billing_Type_Value == 'All'){
                            $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                        $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                i.Item_ID = ppl.Item_ID and
                                                ppl.Status <> 'Removed' and
                                                i.Consultation_Type = '$Location' and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        }
                    }

                    $num_rows = mysqli_num_rows($select);
                    
                    
                    if($num_rows > 0){
                        while($data = mysqli_fetch_array($select)){
                            //calculate total
                            $Billing_Type = $data['Billing_Type'];
                            
                            $Transaction_status = $data['Transaction_status'];
                            $Price = $data['Price'];
                            $Quantity = $data['Quantity'];
                            $Discount = $data['Discount'];
                            
                            $total = (($Price - $Discount) * $Quantity);
                            
                            if(strtolower($Transaction_status) == 'cancelled'){
                                $total_cancelled = $total_cancelled + $total;
                            }else{
                                if(strtolower($Billing_Type) == 'outpatient cash' or strtolower($Billing_Type) == 'inpatient cash'){
                                    $total_cash = $total_cash + $total;
                                }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit'){
                                    $total_credit = $total_credit + $total;
                                }
                            }
                        }
                    }
        ?>
                <tr>
                    <td><?php echo $temp; ?></td>
                    <td><b><?php echo $Employee_Name; ?></b></td>
                    <td style='text-align: right;'><b><?php echo number_format($total_cash); ?></b></td>
                    <td style='text-align: right;'><b><?php echo number_format($total_credit); ?></b></td>
                    <td style='text-align: right;'><b><?php echo number_format($total_cancelled); ?></b></td>
                    <td style='text-align: right;'><b><?php echo number_format($total_cash + $total_credit); ?></b></td>
                </tr>
            <?php
                    $temp++;
                    $total_cash = 0;
                    $total_credit = 0;
                    $total_cancelled = 0;
                }
            }
            echo '</table>';
        ?>
    </td>
</tr>
    </table>
</center>
<?php 
    }else{ 
        echo "<br/><br/><br/><center><b><h3>No Location selected</h3></b></center>";
    } 
?>