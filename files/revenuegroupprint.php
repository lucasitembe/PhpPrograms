<?php

    include("./includes/connection.php");
 
    
    $data='';
    $title_for_report='';
    
    $query_string='';
 if(isset($_GET['type']) && $_GET['type']=='showSponsorData'){ 
     $fromDate=  filter_input(INPUT_GET, 'fromDate');
     $todate=  filter_input(INPUT_GET, 'todate');
     $sponsorID= filter_input(INPUT_GET, 'sponsorID');
     $sponsorName= filter_input(INPUT_GET, 'sponsorname');
      
     if($sponsorName=='All Sponsors'){
         $sponsorID='';
     }
     
      //$query_string='type=showSponsorData&fromDate='.$fromDate.'&todate='.$todate.'&sponsorID='.$sponsorID.'&sponsorname='.$sponsorname;
   
     
//     echo $sponsorID.' '.$sponsorname.'<br/>';
//     exit;
//     
     
     $filter='';
     
     $duration='<b>All Time Revenue</b>';
     //$servedby='<b>Services By All employees</b>';
    
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
     
    if($sponsorID !==''){    
          $data.='
                      <table align="center" width="100%">
                         <tr>
                         <th colspan="2" align="center">REVENUE GROUPED REPORT</th>
                         </tr>
                         <tr>
                         <th colspan="2" align="center">'.$duration.'</th>
                         </tr>
                         <tr>
                         <th colspan="2" align="center">'.strtoupper($sponsorName).' SERVICES</th>
                         </tr>
                       </table>
                           
                       ';
          
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
    
     
     $total=0;
    
     $totalQty=0;
     $totalSubQty=0;
     $totalSubAmount=0;
     
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the Subcategory
          $i=1;
         
     $sqlIterms="SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";
     $itemsRs=  mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
     $data.="<br/><table align='center' width='100%'>
         <thead>
           <tr><td colspan='4'><b>".$row['Item_Category_Name']."</b></td></tr>
           <tr><td colspan='4'><hr style='width:100%'/></td></tr>    
           <tr class='headerrow'>
             <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
           </tr> 
           </thead>
           ";
     //Retreive Iterms and filter
     while ($rowItems = mysqli_fetch_array($itemsRs)) {
          $sqlSubcategory="SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowItems['Item_Subcategory_ID']."' AND Item_category_ID='".$row['Item_Category_ID']."'";
          $subcategoryRs=  mysqli_query($conn,$sqlSubcategory) or die(mysqli_error($conn));
          
          if(mysqli_num_rows($subcategoryRs)>0){
              $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
                WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' $filter

               " 
              ;

              $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
              while ($rowTpl = mysqli_fetch_array($queryResult)) {
                  
                  
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit'){
                  if($rowTpl['Process_Status']=='served'){
                      $totalSubQty+=$rowTpl['Quantity'];
                      $totalSubAmount+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
                  }
                  
              }else {
                  $totalSubQty+=$rowTpl['Quantity'];
                  $totalSubAmount+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];; 
              } 
              }
          }
          $totalQty+=$totalSubQty;
          $total+=$totalSubAmount;
          
         
            if($totalSubQty>0){ 
            $data.='<tr><td colspan="4"><hr style="width:100%"/></td></tr><tr><td>'.$i.'</td><td>'.$rowItems['Product_Name'].'</td><td>'.number_format($totalSubQty).'</td><td>'.number_format($totalSubAmount).'</td></tr></tr>';
            $i++;
            
            }     
            $totalSubAmount=0;
                   $totalSubQty=0;
              
               }
          $data.="<tr><td colspan='4'><hr style='width:100%'/></td></tr><tr><td colspan='2' style='text-align:right'><b>Total</b></td><td><b><b>".$totalQty."</b></td><td><b>".$total."</b></td></tr>";

          $data.='</table><p>&nbsp;</p>';

         $totalQty=0;
          $total=0;
     }
    }else if($sponsorName=='All Sponsors'){
       
         $data.='
                      <table align="center" width="100%">
                         <tr>
                         <th colspan="2" align="center">REVENUE GROUPED REPORT</th>
                         </tr>
                         <tr>
                         <th colspan="2" align="center">'.$duration.'</th>
                         </tr>
                         <tr>
                         <th colspan="2" align="center">ALL SPONSORS SERVICES</th>
                         </tr>
                       </table>
                           
                       ';
        
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    
    while ($rowSponsors = mysqli_fetch_array($query)) {
        // $data.= '<option value="'.$rowSponsors['Sponsor_ID'].'$$>'.$rowSponsors['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
       
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic
                  ";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
    
     
     $total=0;
    
     $totalQty=0;
     $totalSubQty=0;
     $totalSubAmount=0;
     
     $data.="<br/><table width = '100%'>
           <tr>
             <th colspan='4'>".strtoupper($rowSponsors['Guarantor_Name'])." SERVICES</th>
           </tr> 
            </table>
           ";
     
     while ($row = mysqli_fetch_array($categoryRs)) {
//         //retreive the Subcategory
          $i=1;
         
     $sqlIterms="SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";
     $itemsRs=  mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
    
      $data.="<br/><table align='center' width='100%'>
         <thead>
           <tr><td colspan='4'><b>".$row['Item_Category_Name']."</b></td></tr>
           <tr><td colspan='4'><hr style='width:100%'/></td></tr>    
           <tr class='headerrow'>
             <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
           </tr> 
           </thead>
           ";
     //Retreive Iterms and filter
     while ($rowItems = mysqli_fetch_array($itemsRs)) {
          $sqlSubcategory="SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowItems['Item_Subcategory_ID']."' AND Item_category_ID='".$row['Item_Category_ID']."'";
          $subcategoryRs=  mysqli_query($conn,$sqlSubcategory) or die(mysqli_error($conn));
          
          if(mysqli_num_rows($subcategoryRs)>0){
              $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
            
                WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' $filter

               " 
              ;

              $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
              while ($rowTpl = mysqli_fetch_array($queryResult)) {
                  
                  
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit'){
                  if($rowTpl['Process_Status']=='served'){
                      $totalSubQty+=$rowTpl['Quantity'];
                      $totalSubAmount+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
                  }
                  
              }else {
                  $totalSubQty+=$rowTpl['Quantity'];
                  $totalSubAmount+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity'];; 
              } 
              }
          }
          $totalQty+=$totalSubQty;
          $total+=$totalSubAmount;
          
         
            if($totalSubQty>0){ 
            $data.='<tr><td colspan="4"><hr style="width:100%"/></td></tr><tr><td>'.$i.'</td><td>'.$rowItems['Product_Name'].'</td><td>'.number_format($totalSubQty).'</td><td>'.number_format($totalSubAmount).'</td></tr></tr><tr><td colspan="4"><hr style="width:100%"/></td></tr>';
            $i++;
           
            }     
            $totalSubAmount=0;
                   $totalSubQty=0;
              
               }
          $data.="<tr><td colspan='2' style='text-align:right'><b>Total</b></td><td><b><b>".number_format($totalQty)."</b></td><td><b>".number_format($total)."</b></td></tr>";

          $data.='</table><p>&nbsp;</p>';
          // echo $data;exit;
         $totalQty=0;
          $total=0;
     }
    }
    }
 }

 //echo $data;exit;
 
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
 