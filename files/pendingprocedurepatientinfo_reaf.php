<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    $query_string="section=".$_GET['section']."&Registration_ID=".$_GET['Registration_ID']."&Transaction_Type=".$_GET['Transaction_Type']."&Payment_Cache_ID=".$_GET['Payment_Cache_ID']."&NR=".$_GET['NR']."&Billing_Type=".$_GET['Billing_Type']."&Sub_Department_ID=".$_GET['Sub_Department_ID']."&ProcedureWorks=".$_GET['ProcedureWorks']."";
    
    $_SESSION['Transaction_Type']=$_GET['Transaction_Type'];
    $_SESSION['Payment_Cache_ID']=$_GET['Payment_Cache_ID'];
    $_SESSION['Sub_Department_ID']=$_GET['Sub_Department_ID'];
    
        
        $Registration_ID=$_GET['Registration_ID'];
        $billing_type=$_GET['Billing_Type'];
        $transaction_type=strtolower($_GET['Transaction_Type']);
        $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
        $Sub_Department_ID=$_GET['Sub_Department_ID'];
        $typeconsultant=$_GET['typeconsultant'];
		
		$locationURL='procedurepatientinfo.php?section=Procedure&typeconsultant='.$typeconsultant.'&Registration_ID='.$Registration_ID.'&Transaction_Type='.$transaction_type.'&Payment_Cache_ID='.$Payment_Cache_ID.'&NR=True&Billing_Type='.$billing_type.'&Sub_Department_ID='.$Sub_Department_ID.'&statusMsg='.$_GET['statusMsg'].'&ProcedureWorks=ProcedureWorksThisPage';
       
	   $_SESSION['REDIRECT_TO_PROCEDURE']=$locationURL;
//        $billing_type='Outpatient Cash';
//        $status="paid";
        
   
   //echo $query_string;
    
   //exit;
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
   
?>




<?php
    if(isset($_GET['Transaction_Type'])){
	$Transaction_Type = $_GET['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    if(isset($_GET['Payment_Cache_ID'])){
	$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }
    if(isset($_GET['Billing_Type'])){
	$Temp_Billing_Type2 = $_GET['Billing_Type'];
    }else{
	$Temp_Billing_Type2 = '';
    }
?>



<?php
    //if(isset($_SESSION['userinfo'])){
    //    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <!--<a href='#' class='art-button-green'>-->
    <!--    VIEW - EDIT-->
    <!--</a>-->
<?php //} } ?>


<?php
    //if(isset($_SESSION['userinfo'])){
    //    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <!--<a href='#' class='art-button-green'>-->
    <!--    VIEW MY DATA-->
    <!--</a>-->
<?php //} } ?>
<?php
   $doctorproce='';
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['section']) &&  $_GET['section']== 'doctor'){
           echo "<a href='clinicalnotes.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>BACK</a>";
	}elseif(isset($_GET['section']) &&  $_GET['section']== 'Doctorlist'){
           echo "<a href='doctorprocedurelist.php' class='art-button-green'>BACK</a>";
           
             $doctorproce=" and ilc.Procedure_Location='Me' and
                                ilc.Consultant_ID='".$_SESSION['userinfo']['Employee_ID']."'";
	}else{
              $doctorproce=" and ilc.Procedure_Location <> 'Me'";
?>
    <a href='procedurelist.php' class='art-button-green'>
        BACK
    </a>
<?php }
} ?>

<!-- old date function -->
<?php
    /*$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }*/
?>
<!-- end of old date function -->


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->


<!--popup window-->
<!-- not used-->
<!-- not used-->
<!-- not used-->

<script type='text/javascript'>
    function di(){
        alert("All");
		$( "#d").attr("hidden","false").dialog();
	}
    function b(val){
        alert(val);
    }
</script>





<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Payment_Cache_ID'])){ 
         $Payment_Cache_ID = $_GET['Payment_Cache_ID']; 
       
        if(isset($_GET['typeconsultant']) && $_GET['typeconsultant']=='OTHERS_CONSULT'){
             $selectPatQry="select * from tbl_patient_payments pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Patient_Payment_ID = '$Payment_Cache_ID'";
       
             //echo $selectPatQry;exit;
             
        }else if(isset($_GET['typeconsultant'])&& $_GET['typeconsultant']=='DOCTOR_CONSULT'){
             $selectPatQry="select * from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Payment_Cache_ID = '$Payment_Cache_ID'";
        }
       
        $select_Patient = mysqli_query($conn,$selectPatQry) or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
       // echo $no;exit;
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
		        $Temp_Billing_Type = $row['Billing_Type'];
		        $Consultant = $row['Employee_Name'];
                $Folio_Number ='';
                if(isset($_GET['typeconsultant']) && $_GET['typeconsultant']=='OTHERS_CONSULT'){
                    $Folio_Number ='';
                }  else {
                    $Folio_Number = $row['Folio_Number'];
                }
        
		
		
		
		if(strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit' ){
                   if($Transaction_Type=='processAnywhere'){
                     $Billing_Type = $Temp_Billing_Type;  
                   }else{
                     $Billing_Type = 'Outpatient '.$Transaction_Type;   
                   } 
		    
		}elseif(strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit' ){
		    //$Billing_Type = 'Inpatient '.$Transaction_Type;
                    if($Transaction_Type=='processAnywhere'){
                     $Billing_Type = $Temp_Billing_Type;  
                   }else{
                     $Billing_Type = 'Inpatient '.$Transaction_Type;   
                   } 
		}
		
		
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            
	     $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
	    /*}
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }*/
	   
	    
	    
	    
	    
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = ''; 
	    $Consultant = '';
	    $Folio_Number = '';
	    $Billing_Type = '';
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $Consultant = '';
	    $Folio_Number = '';
	    $Billing_Type = '';
        }
?>

<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>


<?php 
$issPorcessed=FALSE;
 if(isset($_POST['process_procedure'])){
    //echo 'Sent';exit;
     $paymentItermCache=$_POST['paymentItermCache'];
     
     $q=  mysqli_query($conn,"SELECT NOW() as ServedDateTIme") or die(mysqli_error($conn));
     $rowq=  mysqli_fetch_assoc($q);
     $servedDateTime=$rowq['ServedDateTIme'];
	 
      
     //echo $servedDateTime;exit;
    if($_POST['typeconsultant']=='OTHERS_CONSULT'){
         foreach ($paymentItermCache as $value) {
		  $status=$_POST['status_'.$value];
			
			if($status !=='Select progress'){
			  if($status =='Done'){
			     $status="served";
			  
            $setUpdates="UPDATE tbl_patient_payment_item_list SET Status='$status',remarks='".$_POST['remarks_'.$value]."', ServedDateTime='$servedDateTime',ServedBy='".$_SESSION['userinfo']['Employee_ID']."'  WHERE Patient_Payment_Item_List_ID='".$value."'";
            $updateQuery=mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
           // echo $_POST['remarks_'.$value].'<br/>';
		     }//End of status=Done
			  else{
				mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Status='$status',remarks='".$_POST['remarks_'.$value]."', ServedDateTime='$servedDateTime',ServedBy='".$_SESSION['userinfo']['Employee_ID']."'  WHERE Patient_Payment_Item_List_ID='".$value."'") or die(mysqli_error($conn));
			  }
		   }
        }
		 echo "<script type='text/javascript'>
                                alert('INFORMATION SAVED SUCCESSFULLY');
                            </script>";
        
        $issPorcessed=TRUE;
    } else {
        
         if(($billing_type=='Outpatient Cash' && $transaction_type=="cash") || ($billing_type=='Outpatient Credit' && $transaction_type=="cash")){
            //Just update the status in Iterm_list_cache
            //$setUpdates="UPDATE tbl_item_list_cache SET Status='served' WHERE Payment_Item_Cache_List_ID='".$value."'";
            //$updateQuery=mysqli_query($conn,$setUpdates);

        foreach ($paymentItermCache as $value) {
		    $status=$_POST['status_'.$value];
			
			if($status !=='Select progress'){
			  if($status =='Done'){
			     $status="served";
			  
			
            $setUpdates="UPDATE tbl_item_list_cache SET Status='$status',remarks='".$_POST['remarks_'.$value]."',ServedDateTime=NOW(),ServedBy='".$_SESSION['userinfo']['Employee_ID']."'  WHERE Payment_Item_Cache_List_ID='".$value."'";
            $updateQuery=mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
             }//End of status=Done
					  else{
					    mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='".$_POST['remarks_'.$value]."',ServedDateTime=NOW(),ServedBy='".$_SESSION['userinfo']['Employee_ID']."' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
					  }
           // echo $_POST['remarks_'.$value].'<br/>';
             }
	    }
           
           $issPorcessed=TRUE;
        
        }elseif($billing_type=='Inpatient Cash' || $billing_type=='Inpatient Credit' || ($billing_type=='Outpatient Credit' && $transaction_type=="credit")){
                  //Bill the time 
            $_SESSION['Procedure_Supervisor']['Employee_ID'];
            
            //get IDs for item list cache
            
            foreach ($paymentItermCache as $value) {
               $status=$_POST['status_'.$value];
			   
			   if($status !='Select progress'){
			      if($status =='Done'){
			       $status="served";
			        //echo "Done Processed";
					
					//exit;
           // $payment_status="Bill";
		   
		     
            $checkPAymentStatus="SELECT * FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE Payment_Item_Cache_List_ID='".$value."'";
                    $paymentQuery=  mysqli_query($conn,$checkPAymentStatus);
                    $rows=  mysqli_fetch_array($paymentQuery);
                
                    if(($rows['Billing_Type']=='Outpatient Credit') && ($rows['Transaction_Type']=='Credit')){
                         $Billing_Type = 'Outpatient Credit'; 
                    }else if(($rows['Billing_Type']=='Inpatient Credit') && ($rows['Transaction_Type']=='Cash')){
                        $Billing_Type = 'Inpatient Cash';                          
                    }else if(($rows['Billing_Type']=='Inpatient Credit') && ($rows['Transaction_Type']=='Credit')) {
                        $Billing_Type = 'Inpatient Credit';
                    }else if(($rows['Billing_Type']=='Inpatient Cash') && ($rows['Transaction_Type']=='Cash')){
                        $Billing_Type = 'Inpatient Cash';
                    }
                     
                      //get claim last form number
                      $select = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where Registration_ID = '".$rows['Registration_ID']."' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                      $result6 = mysql_fetch_row($select);
                      $num = mysqli_num_rows($select);
                      if($num > 0){
                          $Claim_Form_Number = $result6[0];
                      }else{
                          $Claim_Form_Number = '';
                      }
                      
                      
                      //get last check in id
                    
                      $select7 = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '".$rows['Registration_ID']."' order by Registration_ID desc limit 1") or die(mysqli_error($conn));
                      
                      $result7 = mysql_fetch_row($select7);
                      $num7 = mysqli_num_rows($select7);
                      if($num7 > 0){
                          $Check_In_ID = $result7[0];
                      }else{
                          $Check_In_ID = '';
                      }
                      
                      //get supervisor id
                      if(isset($_SESSION['Procedure_Supervisor'])){
                          $Supervisor_ID = $_SESSION['Procedure_Supervisor']['Employee_ID'];                          
                      }else{
                           $Supervisor_ID = 0;
                      }
                      
                      //get branch id
                      if(isset($_SESSION['userinfo'])){
                          $Branch_ID = $_SESSION['userinfo']['Branch_ID'];                          
                      }else{
                           $Branch_ID = 0;
                      }
                      
                      //get data from tbl_payment cache
                      $Registration_ID = $rows['Registration_ID'];
                      $Folio_Number = $rows['Folio_Number'];
                      $Sponsor_ID = $rows['Sponsor_ID'];
                      $Sponsor_Name = $rows['Sponsor_Name'];
                      
                       //echo $Sponsor_ID.' Nikoo hapa';
                      //insert data to tbl_patient_payments
					  $sql=" insert into     tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',(select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID')";
											//echo $sql;
                      $insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                     
                      if($insert){
                          
                           //get the last patient_payment_id & date
                          $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                          $num_row = mysqli_num_rows($select_details);
                          if($num_row > 0){
                              $details_data = mysql_fetch_row($select_details);
                              $Patient_Payment_ID = $details_data[0];
                              $Receipt_Date = $details_data[1];
                          }else{
                              $Patient_Payment_ID = 0;
                              $Receipt_Date = '';
                          }
                          
                          //get data from tbl_item_list_cache
                          $Item_ID = $rows['Item_ID'];
                          $Discount = $rows['Discount'];
                          $Price = $rows['Price'];
                          $Quantity = $rows['Quantity'];
                          $Consultant = $rows['Consultant'];
                          $Consultant_ID = $rows['Consultant_ID'];
                          
                          
                          //insert data to tbl_patient_payment_item_list
                          if($Patient_Payment_ID != 0 && $Receipt_Date != ''){
                              $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,remarks,ServedDateTime,ServedBy,ItemOrigin)
                                                        values('Procedure','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','$status','$Patient_Payment_ID',(select now()),'".$_POST['remarks_'.$value]."','$servedDateTime','".$_SESSION['userinfo']['Employee_ID']."','Doctor')") or die(mysqli_error($conn));
                            if($insert){
                                mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='".$_POST['remarks_'.$value]."',Patient_Payment_ID = '$Patient_Payment_ID', Payment_Date_And_Time = '$Receipt_Date' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
                            }
							}
                          }
                      }//End of status=Done
					  else{
					   //echo "$status Processed";
					
					    mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='".$_POST['remarks_'.$value]."' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
					  }
				  }
                }
                 
            }
			
			echo "<script type='text/javascript'>
                                alert('INFORMATION SAVED SUCCESSFULLY');
                            </script>";
                $issPorcessed=TRUE;

        }
    } 
     
  

?>



<!-- get receipt number and receipt date-->
    <?php
	if(isset($_GET['Patient_Payment_ID'])){
	    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
	    $Patient_Payment_ID = '';
	}
	if(isset($_GET['Payment_Date_And_Time'])){
	    $Payment_Date_And_Time = $_GET['Payment_Date_And_Time'];
	}else{
	    $Payment_Date_And_Time = '';
	}
    
    ?>
<!-- end of getting receipt number and receipt date-->


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='Patient_Billing_Laboratory_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<br/>

<fieldset style="background-color:#EEEEEE">  
            <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PROCEDURE PROCESSING</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style="text-align:right;">Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%' style="text-align:right;">Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                           
			   </tr> 
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
				<td>
                                    <select name='Billing_Type' id='Billing_Type'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<td style="text-align:right;" >Claim Form Number</td>
                                <!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
				<?php
					$select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
					$no = mysqli_num_rows($select_claim_status);
					if($no > 0){
					    while($row = mysqli_fetch_array($select_claim_status)){
						$Claim_Number_Status = $row['Claim_Number_Status'];
					    }
					}else{
					    $Claim_Number_Status = '';
					}
				    ?>
				    <?php if(strtolower($Claim_Number_Status) == 'mandatory'){ ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'  placeholder='Claim Form Number'></td>
				    <?php } else { ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
				    <?php } ?>
                                <td style="text-align:right;">Occupation</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
	                 	<td style="text-align:right;">Doctor Ordered</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                          
				
                            </tr>
                            <tr>
                                <td style="text-align:right;">Type Of Check In</td>
                                <td>  
				    <select name='Type_Of_Check_In' id='Type_Of_Check_In'  onchange='examType()' onclick='examType()'> 
					<option selected='selected'>Laboratory</option> 
				    </select>
				</td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registered Date</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				<td style="text-align:right;">Supervised By</td>
				
				<?php
				    if(isset( $_SESSION['Procedure_Supervisor']['Employee_Name'])) {
					if(isset( $_SESSION['Procedure_Supervisor']['Employee_Name'])){
					    if( $_SESSION['Procedure_Supervisor']['Employee_Name'] != ''){
						$Supervisor =  $_SESSION['Procedure_Supervisor']['Employee_Name'];
					    }else{
						$Supervisor = "Unknown Supervisor";
					    }
					}else{
						$Supervisor = "Unknown Supervisor";
					}
				    }else{
						$Supervisor = "Unknown Supervisor";
				    }
				?> 
                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                      
				 </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' > 
					<option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
			  </tr>
                            <tr>
                                <td style="text-align:right;">Consultant</td>
                                <td>
				    <select name='Consultant' id='Consultant'>
					<option selected='selected'><?php echo $Consultant; ?></option>
				    </select>
				</td>
                                <td style="text-align:right;">Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                                <td style="text-align:right;">Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
				
				      </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>

<fieldset style="background-color:#EEEEEE">   
        <center>
            <table width=100%>
		<tr>
		   <td style='text-align: center;'>
			<?php
			    if(isset($_GET['Payment_Cache_ID'])){
				$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
			    }else{
				$Payment_Cache_ID = '';
			    }
			   
			   /*
			    if(isset($_SESSION['Laboratory'])){
				$Sub_Department_Name = $_SESSION['Laboratory'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    */
			    if(isset($_GET['Sub_Department_ID'])){
				$Sub_Department_ID = $_GET['Sub_Department_ID']; 
			    }else{
				$Sub_Department_ID = 0;
			    }
			   
			    
			    $Transaction_Status_Title = ''; 
			   
                            
                            
                             if(isset($_GET['statusMsg'])){
				$Transaction_Status_Title = $_GET['statusMsg'];
                               
                                   $statusdisp= '<b>STATUS : <span style="color:blue">'.$Transaction_Status_Title.'</span></b>'; 
                               
			     }
			    
			    if(!isset($_GET['Payment_Cache_ID'])){
				$Transaction_Status_Title = 'NO PATIENT SELECTED';
                                $statusdisp= '<b>STATUS : <span style="color:red">'.$Transaction_Status_Title.'</span></b>'; 
			    }
                            
                            if(isset($issPorcessed) && $issPorcessed==TRUE){
                                $Transaction_Status_Title="Saved";
                                $statusdisp= '<b>STATUS : <span style="color:green">'.$Transaction_Status_Title.'</span></b>'; 
                            }
                            
                           $_SESSION['Transaction_Status_Title']=$Transaction_Status_Title;
			  
                            echo $statusdisp;
                        
                        
			?>
			
                   </td>
		   <td style='text-align: right;' width=30%>
                      <?php
                        if(isset($_GET['section']) &&  ($_GET['section']== 'doctor' || $_GET['section']== 'Doctorlist')){
                     
                        }else{
                             ?> 
		         <a href="Patientfile_Record_Detail.php?redirect=true&Registration_ID=<?php echo $Registration_ID; ?>&PatientFile=PatientFileThisForm">
			 <button type="button" class="art-button-green" name="process_procedure" id="<?php echo $Registration_ID; ?>" >Patient File</button>
			 </a>
                       <?php
                        }
                       if($Transaction_Status_Title!="PATIENT SUCCESSFULL PROCESSED!"){ 
                           
                         echo '<input type="hidden" class="submitData" name="typeconsultant" value="'.$typeconsultant.'"/><button type="submit" class="submitData art-button" name="process_procedure" onclick="return confirm(\'Are you sure you want to save info?\');" >Process Patient</button>
                       ';     
                           
                       }
                      ?>
                         
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>


<fieldset style="background-color:#EEEEEE">   
        <center>
            <table width=100%>
		<tr>
		    <td>
                     <!-- get Sub_Department_ID from the URL -->
			<?php if(isset($_GET['Sub_Department_ID'])) { $Sub_Department_ID = $_GET['Sub_Department_ID']; }else{ $Sub_Department_ID = 0; } ?>
                        <div id="patientItemsList" style='height:200px;overflow-x:hidden;overflow-y:scroll;  '>
                            <center><b>LIST OF ITEMS </b></center>
                            <?php include "./pendingprocedure_iframe.php";?>
                        </div>
<!--			<iframe src='Patient_Billing_Laboratory_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>' width='100%' height=270px></iframe>-->
		    </td>
		</tr>
                
           </table>
        </center>
</fieldset>
</form>

<script>
    function Make_Payment_Laboratory() {
        //Save discount before print
       // var datastring=$("form#saveDiscount").serialize();
        
        //end saving
        
        
        var Confirm_Message = confirm("Are you sure you want to perform transaction?");
        if (Confirm_Message == true) {
            document.location = 'Patient_Billing_Laboratory_Page.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>';
        }
    }
    
     function openItemDialog(){
     //Load data to the div
      $("#loader").show();
       $('#getSelectedTests').html('');
       $.ajax({
         type: 'GET',
         url: "search_item_for_test.php",
         data: "loadData=true&section=<?php echo $_GET['section']?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>",
	   success: function (data) {
		     // alert(data['data']);
              $('#loadDataFromItems').html(data);
          },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
      });
      $("#addTests").dialog("open");
               
     }
 
 function removeitem(item){
         // alert(item);
         var check=confirm("Are you sure you want to remove this quantity");
    if(check){     
     $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "Payment_Item_Cache_List_ID="+item,
		 dataType:"json",
         success: function (data) {
		     // alert(data['data']);
              $('#patientItemsList').html(data['data']);
				$('#totalAmount').html(data['total_amount']);			  
             			  
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
    }
 }
 
 function vieweRemovedItem(){
         // alert(item);
         
     $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "viewRemovedItem=true",
         dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);	          
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
 function addItem(item){
      $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "readdItem="+item,
          dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);         
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
 function showItem(){
      $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "show_all_items=true",
         dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);    
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
  function submitAddedItems(){
     
     var datastring=$("form#addedItemForm").serialize();
     
   if(datastring!==''){     
     $.ajax({
         type: 'POST',
         url: "search_item_for_test.php",
         data: "addMoreItems=true&"+datastring+'&section=<?php echo $_GET['section']?>',
         success: function (data) {
		// alert(data);
             if(data=='saved'){
                showItem();
                $("#addTests").dialog("close");
             }//alert(data);
//              $('#patientItemsList').html(data);          
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
   }else{
       alert("No data set");
 }
  $("#loader").hide();
 }
function searchMedicene(search){
   if(search !==''){ 
    $.ajax({
         type: 'GET',
         url: "search_item_for_test.php",
         data: "section=<?php echo $_GET['section']?>&search_word="+search,
         success: function (data) {
            if(data !==''){
              $('#items_to_choose').html(data);   
             }
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
     }
}


</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
#displaySelectedTests,#items_to_choose{
    overflow-y:scroll;
    overflow-x:hidden; 
}
</style>

    <script type='text/javascript'>
        function LaboratoryQuantityUpdate(Payment_Item_Cache_List_ID,Quantity) {
	     if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    } 
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','LaboratoryQuantityUpdate.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Quantity='+Quantity,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
            }
        }
        
         $(document).ready(function(){
            $("#addTests").dialog({ autoOpen: false, width:900,height:560, title:'Choose an Item',modal: true});
//       $(".ui-widget-header").css("background-color","blue");  
        
        $(".chosenTests").live("click",function(){
            //alert("chosen");
             var id=$(this).attr("id");
            if($(this).is(':checked')){
              
              
               $.ajax({
                    type: 'GET',
                    url: "search_item_for_test.php",
                    data: "section=<?php echo $_GET['section']?>&adthisItem="+id,
                    success: function (data) {
                        if(data !==''){
                         $('#getSelectedTests').append(data); 
                        }
                    },error: function (jqXHR, textStatus, errorThrown) {
                      alert(errorThrown);               
                    }
                });
              
            }else{
                $("#itm_id_"+id).remove();
            }
        });
        
         $(".ui-icon-closethick").click(function(){
//         $(this).hide();
            $("#loader").hide();
        });
        
        <?php
          if($totalItem==$totalDone){
               ?>
                              $('.submitData').remove();   
                     // alert('alert if');
               //document.getElementsByClassName('submitData').remove();       
              <?php        
          }
        ?>
        
    });
    </script>
<?php
    include("./includes/footer.php");
    
    
?>