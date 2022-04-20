<?php

    include("./includes/connection.php");
 
    
    $data='';
    $title_for_report='';
    
    $query_string='';
 if(isset($_GET['type']) && $_GET['type']=='showPatientPayment'){ 
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID= filter_input(INPUT_GET, 'sponsorID');
     $sponsorname= filter_input(INPUT_GET, 'sponsorname');
      
     if($sponsorname=='All Sponsors'){
         $sponsorID='';
     }
     
      $filter='';
     
     $duration='<b>All Time Revenue</b>';
     
     if(isset($fromDate) && !empty($fromDate)){
        $filter="AND DATE(tpp.Payment_Date_And_Time) >='".$fromDate."'";
        $date=new DateTime($fromDate);
        $duration='From&nbsp;&nbsp; <b>'.date_format($date,'Y-M-d').'</b>';
     }
        if(isset($todate) && !empty($todate)){
        $filter="AND DATE(tpp.Payment_Date_And_Time) <='".$todate."'";
        // $duration='To &nbsp;&nbsp;<b>'.$todate.'</b>';
         $date=new DateTime($todate);
         $duration='To &nbsp;&nbsp; <b>'.date_format($date,'Y-M-d').'</b>';
     
        }
        if(isset($todate) && !empty($todate) && isset($fromDate) && !empty($fromDate)){
        $filter=" AND DATE(tpp.Payment_Date_And_Time) BETWEEN '". $fromDate."' AND '".$todate."'";
          $date=new DateTime($fromDate);
          $date2=new DateTime($todate);
           $duration='To &nbsp;&nbsp; <b>'.date_format($date,'Y-M-d').'</b>';
           $duration='From&nbsp;&nbsp; <b>'.date_format($date,'Y-M-d').'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.date_format($date2,'Y-M-d').'</b>';
        }
        
        //Retrieve users all users
          $sqlPatient="SELECT Registration_ID,Patient_Name  FROM tbl_patient_registration AS tic";
     $pateintRs=  mysqli_query($conn,$sqlPatient) or die(mysqli_error($conn));
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     
     $noOfColumn=  mysqli_num_rows($categoryRs)+6;
     
    if($sponsorID !==''){    
          $data.='<table align="center" width="100%">
                         <tr>
                         <th colspan="'.$noOfColumn.'" style="text-align:center">REVENUE GROUPED REPORT</th>
                         </tr>
                         <tr>
                         <th colspan="'.$noOfColumn.'" style="text-align:center">'.$duration.'</td>
                         </tr>
                       </table>
                       <p style="border-bottom:1px solid #ccc;width:100%;"></p>
                       <p width = "100%"><b>'.strtoupper($sponsorname).' SERVICES</b></p>
                       ';
          
         
     $data.="<table align='center' width='100%'>
            <thead>
            <tr><td colspan='$noOfColumn+'><hr style='width:100%'/></td></tr> 
            <tr class='headerrow'>
             <th>BILLING DATE</th><th>PATIENT NAME</th><th>DOCTOR NAME</th><th>FOLLO #</th><th>CLAIM FORM #</th>
           ";
     $k=0;
     while ($rowCatgr = mysqli_fetch_array($categoryRs)) {
         $data.="<th title='".$rowCatgr['Item_Category_Name']."'>".substr($rowCatgr['Item_Category_Name'], 0, 6)."</th>";
         $catgName[$rowCatgr['Item_Category_ID']]=$rowCatgr['Item_Category_Name'];
         $catgSubprice[$rowCatgr['Item_Category_ID']]=0;
         $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         $k++;
     }
     

     
    $data.="<th>Sub Total</th></tr></thead>"; 
    $total=0;
    $subtotal=0;
    $GrandTotal=0;
    
    
   //Retrieve the calim form number
           $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='$sponsorID' $filter";
            $check_claimForNumber=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
            
            $Claim_Form_Number='';
            $folionumber='';
            $receiptDate='';
            $employeName='';
    
     //display users
     
     while ($rowPatient = mysqli_fetch_array($pateintRs)) {
         //check if is in the payments\
         
         
            
          if(mysqli_num_rows($check_claimForNumber)>0){  
            while ($rowclaim = mysqli_fetch_array($check_claimForNumber)) {
                $Claim_Form_Number=$rowclaim['Claim_Form_Number'];
                $folionumber=$rowclaim['Folio_Number'];
                $receiptDate=$rowclaim['daterecept'];
                $employeName=$rowclaim['Employee_Name'];
            }
            
          }
         
         
            $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
               
            WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='$sponsorID' $filter
             
           " 
          ;
         
          $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          
        if(mysqli_num_rows($queryResult)>0){  
          while ($rowTpl = mysqli_fetch_array($queryResult)) {
              //CHECK WHICH GROUP HE BELONGS
               //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID,Item_category_ID FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowTpl['Item_Subcategory_ID']."'");
          $getCategoryID=  mysqli_fetch_assoc($querySubcategory);
          
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit' && $rowTpl['Process_Status']=='served'){
                  //$totalsubCredit+=$rowTpl['Price']*$rowTpl['Quantity'];
                  $catgSubprice[$getCategoryID['Item_category_ID']]+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $catgPrices[$getCategoryID['Item_category_ID']]+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                   $subtotal+=$rowTpl['Price']*$rowTpl['Quantity'];
              }else if($rowTpl['Billing_Type']=='Inpatient Cash' || $rowTpl['Billing_Type']=='Outpatient Cash'){
                 // $totalsubCash+=$rowTpl['Price']*$rowTpl['Quantity'];
                  $catgSubprice[$getCategoryID['Item_category_ID']]+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                  $catgPrices[$getCategoryID['Item_category_ID']]+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];
                   $subtotal+=$rowTpl['Price']*$rowTpl['Quantity'];
                          
              } 
              
              //$subtotal+=$catgSubprice[$getCategoryID['Item_category_ID']];
             // $catgPrices[$getCategoryID['Item_category_ID']]+=$catgSubprice[$getCategoryID['Item_category_ID']];
         
         }
         
//          echo '<pre>';
//          print_r($catgPrices);
//          echo '</pre>';
//          exit;
         
        
         $total+=$subtotal;
         $data.="<tr><td colspan='$noOfColumn'><hr style='width:100%'/></td></tr>
            <tr>
             <td>".$receiptDate."</td><td>".$rowPatient['Patient_Name']."</td><td>".$employeName."</td><td>".$folionumber."</td>'<td>".$Claim_Form_Number."</td>";
            $rcat=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
          while ($rowct2 = mysqli_fetch_array($rcat)) {
             $data.="<td>".number_format($catgSubprice[$rowct2['Item_Category_ID']])."</td>";
             $catgSubprice[$rowct2['Item_Category_ID']]=0;
         }
         
       //         echo '<pre>';
//         print_r($catgSubprice);
//          echo '</pre>';
//          exit;
         
         $data.="<td>".number_format($subtotal)."</td></tr>";
        }
      
         $subtotal=0;
      
     }
     $data.="<tr><td colspan='$noOfColumn'><hr style='width:100%'/></td></tr><tr><td style='text-align:center' colspan='5'><b>Total</b></td>";
          $rcat2=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
         while ($rowCatgr = mysqli_fetch_array($rcat2)) {
             $data.="<td>".$catgPrices[$rowCatgr['Item_Category_ID']]."</td>";
             $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         }
     
     $data.='<td>'.$total.'</td></tr></table>';
     
       $GrandTotal=$total;
    
    }else if($sponsorname=='All Sponsors'){
       
         $data.='
                      <table align="center" width="100%">
                         <tr>
                         <th colspan="'.$noOfColumn.'" style="text-align:center">REVENUE GROUPED REPORT</th>
                         </tr>
                         <tr>
                         <th colspan="'.$noOfColumn.'" style="text-align:center">'.$duration.'</td>
                         </tr>
                         <tr>
                         <th colspan="'.$noOfColumn.'" align="center">ALL SPONSORS SERVICES</th>
                         </tr>
                       </table>
                         <p style="border-bottom:1px solid #ccc;width:100%"></p>
                       ';
        
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $GrandTotal=0;
    while ($rowSponsors = mysqli_fetch_array($query)) {
         
           //Retrieve users all users
          $sqlPatient="SELECT Registration_ID,Patient_Name  FROM tbl_patient_registration AS tic";
     $pateintRs=  mysqli_query($conn,$sqlPatient) or die(mysqli_error($conn));
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     
     $noOfColumn=  mysqli_num_rows($categoryRs);
     $number_of_column=$noOfColumn+6;
     
     $data.="<br/><table width = '100%'>
           <tr>
             <th colspan='".$number_of_column."' style='text-align:left'>".strtoupper($rowSponsors['Guarantor_Name'])." SERVICES</th>
           </tr> 
            </table>
           ";
     
     $data.="<table align='center' width='100%'>
            <thead>
            <tr><td colspan='$number_of_column'><hr style='width:100%'/></td></tr>
            <tr class='headerrow'>
             <th>BILLING DATE</th><th>PATIENT NAME</th><th>DOCTOR NAME</th><th>FOLLO #</th><th>CLAIM FORM #</th>
           ";
     $k=0;
     while ($rowCatgr = mysqli_fetch_array($categoryRs)) {
         $data.="<th title='".$rowCatgr['Item_Category_Name']."'>".substr($rowCatgr['Item_Category_Name'], 0, 6)."</th>";
         $catgName[$rowCatgr['Item_Category_ID']]=$rowCatgr['Item_Category_Name'];
         $catgSubprice[$rowCatgr['Item_Category_ID']]=0;
         $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         $k++;
     }
     

     
    $data.="<th>Sub Total</th></tr></thead>"; 
    $total=0;
    $subtotal=0;
  
    
   //Retrieve the calim form number
            $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' $filter";
            $check_claimForNumber=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
            
            $Claim_Form_Number='';
            $folionumber='';
            $receiptDate='';
            $employeName='';
    
     //display users
     
     while ($rowPatient = mysqli_fetch_array($pateintRs)) {
         //check if is in the payments\
         
         
            
          if(mysqli_num_rows($check_claimForNumber)>0){  
            while ($rowclaim = mysqli_fetch_array($check_claimForNumber)) {
                $Claim_Form_Number=$rowclaim['Claim_Form_Number'];
                $folionumber=$rowclaim['Folio_Number'];
                $receiptDate=$rowclaim['daterecept'];
                $employeName=$rowclaim['Employee_Name'];
            }
            
          }
         
         
           $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
               
            WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' $filter
             
           " 
          ;
         
          $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
          
        if(mysqli_num_rows($queryResult)>0){  
          while ($rowTpl = mysqli_fetch_array($queryResult)) {
              //CHECK WHICH GROUP HE BELONGS
               //retreive the category
         $querySubcategory=  mysqli_query($conn,"SELECT Item_Subcategory_ID,Item_category_ID FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowTpl['Item_Subcategory_ID']."'");
          $getCategoryID=  mysqli_fetch_assoc($querySubcategory);
          
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit' && $rowTpl['Process_Status']=='served'){
                  //$totalsubCredit+=$rowTpl['Price']*$rowTpl['Quantity'];
                  $catgSubprice[$getCategoryID['Item_category_ID']]+=$rowTpl['Price']*$rowTpl['Quantity'];
                  $catgPrices[$getCategoryID['Item_category_ID']]+=$rowTpl['Price']*$rowTpl['Quantity'];
                   $subtotal+=$rowTpl['Price']*$rowTpl['Quantity'];
              }else if($rowTpl['Billing_Type']=='Inpatient Cash' || $rowTpl['Billing_Type']=='Outpatient Cash'){
                 // $totalsubCash+=$rowTpl['Price']*$rowTpl['Quantity'];
                  $catgSubprice[$getCategoryID['Item_category_ID']]+=$rowTpl['Price']*$rowTpl['Quantity'];;
                  $catgPrices[$getCategoryID['Item_category_ID']]+=$rowTpl['Price']*$rowTpl['Quantity'];
                   $subtotal+=$rowTpl['Price']*$rowTpl['Quantity'];
                          
              } 
              
              //$subtotal+=$catgSubprice[$getCategoryID['Item_category_ID']];
             // $catgPrices[$getCategoryID['Item_category_ID']]+=$catgSubprice[$getCategoryID['Item_category_ID']];
         
         }
         
//          echo '<pre>';
//          print_r($catgPrices);
//          echo '</pre>';
//          exit;
         
        
         $total+=$subtotal;
         $data.="<tr><td colspan='$number_of_column'><hr style='width:100%'/></td></tr>
            <tr>
             <td>".$receiptDate."</td><td>".$rowPatient['Patient_Name']."</td><td>".$employeName."</td><td>".$folionumber."</td>'<td>".$Claim_Form_Number."</td>'";
            $rcat=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
          while ($rowct2 = mysqli_fetch_array($rcat)) {
             $data.="<td>".number_format($catgSubprice[$rowct2['Item_Category_ID']])."</td>";
             $catgSubprice[$rowct2['Item_Category_ID']]=0;
         }
         
       //         echo '<pre>';
//         print_r($catgSubprice);
//          echo '</pre>';
//          exit;
         
         $data.="<td>".number_format($subtotal)."</td></tr>";
        }
      
         $subtotal=0;
      
     }
     $data.="<tr><td colspan='$number_of_column'><hr style='width:100%'/></td></tr><tr><td style='text-align:center' colspan='5'><b>Total</b></td>";
          $rcat2=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
         while ($rowCatgr = mysqli_fetch_array($rcat2)) {
             $data.="<td>".number_format($catgPrices[$rowCatgr['Item_Category_ID']])."</td>";
             $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         }
     $GrandTotal=$GrandTotal+$total;
     $data.='<td>'.number_format($total).'</td></tr></table>';
    }
    }
    
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     $dataKey='';
     $noOfColumn=  mysqli_num_rows($categoryRs);
     $dataKey.="<p style='text-align:right;width:100%'><b>Grand Total: </b>".number_format($GrandTotal)."</p>
               <p style='border-bottom:1px solid #ccc;width:100%'></p><br/>
                <p style='margin-bottom:0px'><b>Keys:</b></p>
                <table width = '80%'>";
     $k=0;
     while ($rowCatgr = mysqli_fetch_array($categoryRs)) {
         $dataKey.="<tr><td><b>".substr($rowCatgr['Item_Category_Name'], 0, 6)."</b></td><td>".$rowCatgr['Item_Category_Name']."</td></tr>"; 
         $catgName[$rowCatgr['Item_Category_ID']]=$rowCatgr['Item_Category_Name'];
         $catgSubprice[$rowCatgr['Item_Category_ID']]=0;
         $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         $k++;
     }
     
      $dataKey.="</table>";
 }

 //echo $data;exit;
 
 if($data !==''){
 include("./MPDF/mpdf.php");
 
  $mpdf=new mPDF('c','A4',40); 

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($data.$dataKey,2);
   $mpdf->setFooter('{PAGENO} of {nbpg} pages||{PAGENO} of {nbpg} pages') ;
    $mpdf->Output('mpdf.pdf','I');
    exit;
 }  else {
     echo '<h1>No Data To Print</h1><p><i>Try again</i></p>';     
}
 