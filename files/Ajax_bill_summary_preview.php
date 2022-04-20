<?php
session_start();
include("./includes/connection.php");
if(isset($_POST['billsummarypreview'])){
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Emp_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Emp_Name = '';
}

if (isset($_POST['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_POST['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = '';
}


if (isset($_POST['Folio_Number'])) {
    $Folio_Number = $_POST['Folio_Number'];
} else {
    $Folio_Number = '';
}


if (isset($_POST['Sponsor_ID'])) {
    $Sponsor_ID = $_POST['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}


if (isset($_POST['Check_In_ID'])) {
    $Check_In_ID = $_POST['Check_In_ID'];
} else {
    $Check_In_ID = '';
}


if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID = '';
}


if (isset($_POST['Transaction_Type'])) {
    $Transaction_Type = $_POST['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
    $payments_filter = "pp.payment_type = 'post' and ";
} else {
    $payments_filter = '';
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

if (isset($_POST['Status']) && $_POST['Status'] == 'cld') {
    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name,sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp where
							cd.Admission_ID = ad.Admision_ID and
							
							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							(ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
} else {
    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp where
							cd.Admission_ID = ad.Admision_ID and
							
							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							(ad.Admission_Status = 'Pending' or ad.Admission_Status = 'Admitted') and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
}
$num = mysqli_num_rows($select);
$htm="";
$num = mysqli_num_rows($select);

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Registration_ID = $data['Registration_ID'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Member_Number = $data['Member_Number'];
        $Payment_Method = $data['payment_method'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Employee_Name = $data['Employee_Name'];
        $Admission_Date_Time = $data['Admission_Date_Time'];
        $Bed_Name = $data['Bed_Name'];
        $Hospital_Ward_Name = $data['Hospital_Ward_Name'];
        $Sponsor_ID = $data['Sponsor_ID'];
        $Cash_Bill_Status = $data['Cash_Bill_Status'];
        $Credit_Bill_Status = $data['Credit_Bill_Status'];
        $Admision_ID = $data['Admision_ID'];
        $NoOfDay = $data['NoOfDay'];
        

        //calculate age
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    }
} 


    $htm .= '<table class="table table-responsive table-condensed table-striped">
			<tr>
				<td><span style="font-size: x-small;"><b>Patient Name &nbsp;&nbsp;&nbsp;</b>' . ucwords(strtolower($Patient_Name)) . '</span></td>
				<td><span style="font-size: x-small;"><b>Patient Number &nbsp;&nbsp;&nbsp;</b>' . $Registration_ID . '</span></td>
				<td><span style="font-size: x-small;"><b>Member Number &nbsp;&nbsp;&nbsp;</b>' . $Member_Number . '</span></td>
			</tr>
			<tr>
				<td><span style="font-size: x-small;"><b>Gender &nbsp;&nbsp;&nbsp;</b>' . $Gender . '</span></td>
				<td><span style="font-size: x-small;"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b>' . strtoupper($Guarantor_Name) . '</span></td>
				<td><span style="font-size: x-small;"><b>Folio Number &nbsp;&nbsp;&nbsp;</b>' . $Folio_Number . '</span></td>
			</tr>
			<tr>
				<td><span style="font-size: x-small;"><b>Admission Date &nbsp;&nbsp;&nbsp;</b>' . $Admission_Date_Time . '</span></td>
				<td><span style="font-size: x-small;"><b>Admitted By &nbsp;&nbsp;&nbsp;</b>' . $Employee_Name . '</span></td>
			</tr>
		</table>';

    //get categories
    $Grand_Total = 0;
    if ($Transaction_Type == 'Cash_Bill_Details') {
        $get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								$payments_filter
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.payment_type <> 'pre' and
								(pp.Billing_Type = 'Inpatient Cash') and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Transaction_status <> 'cancelled' and
								 
								pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
    } else if ($Transaction_Type == 'Credit_Bill_Details') {
        $get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Transaction_status <> 'cancelled' and
								 
								pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
    }
    $num = mysqli_num_rows($get_cat);
    if ($Transaction_Type == 'Cash_Bill_Details') {
        $htm .= "<span style='font-size: x-small;'><b>INPATIENT INVOICE</b></span><br/>";
        $htm .= "<span style='font-size: x-small;'><b>BILL TYPE ~ CASH BILL</b></span><br/><br/>";
    } else if ($Transaction_Type == 'Credit_Bill_Details') {
        $htm .= "<span style='font-size: x-small;'><b>INPATIENT INVOICE</b></span><br/>";
        $htm .= "<span style='font-size: x-small;'><b>BILL TYPE ~ CREDIT BILL</b></span><br/><br/>";
    }

    if ($num > 0) {
        $temp_cat = 0;
        $htm .= "<span style='font-size: x-small;'><b>BILL DETAILS</b></span>";
        while ($row = mysqli_fetch_array($get_cat)) {
            $Item_category_ID = $row['Item_category_ID'];
            $htm .= '<table class="table table-responsive table-condensed table-hover" width=100% border=1 style="border-collapse: collapse;">';
            $htm .= "<thead><tr><td colspan='8' style='font-size: stong;background:#ccc; text-align:center;'>" . ++$temp_cat . '. ' . strtoupper($row['Item_Category_Name']) . "</td></tr>";


            $htm .= '<tr>
					<td width="4%"><span style="font-size: x-small;">SN</span></td>
					<td  width="23%"><span style="font-size: x-small;">ITEM NAME</span></td>
                    
					<td width="7%" style="text-align: center;"><span style="font-size: x-small;">RECEIPT#</span></td>
                    <td width="13%" style="text-align: center;"><span style="font-size: x-small;">TRANSACTION DATE#</span></td>
                    <td  width="13%"><span style="font-size: x-small;">ADDED BY</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">PRICE</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">DISCOUNT</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">QUANTITY</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">SUB TOTAL</span></td>
				</tr></thead>';

            if ($Transaction_Type == 'Cash_Bill_Details') {
                $items = mysqli_query($conn,"select i.Product_Name,pp.Employee_ID, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
									tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									ic.Item_Category_ID = isc.Item_Category_ID and
									isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
									i.Item_ID = ppl.Item_ID and
									pp.Transaction_type = 'indirect cash' and
									pp.Billing_Type = 'Inpatient Cash' and
									pp.payment_type <> 'pre' and
									$payments_filter
									pp.Transaction_status <> 'cancelled' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									pp.Patient_Bill_ID = '$Patient_Bill_ID' and
									ic.Item_category_ID = '$Item_category_ID' and
									 
									pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            } else if ($Transaction_Type == 'Credit_Bill_Details') {
                $items = mysqli_query($conn,"select i.Product_Name,pp.Employee_ID, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
									tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									ic.Item_Category_ID = isc.Item_Category_ID and
									isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
									i.Item_ID = ppl.Item_ID and
									pp.Transaction_type = 'indirect cash' and
									pp.Transaction_status <> 'cancelled' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
									pp.Patient_Bill_ID = '$Patient_Bill_ID' and
									ic.Item_category_ID = '$Item_category_ID' and
									 
									pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            }

            $nm = mysqli_num_rows($items);
            if ($nm > 0) {
                $temp = 0;
                $Sub_Total = 0;
                while ($dt = mysqli_fetch_array($items)) {
                    $Employee_ID = $dt['Employee_ID'];
                    $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID' "))['Employee_Name'];
                    $htm .= '<tr>
							<td width="4%"><span style="font-size: x-small;">' . ++$temp . '<b>.</b></span></td>
							<td><span style="font-size: x-small;">' . ucwords(strtolower($dt['Product_Name'])) . '</span></td>
                            
							<td width="10%" style="text-align: center"><span style="font-size: x-small;">' . $dt['Patient_Payment_ID'] . '</span></td>
							<td width="10%" style="text-align: center"><span style="font-size: x-small;">' . $dt['Payment_Date_And_Time'] . '</span></td>
                            <td><span style="font-size: x-small;">' . ucwords(strtolower($Employee_Name)) . '</span></td>
							<td width="10%" style="text-align: right"><span style="font-size: x-small;">' . number_format($dt['Price']) . '</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format($dt['Discount']) . '</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . $dt['Quantity'] . '</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</span></td>
						</tr>';
                    $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                    $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                }
                $htm .= "<tr>
							<td colspan='6' style='text-align: right;'>
								<span style='font-size: x-small;'><b>SUB TOTAL</b></span></td><td style='text-align: right;'>
								<span style='font-size: x-small;'><b>" . number_format($Sub_Total) . "</b></span>
							</td>
						</tr>";
            }
            $htm .= "</table>";
        }

        $htm .= '<table class="table table-responsive table-condensed table-hover" width=100% border=1 style="border-collapse: collapse;">';
        $htm .= '<tr>
					<td width="90%" style="text-align: right;">
						<span style="font-size: x-small;"><b>GRAND TOTAL</b></span>
					</td>
					<td style="text-align: right;">
						<span style="font-size: x-small;"><b>' . number_format($Grand_Total) . '</b></span></td></tr>';
        $htm .= '</table>';
    }



    $htm .= '<span style="font-size: stong; background:#ccc; text-align:center;"><b>BILL SUMMARY</b></span>
		<table class="table table-responsive table-condensed table-hover" width=100% border=1 style="border-collapse: collapse;">
			<thead><tr style="font-size: stong; background:#ccc; text-align:left;">
				<td width="5%"><span style="font-size: x-small;">SN</span></td>
				<td><span style="font-size: x-small;">CATEGORY NAME</span></td>
				<td width="20%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
			</tr></thead>';

    if ($Transaction_Type == 'Cash_Bill_Details') {
        $get_cat = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								(pp.Billing_Type = 'Inpatient Cash') and
								pp.payment_type <> 'pre' and
								$payments_filter
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Transaction_status <> 'cancelled' and
								 
								pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
    } else if ($Transaction_Type == 'Credit_Bill_Details') {
        $get_cat = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Transaction_status <> 'cancelled' and
								 
								pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
    }
    $nms_slct = mysqli_num_rows($get_cat);
    $tmp = 0;
    $cont = 0;
    $Cat_Grand_Total = 0;
    if ($nms_slct > 0) {
        while ($dts = mysqli_fetch_array($get_cat)) {
            $Item_Category_Name = $dts['Item_Category_Name'];
            $Item_Category_ID = $dts['Item_Category_ID'];
            $Category_Grand_Total = 0;

            //calculate total
            if ($Transaction_Type == 'Cash_Bill_Details') {
                $items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Billing_Type = 'Inpatient Cash' and
								pp.payment_type <> 'pre' and
								$payments_filter
								pp.Transaction_status <> 'cancelled' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								ic.Item_Category_ID = '$Item_Category_ID' and
								 
								pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            } else if ($Transaction_Type == 'Credit_Bill_Details') {
                $items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Transaction_status <> 'cancelled' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								ic.Item_Category_ID = '$Item_Category_ID' and
								 
								pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            }
            $nums = mysqli_num_rows($items);
            if ($nums > 0) {
                while ($t_item = mysqli_fetch_array($items)) {
                    $Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
                    $Cat_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
                }
            }
            $htm .= "<tr><td><span style='font-size: x-small;'>" . ++$cont . '<b>.</b></span></td>
						<td><span style="font-size: x-small;">' . ucwords(strtolower($Item_Category_Name)) . "</span></td>
						<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($Category_Grand_Total) . "</span></td></tr>";
        }
    }


    $htm .= '<tr style="font-size: stong; background:#ccc; text-align:center;"><td colspan="2" style="text-align: right;"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
				<td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($Cat_Grand_Total) . '</b></span></td></tr>
				</table>';
    if ($Transaction_Type == 'Cash_Bill_Details') {

        $htm .= '<span style="font-size: x-small; text-align:center;"><b>ADVANCE PAYMENTS</b></span>
			<table class="table table-responsive table-condensed table-hover" width=100% border=1 style="border-collapse: collapse;">
				<tr style="font-size: stong; background:#ccc; text-align:center;">
					<td width="5%"><span style="font-size: x-small;">SN</span></td>
					<td><span style="font-size: x-small;">ITEM NAME</span></td>
					<td width="12%"><span style="font-size: x-small;">RECEIPT#</span></td>
					<td width="20%"><span style="font-size: x-small;">RECEIPT DATE</span></td>
					<td width="12%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
				</tr>';

        $Direct_Cash_Grand_Total = 0;
        $sn = 0;
        $select = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, i.Product_Name, ppl.Item_Name from 
							tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
							pp.Transaction_type = 'direct cash' and
							ppl.Item_ID = i.Item_ID and
							pp.Transaction_status <> 'cancelled' and
                                                        pp.Billing_Type<>'Outpatient Cash' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							ppl.Item_ID=i.Item_ID and
                                                        i.Visible_Status='Others' and 
							pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            $temp = 0;
            while ($data = mysqli_fetch_array($select)) {

                $htm .= '<tr>
						<td><span style="font-size: x-small;">' . ++$sn . '<b>.</b></span></td>
						<td><span style="font-size: x-small;">' . ucwords(strtolower($data['Product_Name'] . ' ~ ' . $data['Item_Name'])) . '</span></td>
						<td><span style="font-size: x-small;">' . $data['Patient_Payment_ID'] . '</span></td>
						<td><span style="font-size: x-small;">' . $data['Payment_Date_And_Time'] . '</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">' . number_format(($data['Price'] - $data['Discount']) * $data['Quantity']) . '</span></td>
					</tr>';

                $Direct_Cash_Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
            }
        }

        $htm .= '<tr style="font-size: stong; background:#ccc; text-align:center;"><td colspan="4" style="text-align: left;"><span style="font-size: x-small;"><b>TOTAL AMOUNT PAID</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>' . number_format($Direct_Cash_Grand_Total) . '</b></span></td></tr>
				</table>';
    }
    $htm .= '
				<span style="font-size: x-small;"><b>OVERALL SUMMARY</b></span>
					<table class="table table-responsive table-condensed table-hover" width="55%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="65%"><span style="font-size: x-small;">Total Amount Required</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Cat_Grand_Total) . '</span></td>
						</tr>
						<tr>
							<td width="65%"><span style="font-size: x-small;">Total Amount Paid</span></td>
							<td style="text-align: right;">';

    if ($Transaction_Type == 'Cash_Bill_Details') {
        $htm .= '<span style="font-size: x-small;">' . number_format($Direct_Cash_Grand_Total) . '</span>';
    } else {
        $htm .= "<span style='font-size: x-small;'>(<i>Not applicable</i>)</span>";
    }

    $htm .= '</td>
					</tr>
					<tr>
						<td><span style="font-size: x-small;">Balance</span></td>';

    if ($Transaction_Type == 'Cash_Bill_Details') {
        if ($Cat_Grand_Total > $Direct_Cash_Grand_Total) {
            $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Cat_Grand_Total - $Direct_Cash_Grand_Total) . '</span></td>';
        } else {
            $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">0</span></td>';
        }
    } else {
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . number_format($Cat_Grand_Total) . '</span></td>';
    }
    $htm .= '</tr>';

    if (($Transaction_Type == 'Cash_Bill_Details') && ($Cat_Grand_Total < $Direct_Cash_Grand_Total)) {
        $htm .= "<tr>
							<td><span style='font-size: x-small;'>Refund Amount</span></td>
							<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($Direct_Cash_Grand_Total - $Cat_Grand_Total) . "</span></td>
						</tr>";
    }

    $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Bill Status</span></td>';
    if ($Transaction_Type == 'Cash_Bill_Details') {
        //get employee clearded the bill(Cash)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Cash_Bill_Status . '</span></td>';
    } else {
        //get employee clearded the bill(Credit)
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . $Credit_Bill_Status . '</span></td>';
    }
    $htm .= '</tr>';
    if ($Transaction_Type == 'Cash_Bill_Details' && strtolower($Cash_Bill_Status) == 'cleared') {
        //get employee clearded the bill(Cash)
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Cleared By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cash_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Cash_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Cash_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Cash_Clearer)) . '</span></td>';
    } else if ($Transaction_Type == 'Credit_Bill_Details' && strtolower($Credit_Bill_Status) == 'cleared') {
        //get employee clearded the bill(Credit)
        $htm .= '<tr><td width="65%"><span style="font-size: x-small;">Approved By</span></td>';
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Credit_Clearer_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($dtz = mysqli_fetch_array($select)) {
                $Credit_Clearer = $dtz['Employee_Name'];
            }
        } else {
            $Credit_Clearer = '';
        }
        $htm .= '<td style="text-align: right;"><span style="font-size: x-small;">' . ucwords(strtolower($Credit_Clearer)) . '</span></td>';
    }
    $htm .= '</table></td></tr></table>';
}
    echo $htm;
