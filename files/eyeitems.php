<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['Eye_Supervisor'])){ 
		    header("Location: ./deptsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
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
?>


<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Outpatient cash'){
	    document.location = "./eyecashpatientlist.php";
	}else if (patientlist=='Outpatient credit') {
	    document.location = "#";
	}else if (patientlist=='Inpatient cash') {
	    document.location = "#";
	}else if (patientlist=='Inpatient credit') {
	    document.location = "#";
	}else if (patientlist=='Patient from outside') {
	    document.location = "#";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option>Chagua Mgonjwa</option>
    <option>
	Outpatient cash
    </option>
    <option>
	Outpatient credit
    </option>
    <option>
	Inpatient cash
    </option>
    <option>
	Inpatient credit
    </option>
    <option>
	Patient from outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='#' class='art-button-green'>
        VIEW - EDIT
    </a>
<?php } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='#' class='art-button-green'>
        VIEW MY DATA
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


 

<!--Approved message-->
    <script type='text/javascript'>
	function approved_Message(){
	    alert('    Successfully Approved! Please notify PATIENT to go to CASHIER for payment and then return to PHARMACY to pick up their medication   ');   
	}
	
	function approved_Message2(){
	    alert('    The Bill is already APPROVED! if not yet, please notify PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');   
	}
	
	function Payment_approved_Message(){
	    alert(' Patient\'s medication is not yet paid for. Please advice PATIENT to go to CASHIER\n for payment then return for service ');
	}
    </script>
<!-- end of approved message-->




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
	$qr = "select * from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pr.Sponsor_ID = sp.Sponsor_ID and
								    pc.Payment_Cache_ID = '$Payment_Cache_ID'";
        $select_Patient = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	//echo $qr;
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




<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>

<!-- get receipt number and date -->
<?php
    if(isset($_GET['Payment_Cache_ID'])){
	$Get_Receipt = mysqli_query($conn,"
		select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'dispensed' and 
		    Payment_Cache_ID = '$Payment_Cache_ID' and
			Transaction_Type = '$Transaction_Type' group by Patient_Payment_ID limit 1");
	$no_of_rows = mysqli_num_rows($Get_Receipt);
	if($no_of_rows > 0){
	    while($row = mysqli_fetch_array($Get_Receipt)){
		$Patient_Payment_ID = $row['Patient_Payment_ID'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
	    }
	}else{
	    $Get_Receipt = mysqli_query($conn,"
		select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'paid' and 
		    Payment_Cache_ID = '$Payment_Cache_ID' and
			Transaction_Type = '$Transaction_Type' group by Patient_Payment_ID limit 1");
	    $no_of_rows = mysqli_num_rows($Get_Receipt);
	    if($no_of_rows > 0){
		while($row = mysqli_fetch_array($Get_Receipt)){
		    $Patient_Payment_ID = $row['Patient_Payment_ID'];
		    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		}
	    }else{
		$Patient_Payment_ID = '';
		$Payment_Date_And_Time = '';
	    }
	}
    }else{
	$Patient_Payment_ID = '';
	$Payment_Date_And_Time = '';
    } 
?>
<!-- end of process (getting receipt number)-->




<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b><?php if(isset($_SESSION['Eye'])){ echo $_SESSION['Eye']; } ?></b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style='text-align: right'>Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%' style='text-align: right'>Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%' style='text-align: right'>Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td style='text-align: right'>Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td style='text-align: right'>Billing Type</td> 
				<td>
                                    <select name='Billing_Type' id='Billing_Type'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<td style='text-align: right'>Claim Form Number</td>
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
                                <td style='text-align: right'>Occupation</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				
				<td style='text-align: right'>Receipt Date & Time</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Type Of Check In</td>
                                <td>  
				    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
					<option selected='selected'>Eye</option> 
				    </select>
				</td>
                                <td style='text-align: right'>Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right'>Registered Date</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				
				<td style='text-align: right'>Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td style='text-align: right'>Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' required='required'> 
					<option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td style='text-align: right'>Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right'>Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
				<td style='text-align: right'>Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Consultant</td>
                                <td>
				    <select name='Consultant' id='Consultant'>
					<option selected='selected'><?php echo $Consultant; ?></option>
				    </select>
				</td>
                                <td style='text-align: right'>Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                                <td style='text-align: right'>Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
				
				<td style='text-align: right'>Supervised By</td>
				
				<?php
				    if(isset($_SESSION['Eye_Supervisor'])) {
					if(isset($_SESSION['Eye_Supervisor']['Session_Master_Priveleges'])){
					    if($_SESSION['Eye_Supervisor']['Session_Master_Priveleges'] = 'yes'){
						$Supervisor = $_SESSION['Eye_Supervisor']['Employee_Name'];
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
			    }if(isset($_SESSION['Eye'])){
				$Sub_Department_Name = $_SESSION['Eye'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    $Transaction_Status_Title = '';
		    if(isset($_GET['Registration_ID'])){    //check if patient selected
			    //create sql
			    $Check_Status = "select Status, Transaction_Type from tbl_item_list_Cache where
						Transaction_Type = '$Transaction_Type' and
						    Payment_Cache_ID = '$Payment_Cache_ID' and
							Sub_Department_ID = (select sub_department_id from tbl_sub_department where sub_Department_Name = '$Sub_Department_Name') and
							    status = ";
							    
			$sql_Dispensed = $Check_Status."'dispensed'";
			$select_Status = mysqli_query($conn,$sql_Dispensed);
			$no = mysqli_num_rows($select_Status);
			if($no > 0){
			    $Transaction_Status_Title = 'DISPENSED';
			}else{			    
			    $sql_Active = $Check_Status."'active'";
			    //check for active medication
			    $select_Status = mysqli_query($conn,$sql_Active); 
			    $no = mysqli_num_rows($select_Status);
			    
			    if($no > 0){
				$Transaction_Status_Title = 'NOT PAID';
			    }else{
				//check for removed but no approved medication
				$sql_Removed = $Check_Status."'removed'";
				$select_Status = mysqli_query($conn,$sql_Removed); 
				$no = mysqli_num_rows($select_Status);
				
				if($no > 0){
				    //check if there is no any approved
				    $sql_Approved = $Check_Status."'approved'";
				    $select_Status = mysqli_query($conn,$sql_Approved); 
				    $no = mysqli_num_rows($select_Status);
				    
				    if($no > 0){
					//check if there is no any paid medication
					$sql_Paid = $Check_Status."'paid'";
					$select_Status = mysqli_query($conn,$sql_Paid); 
					$no = mysqli_num_rows($select_Status);
					if($no > 0){
					    $Transaction_Status_Title = 'PAID';
					}else{
					    $Transaction_Status_Title = 'APPROVED';
					}
				    }else{
					//check if there is no paid medication
					$sql_Paid = $Check_Status."'paid'";
					$select_Status = mysqli_query($conn,$sql_Paid); 
					$no = mysqli_num_rows($select_Status);
					if($no > 0){
					    $Transaction_Status_Title = 'PAID';
					}else{
					    $Transaction_Status_Title = 'ALL MEDICATION REMOVED';
					}
				    }
				}else{
				    //check for approved
				    $sql_Approved = $Check_Status."'approved'";
				    $select_Status = mysqli_query($conn,$sql_Approved); 
				    $no = mysqli_num_rows($select_Status);
				    if($no > 0){
					//check if there is no paid medication
					$sql_Paid = $Check_Status."'paid'";
					$select_Status = mysqli_query($conn,$sql_Paid); 
					$no = mysqli_num_rows($select_Status);
					if($no > 0){
					    $Transaction_Status_Title = 'PAID';
					}else{
					    $Transaction_Status_Title = 'APPROVED';
					}
				    }else{
					$Transaction_Status_Title = 'PAID';
				    }
				}
			    }
			}
		    }else{
			$Transaction_Status_Title = 'NO PATIENT SELECTED';
		    }
			echo '<b>STATUS : '.$Transaction_Status_Title.'</b>'; 
			?>
			
		   </td>
		   <td style='text-align: right;' width=40%>
		   <?php
			$Check_Status = mysqli_query($conn,"select status from tbl_item_list_cache where status = 'approved' and 
					    Payment_Cache_ID = '$Payment_Cache_ID' and
						Transaction_Type = '$Transaction_Type' and
						    Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name')");
			
			$no = mysqli_num_rows($Check_Status);
		
	    //check if system setting is centralized and/or departmental
	    //get branch id
	    if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	    }else{
		$Branch_ID = 0;
	    }
	    $check_status = "select * from tbl_system_configuration where branch_id = '$Branch_ID'";
	    $res = mysqli_query($conn,$check_status);
	    $num = mysqli_num_rows($res);
	    if($num > 0){
		while($row = mysqli_fetch_array($res)){
		    $Centralized_Collection = $row['Centralized_Collection'];
		    $Departmental_Collection = $row['Departmental_Collection'];
		}
	    }else{
		$Centralized_Collection = 'yes';
		$Departmental_Collection = 'no';
	    } 
		if(strtolower($Centralized_Collection) == 'yes' || strtolower($Departmental_Collection) == 'no'){
		    if($Transaction_Status_Title != 'DISPENSED'){
			if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'patient from outside'){
			    if($Transaction_Status_Title != 'PAID'){	
				if($no <= 0){ ?> 
<!--					<a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return approved_Message();'>
					    Send To Cashier
					</a> -->
				    <?php }else{  ?>
<!--					     <a href='Send_To_Cashier.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_Name=<?php echo $Sub_Department_Name; ?>&Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' onclick='return approved_Message2();'>
						 Send To Cashier
					     </a> -->
				    <?php }
			    }
			}	
		    }
		}
		
		    //get sub department id
			if(isset($_SESSION['Eye'])){
			    $Sub_Department_Name = $_SESSION['Eye'];
			}else{
			    $Sub_Department_Name = '';
			}
		if($Transaction_Status_Title != 'APPROVED' && $Transaction_Status_Title != 'PAID'){
		    $Sub_d = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
		    while($row = mysqli_fetch_array($Sub_d)){
			$Sub_Department_ID = $row['Sub_Department_ID'];
		    }
			if(strtolower($Departmental_Collection) == 'yes'){
			    if(strtolower($Billing_Type) != 'outpatient credit' && strtolower($Billing_Type) != 'inpatient credit'){
				echo "<a href='Departmental_Patient_Billing_Eye_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Billing_Type."' class='art-button-green'>
					Make Payment
				    </a>";
			    }
			}
		}
		?>
		<?php
		//get sub department id
		    if(isset($_SESSION['Pharmacy'])){
			$Sub_Department_Name = $_SESSION['Pharmacy'];
		    }else{
			$Sub_Department_Name = '';
		    }
		?>
<?php if($Transaction_Status_Title != 'DISPENSED'){ 
    if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'patient from outside' || strtoupper($Transaction_Status_Title) == 'PAID'){ ?>
	<?php if(strtoupper($Transaction_Status_Title) == 'PAID'){ ?>
	    <a href='eyecashpatientlist.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>' class='art-button-green'>Back To Main Page</a>
	<?php }else{ ?>
	    <button class='art-button-green' onclick='return Payment_approved_Message()'>Back To Main Page</button>
	<?php } ?>
<?php }else{ ?>
    <?php if($Transaction_Status_Title != 'ALL SERVICES REMOVED') {?>
	<a href='eyecreditpatientlist.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Billing_Type; ?>' class='art-button-green'>Back To Main Page</a>
    <?php } ?>
<?php }} ?>
		<?php
		    if($Patient_Payment_ID != '' && ($Transaction_Status_Title == 'PAID' || $Transaction_Status_Title == 'DISPENSED')){
			echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
			    Print Receipt
			</a>";
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
			<iframe src='eyeitems_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>' width='100%' height=200px></iframe>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>


<?php
    include("./includes/footer.php");
?>