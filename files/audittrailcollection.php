<?php
    session_start();
    include("./includes/connection.php");
    $filter = '';

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 'All';
    }

    if(isset($_GET['Start_Receipt'])){
        $Start_Receipt = $_GET['Start_Receipt'];
    }else{
        $Start_Receipt = '';
    }

    if(isset($_GET['End_Receipt'])){
        $End_Receipt = $_GET['End_Receipt'];
    }else{
        $End_Receipt = '';
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '0';
    }

    //get sponsor
    if($Sponsor_ID == '0'){
        $Guarantor = "ALL";
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Guarantor = strtoupper($row['Guarantor_Name']);
            }
        }else{
            $Guarantor = 'ALL';
        }
    }

    //get employees
    if($Employee_ID == '0'){
        $Employees = 'EMPLOYEES : ALL';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Employees = 'EMPLOYEE : '.$row['Employee_Name'];
            }
        }else{
            $Employees = 'EMPLOYEES : ALL';
        }
    }

    //swap receipt value if and only if end receipt value is less than start receipt
    if($End_Receipt < $Start_Receipt){
        $Temp_Value = $End_Receipt;
        $End_Receipt = $Start_Receipt;
        $Start_Receipt = $Temp_Value;
    }

    //CREATE FILTER
    if($Employee_ID != 0){
        $filter .= " pp.Employee_ID = '$Employee_ID' and";
    }

    if($Sponsor_ID != 0){
        $filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
    }

    $htm = '<table align="center" width="100%">
                <tr>
                    <td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td>
                </tr>
                <tr><td style="text-align:center"><b>AUDIT TRAIL COLLECTION REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>SPONSOR : '.$Guarantor.'</b></td></tr>
                <tr><td style="text-align:center"><b>RECEIPTS RANGE : '.$Start_Receipt.' to '.$End_Receipt.'</b></td></tr>
                <tr><td style="text-align:center"><b>'.$Employees.'</b></td></tr>
            </table>';

    $Title = '<thead><tr>
                    <td width="3%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="15%"><b>SPONSOR NAME</b></td>
                    <td width="15%"><b>EMPLOYEE CREATED</b></td>
                    <td width="8%"><b>RECEIPT #</b></td>
                    <td width="15%"><b>RECEIPT DATE</b></td>
                    <td width="11%"><b>BILLING TYPE</b></td>
                    <td width="5%"><b>STATUS</b></td>
                    <td width="7%" style="text-align: right;"><b>TOTAL</b></td>
                </tr></thead>';

    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
    $htm .= $Title;

    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, emp.Employee_Name, pp.Transaction_status,
                            sum((price - discount)*quantity) as Amount, sp.Guarantor_Name, pr.Patient_Name, pp.payment_type
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp where
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pr.Registration_ID = pp.Registration_ID and
                            sp.Sponsor_ID = pp.Sponsor_ID and
                            emp.Employee_ID = pp.Employee_ID and
                            $filter
                            pp.Patient_Payment_ID between '$Start_Receipt' and '$End_Receipt'
                            group BY ppl.patient_payment_id order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    $temp = 0;
    $Total_Cash = 0;
    $Total_Credit = 0;
    $Total_Cancelled = 0;
    if($no > 0){
        while($data = mysqli_fetch_array($select)){
            $htm .= '<tr>
                            <td>'.++$temp.'</td>
                            <td>'.ucwords(strtolower($data['Patient_Name'])).'</td>
                            <td>'.$data['Guarantor_Name'].'</td>
                            <td>'.ucwords(strtolower($data['Employee_Name'])).'</td>
                            <td>'.$data['Patient_Payment_ID'].'</td>
                            <td>'.$data['Payment_Date_And_Time'].'</td>
                            <td>'.$data['Billing_Type'].'</td>
                            <td>'.$data['Transaction_status'].'</td>
                            <td style="text-align: right;">'.number_format($data['Amount']).'</td>
                        </tr>';

            //get total
            if((strtolower($data['Billing_Type']) == 'outpatient cash' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
                $Total_Cash += $data['Amount'];
            }else if((strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
                $Total_Credit += $data['Amount'];
            }else if(strtolower($data['Transaction_status']) == 'cancelled'){
                $Total_Cancelled += $data['Amount'];
            }
        }
    }
$htm .= '</table><br/><br/>';
$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
            <tr>
                <td width="20%" style="text-align: left;"><b>No of Receipts : '.$no.'</b></td>
                <td width="20%" style="text-align: left;"><b>Total Cash : '.number_format($Total_Cash).'</b></td>
                <td width="20%" style="text-align: left;"><b>Total Credit : '.number_format($Total_Credit).'</b></td>
                <td width="20%" style="text-align: left;"><b>Total Cancelled : '.number_format($Total_Cancelled).'</b></td>
                <td width="20%" style="text-align: left;"><b>Grand Total : '.number_format($Total_Credit + $Total_Cash).'</b></td>
            </tr>
        </table>';
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4-L','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>