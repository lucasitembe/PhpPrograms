<?php
    include("includes/connection.php");
    require 'PHPMailer-master/PHPMailerAutoload.php';
    include("MPDF/mpdf.php");
?>
<style>
        table,tr,td{
        
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style>
<?php
@session_start();
     $getDate= mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATE(NOW()) AS TODAYDATE"))['TODAYDATE'];
    // die($getDate);
    $Date_From=$Date_To=$today=$getDate; //date('Y-m-d');
    $html='';
	
	//PF3 Report 
	    
	   $select_pf3 = mysqli_query($conn,"SELECT SUM((
										SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
										WHERE pr.Registration_ID=ci.Registration_ID
										   AND ci.Visit_Date = '$today'
											 AND ci.Check_In_ID=pf3.Check_In_ID  AND  pr.Gender='Male'    
										)) as male,
										SUM((
										SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
										WHERE pr.Registration_ID=ci.Registration_ID
											 AND ci.Visit_Date = '$today'
											 AND ci.Check_In_ID=pf3.Check_In_ID AND  pr.Gender='Female'    
										)) as female
                                        FROM tbl_pf3_patients pf3 ORDER BY pf3.pf3_ID ASC"
                        ) or die(mysqli_error($conn));
//  include 'includes/reportheaderEmail.php';

        $SMTP_Host = '';
        $SMTP_Security = '';
		$SMTP_Port = '';
        $Username = '';
		$Password = '';
		$Email_subject ='';
        $Email_Body = '';
	    $Carbon_Copy = '';
		$Blind_Carbon_Copy = '';
     
	$html .= ' <img src="./branchBanner/branchBanner.png" width="100%"><div align="center" style="padding:5px;margin-bottom:20px;margin-top:20px;"> <b>SUMMARY ~ PF3 REPORT</b><b style="color: blue;">&nbsp;&nbsp;&nbsp;'.$today.'</b></div>'; 	
   
    $html .= '<center><table  width="100%" border="0">';
    $html .=" <tr><td style='border: 1px #ccc solid;'><b>GENDER</b></td>
                <td style='border: 1px #ccc solid;text-align:center'><b>TOTAL</b></td>
              </tr>";
	 
     $html .= "<tr>
                <td colspan='2' style='border: 1px #ccc solid'><hr></td></tr>";
    
    
    $row=  mysqli_fetch_assoc($select_pf3);
       $total_male=$row['male'];
       $total_female=$row['female'];
       $total=$total_male+$total_female; 
      
	$html .= "<tr>";
	$html .= "<td style='border: 1px #ccc solid;'>MALE</td>";
	$html .= "<td style='border: 1px #ccc solid;text-align:center'>".number_format($total_male)."</td>
	          </tr>";
	$html .= "<tr>";
	$html .= "<td style='border: 1px #ccc solid;'>FEMALE</td>";
	$html .= "<td style='border: 1px #ccc solid;text-align:center'>".number_format($total_female)."</td>
	          </tr>";
        
    
    $html .= "<tr><td colspan='5'><hr></td></tr>";
    $html .= "<tr><td style='text-align:right;border: 1px #ccc solid;padding-right:30px;'><b>&nbsp;&nbsp;TOTAL</b></td>";
    $html .= "<td style='text-align:left;border: 1px #ccc solid;text-align:center'><b>".number_format($total)."</b></td></tr>";
              
    $html .=" </table></center>";
    $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->WriteHTML($html);
    $mpdf->Output("Daily_reports/pf3_male_female.pdf","F");
    //end Pf3 report
	
  //Number of patient in the day male/female
     $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                        WHERE pr.Registration_ID=ci.Registration_ID
                                                            AND ci.Visit_Date = '$today'
                                                            AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                        ) as male,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                                                        WHERE pr.Registration_ID=ci.Registration_ID
                                                             AND ci.Visit_Date = '$today'
                                                             AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                        ) as female
                                                    FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                        ) or die(mysqli_error($conn)); 
      $html='';
                //  include 'includes/reportheaderEmail.php';

           $html .= ' <img src="./branchBanner/branchBanner.png" width="100%"><div align="center" style="padding:5px;margin-bottom:20px;margin-top:20px;"> <b>SUMMARY ~ DEMOGRAPHIC REPORT</b><b style="color: blue;">&nbsp;&nbsp;&nbsp;'.$today.'</b></div>';   
   
    $html .= '<center><table  width="100%" border="0">';
    $html .= "<tr>
                <td style='text-align:left;width:3%;border: 1px #ccc solid;'><b>SN</b></td>
                <td style='border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
                <td style='border: 1px #ccc solid;'><b>MALE</b></td>
                <td style='border: 1px #ccc solid;'><b>FEMALE</b></td>
		<td style='border: 1px #ccc solid;'><b>TOTAL</b></td>
         </tr>";
	 
     $html .= "<tr>
                <td colspan='5' style='border: 1px #ccc solid'><hr></td></tr>";
     
     $total_Male=0;
    $total_Female=0;
    $res=mysqli_num_rows($select_demograph);//die($res);
    
    //echo $res;exit;
    
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($select_demograph);
	//return rows
	$sponsorID=$row['Sponsor_ID'];
	$sponsorName=$row['Guarantor_Name'];
	$male=$row['male'];
	$female=$row['female'];
        $html .= "<tr><td style='border: 1px #ccc solid;'>".($i+1)."</td>";
        $html .= "<td style='border: 1px #ccc solid;'>".$row['Guarantor_Name']."</td>";
	$total_Male=$total_Male + $male;
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($male)."</td>";
	$total_Female=$total_Female + $female;
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($female)."</td>";
	$total=$male+$female;
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($total)."</td>";
    }//end for loop
	$html .= "<tr><td colspan='5'><hr></td></tr>";
    $html .= "<tr><td colspan=2 style='text-align:right;width:2%;border: 1px #ccc solid;padding-right:20px;'><b>&nbsp;&nbsp;TOTAL</b></td>";
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total_Male)."</b></td>";
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total_Female)."</b></td>";
    $total_Male_Female=$total_Male+$total_Female;
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total_Male_Female)."</b></td></tr>"
            . " </table></center>";
	
    
    $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->WriteHTML($html);
    //$mpdf->Output();
    $mpdf->Output("Daily_reports/number_patient_male_female.pdf ","F");

    //echo $html;exit;
  //End patient in the day male/female 
  
    //summary sponsors report CASH/CREDIT/MSAMAHA

    $sql="SELECT * tbl_sponsor sp "
            . "JOIN tbl_patient_payments pp ON pp.Sponsor_ID=sp.Sponsor_ID"
            . "JOIN tbl_patient_payment_item_list ppil ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID" 
          ;
   // 
     $select_revenue = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                     (
                                                        SELECT SUM((Price-Discount)*Quantity) FROM tbl_patient_payments pp
                                                        JOIN tbl_patient_payment_item_list ppil ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID
                                                        WHERE pp.Billing_Type IN ('Outpatient Cash','Inpatient Cash') AND pp.Sponsor_ID=sp.Sponsor_ID  AND pp.Receipt_Date='$today'
                                                     ) as totalCash,
                                                    (
                                                     SELECT SUM((Price-Discount)*Quantity) FROM tbl_patient_payments pp
                                                     JOIN tbl_patient_payment_item_list ppil ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID
                                                     WHERE pp.Billing_Type IN ('Outpatient Credit','Inpatient Credit') AND pp.Sponsor_ID=sp.Sponsor_ID  AND pp.Receipt_Date='$today'
                                                     ) as totalCredit
                                                        
                                                    FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                        ) or die(mysqli_error($conn)); 
     
    $html=''; 
   // //  include 'includes/reportheaderEmail.php';
     
	$html .= ' <img src="./branchBanner/branchBanner.png" width="100%"><div align="center" style="padding:5px;margin-bottom:20px;margin-top:20px;"> <b>SUMMARY ~ REVENUE REP0RT</b><b style="color: blue;">&nbsp;&nbsp;&nbsp;'.$today.'</b></div>'; 	
   
    $html .= '<center><table  width="100%" border="0">';
    $html .=" <tr>           <td style='text-align:left;width:3%;border: 1px #ccc solid;'><b>SN</b></td>
                <td style='border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
                <td style='border: 1px #ccc solid;'><b>CASH</b></td>
                <td style='border: 1px #ccc solid;'><b>CREDIT</b></td>
		<td style='border: 1px #ccc solid;'><b>TOTAL</b></td>
         </tr>";
	 
     $html .= "<tr>
                <td colspan='5' style='border: 1px #ccc solid'><hr></td></tr>";
     
    $total_cash=0;
    $total_credit=0;
    $total=0;$sn=1;
    
    while($row=  mysqli_fetch_array($select_revenue)){
       $total_cash +=$row['totalCash'];
       $total_credit +=$row['totalCredit'];
       $total+=$row['totalCredit']+$row['totalCash']; 
        $sponsorID=$row['Sponsor_ID'];
	$sponsorName=$row['Guarantor_Name'];
	$html .= "<tr><td style='border: 1px #ccc solid;'>".($sn++)."</td>";
        $html .= "<td style='border: 1px #ccc solid;'>".$row['Guarantor_Name']."</td>";
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($row['totalCash'])."</td>";
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($row['totalCredit'])."</td>";
	$html .= "<td style='border: 1px #ccc solid;'>".number_format($row['totalCredit']+$row['totalCash'])."</td>";
        
    }
    $html .= "<tr><td colspan='5'><hr></td></tr>";
    $html .= "<tr><td colspan=2 style='text-align:right;width:2%;border: 1px #ccc solid;padding-right:20px;'><b>&nbsp;&nbsp;TOTAL</b></td>";
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total_cash)."</b></td>";
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total_credit)."</b></td>";
    $html .= "<td style='text-align:left;width:15%;border: 1px #ccc solid;'><b>".number_format($total)."</b></td></tr>"
            . " </table></center>";
    $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->WriteHTML($html);
    $mpdf->Output("Daily_reports/cash_credit_msamaha.pdf","F");
    //End revenue Collection
    
    
    //Desease report
      $html=''; 
   // //  include 'includes/reportheaderEmail.php';
    $html = ' <img src="./branchBanner/branchBanner.png" width="100%"><div align="center" style="padding:5px;margin-bottom:20px;margin-top:20px;"> <br/><b>SUMMARY ~ DIAGNOSED DISEASES REPORT</b><b style="color: blue;">&nbsp;&nbsp;&nbsp;'.$today.'</b></div>';   
   
    $html .= '<center><table  width="100%" border="0">
                
            <tr><td colspan="6"><hr/></td></tr>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td ><b>DISEASE NAME</b></td>
                <td width=""  style="text-align: left;"><b>DISEASE CODE</b></td>
                <td width="" style="text-align: left;"><b>MALE</b></td>
                <td width="" style="text-align: left;"><b>FEMALE</b></td>
                <td width="" style="text-align: left;"><b>TOTAL</b></td>
            </tr>
            <tr><td colspan="6"><hr/></td></tr>
            ';
    
   
   
     $result = mysqli_query($conn,"select count(dc.disease_ID) as amount, d.disease_name,d.disease_code,d.disease_ID
                                    from tbl_disease_consultation dc, tbl_disease d where
                                    d.disease_ID = dc.disease_ID and
                                    diagnosis_type = 'diagnosis'
                                    AND DATE( dc.Disease_Consultation_Date_And_Time) = '$today'
                                    group by d.disease_ID order by d.disease_name") or die(mysqli_error($conn));
     
     
      $temp=1;
      $maleNumber= 0;
      $femaleNumber= 0;
      
      while($row = mysqli_fetch_array($result)){
          
          $query=  mysqli_query($conn,"SELECT (SELECT COUNT( pr.Registration_ID ) FROM tbl_disease_consultation  dc
                             RIGHT JOIN tbl_consultation c ON c.consultation_ID=dc.consultation_ID
                             JOIN tbl_patient_registration pr ON  pr.Registration_ID = c.Registration_ID
                              WHERE pr.Gender = 'Male' AND  diagnosis_type = 'diagnosis' AND dc.disease_ID='".$row['disease_ID']."' AND DATE( dc.Disease_Consultation_Date_And_Time) = '$today'
                            ) as male,
                            (SELECT COUNT( pr.Registration_ID ) FROM tbl_disease_consultation  dc
                             RIGHT JOIN tbl_consultation c ON c.consultation_ID=dc.consultation_ID
                             JOIN tbl_patient_registration pr ON  pr.Registration_ID = c.Registration_ID
                             WHERE pr.Gender = 'Female' AND  diagnosis_type = 'diagnosis' AND dc.disease_ID='".$row['disease_ID']."' AND DATE( dc.Disease_Consultation_Date_And_Time) = '$today'
                            ) as female
                  "
                  ) or die(mysqli_error($conn));
          
          $rowC=mysqli_fetch_array($query);
          $maleNumber = $rowC['male'];
          $femaleNumber = $rowC['female'];
          
          if($femaleNumber ==''){
              $femaleNumber=0;
          }
          
           $html .= "<tr><td >".($temp++)."</td>
                      <td>
                       ".$row['disease_name']."
                      </td> 
                      <td style='text-align: left;'>
                       ".$row['disease_code']."
                      </td> 
                      <td style='text-align: left;'>
                        ".$maleNumber."
                      </td>
                      <td style='text-align: left;'>
                        ".$femaleNumber."
                      </td>
                      <td style='text-align: left;'>
                        ".($maleNumber+$femaleNumber)."
                      </td>
                    </tr>";
      }
       $html .= "</table></center>";
       
       
      //echo $html;exit;
    $mpdf=new mPDF('','A4-L',0,'',12.7,12.7,14,12.7,8,8); 
   // $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->WriteHTML($html);
    $mpdf->Output("Daily_reports/desease_report.pdf","F");
    
    //Patients seen by doctors
      $html=''; 
    //  include 'includes/reportheaderEmail.php';
    $html .= '<img src="./branchBanner/branchBanner.png" width="100%"><div align="center" style="padding:5px;margin-bottom:20px;margin-top:20px;"> <b>SUMMARY ~ DOCTOR\'S PERFORMANCE REPORT</b><b style="color: blue;">&nbsp;&nbsp;&nbsp;'.$today.'</b></div>';   
   
    $html .= '<center><table  width="100%" border="0">
            <tr><td colspan="3"><hr></td></tr>
           <tr>
                <td width="3"% style="text-align:left"><b>SN</b></td>
                <td style="text-align:left"><b>DOCTOR\'S NAME</b></td>
                <td style="text-align: left;" width="12%"><b>PATIENTS</b></td>
           </tr>
           <tr><td colspan="3"><hr></td></tr>';
        
      //run the query to select all data from the database according to the branch id
		      $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
                        $result_patient_no= mysqli_query($conn,"SELECT COUNT(c.Registration_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE DATE(ch.cons_hist_Date) = '$today' AND ch.employee_ID='$employeeID'") or die(mysqli_error($conn));
                         
                        
//			$select_patient_item_list=mysqli_query($conn,"SELECT COUNT(Registration_ID) AS numberOfPatients FROM tbl_consultation co,tbl_patient_payment_item_list ppl
//			  WHERE co.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
//			  co.employee_ID=ppl.Consultant_ID AND
//			  ppl.Consultant_ID='$employeeID' AND ppl.Process_Status='signedoff'
//			  AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
//			  ");
			$patient_no_number=mysqli_fetch_array($result_patient_no)['numberOfPatients'];
			
			
			 $empSN++;
			 $html .= "<tr><td>".($empSN)."</td>";
			 $html .= "<td style='text-align:left'>".$employeeName."</td>";
			 $html .= "<td style='text-align:center'>".number_format($patient_no_number)."</td></tr>";
		    }
			    
			 $html .='</table></center>';
			
    $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->WriteHTML($html);
    $mpdf->Output("Daily_reports/doctors_performance.pdf","F");
    
    
    //Start sending email for the generated pdf
    
     date_default_timezone_set('Africa/Dar_es_Salaam');

    $q=mysqli_query($conn,"SELECT * FROM tbl_email_settings") or die(mysqli_error($conn));
	$row=mysqli_fetch_assoc($q);
	$exist=mysqli_num_rows($q);
	 if ($exist >0){
	    $SMTP_Host = $row['SMTP_Host'];
        $SMTP_Security = $row['SMTP_Security'];
		$SMTP_Port = $row['SMTP_Port'];
        $Username = $row['Username'];
		$Password = $row['Password'];
		$Email_subject = $row['Email_subject'];
        $Email_Body = $row['Email_Body'];
	    $Carbon_Copy = $row['Carbon_Copy'];
		$Blind_Carbon_Copy = $row['Blind_Carbon_Copy'];
	}
    $mail = new PHPMailer;
    
    $mail->isSMTP();
    $mail->Host = trim($SMTP_Host);
    $mail->SMTPAuth = true;
    $mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->SMTPSecure = trim($SMTP_Security);
    //$mail->SMTPDebug = 1;
    $mail-> Port = trim($SMTP_Port);
    $mail->Username = trim($Username);
    $mail->Password = trim($Password);
    $mail->setFrom(trim($Username), 'AMANA REFERRAL HOSPITAL');
    //$mail->addReplyTo('ibnmvungi@outlook.com', 'AUTOMATIC NOTIFICATIONS FOR AMANA');
    $mail->Subject =trim($Email_subject).' '.$today; //"AMANA REFFERAL HOSPITAL REPORTS YESTERDAY";
	
	 $select_recepients = mysqli_query($conn,
            "select * from tbl_email_recepients") or die(mysqli_error($conn));
		
     while($rowTo=mysqli_fetch_array($select_recepients)){	
        $mail->addAddress($rowTo['Recepient_Email'], $rowTo['Recepient_Name']);	 
	 }
    
    $body='<p>'.trim($Email_Body).'</p>
	        <p>Please find '.$today.' reports</p>
	      ';
	   
    $mail->msgHTML($body);
	$mail->addAttachment('Daily_reports/pf3_male_female.pdf', 'PF3 Male and Female Report');
    $mail->addAttachment('Daily_reports/cash_credit_msamaha.pdf', 'Revenue Collection Report');
    $mail->addAttachment('Daily_reports/doctors_performance.pdf', "Doctor's Performance Report");
    $mail->addAttachment('Daily_reports/desease_report.pdf', 'Disease Report');
    $mail->addAttachment('Daily_reports/number_patient_male_female.pdf', 'Number of Female And Male Report');
          //msgHTML also sets AltBody, but if you want a custom one, set it afterwards
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    
	//cc
	   $cc=explode(';',$Carbon_Copy);
	   
	   foreach($cc as $Email_cc){
	       $mail->addCC(trim($Email_cc), 'A copy has been made to you');
	   }
	//BCC
       $bcc=explode(';',$Blind_Carbon_Copy);
	   // echo '<pre>';
		// print_r($bcc);exit;
	   foreach($bcc as $Email_bcc){
	       $mail->addBCC(trim($Email_bcc), 'A copy has been made to you');
	   }	
    
        if (!$mail->send()) {
           echo "Mailer " . $mail->ErrorInfo . '<br />';
        } else {
           echo "Message sent.<br />";
        }