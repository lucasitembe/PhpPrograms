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
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $E_Name = '';
}
if (isset($_SESSION['Pharmacy'])) {
    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}

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

// if (isset($_GET['employeeID'])) {
//     $employeeID = mysqli_real_escape_string($conn,$_GET['employeeID']);
// } else {
//     $employeeID = '';
// }

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
    $filter .= "   AND ilc.Transaction_Date_And_Time  BETWEEN '$Start_Date' AND '$End_Date'   AND ilc.Sub_Department_ID = '$Sub_Department_ID'";
}


if ($Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID='$Sponsor'";
}

// if ($employeeID != 'All') {
//     $filter .="  AND ilc.Dispensor='$employeeID'";
// }


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

$htm = "<center><img src='./branchBanner/branchBanner.png' width=100%></center>";
$htm .= "<p align='center'><b>PHARMACY NOT DISPENSED LIST REPORT <br/>FROM</b> <b style=''>" . date('j F,Y H:i:s', strtotime($Start_Date)) . "</b> <b>TO</b> <b style=''>" . date('j F,Y H:i:s', strtotime($End_Date)) . "</b><br/> <b>SPONSOR: $Sponsor_Name</b></p>";

$htm .= '<table width="100%" border=0 style="border-collapse: collapse;">';

    $Grand_Total = 0;
        $select = mysqli_query($conn,"SELECT pp.Payment_Cache_ID, pp.Registration_ID, sp.Guarantor_Name, pr.Date_Of_Birth, pr.Gender, pr.Patient_Name,pp.Billing_Type,ilc.status From
        tbl_payment_cache pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc where
        pp.Payment_Cache_ID = ilc.Payment_Cache_ID and
        pr.Sponsor_ID = sp.Sponsor_ID and
        pr.Registration_ID = pp.Registration_ID and
        ilc.Check_In_Type = 'Pharmacy' and
        ilc.status <> 'dispensed'
        $filter
        group by pp.Payment_Cache_ID order by Payment_Cache_ID") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
    if($num > 0){
        $htm = "<center><img src='branchBanner/branchBanner1.png' width='100%'></center>";
$htm .= "<p align='center'><b>PHARMACY NOT  DISPENSED LIST REPORT <br/>FROM</b> <b style=''>" . date('j F,Y H:i:s', strtotime($Start_Date)) . "</b> <b>TO</b> <b style=''>" . date('j F,Y H:i:s', strtotime($End_Date)) . "</b><br/> <b>SPONSOR: $Sponsor_Name</b><br><b>Number of Patients: ".$num."</b></p>";
        $temp = 0;
        while ($data = mysqli_fetch_array($select)) {
            $Total = 0;
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $Date_Of_Birth = $data['Date_Of_Birth'];
            //Generate Age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            //$age .= $diff->d . " Days";


            $htm .= '<tr><td style="text-align: right;"><span style="font-size: x-small;">'.++$temp.'</td>
                        <td style="text-align: right;" width="10%"><span style="font-size: x-small;">PATIENT : </span></td>
                        <td width="15%"><span style="font-size: x-small;">'.strtoupper($data['Patient_Name']).'</span></td>
                        <td style="text-align: right;" width="6%"><span style="font-size: x-small;">GENDER : </span></td>
                        <td width="6%"><span style="font-size: x-small;">'.strtoupper($data['Gender']).'</span></td>
                        <td style="text-align: right;" width="7%"><span style="font-size: x-small;">AGE : </span></td>
                        <td width="11%"><span style="font-size: x-small;">'.$age.'</span></td>
                        <td style="text-align: right;" width="8%"><span style="font-size: x-small;">SPONSOR : </span></td>
                        <td width="10%"><span style="font-size: x-small;">'.strtoupper($data['Guarantor_Name']).'</span></td>
                        
                        <td style="text-align: right;" width="5%"><span style="font-size: x-small;">BILL : </span></td>
                        <td width="14%"><span style="font-size: x-small;">'.$data['Billing_Type'].'</span></td>
                    </tr>';

            //get medications
            $slct = mysqli_query($conn,"SELECT i.Product_Name, ilc.Quantity,ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Consultant_ID, ilc.Doctor_Comment, ilc.Transaction_Date_And_Time,ilc.status  from
            tbl_item_list_cache ilc, tbl_items i,  tbl_payment_cache pc where
            i.Item_ID = ilc.Item_ID and ilc.Payment_Cache_ID='$Payment_Cache_ID' and
            pc.Payment_Cache_ID='$Payment_Cache_ID' and
            i.Item_ID = ilc.Item_ID and
            ilc.Check_In_Type = 'Pharmacy' and pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
            ilc.status <> 'dispensed' order by ilc.Transaction_Date_And_Time desc") or die(mysqli_error($conn));
            $no = mysqli_num_rows($slct);

            if($no > 0){
                $count = 0;
                    $htm .= '<tr>
                                <td colspan="13">
                                    <table width="100%" border=1 style="border-collapse: collapse;">
                                        <tr>
                                            <td width="5%"><span style="font-size: x-small;">SN</span></td>
                                            <td width="25%"><span style="font-size: x-small;">PRODUCT NAME</span></td>
                                            <td width="9%"><span style="font-size: x-small;">DOSAGE</span></td>
                                            <td width="9%" style="text-align: right;"><span style="font-size: x-small;">PRICE</span></td>
                                            <td width="13%"><span style="font-size: x-small;">ORDERED BY</span></td>
                                            
                                            <td width="13%"><span style="font-size: x-small;">ORDERED DATE</span></td>
                                            <td width="13%"><span style="font-size: x-small;">STATUS</span></td>
                                        </tr>';

                while($row = mysqli_fetch_array($slct)){
                    $status='';
                    $output='';

                    if($row['status']=='active'){
                        if($Payment_Mode=='Credit'){
                            $status='NOT BILLED';
                            $output='<label style="color:red;">'.$status.'</label>';
                          }
                          else{
                              $status='NOT PAID';
                              $output='<label style="color:red;">'.$status.'</label>';
                          }
                      } else if($row['status']=='approved'){
                        $status='APPROVED';
                        $output='<label style="color:blue;">'.$status.'</label>';
                      }
                      else if($row['status']=='paid'){
                          if($Payment_Mode=='Credit'){
                            $status='BILLED';
                            $output= '<label style="color:blue;">'.$status.'</label>';
                          }else{
                            $status='PAID';
                            $output='<label style="color:blue;">'.$status.'</label>';
                          }
                       
                      }
                      else if($row['status']=='removed'){
                        $status='REMOVED';
                        $output='<label style="color:red;">'.$status.'</label>';
                      }
                      else{
                        $output= '<label style="color:red;">'.$status.'</label>';
                      }
                    //get employee ordered
                    $Consultant_ID = $row['Consultant_ID'];
                    $get_ord = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                    $get_no = mysqli_num_rows($get_ord);
                    if($get_no > 0){
                        while($rw = mysqli_fetch_array($get_ord)){
                            $Employee_Ordered = $rw['Employee_Name'];
                        }
                    }else{
                        $Employee_Ordered = '';
                    }
                            $htm .= '<tr>
                                        <td><span style="font-size: x-small;">'.++$count.'</span></td>
                                        <td><span style="font-size: x-small;">'.$row['Product_Name'].'</span></td>
                                        <td><span style="font-size: x-small;">'.$row['Doctor_Comment'].'</span></td>
                                        <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($row['Price']).'</span></td>
                                        <td><span style="font-size: x-small;">'.ucwords(strtolower($Employee_Ordered)).'</span></td>
                                        <td>'.$row['Transaction_Date_And_Time'].'</td>
                                        <td>'.$output.'</td>
                                    </tr>';
                    //Get Grand Total & Total
                    $Total += (($row['Price'] - $row['Discount']) * $row['Quantity']);
                    $Grand_Total += (($row['Price'] - $row['Discount']) * $row['Quantity']);
                }
                $htm .= '<tr>
                            </tr>';
                $htm .= '';
                $htm .= "</table></td></tr><tr><td colspan='11'>&nbsp;</td></tr><tr><td colspan='11'>&nbsp;</td></tr>";
            }
        }
    }else{
        $htm .= "<center><h4><b><span style='font-size: x-small;'>NO TRANSACTION FOUND</span></b></h4></center>";
    }
    $htm .= '<tr>
                <td colspan="12" style="text-align: right;"><h4><b><span style="font-size: x-small;">GRAND TOTAL</span></b></h4></td>
                <td style="text-align: right;"><b><h4><span style="font-size: x-small;">'.number_format($Grand_Total).'</span></b></h4></td></tr>
        </table>';
    $htm = mb_convert_encoding($htm, 'UTF-8', 'UTF-8');
    ini_set('memory_limit', '1024M');
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>