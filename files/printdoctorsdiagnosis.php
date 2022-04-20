<?php
    @session_start();
    include("./includes/connection.php");
    
    $Date_From='';//@$_POST['Date_From'];
    $Date_To='';//@$_POST['Date_To'];
    $todayqr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
    $today=$todayqr;//date('Y-m-d H:m:s');
    
        if(!isset($_GET['Date_From'])){
             $Date_From=  $today;
        }else{
            $Date_From=$_GET['Date_From']; 
        }
        if(!isset($_GET['Date_To'])){
             $Date_To=   $today;
        }else{
            $Date_To=$_GET['Date_To'];
        }if(!isset($_GET['Employee_ID'])){
            $Employee_ID=  0;
        }else{
            $Employee_ID=$_GET['Employee_ID'];
        }
        
        $employeeID=$employee_ID=$Employee_ID;//exit;
        $EmployeeName= strtoupper(mysqli_fetch_assoc( mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);
    
        $htm = "<table width ='100%'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>$EmployeeName PATIENTS WITH NO FINAL DIAGNOSIS</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>". date('j F, Y H:i:s',strtotime($Date_From))."</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>". date('j F, Y H:i:s' ,strtotime($Date_To))."</b></td></tr>
		    </table><br/>";

       $htm .= '<center><table width ="100%" >';
		$htm .= "<thead><tr class='headerrow'>
			    <th width='3%' style='text-align:left'>SN</th>
                            <th style='text-align:left;width:40%'>PATIENT NAME</th>
                            <th style='text-align:left'>CONSULT</th>
                            <th style='text-align:left'>LAB</th> 
                            <th style='text-align:left'>RADIOLOGY</th>
                            <th style='text-align:left'>PHARMACY</th>
                            <th style='text-align:left'>PROCEDURE</th>
                            <th style='text-align:left;width:20%'>FINAL DIAGNOSIS</th>
                            <th style='text-align:left'>PHONE</th>
                            </tr>
                            <tr><td colspan='9'><hr style='width:100%'/></td></tr>
                            </thead>";
		   
		  $avoidDoctorNameDuplicate=0;$Employee_Name_Cur='';
                  
		  $consultArrray=array();
      $getConsultationResult=mysqli_query($conn,"SELECT ch.consultation_ID FROM tbl_consultation_history ch JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employeeID'") or die(mysqli_error($conn));
                        
                       while ($row = mysqli_fetch_array($getConsultationResult)) {
                           
                       
                          $result_patient=   mysqli_query($conn,"SELECT dc.Disease_Consultation_ID FROM tbl_disease_consultation dc WHERE dc.consultation_ID ='".$row['consultation_ID']."' AND dc.diagnosis_type='diagnosis'") or die(mysqli_error($conn));
                          
                          if(mysqli_num_rows($result_patient) > 0){
                              
                          }  else {
                              $consultArrray[]=$row['consultation_ID'];
                          }
                          
                       }             
    //retrieve consultations for the employee		
    //$consultations=mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,co.Registration_ID,co.employee_ID,co.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID FROM tbl_consultation co JOIN tbl_patient_registration pr ON pr.Registration_ID=co.Registration_ID JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE co.employee_ID='$employeeID' AND DATE(co.Consultation_Date_And_Time)='$today'") or die(mysqli_error($conn));
    $consultations= mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch INNER JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE c.consultation_ID IN (".  implode(',', $consultArrray).")") or die(mysqli_error($conn));
                  $empSN=1; 
          
     if(mysqli_num_rows( $consultations)>0){	
	    
	      
		while($row=mysqli_fetch_array($consultations)){
			
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<span class='linkstyle' >".$row['Patient_Name']."</a>";
			
			$employeeName="<span class='linkstyle' >".$row['Employee_Name']."</a>";
			
			$Employee_Name_Cur=$row['Employee_Name'];
			
			$Phone_Number="<span class='linkstyle' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<span class='linkstyle' >No</a>";
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today'
		") or die(mysql_error);
		
		
		//$htm .= "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		  $finalDiagnosis="<span class='linkstyle' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ) as Procedur
	 ") or die(mysqli_error($conn));
	 
	$rowChkType=mysqli_fetch_assoc($select_checking_type);
        $Laboratory=$rowChkType['Laboratory'];
	$Radiology=$rowChkType['Radiology'];
	$Pharmacy=$rowChkType['Pharmacy'];	
	$Procedur=$rowChkType['Procedur'];
        //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
			$htm .= "<tr><td>".($empSN++)."</td>";
			//$htm .= "<td style='text-align:left'>".$employeeName."</td>";
			$htm .= "<td style='text-align:left'><span class='linkstyle' >".$patient_name."</span></td>";
			$htm .= "<td style='text-align:center'><span class='linkstyle' >Yes</a></td>";
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Laboratory>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Radiology>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Pharmacy>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Procedur>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'>".($finalDiagnosis)."</td>";
			$htm .= "<td style='text-align:center'>".($Phone_Number)."</td></tr>";
			$htm .= "<tr><td colspan='9'><hr style='width:100%'/></td></tr>";
			// $avoidDoctorNameDuplicate=1;
		}
	  }else{
	  
	      $checkOtherConsultaion=mysqli_query($conn,"SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID JOIN tbl_consultation co ON co.consultation_ID=pc.consultation_id JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID  WHERE ilc.Consultant_ID='$employeeID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' GROUP BY pc.Registration_ID") or die(mysqli_error($conn));
		  
		  //ECHO "SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID  WHERE ilc.Consultant_ID='$employeeID' AND DATE(pc.Payment_Date_And_Time)='$today' GROUP BY pc.Registration_ID";
		  $avoidDoctorNameDuplicate=0;
		
      if(mysqli_num_rows( $checkOtherConsultaion) >0){		
		  //$empSN ++;
		while($row=mysqli_fetch_array($checkOtherConsultaion)){
			
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<span class='linkstyle' >".$row['Patient_Name']."</a>";
			
			$employeeName="<span class='linkstyle' >".$Employee_Name_Cur."</a>";
			
			
			
			$Phone_Number="<span class='linkstyle' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<span class='linkstyle' >No</span>";
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today'
		") or die(mysql_error);
		
		//$htm .= "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		   $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".			$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND   pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id=$consultation_ID) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id=$consultation_ID) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id=$consultation_ID) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id=$consultation_ID) as Procedur
	 ") or die(mysqli_error($conn));
	 
	$row2=mysqli_fetch_assoc($select_checking_type);
        $Laboratory=$row2['Laboratory'];
	$Radiology=$row2['Radiology'];
	$Pharmacy=$row2['Pharmacy'];	
	$Procedur=$row2['Procedur'];
       // if( $avoidDoctorNameDuplicate==1){$employeeName='';}
			$htm .= "<tr><td>".($empSN++)."</td>";
			//$htm .= "<td style='text-align:left'>".$employeeName."</td>";
			$htm .= "<td style='text-align:left;width:40%'><span class='linkstyle'>".$patient_name."</span></td>";
			$htm .= "<td style='text-align:center'><span class='linkstyle' >Yes</span></td>";
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Laboratory>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Radiology>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Pharmacy>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center'><span class='linkstyle' >".($Procedur>0?'Yes':'No')."</span></td>";
			
			$htm .= "<td style='text-align:center;width:20%'>".($finalDiagnosis)."</td>";
			$htm .= "<td style='text-align:center'>".($Phone_Number)."</td></tr>";
			$htm .= "<tr><td colspan='9'><hr style='width:100%'/></td></tr>";
			 //$avoidDoctorNameDuplicate=1;
		  }
		}
	  }
          
           $htm .='</table></center>';
           
           include("MPDF/mpdf.php");
            $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
            
            //$mpdf->SetDisplayMode('fullpage');

            //$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

            // LOAD a stylesheet
            $stylesheet = file_get_contents('mpdfstyletables.css');
            $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

            $mpdf->WriteHTML($htm,2);

            $mpdf->Output('mpdf.pdf','I');