<?php
session_start();
include("./includes/connection.php");

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
/*if (isset($_SESSION['Pharmacy'])) {
    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}*/

$filterSponsor = '';
if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '';
}


if (isset($_GET['Search_Patient'])) {
    $Search_Patient = str_replace(" ", "%", mysqli_real_escape_string($conn,$_GET['Search_Patient']));
} else {
    $Search_Patient = '';
}

if (isset($_GET['Sponsor'])) {
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor']);
} else {
    $Sponsor = '';
}

//get guarantor name
if($Sponsor != 'All'){
    $slct = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($slct);
    if($no > 0){
        while ($data = mysqli_fetch_array($slct)) {
            $Sponsor_Name = $data['Guarantor_Name'];
        }
    }else{
        $Sponsor_Name = 'All';
    }
}else{
    $Sponsor_Name = 'All';
}

if (isset($_GET['employeeID'])) {
    $employeeID = mysqli_real_escape_string($conn,$_GET['employeeID']);
} else {
    $employeeID = '';
}

if (isset($_GET['Bill_Type'])) {
    $Bill_Type = mysqli_real_escape_string($conn,$_GET['Bill_Type']);
} else {
    $Bill_Type = '';
}

if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = mysqli_real_escape_string($conn,$_GET['Payment_Mode']);
} else {
    $Payment_Mode = '';
}

$filter = " and pp.Transaction_status <> 'cancelled' ";

if (isset($_GET['End_Date']) && $End_Date != '' && isset($_GET['Start_Date']) && $Start_Date != '') {
    $filter .= " AND ilc.Dispense_Date_Time  BETWEEN '$Start_Date' AND '$End_Date' ";
}


if ($Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID='$Sponsor' ";
}

if ($employeeID != 'All') {
    $filter .="  AND ilc.Dispensor='$employeeID' ";
}


if ($Bill_Type == 'All') {
    if ($Payment_Mode == 'Cash') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') ";
    }
} else if ($Bill_Type == 'Outpatient') {
    if ($Payment_Mode == 'All') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') ";
    } else if ($Payment_Mode == 'Cash') {
        $filter .= " and pp.Billing_Type = 'Outpatient Cash' ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and pp.Billing_Type = 'Outpatient Credit' ";
    }
} else if ($Bill_Type == 'Inpatient') {
    if ($Payment_Mode == 'All') {
        $filter .= " and (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') ";
    } else if ($Payment_Mode == 'Cash') {
        $filter .= " and pp.Billing_Type = 'Inpatient Cash' ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and pp.Billing_Type = 'Inpatient Credit' ";
    }
}


if (!empty($Search_Patient)) {
    $filter .= " AND pr.Patient_Name LIKE '%" . $Search_Patient . "%'";
}


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

$htm = '';

$htm = "<center><img src='branchBanner/branchBanner1.png' width='100%'></center>";
$htm .= "<p align='center'><b><span style='font-size: x-small;'>ACTUAL QUANTITIES DISPENSED <br/>FROM" . date('j F,Y H:i:s', strtotime($Start_Date)) . "
            TO " . date('j F,Y H:i:s', strtotime($End_Date)) . "<br/> <b>SPONSOR: $Sponsor_Name</b></span></p>";
    
$htm .= '<table width="100%" border=0 style="border-collapse: collapse;">';

    $Grand_Total = 0;
    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Registration_ID, sp.Guarantor_Name, pr.Date_Of_Birth, pr.Gender, pr.Patient_Name, pp.Billing_Type From
                            tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc where
                            pp.Patient_Payment_ID = ilc.Patient_Payment_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = pp.Registration_ID and
                            ilc.Check_In_Type = 'Pharmacy' and
                            ilc.status = 'dispensed'
                            $filter
                            group by pp.Patient_Payment_ID order by Patient_Payment_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $temp = 0;
        while ($data = mysqli_fetch_array($select)) {
            $Total = 0;
            $Patient_Payment_ID = $data['Patient_Payment_ID'];
            $Date_Of_Birth = $data['Date_Of_Birth'];
            //Generate Age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years";
            //$age .= $diff->m . " Months ";
            //$age .= $diff->d . " Days";

            $htm .= '<tr>
                        <td><span style="font-size: x-small;">'.++$temp.'</td>
                        <td style="text-align: right;" width="7%"><span style="font-size: x-small;">PATIENT : </span></td>
                        <td width="15%"><span style="font-size: x-small;">'.strtoupper($data['Patient_Name']).'</span></td>
                        <td style="text-align: right;" width="6%"><span style="font-size: x-small;">GENDER : </span></td>
                        <td width="6%"><span style="font-size: x-small;">'.strtoupper($data['Gender']).'</span></td>
                        <td style="text-align: right;" width="7%"><span style="font-size: x-small;">AGE : </span></td>
                        <td width="10%"><span style="font-size: x-small;">'.$age.'</span></td>
                        <td style="text-align: right;" width="8%"><span style="font-size: x-small;">SPONSOR : </span></td>
                        <td width="10%"><span style="font-size: x-small;">'.strtoupper($data['Guarantor_Name']).'</span></td>
                        <td style="text-align: right;" width="9%"><span style="font-size: x-small;">RECEIPT #</span></td>
                        <td width="6%"><span style="font-size: x-small;">'.$Patient_Payment_ID.'</span></td>
                        <td style="text-align: right;" width="5%"><span style="font-size: x-small;">BILL : </span></td>
                        <td width="14%"><span style="font-size: x-small;">'.$data['Billing_Type'].'</span></td>
                    </tr>';

            //get medications
            $slct = mysqli_query($conn,"select emp.Employee_Name as Dispensor_Name, i.Product_Name, ilc.Quantity, 
                                ilc.Consultant_ID, ilc.Doctor_Comment, ilc.Dispense_Date_Time, Employee_Created, Edited_Quantity from
                                tbl_item_list_cache ilc, tbl_items i, tbl_patient_payment_item_list ppl, tbl_employee emp where
                                i.Item_ID = ilc.Item_ID and
                                ilc.Dispensor = emp.Employee_ID and
                                ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
                                ppl.Item_ID = ilc.Item_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                ilc.Patient_Payment_ID = '$Patient_Payment_ID' and
                                ilc.status = 'dispensed'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($slct);
            if($no > 0){
                $count = 0;

                $htm .= '<tr>
                            <td colspan="13">
                                <table width="100%" border=1 style="border-collapse: collapse;">
                                    <tr>
                                        <td width="5%"><span style="font-size: x-small;"><b>SN</b></span></td>
                                        <td width="20%"><span style="font-size: x-small;"><b>PRODUCT NAME</b></span></td>
                                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;"><b>ORDERED QTY</b></span></td>
                                        <td width="10%" style="text-align: right;"><span style="font-size: x-small;"><b>DISPENSED QTY</b>&nbsp;&nbsp;&nbsp;</span></td>
                                        <td width="15%" style="text-align: left;"><span style="font-size: x-small;"><b>DOSAGE</b>&nbsp;&nbsp;&nbsp;</span></td>
                                        <td width="13%"><span style="font-size: x-small;"><b>ORDERED BY</b></span></td>
                                        <td width="12%"><span style="font-size: x-small;"><b>DISPENSED BY</b></span></td>
                                        <td width="13%"><span style="font-size: x-small;"><b>DISPENSED DATE</b>&nbsp;&nbsp;&nbsp;</span></td>
                                    </tr>';
                while($row = mysqli_fetch_array($slct)){
                    //get employee ordered
                    $Consultant_ID = $row['Consultant_ID'];
                    $Employee_Created = $row['Employee_Created'];
                    $get_creator = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_Created'") or die(mysqli_error($conn));
                    $no_creator = mysqli_num_rows($get_creator);
                    if($no_creator > 0){
                        while($rw = mysqli_fetch_array($get_creator)){
                            $Employee_Ordered = $rw['Employee_Name'];
                        }
                    }else{
                        $get_ord = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                        $get_no = mysqli_num_rows($get_ord);
                        if($get_no > 0){
                            while($rw = mysqli_fetch_array($get_ord)){
                                $Employee_Ordered = $rw['Employee_Name'];
                            }
                        }else{
                            $Employee_Ordered = '';
                        }
                    }

                    //get quantity dispensed
                    if($row['Edited_Quantity'] > 0){
                        $Qty = $row['Edited_Quantity'];
                    }else{
                        $Qty = $row['Quantity'];
                    }
                        $htm .= '<tr>
                                    <td><span style="font-size: x-small;">'.++$count.'</span></td>
                                    <td><span style="font-size: x-small;">'.$row['Product_Name'].'</span></td>
                                    <td style="text-align: right;"><span style="font-size: x-small;">'.$row['Quantity'].'</span></td>
                                    <td style="text-align: right;"><span style="font-size: x-small;">'.$Qty.'</span></td>
                                    <td style="text-align: left;"><span style="font-size: x-small;">'.$row['Doctor_Comment'].'</span></td>
                                    <td><span style="font-size: x-small;">'.ucwords(strtolower($Employee_Ordered)).'</span></td>
                                    <td><span style="font-size: x-small;">'.ucwords(strtolower($row['Dispensor_Name'])).'</span></td>
                                    <td><span style="font-size: x-small;">'.$row['Dispense_Date_Time'].'</span></td>
                                </tr>';
                }
                $htm .= "</table></td></tr>";
                $htm .= '<tr><td colspan="13"></td></tr>';
                $htm .= '<tr><td colspan="13">&nbsp;</td></tr>';
            }
        }
    }else{
        $htm .= "<center><h4><b>NO TRANSACTION FOUND</b></h4></center>";
    }
    $htm .= '</table>';

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>