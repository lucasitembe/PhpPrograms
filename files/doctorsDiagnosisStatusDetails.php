<style>
    .linkstyle{
        color:#3EB1D3;
    }
    
    .linkstyle:hover{
        cursor:pointer;
    }
</style>
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
    
    $Date_From='';//@$_POST['Date_From'];
    $Date_To='';//@$_POST['Date_To'];
    $Employee_ID='';
        
        if(!isset($_GET['Date_From'])){
             $Date_From= date('Y-m-d H:m');
        }else{
            $Date_From=$_GET['Date_From']; 
        }
        if(!isset($_GET['Date_To'])){
             $Date_To=  date('Y-m-d H:m');;
        }else{
            $Date_To=$_GET['Date_To'];
        }if(!isset($_GET['Employee_ID'])){
            $Employee_ID=  0;
        }else{
            $Employee_ID=$_GET['Employee_ID'];
        }
	
	$today=date('Y-m-d');
	//run the query to select all data from the database according to the branch id
		  //  $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    //$select_doctor_result = mysqli_query($conn,$select_doctor_query);
			
			//$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		  // SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID'
		//") or die(mysql_error);
    
    $employeeID=$employee_ID=$Employee_ID;//exit;
    $EmployeeName= strtoupper(mysqli_fetch_assoc( mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);
    
     
		    
?>
<a target="_blank" href='./printdoctorsdiagnosis.php?Employee_ID=<?php echo $employee_ID ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>' class='art-button-green'>
        PRINT
</a>
<?php

echo "<a href='./doctorsDiagnosisStatusFilter.php?DoctorsPerformanceReportThisPage=ThisPage&Date_From=".$_GET['Date_From']."&Date_To=".$_GET['Date_To']."' class='art-button-green'>
        BACK
    </a>
 <br/><br/>";

 
?>
<fieldset style='overflow-y:scroll; height:500px'>
    <center>
	
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;">
		<br/>
                <form action='doctorsDiagnosisStatusFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>
	
    <table width='75%' border='0' id='actionsTable'>
        <tr>
		
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required'>    
	    </td>
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>    
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter'  class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
   </form>
</center>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>THE FOLLOWING PATIENT WERE NOT GIVEN FINAL DIAGNOSIS BY <?php echo $EmployeeName;?></b><b style="color:#e0d8d8;"> <?php echo date('j F, Y H:i:s',strtotime($Date_From))?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s' ,strtotime($Date_To))?></b></legend>
       
		<div >
            <?php
		echo '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
		echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			   <!-- <th style='text-align:left'>DOCTOR'S NAME</th>-->
				 <th style='text-align:left'>PATIENT NAME</th>
				 <th style='text-align:left'>CONSULT</th>
				  <th style='text-align:left'>LAB</th> 
				  <th style='text-align:left'>RADIOLOGY</th>
				  <th style='text-align:left'>PHARMACY</th>
                                  <th style='text-align:left'>PROCEDURE</th>
                                  <th style='text-align:left'>FINAL DIAGNOSIS</th>
                                  <th style='text-align:left'>PATIENT PHONE</th>
			  </tr></thead>";
		   
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
	
   if(count($consultArrray)>0){                    
    $consultations= mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch INNER JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE c.consultation_ID IN (".  implode(',', $consultArrray).")") or die(mysqli_error($conn));
	
	
           
    //echo "SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID'";
    $empSN=1; 
          
     if(mysqli_num_rows( $consultations)>0){	
	    
	      
		while($row=mysqli_fetch_array($consultations)){
			
			$Registration_ID=$row['Registration_ID'];
			$patient_name=$row['Patient_Name'];
			
			$employeeName=$row['Employee_Name'];
			
			$Employee_Name_Cur=$row['Employee_Name'];
			
			$Phone_Number=$row['Phone_Number'];
			
			$consultation_ID=$row['consultation_ID'];
			
		    $finalDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";
                    
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'
		") or die(mysql_error);
                
               
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		  $finalDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span>";
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
        $hrefpatientfile='Patientfile_Record_Detail.php?consultation_ID='.$consultation_ID.'&Registration_ID='.$Registration_ID.'&Section=ManagementPatient&Employee_ID='.$employeeID.'&Date_From='.$_GET['Date_From'].'&Date_To='.$_GET['Date_To'].'&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage&PatientFile=PatientFileThisForm';
       //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
			echo "<tr><td>".($empSN++)."</td>";
			//echo "<td style='text-align:left'>".$employeeName."</td>";
			echo "<td style='text-align:left'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".$patient_name."</span></td>";
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span></td>";
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Laboratory>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Radiology>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Pharmacy>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Procedur>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'>".($finalDiagnosis)."</td>";
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Phone_Number)."</span></td></tr>";
			
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
			$patient_name=$row['Patient_Name'];
			
			$employeeName=$Employee_Name_Cur;
			
			$hrefpatientfile='Patientfile_Record_Detail.php?consultation_ID='.$consultation_ID.'&Registration_ID='.$Registration_ID.'&Section=ManagementPatient&Employee_ID='.$employeeID.'&Date_From='.$_GET['Date_From'].'&Date_To='.$_GET['Date_To'].'&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage&PatientFile=PatientFileThisForm';
       
			
			$Phone_Number=$row['Phone_Number'];
			
			$consultation_ID=$row['consultation_ID'];
			
		     $finalDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";
                    $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";
                    
		$checkIfHasFinalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'
		") or die(mysql_error);
                
                $checkIfHasprovisionalDiagnosis=mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID' AND dc.diagnosis_type='provisional_diagnosis'
		") or die(mysql_error);
		
		
		//echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'";
		
		if(mysqli_num_rows($checkIfHasFinalDiagnosis)>0){ 
		  $finalDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span>";
		}
                
                if(mysqli_num_rows($checkIfHasprovisionalDiagnosis)>0){ 
		  $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span>";
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
			echo "<tr><td>".($empSN++)."</td>";
			//echo "<td style='text-align:left'>".$employeeName."</td>";
			echo "<td style='text-align:left'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle'".$patient_name."</span></td>";
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span></td>";
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Laboratory>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Radiology>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Pharmacy>0?'Yes':'No')."</span></td>";
			
			echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Procedur>0?'Yes':'No')."</span></td>";
			
			
			echo "<td style='text-align:center'>".($finalDiagnosis)."</td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >".($Phone_Number)."</span></td></tr>";
			
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
<script>
    function Show_Patient_File(Registration_ID) {
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID='+Registration_ID+'&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');
      
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>