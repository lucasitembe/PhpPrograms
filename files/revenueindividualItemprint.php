<?php

    include("./includes/connection.php");
 
    
    $data = '';
$title_for_report = '';

$query_string = '';
$TotalItem = 0;
$SuperTotalItems = 0;


if (isset($_GET['type']) && $_GET['type'] == 'cash') {
    $fromDate = filter_input(INPUT_GET, 'fromDate');
    $todate = filter_input(INPUT_GET, 'todate');
    $sponsorID = filter_input(INPUT_GET, 'sponsorID');
    $sponsorname = filter_input(INPUT_GET, 'sponsorname');
    $employeeID = filter_input(INPUT_GET, 'employeeID');
    $employeeName = filter_input(INPUT_GET, 'employeeName');
    $patientStatus = filter_input(INPUT_GET, 'patientStatus');
    $sub_category = filter_input(INPUT_GET, 'sub_category');
    $sub_category_id = filter_input(INPUT_GET, 'sub_category_id');
    $category = filter_input(INPUT_GET, 'category');
    $category_id = filter_input(INPUT_GET, 'category_id');

    $query_string = 'category_id=' . $category_id . '&category=' . $category . '&type=cash&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;

    $filter = '';
    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time<='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }

    if (isset($sponsorname) && !empty($sponsorname) && trim($sponsorname) !== "All Sponsors") {
        $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
    }

    if (isset($employeeName) && !empty($employeeName) && trim($employeeName) !== "All Data Entry") {
        $filter.=" AND tpp.Employee_ID='$employeeID'";
        $servedby = 'Served By&nbsp;&nbsp;<b>' . $employeeName . '</b>';
    }
    if (isset($patientStatus) && !empty($patientStatus)) {
        if ($patientStatus === 'INPATIENT') {
            $filter.=" AND tpp.Billing_Type='Inpatient Cash'";
        } else if ($patientStatus === 'OUTPATIENT') {
            $filter.=" AND tpp.Billing_Type='Outpatient Cash'";
        } else if ($patientStatus === "OUTPATIENT AND INPATIENT") {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Cash','Inpatient Cash')";
        }
    }

    // die($filter);

    $data.='<table align="center" width="100%" border="0">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>' . strtoupper($category) . ' REVENUE SUMMARY REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Cash</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $servedby . '</td>
                         </tr>
                       </table>
                       <hr style="width:100%"/>
                       ';


    //retrieve all sabcategory category

    $i = 1;
    $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
             <th>S/N</th><th>ITEM NAME</th><th style='text-align:right'>QTY</th><th style='text-align:right'>CASH</th><th style='text-align:right'>TOTAL</th>
           </tr>    
           <tr>
             <td colspan='5'> <hr style='width:100%'/></td>
           </tr>
           </thead>
           ";
    $total = 0;
    $totalCash = 0;
    $totalsubCash = 0;
//         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID,Item_Subcategory_Name FROM tbl_item_subcategory WHERE Item_Category_ID='".$sub_category_id."'");
//         
//         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
//          $subCatID=$rowSubcat['Item_Subcategory_ID'];
//         //echo $subCatID;
//         //exit;

     $sqlItem = "SELECT distinct(tpl.Item_ID) FROM tbl_patient_payments as tpp
            LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$sub_category_id' $filter
             
           ";

    $getItemID = mysqli_query($conn,$sqlItem) or die(mysqli_error($conn));
    while ($rowitem = mysqli_fetch_array($getItemID)) {
        $sql = "SELECT 
                 (SELECT itm.Product_Name FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Product_Name,
                   
                (SELECT SUM(tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Quntity,"
                 
                  
                . "(SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  AND tpp.Billing_Type IN ('Inpatient Cash','Outpatient Cash') GROUP BY itm.Product_Name) AS TotalsubCash";



//        $sql="SELECT tpp.Patient_Payment_ID,itm.Product_Name,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
//               FROM tbl_patient_payments as tpp
//               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
//               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
//            
//               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter
//           " 
//          ;
        $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        while ($rowCash = mysqli_fetch_array($queryResult)) {
            $totalsubCash+=$rowCash['TotalsubCash'];
            $TotalItem =$rowCash['Quntity'];

        $totalCash+=$totalsubCash;
        $total+=$totalsubCash;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td>' . $rowCash['Product_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td></tr>';
        $totalsubCash = 0;
        $SuperTotalItems += $TotalItem; //$TotalItem
        $TotalItem = 0;
        $i++;
    }
   
    // $data=$sql;
    //$data=mysql
    }
    
     $data.="<tr><td colspan='2' style='text-align:center;font-weight:bold'>Total</td><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totalCash) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

    $data.='</table>';
}

//For credit report

if (isset($_GET['type']) && $_GET['type'] == 'credit') {
    $fromDate = filter_input(INPUT_GET, 'fromDate');
    $todate = filter_input(INPUT_GET, 'todate');
    $sponsorID = filter_input(INPUT_GET, 'sponsorID');
    $sponsorname = filter_input(INPUT_GET, 'sponsorname');
    $employeeID = filter_input(INPUT_GET, 'employeeID');
    $employeeName = filter_input(INPUT_GET, 'employeeName');
    $patientStatus = filter_input(INPUT_GET, 'patientStatus');
    $sub_category = filter_input(INPUT_GET, 'sub_category');
    $sub_category_id = filter_input(INPUT_GET, 'sub_category_id');
    $category = filter_input(INPUT_GET, 'category');
    $category_id = filter_input(INPUT_GET, 'category_id');

    $query_string = 'category_id=' . $category_id . '&category=' . $category . '&type=credit&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;


    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time<='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }

    if (isset($sponsorname) && !empty($sponsorname) && trim($sponsorname) !== "All Sponsors") {
        $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
    }

    if (isset($employeeName) && !empty($employeeName) && trim($employeeName) !== "All Data Entry") {
        $filter.=" AND tpp.Employee_ID='$employeeID'";
        $servedby = 'Served By&nbsp;&nbsp;<b>' . $employeeName . '</b>';
    }
    if (isset($patientStatus) && !empty($patientStatus)) {
        if ($patientStatus === 'INPATIENT') {
            $filter.=" AND tpp.Billing_Type='Inpatient Credit'";
        } else if ($patientStatus === 'OUTPATIENT') {
            $filter.=" AND tpp.Billing_Type='Outpatient Credit'";
        } else if ($patientStatus === "OUTPATIENT AND INPATIENT") {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit')";
        }
    }

    $data.='<table align="center" width="100%">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>' . strtoupper($category) . ' REVENUE SUMMARY REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Credit</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $servedby . '</td>
                         </tr>
                       </table>
                       <hr style="width:100%"/>
                       ';


    //retrieve all category

    $i = 1;
    $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
             <th>S/N</th><th>PRODUCT NAME</th><th style='text-align:right'>QTY</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
           </tr>
            <tr>
             <td colspan='5'> <hr style='width:100%'/></td>
           </tr>
           </thead>
           ";
    $total = 0;
    $totalCredit = 0;
    $totalsubCredit = 0;

$sqlItem = "SELECT distinct(tpl.Item_ID) FROM tbl_patient_payments as tpp
            LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$sub_category_id' $filter
             
           ";

    $getItemID = mysqli_query($conn,$sqlItem) or die(mysqli_error($conn));
    while ($rowitem = mysqli_fetch_array($getItemID)) {
        $sql = "SELECT 
                 (SELECT itm.Product_Name FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Product_Name,
                   
                (SELECT SUM(tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Quntity,"
                 
                  
                . "(SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  AND tpp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') GROUP BY itm.Product_Name) AS TotalsubCredit";



//        $sql="SELECT tpp.Patient_Payment_ID,itm.Product_Name,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
//               FROM tbl_patient_payments as tpp
//               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
//               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
//            
//               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter
//           " 
//          ;
        $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        while ($rowCredit = mysqli_fetch_array($queryResult)) {
            $totalsubCredit+=$rowCredit['TotalsubCredit'];
            $TotalItem =$rowCredit['Quntity'];

        $totalCredit+=$totalsubCredit;
        $total+=$totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td>' . $rowCredit['Product_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td></tr>';
        $totalsubCredit = 0;
        $SuperTotalItems += $TotalItem; //$TotalItem
        $TotalItem = 0;
        $i++;
    }
   
    // $data=$sql;
    //$data=mysql
    }
    
    $data.="<tr><td colspan='2' style='text-align:center;font-weight:bold'>Total</td><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

    $data.='</table>';
    
}

//For credit Cash report

if (isset($_GET['type']) && $_GET['type'] == 'credit_cash') {
    $fromDate = filter_input(INPUT_GET, 'fromDate');
    $todate = filter_input(INPUT_GET, 'todate');
    $sponsorID = filter_input(INPUT_GET, 'sponsorID');
    $sponsorname = filter_input(INPUT_GET, 'sponsorname');
    $employeeID = filter_input(INPUT_GET, 'employeeID');
    $employeeName = filter_input(INPUT_GET, 'employeeName');
    $patientStatus = filter_input(INPUT_GET, 'patientStatus');
    $sub_category = filter_input(INPUT_GET, 'sub_category');
    $sub_category_id = filter_input(INPUT_GET, 'sub_category_id');
    $category = filter_input(INPUT_GET, 'category');
    $category_id = filter_input(INPUT_GET, 'category_id');

    $query_string = 'category_id=' . $category_id . '&category=' . $category . '&type=credit_cash&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
//die($query_string);
    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time<='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }

    if (isset($sponsorname) && !empty($sponsorname) && trim($sponsorname) !== "All Sponsors") {
        $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
    }

    if (isset($employeeName) && !empty($employeeName) && trim($employeeName) !== "All Data Entry") {
        $filter.=" AND tpp.Employee_ID='$employeeID'";
        $servedby = 'Served By&nbsp;&nbsp;<b>' . $employeeName . '</b>';
    }
    if (isset($patientStatus) && !empty($patientStatus)) {
        if ($patientStatus === 'INPATIENT') {
            $filter.=" AND tpp.Billing_Type  IN ('Inpatient Credit','Inpatient Cash')";
        } else if ($patientStatus === 'OUTPATIENT') {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Outpatient Cash')";
        } else if ($patientStatus === "OUTPATIENT AND INPATIENT") {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit','Outpatient Cash','Inpatient Cash')";
        }
    }

    $data.='<table align="center" width="100%">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>' . strtoupper($category) . ' REVENUE SUMMARY REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Credit && Cash</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $servedby . '</td>
                         </tr>
                       </table>
                       <hr style="width:100%"/>
                       ';

    $sqlItem = "SELECT distinct(tpl.Item_ID) FROM tbl_patient_payments as tpp
            LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$sub_category_id' $filter
             
           ";

    $getItemID = mysqli_query($conn,$sqlItem) or die(mysqli_error($conn));

    $i = 1;
    $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
            <th>S/N</th><th>ITEM NAME</th><th style='text-align:right'>QTY CASH</th><th style='text-align:right'>QTY CREDIT</th><th style='text-align:right'>QTY</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
            </tr>   
            <tr>
             <td colspan='8'><hr width='100%'/></td>
           </tr>
           </thead>
           ";


    $total = 0;
    $totalCash = 0;
    $totalCredit = 0;
    $totalsubCredit = 0;
    $totalsubCash = 0;
    
    $totalSuperQtyCredit = 0;
    $totalSuperQtyCash = 0;

// 
//         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID,Item_Subcategory_Name FROM tbl_item_subcategory WHERE Item_Category_ID='".$sub_category_id."'");
//         
//         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
//          $subCatID=$rowSubcat['Item_Subcategory_ID'];
//         //echo $subCatID;
//         //exit;


    while ($rowitem = mysqli_fetch_array($getItemID)) {
        $sql = "SELECT 
                 (SELECT itm.Product_Name FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Product_Name,
                   
               
               (SELECT SUM(tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "' AND tpp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') GROUP BY itm.Product_Name) AS TotalQtyCredit,
               
               (SELECT SUM(tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  AND tpp.Billing_Type IN ('Inpatient Cash','Outpatient Cash') GROUP BY itm.Product_Name) AS TotalQtyCash,


               (SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "' AND tpp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') GROUP BY itm.Product_Name) AS TotalsubCredit,
               
               (SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  AND tpp.Billing_Type IN ('Inpatient Cash','Outpatient Cash') GROUP BY itm.Product_Name) AS TotalsubCash";


//        $sql="SELECT tpp.Patient_Payment_ID,itm.Product_Name,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
//               FROM tbl_patient_payments as tpp
//               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
//               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
//            
//               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter
//           " 
//          ;
        $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        while ($rowTpl = mysqli_fetch_array($queryResult)) {
            $totalsubCredit+=$rowTpl['TotalsubCredit'];
            $totalsubCash+=$rowTpl['TotalsubCash'];
           // $TotalItem =$rowTpl['Quntity'];
            
            $totalQtyCredit = (empty($rowTpl['TotalQtyCredit'])?0:$rowTpl['TotalQtyCredit']);
            $totalQtyCash = (empty($rowTpl['TotalQtyCash'])?0:$rowTpl['TotalQtyCash']);
           
            $TotalItem=$totalQtyCredit+$totalQtyCash;
            
            $totalSuperQtyCredit +=$totalQtyCredit;
            $totalSuperQtyCash +=$totalQtyCash;


            $totalCash+=$totalsubCash;
            $totalCredit+=$totalsubCredit;
            $total+=$totalsubCash + $totalsubCredit;
            // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
            $data.='<tr><td>' . $i . '</td><td>' . $rowTpl['Product_Name'] . '</td><td style="text-align:right">' . $totalQtyCash . '</td><td style="text-align:right">' . $totalQtyCredit . '</td><td style="text-align:right">' . $TotalItem . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format($totalsubCash + $totalsubCredit) . '</td></tr>';
            $totalsubCredit = 0;
            $totalsubCash = 0;
            $totalQtyCredit = 0;
            $totalQtyCash = 0;
            $SuperTotalItems += $TotalItem; //$TotalItem
            $TotalItem = 0;
            $i++;
        }
    }
    
     $data.="<tr><td colspan='2' style='text-align:center;font-weight:bold'>Total</td><td style='text-align:right'><b>".number_format($totalSuperQtyCash)."</b></td><td style='text-align:right'><b>".number_format($totalSuperQtyCredit)."</b></td><td style='text-align:right'><b>".number_format($SuperTotalItems)."</b></td><td style='text-align:right'><b>".number_format($totalCash)."</b></td><td style='text-align:right'><b>".number_format($totalCredit)."</b></td><td style='text-align:right'><b>".number_format($total)."</b></td></tr>";
     
     $data.='</table>';
}

//For cancelled report

if (isset($_GET['type']) && $_GET['type'] == 'canceled') {
    $fromDate = filter_input(INPUT_GET, 'fromDate');
    $todate = filter_input(INPUT_GET, 'todate');
    $sponsorID = filter_input(INPUT_GET, 'sponsorID');
    $sponsorname = filter_input(INPUT_GET, 'sponsorname');
    $employeeID = filter_input(INPUT_GET, 'employeeID');
    $employeeName = filter_input(INPUT_GET, 'employeeName');
    $patientStatus = filter_input(INPUT_GET, 'patientStatus');
    $sub_category = filter_input(INPUT_GET, 'sub_category');
    $sub_category_id = filter_input(INPUT_GET, 'sub_category_id');
    $category = filter_input(INPUT_GET, 'category');
    $category_id = filter_input(INPUT_GET, 'category_id');

    $query_string = 'category_id=' . $category_id . '&category=' . $category . '&type=canceled&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
 
    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time<='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }

    if (isset($sponsorname) && !empty($sponsorname) && trim($sponsorname) !== "All Sponsors") {
        $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
    }

    if (isset($employeeName) && !empty($employeeName) && trim($employeeName) !== "All Data Entry") {
        $filter.=" AND tpp.Employee_ID='$employeeID'";
        $servedby = 'Served By&nbsp;&nbsp;<b>' . $employeeName . '</b>';
    }
    if (isset($patientStatus) && !empty($patientStatus)) {
        if ($patientStatus === 'INPATIENT') {
            $filter.=" AND tpp.Billing_Type  IN ('Inpatient Credit','Inpatient Cash')";
        } else if ($patientStatus === 'OUTPATIENT') {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Outpatient Cash')";
        } else if ($patientStatus === "OUTPATIENT AND INPATIENT") {
            $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit','Outpatient Cash','Inpatient Cash')";
        }
    }

    $data.='<table align="center" width="100%">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>' . strtoupper($category) . ' REVENUE SUMMARY REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Cancelled</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $servedby . '</td>
                         </tr>
                       </table>
                         <hr width="100%"/>
                       ';



    $i = 1;
    $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
             <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
           </tr> 
            <tr>
             <td colspan='6'><hr width='100%'/></td>
           </tr>
           </thead>
           ";
    $total = 0;
    $totalsubCash = 0;
    $totalsubCredit = 0;
    $totalCash = 0;
    $totalCredit = 0;
    
    $sqlItem = "SELECT distinct(tpl.Item_ID) FROM tbl_patient_payments as tpp
            LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status ='cancelled' AND itm.Item_Subcategory_ID='$sub_category_id' $filter
             
           ";

    $getItemID = mysqli_query($conn,$sqlItem) or die(mysqli_error($conn));

while ($rowitem = mysqli_fetch_array($getItemID)) {
        $sql = "SELECT 
                 (SELECT itm.Product_Name FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Product_Name,
                   
                (SELECT SUM(tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  GROUP BY itm.Product_Name) AS Quntity,"
                . "(SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "' AND tpp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') GROUP BY itm.Product_Name) AS TotalsubCredit,"
                . ""
                . "(SELECT SUM((tpl.Price-tpl.Discount)*tpl.Quantity) FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$sub_category_id' $filter AND tpl.Item_ID='" . $rowitem['Item_ID'] . "'  AND tpp.Billing_Type IN ('Inpatient Cash','Outpatient Cash') GROUP BY itm.Product_Name) AS TotalsubCash";


        $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        while ($rowTpl = mysqli_fetch_array($queryResult)) {
            $totalsubCredit+=$rowTpl['TotalsubCredit'];
            $totalsubCash+=$rowTpl['TotalsubCash'];
            $TotalItem =$rowTpl['Quntity'];


            $totalCash+=$totalsubCash;
            $totalCredit+=$totalsubCredit;
            $total+=$totalsubCash + $totalsubCredit;
            // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
            $data.='<tr><td>' . $i . '</td><td>' . $rowTpl['Product_Name'] . '</td><td style="text-align:right">' . $TotalItem . '</td><td style="text-align:right">' . $totalsubCash . '</td><td style="text-align:right">' . $totalsubCredit . '</td><td style="text-align:right">' . ($totalsubCash + $totalsubCredit) . '</td></tr>';
            $totalsubCredit = 0;
            $totalsubCash = 0;
            $SuperTotalItems += $TotalItem; //$TotalItem
            $TotalItem = 0;
            $i++;
        }
    }
    
     $data.="<tr><td colspan='2' style='text-align:center;font-weight:bold'>Total</td><td style='text-align:right'><b>".number_format($SuperTotalItems)."</b></td><td style='text-align:right'><b>".number_format($totalCash)."</b></td><td style='text-align:right'><b>".number_format($totalCredit)."</b></td><td style='text-align:right'><b>".number_format($total)."</b></td></tr>";
     
     $data.='</table>';
}
 
 if($data !==''){
 include("./MPDF/mpdf.php");

    $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($data,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
 }  else {
     echo '<h1>No Data To Print</h1><p><i>Try again</i></p>';     
}
 
 ?>
