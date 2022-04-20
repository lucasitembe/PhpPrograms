<?php
session_start();
include("./includes/connection.php");
$temp = 0;
$Grand_Total = 0;
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = '';
}

if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}

if (isset($_GET['Report_Type'])) {
    $Report_Type = $_GET['Report_Type'];
} else {
    $Report_Type = '';
}

if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = $_GET['Payment_Mode'];
} else {
    $Payment_Mode = '';
}

if (isset($_GET['Terminal_ID'])) {
    $Terminal_ID = $_GET['Terminal_ID'];
}

$filterterminal='';
if(!empty($Terminal_ID) && $Terminal_ID !='all'){
  $filterterminal=" AND ba.Terminal_ID='$Terminal_ID'";  
}
$htm = "<table width ='100%' height = '30px'>
            <tr><td>
            <img src='./branchBanner/branchBanner.png'>
            </td></tr>
            <tr><td style='text-align: center;'><span><b>ePayment Collection Report - Paid Transactions</b></span></td></tr>
            </table><br/>";
            $htm .= "<table width='100%'>
                <tr>
                    <td style='text-align: left;'><b>Start Date : </b>".@date("d F Y H:i:s", strtotime($Date_From))."</td>
                <tr>
                <tr>
                    <td style='text-align: left;'><b>End Date : </b>".@date("d F Y H:i:s", strtotime($Date_To))."</td>
                <tr>
                <tr>
                    <td style='text-align: left;'><b>Terminal Number : </b>All Terminals</td>
                <tr>
            </table><br/><br/>";


    $htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
            <tr>
                <td width="5%"><b><span style="font-size: small;">SN</span></b></td>
                <td><b><span style="font-size: small;">PATIENT NAME</span></b></td>
                <td><b><span style="font-size: small;">PATIENT NUMBER</span></b></td>
                <td><b><span style="font-size: small;">SPONSOR</span></b></td>
                <td style="text-align: right;"><b><span style="font-size: small;">PREPARED DATE</span></b></td>
                <td style="text-align: right;"><b><span style="font-size: small;">EMPLOYEE PREPARED</span></b></td>
                <td>&nbsp;&nbsp;&nbsp;<b><span style="font-size: small;">BILL NUMBER</span></b></td>
                <td><b><span style="font-size: small;">TRANSACTION REF</span></b></td>
                <td><b><span style="font-size: small;">TRANSACTION DATE</span></b></td>
                <td><b><span style="font-size: small;">TERMINAL ID</span></b></td>
                <td><b><span style="font-size: small;">MERCHANT ID</span></b></td>
                <td><b><span style="font-size: small;">BATCH NO</span></b></td>
                <td><b><span style="font-size: small;">AUTH NO</span></b></td>
                <td style="text-align: right;"><b><span style="font-size: small;">AMOUNT</span></b></td>
            </tr>';

        $select = mysqli_query($conn,"select tc.Source, tc.Amount_Required, pr.Patient_Name, tc.Transaction_ID, 
                            tc.Payment_Code, sp.Guarantor_Name, emp.Employee_Name, tc.Transaction_Date_Time, pr.Registration_ID,
                            ba.Transaction_Ref, ba.Transaction_Date,Terminal_ID,Merchant_ID,Batch_No,Auth_No
                            from tbl_bank_transaction_cache tc
                            JOIN tbl_patient_registration pr ON tc.Registration_ID = pr.Registration_ID 
                            JOIN tbl_sponsor sp ON pr.Sponsor_ID = sp.Sponsor_ID
                            JOIN tbl_employee emp ON emp.Employee_ID = tc.Employee_ID
                            JOIN tbl_bank_api_payments_details ba ON ba.Payment_Code=tc.Payment_Code
                            WHERE
                            ba.Transaction_Date between '$Date_From' and '$Date_To' AND
                            tc.Transaction_Status = 'Completed'
                            $filterterminal
                            order by ba.Transaction_Date desc limit 500") or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Grand_Total += $data['Amount_Required'];
                $Transaction_ID = $data['Transaction_ID'];
                $Payment_Code = $data['Payment_Code'];

                //get Transaction_Ref & Transaction_Date
               
                        $Transaction_Ref = $data['Transaction_Ref'];
                        $Transaction_Date = $data['Transaction_Date'];
                        $Terminal_ID = $data['Terminal_ID'];
                        $Merchant_ID = $data['Merchant_ID'];
                        $Batch_No = $data['Batch_No'];
                        $Auth_No = $data['Auth_No'];
             
               $htm .= '<tr>
                            <td>'.(++$temp).'</td>
                            <td>'.ucwords(strtolower($data['Patient_Name'])).'</td>
                            <td>'.$data['Registration_ID'].'</td>
                            <td>'.$data['Guarantor_Name'].'</td>
                            <td style="text-align: right;">'.$data['Transaction_Date_Time'].'</td>
                            <td style="text-align: right;">'.$data['Employee_Name'].'</td>
                            <td>'.$data['Payment_Code'].'</td>
                            <td>'.$Transaction_Ref.'</td>
                            <td>'.$Transaction_Date.'</td>
                             <td>'.$Terminal_ID.'</td>
                             <td>'.$Merchant_ID.'</td>
                             <td>'.$Batch_No.'</td>
                             <td>'.$Auth_No.'</td>
                            <td style="text-align: right;">'.number_format($data['Amount_Required']).'</td></tr>';
            }
            $htm .= '<tr><td colspan="13" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;">' . number_format($Grand_Total) . '</td></tr></table>';
        }
        $Today_Date = mysqli_query($conn,"select now() as today");
            while ($row = mysqli_fetch_array($Today_Date)) {
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age = '';
            }
        $htm .= '<br/><br/><span style="font-size: small;">Printed By : <b>Adelard Kiliba </b>&nbsp;&nbsp;Date : <b>'.@date("d F Y H:i:s", strtotime($original_Date)).'</b></span>';
        echo $htm;


        /*include("MPDF/mpdf.php");

    $mpdf=new mPDF('','A3','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('Printed on  {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;*/