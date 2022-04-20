<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $GrandTotal = 0;
    $total = 0;
    $sub_Total = 0;
    $patient_number = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Quality_Assurance'])){
	    if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
 	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php

    //get all details from the URL
    //Branch
    if(isset($_GET['Branch'])){
        $Branch = $_GET['Branch'];
    }else{
        $Branch = '';
    } 
    //Date From
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = '';
    }
    //Date to
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    //Insurance
    if(isset($_GET['Insurance'])){
        $Insurance = $_GET['Insurance'];
    }else{
        $Insurance = '';
    }
    //Payment_Type
    if(isset($_GET['Payment_Type'])){
        $Payment_Type = $_GET['Payment_Type'];
    }else{
        $Payment_Type = '';
    }
    //Patient_Type
    if(isset($_GET['Patient_Type'])){
        $Patient_Type = $_GET['Patient_Type'];
    }else{
        $Patient_Type = '';
    }
$htm = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr></table>";

?>

<?php
    //based on parameter we select all insurances and display details (General)
    if(strtolower($Insurance) == 'all'){
        $select_Insurances = "select pp.Sponsor_Name from tbl_patient_payments pp
                                where pp.receipt_date between '$Date_From' and '$Date_To'
                                        GROUP BY  pp.sponsor_name order by pp.sponsor_name";
                                
    }else{
         $select_Insurances = "select pp.Sponsor_Name from tbl_patient_payments pp
                                where pp.receipt_date between '$Date_From' and '$Date_To' and
                                        pp.sponsor_name = '$Insurance'
                                        GROUP BY  pp.sponsor_name order by pp.sponsor_name";     
    }
    $htm .= "<table width=100%>";
    $htm .= "<tr><td colspan=9><hr height = '3px'></td></tr>";
    $Result = mysqli_query($conn,$select_Insurances) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($Result)){
        $Sponsor_Name = $row['Sponsor_Name']; 
        $htm .= '<tr><td colspan=4><b>Sponsor Name : '.$Sponsor_Name.'</b></td></tr>';
        
	if(strtolower($Branch) == 'all'){
	    //check Patient Type
		if(strtolower($Patient_Type) == 'all'){ //all outpatient and inpatient
		    if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    pp.Sponsor_Name = '$Sponsor_Name'
						group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
		    }elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'outpatient cash' or pp.Billing_Type = 'inpatient cash') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			
		    }else{ //credit only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'outpatient credit' or pp.Billing_Type = 'inpatient credit') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));                
		    }
		}elseif(strtolower($Patient_Type) == 'outpatient'){ //outpatient only
		    
		    if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'outpatient cash' or pp.Billing_Type = 'outpatient credit') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
		    }elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'outpatient cash') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			
		    }else{ //credit only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'outpatient credit') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));                
		    }
		    
		}else{ //inpatient only
		    
		    if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'inpatient cash' or pp.Billing_Type = 'inpatient credit') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
		    }elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'inpatient cash') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			
		    }else{ //credit only
			$select_Patient_Details = mysqli_query($conn,"
			    select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				tbl_patient_registration pr, tbl_patient_payments pp
				    where pr.registration_id = pp.registration_id and
					pp.receipt_date between '$Date_From' and '$Date_To' and
					    (pp.Billing_Type = 'inpatient credit') and
						pp.Sponsor_Name = '$Sponsor_Name'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
		    }
		    
        }
	}else{
		    //select all information based on Branch - (selected)
		    //check Patient Type
		    if(strtolower($Patient_Type) == 'all'){ //all outpatient and inpatient
			if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						pp.Sponsor_Name = '$Sponsor_Name' and
						    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			}elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'outpatient cash' or pp.Billing_Type = 'inpatient cash') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			    
			}else{ //credit only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'outpatient credit' or pp.Billing_Type = 'inpatient credit') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));                
			}
		    }elseif(strtolower($Patient_Type) == 'outpatient'){ //outpatient only
			
			if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'outpatient cash' or pp.Billing_Type = 'outpatient credit') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			}elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'outpatient cash') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			    
			}else{ //credit only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'outpatient credit') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));                
			}
			
		    }else{ //inpatient only
			
			if(strtolower($Payment_Type) == 'all'){ //all cash and credit
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'inpatient cash' or pp.Billing_Type = 'inpatient credit') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			}elseif(strtolower($Payment_Type) == 'cash'){ //cash only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'inpatient cash') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
			    
			}else{ //credit only
			    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp
					where pr.registration_id = pp.registration_id and
					    pp.receipt_date between '$Date_From' and '$Date_To' and
						(pp.Billing_Type = 'inpatient credit') and
						    pp.Sponsor_Name = '$Sponsor_Name' and
							pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
							group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));                
			}
			
		    }
	}
       
        //display all items 
        while($row2 = mysqli_fetch_array($select_Patient_Details)){
            $Folio_Number = $row2['Folio_Number'];  
            $htm .= "<tr><td width=5%><b>(".$patient_number.")</b></td>
		    <td width='15%'><b>Patient :</b> ".$row2['Patient_Name']."</td>
		    <td width='20%'><b>Folio Number :</b> ".$row2['Folio_Number']."</td>
		 ";
            $htm .= " 
		    <td width='20%'><b>Patient N<u>o</u> :</b>".$row2['Registration_ID']."</td>
		    <td width='30%'><b>Sponsor :</b>".$row2['Sponsor_Name']."</td>
		</tr>";
            $htm .= "<tr><td colspan=9><hr height = '3px'></td></tr>";
            /* $htm .= "<tr><td colspan=4><hr height = '3px'></td></tr>";
            $htm .= "<tr><td colspan=4>".$Folio_Number."</td></tr>";
            $htm .= "<tr><td colspan=4>".$Sponsor_Name."</td></tr>";
            $htm .= "<tr><td colspan=4><hr height = '3px'></td></tr>";
            */
            
             
                                                    
                                                    
            $htm .= "
                <tr>
                     
                                <td width=4%><b>SN</b></td>
                                <td width=7%><b>Category</b></td>
                                <td width=7%><b>Receipt N<u>o</u></b></td>
                                <td width=15%><b>Item / Service</b></td> 
                                <td width=15% style='text-align: center;'><b>Claim Form N<u>o</u></b></td> 
                                <td width=15% style='text-align: right;'><b>Price</b></td>
                                <td width=15% style='text-align: right;'><b>Quantity</b></td>
                                <td width=15% style='text-align: right;'><b>Discount</b></td>
                                <td width=10% style='text-align: right;'><b>Amount</b></td>
                             
                     
                </tr>";
            
            
            $results = mysqli_query($conn,"
                    select
                    ic.Item_Category_Name, pp.Patient_Payment_ID, t.Product_Name, pp.Claim_Form_Number, pp.Receipt_Date, ppl.Price, ppl.Quantity, ppl.Discount
                    from
			tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
			    tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				    pr.registration_id = pp.registration_id and
					e.employee_id = pp.employee_id and
					    ic.item_category_id = ts.item_category_id and
						ts.item_subcategory_id = t.item_subcategory_id and
						    t.item_id = ppl.item_id and
                            pp.	Sponsor_ID = (select Sponsor_ID from tbl_Patient_Payments where sponsor_name = '$Sponsor_Name' limit 1) and
                                pp.Folio_Number = '$Folio_Number'  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
	    while($row3 = mysqli_fetch_array($results)){
                $htm .= "<tr><td>".$temp."</td>";
                $htm .= "<td>".$row3['Item_Category_Name']."</td>";
                $htm .= "<td>".$row3['Patient_Payment_ID']."</td>";
                $htm .= "<td>".$row3['Product_Name']."</td>";
                $htm .= "<td style='text-align: center;'>".$row3['Claim_Form_Number']."</td>";
                $htm .= "<td style='text-align: right;'>".number_format($row3['Price'])."</td>";
                $htm .= "<td style='text-align: right;'>".$row3['Quantity']."</td>"; 
                $htm .= "<td style='text-align: right;'>".number_format($row3['Discount'])."</td>";
                $total = $total + (($row3['Price'] - $row3['Discount'])*$row3['Quantity']);
                $sub_Total = $sub_Total + $total;
                $htm .= "<td style='text-align: right;'>".number_format($total)."</td></tr>"; 
                $temp++;
                $total = 0;
            }
            
	    $temp = 1;
	$htm .= "<tr><td colspan=9><hr height = '3px'></td></tr>";
	$htm .= "<tr><td colspan=9 style='text-align: right;'><span style='font-size: x-small;'><b>Sub Total : ".number_format($sub_Total)."</b></span></td></tr>";
	$GrandTotal = $GrandTotal + $sub_Total;
        $sub_Total = 0;
        $htm .= "<tr><td colspan=9><hr height = '3px'></td></tr>";
	$total = 0;
	$patient_number++;
        
        }
        $patient_number = 1;
    }$htm .= "<tr><td colspan=9 style='text-align: right;'><b>Grand Total ".number_format($GrandTotal)."</b></td></tr>";
?>  
 
    

<?php
    $htm .= '</table>';
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>

  