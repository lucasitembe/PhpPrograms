<?php 
 // $GrandTotal=0;
   ini_set('max_execution_time', 300);
   include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    } 
    $today=date('Y-m-d');
    $data='';
    $title_for_report='';
    $query_string='';
    
    $EntryName= filter_input(INPUT_POST, 'EntryName');
    if($EntryName=='All'){
      $dataEntry='';  
    }  else {
     $dataEntry='AND tpp.Employee_ID="'.$EntryName.'"';  
    }
    if($EntryName=='All'){
     $Name='All';  
    }else{
    $StaffName=mysqli_query($conn,'SELECT Employee_Name FROM tbl_employee WHERE Employee_ID="'.$EntryName.'"');
    $qry= mysqli_fetch_assoc($StaffName);
     $Name=$qry['Employee_Name'];  
    }
     $Status= filter_input(INPUT_POST, 'Status');//
     $Transaction= filter_input(INPUT_POST, 'Transaction');  
 if(isset($_POST['showPatientPayment'])){ 
     $fromDate=  filter_input(INPUT_POST, 'fromDate');
     $todate=  filter_input(INPUT_POST, 'todate');
     $temp= filter_input(INPUT_POST, 'sponsor');
     $sponsorID='';
     $sponsorname='All Sponsors';
     $EntryName=filter_input(INPUT_POST, 'EntryName');
     $Status=filter_input(INPUT_POST, 'Status');
     $Transaction=filter_input(INPUT_POST, 'Transaction');
     $catgName=array();$catgSubprice=array();
     $catgPrices=array();
     
     if($temp=='All Sponsors'){
         
     }else{
         $temp=  explode("$$>", $temp);
         $sponsorID= $temp[0];
         $sponsorname=$temp[1];
     }
     
    $query_string='type=showPatientPayment&fromDate='.$fromDate.'&todate='.$todate.'&sponsorID='.$sponsorID.'&sponsorname='.$sponsorname;
     $filter='';
     
     $duration='<b>All Time Revenue</b>';
     //$servedby='<b>Services By All employees</b>';
    
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
        }if(isset($todate) && empty($todate) && isset($fromDate) && empty($fromDate)){
			$filter="AND DATE(tpp.Payment_Date_And_Time) >='".$today."'";
			$date=new DateTime($today);
			$duration='From&nbsp;&nbsp; <b>'.date_format($date,'Y-M-d').'</b>';
		  
			$query_string='type=showPatientPayment&fromDate='.$today.'&todate=&sponsorID='.$sponsorID.'&sponsorname='.$sponsorname;
        }

        if($sponsorID !==''){  
          $GrandTotal=0;
          $data.='<table align="center" width="80%">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>REVENUE GROUPED REPORT</b></td>
                         </tr>
                         <tr>
                         <td style="text-align:center"><b>'.$duration.'</b></td>
                         <td style="text-align:center"><b>STAFF NAME:'.strtoupper($Name).'</b></td>   
                         <td style="text-align:center"><b>PAYMENT MODE:'.strtoupper($Transaction).'</b></td>
                         <td style="text-align:center"><b>PATIENT STATUS:'.strtoupper($Status).'</b></td>
                         </tr>
                       </table>
                       <p width = "80%"><b>'.strtoupper($sponsorname).' SERVICES</b></p>
                       ';
           
          //Retrieve users all users
     $sqlPatient="SELECT Registration_ID,Patient_Name  FROM tbl_patient_registration AS tic";
     $pateintRs=  mysqli_query($conn,$sqlPatient) or die(mysqli_error($conn));
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     
     $noOfColumn=  mysqli_num_rows($categoryRs);
     $data.="<br/><table width = '80%'>
            <tr>
             <td><b>BILLING DATE</td><td><b>PATIENT NAME</td><td><b>DOCTOR NAME</td><td><b>FOLLO #</td><td><b>CLAIM FORM #</td>
           ";
     $k=0;
     while ($rowCatgr = mysqli_fetch_array($categoryRs)) {
         $data.="<td title='".$rowCatgr['Item_Category_Name']."'><b>".substr($rowCatgr['Item_Category_Name'], 0, 6)."</b></td>"; 
         $catgName[$rowCatgr['Item_Category_ID']]=$rowCatgr['Item_Category_Name'];
         $catgSubprice[$rowCatgr['Item_Category_ID']]=0;
         $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         $k++;
     }
     
    $data.="<td >Sub Total</td></tr>"; 
    $total=0;
    $subtotal=0;
 
 //Retrieve the calim form number
            
            if($Status=='All'){
                if($Transaction=='All'){
               $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='$sponsorID' $filter $dataEntry";
               
                }  else { 
               $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='$sponsorID' AND Billing_Type=('Outpatient ".$Transaction."' || 'Inpatient ".$Transaction."') $filter"; 
                }
                
                
            }  else {
              if($Transaction=='All'){
               $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='$sponsorID' AND Billing_Type=('Outpatient Credit' || 'Inpatient Cash') $filter"; 
                
                  
              } else {
                  
               $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
               WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='$sponsorID'  $filter"; 
                  
                  
              }   
            }

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
         
           if($Status=='All'){
              if($Transaction=='All'){
               $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='$sponsorID' $filter $dataEntry
             
           ";     
              } else {
                 
               $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='$sponsorID' AND Billing_Type=('Outpatient ".$Transaction."' || 'Inpatient ".$Transaction."') $filter $dataEntry";
  
              }
    
           }  else {
             
            if($Transaction=='All'){
               $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='$sponsorID' AND Billing_Type=('Outpatient Credit' || 'Inpatient Cash') $filter $dataEntry
             
           ";    
              } else {
                 
               $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity,tpl.Discount ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
               FROM tbl_patient_payments as tpp
               LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
               JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='$sponsorID'  AND Billing_Type='".$Status." ".$Transaction."' $filter $dataEntry
             
           ";      
              }  
                 
           }

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
         $data.="
            <tr>
             <td>".$receiptDate."</td><td>".$rowPatient['Patient_Name']."</td><td>".$employeName."</td><td>".$folionumber."</td><td>".$Claim_Form_Number."</td>";
            $rcat=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
          while ($rowct2 = mysqli_fetch_array($rcat)) {
             $data.="<td>".number_format($catgSubprice[$rowct2['Item_Category_ID']])."</td>";
             $catgSubprice[$rowct2['Item_Category_ID']]=0;
         }
         
       //         echo '<pre>';
//         print_r($catgSubprice);
//          echo '</pre>';
//          exit;
         
         $data.="<td width='400'>".number_format($subtotal)."</td></tr>";
        }
      
         $subtotal=0;
      
     }
     $data.="<tr><td style='text-align:center' colspan='5'><b>Total</b></td>";
          $rcat2=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
         while ($rowCatgr = mysqli_fetch_array($rcat2)) {
             $data.="<td>".number_format($catgPrices[$rowCatgr['Item_Category_ID']])."</td>";
             $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         }
     
     $data.='<td>'.number_format($total).'</td></tr></table>';
     $GrandTotal=$GrandTotal+$total;

    }else if($sponsorname=='All Sponsors'){
       $GrandTotal=0;
         $data.='
                      <table align="center" width="80%">
                         <tr>
                         <td colspan="2" style="text-align:center"><b>REVENUE GROUPED REPORT</b></td>
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
                         <td colspan="2" style="text-align:center"><b>ALL SPONSORS SERVICES</b></td>
                         </tr>
                       </table>
                           
                       ';
        
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    
    while ($rowSponsors = mysqli_fetch_array($query)) {
         
           //Retrieve users all users
          $sqlPatient="SELECT Registration_ID,Patient_Name  FROM tbl_patient_registration AS tic";
     $pateintRs=  mysqli_query($conn,$sqlPatient) or die(mysqli_error($conn));
          //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     
     $noOfColumn=  mysqli_num_rows($categoryRs);
     $number_of_column=$noOfColumn+5;
     
     $data.="<br/><table width = '97%'>
           <tr>
             <td colspan='".$number_of_column."' style='text-align:left'><b>".strtoupper($rowSponsors['Guarantor_Name'])." SERVICES</b></td>
           </tr> 
            </table>
           ";
     
     $data.="<br/><table width = '60%'>
            <tr>
             <td><b>BILLING DATE</td><td><b>PATIENT NAME</td><td><b>DOCTOR NAME</td><td><b>FOLLO #</td><td><b>CLAIM FORM #</td>
           ";
     $k=0;
     while ($rowCatgr = mysqli_fetch_array($categoryRs)) {
          $data.="<td title='".$rowCatgr['Item_Category_Name']."'><b>".substr($rowCatgr['Item_Category_Name'], 0, 6)."</b></td>";  
         $catgName[$rowCatgr['Item_Category_ID']]=$rowCatgr['Item_Category_Name'];
         $catgSubprice[$rowCatgr['Item_Category_ID']]=0;
         $catgPrices[$rowCatgr['Item_Category_ID']]=0;
         $k++;
     }
     

     
    $data.="<td><b>Sub Total</td></tr>";
    $total=0;
    $subtotal=0;
   //Retrieve the calim form number

              if($Status=='All'){
                  
                  if($Transaction=='All'){
                    $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
                    WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' $filter $dataEntry";  
                  }  else {
                    $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
                    WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type=('Outpatient ".$Transaction."' || 'Inpatient ".$Transaction."') $filter $dataEntry";
  
                  }
              }  else {
                  if($Transaction=='All'){
                    $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
                    WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type=('Outpatient Credit' || 'Inpatient Cash') $filter $dataEntry";
                    
                  }  else {
                    
                    $sql="SELECT tpp.Patient_Payment_ID,em.Employee_Name,tpp.Folio_Number,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_employee as em ON em.Employee_ID=tpp.Employee_ID
                    WHERE  tpp.Transaction_status !='cancelled' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type='".$Status." ".$Transaction."' $filter $dataEntry"; 
                  }   
              } 

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
        if($Status=='All'){
                  
                  if($Transaction=='All'){
                    $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' $filter $dataEntry
              ";  
                  }  else {
                    $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type=('Outpatient ".$Transaction."' || 'Inpatient ".$Transaction."') $filter $dataEntry
             
           ";
                    
                  }
              }  else {
                  if($Transaction=='All'){
                    $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type=('Outpatient Credit' || 'Inpatient Cash') $filter $dataEntry";
                    
                  }  else {
                    $sql="SELECT tpp.Patient_Payment_ID,itm.Item_Subcategory_ID,tpp.Claim_Form_Number,tpl.Item_ID,tpp.Registration_ID,tpp.Supervisor_ID,tpp.Employee_ID, tpp.Sponsor_ID,tpp.Patient_Payment_ID, DATE_FORMAT(tpp.Receipt_Date,'%d-%b-%Y') AS daterecept,tpp.Billing_Type,tpp.Transaction_status,tpl.Patient_Payment_Item_List_ID,tpl.Item_ID,tpl.Price,tpl.Quantity ,tpp.Payment_Date_And_Time,tpl.Sub_Department_ID,tpl.Process_Status
                    FROM tbl_patient_payments as tpp
                    LEFT JOIN tbl_patient_payment_item_list AS tpl ON tpp.Patient_Payment_ID=tpl.Patient_Payment_ID
                    JOIN tbl_items AS itm ON itm.Item_ID=tpl.Item_ID WHERE tpp.Claim_Form_Number='$Claim_Form_Number' AND tpp.Transaction_status !='cancelled' AND tpp.Registration_ID='".$rowPatient['Registration_ID']."' AND tpp.Sponsor_ID='".$rowSponsors['Sponsor_ID']."' AND Billing_Type='".$Status." ".$Transaction."' $filter $dataEntry";  
                  }   
              } 
           
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

         }
         
//          echo '<pre>';
//          print_r($catgPrices);
//          echo '</pre>';
//          exit;
         
        
         $total+=$subtotal;
         $data.="
            <tr>
             <td>".$receiptDate."</td><td>".$rowPatient['Patient_Name']."</td><td>".$employeName."</td><td>".$folionumber."</td><td>".$Claim_Form_Number."</td>";
            $rcat=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
          while ($rowct2 = mysqli_fetch_array($rcat)) {
             $data.="<td>".number_format($catgSubprice[$rowct2['Item_Category_ID']])."</td>";
             $catgSubprice[$rowct2['Item_Category_ID']]=0;
         }

         $data.="<td>".number_format($subtotal)."</td></tr>";
        }
        
         $subtotal=0;
     }
     $data.="<tr><td style='text-align:center' colspan='5'><b>Total</b></td>";
          $rcat2=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
         while ($rowCatgr = mysqli_fetch_array($rcat2)) {
             $data.="<td>".number_format($catgPrices[$rowCatgr['Item_Category_ID']])."</td>";
             $catgPrices[$rowCatgr['Item_Category_ID']]=0;
      }
     
     $data.='<td>'.number_format($total).'</td></tr></table>';
     $GrandTotal=$GrandTotal+$total;
    }
    }
     //retrieve all category
     $sqlCategory="SELECT tic.Item_Category_ID,tic.Item_Category_Name,tic.Category_Type  FROM tbl_item_category AS tic";
     $categoryRs=  mysqli_query($conn,$sqlCategory) or die(mysqli_error($conn));
     $dataKey='';
     $noOfColumn= mysqli_num_rows($categoryRs);
     $dataKey.="<p style='text-align:right;width:97%;margin-right:10px;'><b>Grand Total: </b>".number_format($GrandTotal)."</p>
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

?>
 <a href="revenuepatientsummaryprint.php?<?php echo $query_string?>" target="_blank" style=""><button type="button" class="art-button-green">Print</button></a>
 <a href="revenuepatients.php" target="" style=""><button type="button" class="art-button-green">Back</button></a>
   
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/> 

<style>
     table,tr,td{
           border-collapse:collapse !important;
           border:none !important;

           }
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 
   
<fieldset style="background-color:white;font-size:larger">
    <legend align='center' style="padding: 10px;background-color:#037CB0;color: white;font-size: 18px;width:50%;margin-bottom: 35px;  "><b>REVENUE COLLECTION BY PATIENT REPORT</b></legend>
    <div style="height:480px;overflow-x:hidden;overflow-y:scroll; width:100%  "> 
        <center>
            <?php echo $data ?>
        </center>
        <div style="width:100%  ">
         <?php echo $dataKey ?>
        </div>
     </div>
    
   
</fieldset>     
<?php
    include("./includes/footer.php");
?>