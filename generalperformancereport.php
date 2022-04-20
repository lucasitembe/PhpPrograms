<?php
    @session_start();
    include("./includes/connection.php");

    $temp = 1;
    $total_cash = 0;
    $total_credit = 0;
    $total_cancelled = 0;
    $Grand_Total = 0;
    $Grand_Total_Cancelled = 0;

    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Session_Master_Priveleges'])){
    	    if($_SESSION['userinfo']['Session_Master_Priveleges'] != 'yes'){
    		  header("Location: ./index.php?InvalidPrivilege=yes");
    	    }
    	}else{
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
        @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['Location'])){
        $Location = $_SESSION['Location'];
    }else{
        $Location = '';
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
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
        $Billing_Type_Value = 'all'; 
    }
    
    if (isset($_GET['Sponsor_ID'])) {
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }

    //get sponsor name
    if($Sponsor_ID != 0){
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                $Guarantor_Name = $data['Guarantor_Name'];
            }
        }else{
            $Guarantor_Name = 'All';
        }
    }else{
        $Guarantor_Name = 'All';
    }
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	   $Date_Time = $row['datetime'];
    } 

    if($Location != '' && $Location != null){

    $htm = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>Performance Report ~ ".$Location."</b></span></td></tr>
		    </table><br/>";

    //get employee details
    $select_details = mysqli_query($conn,"select * from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_details);
    if($num > 0){
    	while($row2 = mysqli_fetch_array($select_details)){
    	    $Employee_Name = $row2['Employee_Name'];
    	}
    }
    
    $htm .= "<table width='100%'>
                <tr>
                    <td style='text-align: left;'><b>Start Date & Time ~ </b>".$Date_From."</td>
                <tr>
                <tr>
                    <td style='text-align: left;'><b>End Date & Time ~ </b>".$Date_To."</td>
                <tr>
                <tr>
                    <td style='text-align: left;'><b>Sponsor ~ </b>".$Guarantor_Name."</td>
                <tr>
                <tr>
                    <td style='text-align: left;'><b>Billing Type ~ </b>".$Billing_Type_Value."</td>
                <tr>
            </table>";

    $htm .= "</table><br/><br/>";
?> 
<?php
    $htm .="<table width=100%>
		<tr><td>Sn</td>
		    <td>Employee Name</td>
		    <td style='text-align: right;'>Cash</td>
		    <td style='text-align: right;'>Credit</td>
		    <td style='text-align: right;'>Cancelled</td>
        </tr><tr><td colspan=7><hr></td></tr>";
	

    //sql statement
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

                        $htm .= "<tr>
                                <td>".$temp."</td>
                                <td>".$Employee_Name."</td>
                                <td style='text-align: right;'>".number_format($total_cash)."</td>
                                <td style='text-align: right;'>".number_format($total_credit)."</td>
                                <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                            </tr>";
                            
                            $Grand_Total = $Grand_Total + ($total_cash + $total_credit);
                            
                            $Grand_Total_Cancelled = $Grand_Total_Cancelled + ($total_cancelled);
                            $Grand_Total_Cash = $Grand_Total_Cash + $total_cash;
                            $Grand_Total_Credit = $Grand_Total_Credit + $total_credit;

                            $temp++;
                            $total_cash = 0;
                            $total_credit = 0;
                            $total_cancelled = 0;
                    }
                }
            }

            $htm .= "<tr><td colspan='5'><hr></td></tr>";

            $htm .= "<tr>
                        <td colspan='2'><b>TOTAL</b></td>
                        <td style='text-align: right;'><b>".number_format($Grand_Total_Cash)."</b></td>
                        <td style='text-align: right;'><b>".number_format($Grand_Total_Credit)."</b></td>
                        <td style='text-align: right;'><b>".number_format($Grand_Total_Cancelled)."</b></td>
                    </tr>";
            $htm .= "<tr><td colspan='5'><hr></td></tr>";
            $htm .= "<tr><td style='text-align: left;' colspan='2'><b>GRAND TOTAL</b></td><td colspan='3' style='text-align: right;'><b>".number_format($Grand_Total)."</b></td></td></tr></table>";
?>
    

<?php


    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
?>


<?php
    $htm .= "</table>";
}else{
    $htm = "<br/><br/><center><b>NO LOCATION DETECTED</b></center>";
}
    //echo $htm; exit();

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    
?>

 