<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Maternity_Works'])){
	    if($_SESSION['userinfo']['Maternity_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Maternity_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Maternity&InvalidSupervisorAuthentication=yes");
                    }
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}

//    select patient information
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
		$Claim_Number_Status = $row['Claim_Number_Status'];
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
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
	    
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days.";
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
            $Claim_Number_Status = '';
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
	    $age =0;
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Number_Status = '';
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
	    $age =0;
        }
?>
<?php
    //get cache details
    if( isset($_GET['Payment_Cache_ID'])){
	$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	
	$select_receipt_details = "SELECT * FROM  tbl_item_list_cache ic,tbl_consultation c,tbl_payment_cache pc,
				   tbl_patient_payment_item_list ppl,tbl_patient_payments pp
				   WHERE pc.Payment_Cache_ID = $Payment_Cache_ID
				   AND pc.Payment_Cache_ID = ic.Payment_Cache_ID
				   AND c.consultation_ID = pc.consultation_ID
				   AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
				   AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID
				   AND 	ic.Check_In_Type='Maternity' LIMIT 1";
	$receipt_result = mysqli_query($conn,$select_receipt_details);
	while($receipt_row = mysqli_fetch_assoc($receipt_result)){
	    $Consultant = $receipt_row['Consultant'];
	    $Patient_Direction = $receipt_row['Patient_Direction'];
	    $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'],0,10);
	    $Folio_Number = $receipt_row['Folio_Number'];
	    $Sponsor_ID = $receipt_row['Sponsor_ID'];
	    $Sponsor_Name = $receipt_row['Sponsor_Name'];
	    $Billing_Type = 'Outpatient Credit';
	    $branch_id = $_SESSION['userinfo']['Branch_ID'];
	    $Patient_Payment_ID = $receipt_row['Patient_Payment_ID'];
	    $Claim_Form_Number = $receipt_row['Claim_Form_Number'];
	}
    }
?>
<?php
    //on form submit add bill information to payment table for credit patient
    if(isset($_POST['frmsubmit'])){
	if(isset($_GET['Payment_Cache_ID']) && isset($_GET['Payment_Item_Cache_List_ID'])){
	    //insert into payment table
	    $Supervisor_ID = $_SESSION['Maternity_Supervisor']['Employee_ID'];
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	    
	    $inser_qr1 = "INSERT INTO tbl_patient_payments(Registration_ID, Supervisor_ID,Employee_ID,
							    Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
							    Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,Transaction_status,
							    Transaction_type, branch_id)
						    VALUES ($Registration_ID,$Supervisor_ID,
							    $Employee_ID,NOW(),$Folio_Number,'$Claim_Form_Number',$Sponsor_ID,
							    '$Sponsor_Name','$Billing_Type',
							    NOW(),'active','indirect cash',$branch_id)";
	    if(mysqli_query($conn,$inser_qr1)){
		$select_payment_id = "(SELECT Patient_Payment_ID,Payment_Date_And_Time FROM tbl_patient_payments
					WHERE Registration_ID =$Registration_ID AND Employee_ID=$Employee_ID ORDER BY Payment_Date_And_Time DESC LIMIT 1)";
		$payment_results = mysqli_query($conn,$select_payment_id);
		$Patient_Payment_ID = mysqli_fetch_assoc($payment_results)['Patient_Payment_ID'];
		$Payment_Date_And_Time = mysqli_fetch_assoc($payment_results)['Payment_Date_And_Time'];
		
		//LOOP TO INSERT DETAILS FROM CACHE TABLE
		$select_from_cache = "SELECT * FROM tbl_item_list_cache il, tbl_items i
					WHERE il.Payment_Item_Cache_List_ID = ".$_GET['Payment_Item_Cache_List_ID']."
					AND i.Item_ID = il.Item_ID
					AND il.Check_In_Type='Maternity'";
		    $cache_result_list = mysqli_query($conn,$select_from_cache);
		    while($cache_row = mysqli_fetch_assoc($cache_result_list)){
			//insert items
			$Check_In_Type = $cache_row['Check_In_Type'];
			$Category = $cache_row['Category'];
			$Item_ID = $cache_row['Item_ID'];
			$Discount = $cache_row['Discount'];
			$Price = $cache_row['Price'];
			$Quantity = $cache_row['Quantity'];
			$Patient_Direction = $cache_row['Patient_Direction'];
			$Consultant = $cache_row['Consultant'];
			$Consultant_ID = $cache_row['Consultant_ID'];
			$Status = 'active';
			$Process_Status = 'served';
			$Sub_Department_ID = $cache_row['Sub_Department_ID'];
			
			$inser_qr2 = "INSERT INTO tbl_patient_payment_item_list(Check_In_Type, Category, Item_ID,
			Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status, Patient_Payment_ID,
			Transaction_Date_And_Time, Process_Status, Sub_Department_ID)
			VALUES ('$Check_In_Type','$Category',$Item_ID,
			'$Discount', '$Price','$Quantity', '$Patient_Direction','$Consultant', $Consultant_ID,
			'$Status', $Patient_Payment_ID,(SELECT NOW()),
			'$Process_Status',$Sub_Department_ID)";
			
			if(mysqli_query($conn,$inser_qr2)){
			    mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID=$Patient_Payment_ID,Payment_Date_And_Time='$Payment_Date_And_Time',Employee_ID=".$_SESSION['userinfo']['Employee_ID'].",Status='served' WHERE Payment_Cache_ID = ".$Payment_Cache_ID);
			}
		    }
		    
		    ?>
		    <script type='text/javascript'>
			alert('Bill Created Successfully !');
			document.location = "maternityclinicalnotes.php?Eadiologyclinicalnotes=MaternityPageclinicalnotes&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&Registration_ID=<?php echo $Registration_ID;?>";
		    </script>
		    <?php
	    }   
	}
    }
?>
<?php
//select payment details
    if( isset($_GET['Patient_Payment_ID']) ){
	$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
	    $select_payment_id = "(SELECT * FROM tbl_patient_payment_item_list
					WHERE Patient_Payment_ID=$Patient_Payment_ID)";
	    $payment_results = mysqli_query($conn,$select_payment_id);
	    $Patient_Payment_Item_List_ID = mysqli_fetch_assoc($payment_results)['Patient_Payment_Item_List_ID'];
	}
	
	$select_receipt_details = "SELECT * FROM tbl_patient_payment_item_list
				   WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID
				   AND 	Check_In_Type='Maternity'";
	$receipt_result = mysqli_query($conn,$select_receipt_details);
	while($receipt_row = mysqli_fetch_assoc($receipt_result)){
	    $Consultant = $receipt_row['Consultant'];
	    $Patient_Direction = $receipt_row['Patient_Direction'];
	    $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'],0,10);
	}
    }
?>
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Outpatient cash'){
	    document.location = "./maternitycashpatientlist.php";
	}else if (patientlist=='Outpatient credit') {
	    document.location = "maternitycreditpatientlist.php?MaternityCreditPatientlist=MaternityCreditPatientlistThispage";
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

<center>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"> 
<fieldset><legend align='right'><b>Maternity Works</b></legend>
	<table width=100%>
		<tr>
		    <td><b>Patient Name</b></td><td><input type="text" name="" readonly='readonly' value='<?php if(isset($Patient_Name)){ echo $Patient_Name; } ?>' id=""></td>
		    <td><b>Visit Date</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($Transaction_Date_And_Time)){ echo $Transaction_Date_And_Time; } ?>'></td>
		</tr>
		<tr>
		    <td><b>Patient Number</b></td><td><input type="text" name="" readonly='readonly' value='<?php if(isset($Registration_ID)){ echo $Registration_ID; } ?>' id="" ></td>
		    <td><b>Gender</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($Gender)){ echo $Gender; } ?>' ></td></td>
		</tr>
		<tr>
		    <td><b>Sponsor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($Guarantor_Name)){ echo $Guarantor_Name; } ?>' ></td>
		    <td><b>Age</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($age)){ echo $age; } ?>' ></td></td>
		</tr>
		<tr>
		    <td><b>Doctor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
									if(isset($Consultant)){
									    if($Patient_Direction=='Direct To Clinic'){
										echo $_SESSION['userinfo']['Employee_Name'];
									    }else{
										echo $Consultant;
									    }
									    } ?>' ></td>
		</tr>
	</table>
</fieldset>
<br><br>
<fieldset style=' height:220px; overflow:scroll;' >
	<table width='100%'>
		<tr>
			<td width='4%'><b>Sn</b></td>
			<td><b>Test Name</></td>
			<td width='8%'><b>Status</b></td>
			<td colspan=3><b>Results</b></td>
		</tr>
	<?php
	    if( isset($_GET['Patient_Payment_ID']) ){
		$select_items = "SELECT * FROM tbl_patient_payment_item_list pl, tbl_items i
					WHERE pl.Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID
					AND i.Item_ID = pl.Item_ID
					AND pl.Check_In_Type='Maternity'";
		 $receipt_items = mysqli_query($conn,$select_items);
		 $i= 1;
		 while($item_rows = mysqli_fetch_assoc($receipt_items)){
		    ?>
		    <tr>
			    <td><input type='text' value='<?php echo $i ?>' readonly='readonly' ></td>
			    <td><input type='text' value='<?php echo $item_rows['Product_Name'] ?>' readonly='readonly'></td>
			    <td>
				<select name='status' id='status' required='required'>
				    <option selected='selected'></option>
				    <option>Not Applicable</option>
				    <option>Pending</option>
				    <option>Done</option>
				</select>
			    </td>
			    <td width='10%'><button class='art-button-green'>Results</button></td>
		    </tr>
		    <?php
		    $i++;
		 }
	    }elseif(isset($_GET['Payment_Cache_ID']) && isset($_GET['Payment_Item_Cache_List_ID'])){
		$select_items = "SELECT * FROM tbl_item_list_cache il, tbl_items i
					WHERE il.Payment_Item_Cache_List_ID = ".$_GET['Payment_Item_Cache_List_ID']."
					AND i.Item_ID = il.Item_ID
					AND il.Check_In_Type='Maternity'";
		 $receipt_items = mysqli_query($conn,$select_items);
		 $i= 1;
		 while($item_rows = mysqli_fetch_assoc($receipt_items)){
		    ?>
		    <tr>
			    <td><input type='text' value='<?php echo $i ?>' readonly='readonly' ></td>
			    <td><input type='text' value='<?php echo $item_rows['Product_Name'] ?>' readonly='readonly'></td>
			    <td>
				<select name='status' id='status' required='required'>
				    <option selected='selected'></option>
				    <option>Not Applicable</option>
				    <option>Pending</option>
				    <option>Done</option>
				</select>
			    </td>
			    <td width='10%'><button class='art-button-green'>Results</button></td>
		    </tr>
		    <?php
		    $i++;
		 }
	    }
	?>
	</table>

</fieldset>
<div style="width:100%;text-align:center;padding-top:10px;">
<input type='hidden' id='frmsubmit' name='frmsubmit'>
<input type='submit' value="SAVE" class="art-button-green" />
<button class='art-button-green'>Attachments</button>

<?php
    if(isset($_GET['Patient_Payment_ID'])){
	?>
	<a href='./individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' style='text-decoration: none' target='_blank'>Print Receipt</a>
	<?php
    }
?>
</div>
</form>
</center>




<?php
    include("./includes/footer.php");
?>