
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$todayqr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
$today = $todayqr; //date('Y-m-d H:m:s');
$data = '';
$title_for_report = '';

$query_string = '';
$TotalItem = 0;
$SuperTotalItems = 0;

        $fromDate= '';
        $todate=  '';
        $sponsorID=  '';
        $sponsorname= '';
        $employeeID=  '';
        $employeeName=  '';
        $patientStatus= '';
        $category='';
        $category_id='';
        

if (isset($_POST['cash']) || (isset($_GET['type']) && $_GET['type']=='cash')) {
    
    if (isset($_POST['cash'])) {
        
        $fromDate = filter_input(INPUT_POST, 'fromDate');
        $todate = filter_input(INPUT_POST, 'todate');
        $sponsorID = filter_input(INPUT_POST, 'sponsorID');
        $sponsorname = filter_input(INPUT_POST, 'sponsorname');
        $employeeID = filter_input(INPUT_POST, 'employeeID');
        $employeeName = filter_input(INPUT_POST, 'DataEntryName');
        $patientStatus = filter_input(INPUT_POST, 'patientStatus');
    } elseif (isset($_GET['type']) && $_GET['type']=='cash') {
        $fromDate=  filter_input(INPUT_GET, 'fromDate');
        $todate=  filter_input(INPUT_GET, 'todate');
        $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
        $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
        $employeeID=  filter_input(INPUT_GET, 'employeeID');
        $employeeName=  filter_input(INPUT_GET, 'employeeName');
        $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
        $category=filter_input(INPUT_GET, 'category_name');
        $category_id=filter_input(INPUT_GET, 'category_id');
        
    }


    $query_string = 'type=cash&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
    
    $filter = '';
    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time <='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $today . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $today . '</b>';
        $fromDate = $today;
        $todate = $today;

        $query_string = 'type=cash&fromDate=' . $today . '&todate=&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
    }

     //echo $fromDate.' '.$todate;exit;
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

    $data.='<table align="center" width="80%" border="0" >
                         <tr>
                         <td colspan="2" style="text-align:center"><b>TOTAL REVENUE SUMMARY REPORT</b></td>
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
                        <hr style="width:80%"/>
                       ';

    //die($filter);
    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                 
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;
    $data.="<table width = '80%'>
           <tr>
             <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CASH</th><th style='text-align:right'>TOTAL</th>
           </tr>
           <tr>
             <td colspan='6'> <hr style='width:100%'/></td>
           </tr>
           ";

    $total = 0;
    $totalCash = 0;
    $totalsubCash = 0;
    $totolPatients=0;
    while ($row = mysqli_fetch_array($categoryRs)) {
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
         $NumberOFPatients=0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter
           ";
            
            $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];


            //die( $sql);
            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowCash = mysqli_fetch_array($queryResult)) {
                $totalsubCash+=($rowCash['Price'] - $rowCash['Discount']) * $rowCash['Quantity'];
                $TotalItem += $rowCash['Quantity'];
            }
        }
 $totolPatients +=$NumberOFPatients;
        $totalCash+=$totalsubCash;
        $total+=$totalsubCash;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td><a href="individualrevenue.php?category_id=' . $row['Item_Category_ID'] . '&category_name=' . $row['Item_Category_Name'] . '&' . $query_string . '">' . $row['Item_Category_Name'] . '</a></td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td></tr>';
//         $data.="<tr>
//             <td colspan='4'> <hr style='width:100%'/></td>
//           </tr>";
        $totalsubCash = 0;
        $SuperTotalItems += $TotalItem; //$TotalItem
        $TotalItem = 0;

        $i++;
    }
    $data.="<tr><td colspan='2' style='text-align:center'><b>Total</b></td><td style='text-align:right'><b>".number_format($SuperTotalItems)."</b></td><td style='text-align:right'><b>".number_format($totolPatients)."</b></td><td style='text-align:right'><b>".number_format($totalCash)."</b></td><td style='text-align:right'><b>".number_format($total)."</b></td></tr>";
     
    $data.='</table>';
    // $data=$sql;
    //$data=mysql
}

//For credit report

if (isset($_POST['credit']) || (isset($_GET['type']) && $_GET['type']=='credit')) {
    //echo 'Yes';exit;
    if (isset($_POST['credit'])) {
        $fromDate = filter_input(INPUT_POST, 'fromDate');
        $todate = filter_input(INPUT_POST, 'todate');
        $sponsorID = filter_input(INPUT_POST, 'sponsorID');
        $sponsorname = filter_input(INPUT_POST, 'sponsorname');
        $employeeID = filter_input(INPUT_POST, 'employeeID');
        $employeeName = filter_input(INPUT_POST, 'DataEntryName');
        $patientStatus = filter_input(INPUT_POST, 'patientStatus');
    } elseif (isset($_GET['type']) && $_GET['type']=='credit') {
        $fromDate=  filter_input(INPUT_GET, 'fromDate');
        $todate=  filter_input(INPUT_GET, 'todate');
        $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
        $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
        $employeeID=  filter_input(INPUT_GET, 'employeeID');
        $employeeName=  filter_input(INPUT_GET, 'employeeName');
        $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
        $category=filter_input(INPUT_GET, 'category_name');
        $category_id=filter_input(INPUT_GET, 'category_id');
        
    }

    $query_string = 'type=credit&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;


    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time <='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }if (isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $today . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $today . '</b>';
        $fromDate = $today;
        $todate = $today;
        $query_string = 'type=cash&fromDate=' . $today . '&todate=&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
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

    $data.='<table align="center" width="80%" border="0">
                         <tr>
                         <th colspan="2"><b>TOTAL REVENUE SUMMARY REPORT</b></th>
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
                         <hr style="width:80%"/>
                       ';


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;
    $data.="<table width = '80%'>
           <tr>
             <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
           </tr>
           <tr>
            <td colspan='6'> <hr style='width:100%'/></td>
            </tr>
           ";
    $total = 0;
    $totalCredit = 0;
    $totalsubCredit = 0;
    $totolPatients=0;
    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
$NumberOFPatients=0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter";
            
            $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowCredit = mysqli_fetch_array($queryResult)) {
                $totalsubCredit+=($rowCredit['Price'] - $rowCredit['Discount']) * $rowCredit['Quantity'];
                $TotalItem +=$rowCredit['Quantity'];
            }
        }
        
         $totolPatients +=$NumberOFPatients;
        $totalCredit+=$totalsubCredit;
        $total+=$totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td><a href="individualrevenue.php?category_id=' . $row['Item_Category_ID'] . '&category_name=' . $row['Item_Category_Name'] . '&' . $query_string . '">' . $row['Item_Category_Name'] . '</a></td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td></tr>';
        $totalsubCredit = 0;
        $SuperTotalItems +=$TotalItem;
        $TotalItem = 0;
        $i++;
    }
    $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

    $data.='</table>';
    // $data=$sql;
    //$data=mysql
}

//For credit Cash report

if (isset($_POST['credit_cash']) || (isset($_GET['type']) && $_GET['type']=='credit_cash')) {
    //echo 'Yes';exit;
    if (isset($_POST['credit_cash'])) {
        $fromDate = filter_input(INPUT_POST, 'fromDate');
        $todate = filter_input(INPUT_POST, 'todate');
        $sponsorID = filter_input(INPUT_POST, 'sponsorID');
        $sponsorname = filter_input(INPUT_POST, 'sponsorname');
        $employeeID = filter_input(INPUT_POST, 'employeeID');
        $employeeName = filter_input(INPUT_POST, 'DataEntryName');
        $patientStatus = filter_input(INPUT_POST, 'patientStatus');
    } elseif (isset($_GET['type']) && $_GET['type']=='credit_cash') {
        $fromDate=  filter_input(INPUT_GET, 'fromDate');
        $todate=  filter_input(INPUT_GET, 'todate');
        $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
        $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
        $employeeID=  filter_input(INPUT_GET, 'employeeID');
        $employeeName=  filter_input(INPUT_GET, 'employeeName');
        $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
        $category=filter_input(INPUT_GET, 'category_name');
        $category_id=filter_input(INPUT_GET, 'category_id');
        
    }

    $query_string = 'type=credit_cash&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;

    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time <='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }if (isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $today . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $today . '</b>';
        $fromDate = $today;
        $todate = $today;
        $query_string = 'type=cash&fromDate=' . $today . '&todate=&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
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

    $data.='<table align="center" width="80%" border="0">
                         <tr>
                         <th colspan="2"><b>TOTAL REVENUE SUMMARY REPORT</b></th>
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
                        <hr style="width:80%"/>
                       ';


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                   
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;
    $data.="<table width = '80%' border='0'>
           <tr>
             <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
           </tr> 
           <tr>
             <td colspan='7'><hr width='100%'/></td>
           </tr>
           ";
    $total = 0;
    $totalCash = 0;
    $totalCredit = 0;
    $totalsubCredit = 0;
    $totalsubCash = 0;
    
    $totolPatients=0;

    while ($row = mysqli_fetch_array($categoryRs)) {
         $NumberOFPatients=0;
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");

        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID

                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter" ;
            
            $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter  "
                    . "") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];
            
            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowTpl = mysqli_fetch_array($queryResult)) {
                
                
                if (($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit')) {
                    $totalsubCredit+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                    $TotalItem +=$rowTpl['Quantity'];
                } else if ($rowTpl['Billing_Type'] == 'Inpatient Cash' || $rowTpl['Billing_Type'] == 'Outpatient Cash') {
                    $totalsubCash+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                    $TotalItem +=$rowTpl['Quantity'];
                }
            }
        }
        
        $totolPatients +=$NumberOFPatients;
        $totalCash+=$totalsubCash;
        $totalCredit+=$totalsubCredit;
        $total+=$totalsubCash + $totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td><a href="individualrevenue.php?category_id=' . $row['Item_Category_ID'] . '&category_name=' . $row['Item_Category_Name'] . '&' . $query_string . '">' . $row['Item_Category_Name'] . '</a></td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
        $totalsubCredit = 0;
        $totalsubCash = 0;
        $totalsubCredit = 0;
        $SuperTotalItems +=$TotalItem;
        $TotalItem = 0;
        $i++;
    }
    $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCash) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

    $data.='</table>';
    // $data=$sql;
    //$data=mysql
}

//For cancelled report

if (isset($_POST['canceled']) || (isset($_GET['type']) && $_GET['type']=='canceled')) {
    //echo 'Yes';exit;
    if (isset($_POST['canceled'])) {
        $fromDate = filter_input(INPUT_POST, 'fromDate');
        $todate = filter_input(INPUT_POST, 'todate');
        $sponsorID = filter_input(INPUT_POST, 'sponsorID');
        $sponsorname = filter_input(INPUT_POST, 'sponsorname');
        $employeeID = filter_input(INPUT_POST, 'employeeID');
        $employeeName = filter_input(INPUT_POST, 'DataEntryName');
        $patientStatus = filter_input(INPUT_POST, 'patientStatus');
    } elseif (isset($_GET['type']) && $_GET['type']=='canceled') {
        $fromDate=  filter_input(INPUT_GET, 'fromDate');
        $todate=  filter_input(INPUT_GET, 'todate');
        $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
        $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
        $employeeID=  filter_input(INPUT_GET, 'employeeID');
        $employeeName=  filter_input(INPUT_GET, 'employeeName');
        $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
        $category=filter_input(INPUT_GET, 'category_name');
        $category_id=filter_input(INPUT_GET, 'category_id');
        
    }

    $query_string = 'type=canceled&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;

    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    $servedby = '<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time <='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $today . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $today . '</b>';
        $fromDate = $today;
        $todate = $today;
        $query_string = 'type=cash&fromDate=' . $today . '&todate=&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname . '&employeeID=' . $employeeID . '&employeeName=' . $employeeName . '&patientStatus=' . $patientStatus;
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

    $data.='<table align="center" width="80%" border="0">
                         <tr>
                         <th colspan="2"><b>TOTAL REVENUE SUMMARY REPORT</b></th>
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
                       <hr width="80%"/>
                       ';


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;
    $data.="<table width = '80%'>
           <tr>
                <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
           </tr> 
           <tr>
             <td colspan='7'><hr width='100%'/></td>
           </tr>";
    $total = 0;
    $totalsubCash = 0;
    $totalsubCredit = 0;
    $totalCash = 0;
    $totalCredit = 0;
    $totolPatients=0;
    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");

        $NumberOFPatients=0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           "
            ;
            
            $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowTpl = mysqli_fetch_array($queryResult)) {
                if ($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit') {
                    $totalsubCredit+=($rowTpl['Price'] * $rowTpl['Discount']) * $rowTpl['Quantity'];
                    $TotalItem +=$rowTpl['Quantity'];
                } else if ($rowTpl['Billing_Type'] == 'Inpatient Cash' || $rowTpl['Billing_Type'] == 'Outpatient Cash') {
                    $totalsubCash+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                    $TotalItem +=$rowTpl['Quantity'];
                }
            }
        }
         $totolPatients +=$NumberOFPatients;
        $totalCash+=$totalsubCash;
        $totalCredit+=$totalsubCredit;
        $total+=$totalsubCash + $totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        $data.='<tr><td>' . $i . '</td><td><a href="individualrevenue.php?category_id=' . $row['Item_Category_ID'] . '&category_name=' . $row['Item_Category_Name'] . '&' . $query_string . '">' . $row['Item_Category_Name'] . '</a></td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
        $totalsubCredit = 0;
        $totalsubCash = 0;
        $totalsubCredit = 0;
        $SuperTotalItems +=$TotalItem;
        $TotalItem = 0;
        $i++;
    }
    $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCash) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

    $data.='</table>';
    // $data=$sql;
    //$data=mysql
}

if (isset($_POST['showSponsorData'])) {
    $fromDate = filter_input(INPUT_POST, 'fromDate');
    $todate = filter_input(INPUT_POST, 'todate');
    $temp = filter_input(INPUT_POST, 'sponsor');
    $sponsorID = '';
    $sponsorname = 'All Sponsors';

    if ($temp == 'All Sponsors') {
        
    } else {
        $temp = explode("$$>", $temp);
        $sponsorID = $temp[0];
        $sponsorname = $temp[1];
    }

    $query_string = 'type=showSponsorData&fromDate=' . $fromDate . '&todate=' . $todate . '&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname;


//     echo $sponsorID.' '.$sponsorname.'<br/>';
//     exit;
//     

    $filter = '';

    $duration = '<b>All Time Revenue</b>';
    //$servedby='<b>Services By All employees</b>';

    if (isset($fromDate) && !empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $fromDate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>';
    }
    if (isset($todate) && !empty($todate)) {
        $filter = "AND tpp.Payment_Date_And_Time <='" . $todate . "'";
        $duration = 'To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }
    if (isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)) {
        $filter = " AND tpp.Payment_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $todate . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $fromDate . '</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>' . $todate . '</b>';
    }if (isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)) {
        $filter = "AND tpp.Payment_Date_And_Time >='" . $today . "'";
        $duration = 'From&nbsp;&nbsp; <b>' . $today . '</b>';
        $fromDate = $today;
        $todate = $today;
        $query_string = 'type=showSponsorData&fromDate=' . $today . '&todate=&sponsorID=' . $sponsorID . '&sponsorname=' . $sponsorname;
    }


    if ($sponsorID !== '') {


        $data.='<table align="center" width="80%" border="0" class="hv_table">
                         <tr>
                          <td colspan="2"><b>REVENUE GROUPED REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                       </table>
                       <p width = "80%"><b>' . strtoupper($sponsorname) . ' SERVICES</b></p>
                       ';

        //retrieve all category
        $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
        $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));


        $total = 0;

        $totalQty = 0;
        $totalSubQty = 0;
        $totalSubAmount = 0;

        while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the Subcategory
            $i = 1;

            $sqlIterms = "SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";
            $itemsRs = mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
            $data.="<br/><table width = '80%'>
           <tr><td colspan='4'><b>" . $row['Item_Category_Name'] . "</b></td></tr>
           <tr>
             <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
           </tr>     
           ";
            //Retreive Iterms and filter
            while ($rowItems = mysqli_fetch_array($itemsRs)) {
                $sqlSubcategory = "SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='" . $rowItems['Item_Subcategory_ID'] . "' AND Item_category_ID='" . $row['Item_Category_ID'] . "'";
                $subcategoryRs = mysqli_query($conn,$sqlSubcategory) or die(mysqli_fetch_assoc($result));

                if (mysqli_num_rows($subcategoryRs) > 0) {
                    $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
                WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='" . $rowItems['Item_ID'] . "' $filter

               "
                    ;

                    $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                    while ($rowTpl = mysqli_fetch_array($queryResult)) {


                        if ($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit') {
                            // if($rowTpl['Process_Status']=='served'){
                            $totalSubQty+=$rowTpl['Quantity'];
                            $totalSubAmount+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                            // }
                        } else {
                            $totalSubQty+=$rowTpl['Quantity'];
                            $totalSubAmount+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                        }
                    }
                }
                $totalQty+=$totalSubQty;
                $total+=$totalSubAmount;


                if ($totalSubQty > 0) {
                    $data.='<tr><td>' . $i . '</td><td>' . $rowItems['Product_Name'] . '</td><td>' . number_format($totalSubQty) . '</td><td>' . number_format($totalSubAmount) . '</td></tr>';
                    $i++;
                }
                $totalSubAmount = 0;
                $totalSubQty = 0;
            }
            $data.="<tr><td colspan='2' style='text-align:right'><b>Total</b></td><td><b><b>" . number_format($totalQty) . "</b></td><td><b>" . number_format($total) . "</b></td></tr>";

            $data.='</table><p>&nbsp;</p>';

            $totalQty = 0;
            $total = 0;
        }
    } else if ($sponsorname == 'All Sponsors') {


        $data.='<table align="center" class="hv_table" width="80%" border="0">
                         <tr>
                         <td colspan="2"><b>REVENUE GROUPED REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">' . $duration . '</td>
                         </tr>
                       </table>
                       <p width = "80%"><b>ALL SPONSORS SERVICES</b></p>
                       ';

        $query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));

        while ($rowSponsors = mysqli_fetch_array($query)) {
            // $data.= '<option value="'.$rowSponsors['Sponsor_ID'].'$$>'.$rowSponsors['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
            //retrieve all category
            $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
            $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));


            $total = 0;

            $totalQty = 0;
            $totalSubQty = 0;
            $totalSubAmount = 0;

            $data.="<br/><table width = '80%'>
           <tr>
             <th colspan='4'>" . strtoupper($rowSponsors['Guarantor_Name']) . " SERVICES</th>
           </tr>     
           ";

            while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the Subcategory
                $i = 1;

                $sqlIterms = "SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";
                $itemsRs = mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
                $data.="<br/><table width = '80%'>
           <tr><td colspan='4'><b>" . $row['Item_Category_Name'] . "</b></td></tr>
           <tr>
             <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
           </tr>     
           ";
                //Retreive Iterms and filter
                while ($rowItems = mysqli_fetch_array($itemsRs)) {
                    $sqlSubcategory = "SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='" . $rowItems['Item_Subcategory_ID'] . "' AND Item_category_ID='" . $row['Item_Category_ID'] . "'";
                    $subcategoryRs = mysqli_query($conn,$sqlSubcategory) or die(mysqli_error($conn));

                    //echo ( $sqlSubcategory).'<br/>';

                    if (mysqli_num_rows($subcategoryRs) > 0) {
                        $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
                WHERE tpp.Sponsor_ID='" . $rowSponsors['Sponsor_ID'] . "' AND tpl.Item_ID='" . $rowItems['Item_ID'] . "' $filter

               "
                        ;




                        $u = 1;
                        $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        while ($rowTpl = mysqli_fetch_array($queryResult)) {


                            if ($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit') {
                                //if($rowTpl['Process_Status']=='served'){
                                $totalSubQty+=$rowTpl['Quantity'];
                                $totalSubAmount+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                                //}
                            } else {
                                $totalSubQty+=$rowTpl['Quantity'];
                                $totalSubAmount+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                            }
                        }
                    }
                    $totalQty+=$totalSubQty;
                    $total+=$totalSubAmount;


                    if ($totalSubQty > 0) {
                        $data.='<tr><td>' . $i . '</td><td>' . $rowItems['Product_Name'] . '</td><td>' . number_format($totalSubQty) . '</td><td>' . number_format($totalSubAmount) . '</td></tr>';
                        $i++;
                    }
                    $totalSubAmount = 0;
                    $totalSubQty = 0;
                }
                $data.="<tr><td colspan='2' style='text-align:right'><b>Total</b></td><td><b><b>" . number_format($totalQty) . "</b></td><td><b>" . number_format($total) . "</b></td></tr>";

                $data.='</table><p>&nbsp;</p>';

                $totalQty = 0;
                $total = 0;
            }
        }
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<?php
$q = '';
if (isset($_POST['showSponsorData'])) {
    $q = '<a href="revenuegroupprint.php?' . $query_string . '" target="_blank" style=""><button type="button" class="art-button-green">Print</button></a>';
    echo '<a href="generalledgercenter.php" target="" style=""><button type="button" class="art-button-green">Back</button></a>';
} else {
    $q = '<a href="revenueprint.php?' . $query_string . '" target="_blank" style=""><button type="button" class="art-button-green">Print</button></a>';
     $qGraph = '<button type="button" onclick="getGraph(\'' . $query_string . '\')" class="art-button-green">Graph</button></a>';
    echo '<a href="revenue.php?' . $query_string . '" target="" style=""><button type="button" class="art-button-green">Back</button></a>';
}
echo $qGraph;
echo $q;
?>

<style>
    table{
        border:none !important; 
    }td{
        border:none !important; 
    } th{
        border:none !important; 
    } tr{
        border:none !important; 
    }   
</style>
<br/><br/> 

<fieldset style="background-color:white;font-size:larger">
    <div style="height:500px;overflow-y: scroll; overflow-x: hidden;   "> 
        <center>

<?php echo $data ?>
            <br/><br/>
        </center>
    </div>

</fieldset>     
<div id="showdata" style="width:100%;height:500px;margin:auto; overflow-x:hidden;overflow-y:scroll; display:none;text-align: center">
    <div id="contentdata" style="text-align: center">
    </div>
    <a href="printrevenuegraph.php?<?php echo $query_string ;?>" class="art-button-green" target="_blank">Print</a>
</div>
<script>
    function getGraph(href) {
        var datastring = href;

        $('#showdata').dialog({
            modal: true,
            width: '90%',
            height: 550,
            resizable: true,
            draggable: true,
        });
        
      $('#contentdata').html('');
      
        $.ajax({
            type: 'GET',
            url: 'getRevenueGraph.php',
            data: datastring,
            beforeSend: function (xhr) {
                $('#contentdata').html('<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
            },
            success: function (result) {
              if(result !=''){
                $('#contentdata').html(result);
              }
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
        
        $("#showdata").dialog("option", "title", "REVENUE STATISTICS");

    }
</script>
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<?php
include("./includes/footer.php");
?>