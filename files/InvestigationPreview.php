<?php 
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

    if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = '';
    }

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
            if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                @session_start();
                if (!isset($_SESSION['supervisor'])) {
                    header("Location: ./supervisorauthentication.php?{$Section_Link}InvalidSupervisorAuthentication=yes");
                }
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo']['Employee_Name'])){
    	$Emp_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
    	$Emp_Name = '';
    }
    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
    $Supervisor_Name = $_SESSION['supervisor']['Employee_Name'];

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    if (isset($_GET['Registration_ID'])) {
        $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp where
										pr.Sponsor_ID = sp.Sponsor_ID and
										pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);

        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_Patient)) {
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
            }


            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

        } else {
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
    }

    //get consu
    $slct = mysqli_query($conn,"select Employee_Name, ilc.Payment_Date_And_Time from tbl_employee emp, tbl_item_list_cache ilc where
                            emp.Employee_ID = ilc.Consultant_ID and
                            ilc.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    $slct_no = mysqli_num_rows($slct);
    if($slct > 0){
        while ($dt = mysqli_fetch_array($slct)) {
            $Consulting_Doctor = $dt['Employee_Name'];
        }
    }else{
        $Consulting_Doctor = '';
    }
    
    $slct = mysqli_query($conn,"select Employee_Name, ilc.Payment_Date_And_Time,Billing_Type,ilc.Transaction_Type from tbl_employee emp, tbl_item_list_cache ilc ,tbl_payment_cache pc where
                                                        emp.Employee_ID = ilc.Consultant_ID and
                                                        pc.payment_cache_ID=ilc.payment_cache_ID and
                                                        ilc.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
$slct_no = mysqli_num_rows($slct);

if ($slct > 0) {
   $dt = mysqli_fetch_assoc($slct);
        $Billing_Type = $dt['Billing_Type'];
        $Transaction_Type = $dt['Transaction_Type'];
   
}
    
    //get invoice details
    $invoice_details = mysqli_query($conn,"select Pre_Paid, Folio_Number, Receipt_Date, Claim_Form_Number, Employee_Name from tbl_patient_payments pp, tbl_employee emp where 
    								pp.Patient_Payment_ID = '$Patient_Payment_ID' and
    								pp.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
    $num_invouce = mysqli_num_rows($invoice_details);
    if($num_invouce > 0){
    	while ($data = mysqli_fetch_array($invoice_details)) {
    		$Folio_Number = $data['Folio_Number'];
    		$Receipt_Date = $data['Receipt_Date'];
    		$Claim_Form_Number = $data['Claim_Form_Number'];
    		$Prepared_By = $data['Employee_Name'];
            $Pre_Paid = $data['Pre_Paid'];
    	}
    }else{
		$Folio_Number = '';
		$Receipt_Date = '0000-00-00';
		$Claim_Form_Number = '';
		$Prepared_By = '';
        $Pre_Paid  = 0;
    }
    
    $htm = "<table width ='100%' height = '30px'>
			    <tr><td>
				<center><img src='./branchBanner/branchBanner.png' style='height:100px;width:50%'></center>
			    </td></tr>
			    <tr><td><hr></td></tr>";
    if(strtolower($Billing_Type) == 'outpatient cash' && $Pre_Paid == 1){
        $htm .= "<tr><td style='text-align: center;'><span style='font-size: small;'><b>".$Transaction_Type." ~ Outpatient Bill</b></span></td></tr>";
    }else{
	    $htm .= "<tr><td style='text-align: center;'><span style='font-size: small;'><b>".$Transaction_Type." Bill</b></span></td></tr>";
    }
	$htm .= "</table>";
	
	$htm .= '<table width="100%">
				<tr>
					<td width="15%"><span style="font-size: small;"><span style="font-size: small;">Invoice  Number</span></td>
					<td width="1%">:</td><td width="30%"><span style="font-size: small;">'.$Patient_Payment_ID.'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Location</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">Revenue Center</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Invoice  Date</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">'.$Receipt_Date.'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Patient Age</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">'.$age.'</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Claim Form Number</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">'.$Claim_Form_Number.'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Sponsor Name</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">'.$Guarantor_Name.'</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Membership  No</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">'.$Member_Number.'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Session Supervisor</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">'.strtoupper($Supervisor_Name).'</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Patient Name</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">'.strtoupper($Patient_Name).'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Gender</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">'.$Gender.'</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Consulting Doctor</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">'.strtoupper($Consulting_Doctor).'</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Folio Number</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">'.$Folio_Number.'</span></td>
				</tr>
                                <tr>
					<td width="15%"><span style="font-size: small;">Patient Number</span></td><td width="1%">:</td>
					<td width="30%" colspan="2"><span style="font-size: small;">' . $Registration_ID . '</span></td></td>
				</tr>
			</table><br/>';

			//get transactions
			$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_items i where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											ppl.Item_ID = i.Item_ID and
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
			$num = mysqli_num_rows($get_categories);
			if($num > 0){
				$Grand_Total = 0;
				while ($row = mysqli_fetch_array($get_categories)) {
					$Item_Category_ID = $row['Item_Category_ID'];
					$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">';
					$htm .= '<tr><td colspan="4"><b><span style="font-size: small;">'.strtoupper($row['Item_Category_Name']).'</span></b></td></tr>';
					//get transactions based on Item_Category_ID
					$get_details = mysqli_query($conn,"select Product_Name, Price, Quantity, Discount from
												tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_items i where
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												ppl.Item_ID = i.Item_ID and
												isc.Item_Category_ID = '$Item_Category_ID' and
												ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
					$num_get_details = mysqli_num_rows($get_details);
					if($num > 0){
						$temp = 0;
						$Sub_Total = 0;
						$htm .= '<tr>
									<td width="4%"><b><span style="font-size: small;">No</span></b></td>
									<td><b><span style="font-size: small;">Particular</span></b></td>
									<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Quantity</span></b></td>
									<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Amount</span></b></td>
								</tr>';
						while ($dtz = mysqli_fetch_array($get_details)) {
							$htm .= '<tr>
										<td><span style="font-size: small;">'.++$temp.'</span></td>
										<td><span style="font-size: small;">'.$dtz['Product_Name'].'</span></td>
										<td style="text-align: right;"><span style="font-size: small;">'.$dtz['Quantity'].'</span></td>
										<td style="text-align: right;"><span style="font-size: small;">'.number_format(($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']).'</span></td>
									</tr>';
							$Sub_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
							$Grand_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
						}
					}
					$htm .= '<tr>
									<td colspan="3"><b><span style="font-size: small;">Sub Total</span></b></td>
									<td style="text-align: right;"><b><span style="font-size: small;">'.number_format($Sub_Total).'</span></b></td>
								</tr>';
					$htm .= '</table><br/>';
				}
			}

			$htm .= '<br/><table width="100%">
							<tr>
							<td><b>GRAND TOTAL : '.number_format($Grand_Total).'</b></td>
							</tr>
						</table>';

	$htm .= '<br/><b><span style="font-size: small;">Employee Signature </span></b>______________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b><span style="font-size: small;">Patient Signature</span></b>______________________________<br/>';
	$htm .= '<b><span style="font-size: small;">Prepared By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.strtoupper($Prepared_By).'</span>	</b>';

    include("./MPDF/mpdf.php");
//    $mpdf=new mPDF('','', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>