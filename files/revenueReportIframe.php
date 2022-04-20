<?php
    include("./includes/connection.php");
    $temp = 1;
    $branchID= $_GET['branchID'];
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    
    //echo $Date_From." ".$Date_To." ".$branchID; exit;
    echo '<center><table width =100% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
                <td><b>DATE & TIME</b></td>
                <td style='text-align: left'><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td><b>BILLING TYPE</b></td>
                <td width=10% style='text-align: center;'><b>RECEIPT NO</b></td>
                <td style='text-align: right;' width=10%><b>CASH</b></td>
                <td style='text-align: right;' width=10%><b>CREDIT</b></td>
         </tr>";
    echo "<tr>
                <td colspan=7><hr></td></tr>";
    if(!empty($branchID)){
    //run the query to select all data from the database according to the branch id
    $filterRevenue = mysqli_query($conn,
                "SELECT pp.Patient_Payment_ID,pr.Patient_Name, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items it
                 WHERE pr.Sponsor_ID=sp.Sponsor_ID
                 AND pp.Registration_ID=pr.Registration_ID
                 AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                 AND it.Item_ID=ppl.Item_ID
                 AND pp.branch_id = '$branchID' 
                 AND pp.Receipt_Date BETWEEN '$Date_From' AND '$Date_To' GROUP BY pp.Patient_Payment_ID ORDER BY pp.Payment_Date_And_Time") or die(mysqli_error($conn));
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    
    while($row = mysqli_fetch_array($filterRevenue)){
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Payment_Date_And_Time']."</td>";
        echo "<td>".$row['Patient_Name']."</td>";
        echo "<td>".$row['Billing_Type']."</td>";
        echo "<td style='text-align: center;'><a href='individualsummaryreport.php?Patient_Payment_ID=".$row['Patient_Payment_ID']."&IndividualSummaryReport=IndividualSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Patient_Payment_ID']."</a></td>";
        if(((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'active')){
            echo "<td style='text-align: right;'>".number_format($row['Total'])."</td>";
            echo "<td style='text-align: right;'>0</td>";
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'active')){
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>".number_format($row['Total'])."</td>"; 
            $Credit_Total = $Credit_Total + $row['Total'];
        }
        echo "<tr><td colspan=7><hr></td></tr>";
        $temp++;        
    }
    echo "<tr><td colspan=5 style='text-align: right;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($Cash_Total)."</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($Credit_Total)."</b></td>";
?></table></center>

