<?php

@session_start();
include("./includes/connection.php");

$source = $_GET['source'];
$Registration_ID = $_GET['Registration_id'];
$SubCategory = $_GET['SubCategory'];
$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];  //
$Sponsor = strtolower($_GET['Sponsor']);
$billtype = strtoupper($_GET['billtype']);

$Sub_Department_ID = 0;

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}

$wherStatus = '';
if ($source == 'new') {
    $wherStatus = " ilc.status IN ('active','paid')  and 
                    ilc.removing_status='no' and";
} elseif ($source == 'removed') {
    $wherStatus = " ilc.status IN ('active','paid')  and
                    ilc.removing_status='yes'  and";
} elseif ($source == 'consult') {
    $wherStatus = "  ilc.status = 'served'  and";
} elseif ($source == 'pending') {
    $wherStatus = " ilc.status IN ('pending','not done') and";
}

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$filter = " AND DATE(Transaction_Date_And_Time)= DATE(NOW())  AND ilc.Sub_Department_ID='$Sub_Department_ID'";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'  AND ilc.Sub_Department_ID='$Sub_Department_ID'";
}

if (isset($SubCategory) && $SubCategory != 'All') {
    $filter .=" AND its.Item_Subcategory_ID='$SubCategory'";
}



//  $data .=  '$sqlq';exit; style="background-color:#037DB0;color:white;font-weight:bold"



$sqlq = " select ilc.Status,its.Item_ID, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID,ilc.Doctor_Comment,e.Employee_Name,ilc.Transaction_Date_And_Time,ilc.remarks,pc.Billing_Type,ilc.Transaction_Type,ilc.payment_type,Require_Document_To_Sign_At_receiption
		from tbl_item_list_cache ilc,tbl_payment_cache pc, tbl_items its, tbl_sponsor sp , tbl_employee e		  
                where ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                    ilc.item_id = its.item_id and
                    ilc.Consultant_ID = e.Employee_ID and
                    sp.Sponsor_ID=pc.Sponsor_ID and
                    $wherStatus
                    pc.Registration_ID='$Registration_ID' and
                                        ilc.Check_In_Type = 'Radiology' $filter
		 ";





$select_Transaction_Items_Active = mysqli_query($conn,$sqlq) or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Transaction_Items_Active);

if (empty($source) || $no == 0) {
     $data = "<table width ='100%' height = '30px'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td style='text-align: center;font-size: small;'><b>No test availlable.</b></td>
            </tr></table><br/>";
    
} else {
    $getPatientDetails = "
          SELECT Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,Guarantor_Name, TIME(NOW()) myTime 
           FROM tbl_patient_registration pr 
           JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID 
           WHERE pr.Registration_ID='$Registration_ID'
        ";

$resultPat = mysqli_query($conn,$getPatientDetails) or die(mysqli_error($conn));

$rowPat = mysqli_fetch_array($resultPat);

    $patientName = $rowPat['Patient_Name'];
    $Registration_ID = $rowPat['Registration_ID'];
    $Gender = $rowPat['Gender'];
    $age = $rowPat['age'];
    $Region = $rowPat['Region'];
    $District = $rowPat['District'];
    $Guarantor_Name = $rowPat['Guarantor_Name'];
    $Billing_Type = $rowPat['Billing_Type'];//
    $doctorName= $rowPat['Employee_Name'];
    $doctorDate= $rowPat['Payment_Date_And_Time'];
    $myTime= $rowPat['myTime'];


    $data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td style='text-align: center;'><b>PATIENT RADIOLOGY TESTS</b></td>
            </tr></table><br/>";

    $data .= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;" width="10%"><b>Name:</b></td>
                    <td width="25%">' . $patientName . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Billing Type:</b></td>
                    <td>' . $billtype . '</td>
                    <td style="text-align: right;"><b>Date:</b></td>
                    <td colspan="3">' . date('d, M Y') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>
            </table><br/>';


    $data .= "
      <table width='100%' style='border-collapse: collapse;'  >
      <thead>
        <tr>
            <td><b>S/n</b></td>
            <td><b>Test Name</b></td>
            <td><b>Doctor Comment</b></td>
            <td><b>Doctor Ordered</b></td>
            <td><b>Date Ordered</b></td>
        </tr>
      </thead>";

    $temp = 1;
    
$transStatust = false;
while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
    $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $payment_type = strtolower($row['payment_type']);
    $require_approve = strtolower($row['Require_Document_To_Sign_At_receiption']);
    $The_Item_ID = $row['Item_ID'];
    $displ = '';

    $msg = '';

    if (($billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $transStatust = true;
        $msg = 'Radiology not paid.';
    } elseif ($billing_Type == 'outpatient credit' && $status == 'active' && $require_approve == 'mandatory') {
        $transStatust = true;
        $msg = 'Radiology not approved.';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        if ($pre_paid == '1') {
            $transStatust = true;
            $msg = 'Radiology not paid.';
        } elseif ($payment_type == 'pre') {
            $transStatust = true;
            $msg = 'Radiology not paid.';
        }
    }


    if ($transStatust) {
        
    } else {

         $data .= "<tr><td width='5%'>".$temp."</td>";
         $data .= "<td><div style='width:100%;margin:4px;'>" . $row['Product_Name'] . "</div></td>";
         $data .= "<td><div style='width:100%;margin:4px;'>" . $row['Doctor_Comment'] . "</div></td>";
         $data .= "<td><div style='width:100%;margin:4px;'>" . $row['Employee_Name'] . "</div></td>";
         $data .= "<td><div style='width:100%;margin:4px;'>" . $row['Transaction_Date_And_Time'] . "</div></td>";
          $temp++;
    }


  
    $transStatust = false;
     $data .= "</tr>";
}
}
 $data .= "</table>";


include("./MPDF/mpdf.php");
//$mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
////$mpdf->SetFooter('Consulted By ' . strtoupper($_SESSION['userinfo']['Employee_Name']) . '|Page {PAGENO} of {nb}|{DATE d-m-Y}');
//$mpdf->WriteHTML($data);
//$mpdf->Output();
//exit;

$mpdf = new mPDF('c', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
