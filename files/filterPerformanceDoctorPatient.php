 <?php 
 
 include("./includes/connection.php");
 
 if(isset($_POST['filterDoctorsPatient']) && $_POST['filterDoctorsPatient']=='true'){
      //echo 'Success';
	 $doctorID=$date_From=$date_To='';
	  
	 if(isset($_POST['doctorID']) && !empty($_POST['doctorID'])){
		$doctorID=$_POST['doctorID'];
		
     }
	 if(isset($_POST['date_From']) && !empty($_POST['date_From'])){
		$date_From=$_POST['date_From'];
		$temp=explode(' ',$date_From);
		$date_From=trim($temp[0]);
     }
	 if(isset($_POST['date_To']) && !empty($_POST['date_To'])){
		$date_To=$_POST['date_To'];
		$temp=explode(' ',$date_To);
		$date_To=trim($temp[0]);
     }
	$filterConsulDate=$filterPaymentDate=$filterDeasesConsulDate=''; 
	if($date_From !='' && $date_To=='' ){
	  $filterConsulDate="AND DATE(co.Consultation_Date_And_Time) >= '$date_From' ";
	  $filterPaymentDate="AND DATE(pc.Payment_Date_And_Time) >= '$date_From' ";
	  $filterDeasesConsulDate="AND DATE(dc.Disease_Consultation_Date_And_Time) >= '$date_From' ";
	}elseif($date_From =='' && $date_To !=''){
	  $filterConsulDate="AND DATE(co.Consultation_Date_And_Time) <= '$date_To' ";
	  $filterPaymentDate="AND DATE(pc.Payment_Date_And_Time) <= '$date_To' ";
	  $filterDeasesConsulDate="AND DATE(dc.Disease_Consultation_Date_And_Time) <= '$date_To' ";
	}elseif($date_From !='' && $date_To !=''){
	  $filterConsulDate="AND DATE(co.Consultation_Date_And_Time) BETWEEN  '$date_From' AND '$date_To' ";
	  $filterPaymentDate="AND DATE(pc.Payment_Date_And_Time) BETWEEN  '$date_From' AND '$date_To' ";
	  $filterDeasesConsulDate="AND DATE(dc.Disease_Consultation_Date_And_Time) BETWEEN  '$date_From' AND '$date_To' ";
	}
	
	$filteDoctor='';
	
	if($doctorID != '' && $doctorID != 'All' && $doctorID != 'SELECT A DOCTOR'){
	   $filteDoctor="AND Employee_ID='$doctorID'";
	}
	
		echo "<tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
				 <th style='text-align:left'>PATIENT NAME</th>
				 <th style='text-align:left'>CONSULT</th>
				  <th style='text-align:left'>LAB</th> 
				  <th style='text-align:left'>RADIOLOGY</th>
				  <th style='text-align:left'>PHARMACY</th>
	               <th style='text-align:left'>PROCEDURE</th>
	               <th style='text-align:left'>FINAL DIAGNOSIS</th>
	               <th style='text-align:left'>PATIENT PHONE</th>
			  </tr></thead>";
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' $filteDoctor ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query)or die(mysqli_error($conn));
		    
		    $empSN=1;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			
			//$employeeNumber=$select_doctor_row['Employee_Number'];
		  $avoidDoctorNameDuplicate=0;
		  
    //retrieve consultations for the employee		
    $consultations=mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,co.Registration_ID,co.employee_ID,co.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID FROM tbl_consultation co JOIN tbl_patient_registration pr ON pr.Registration_ID=co.Registration_ID JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID  WHERE co.employee_ID='$employeeID'  $filterConsulDate ") or die(mysqli_error($conn));
	 
     if(mysqli_num_rows( $consultations)>0){	
	     
		while($row=mysqli_fetch_array($consultations)){
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Patient_Name']."</a>";
			
			$employeeName="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$select_doctor_row['Employee_Name']."</a>";
			
			
			
			$Phone_Number="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >No</a>";
			
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'  $filterDeasesConsulDate
		") or die(mysql_error);
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '2015-05-18' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		$finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".			$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID'  $filterPaymentDate ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Procedur
	 ") or die(mysqli_error($conn));
	 
	$rowChkType=mysqli_fetch_assoc($select_checking_type);
    $Laboratory=$rowChkType['Laboratory'];
	$Radiology=$rowChkType['Radiology'];
	$Pharmacy=$rowChkType['Pharmacy'];	
	$Procedur=$rowChkType['Procedur'];
        //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
			echo "<tr><td>".($empSN++)."</td>";
			echo "<td style='text-align:left'>".$employeeName."</td>";
			echo "<td style='text-align:left'>".$patient_name."</td>";
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a></td>";
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Laboratory>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Radiology>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Pharmacy>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Procedur>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'>".($finalDiagnosis)."</td>";
			echo "<td style='text-align:center'>".($Phone_Number)."</td></tr>";
			
			// $avoidDoctorNameDuplicate=1;
		}
	  }else{
	        $checkOtherConsultaion=mysqli_query($conn,"SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID JOIN tbl_consultation co ON co.consultation_ID=pc.consultation_id JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ilc.Consultant_ID='$employeeID' $filterPaymentDate GROUP BY pc.Registration_ID") or die(mysqli_error($conn));
		  
		  //ECHO "SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID  WHERE ilc.Consultant_ID='$employeeID' AND DATE(pc.Payment_Date_And_Time)='2015-05-18' GROUP BY pc.Registration_ID";
		  $avoidDoctorNameDuplicate=0;
		 if(mysqli_num_rows( $checkOtherConsultaion) >0){
		while($row=mysqli_fetch_array($checkOtherConsultaion)){
			//$empSN ++;
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Patient_Name']."</a>";
			
			$employeeName="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$select_doctor_row['Employee_Name']."</a>";
			
			
			
			$Phone_Number="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_id'];
			
		    $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >No</a>";
			
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'  $filterDeasesConsulDate
		") or die(mysql_error);
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '2015-05-18' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		$finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".			$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID'  $filterPaymentDate ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' $filterPaymentDate ) as Procedur
	 ") or die(mysqli_error($conn));
	 
	$rowChkType=mysqli_fetch_assoc($select_checking_type);
    $Laboratory=$rowChkType['Laboratory'];
	$Radiology=$rowChkType['Radiology'];
	$Pharmacy=$rowChkType['Pharmacy'];	
	$Procedur=$rowChkType['Procedur'];
        //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
			
		 
			
			echo "<tr><td>".($empSN++)."</td>";
			echo "<td style='text-align:left'>".$employeeName."</td>";
			echo "<td style='text-align:left'>".$patient_name."</td>";
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a></td>";
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Laboratory>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Radiology>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Pharmacy>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'><a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".($Procedur>0?'Yes':'No')."</a></td>";
			
			echo "<td style='text-align:center'>".($finalDiagnosis)."</td>";
			echo "<td style='text-align:center'>".($Phone_Number)."</td></tr>";
			
			 //$avoidDoctorNameDuplicate=1;
		  }
	   }
	  }
	}
			    ?>    
			
<?php
}
?>
