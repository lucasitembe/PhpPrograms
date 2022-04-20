<?php
    session_start();
    include("./includes/connection.php");
    include 'pharmacy-repo/interface.php';

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
    $Interface = new PharmacyInterface();

    $htm .= "<style> table, th, td { border: 1px solid black;border-collapse: collapse; }</style>";
    $Start_Date = $_GET['Start_Date'];
    $End_Date = $_GET['End_Date'];
    $Search_Patient = $_GET['Search_Patient'];
    $Sponsor_ID = $_GET['Sponsor'];
    $Employee_ID = $_GET['employeeID'];
    $Bill_Type = $_GET['Bill_Type'];
    $Payment_Mode = $_GET['Payment_Mode'];
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
    $output = "";
    $Sponsor_Name = "";
    $count = 1;
    $grand_total = 0;
    $sub_department_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = $Sub_Department_ID"))['Sub_Department_Name'];
    if($Sponsor_ID !=  'all'){
        $Sponsor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = $Sponsor_ID"))['Guarantor_Name'];
    }else{ $Sponsor_Name = "all"; }

    $htm .="<h4 style='font-family:arial;text-align:center'>DISPENSE MEDICATION REPORT FOR ".strtoupper($sub_department_name)." <br> FROM {$Start_Date} TO {$End_Date} <br> SPONSOR : ".strtoupper($Sponsor_Name)."</h4>";

    $Today = $Interface->getTodayDateTime();
    $report_details = $Interface->filterDispenseMedicationReport($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Employee_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID);
    
    $htm .= "<b><h5 style='margin-bottom:0.5em;font-family:arial'>Number Of Patients : ".sizeof($report_details)."</h5> </b>";
    foreach($report_details as $details){
        $date1 = new DateTime($Today);
        $date2 = new DateTime($details['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Yrs ";
        // $age .= $diff->m . " Months, ";
        // $age .= $diff->d . " Days";

        $htm .="
            <table style='font-family:arial;font-size:11px;' width='100%'>
                <tr style='background-color: #fff;'>
                    <td style='padding: 6px;font-weight:bold' width='3%' ><center>".$count++."</center></td>
                    <td style='padding: 6px;font-weight:bold'>PATIENT NAME : {$details['Patient_Name']}</td>
                    <td style='padding: 6px;font-weight:bold' colspan='2'>GENDER : {$details['Gender']}</td>
                    <td style='padding: 6px;font-weight:bold' colspan='2'>AGE : {$age}</td>
                    <td style='padding: 6px;font-weight:bold' colspan='2'>SPONSOR : {$details['Guarantor_Name']}</td>
                    <td style='padding: 6px;font-weight:bold' colspan='2'>BILL : {$details['Billing_Type']}</td>
                </tr>
                <tr style='background-color: #ddd;'>
                    <td style='padding: 6px;font-weight:bold' width='2%'><center>SN</center></td>
                    <td style='padding: 6px;font-weight:bold' width='35%'>PRODUCT NAME</td>
                    <td style='padding: 6px;font-weight:bold' width='8%'>DOSAGE</td>
                    <td style='padding: 6px;font-weight:bold;text-align:right' width='7%'>PRICE</td>
                    <td style='padding: 6px;font-weight:bold;text-align:center' width='6%'>DISCOUNT</td>
                    <td style='padding: 6px;font-weight:bold;text-align:center' width='5%'>QTY</td>
                    <td style='padding: 6px;font-weight:bold' width='8%'>ORDER BY</td>
                    <td style='padding: 6px;font-weight:bold' width='9%'>DISPENSE BY</td>
                    <td style='padding: 6px;font-weight:bold' width='8%'>DISPENSE DATE</td>
                    <td style='padding: 6px;font-weight:bold;text-align:right' width='6%'>TOTAL</td>
                </tr>";
                    $in = 1;
                        $__sub_total = 0;
                        foreach($details[0] as $inner_details){    
                            $Consultant_name = $Interface->getConsultantName($inner_details['Consultant_ID']);

                            if($inner_details['sub_total_from_dispense_qty'] > 0){
                                $__sub_total = $__sub_total + $inner_details['sub_total_from_dispense_qty'];
                                $qty = $inner_details['dispensed_quantity'];
                                $formatted_total = (int)$inner_details['sub_total_from_dispense_qty'];
                            }else if($inner_details['sub_total_from_qty'] > 0){
                                $qty = $inner_details['Quantity'];
                                $__sub_total = $__sub_total + $inner_details['sub_total_from_qty'];
                                $formatted_total = (int)$inner_details['sub_total_from_qty'];
                            }else{
                                $qty = $inner_details['Edited_Quantity'];
                                $__sub_total = $__sub_total + $inner_details['sub_total_from_edited_dispense_qty'];
                                $formatted_total = (int)$inner_details['sub_total_from_edited_dispense_qty'];
                            }

                            $htm .= "<tbody>
                                    <tr style='background-color: #fff;'>
                                        <td style='padding: 6px;' width='3%'><center>".$in++."</center></td>
                                        <td style='padding: 6px;' width='35%'>{$inner_details['Product_Name']}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Doctor_Comment']}</td>
                                        <td style='padding: 6px;text-align:right' width='7%'>".number_format($inner_details['Price'],2)."</td>
                                        <td style='padding: 6px;text-align:center' width='7%'>{$inner_details['Discount']}</td>
                                        <td style='padding: 6px;text-align:center' width='7%'>{$qty}</td>
                                        <td style='padding: 6px;' width='7%'>{$Consultant_name}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Dispensor_Name']}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Dispense_Date_Time']}</td>
                                        <td style='padding: 6px;text-align:right' width='7%'>".number_format($formatted_total,2)."</td>
                                    </tr>";
                        }
                            $grand_total = $grand_total + $__sub_total;
                            $htm .= "<tr style='background-color: #fff;'>
                                        <td colspan='9' style='padding: 6px;text-align:end'></td>
                                        <td style='padding: 6px;text-align:right;font-weight:bold'>".number_format($__sub_total)."</td>
                                    </tr>
                                </tbody>
            </table><br/>
        ";
    }

    $htm .= "
        <table>
            <tr>
                <td colspan='9' style='padding: 6px;text-align:end'>Grand Total</td>
                <td style='padding: 6px;text-align:right;font-weight:bold'>".number_format($grand_total)."</td>
            </tr>
        </table>
    ";

    //$htm.="<h5 style='float:right;margin-bottom:.5em;font-family:arial'>Grand Total : ".number_format($grand_total)."</b>";

    // $htm = mb_convert_encoding($htm, 'UTF-8', 'UTF-8');
    // $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    // ini_set('memory_limit', '1020004M');
    // include("./MPDF/mpdf.php");
    // $mpdf=new mPDF('','A4-L', 0, '', 15,15,20,40,15,35, 'P');
    // $mpdf->SetFooter('Printed By ~ '.strtolower($Employee_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    // $mpdf->WriteHTML($htm);
    // $mpdf->Output();
    header("Content-Type:application/xls");
    header("content-disposition: attachment; filename=Pharmacy_report.xls");
    echo $htm;
?>