<?php

session_start();
include("./includes/connection.php");

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Emp_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Emp_Name = '';
}

if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = '';
}


if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = '';
}


if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}
if(isset($_GET['Transaction_Type'])){
    $Transaction_Type = $_GET['Transaction_Type'];
}else{
    $Transaction_Type ='';
}

if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
    $select = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,
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
    } else {
        $Patient_Name = '';
        $Registration_ID = '';
        $Gender = '';
        $Date_Of_Birth = '';
        $Member_Number = '';
        $Guarantor_Name = '';
        $Employee_Name = '';
        $Admission_Date_Time = '';
        $Bed_Name = '';
        $Hospital_Ward_Name = '';
        $Sponsor_ID = '';
        $Cash_Bill_Status = '';
        $Credit_Bill_Status = '';
        $Credit_Clearer_ID = '';
        $Cash_Clearer_ID = '';
        $Admision_ID='';
    }
    
    $htm .= "<table width ='100%' height = '30px'>
    <tr><td><img src='./branchBanner/branchBanner.png' alt='123' width=100%></td></tr>
    <tr><td>&nbsp;</td></tr></table>";




$htm .= '<table width="100%">
        <tr>
            <td width="33%"><span style="font-size: x-small;"><b>Patient Name &nbsp;&nbsp;&nbsp;</b>' . ucwords(strtolower($Patient_Name)) . '</span></td>
            <td width="33%"><span style="font-size: x-small;"><b>Patient Number &nbsp;&nbsp;&nbsp;</b>' . $Registration_ID . '</span></td>
            <td width="33%"><span style="font-size: x-small;"><b>Member Number &nbsp;&nbsp;&nbsp;</b>' . $Member_Number . '</span></td>
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
    </table><br/>';
    
//get categories
$Grand_Total = 0;
if ($Transaction_Type == 'Cash_Bill_Details') {
    $get_cat = mysqli_query($conn,"SELECT ic.Item_category_ID, ic.Item_Category_Name from 
                            tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                            ic.Item_Category_ID = isc.Item_Category_ID and
                            isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            i.Item_ID = ppl.Item_ID and
                            pp.Transaction_type = 'indirect cash' and   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pp.payment_type <> 'pre' and pp.Check_In_ID='$Check_In_ID' and
                            (pp.Billing_Type = 'Inpatient Cash' OR pp.Pre_Paid='1') and
                            pp.Patient_Bill_ID = $Patient_Bill_ID and
                            pp.Transaction_status <> 'cancelled' and
                            pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
} else if ($Transaction_Type == 'Credit_Bill_Details') {
    $get_cat = mysqli_query($conn,"SELECT ic.Item_category_ID, ic.Item_Category_Name from 
                            tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                            ic.Item_Category_ID = isc.Item_Category_ID and
                            isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            i.Item_ID = ppl.Item_ID and pp.Check_In_ID='$Check_In_ID' and
                            pp.Transaction_type = 'indirect cash' and
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                            pp.Patient_Bill_ID = $Patient_Bill_ID and
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
        $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
        $htm .= "<thead><tr><td colspan='8'><span style='font-size: x-small;'>" . ++$temp_cat . '. ' . strtoupper($row['Item_Category_Name']) . "</span></td></tr>";


        $htm .= '<tr>
                <td width="4%"><span style="font-size: x-small;">SN</span></td>
                <td><span style="font-size: x-small;">ITEM NAME</span></td>
                <td width="10%" style="text-align: center;"><span style="font-size: x-small;">RECEIPT#</span></td>
                <td width="10%" style="text-align: center;"><span style="font-size: x-small;">DATE</span></td>
                <td width="10%" style="text-align: right;"><span style="font-size: x-small;">PRICE</span></td>
                <td width="10%" style="text-align: right;"><span style="font-size: x-small;">DISCOUNT</span></td>
                <td width="10%" style="text-align: right;"><span style="font-size: x-small;">QUANTITY</span></td>
                <td width="10%" style="text-align: right;"><span style="font-size: x-small;">SUB TOTAL</span></td>
            </tr></thead>';
        //<!-- <tr><td colspan='7'><hr></td></tr> -->
       
        if ($Transaction_Type == 'Cash_Bill_Details') {
            $items = mysqli_query($conn,"SELECT i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
                                tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                                ic.Item_Category_ID = isc.Item_Category_ID and
                                isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                i.Item_ID = ppl.Item_ID and
                                pp.Transaction_type = 'indirect cash' and
                                (pp.Billing_Type = 'Inpatient Cash' OR pp.Pre_Paid='1')and
                                pp.payment_type <> 'pre' and pp.Check_In_ID='$Check_In_ID' and
                                
                                pp.Transaction_status <> 'cancelled' and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                pp.Patient_Bill_ID = $Patient_Bill_ID and
                                ic.Item_category_ID = '$Item_category_ID' and
                                 
                                pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        } else if ($Transaction_Type == 'Credit_Bill_Details') {
            $items = mysqli_query($conn,"SELECT i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
                                tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                                ic.Item_Category_ID = isc.Item_Category_ID and
                                isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                i.Item_ID = ppl.Item_ID and
                                pp.Transaction_type = 'indirect cash' and pp.Check_In_ID='$Check_In_ID' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                                pp.Patient_Bill_ID = $Patient_Bill_ID and
                                ic.Item_category_ID = '$Item_category_ID' and
                                 
                                pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        }

        $nm = mysqli_num_rows($items);
        if ($nm > 0) {
            $temp = 0;
            $Sub_Total = 0;
            while ($dt = mysqli_fetch_array($items)) {
                $Payment_Date_And_Time = $dt['Payment_Date_And_Time'];
                
                $htm .= '<tr>
                        <td width="4%"><span style="font-size: x-small;">' . ++$temp . '<b>.</b></span></td>
                        <td><span style="font-size: x-small;">' . ucwords(strtolower($dt['Product_Name'])) . '</span></td>
                        <td width="10%" style="text-align: center"><span style="font-size: x-small;">' . $dt['Patient_Payment_ID'] . '</span></td>
                        <td width="10%" style="text-align: center"><span style="font-size: x-small;">' . $dt['Payment_Date_And_Time'] . '</span></td>
                        <td width="10%" style="text-align: right"><span style="font-size: x-small;">' . number_format($dt['Price']) . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format($dt['Discount']) . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . $dt['Quantity'] . '</span></td>
                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</span></td>
                    </tr>';
                $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
            }
            //$htm .= "<tr><td colspan='7'><hr></td></tr>";
            $htm .= "<tr>
                        <td colspan='7' style='text-align: right;'>
                            <span style='font-size: x-small;'><b>SUB TOTAL</b></span></td><td style='text-align: right;'>
                            <span style='font-size: x-small;'><b>" . number_format($Sub_Total) . "</b></span>
                        </td>
                    </tr>";
        }
        $htm .= "</table><br/>";
    }

    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
    $htm .= '<tr>
                <td width="90%" style="text-align: right;">
                    <span style="font-size: x-small;"><b>GRAND TOTAL</b></span>
                </td>
                <td style="text-align: right;">
                    <span style="font-size: x-small;"><b>' . number_format($Grand_Total) . '</b></span></td></tr>';
    $htm .= '</table>';
}


include("./MPDF/mpdf.php");
$mpdf = new mPDF('', '', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('Printed By ' . strtoupper($Emp_Name) . '|{PAGENO}/{nb}|{DATE d-m-Y}' .'             Powered by GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
