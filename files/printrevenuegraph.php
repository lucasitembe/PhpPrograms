<?php

include("./includes/connection.php");
include './libchart/libchart/classes/libchart.php';

$data = '';
$title_for_report = '';

$query_string = '';

$totolPatients = 0;

if (isset($_GET['type']) && $_GET['type'] == 'cash') {
    $fromDate = filter_input(INPUT_GET, 'fromDate');
    $todate = filter_input(INPUT_GET, 'todate');
    $sponsorID = filter_input(INPUT_GET, 'sponsorID');
    $sponsorname = filter_input(INPUT_GET, 'sponsorname');
    $employeeID = filter_input(INPUT_GET, 'employeeID');
    $employeeName = filter_input(INPUT_GET, 'employeeName');
    $patientStatus = filter_input(INPUT_GET, 'patientStatus');


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

    $chart = new VerticalBarChart(900, 450);
    $dataSet = new XYDataSet();


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;


    $total = 0;
    $totalCash = 0;
    $totalsubCash = 0;
    $totolPatients = 0;
    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
        $NumberOFPatients = 0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           "
            ;
            $getpatients = mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));

            $NumberOFPatients += mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowCash = mysqli_fetch_array($queryResult)) {
                $totalsubCash+=($rowCash['Price'] - $rowCash['Discount']) * $rowCash['Quantity'];
            }
        }
        $dataSet->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCash / pow(10, 5)))));
        $totalsubCash = 0;
    }

    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphCaptionRatio(0.87);
    // $chart->getPlot()->setOuterPadding(new Padding(5, 30, 20, 140));//Padding($top, $right, $bottom, $left)
    $chart->getPlot()->setGraphPadding(new Padding(10, 30, 20, 30));
    $chart->setTitle("                       REVENUE STATISTICS \n FROM " . $fromDate . " TO " . $todate . " \n                                    CASH \n                                 ( x  10^5 )");
    $chart->getPlot()->setTitleHeight(150);


    $chart->render("generated/revenue.png");
    // $data=$sql;
    //$data=mysql
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

    $chart = new VerticalBarChart(900, 450);
    $dataSet = new XYDataSet();



    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;


    $total = 0;
    $totalCash = 0;
    $totalsubCash = 0;
    $totalsubCredit = 0;
    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
        $NumberOFPatients = 0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           "
            ;
            $getpatients = mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));

            $NumberOFPatients += mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowCredit = mysqli_fetch_array($queryResult)) {
                $totalsubCredit+=($rowCredit['Price'] - $rowCredit['Discount']) * $rowCredit['Quantity'];
            }
        }
        $totolPatients +=$NumberOFPatients;


        $dataSet->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCredit / pow(10, 5)))));
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
        //$data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td></tr>';
        $totalsubCredit = 0;

        $i++;
    }

    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphCaptionRatio(0.87);
    // $chart->getPlot()->setOuterPadding(new Padding(5, 30, 20, 140));//Padding($top, $right, $bottom, $left)
    $chart->getPlot()->setGraphPadding(new Padding(10, 30, 20, 30));
    $chart->setTitle("                       REVENUE STATISTICS \n FROM " . $fromDate . " TO " . $todate . " \n                                    CREDIT \n                                 ( x  10^5 )");
    $chart->getPlot()->setTitleHeight(150);


    $chart->render("generated/revenue.png");
    // $data=$sql;
    //$data=mysql
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

    $chart = new VerticalBarChart(900, 450);
//Category cash
    $serie1 = new XYDataSet();

//Category Credit
    $serie2 = new XYDataSet();

    $dataSet = new XYSeriesDataSet();


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                   
                  ";


    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;

    $total = 0;
    $totalCash = 0;
    $totalCredit = 0;
    $totalsubCredit = 0;
    $totalsubCash = 0;

    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
        $NumberOFPatients = 0;
        while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
            $subCatID = $rowSubcat['Item_Subcategory_ID'];
            //echo $subCatID;
            //exit;
            $sql = "SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           "
            ;

            $getpatients = mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));

            $NumberOFPatients += mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowTpl = mysqli_fetch_array($queryResult)) {
                if (($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit')) {
                    $totalsubCredit+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                    //$TotalItem +=$rowTpl['Quantity'];
                } else if ($rowTpl['Billing_Type'] == 'Inpatient Cash' || $rowTpl['Billing_Type'] == 'Outpatient Cash') {
                    $totalsubCash+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                    // $TotalItem +=$rowTpl['Quantity'];
                }
            }
        }
        $totolPatients +=$NumberOFPatients;
        $totalCash+=$totalsubCash;
        $totalCredit+=$totalsubCredit;
        $total+=$totalsubCash + $totalsubCredit;

        //Category cash
        $serie1->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCash / pow(10, 5)))));

        $serie2->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCredit / pow(10, 5)))));

        //$data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
        $totalsubCredit = 0;
        $totalsubCash = 0;
        $totalsubCredit = 0;
        $TotalItem = 0;
        $i++;
    }

    $dataSet->addSerie("Cash", $serie1);
    $dataSet->addSerie("Credit", $serie2);
    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphCaptionRatio(0.87);
    // $chart->getPlot()->setOuterPadding(new Padding(5, 30, 20, 140));//Padding($top, $right, $bottom, $left)
    $chart->getPlot()->setGraphPadding(new Padding(10, 30, 20, 30));
    //$chart->setTitle("TOTAL REVENUE SUMMARY \n         CREDIT AND CASH \n                 ( x  10^5 )");
    $chart->setTitle("                       REVENUE STATISTICS \n FROM " . $fromDate . " TO " . $todate . " \n                       CREDIT AND CASH \n                                 ( x  10^5 )");
    $chart->getPlot()->setTitleHeight(150);

    $chart->render("generated/revenue.png");


    //$data=mysql
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

    $chart = new VerticalBarChart(900, 450);
//Category cash
    $serie1 = new XYDataSet();

//Category Credit
    $serie2 = new XYDataSet();

    $dataSet = new XYSeriesDataSet();


    //retrieve all category
    $sqlCategory = "SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";

    $categoryRs = mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
    $i = 1;
   
    $total = 0;
    $totalsubCash = 0;
    $totalsubCredit = 0;
    $totalCash = 0;
    $totalCredit = 0;
    while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
        $querySubcategory = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='" . $row['Item_Category_ID'] . "'");
        $NumberOFPatients = 0;
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
            $getpatients = mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status ='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));

            $NumberOFPatients += mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

            $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            while ($rowTpl = mysqli_fetch_array($queryResult)) {
                if ($rowTpl['Billing_Type'] == 'Inpatient Credit' || $rowTpl['Billing_Type'] == 'Outpatient Credit') {
                    $totalsubCredit+=($rowTpl['Price'] * $rowTpl['Discount']) * $rowTpl['Quantity'];
                   
                } else if ($rowTpl['Billing_Type'] == 'Inpatient Cash' || $rowTpl['Billing_Type'] == 'Outpatient Cash') {
                    $totalsubCash+=($rowTpl['Price'] - $rowTpl['Discount']) * $rowTpl['Quantity'];
                   
                }
            }
        }
        //Category cash
        $serie1->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCash / pow(10, 3)))));

        $serie2->addPoint(new Point(substr($row['Item_Category_Name'], 0, 13), round(($totalsubCredit / pow(10, 3)))));

        //$data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
        $totalsubCredit = 0;
        $totalsubCash = 0;
        $totalsubCredit = 0;
    }

    $dataSet->addSerie("Cash", $serie1);
    $dataSet->addSerie("Credit", $serie2);
    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphCaptionRatio(0.87);
    // $chart->getPlot()->setOuterPadding(new Padding(5, 30, 20, 140));//Padding($top, $right, $bottom, $left)
    $chart->getPlot()->setGraphPadding(new Padding(10, 30, 20, 30));
    //$chart->setTitle("TOTAL REVENUE SUMMARY \n         CREDIT AND CASH \n                 ( x  10^5 )");
    $chart->setTitle("                       REVENUE STATISTICS \n FROM " . $fromDate . " TO " . $todate . " \n               CANCELLED CREDIT AND CASH \n                                 ( x  10^3 )");
    $chart->getPlot()->setTitleHeight(150);

    $chart->render("generated/revenue.png");
}
//echo $data;
$data= '<img src="branchBanner/branchBanner1.png" width="100%" >';
$data.= '<img alt="Line chart" src="generated/revenue.png"  style="border: 1px solid gray;"/>';


include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();
?>

