<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
    ini_set('max_execution_time',300);
    include("./includes/connection.php");
    $today=date('Y-m-d');
    $data='';
    $title_for_report='';
    
    $query_string='';
    $EntryName= filter_input(INPUT_GET, 'EntryName');
    $consultation= filter_input(INPUT_GET, 'consultation');
    $Status= filter_input(INPUT_GET, 'Status');
    $Transaction= filter_input(INPUT_GET, 'Transaction');
    $fromDate=  filter_input(INPUT_GET, 'fromDate');
    $todate=  filter_input(INPUT_GET, 'todate');
    $sponsorID= filter_input(INPUT_GET, 'sponsorID');
     $sponsorname= filter_input(INPUT_GET, 'sponsorname');
     $employeeID= filter_input(INPUT_GET, 'employeeID');
     $employeeName= filter_input(INPUT_GET, 'DataEntryName');
     $patientStatus= filter_input(INPUT_GET, 'patientStatus');
     $temp= filter_input(INPUT_GET, 'sponsorID');
     $sponsorname='All Sponsors';
     
     
     //Select employee name
     if($EntryName=='All'){
     $Name='All';  
    }else{
    $StaffName=mysqli_query($conn,'SELECT Employee_Name FROM tbl_employee WHERE Employee_ID="'.$EntryName.'"');
    $qry= mysqli_fetch_assoc($StaffName);
     $Name=$qry['Employee_Name'];  
    }
     
     //select sponsor
     if($temp=='All Sponsors'){
         $sponsorQuery='';
     }else{
         $sponsorID= $temp;
         $sponsorname=$temp;
         $sponsorQuery="AND tpp.Sponsor_ID='$sponsorID'";
     }
     //select sponsor name
     
     if($sponsorID=='All'){
         $sponsorName='All';
     }  else {
         $sqlName=mysqli_query($conn,"SELECT Guarantor_Name FROM  tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
         $result=  mysqli_fetch_assoc($sqlName);
         $sponsorName=$result['Guarantor_Name'];  
    }
    
    
     //Select data entry
    if($EntryName=='All'){
      $dataEntry='';  
    } else {
     $dataEntry="AND tpp.Employee_ID='".$EntryName."'";  
    }
     
     //select patient status
 
    if($Status=='All'){
     
     $StatusTYpe='All';
    }  else {
    // $consultationvalue=$Status; 
     $StatusTYpe=$Status;
    }
    
    //Consultation type
    if($consultation=='All'){
        $consultationvalue='';
    }else{
        
        $consultationvalue="AND itm.Consultation_Type='$consultation'";//AND 
    }
    
    //select transaction
    if($Transaction=='All'){  
        $transactionType='All';
     } else {
       $transactionType=$Transaction;
    }

    if($StatusTYpe=='All'){
        if($transactionType=='All'){
          $Billing_Type="AND (tpp.Billing_Type='Outpatient Credit' || tpp.Billing_Type='Outpatient Cash' || tpp.Billing_Type='Inpatient Credit' || tpp.Billing_Type='Inpatient Cash')";  
            
        }else{
          $Billing_Type="AND (tpp.Billing_Type='Outpatient ".$transactionType." || tpp.Billing_Type='Inpatient ".$transactionType."')";
         }
        
    }else{
        
       if($transactionType=='All'){

           $Billing_Type="AND (tpp.Billing_Type='".$StatusTYpe." Credit' || tpp.Billing_Type='".$StatusTYpe." Cash')";
        }else{
            
         //$Billing_Type='AND tpp.Billing_Type="'.$StatusTYpe.' '.$transactionType.'"';   
         $Billing_Type="AND tpp.Billing_Type='".$StatusTYpe."  ".$transactionType."'";   
       }
       
    }

     $query_string='type=showSponsorData&fromDate='.$fromDate.'&todate='.$todate.'&sponsorID='.$sponsorID.'&sponsorname='.$sponsorname;
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
        }if(isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)){
          $filter="AND tpp.Payment_Date_And_Time >='".$today."'";
          $duration='From&nbsp;&nbsp; <b>'.$today.'</b>';
          $fromDate= $today;
          $todate= $today;  
	  $query_string='type=showSponsorData&fromDate='.$today.'&todate=&sponsorID='.$sponsorID.'&sponsorname='.$sponsorname;
        }

        
//        echo $temp;
//        exit();
////        
//      $printString="consultation=$consultation&Status=$Status&Transaction=$transactionType&fromDate=$fromDate&todate=$todate&sponsorID=$temp&EntryName=$EntryName";

     if($temp !=='All Sponsors'){
        $data.='<table align="center" width="80%" border="0" class="hv_table">
                <tr>
                 <td style="text-align:center"><b>REVENUE GROUPED REPORT</b></td>
                </tr>
                <tr>
                <td style="text-align:center"><b>'.$duration.'</b></td>
                </tr>
                <tr>
                <td style="text-align:center"><b>STAFF NAME:'.strtoupper($Name).'</b></td>
                </tr>
                <tr>
                 <td style="text-align:center"><b>PAYMENT MODE:'.strtoupper($Transaction).'</b></td>
                </tr>
                <tr>
                <td style="text-align:center"><b>PATIENT STATUS:'.strtoupper($Status).'</b></td>
                </tr>
                <tr>
                <td style="text-align:center"><b>CONSULTATION TYPE:'.strtoupper($consultation).'</b></td>
                </tr>
              </table>
              <p width = "80%"><b>'.strtoupper($sponsorName).' SERVICES</b></p>
              ';

     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     $total=0;
     $totalQty=0;
     $totalSubQty=0;
     $totalSubAmount=0;
     $TotalIndividual=0;
     $TotalQty=0;
     $grandTotal=0;
     while ($row = mysqli_fetch_array($categoryRs)){
     //retreive the Subcategory
     $i=1;    
//    if($consultationvalue=='All'){
      $sqlIterms="SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";   
//     }else{
//        $sqlIterms="SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items WHERE Consultation_Type='$consultationvalue'"; 
//     }
    $itemsRs=  mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
    $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,itc.Item_category_ID,tpl.Process_Status
     FROM tbl_patient_payments as tpp JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID JOIN tbl_item_subcategory as itsb ON itsb.Item_Subcategory_ID=itm.Item_Subcategory_ID JOIN tbl_item_category as itc ON itc.Item_category_ID=itsb.Item_category_ID WHERE itc.Item_category_ID='".$row['Item_Category_ID']."' AND tpp.Sponsor_ID='".$sponsorID."' $consultationvalue $Billing_Type $dataEntry $filter";
      
     //echo $sql.'<br /><br />';
      
     $query2= mysqli_query($conn,$sql);
     $resulting=  mysqli_fetch_assoc($query2);
     $num_query=  mysqli_num_rows($query2);
     if($num_query>0){
        $data.="<br/><table width = '80%'>
        <tr><td colspan='4'><b>".$row['Item_Category_Name']."</b></td></tr>
        <tr>
          <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
        </tr>";
           
     //Retreive Iterms and filter
      while ($rowItems = mysqli_fetch_array($itemsRs)) {
        $sqlSubcategory="SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowItems['Item_Subcategory_ID']."' AND Item_category_ID='".$row['Item_Category_ID']."'";
        $subcategoryRs=  mysqli_query($conn,$sqlSubcategory) or die(mysqli_fetch_assoc($result));
          
        if(mysqli_num_rows($subcategoryRs)>0){
         if($Status=='All'){
              if($Transaction=='All'){
              $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' $dataEntry $filter";
                } else {
               $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID
                WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' AND Billing_Type=('Outpatient ".$Transaction."' || 'Inpatient ".$Transaction."')$dataEntry $filter
               ";     
              }
              
              }else{
               
               if($Transaction=='All'){
                $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                FROM tbl_patient_payments as tpp
                LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID

                 WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' AND Billing_Type=('Outpatient Credit' || 'Inpatient Cash') $dataEntry $filter
                ";  
                } else {
                $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                FROM tbl_patient_payments as tpp
                LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID

                 WHERE tpp.Sponsor_ID='$sponsorID' AND tpl.Item_ID='".$rowItems['Item_ID']."' AND Billing_Type='".$Status." ".$Transaction."' $dataEntry $filter
                ";     
              } 
          }
          
          
          
          
//            echo $sql.'<br /><br />';
              $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
              while ($rowTpl = mysqli_fetch_array($queryResult)) {
              if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit'){
                 // if($rowTpl['Process_Status']=='served'){
                      $totalSubQty=$rowTpl['Quantity'];
                      $totalSubAmount=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
                 // }
                  
              } else {
                  $totalSubQty=$rowTpl['Quantity'];
                  $totalSubAmount=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
              } 
              
              }
          }
          
          $TotalQty+=$totalSubQty;
          $total+=$totalSubAmount;
          $TotalIndividual+=$totalSubAmount;
         
            if($totalSubQty>0){ 
                $data.='<tr><td>'.$i.'</td><td>'.$rowItems['Product_Name'].'</td><td>'.number_format($totalSubQty).'</td><td>'.number_format($totalSubAmount).'</td></tr>';
                $i++;
            
              }     
                $totalSubAmount=0;
                $totalSubQty=0;
              }
              
               
//        echo $Quantity.'<br /><br /><br />';
          $data.="<tr><td colspan='2' style='text-align:right'><b>Total</b></td><td><b><b>".number_format($TotalQty)."</b></td><td><b>".number_format($TotalIndividual)."</b></td></tr>";
          $data.='</table><p>&nbsp;</p>';

          $TotalQty=0;
          $TotalIndividual=0;
          $grandTotal=0;
          $grandTotal=$grandTotal+$total;
         
         
           }
         }
      $data.='<span style="font-weight:bold;font-size:18px">Grand Total :'.number_format($grandTotal).'</span>';
     
     //loop from here
      
    }else if($temp=='All Sponsors'){
        
                $grandTotal=0;
                $data.='<table align="center" class="hv_table" width="80%" border="0">
                  <tr>
                  <tr>
                     <td style="text-align:center">'.$duration.'</td>
                  </tr>
                  <tr>
                     <td style="text-align:center">STAFF NAME:'.strtoupper($Name).'</td>
                  </tr>
                  <tr>
                     <td style="text-align:center">PAYMENT MODE:'.strtoupper($Transaction).'</td>
                  </tr>
                  <tr>
                    <td style="text-align:center">PATIENT STATUS:'.strtoupper($Status).'</td>
                  </tr>
                  <tr>
                <td style="text-align:center"><b>CONSULTATION TYPE:'.strtoupper($consultation).'</b></td>
                </tr>
                </table>
                <p width = "80%"><b>ALL SPONSORS SERVICES</b></p>
                ';
        
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    while ($rowSponsors = mysqli_fetch_array($query)) {
        // $data.= '<option value="'.$rowSponsors['Sponsor_ID'].'$$>'.$rowSponsors['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
       
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     $total=0;
     $totalQty=0;
     $totalSubQty=0;
     $totalSubAmount=0;
     $TotalIndividual=0;
//   $grandTotal=0;
     $TotalQty=0;
     $individualSponsor=0;
     $data.="<br/><table width = '80%'>
           <tr style='background-color:#037DB0;color:rgb(255,255,255);font-weight:bold;font-size:18px'>
             <th colspan='4'>".strtoupper($rowSponsors['Guarantor_Name'])." SERVICES</th>
           </tr></table>     
           ";
     
      
     
   while ($row = mysqli_fetch_array($categoryRs)) {
//  //retreive the Subcategory
          $i=1;
         
      $sqlIterms="SELECT Item_ID,Product_Name,Item_Subcategory_ID  FROM tbl_items";   

      $itemsRs=  mysqli_query($conn,$sqlIterms) or die(mysqli_error($conn));
      $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,itc.Item_category_ID,tpl.Process_Status
             FROM tbl_patient_payments as tpp JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
            JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID JOIN tbl_item_subcategory as itsb ON itsb.Item_Subcategory_ID=itm.Item_Subcategory_ID JOIN tbl_item_category as itc ON itc.Item_category_ID=itsb.Item_category_ID WHERE itc.Item_category_ID='".$row['Item_Category_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' $consultationvalue $Billing_Type $dataEntry $filter";
      
        //   echo $sql.'<br /><br />';
        //   exit();
     $query2= mysqli_query($conn,$sql);
     $resulting=  mysqli_fetch_assoc($query2);
     $num_query=  mysqli_num_rows($query2);
     if($num_query>0){
        $data.="<br/><table width = '80%' id='hide".$row['Item_Category_ID'].$rowSponsors['Sponsor_ID']."'>
           <tr><td colspan='4'><b>".$row['Item_Category_Name']."</b></td></tr>
           <tr>
             <th>S/N</th><th>PARTICUALR NAME</th><th>QUANTITY</th><th>AMOUNT</th>
           </tr>    
           ";      

     //Retreive Iterms and filter
       while ($rowItems = mysqli_fetch_array($itemsRs)){
       $sqlSubcategory="SELECT Item_Subcategory_ID,Item_Category_ID  FROM tbl_item_subcategory WHERE Item_Subcategory_ID='".$rowItems['Item_Subcategory_ID']."' AND Item_category_ID='".$resulting['Item_category_ID']."'";
       $subcategoryRs=mysqli_query($conn,$sqlSubcategory) or die(mysqli_error($conn));
           if(mysqli_num_rows($subcategoryRs)>0){
              $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, tpp.Receipt_Date,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Discount,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND tpl.Item_ID='".$rowItems['Item_ID']."' $Billing_Type $consultationvalue $dataEntry $filter";
	  
              $u=1;
             // echo $sql1.'<br /><br />';
             $queryResult=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
              while ($rowTpl = mysqli_fetch_array($queryResult)) {   
             if($rowTpl['Billing_Type']=='Inpatient Credit' || $rowTpl['Billing_Type']=='Outpatient Credit'){
                  //if($rowTpl['Process_Status']=='served'){
                     $totalSubQty=$rowTpl['Quantity'];
                     $totalSubAmount=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
                 //}
                 
            }else {
                 $totalSubQty+=$rowTpl['Quantity'];
                 $totalSubAmount+=($rowTpl['Price']-$rowTpl['Discount'])*$rowTpl['Quantity']; 
              }
              
              }
          }

          $TotalQty+=$totalSubQty;
          $total+=$totalSubAmount;
          $TotalIndividual+=$totalSubAmount;
         
            if($totalSubQty>0){ 
           $data.='<tr><td>'.$i.'</td><td>'.$rowItems['Product_Name'].'</td><td>'.number_format($totalSubQty).'</td><td>'.number_format($totalSubAmount).'</td></tr>';
            $i++;
            
            }     
            $totalSubAmount=0;
            $totalSubQty=0;
            
            
           }
           
           //mwisho
               
          $data.="<tr><td colspan='2' style='text-align:right'><b>Total</b></td><td class='hideMe' id='".$row['Item_Category_ID'].$rowSponsors['Sponsor_ID']."' ppval='".$TotalQty."'><b><b>".number_format($TotalQty)."</b></td><td><b>".number_format($TotalIndividual)."</b></td></tr>";
          $data.='</table><p>&nbsp;</p>';
          $grandTotal=$grandTotal+$TotalIndividual;
          $individualSponsor=$individualSponsor+$TotalIndividual;
          $TotalQty=0;
          $TotalIndividual=0;
       
       
     }else{
       // $data.=$row['Item_Category_Name'].  "Is equal to zero";
     }
     }
     
     
     //hapapa
     $data.='<span style="font-weight:bold">'.strtoupper($rowSponsors['Guarantor_Name']).' TOTAL: '.number_format($individualSponsor).'</span><br />';
     $individualSponsor=0;
    }
    
    $data.='<br /><br /><span style="font-weight:bold;font-size:20px">GRAND TOTAL :'.number_format($grandTotal).'</span>';
    }
    
    
 if($data !==''){        
//include("MPDF/mpdf.php");
////htmlspecialchars($data, ENT_NOQUOTES, "UTF-8"); 
//$data = utf8_encode($data);
//$mpdf=new mPDF(); 
//$mpdf->WriteHTML($data);
//$mpdf->Output();
 include("./MPDF/mpdf.php");
     $data = utf8_encode($data);
    $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet =file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($data,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
 }else{
     echo 'passed IN';
 }
?>

