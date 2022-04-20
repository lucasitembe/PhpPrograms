<?php
    include("./includes/connection.php");
    
    $data='';
    $title_for_report=''; 
    
    $query_string='';
    
     $totolPatients=0;
  
   // echo $_GET['type'];exit;
 
 if(isset($_GET['type']) && $_GET['type']=='cash'){
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
     $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
     $employeeID=  filter_input(INPUT_GET, 'employeeID');
     $employeeName=  filter_input(INPUT_GET, 'employeeName');
     $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
     
     
     $filter='';
     $duration='<b>All Time Revenue</b>';
     $servedby='<b>Services By All employees</b>';
    
     if(isset($fromDate) && !empty($fromDate)){
        $filter="AND tpp.Payment_Date_And_Time >='".$fromDate."'";
        $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>';
      }
        if(isset($todate) && !empty($todate)){
        $filter="AND tpp.Payment_Date_And_Time <='".$todate."'";
         $duration='To &nbsp;&nbsp;<b>'.$todate.'</b>';
     
        }
        if(isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)){
        $filter=" AND tpp.Payment_Date_And_Time BETWEEN '". $fromDate."' AND '".$todate."'";
           $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.$todate.'</b>';
        }
        
        if(isset($sponsorname) && !empty($sponsorname) && trim($sponsorname)!=="All Sponsors"){
            $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
        }
    
         if(isset($employeeName) && !empty($employeeName) &&  trim($employeeName) !=="All Data Entry"){
            $filter.=" AND tpp.Employee_ID='$employeeID'";
            $servedby='Served By&nbsp;&nbsp;<b>'.$employeeName.'</b>';
        }
        if(isset($patientStatus) && !empty($patientStatus)){
            if($patientStatus==='INPATIENT'){
                $filter.=" AND tpp.Billing_Type='Inpatient Cash'";
            }else if($patientStatus==='OUTPATIENT'){
                $filter.=" AND tpp.Billing_Type='Outpatient Cash'";
            }else if($patientStatus ==="OUTPATIENT AND INPATIENT"){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Cash','Inpatient Cash')";
            }
            
        }
        
        $data.='<table align="center" width="100%">
                         <tr>
                         <th style="text-align:center">TOTAL REVENUE SUMMARY REPORT</th>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Cash</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$duration.'</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$servedby.'</td>
                         </tr>
                         
                       </table>
                       <hr style="width:100%"/>
                       ';
     
     
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
     
     
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
     $i=1;

     $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
                <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CASH</th><th style='text-align:right'>TOTAL</th>
            </tr> 
           </thead>
           ";
        $total=0;
     $totalCash=0;
     $totalsubCash=0;
     $totolPatients=0;
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='".$row['Item_Category_ID']."'");
         $NumberOFPatients=0;
         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
          $subCatID=$rowSubcat['Item_Subcategory_ID'];
         //echo $subCatID;
         //exit;
          $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           " 
          ;
           $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

           $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          while ($rowCash = mysqli_fetch_array($queryResult)) {
                $totalsubCash+=($rowCash['Price']- $rowCash['Discount'])*$rowCash['Quantity'];
                $TotalItem += $rowCash['Quantity'];
          }
          
         }
         $totolPatients +=$NumberOFPatients;
           $totalCash+=$totalsubCash;
          $total+=$totalsubCash;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
                $data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td></tr>';
          $totalsubCash=0;
          $SuperTotalItems += $TotalItem;//$TotalItem
         $TotalItem=0;
         $i++;
          }
      $data.="<tr><td colspan='2' style='text-align:center'><b>Total</b></td><td style='text-align:right'><b>".number_format($SuperTotalItems)."</b></td><td style='text-align:right'><b>".number_format($totolPatients)."</b></td><td style='text-align:right'><b>".number_format($totalCash)."</b></td><td style='text-align:right'><b>".number_format($total)."</b></td></tr>";
    
     $data.='</table>';
    // $data=$sql;
     
     //$data=mysql
 }
 
 //For credit report
 
 if(isset($_GET['type']) && $_GET['type']=='credit'){
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
     $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
     $employeeID=  filter_input(INPUT_GET, 'employeeID');
     $employeeName=  filter_input(INPUT_GET, 'employeeName');
     $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
      
     $filter='';
     
     $duration='<b>All Time Revenue</b>';
     $servedby='<b>Services By All employees</b>';
    
     if(isset($fromDate) && !empty($fromDate)){
        $filter="AND tpp.Payment_Date_And_Time >='".$fromDate."'";
        $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>';
      }
        if(isset($todate) && !empty($todate)){
        $filter="AND tpp.Payment_Date_And_Time <='".$todate."'";
         $duration='To &nbsp;&nbsp;<b>'.$todate.'</b>';
     
        }
        if(isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)){
        $filter=" AND tpp.Payment_Date_And_Time BETWEEN '". $fromDate."' AND '".$todate."'";
           $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.$todate.'</b>';
        }
        
        if(isset($sponsorname) && !empty($sponsorname) && trim($sponsorname)!=="All Sponsors"){
            $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
        }
    
         if(isset($employeeName) && !empty($employeeName) &&  trim($employeeName) !=="All Data Entry"){
            $filter.=" AND tpp.Employee_ID='$employeeID'";
            $servedby='Served By&nbsp;&nbsp;<b>'.$employeeName.'</b>';
        }
        if(isset($patientStatus) && !empty($patientStatus)){
            if($patientStatus==='INPATIENT'){
                $filter.=" AND tpp.Billing_Type='Inpatient Credit'";
            }else if($patientStatus==='OUTPATIENT'){
                $filter.=" AND tpp.Billing_Type='Outpatient Credit'";
            }else if($patientStatus ==="OUTPATIENT AND INPATIENT"){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit')";
            }
            
        }
        
        $data.='<table align="center" width="100%">
                         <tr>
                         <th style="text-align:center">TOTAL REVENUE SUMMARY REPORT</th>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Credit</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$duration.'</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$servedby.'</td>
                         </tr>
                         
                       </table>
                       <hr style="width:100%"/>
                       ';
     
     
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
     
     
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
     $i=1;

     $data.="<table align='center' width='100%'>
         <thead>
           <tr class='headerrow'>
              <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PATIENTS</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
          </tr> 
           </thead>
           ";
        $total=0;
     $totalCash=0;
     $totalsubCash=0;
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='".$row['Item_Category_ID']."'");
         $NumberOFPatients=0;
         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
          $subCatID=$rowSubcat['Item_Subcategory_ID'];
         //echo $subCatID;
         //exit;
          $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time
            FROM tbl_patient_payments as tpp
            RIGHT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
            WHERE tpp.Transaction_status !='cancelled' AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           " 
          ;
          $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];

           $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          while ($rowCredit = mysqli_fetch_array($queryResult)) {
                $totalsubCredit+=($rowCredit['Price']- $rowCredit['Discount'])*$rowCredit['Quantity'];
                $TotalItem += $rowCredit['Quantity'];
          }
          
         }
         $totolPatients +=$NumberOFPatients;
           $totalCredit+=$totalsubCredit;
          $total+=$totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
         $data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td></tr>';
         $totalsubCredit=0;
          $SuperTotalItems += $TotalItem;//$TotalItem
         $TotalItem=0;
         $i++;
          }
     $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

     $data.='</table>';
    // $data=$sql;
     
     //$data=mysql
 }
 
  //For credit Cash report
 
 if(isset($_GET['type']) && $_GET['type']=='credit_cash'){
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
     $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
     $employeeID=  filter_input(INPUT_GET, 'employeeID');
     $employeeName=  filter_input(INPUT_GET, 'employeeName');
     $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
     
     $filter='';
     
     $duration='<b>All Time Revenue</b>';
     $servedby='<b>Services By All employees</b>';
    
     if(isset($fromDate) && !empty($fromDate)){
        $filter="AND tpp.Payment_Date_And_Time >='".$fromDate."'";
        $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>';
      }
        if(isset($todate) && !empty($todate)){
        $filter="AND tpp.Payment_Date_And_Time <='".$todate."'";
         $duration='To &nbsp;&nbsp;<b>'.$todate.'</b>';
     
        }
        if(isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)){
        $filter=" AND tpp.Payment_Date_And_Time BETWEEN '". $fromDate."' AND '".$todate."'";
           $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.$todate.'</b>';
        }
        
        if(isset($sponsorname) && !empty($sponsorname) && trim($sponsorname)!=="All Sponsors"){
            $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
        }
    
         if(isset($employeeName) && !empty($employeeName) &&  trim($employeeName) !=="All Data Entry"){
            $filter.=" AND tpp.Employee_ID='$employeeID'";
            $servedby='Served By&nbsp;&nbsp;<b>'.$employeeName.'</b>';
        }
        if(isset($patientStatus) && !empty($patientStatus)){
            if($patientStatus==='INPATIENT'){
                $filter.=" AND tpp.Billing_Type  IN ('Inpatient Credit','Inpatient Cash')";
            }else if($patientStatus==='OUTPATIENT'){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Outpatient Cash')";
            }else if($patientStatus ==="OUTPATIENT AND INPATIENT"){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit','Outpatient Cash','Inpatient Cash')";
            }
            
        }
        
       $data.='<table align="center" width="100%" border="0">
                         <tr>
                         <th colspan="2" style="text-align:center"><b>TOTAL REVENUE SUMMARY REPORT</b></th>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Credit && Cash</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$duration.'</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$servedby.'</td>
                         </tr>
                       </table>
                        <hr style="width:100%"/>
                       ';
     
     
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                   
                  ";
     
     
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
     $i=1;
     $data.="<table align='center' width='100%'>
           <thead>
           <tr class='headerrow'>
               <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PAT</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
          </tr> 
           </thead>
           ";
     $total=0;
     $totalCash=0;
     $totalCredit=0;
     $totalsubCredit=0;
     $totalsubCash=0;
     
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='".$row['Item_Category_ID']."'");
          $NumberOFPatients=0;
         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
          $subCatID=$rowSubcat['Item_Subcategory_ID'];
         //echo $subCatID;
         //exit;
         $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
               WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter
             
           " 
          ;
         
          $getpatients=mysqli_query($conn,"SELECT COUNT(tpp.Registration_ID) AS NumberOfPatients FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                    WHERE tpp.Transaction_status !='cancelled'  AND itm.Item_Subcategory_ID='$subCatID' $filter ") or die(mysqli_error($conn));
            
            $NumberOFPatients +=  mysqli_fetch_assoc($getpatients)['NumberOfPatients'];
         
          $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          while ($rowTpl = mysqli_fetch_array($queryResult)) {
              if(($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit')){
                  $totalsubCredit+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $TotalItem +=$rowTpl['Quantity'];
              }else if($rowTpl['Billing_Type']=='Inpatient Cash' || $rowTpl['Billing_Type']=='Outpatient Cash'){
                  $totalsubCash+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $TotalItem +=$rowTpl['Quantity'];
              } 
              
          }
         }
           $totolPatients +=$NumberOFPatients;
          $totalCash+=$totalsubCash;
          $totalCredit+=$totalsubCredit;
          $total+=$totalsubCash+$totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
         $data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
         $totalsubCredit=0;
         $totalsubCash=0;
         $totalsubCredit=0;
         $SuperTotalItems +=$TotalItem;
         $TotalItem=0;
         $i++;
          }
     $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCash) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";
 
     $data.='</table>';
    // $data=$sql;
     
     //$data=mysql
 }
 
  //For cancelled report
 
 if(isset($_GET['type'])&& $_GET['type']=='canceled'){
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID=  filter_input(INPUT_GET, 'sponsorID');
     $sponsorname=  filter_input(INPUT_GET, 'sponsorname');
     $employeeID=  filter_input(INPUT_GET, 'employeeID');
     $employeeName=  filter_input(INPUT_GET, 'employeeName');
     $patientStatus=  filter_input(INPUT_GET, 'patientStatus');
     
      
     $filter='';
     
     $duration='<b>All Time Revenue</b>';
     $servedby='<b>Services By All employees</b>';
    
     if(isset($fromDate) && !empty($fromDate)){
        $filter="AND tpp.Payment_Date_And_Time >='".$fromDate."'";
        $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>';
      }
        if(isset($todate) && !empty($todate)){
        $filter="AND tpp.Payment_Date_And_Time <='".$todate."'";
         $duration='To &nbsp;&nbsp;<b>'.$todate.'</b>';
     
        }
        if(isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)){
        $filter=" AND tpp.Payment_Date_And_Time BETWEEN '". $fromDate."' AND '".$todate."'";
           $duration='From&nbsp;&nbsp; <b>'.$fromDate.'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.$todate.'</b>';
        }
        
        if(isset($sponsorname) && !empty($sponsorname) && trim($sponsorname)!=="All Sponsors"){
            $filter.=" AND tpp.Sponsor_ID='$sponsorID'";
        }
    
         if(isset($employeeName) && !empty($employeeName) &&  trim($employeeName) !=="All Data Entry"){
            $filter.=" AND tpp.Employee_ID='$employeeID'";
            $servedby='Served By&nbsp;&nbsp;<b>'.$employeeName.'</b>';
        }
        if(isset($patientStatus) && !empty($patientStatus)){
            if($patientStatus==='INPATIENT'){
                $filter.=" AND tpp.Billing_Type  IN ('Inpatient Credit','Inpatient Cash')";
            }else if($patientStatus==='OUTPATIENT'){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Outpatient Cash')";
            }else if($patientStatus ==="OUTPATIENT AND INPATIENT"){
                $filter.=" AND tpp.Billing_Type  IN ('Outpatient Credit','Inpatient Credit','Outpatient Cash','Inpatient Cash')";
            }
            
        }
        
        $data.='<table align="center" width="100%" border="0">
                         <tr>
                         <th colspan="2" style="text-align:center"><b>TOTAL REVENUE SUMMARY REPORT</b></th>
                         </tr>
                         <tr>
                         <td style="text-align:center">Type&nbsp;&nbsp;<b>Cancelled</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$duration.'</td>
                         </tr>
                         <tr>
                         <td style="text-align:center">'.$servedby.'</td>
                         </tr>
                       </table>
                       <hr width="100%"/>
                       ';
     
     
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
     
     
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_fetch_assoc($result));
     $i=1;
     $data.="<table align='center' width='100%'>
          <thead>
           <tr class='headerrow'>
               <th>S/N</th><th>REVENUE CENTER</th><th style='text-align:right'>QTY</th><th style='text-align:right'>NO. PAT</th><th style='text-align:right'>CASH</th><th style='text-align:right'>CREDIT</th><th style='text-align:right'>TOTAL</th>
            </tr>    
           </thead>
           ";
     $total=0;
     $totalsubCash=0;
     $totalsubCredit=0;
     $totalCash=0;
     $totalCredit=0;
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='".$row['Item_Category_ID']."'");
          $NumberOFPatients=0;
         while ($rowSubcat = mysqli_fetch_array($querySubcategory)) {
          $subCatID=$rowSubcat['Item_Subcategory_ID'];
         //echo $subCatID;
         //exit;
         $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
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

          $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          while ($rowTpl = mysqli_fetch_array($queryResult)) {
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit'){
                  $totalsubCredit+=($rowTpl['Price']*$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $TotalItem +=$rowTpl['Quantity'];
              }else if($rowTpl['Billing_Type']=='Inpatient Cash' || $rowTpl['Billing_Type']=='Outpatient Cash'){
                  $totalsubCash+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $TotalItem +=$rowTpl['Quantity'];
              } 
              
          }
         }
          $totolPatients +=$NumberOFPatients;
          $totalCash+=$totalsubCash;
          $totalCredit+=$totalsubCredit;
          $total+=$totalsubCash+$totalsubCredit;
        // echo $i.'. Category_ID='.$row['Item_Category_ID'].' Category_Name='.$row['Item_Category_Name'].' Subcategory_ID='.$row['Item_Subcategory_ID'].'<br/>';
       $data.='<tr><td>' . $i . '</td><td>' . $row['Item_Category_Name'] . '</td><td style="text-align:right">' . number_format($TotalItem) . '</td><td style="text-align:right">' . number_format($NumberOFPatients) . '</td><td style="text-align:right">' . number_format($totalsubCash) . '</td><td style="text-align:right">' . number_format($totalsubCredit) . '</td><td style="text-align:right">' . number_format(($totalsubCash + $totalsubCredit)) . '</td></tr>';
          $totalsubCash=0;$totalsubCredit=0;
        $SuperTotalItems +=$TotalItem;
         $TotalItem=0;
         $i++;
          }
     $data.="<tr><th colspan='2'>Total</th><td style='text-align:right'><b>" . number_format($SuperTotalItems) . "</b></td><td style='text-align:right'><b>" . number_format($totolPatients) . "</b></td><td style='text-align:right'><b>" . number_format($totalCash) . "</b></td><td style='text-align:right'><b>" . number_format($totalCredit) . "</b></td><td style='text-align:right'><b>" . number_format($total) . "</b></td></tr>";

     $data.='</table>';
    // $data=$sql;
     
     //$data=mysql
 }
 //echo $data;
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
 }else{
     echo 'passed IN';
 }
?>

