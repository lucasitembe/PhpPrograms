<?php

session_start();
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
$temp = 1;

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
} else {
    $Employee_ID = 0;
}
if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}
if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
}
$htm = "<table width ='100%' height = '30px'>
	        <tr><td><img src='./branchBanner/branchBanner.png' width='100%'></td></tr>
	        <tr><td style='text-align: center;'><span style='font-size: x-small;'><b>ePAYMENT COLLECTIONS REPORT</b></span></td></tr></table>";
$htm .= '<table>
                <tr><td>Start Date : </td><td><span style="font-size: x-small;">' . $Start_Date . '</span></td></tr>
                <tr><td>End Date : </td><td><span style="font-size: x-small;">' . $End_Date . '</span></td></tr>
            </table>';

$htm .= '<center><table width =100% id="dtTableperformancedetails" class="display" style="background-color:white;">';

$Title = "<tr><td colspan='9'><hr></td></tr>
                <tr>
                    <td width = '4%'><span style='font-size: x-small;'><b>SN</b></span></td>
                    <td style='text-align: left;' width='12%'><span style='font-size: x-small;'><b>DATE & TIME</b></span></td>
                    <td style='text-align: left;'><span style='font-size: x-small;'><b>PATIENT NAME</b></span></td>
                    <td width='13%' style='text-align: right;'><span style='font-size: x-small;'><b>RECEIPT NO</b></span></td>
                    <td width='13%'>&nbsp;&nbsp;&nbsp;<span style='font-size: x-small;'><b>BILL NUMBER</b></span></td>
                    <td width='15%'><span style='font-size: x-small;'><b>TRANSACTION REF</b></span></td>
                    <td width='15%'><span style='font-size: x-small;'><b>TRANSACTION DATE</b></span></td>
                    <td style='text-align: right;' width=8%><span style='font-size: x-small;'><b>AMOUNT</b></span></td>
                </tr>
                <tr><td colspan='9'><hr></td></tr>";
$htm .= $Title;
$select = mysqli_query($conn,"select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, 
                            sum((price*quantity)-(discount*quantity)) as Total from 
                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            pp.Transaction_status <> 'cancelled' and
                            pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn));
//declare all total
$Cash_Total = 0;

while ($row = mysqli_fetch_array($select)) {
    $Payment_Code = $row['Payment_Code'];

    //get Transaction_Ref & Transaction_Date
    $slct = mysqli_query($conn,"select Transaction_Ref, Transaction_Date, Terminal_ID, Merchant_ID, Batch_No, Auth_No from tbl_bank_api_payments_details where Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($slct);
    if ($no > 0) {
        while ($dt = mysqli_fetch_array($slct)) {
            $Transaction_Ref = $dt['Transaction_Ref'];
            $Transaction_Date = $dt['Transaction_Date'];
            $Terminal_ID = $dt['Terminal_ID'];
            $Merchant_ID = $dt['Merchant_ID'];
            $Batch_No = $dt['Batch_No'];
            $Auth_No = $dt['Auth_No'];
        }
    } else {
        $Transaction_Ref = '';
        $Transaction_Date = '';
    }

    $htm .= "<tr><td><span style='font-size: x-small;'><b>" . $temp . ".</b></span></td>";
    $htm .= "<td><span style='font-size: x-small;'>" . $row['Payment_Date_And_Time'] . "</span></td>";
    $htm .= "<td><span style='font-size: x-small;'>" . ucwords(strtolower($row['Patient_Name'])) . "</span></td>";
    $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . $row['Patient_Payment_ID'] . "</a></span></td>";
    $htm .= "<td>&nbsp;&nbsp;&nbsp;<span style='font-size: x-small;'>" . $Payment_Code . "</span></td>";
    $htm .= "<td><span style='font-size: x-small;'>" . $Transaction_Ref . "</span></td>";
    $htm .= "<td><span style='font-size: x-small;'>" . $Transaction_Date . "</span></td>";
    if (((strtolower($row['Billing_Type']) == 'outpatient cash') or ( strtolower($row['Billing_Type']) == 'inpatient cash')) and ( strtolower($row['Transaction_status']) == 'active')) {
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($row['Total']) . "</span></td></tr>";
        $Cash_Total = $Cash_Total + $row['Total'];
    }
    $temp++;
    if (($temp % 31) == 0) {
        $htm .= $Title;
    }
}
$htm .= "<tr><td colspan='9'><hr></td></tr>";
$htm .= "<tr><td colspan='7' style='text-align: left;'><span style='font-size: x-small;'><b>GRAND TOTAL</b></span></td>";
$htm .= "<td style='text-align: right;'><span style='font-size: x-small;'><b>" . number_format($Cash_Total) . "</b></span></td></tr>";
$htm .= "<tr><td colspan='9'><hr></td></tr>";
$htm .= "</table></center>";

include("./MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
$mpdf->SetFooter('|page {PAGENO} of {nb}|{DATE d-m-Y}');
//$mpdf=new mPDF(); 
$mpdf->WriteHTML($htm);
$mpdf->Output();
?>

