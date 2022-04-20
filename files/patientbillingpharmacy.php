<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./departmentalsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    $query_string="section=".$_GET['section']."&Registration_ID=".$_GET['Registration_ID']."&Transaction_Type=".$_GET['Transaction_Type']."&Payment_Cache_ID=".$_GET['Payment_Cache_ID']."&NR=".$_GET['NR']."&Billing_Type=".$_GET['Billing_Type']."&Sub_Department_ID=".$_GET['Sub_Department_ID']."&PharmacyWorks=".$_GET['PharmacyWorks']."";
    if(isset($_GET['Registration_ID'])){
        $_SESSION['Registration_ID']=$_GET['Registration_ID'];
    }else{
        $_SESSION['Registration_ID']='';
    }
    if(isset($_GET['Transaction_Type'])){
        $_SESSION['Transaction_Type']=$_GET['Transaction_Type'];
    }else{
        $_SESSION['Transaction_Type']='';
    }
    if(isset($_GET['Payment_Cache_ID'])){
        $_SESSION['Payment_Cache_ID']=$_GET['Payment_Cache_ID'];
    }else{
        $_SESSION['Payment_Cache_ID']='';
    }
    if(isset($_GET['Sub_Department_ID'])){
        $_SESSION['Sub_Department_ID']=$_GET['Sub_Department_ID'];
    }else{
        $_SESSION['Sub_Department_ID']='';
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

    
    
///remove for item dublication
 for($i=0;$i<4;$i++){
    $sql_select_dublicate_item_result=mysqli_query($conn,"SELECT MAX(Payment_Item_Cache_List_ID) AS Payment_Item_Cache_List_ID FROM `tbl_item_list_cache` WHERE `Payment_Cache_ID`='$Payment_Cache_ID' AND Status<>'removed' GROUP BY Item_ID,`Quantity` HAVING COUNT(Item_ID)>1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_dublicate_item_result)>0){
       while($dublicate_item_rows=mysqli_fetch_assoc($sql_select_dublicate_item_result)){
           $Payment_Item_Cache_List_ID_r=$dublicate_item_rows['Payment_Item_Cache_List_ID'];
           $sql_delete_dublicate_item_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET removing_status='yes',Status='removed' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID_r'") or die(mysqli_error($conn));
       } 
    }
 }
	
?>


<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <!-- <option></option> -->
    <option>
	OUTPATIENT CASH
    </option>
<!--    <option>-->
<!--	OUTPATIENT CREDIT-->
<!--    </option>-->
<!--     <option>
	INPATIENT CASH
    </option> -->
<!--    <option>-->
<!--	INPATIENT CREDIT-->
<!--    </option>-->
 <!--    <option>
	PATIENT FROM OUTSIDE
    </option> -->
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='revenuecenterpharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm' class='art-button-green'>
        BACK
    </a>
<?php } } ?>

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
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>


<script type='text/javascript'>
    function di(){
        alert("All");
		$( "#d").attr("hidden","false").dialog();
	}
    function b(val){
        alert(val);
    }
</script>
<div id='d' title='CATEGORIES' hidden='hidden'>
    <a href='#' id='s' onclick="b('s')">ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
</div>
<!-- not used-->
<!-- not used-->
<!-- end of popup window-->

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
        $select_Patient = mysqli_query($conn,"select pr.Phone_Number, pr.Registration_ID, pr.Old_Registration_Number, pr.Title, pr.Patient_Name, sp.Sponsor_ID, pr.Date_Of_Birth, pr.Member_Card_Expire_Date,
                                        pr.Gender, pr.Region, pr.District, pr.Ward, sp.Guarantor_Name, pr.Member_Number, pr.Email_Address, pr.Occupation, pr.Employee_Vote_Number,
                                        pr.Emergence_Contact_Name, pr.Emergence_Contact_Number, pr.Company, emp.Employee_ID, pr.Registration_Date_And_Time, pc.Billing_Type, emp.Employee_Name, pc.Folio_Number
                                        from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
                                        pc.Registration_ID = pr.Registration_ID and
                                        pc.Employee_ID = emp.Employee_ID and
                                        pc.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
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
		$Folio_Number = $row['Folio_Number'];
		
		
		if(strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit' ){
		    $Billing_Type = 'Outpatient '.$Transaction_Type;
		}elseif(strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit' ){
		    $Billing_Type = 'Inpatient '.$Transaction_Type;
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
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<br/>
<fieldset>  
            <legend align=right><b>PAYMENTS FROM OTHER DEPARTMENTS</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%'><b>Patient Name</b></td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%' style="text-align:right;">Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td style="text-align:right;">Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
				<td>
                                    <select name='Billing_Type' id='Billing_Type'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<td style="text-align:right;">Claim Form Number</td>
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
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' placeholder='Claim Form Number'></td>
				    <?php } else { ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
				    <?php } ?>
                                <td style="text-align:right;">Occupation</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				
				<td style="text-align:right;">Receipt Date & Time</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Type Of Check In</td>
                                <td>  
				    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
					<option selected='selected'>Pharmacy</option> 
				    </select>
				</td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registered Date</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				
				<td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' required='required'> 
					<option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
				<td style="text-align:right;">Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
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
				
				<td style="text-align:right;">Supervised By</td>
				
				<?php
				    if(isset($_SESSION['supervisor'])) {
					if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
					    if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
						$Supervisor = $_SESSION['supervisor']['Employee_Name'];
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
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>

<fieldset>   
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
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
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
			    //create sql
			    $Check_Status = "select Status, Transaction_Type from tbl_item_list_cache where
						Transaction_Type = '$Transaction_Type' and
						    Payment_Cache_ID = '$Payment_Cache_ID' and
							Sub_Department_ID = '$Sub_Department_ID' and
                                                            Check_In_Type='Pharmacy' and 
							    status = ";
			    $sqlSt = $Check_Status."'dispensed'";
			    //check for dispensed
			    $select_Status = mysqli_query($conn,$sqlSt); 
			    $no = mysqli_num_rows($select_Status);
			    if($no > 0){
				  $sqlSt = $Check_Status."'approved'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
								if($no > 0){
								  $Transaction_Status_Title = 'APPROVED';
								}else{
								   $sqlSt = $Check_Status."'paid'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
									if($no > 0){
									  $Transaction_Status_Title = 'PAID';
									}else{
									   $Transaction_Status_Title = 'DISPENCED';
									}
								   //$Transaction_Status_Title = 'DISPENCED';
								}
			    }else{
				//check for paid
				$sqlSt = $Check_Status."'paid'";
				$select_Status = mysqli_query($conn,$sqlSt);
				$no = mysqli_num_rows($select_Status);
				if($no > 0){
				    $sqlSt = $Check_Status."'approved'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
								if($no > 0){
                                                                    $sqlSt = $Check_Status."'paid'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
									if($no > 0){
									  $Transaction_Status_Title = 'PAID';
									}else{
									   $Transaction_Status_Title = 'APPROVED';
									}
								  //$Transaction_Status_Title = 'APPROVED';
								}else{
								   $Transaction_Status_Title = 'PAID';
								}
				}else{
				    //check for approved
				    $sqlSt = $Check_Status."'approved'";
				    $select_Status = mysqli_query($conn,$sqlSt);
				    $no = mysqli_num_rows($select_Status);
				    if($no >0){
					$Transaction_Status_Title = 'APPROVED';
				    }else{
					//check for active
					$sqlSt = $Check_Status."'active'";
					$select_Status = mysqli_query($conn,$sqlSt);
					$no = mysqli_num_rows($select_Status);
					if($no > 0){
					    $Transaction_Status_Title = 'NOT YET APPROVED';
					}
				    }
				}
			    }
			    
			    if(!isset($_GET['Payment_Cache_ID'])){
				$Transaction_Status_Title = 'NO PATIENT SELECTED';
			    }
			echo '<b>STATUS : '.$Transaction_Status_Title.'</b>'; 
                        
                        if($Transaction_Status_Title == 'APPROVED'){
                               /*  echo '<button class="art-button-green" type="button" style="float:left;" onclick="openItemDialog()">Add More Item(s)</button><img id="loader" style="float:left;display:none" src="images/22.gif"/>'; */
                            }
			?>
			
		   </td>
		   <td style='text-align: right;' width=30%>
		    <input type="button"  value="Payment Via GePG" class="art-button-green" onclick="open_gpg_dialog()">&nbsp;&nbsp;
			<?php
			    if($Transaction_Status_Title == 'APPROVED'){
                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                         ?>
                        <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                    <?php }  if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                        <input type='button' name='Make_Payment' id='Make_Payment' value='Make Payment' onclick='Make_Payment_Laboratory()' class='art-button-green'>&nbsp;&nbsp;
                        
<!--				echo "<a href='Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Temp_Billing_Type2."' class='art-button-green'>
					    Make Payment
					</a>";-->
                        <?php }//end of show payment button setup
			    }
			?>
			
			<?php 
			    if($Patient_Payment_ID != '' && $Transaction_Status_Title == 'PAID'){
				// echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
				// Print Receipt
			    // </a>";
				  echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Receipt' onclick='Print_Receipt_Payment()' class='art-button-green'>";
			    }
			?>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>


<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
			<!-- get Sub_Department_ID from the URL -->
			<?php if(isset($_GET['Sub_Department_ID'])) { $Sub_Department_ID = $_GET['Sub_Department_ID']; }else{ $Sub_Department_ID = 0; } ?>
			<!--<iframe src='Patient_Billing_Pharmacy_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>' width='100%' height=270px></iframe>-->
		        <div id="patientItemsList" style='height:200px;overflow-y:scroll;overflow-x:hidden'>
                            <center><b>List of Items </b></center>
                            <?php include "Patient_Billing_Pharmacy_Iframe.php";?>
                        </div>
                    </td>
		</tr> 
                <tr id="totalAmount">
                    <?php echo $dataAmount; ?>
                </tr>
	    </table>
        </center>
</fieldset>
</form>
<!--Dialog div-->
<div id="addTests" style="width:100%;overflow:hidden; display: none;" >
   
    <fieldset>
        <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
            <center>
                <table width = "100%" style="border:0 " border="1">
                <tr>
                    <td width="40%" style="text-align: center"><input type="text" name="search" id="search_medicene" placeholder="-----------------------------------------Search Item-------------------------------------------" onkeyup="searchMedicene(this.value)"></td><td width="50%" style="text-align: center"><button style="width:90%;font-size:20px; " name="submitadded" class="art-button-green" type="button" onclick="submitAddedItems()">Add Item(s)</button></td>
                </tr>   
                <tr>
                    <td width="40%" style="text-align: center"><b>Items</b></td><td width="50%" style="text-align: center"><b>Chosen Tests</b></td>
                </tr>
                <tr>
                <td width="40%">
                   <!--Show tests for the section--> 
                   <div id="items_to_choose" style="height:400px;">
                       <table id="loadDataFromItems">
                        </table>
                    </div>
                </td>
                <td width="50%">
                    <!--Display selected tests for the section--> 
                    <div id="displaySelectedTests"  style="height:400px;width:100% ">
                        <form id="addedItemForm" action="" method="post"> 
                        <table width="100%" id="getSelectedTests">
                            <tr>
                               <td style="width:35%" ><b>Description</b></td><td style="width:15%"><b>Price</b></td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </td>
                </tr>
            </table>
                   </center>
    </fieldset><br/>
    
</div>



<div id="ePayment_Window" style="width:50%;">
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

<div id="gpg_dialog">

</div>

<script>
        function open_gpg_dialog(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
      $.ajax({
          type:'POST',
          url:'ajax_open_gpg_dialog.php',
          data:{Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID,Sub_Department_ID:Sub_Department_ID,Transaction_Type:Transaction_Type,Patient_Name:Patient_Name,Check_In_Type:'Pharmacy'},
          success:function(data){
              //console.log(data);
            $("#gpg_dialog").html(data);  
            $("#gpg_dialog").dialog('open');  
          }
      });  
    }
   $(document).ready(function(){
      $("#gpg_dialog").dialog({ autoOpen: false, width:'55%',height:250, title:'Make Payment Via GePG',modal: true});
   });
   $(document).ready(function(){
      $("#ePayment_Window").dialog({ autoOpen: false, width:'55%',height:250, title:'Create ePayment Bill',modal: true});
   });
</script>

<script>
function Print_Receipt_Payment(){
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");

    var data = "<?php echo $Patient_Payment_ID; ?>"
    if(checkForMaximmumReceiptrinting(data) === 'true'){

      var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
      //winClose.close();
     //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');


      $.ajax({
                    type:"POST",
                    url:"update_receipt_count.php",
                    async:false,
                    data:{payment_id:data},
                    success:function(result){
                        console.log(result)
                    }
                })

}else{
        alert("You have exeded maximumu print count")
        return false;
    }
    
}


function checkForMaximmumReceiptrinting(theId){
    
    var theCount = '';
    $.ajax({
                    type:"POST",
                    url:"compare_receipt_count.php",
                    async:false,
                    data:{payment_id:theId},
                    success:function(result){
                        // alert(result)
                        theCount = result;
                        console.log(theCount)
                                                
                    }
                })

return theCount;
}

  
function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var registration_id = '<?php echo $Registration_ID; ?>';
        
        var itemzero = document.getElementById('Item_price_zero').value;
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
              if (itemzero != '' && itemzero != null){
                         
                      document.location = 'Patient_Billing_Pharmacy_Page.php?itemzero='+itemzero+'&Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Temp_Billing_Type2; ?>'+'&manual_offline=manual';
                    } else{
                       
                      document.location = 'Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Temp_Billing_Type2; ?>'+'&manual_offline=manual';
                 }

           }
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }
    
    
      function offline_transaction(amount_required,reg_id){
               
        var uri = '../epay/patientbillingpharmacyOfflinePayment.php';
        
             
           var itemzero = document.getElementById('Item_price_zero').value;
           var Transaction_Type='<?php echo $Transaction_Type; ?>';
           var Payment_Cache_ID='<?php echo $Payment_Cache_ID; ?>';
           var Sub_Department_ID='<?php echo $Sub_Department_ID; ?>';
           var Registration_ID='<?php echo $Registration_ID; ?>';
           var Billing_Type='<?php echo $Temp_Billing_Type2; ?>';
                    
               
                       
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,itemzero:itemzero,Transaction_Type:Transaction_Type,Payment_Cache_ID:Payment_Cache_ID,Sub_Department_ID:Sub_Department_ID,Billing_Type:Billing_Type},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
</script>
<script>
    function Make_Payment_Laboratory() {
        
         var itemzero = document.getElementById('Item_price_zero').value;
            if (itemzero != '' && itemzero != null){
            alert('You have item with zero price.Receipt for item with zero price will not be created.');
            exit;
            }
             var reg_id = '<?php echo $Registration_ID; ?>';
             var amount_required=document.getElementById("total_txt").value;
           offline_transaction(amount_required,reg_id) 
            
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
         var check=confirm("Are you sure you want to remove selected item");
    if(check){     
     $.ajax({
         type: 'POST',
         url: "change_items_info_pharmacy.php",
         data: "Payment_Item_Cache_List_ID="+item,
		 dataType:"json",
         success: function (data) {
		     // alert(data['data']);
              $('#patientItemsList').html(data['data']);
		$('#totalAmount').html(data['total_amount']);	
                //alert(data['data']);
             			  
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
         url: "change_items_info_pharmacy.php",
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
         url: "change_items_info_pharmacy.php",
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
         url: "change_items_info_pharmacy.php",
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



<script>
    function Pay_Via_Mobile_Function(Payment_Cache_ID){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php $Employee_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetDetails.open('GET','ePayment_Patient_Details_Departmental.php?Section=Pharmacy&Employee_ID='+Employee_ID+'&Registration_ID='+Registration_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObjectGetDetails.send();
    }
</script>


<script type="text/javascript">
    function Confirm_Create_ePayment_Bill(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectConfirm = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectConfirm.overrideMimeType('text/xml');
        }

        myObjectConfirm.onreadystatechange = function (){
            data2933 = myObjectConfirm.responseText;
            if (myObjectConfirm.readyState == 4) {
                var feedback = data2933;
                if(feedback == 'yes'){
                    Create_ePayment_Bill();
                }else if(feedback == 'not'){
                    alert("No Item Found!");
                }else{
                    alert("You are not allowed to create transaction whith zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........
        myObjectConfirm.open('GET','Confirm_ePayment_Patient_Details_Departmental.php?Section=Pharmacy&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObjectConfirm.send();
    }
</script>


<script type="text/javascript">
    function Create_ePayment_Bill(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
            alert("Process Fail! You can not prepare a bill with zero amount");
        }else{
            if(Payment_Mode != null && Payment_Mode != ''){
                if(Payment_Mode == 'Bank_Payment'){
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        document.location = 'Departmental_Bank_Payment_Transaction.php?Section=Pharmacy&Registration_ID='+Registration_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Billing_Type='+Billing_Type;
                    }
                }else if(Payment_Mode == 'Mobile_Payemnt'){
                    var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                }
            }else{
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>


<script type="text/javascript">
    function Print_Payment_Code(Payment_Code){
        var winClose=popupwindow('paymentcodepreview.php?Payment_Code='+Payment_Code+'&PaymentCodePreview=PaymentCodePreviewThisPage', 'PAYMENT CODE', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
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
        function pharmacyQuantityUpdate(Payment_Item_Cache_List_ID,Quantity) {
	     if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    } 
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','pharmacyQuantityUpdate.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Quantity='+Quantity,true);
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
        
    });
            
       
        
    </script>



<?php
    include("./includes/footer.php");
?>