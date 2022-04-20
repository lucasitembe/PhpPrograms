<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
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
	//run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
			
			//$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		  // SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today'
		//") or die(mysql_error);
		    
?>
<a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<br/><br/>

<fieldset style='overflow-y:scroll; height:500px'>
    <center>
	
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;">
		<br/>
    <table width='75%' border='0' id='actionsTable'>
        <tr>
		 <td style="text-align: center">
		   <select id='doctorID' onChange='selectDoctor(this.value)' style='padding:4px;width:100%'>
		     <option value="SELECT A DOCTOR">SELECT A DOCTOR</option>
			 <option value="All">All</option>
		     <?php
			  while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
				$employeeID=$select_doctor_row['Employee_ID'];
				$employeeName=$select_doctor_row['Employee_Name'];
				echo '<option value="'.$employeeID.'">';
				echo $employeeName;
				echo '</option>';
			 }
			 ?>
		   </select>
		 </td>
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required'>    
	    </td>
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>    
            <td style='text-align: center;'>
                <input type='button' name='Print_Filter' onclick='filterEmployeePatients()' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
   
</center>
    <br>
    <legend align=center><b>DOCTOR'S PERFORMANCE REPORT SUMMARY</b></legend>
       
		<div >
            <?php
		echo '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
		echo "<thead><tr>
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
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query)or die(mysqli_error($conn));
		    
		    $empSN=1;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
		  $avoidDoctorNameDuplicate=0;
		  
    //retrieve consultations for the employee		
    $consultations=mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,co.Registration_ID,co.employee_ID,co.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID FROM tbl_consultation co JOIN tbl_patient_registration pr ON pr.Registration_ID=co.Registration_ID JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE co.employee_ID='$employeeID' AND DATE(co.Consultation_Date_And_Time)='$today'") or die(mysqli_error($conn));
	 
     if(mysqli_num_rows( $consultations)>0){	
	    
	       //$empSN++;
		while($row=mysqli_fetch_array($consultations)){
			
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Patient_Name']."</a>";
			
			$employeeName="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$select_doctor_row['Employee_Name']."</a>";
			
			
			
			$Phone_Number="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >No</a>";
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today'
		") or die(mysql_error);
		
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		  $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Procedur
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
	  
	      $checkOtherConsultaion=mysqli_query($conn,"SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID JOIN tbl_consultation co ON co.consultation_ID=pc.consultation_id JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID  WHERE ilc.Consultant_ID='$employeeID' AND DATE(pc.Payment_Date_And_Time)='$today' GROUP BY pc.Registration_ID") or die(mysqli_error($conn));
		  
		  //ECHO "SELECT * FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID  WHERE ilc.Consultant_ID='$employeeID' AND DATE(pc.Payment_Date_And_Time)='$today' GROUP BY pc.Registration_ID";
		  $avoidDoctorNameDuplicate=0;
		
      if(mysqli_num_rows( $checkOtherConsultaion) >0){		
		  //$empSN ++;
		while($row=mysqli_fetch_array($checkOtherConsultaion)){
			
			$Registration_ID=$row['Registration_ID'];
			$patient_name="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Patient_Name']."</a>";
			
			$employeeName="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$select_doctor_row['Employee_Name']."</a>";
			
			
			
			$Phone_Number="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >".$row['Phone_Number']."</a>";
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >No</a>";
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today'
		") or die(mysql_error);
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND DATE(dc.Disease_Consultation_Date_And_Time) = '$today' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		   $finalDiagnosis="<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=".			$Registration_ID."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&PatientFile=PatientFileThisForm' >Yes</a>";
		}
			
			//select checking type
  $select_checking_type=mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  DATE(pc.Payment_Date_And_Time)='$today') as Procedur
	 ") or die(mysqli_error($conn));
	 
	$row2=mysqli_fetch_assoc($select_checking_type);
        $Laboratory=$row2['Laboratory'];
	$Radiology=$row2['Radiology'];
	$Pharmacy=$row2['Pharmacy'];	
	$Procedur=$row2['Procedur'];
       // if( $avoidDoctorNameDuplicate==1){$employeeName='';}
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
			</table>
			
			</center>
	  </div>
</fieldset>

<?php
    include("./includes/footer.php");
?>
	<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
	<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
	<script src="media/js/jquery.js"></script>
	<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>

<script>
 $('#doctorsperformancetbl').dataTable({
    "bJQueryUI":true,
	});
</script>
<script>
  function filterEmployeePatients(){
    //alert('hallow');
	var doctorID,date_From,date_To;
  
       doctorID=document.getElementById('doctorID').value;
	   date_From=document.getElementById('date_From').value;
	   date_To=document.getElementById('date_To').value;
	 //ajax requests
		   
		   var mypostrequest=new ajaxRequest();
			mypostrequest.onreadystatechange=function(){
			 if (mypostrequest.readyState==4){
			  if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1){
			   	document.getElementById("doctorsperformancetbl").innerHTML=mypostrequest.responseText;
				 
			  }
			  else{
			   alert("An error has occured making the request");
			  }
			 }
			}
	     	var parameters="filterDoctorsPatient=true&doctorID="+doctorID+"&date_From="+date_From+"&date_To="+date_To;
			mypostrequest.open("POST", "filterPerformanceDoctorPatient.php", true);
			mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			mypostrequest.send(parameters);
			//alert(parameters);
		
		
		//alert(Price +" "+Quantity+" "+Discount+" "+ppil);
  }
</script>
<script>
function ajaxRequest(){
 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  for (var i=0; i<activexmodes.length; i++){
   try{
    return new ActiveXObject(activexmodes[i]);
   }
   catch(e){
    //suppress error
   }
  }
 }
 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  return new XMLHttpRequest();
 else
  return false;
}
</script>